<?php
namespace Apps\Http\Controllers\Iso;

use Illuminate\Http\Request;
use Apps\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Apps\Http\Requests\Iso\FileRequest;

class FileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(FileRequest $request){

        $data = collect([]);

        // $fileNamesInvalids = [];

        // foreach ($request->file('files') as $key => $file) {            
        //     if (!preg_match('/^[a-z,A-Z,0-9,\., ]*$/', $file->getClientOriginalName())) {
        //         $fileNamesInvalids[$file->getClientOriginalName()] = ['existen caracteres invalidos en este nombre de archivo.'];
        //     }
        // }


        // if (count($fileNamesInvalids) > 0) {
        //     return response()->json([
        //         'errors' => $fileNamesInvalids,
        //     ], 422);
        // }

        foreach ($request->file('files') as $key => $file) {            
            
            $name = $file->getClientOriginalName();

            $extension = $file->extension();

            $path = $request->get('path') . '/' . $name;

            Storage::disk('iso')->put(
                $path,
                \File::get($file)
            );

            $data->push([
                'url' => $path,
                'path' => $path,
                'type' => 'file',
                'name' => $name,
                'extension' => $extension,
                'pathParent' => $request->get('path'),
            ]);            
        }

        return response()->json([
            'data' => $data,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request){
        return Storage::disk('iso')->response($request->get('path'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request){

        Storage::disk('iso')->delete($request->get('path'));
        
        return response()->json([
            'data' => $request->all(),
        ]);        
    }
}