<?php

namespace Apps\Http\Controllers;

use Apps\File;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;

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

        $file = $request->file('file');

        $originalName = $file->getClientOriginalName();

        $extension = $file->extension();
        
        $name = time() . "." . $extension;

        $path = $request->get("path") . $name;

        //indicamos que queremos guardar un nuevo archivo en el disco local
        Storage::disk('local')->put($path . ".temp",  \File::get($file));

        $photo = File::create([
            "plist_id" => $request->plist_id,
            "name" => $originalName,
            "path" => $path,
            "type" => $request->type,
        ]);

        $photo->src = asset($photo->path);

        Storage::disk('local')->move($path.".temp", $path);

        return response()->json([
            "success" => true,
            "data" => $photo,
        ], 200, [], JSON_NUMERIC_CHECK);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Apps\File  $file
     * @return \Illuminate\Http\Response
     */
    public function show(File $file)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Apps\File  $file
     * @return \Illuminate\Http\Response
     */
    public function edit(File $file)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Apps\File  $file
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, File $file)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Apps\File  $file
     * @return \Illuminate\Http\Response
     */
    public function destroy(File $file)
    {
        Storage::delete($file->path);

        $file->delete();

        return response()->json([
            "success" => true,
        ], 200, [], JSON_NUMERIC_CHECK);
    }
}
