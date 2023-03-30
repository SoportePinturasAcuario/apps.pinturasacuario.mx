<?php

namespace Apps\Http\Controllers\Sca;

use Illuminate\Http\Request;
use Apps\Http\Controllers\Controller;

// Request
use Apps\Http\Requests\Sca\GroupRequest;

// Models
use Apps\Collaborator;
use Apps\Models\Sca\Group;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $groups = Group::select(['id', 'name'])->with('checkers:name,group_id')->get();

        $groups = $groups->map(function($group) {
            $collaborator_ids = \DB::connection('sca')->table('collaborator_group')
            ->where('group_id', $group->id)
            ->select('collaborator_id')
            ->get();

            // $group->collaborators = Collaborator::whereIn('id', array_column($collaborator_ids->toArray(), 'collaborator_id'))->get();

            $group->collaborators_count = $collaborator_ids->count();

            return $group;
        });

        return response()->json([
            'data' => $groups,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(GroupRequest $request)
    {
        $group = Group::create($request->all());

        // $group->collaborators()->attach($request->get('collaborators'));

        \DB::connection('sca')->table('collaborator_group')->insert(array_map(function($collaborator_id) use ($group) {
            return [
                'group_id' => $group->id,
                'collaborator_id' => $collaborator_id,
            ];
        }, $request->get('collaborators')));


        return response()->json([
            'data' => $group,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Apps\Models\Sca\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function show($group_id)
    {
        $group = Group::select('id', 'name')->with('checkers')->findOrFail($group_id);

        $collaborator_ids = \DB::connection('sca')->table('collaborator_group')
        ->where('group_id', $group->id)
        ->select('collaborator_id')
        ->get();

        $group->collaborators = Collaborator::select(['id', 'folio', 'name', 'departamento_id', 'sucursal_id'])
        ->whereIn('id', array_column($collaborator_ids->toArray(), 'collaborator_id'))
        ->get();

        return response()->json([
            'data' => $group,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Apps\Models\Sca\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function update(GroupRequest $request, $group_id)
    {        
        $group = Group::findOrFail($group_id);

        $group->update($request->all());

        \DB::connection('sca')
        ->table('collaborator_group')
        ->where('group_id', $group_id)
        ->delete();

        \DB::connection('sca')->table('collaborator_group')->insert(array_map(function($collaborator_id) use ($group) {
            return [
                'group_id' => $group->id,
                'collaborator_id' => $collaborator_id,
            ];
        }, $request->get('collaborators')));

        return response()->json([
            'data' => $group,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Apps\Models\Sca\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function destroy(Group $group)
    {
        //
    }

    // Checker
    public function checker_sync(Request $request, $id) {
        $this->validate($request, [
            'checkers' => 'array',
            'checkers.*' => 'integer',
        ], [], ['checkers' => '"instancias"']);

        $group = Group::findOrFail($id);

        $group->checkers()->sync($request->get('checkers'));

        return response()->json([
            'data' => $group->checkers
        ]);
    }     
}
