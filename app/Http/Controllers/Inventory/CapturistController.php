<?php

namespace Apps\Http\Controllers\Inventory;

use Illuminate\Http\Request;
use Apps\Http\Controllers\Controller;

// Models
use Apps\Models\Inventory\Capturist;

// Requests
use Apps\Http\Requests\Inventory\CapturistRequest;

class CapturistController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $capturist = Capturist::withCount(['teams'])->get();

        return response()->json([
            'data' => $capturist,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CapturistRequest $request) {

        $capturist = Capturist::create($request->all());

        return response()->json([
            'data' => $capturist,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Apps\Models\Inventory\Capturist  $capturist
     * @return \Illuminate\Http\Response
     */
    public function show(Capturist $capturist)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Apps\Models\Inventory\Capturist  $capturist
     * @return \Illuminate\Http\Response
     */
    public function update(CapturistRequest $request, $capturist_id) {
        
        $capturist = Capturist::findOrFail($capturist_id);

        $capturist->update($request->all());

        return response()->json([
            'data' => $capturist
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Apps\Models\Inventory\Capturist  $capturist
     * @return \Illuminate\Http\Response
     */
    public function destroy(Capturist $capturist)
    {
        //
    }
}
