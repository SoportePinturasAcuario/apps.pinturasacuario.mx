<?php

namespace Apps\Http\Controllers\Pa;

use Apps\Approval;
use Illuminate\Http\Request;
use Apps\Http\Controllers\Controller;
use Apps\Events\ApprovalSatusUpdate;
class ApprovalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \Apps\Approval  $approval
     * @return \Illuminate\Http\Response
     */
    public function show(Approval $approval)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Apps\Approval  $approval
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Approval $approval)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Apps\Approval  $approval
     * @return \Illuminate\Http\Response
     */
    public function destroy(Approval $approval)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Apps\Approval  $approval
     * @return \Illuminate\Http\Response
     */
    public function updateStatus(Request $request, Approval $approval)
    {
        $approval->update($request->all());

        $approval->authorizingUser->collaborator;

        event(new ApprovalSatusUpdate($approval));

        return response()->json([
            "success" => true,
            "data" => $approval
        ], 200, [], JSON_NUMERIC_CHECK);        
    }    
}
