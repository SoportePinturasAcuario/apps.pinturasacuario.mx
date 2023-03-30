<?php

namespace Apps\Http\Controllers\Bda;

use Illuminate\Http\Request;
use Apps\Http\Controllers\Controller;

use Illuminate\Support\Facades\Storage;

// Requests
use Apps\Models\Bda\File;
use Apps\Http\Requests\Bda\FileRequest;

class FileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(FileRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \Apps\Models\Bda\file  $file
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $file_id)
    {
        $file = File::findOrFail($file_id);

        return Storage::disk('local')->response("storage/bda/$file->path");
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Apps\Models\Bda\file  $file
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, file $file)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Apps\Models\Bda\file  $file
     * @return \Illuminate\Http\Response
     */
    public function destroy($file_id)
    {
        //
    }
}
