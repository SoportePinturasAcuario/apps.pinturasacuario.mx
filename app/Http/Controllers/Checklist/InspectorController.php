<?php

namespace Apps\Http\Controllers\Checklist;

use Illuminate\Http\Request;
use Apps\Http\Controllers\Controller;
use Apps\Inspector;

class InspectorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $inspectores = Inspector::all();

        return response()->json([
            "success" => true,
            "data" => $inspectores,
        ], 200, [], JSON_NUMERIC_CHECK); 
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function withtrashed()
    {
        $inspectores = Inspector::withTrashed()->get();

        return response()->json([
            "success" => true,
            "data" => $inspectores,
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
            'name' => 'required|unique:checklists.inspectores,name'
        ]);

        $inspector = Inspector::create($request->all());

        return response()->json([
            "success" => true,
            "data" => $inspector,
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
        $inspector = Inspector::find($id);

        $inspector->checklists->isEmpty() ? $inspector->forceDelete() : $inspector->delete();

        return response()->json([
            'success' => 200,
            'data' => $inspector,
        ], 200, [], JSON_NUMERIC_CHECK); 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function restore($inspector_id)
    {
        Inspector::withTrashed()->where('id', $inspector_id)->restore();

        return response()->json([
            'success' => 200,
            'data' => Inspector::find($inspector_id),
        ], 200, [], JSON_NUMERIC_CHECK); 
    } 
}
