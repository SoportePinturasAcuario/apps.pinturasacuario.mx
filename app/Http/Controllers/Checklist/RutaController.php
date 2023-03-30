<?php

namespace Apps\Http\Controllers\Checklist;

use Illuminate\Http\Request;
use Apps\Http\Controllers\Controller;
use Apps\Ruta;

class RutaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Htstp\Response
     */
    public function index()
    {
        $rutas = Ruta::all();

        return response()->json([
            "success" => true,
            "data" => $rutas,
        ], 200, [], JSON_NUMERIC_CHECK); 
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Htstp\Response
     */
    public function withtrashed()
    {
        $rutas = Ruta::withTrashed()->get();

        return response()->json([
            "success" => true,
            "data" => $rutas,
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
            'name' => 'required|unique:checklists.rutas,name'
        ]);

        $ruta = Ruta::create($request->all());

        return response()->json([
            "success" => true,
            "data" => $ruta,
        ], 200, [], JSON_NUMERIC_CHECK); 
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
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
        $ruta = Ruta::find($id);

        $ruta->checklists->isEmpty() ? $ruta->forceDelete() : $ruta->delete();

        return response()->json([
            'success' => 200,
            'data' => $ruta,
        ], 200, [], JSON_NUMERIC_CHECK); 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function restore($ruta_id)
    {
        Ruta::withTrashed()->where('id', $ruta_id)->restore();

        return response()->json([
            'success' => 200,
            'data' => Ruta::find($ruta_id),
        ], 200, [], JSON_NUMERIC_CHECK); 
    } 
}
