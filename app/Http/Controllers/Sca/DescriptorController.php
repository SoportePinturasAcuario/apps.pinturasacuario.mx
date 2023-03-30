<?php

namespace Apps\Http\Controllers\Sca;

use Illuminate\Http\Request;
use Apps\Http\Controllers\Controller;

// Models
use Apps\Models\Sca\Descriptor;
use Apps\Models\Sca\Collaborator;

// Requests
use Apps\Http\Requests\Sca\DescriptorRequest;

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
    public function store(DescriptorRequest $request)
    {
        $descriptor = Descriptor::create($request->all());

        return response()->json([
            'success' => true
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Apps\Models\Sca\Descriptor  $descriptor
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
     * @param  \Apps\Models\Sca\Descriptor  $descriptor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Descriptor $descriptor)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Apps\Models\Sca\Descriptor  $descriptor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Descriptor $descriptor)
    {
        $descriptor->delete();

        return response()->json([
            'success' => true,
        ]);        
    }

    public function massive(Request $request) {
        $this->validate($request, [
            'overwrite' => 'required|boolean',
            'reference' => 'required|in:folio,id',
            'descriptors' => 'array|min:1|max:500',
            'descriptors.*.reference_value' => 'required',
            'descriptors.*.descriptor' => 'required|array',
        ]);

        foreach ($request->get('descriptors') as $key => $item) {

            $collaborator = Collaborator::where($request->get('reference'), $item['reference_value'])->get()->first();

            if ($collaborator) {
                if ($request->get('overwrite')) {
                    $collaborator->descriptors()->delete();
                }

                $collaborator->descriptors()->create(['descriptor' => $item['descriptor']]);
            }
        }

        return response()->json([
            'success' => true,
        ]);
    }     


    public function query(Request $request) {

        $this->validate($request, [
            'collaborator_id' => 'nullable|integer',
        ]);

        $query = $request->only(['collaborator_id']);

        $results = Descriptor::where($query)
        ->select(['id', 'created_at'])
        ->get();

        return response()->json([
            'data' => $results,
        ]);
    } 
}
