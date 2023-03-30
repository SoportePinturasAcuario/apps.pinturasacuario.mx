<?php

namespace Apps\Http\Controllers\Claim;

use Apps\Models\Claim\Classification;
use Illuminate\Http\Request;
use Apps\Http\Controllers\Controller;

class ClassificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $classification = Classification::all();

        return response()->json([
            'success' => true,
            'data' => $classification,
        ], 200, [], JSON_NUMERIC_CHECK); 
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function withtrashed()
    {
        $classification = Classification::withTrashed()->get();

        return response()->json([
            'success' => true,
            'data' => $classification,
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
            'name' => 'required|unique:claims.classifications,name'
        ]);

        $classification = Classification::create($request->all());

        return response()->json([
            "success" => true,
            "data" => $classification,
        ], 200, [], JSON_NUMERIC_CHECK); 
    }

    /**
     * Display the specified resource.
     *
     * @param  \Apps\Classification  $classification
     * @return \Illuminate\Http\Response
     */
    public function show(Classification $classification)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Apps\Classification  $classification
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Classification $classification)
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
        $classification = Classification::find($id);

        $classification->claims->isEmpty() ? $classification->forceDelete() : $classification->delete();

        return response()->json([
            'success' => 200,
            'data' => $classification,
        ], 200, [], JSON_NUMERIC_CHECK); 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function restore($classification_id)
    {
        Classification::withTrashed()->where('id', $classification_id)->restore();

        return response()->json([
            'success' => 200,
            'data' => Classification::find($classification_id),
        ], 200, [], JSON_NUMERIC_CHECK); 
    } 
}
