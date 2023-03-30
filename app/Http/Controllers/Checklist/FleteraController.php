<?php

namespace Apps\Http\Controllers\Checklist;

use Illuminate\Http\Request;
use Apps\Http\Controllers\Controller;
use Apps\Fletera;

class FleteraController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Htstp\Response
     */
    public function index()
    {
        $fleteras = Fletera::all();

        return response()->json([
            "success" => true,
            "data" => $fleteras,
        ], 200, [], JSON_NUMERIC_CHECK); 
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Htstp\Response
     */
    public function withtrashed()
    {
        $fleteras = Fletera::withTrashed()->get();

        return response()->json([
            "success" => true,
            "data" => $fleteras,
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
            'name' => 'required|unique:checklists.fleteras,name'
        ]);

        $fletera = Fletera::create($request->all());

        return response()->json([
            "success" => true,
            "data" => $fletera,
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
        $fletera = Fletera::find($id);

        $fletera->checklists->isEmpty() ? $fletera->forceDelete() : $fletera->delete();

        return response()->json([
            'success' => 200,
            'data' => $fletera,
        ], 200, [], JSON_NUMERIC_CHECK); 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function restore($fletera_id)
    {
        Fletera::withTrashed()->where('id', $fletera_id)->restore();

        return response()->json([
            'success' => 200,
            'data' => Fletera::find($fletera_id),
        ], 200, [], JSON_NUMERIC_CHECK); 
    } 
}
