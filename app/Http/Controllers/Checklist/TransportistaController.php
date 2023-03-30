<?php

namespace Apps\Http\Controllers\Checklist;

use Illuminate\Http\Request;
use Apps\Http\Controllers\Controller;
use Apps\Transportista;

class TransportistaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $transportistas = Transportista::all();

        return response()->json([
            'success' => 200,
            'data' => $transportistas,
        ], 200, [], JSON_NUMERIC_CHECK);        
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function withtrashed()
    {
        $transportistas = Transportista::withTrashed()->get();

        return response()->json([
            'success' => 200,
            'data' => $transportistas,
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
            'name' => 'required|unique:checklists.transportistas,name'
        ]);

        $transportista = Transportista::create($request->all());

        return response()->json([
            'success' => 200,
            'data' => $transportista,
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
        $transportista = Transportista::find($id);
        
        $request->validate([
            'name' => 'required|unique:checklists.transportistas,name,' . $transportista->id
        ]);

        $transportista->update($request->all());

        return response()->json([
            'success' => 200,
            'data' => $transportista,
        ], 200, [], JSON_NUMERIC_CHECK); 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $transportista = Transportista::find($id);

        $transportista->checklists->isEmpty() ? $transportista->forceDelete() : $transportista->delete();

        return response()->json([
            'success' => 200,
            'data' => $transportista,
        ], 200, [], JSON_NUMERIC_CHECK); 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function restore($transportista_id)
    {
        Transportista::withTrashed()->where('id', $transportista_id)->restore();

        return response()->json([
            'success' => 200,
            'data' => Transportista::find($transportista_id),
        ], 200, [], JSON_NUMERIC_CHECK); 
    }    
}
