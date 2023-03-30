<?php

namespace Apps\Http\Controllers\Inventory;

use Illuminate\Http\Request;
use Apps\Http\Controllers\Controller;

// Models
use Apps\Models\Inventory\InventoryType;

class InventoryTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json([
            'data' => InventoryType::all(),
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
     * @param  \Apps\Models\Inventory\InventoryType  $inventoryType
     * @return \Illuminate\Http\Response
     */
    public function show(InventoryType $inventoryType)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Apps\Models\Inventory\InventoryType  $inventoryType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, InventoryType $inventoryType)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Apps\Models\Inventory\InventoryType  $inventoryType
     * @return \Illuminate\Http\Response
     */
    public function destroy(InventoryType $inventoryType)
    {
        //
    }
}
