<?php

namespace Apps\Http\Controllers;

use Apps\Traits\File;
use Apps\Waste;
use Apps\User;
use Apps\Approval;
use Apps\Collaborator;
use Apps\Notifications\WasteApprovalRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class WasteController extends Controller
{
    use file;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $wastes = Waste::with("photos")->get();

        $wastes = $wastes->map(function($waste){
            $waste->wasReviewed = $waste->wasReviewed();

            $waste->isApproved = $waste->isApproved();
            
            $waste->approvals;

            $waste->items = (empty($waste->items))?array():json_decode($waste->items);


            if (collect($waste->para)->isNotEmpty()) {
                $waste->para = Collaborator::whereIn('id', json_decode($waste->para))->get();

                $waste->de = Collaborator::find($waste->de);
            }

            return $waste;
        });
        
        return response()->json([
            'success' => true,
            'data' => $wastes,
        ], 200, [], JSON_NUMERIC_CHECK);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();

        $waste = Waste::create($request->all());
        
        return response()->json([
            'success' => true,
            'data' => $waste,
        ], 200, [], JSON_NUMERIC_CHECK);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Apps\Waste  $waste
     * @return \Illuminate\Http\Response
     */
    public function show($waste_id)
    {

        $waste = Waste::with('photos')->find($waste_id);

        $waste->approved = $waste->isApproved();

        $waste->wasReviewed = $waste->wasReviewed();

        $waste->photos = $waste->photos->map(function($photo){
            $photo->src = asset($photo->path);

            return $photo;
        });

        $waste->approvals = $waste->approvals->map(function($approval){
            $approval->authorizingUser->collaborator;

            return $approval;
        });       

        $waste->items = (empty($waste->items))?array():json_decode($waste->items);

        $waste->para = (empty($waste->para))?array():json_decode($waste->para);

        return response()->json([
            'success' => true,
            'data' => $waste,
        ], 200, [], JSON_NUMERIC_CHECK);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Apps\Waste  $waste
     * @return \Illuminate\Http\Response
     */
    public function edit(Waste $waste)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Apps\Waste  $waste
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Waste $waste) {

        $data = $request->all();

        $data["items"] = (count($request->get("items")) == 0)?null:json_encode($request->get("items"));

        $data["para"] = (count($request->get("para")) == 0)?null:json_encode($request->get("para"));

        $waste->update($data);

        $waste->photos = $waste->photos->map(function($photo){
            $photo->src = asset($photo->path);

            return $photo;
        }); 

        $waste->photos;

        $waste->approved = $waste->isApproved();

        $waste->wasReviewed = $waste->wasReviewed();

        $waste->approvals = $waste->approvals->map(function($approval){
            $approval->authorizingUser->collaborator;

            return $approval;
        });

        $waste->items = (empty($waste->items))?array():json_decode($waste->items);

        $waste->para = (empty($waste->para))?array():json_decode($waste->para);

        return response()->json([
            "success" => true,
            "data" => $waste,
        ], 200, [], JSON_NUMERIC_CHECK);          
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Apps\Waste  $waste
     * @return \Illuminate\Http\Response
     */
    public function destroy(Waste $waste)
    {
        $waste->photos()->each(function($photo) {
            $path = $photo->path;

            $photo->delete();

            $this->storageDeleteFile($path);
        });

        $waste->delete();

        return response()->json([
            "success" => true,
            "data" => $waste,
        ], 200, [], JSON_NUMERIC_CHECK);  
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Plist  $plist
     * @return \Illuminate\Http\Response
     */
    public function finalize(Waste $waste)
    {
        $waste->update([
            "status_id" => 2,
        ]);
        
        foreach (json_decode($waste->para) as $key => $collaborator_id) {

            $collaborator = Collaborator::with('user')->find($collaborator_id);

            $user = $collaborator->user;

            if (!empty($user)) {

                // Asign aprovacion
                $waste->approvals()->create([
                    'approving_user_id' => $user->id,
                ]);

                $waste->from;

                // Create Notificación
                $user->notify(new WasteApprovalRequest($waste));
            }
        }

        $waste->photos = $waste->photos->map(function($photo){
            $photo->src = asset($photo->path);

            return $photo;
        });
        
        $waste->approved = $waste->isApproved();

        $waste->wasReviewed = $waste->wasReviewed();   

        $waste->approvals = $waste->approvals->map(function($approval){
            $approval->authorizingUser->collaborator;

            return $approval;
        });       

        $waste->items = (empty($waste->items))?array():json_decode($waste->items);

        $waste->para = (empty($waste->para))?array():json_decode($waste->para);

        return response()->json([
            "success" => true,
            "data" => $waste,
        ], 200); 
    }    


    // Reporte
    public function report($id){

        $waste = Waste::with("photos")->find($id);

        $waste->items = (empty($waste->items))?array():json_decode($waste->items);

        $waste->para = (empty($waste->para))?array():json_decode($waste->para);

        $waste->para = Collaborator::whereIn("id", $waste->para)->get();

        $waste->de = Collaborator::find($waste->de);

        return view("Waste.Reports.report1", compact(["waste"]));
    }  


    // Files
    public function file(Request $request, Waste $waste)
    {
        $tempPath = $this->makeTempFile($request->file('file'), $request->get('path'));

        $photo = $waste->photos()->create(array_merge($request->except("path"), ["path" => $tempPath]));

        $photo->src = $this->storageTempFile($tempPath);

        return response()->json([
            "success" => true,
            "data" => $photo,
        ], 200); 
    }

    // File Delete
    public function fileDelete(Waste $waste, $file_id)
    {
        $photo = $waste->photos->firstWhere('id', $file_id);

        $path = $photo->path;

        $photo->delete();

        $this->storageDeleteFile($path);

        return response()->json([
            "success" => true,
            "data" => $photo,
        ], 200); 
    }

    // Approve
    public function approve(Request $request, Waste $waste)
    {
        dd($request->all());

        return response()->json([
            "success" => true,
            "data" => $photo,
        ], 200); 
    }   
}
