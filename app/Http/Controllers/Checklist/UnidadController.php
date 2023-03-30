<?php

namespace Apps\Http\Controllers\Checklist;

use Apps\Unidad;
use Illuminate\Http\Request;
use Apps\Http\Controllers\Controller;

class UnidadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $unidades = Unidad::all();

        return response()->json([
            'success' => 200,
            'data' => $unidades,
        ], 200, [], JSON_NUMERIC_CHECK);  
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function withtrashed()
    {
        $unidades = Unidad::withTrashed()->get();

        return response()->json([
            'success' => 200,
            'data' => $unidades,
        ], 200, [], JSON_NUMERIC_CHECK);  
    }    

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:checklists.unidades,name'
        ]);

        $unidades = Unidad::create($request->all());

        return response()->json([
            'success' => 200,
            'data' => $unidades,
        ], 200, [], JSON_NUMERIC_CHECK); 
    }

    /**
     * Display the specified resource.
     *
     * @param  \Apps\Unidad  $unidad
     * @return \Illuminate\Http\Response
     */
    public function show(Unidad $unidad)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Apps\Unidad  $unidad
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Unidad $unidad)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $unidad = Unidad::find($id);

        $unidad->checklists->isEmpty() ? $unidad->forceDelete() : $unidad->delete();

        return response()->json([
            'success' => 200,
            'data' => $unidad,
        ], 200, [], JSON_NUMERIC_CHECK); 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function restore($unidad_id)
    {
        Unidad::withTrashed()->where('id', $unidad_id)->restore();

        return response()->json([
            'success' => 200,
            'data' => Unidad::find($unidad_id),
        ], 200, [], JSON_NUMERIC_CHECK); 
    } 
}
