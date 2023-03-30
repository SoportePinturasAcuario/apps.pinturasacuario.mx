<?php

namespace Apps\Http\Controllers\Poll;

use Illuminate\Http\Request;
use Apps\Http\Controllers\Controller;

// Models
use Apps\Poll;
use Apps\Form;
use Apps\Apply;
use Apps\Collaborator;

class PollController extends Controller
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
     * @param  \Apps\Models\Poll\Poll  $poll
     * @return \Illuminate\Http\Response
     */
    public function show(Poll $poll)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Apps\Models\Poll\Poll  $poll
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Poll $poll)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Apps\Models\Poll\Poll  $poll
     * @return \Illuminate\Http\Response
     */
    public function destroy(Poll $poll)
    {
        //
    }


    public function form_qualification($poll_id, $form_id) {
        $data = Poll::findOrFail($poll_id);

        $data->form = Form::where([
            ['id', $form_id],
            ['poll_id', $poll_id],
        ])->with([
            'applies.qualification',
            'applies.qualification',
            'applies.collaborator.department',
            'applies.collaborator.branchOffice',
        ])->get()->first();

        return response()->json([
            'data' => $data,
        ]);
    }
}
