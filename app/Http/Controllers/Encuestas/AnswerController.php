<?php

namespace Apps\Http\Controllers\Encuestas;

use Apps\Answer;
use Apps\Apply;
use Illuminate\Http\Request;
use Apps\Http\Controllers\Controller;

class AnswerController extends Controller
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
        $answer = Answer::create($request->all());
        
        return response()->json([
            'success' => true,
            'data' => $answer,
        ], 200, [], JSON_NUMERIC_CHECK);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Apps\Answer  $answer
     * @return \Illuminate\Http\Response
     */
    public function show(Answer $answer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Apps\Answer  $answer
     * @return \Illuminate\Http\Response
     */
    public function edit(Answer $answer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Apps\Answer  $answer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Answer $answer)
    {
        $answer->update($request->all());

        return response()->json([
            'success' => true,
            'data' => $answer,
        ], 200, [], JSON_NUMERIC_CHECK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Apps\Answer  $answer
     * @return \Illuminate\Http\Response
     */
    public function answersApply($apply_id)
    {
        $apply = Apply::with("form.questions.options", "answers.option")->find($apply_id);

        $questions = $apply->form->questions->map(function($question) use ($apply){
            $question->answer = $apply->answers->firstWhere("question_id", $question->id);

            return $question;
        });

        return response()->json([
            'success' => true,
            'data' => $questions,
        ], 200, [], JSON_NUMERIC_CHECK);        
    }
}
