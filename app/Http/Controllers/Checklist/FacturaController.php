<?php

namespace Apps\Http\Controllers\Checklist;

use Illuminate\Http\Request;
use Apps\Http\Controllers\Controller;
use Apps\Factura;
use Apps\Checklist;

class FacturaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $facturas = Factura::all();

        return response()->json([
            "success" => true,
            "data" => $facturas,
        ], 200); 
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function byChecklistId(Checklist $checklist)
    {
        $facturas = $checklist->facturas->each(function($factura){
            return $factura->customer;
        }); 

        return response()->json([
            "success" => true,
            "data" => $facturas,
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
            'folio' => 'required|unique:checklists.facturas,folio',
            'monto' => 'required|numeric',
            'checklist_id' => 'required|integer',
            'customer_id' => 'required|integer',
        ]);

        $factura = Factura::create($request->all());

        return response()->json([
            "success" => true,
            "data" => $factura,
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
    public function update(Request $request, Factura $factura)
    {
        $request->validate([
            'folio' => 'required|integer|unique:checklists.facturas,folio,' . $factura->id,
            'monto' => 'required|numeric',
            'checklist_id' => 'required|integer',
            'customer_id' => 'required|integer',            
        ]);

        $factura->update($request->all());

        return response()->json([
            "success" => true,
            "data" => $factura,
        ], 200, [], JSON_NUMERIC_CHECK); 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Factura $factura)
    {
        $factura->delete();

        return response()->json([
            "success" => true,
            "data" => $factura,
        ], 200, [], JSON_NUMERIC_CHECK); 
    }
}
