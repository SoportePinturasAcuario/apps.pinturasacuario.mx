<?php

namespace Apps\Http\Controllers\checklist;

use Apps\Checklist;
use Apps\Traits\File;
use Illuminate\Http\Request;
use Apps\Http\Controllers\Controller;
use Apps\CheckListFile;
use Illuminate\Support\Facades\Storage;
use Apps\Customer;

class ChecklistController extends Controller
{
    // use file;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $checklists = Checklist::with(['ruta', 'fletera', 'cargador', 'inspector', 'transportista', 'facturas'])->get();

        $checklists = $checklists->map(function($checklist){
            $checklist->clientes = empty($checklist->clientes) ? [] : explode(',', ($checklist->clientes));
            return $checklist;
        });
        
        return response()->json([
            "success" => true,
            "data" => $checklists,
        ], 200, [], JSON_NUMERIC_CHECK); 
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $checklist = Checklist::create($request->all());

        return response()->json([
            "success" => true,
            "data" => $checklist,
        ], 200, [], JSON_NUMERIC_CHECK); 
    }

    /**
     * Display the specified resource.
     *
     * @param  \Apps\Doc  $doc
     * @return \Illuminate\Http\Response
     */
    public function show($checklist_id)
    {
        $checklist = Checklist::with('photos')->find($checklist_id);

        $checklist->clientes = empty($checklist->clientes) ? [] : explode(',', ($checklist->clientes));

        return response()->json([
            "success" => true,
            "data" => $checklist,
        ], 200, [], JSON_NUMERIC_CHECK);         
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Apps\Doc  $doc
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $checklist = Checklist::find($id);

        $data = collect($request->all());

        $data->put('clientes', empty($data->get('clientes')) ? '' : implode(',', $data->get('clientes')));

        $checklist->update($data->toArray());

        $checklist->clientes = empty($checklist->clientes) ? [] : explode(',', ($checklist->clientes));
        
        return response()->json([
            "success" => true,
            "data" => $checklist,
        ], 200); 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Apps\Doc  $doc
     * @return \Illuminate\Http\Response
     */
    public function destroy($checklist_id)
    {
        $checklist = Checklist::find($checklist_id);

        $checklist->delete();

        return response()->json([
            "success" => true,
            "data" => $checklist,
        ], 200, [], JSON_NUMERIC_CHECK); 
    }

    public function finalize(Checklist $checklist)
    {
        $checklist->update([
            'status_id' => 2
        ]);

        return response()->json([
            "success" => true,
            "data" => $checklist,
        ], 200, [], JSON_NUMERIC_CHECK); 
    }    

    // Files
    public function fileStore(Request $request, Checklist $checklist)
    {
        // Recibimos el archivo
        $file = $request->file('file');

        // Salvamos extension
        $extension = strtolower($file->extension());

        // $fileName = $request->file('file')->store("/", "gdrive_checklist");

        // $fileUrl = Storage::disk('gdrive_checklist')->url($fileName);

        $url = Storage::disk('gdrive_checklist')->url($request->file('file')->store("/", "gdrive_checklist"));

        parse_str(parse_url($url, PHP_URL_QUERY), $query);

        $gdriveid = $query['id'];

        $photo = $checklist->photos()->create(array_merge($request->all(), [
            'url' => $url,
            'gdriveid' => $gdriveid,
        ]));

        return response()->json([
            "success" => true,
            "data" => $photo,
        ], 200); 
    }

    // File Delete
    public function fileDelete($id)
    {
        $file = CheckListFile::find($id);

        $gdriveid = $file->gdriveid;

        $file->delete();

        Storage::disk('gdrive_checklist')->delete('1r4dIRD7_VsBzQYo3HsR35AB4i7GXwEnE/' . $gdriveid);

        return response()->json([
            "success" => true,
            "data" => $file,
        ], 200); 
    } 

    public function format($checklist_id){

        $checklist = Checklist::with('photos', 'facturas')->find($checklist_id);

        $customers = Customer::whereIn('id', explode(',', $checklist->clientes))->get();

        return view('Checklist/reports/1', compact(['checklist', 'customers']));
    }
}
