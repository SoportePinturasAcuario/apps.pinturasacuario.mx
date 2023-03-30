<?php

namespace Apps\Http\Controllers\Sca;

use Illuminate\Http\Request;
use Apps\Http\Controllers\Controller;

// Models
use Apps\Models\Sca\RegisterType;

class RegisterTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $registers = RegisterType::all();

        return response()->json([
            'data' => $registers,
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
     * @param  \Apps\models\sca\RegisterType  $registerType
     * @return \Illuminate\Http\Response
     */
    public function show(RegisterType $registerType)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Apps\models\sca\RegisterType  $registerType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, RegisterType $registerType)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Apps\models\sca\RegisterType  $registerType
     * @return \Illuminate\Http\Response
     */
    public function destroy(RegisterType $registerType)
    {
        //
    }
}
