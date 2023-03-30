<?php

namespace Apps\Http\Controllers\Inventory;

use Apps\Models\Inventory\Team;
use Illuminate\Http\Request;
use Apps\Http\Controllers\Controller;

// Requests
use Apps\Http\Requests\Inventory\TeamRequest;

class TeamController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json([
            'data' => Team::withCount(['capturists', 'inventories'])->with(['capturists', 'inventories'])->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TeamRequest $request)
    {
        $team = Team::create($request->all());

        return response()->json([
            'data' => $team,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Apps\Models\Inventory\Team  $team
     * @return \Illuminate\Http\Response
     */
    public function show(Team $team)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Apps\Models\Inventory\Team  $team
     * @return \Illuminate\Http\Response
     */
    public function update(TeamRequest $request, $team_id)
    {
        $team = Team::findOrFail($team_id);

        $team->update($request->all());

        if ($request->has('capturists')) {
            $team->capturists()->sync($request->get('capturists'));
        }

        return response()->json([
            'data' => $team,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Apps\Models\Inventory\Team  $team
     * @return \Illuminate\Http\Response
     */
    public function destroy(Team $team)
    {
        //
    }
}
