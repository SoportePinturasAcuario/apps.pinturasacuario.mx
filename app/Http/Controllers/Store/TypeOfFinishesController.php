<?php

namespace Apps\Http\Controllers\Store;

use Apps\Models\Store\TypeOfFinishes;
use Illuminate\Http\Request;
use Apps\Http\Controllers\Controller;

class TypeOfFinishesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json([
            'data' => TypeOfFinishes::orderBy('name', 'ASC')->get(),
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
     * @param  \Apps\TypeOfFinishes  $typeOfFinishes
     * @return \Illuminate\Http\Response
     */
    public function show(TypeOfFinishes $typeOfFinishes)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Apps\TypeOfFinishes  $typeOfFinishes
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TypeOfFinishes $typeOfFinishes)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Apps\TypeOfFinishes  $typeOfFinishes
     * @return \Illuminate\Http\Response
     */
    public function destroy(TypeOfFinishes $typeOfFinishes)
    {
        //
    }
}
