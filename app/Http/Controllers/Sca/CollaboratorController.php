<?php

namespace Apps\Http\Controllers\Sca;

use Illuminate\Http\Request;
use Apps\Http\Controllers\Controller;

// Models
use Apps\Models\Sca\Descriptor;
use Apps\Models\Sca\Collaborator;

// Requests
use Apps\Http\Requests\Sca\CollaboratorRequest;

class CollaboratorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index_with_trashed()
    {
        $collaborators = Collaborator::select([
            'id', 'folio', 'name', 'departamento_id', 'sucursal_id', 'deleted_at',
        ])->with([
            'groups:group_id AS id,collaborator_id,name',
            'department:id,nombre',
            'branch_office:id,name',
            'descriptors:collaborator_id,id',
        ])
        ->withTrashed()
        ->get();

        return response()->json([
            'data' => $collaborators,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Apps\Collaborator  $collaborator
     * @return \Illuminate\Http\Response
     */
    public function show($collaborator_id)
    {
        $collaborator = Collaborator::with([
            'department',
            'branch_office',
            'groups', 
            'descriptors' => function($q) {
                $q->orderBy('id', 'DESC');
            },
            'turns' => function($q) {
                $q->orderBy('id', 'DESC');
            }
        ])
        ->withTrashed()
        ->find($collaborator_id);

        return response()->json([
            'data' => $collaborator,
        ]);
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

    // Group
    public function group_sync(Request $request, $collaborator_id) {
        $this->validate($request, [
            'groups' => 'array',
            'groups.*' => 'nullable|integer',
        ], [], ['groups' => '"grupos"']);

        $collaborator = Collaborator::findOrFail($collaborator_id);

        $collaborator->groups()->sync($request->get('groups'));

        return response()->json([
            'data' => $collaborator->groups
        ]);
    }

    // Turn
    public function turn_delete($collaborator_id, $turn_id) {
        $collaborator = Collaborator::findOrFail($collaborator_id);

        $collaborator->turns()->detach($turn_id);

        return response()->json([
            'data' => [],
        ]);
    }
}
