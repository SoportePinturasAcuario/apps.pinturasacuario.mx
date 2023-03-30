<?php

namespace Apps\Http\Controllers\Bda;

use Illuminate\Http\Request;
use Apps\Http\Controllers\Controller;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

// Models
use Apps\Models\Bda\Resource as ResourceModel;

// Requests
use Apps\Http\Requests\Bda\ResourceRequest;

class ResourceController extends Controller
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
    public function store(ResourceRequest $request)
    {

    }

    /**
     * Display the specified resource.
     *
     * @param  \Apps\Models\Bda\resource  $resource
     * @return \Illuminate\Http\Response
     */
    public function show(resource $resource)
    {
        //
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Apps\Models\Bda\resource  $resource
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $resource_id)
    {
        $resource = ResourceModel::findOrFail($resource_id);

        $resource->update($request->only(['name', 'description', 'module_id']));

        return response()->json([
            'data' => $resource,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Apps\Models\Bda\resource  $resource
     * @return \Illuminate\Http\Response
     */
    public function destroy(resource $resource)
    {
        //
    }

    public function getTypeFile($extension)
    {
        $types = [
            1 => ['mp4'],
            2 => ['mp3'],
            3 => ['gif', 'jpg', 'jpeg', 'png'],
            4 => ['pdf', 'doc', 'docx', 'docm', 'dotx', 'dotm', 'pptx', 'pptm', 'ppt', 'csv', 'xls', 'xlsx', 'xlsm', 'xlsb', 'xltx'],
        ];

        foreach ($types as $type_id => $mimes) {
            if (in_array($extension, $mimes)) {
                return $type_id;
            }
        }
    }   
}
