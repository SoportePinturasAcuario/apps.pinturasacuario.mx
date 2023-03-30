<?php

namespace Apps\Http\Controllers\Encuestas;

use Apps\Collaborator;
use Illuminate\Http\Request;
use Psr\Http\CollaboratorRequest;
use Apps\Http\Controllers\Controller;

// use Apps\Http\Requests\Encuestas\ColabaoratorRequest;


class CollaboratorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $collaborators = Collaborator::with("department", 'branchoffice')->get();

        return response()->json([
            "success" => true,
            "data" => $collaborators,
        ], 200, [], JSON_NUMERIC_CHECK);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|integer|unique:nom.collaborators,id',
            'name' => 'required',
        ]);

        $collaborator = Collaborator::create($request->all());

        $collaborator = Collaborator::find($request->get("id"));

        $collaborator->polls()->attach(1);
        
        return response()->json([
            "success" => true,
            "data" => $collaborator,
        ], 200, [], JSON_NUMERIC_CHECK);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Apps\Collaborator  $collaborator
     * @return \Illuminate\Http\Response
     */
    public function show(Collaborator $collaborator)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Apps\Collaborator  $collaborator
     * @return \Illuminate\Http\Response
     */
    public function edit(Collaborator $collaborator)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Apps\Collaborator  $collaborator
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Collaborator $collaborator)
    {
        $collaborator->update($request->all());

        return response()->json([
            "success" => true,
            "data" => $collaborator,
        ], 200, [], JSON_NUMERIC_CHECK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Apps\Collaborator  $collaborator
     * @return \Illuminate\Http\Response
     */
    public function destroy(Collaborator $collaborator)
    {
        \DB::connection('nom')->table('collaborator_poll')->where('collaborator_id', '=', $collaborator->id)->delete();

        foreach ($collaborator->applices as $key => $apply) {
            \DB::connection('nom')->table('applies')->where('id', '=', $apply->id)->delete();

            \DB::connection('nom')->table('answers')->where('apply_id', '=', $apply->id)->delete();
        }

        $collaborator->delete();

        return response()->json([
            "success" => true,
        ], 200, [], JSON_NUMERIC_CHECK);        
    }
}
