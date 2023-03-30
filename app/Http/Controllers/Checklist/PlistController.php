<?php

namespace Apps\Http\Controllers\Plist;

use Apps\Traits\File;
use Apps\Plist;
use Illuminate\Http\Request;
use Apps\Http\Controllers\Controller;

class PlistController extends Controller
{
    use file;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Plist::with("customer")->get();

        $data = $data->map(function($item){
            $item->bills = json_decode($item->bills);
            return $item;
        });

        return response()->json([
            "success" => true,
            "data" => $data,
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
        $plist = Plist::create();
        $plist->status_id = 1;

        $plist->bills = [];

        $plist->photos = $plist->photos;


        return response()->json([
            "success" => true,
            "data" => $plist,
        ], 200, [], JSON_NUMERIC_CHECK);   
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Plist  $plist
     * @return \Illuminate\Http\Response
     */
    public function show(Plist $plist)
    {
        if (empty($plist->bills)) {
            $plist->bills = [];
        }else{
            $plist->bills = json_decode($plist->bills);
        }

        $plist->status_id = intval($plist->status_id);

        $plist->photos = $plist->photos;

        $plist->photos->map(function($photo){
            $photo["src"] = asset($photo["path"]);

            return $photo;
        });          

        return response()->json([
            "success" => true,
            "data" => $plist,
        ], 200, [], JSON_NUMERIC_CHECK);   
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Plist  $plist
     * @return \Illuminate\Http\Response
     */
    public function edit(Plist $plist)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Plist  $plist
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Plist $plist)
    {

        $plist->update($request->except("bills"));
        
        $plist->photos = $plist->photos;

        $plist->bills = json_decode($plist->bills);

        return response()->json([
            "success" => true,
            "data" => $plist,
        ], 200, [], JSON_NUMERIC_CHECK);   
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Plist  $plist
     * @return \Illuminate\Http\Response
     */
    public function delete(Plist $plist)
    {
        $plist->delete();

        return response()->json([
            "success" => true,
            "data" => $plist,
        ], 200, [], JSON_NUMERIC_CHECK);           
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Plist  $plist
     * @return \Illuminate\Http\Response
     */
    public function destroy(Plist $plist)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Plist  $plist
     * @return \Illuminate\Http\Response
     */
    public function finalize(Plist $plist)
    {
        $plist->update([
            "status_id" => 2,
        ]);

        return response()->json([
            "success" => true,
            "data" => $plist,
        ], 200); 
    }    


    // Bill
    public function bill_store(Plist $plist, Request $request){
        $plists = Plist::all();

        $bills = $plists->map(function($item){
            return collect(json_decode($item->bills))->map(function($bill){
                return $bill->folio;
            });
        });

        if (collect($bills)->collapse()->contains($request->get('folio'))) {
            return response()->json([
                "errors" => [
                    'folio' => ['Esta factura ya fue utilizada anteriormente.']
                ],
            ], 402);  
        }

        $bills = $plist->bills;

        $bills = json_decode($bills);

        if ($bills === null) {
            $bills = [];
        }

        array_push($bills, $request->all());

        $bills = json_encode($bills);

        $plist->update([
            "bills" => $bills,
        ]);

        return response()->json([
            "success" => true,
            "data" => $request->all(),
        ], 200);        
    }

    public function bill_update(Plist $plist, Request $request){
        $plist->update([
            "bills" => json_encode($request->bills),
        ]);

        return response()->json([
            "success" => true,
            "data" => json_decode($plist->bills),
        ], 200);        
    }  

    public function export(Request $request){

        $csv = fopen(public_path("storage/packlist/export.csv"), 'w');

        $headers = collect($request->get("headers"));
        
        fputcsv($csv, $headers->toArray());

        foreach ($request->get("data") as $key => $row) {

            $row = array_map(function($item){
                return ($item === null)?"":strtoupper(utf8_encode($item));
            }, $row);

            fputcsv($csv, $row);
        }

        fclose($csv);

        return response()->json([
            "success" => true,
            "data" => [
                "fileurl" => asset("storage/packlist/export.csv")
            ],
        ], 200);        
    }

    public function report($plist_id){
        $plist = Plist::with("photos")->find($plist_id);

        return view("Plist/reports/1", compact(["plist"]));
    }


    // Files
    public function file(Request $request, Plist $plist)
    {
        $tempPath = $this->makeTempFile($request->file('file'), $request->get('path'));

        $photo = $plist->photos()->create(array_merge($request->except("path"), ["path" => $tempPath]));

        $photo->src = $this->storageTempFile($tempPath);

        return response()->json([
            "success" => true,
            "data" => $photo,
        ], 200); 
    }

    // File Delete
    public function fileDelete(Plist $plist, $file_id)
    {
        $photo = $plist->photos->firstWhere('id', $file_id);

        $path = $photo->path;

        $photo->delete();

        $this->storageDeleteFile($path);

        return response()->json([
            "success" => true,
            "data" => $photo,
        ], 200); 
    }     
}
