<?php

namespace Apps\Http\Controllers\Checker;

use Illuminate\Http\Request;
use Apps\Http\Controllers\Controller;

// Models
use Apps\Collaborator;
use Apps\Models\Checker\Descriptor;

// Requests
use Apps\Http\Requests\Checker\DescriptorRequest;

class DescriptorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Descriptor::with('collaborator')->get();

        $data = array_map(function($item) {
            
            $item['descriptor'] = collect(json_decode($item['descriptor']))->toArray();

            return $item;
        }, $data->toArray());

        return response()->json([
            'data' => $data
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \Apps\Models\Checker\Descriptor  $descriptor
     * @return \Illuminate\Http\Response
     */
    public function show(Descriptor $descriptor)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Apps\Models\Checker\Descriptor  $descriptor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Descriptor $descriptor)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Apps\Models\Checker\Descriptor  $descriptor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Descriptor $descriptor)
    {
        //
    }

    public function insert(DescriptorRequest $request) {

        $collaborator = Collaborator::with('descriptors')->find($request->get('collaborator_id'));

        if ($collaborator) {
            $collaborator->descriptors()->create($request->all());

            // if ($collaborator->descriptors) {
                // $collaborator->descriptors()->updtea($request->get('descriptors'));
            // }else{
                // $collaborator->descriptors()->create($request->get('descriptors'));
            // }
        }

        return response()->json([
            'success' => true,
        ]);
    }      
}
