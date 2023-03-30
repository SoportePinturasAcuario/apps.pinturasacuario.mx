<?php

namespace Apps\Http\Controllers\Poll;

use Apps\Apply;
use Illuminate\Http\Request;
use Apps\Http\Controllers\Controller;

class ApplyController extends Controller
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
     * @param  \Apps\Apply  $apply
     * @return \Illuminate\Http\Response
     */
    public function show(Apply $apply)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Apps\Apply  $apply
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Apply $apply)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Apps\Apply  $apply
     * @return \Illuminate\Http\Response
     */
    public function destroy(Apply $apply)
    {
        //
    }

    public function query(Request $request) {
        $this->validate($request, [
            'poll_id' => 'nullable|numeric',
            'form_id' => 'nullable|numeric',
            'department_id' => 'nullable|numeric',
        ]);

        $applies = Apply::with([
            'collaborator',
            // 'answers.option',
            'answers.question.options',
            // 'collaborator.department',
            // 'collaborator.branchoffice',
        ])->where($request->only(['form_id', 'poll_id']))->get();

        $applies = $applies->where('collaborator.departamento_id', $request->get('department_id'));

        $applies = $applies->map(function($apply) {

            $answers = $apply->answers->map(function($answer) {

                $option = $answer->question->options->firstWhere('id', $answer->option_id);
                
                $option->value = (int) $option->pivot->value;

                $answer->question_index = $option->pivot->question_index;

                $answer = collect($answer);

                $answer->put("option", collect($option)->except('pivot'));

                return $answer->except('question');
            });

            $apply = collect($apply);

            $apply->put('answers', $answers);

            return $apply;
        });

        $applies = $applies->values()->toArray();


        return response()->json([
            'data' => $applies,
        ]);
    }    
}
