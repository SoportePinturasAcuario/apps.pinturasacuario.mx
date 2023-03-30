<?php

namespace Apps\Http\Controllers\Sca;

use Illuminate\Http\Request;
use Apps\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

// Models
use Apps\Models\Sca\Turn;
use Apps\Models\Sca\Collaborator;

// Requests
use Apps\Http\Requests\Sca\TurnRequest;

class TurnController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $turns = Turn::all();

        return response()->json([
            'data' => $turns,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TurnRequest $request) {
        $turn = Turn::create($request->all());

        $events = array_reduce($request->get('events'), function($acc, $event) {

            $acc[$event['id']] = $event['pivot'];

            return $acc;
        }, []);

        $turn->events()->sync($events);

        return response()->json([
            'data' => $turn,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Apps\Models\Sca\Turn  $turn
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $turn = Turn::with(['events'])->findOrFail($id);

        $collaborators = \DB::connection('sca')
        ->table('collaborator_turn')
        ->select('collaborator_id')
        ->where('turn_id', $turn->id)
        ->get();

        $collaborator_ids = array_column($collaborators->toArray(), 'collaborator_id');

        $turn->collaborators = Collaborator::select(['id', 'name', 'departamento_id', 'sucursal_id'])
        ->with(['department:id,nombre', 'branch_office:id,name'])
        ->whereIn('id', $collaborator_ids)
        ->get();

        return response()->json([
            'data' => $turn,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Apps\Models\Sca\Turn  $turn
     * @return \Illuminate\Http\Response
     */
    public function update(TurnRequest $request, $id)
    {
        $turn = Turn::findOrFail($id);

        $turn->update($request->all());

        $events = array_reduce($request->get('events'), function($acc, $event) {

            $acc[$event['id']] = $event['pivot'];

            return $acc;
        }, []);

        $turn->events()->sync($events);

        return response()->json([
            'data' => $turn,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Apps\Models\Sca\Turn  $turn
     * @return \Illuminate\Http\Response
     */
    public function destroy(Turn $turn)
    {
        //
    }

    public function collaborator_store(Request $request, $turn_id) {
        $this->validate($request, [
            'end' => "required",
            'start' => "required",
            'collaborators' => 'required|array|min:1|max:500',
            'collaborators.*' => 'numeric',

            'turn_id' => 'required|exists:sca.turns,id',
        ]);

        $insert = array_map(function($collaborator_id) use($request) {
            return array_merge(
                $request->only(['end', 'start', 'turn_id']),
                [
                    'collaborator_id' => $collaborator_id
                ]
            );
        }, $request->get('collaborators'));

        DB::connection('sca')->table('collaborator_turn')->insert($insert);

        return response()->json([
            'data' => [],
        ]);
    }

    public function query(Request $request) {


        $data = \DB::connection('sca')->table('collaborator_turn')->get();

        $turns = Turn::whereIn('id', array_unique(array_column($data->toArray(), 'turn_id')))->get();

        $turns = array_reduce(array_unique(array_column($data->toArray(), 'turn_id')), function($acc, $turn_id) use($data, $turns) {

            $turn = $data->firstWhere('id', $turn_id);

            $turn->collaborators = $data->where('turn_id', $turn_id)->toArray();
            
            $acc[$turn_id] = $turn;

            return $acc;
        }, []);

        return response()->json([
            'data' => array_values($turns)
        ]);


        // $data = \DB::connection('sca')->table("collaborator_turn")->select([
        //     '*',
        //     DB::raw("CONCAT(start, ' ', end) AS 'range'")
        // ])->get();

        // dd($data->toArray());

        // $collaborators = Collaborator::select(['id', 'name'])->whereIn('id', array_unique(array_column($data->toArray(), 'collaborator_id')))->get();

        // $turns = Turn::select(['id', 'name'])->whereIn('id', array_unique(array_column($data->toArray(), 'turn_id')))->get();

        // $data = array_reduce(array_unique(array_column($data->toArray(), 'range')), function($acc, $range) use($collaborators, $turns, $data) {

        //     // $collaborator_ids = array_unique(array_column($data->where('range', $range)->toArray(), 'collaborator_id'));

        //     // dd($collaborators->whereIn('id', $collaborator_ids)->toArray());

        //     $acc[$range] = $data->whereIn('range', $range)->toArray();

        //     return $acc;
        // }, []);

        // return response()->json([
        //     'data' => $data,
        // ]);
    }
}
