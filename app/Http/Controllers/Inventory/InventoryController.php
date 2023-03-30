<?php

namespace Apps\Http\Controllers\Inventory;

use Illuminate\Http\Request;
use Apps\Http\Controllers\Controller;
use Carbon\Carbon;

// Models
use Apps\Models\Inventory\Team;
use Apps\Models\Inventory\Inventory;
use Apps\Models\Inventory\Capture;

// Requests
use Apps\Http\Requests\Inventory\InventoryRequest;

// Traits
use Apps\Traits\Inventory\InventoryTrait;

class InventoryController extends Controller
{
    use InventoryTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json([
            'data' => Inventory::with(['teams.capturists', 'status', 'type'])->withCount('captures', 'teams')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(InventoryRequest $request) {
        $inventory = Inventory::create($request->all());

        $inventory->status;
        
        return response()->json([
            'data' => $inventory,
        ]);        
    }

    /**
     * Display the specified resource.
     *
     * @param  \Apps\Models\Inventory\Inventory  $inventory
     * @return \Illuminate\Http\Response
     */
    public function show($inventory_id) {
        $inventory = Inventory::with(['teams', 'status', 'type'])->findOrFail($inventory_id);

        $inventory->start_date = Carbon::parse($inventory->date_start)->format('Y-m-d');

        $inventory->start_hour = Carbon::parse($inventory->date_start)->format('H:m:s');

        return response()->json([
            'data' => $inventory,
        ]);        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Apps\Models\Inventory\Inventory  $inventory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $inventory_id) {
        $inventory = Inventory::with(['teams.capturists', 'status'])->findOrFail($inventory_id);

        $inventory->update($request->all());

        $inventory->status;

        return response()->json([
            'data' => $inventory,
        ]);   
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Apps\Models\Inventory\Inventory  $inventory
     * @return \Illuminate\Http\Response
     */
    public function destroy(Inventory $inventory)
    {
        //
    }

    public function query(Request $request) {
        $query = [];

        foreach ($request->only('status_id', 'type_id') as $key => $value) {
            array_push($query, [$key, $value]);
        };
        
        $inventories = Inventory::with(['teams.capturists', 'status', 'type'])->where($query)->get();

        return response()->json([
            'data' => $inventories,
        ]);  
    }

    // Teams
    public function team_index($inventory_id) {
        $inventory = Inventory::findOrFail($inventory_id);

        return response()->json([
            'data' => $inventory->teams
        ]);
    }

    public function team_sync(Request $request, $inventory_id) {
        $this->validate($request, [
            'teams' => 'array',
            'teams.*' => 'integer|exists:inventory.teams,id',
        ], [], ['teams' => '"equipos"']);

        $inventory = Inventory::findOrFail($inventory_id);

        $inventory->teams()->sync($request->get('teams'));

        return response()->json([
            'data' => $inventory->teams
        ]);
    } 

    // Team Capture
    public function team_capture_index($inventory_id, $team_id) {
        $captures = Capture::with('team')->where([
            ['inventory_id', $inventory_id],
            ['team_id', $team_id],
        ])
        ->orderBy('id', 'DESC')
        ->get();

        return response()->json([
            'data' => [
                'captures' => $captures->take(10),
                'captures_count' => $captures->count()
            ]
        ]);
    }

    public function templatestructure(Request $request, $inventory_id) {
        $inventory = Inventory::with(['captures'])->findOrFail($inventory_id);

        $structure = $this->structureGenerate($inventory);

        return response()->json([
            'data' => $structure, 
        ]);
    }

    public function report($inventory_id) {
        $inventory = Inventory::with('captures')->findOrFail($inventory_id);

        $captures = $inventory->captures;

        $data = [];

        $start = Carbon::parse($captures->first()->created_at)->toDateTimeString();

        $moments = [];

        while ($start < $captures->last()->created_at->toDateTimeString()) {
            array_push($moments, $start);

            $start = Carbon::parse($start)->addMinutes(5);
        }

        foreach ($moments as $key => $start) {
            $end = $start;

            if (isset($moments[$key + 1])) {
                $end = $moments[$key + 1];
            }

            $start = Carbon::parse($start);

            // $index = "$start->month-$start->day $start->hour:$start->minute";

            $index = "$start->hour:$start->minute horas";

            $data[$index] = $captures->where('created_at', '>=', $start)
            ->where('created_at', '<=', $end)
            ->count();
        }        

        return response()->json([
            'data' => [
                'labels' => array_keys($data),
                'values' => array_values($data),
            ], 
        ]);        
    }
}
