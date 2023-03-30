<?php

namespace Apps\Http\Controllers\Checker;

use Illuminate\Http\Request;
use Apps\Http\Controllers\Controller;

// Models
use Apps\Collaborator;
use Apps\Models\Checker\Descriptor;

class CollaboratorController extends Controller
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \Apps\Collaborator  $collaborator
     * @return \Illuminate\Http\Response
     */
    public function show(Collaborator $collaborator)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Apps\Collaborator  $collaborator
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Collaborator $collaborator)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Apps\Collaborator  $collaborator
     * @return \Illuminate\Http\Response
     */
    public function destroy(Collaborator $collaborator)
    {
        //
    }


    // Descriptors
    public function descriptor_index()
    {
        $collaborator_ids = Descriptor::select('collaborator_id AS id')
        ->get()
        ->map(function($item) {
            return $item->id;
        });

        $collaborators = Collaborator::with('descriptors')
        ->whereIn('id', $collaborator_ids)
        ->get();

        $data = $collaborators->map(function($collaborator) {

            $collaborator = $collaborator->only(['id', 'name', 'descriptors']);

            $collaborator['descriptors'] = array_map(function($descriptor) {
                return collect(json_decode($descriptor['descriptor']))->toArray();
            }, $collaborator['descriptors']->toArray());

            return $collaborator;
        });

        return response()->json([
            'data' => $data
        ]);
    }    
}
