<?php

namespace Apps\Http\Controllers\Encuestas;
use Carbon\Carbon;

use Apps\Apply;
use Apps\Answer;
use Illuminate\Http\Request;
use Apps\Http\Controllers\Controller;
use Apps\Qualification;

class ApplyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $applies = Apply::all();

        return response()->json([
            'success' => true,
            'data' => $applies,
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
        $apply = Apply::create($request->all());

        return response()->json([
            'success' => true,
            'data' => $apply,
        ], 200, [], JSON_NUMERIC_CHECK);       
    }    

    /**
     * Display the specified resource.
     *
     * @param  \App\Apply  $apply
     * @return \Illuminate\Http\Response
     */
    public function show($apply_id)
    {   
        $apply = Apply::with('answers.question.options', 'collaborator.department', 'form', 'poll')->find($apply_id);
        $apply->formatDates();

        $answers = $apply->answers->map(function($answer){

            $answer->option->value = $answer->question->options->firstWhere('id', $answer->option_id)->pivot->value; 
            $answer->question_index = $answer->question->options->firstWhere('id', $answer->option_id)->pivot->question_index; 


            $answer = collect($answer);

            return $answer->except(['question']);
        });

        $apply = collect($apply);


        $apply->put('answers', $answers);


        return response()->json([
            'success' => true,
            'data' => $apply,
        ], 200, [], JSON_NUMERIC_CHECK);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Apply  $apply
     * @return \Illuminate\Http\Response
     */
    public function edit(Apply $apply)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Apply  $apply
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Apply $apply)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Apply  $apply
     * @return \Illuminate\Http\Response
     */
    public function destroy(Apply $apply)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Apply  $apply
     * @return \Illuminate\Http\Response
     */
    public function poll($apply_id)
    {
        $apply = Apply::with('collaborator', 'poll')->find($apply_id);

        $apply->progress = $apply->progress();

        return response()->json([
            'success' => true,
            'data' => $apply,
        ], 200, [], JSON_NUMERIC_CHECK);
    }    

    public function form($apply_id)
    {
        $apply = Apply::with('form.questions', 'answers')->find($apply_id);

        $apply->progress = $apply->progress();
        $apply->formatDates();

        $form = collect($apply->form);
        $form->put('questions_count', count($form->get('questions')));
        $form = $form->except('questions');
        
        $apply = collect($apply);
        $apply->put('answers_count', count($apply->get('answers')));

        $apply->put('form', $form);

        $apply = $apply->except('answers');

        return response()->json([
            'success' => true,
            'data' => $apply,
        ], 200, [], JSON_NUMERIC_CHECK);
    }  

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Apply  $apply
     * @return \Illuminate\Http\Response
     */
    public function questions($apply_id)
    {
        $apply = Apply::with("form.questions.options", "answers")->find($apply_id);

        $answers = $apply->answers;

        $questions = $apply->form->questions;

        $questions = $questions->map(function($question) use ($answers){
            
            $question->answer = $answers->firstWhere('question_id', '==', $question->id);
            
            return $question;
        });
        
        return response()->json([
            'success' => true,
            'data' => $questions
        ], 200, [], JSON_NUMERIC_CHECK);
    }

    public function finalize(Apply $apply)
    {
        $apply->status_id = 20;
        $apply->save();

        return response()->json([
            'success' => true,
            'data' => $apply,
        ], 200, [], JSON_NUMERIC_CHECK);        
    }  


    public function applies(Request $request)
    {   
        $applies = Apply::whereIn('id', $request->get('applies'))
            ->with('collaborator.department', 'answers.question.options')
            ->get();

        $applies = $applies->map(function($apply){

            $answers = $apply->answers->map(function($answer){

                $option = $answer->question->options->firstWhere('id', $answer->option_id);
                
                $option->value = $option->pivot->value;

                $answer->question_index = $option->pivot->question_index;

                $answer = collect($answer);

                $answer->put("option", collect($option)->except('pivot'));

                return $answer->except('question');
            });

            $apply->progress = $apply->progress();

            $apply = collect($apply);

            $apply->put('answers', $answers);

            return $apply;
        });

        return response()->json([
            'success' => true,
            'data' => $applies,
        ], 200, [], JSON_NUMERIC_CHECK);        
    }

    public function whereId(Request $request){
        $applies = [];
        $ids = $request->get('data');

        if (!empty($ids)) {
            $applies = Apply::with('answers', 'collaborator.department')->whereIn('id', $ids)->get();

            $data = \DB::connection('nom')->select('SELECT `applies`.`id` AS "apply_id", `answers`.`id` AS "answer_id", `option_question`.`question_index`, `option_question`.`value` AS "option_value"FROM `applies` JOIN `answers` ON `answers`.`apply_id` = `applies`.`id` JOIN `option_question` ON `option_question`.`option_id` = `answers`.`option_id` AND `option_question`.`question_id` = `answers`.`question_id` WHERE `applies`.`id` IN ('.join(',', $request->get('data')).') ORDER BY `applies`.id ASC');

            $applies = $applies->map(function($apply) use($data){
                $answers = $apply->answers->map(function($answer) use($data){
                    $row = collect(collect($data)->firstWhere('answer_id', $answer->id));

                    return [
                        'option' => [
                            'value' => (int) $row->get('option_value'),
                        ],
                        'question_index' => $row->get('question_index')

                    ];
                });

                $apply = collect($apply)->put('answers', $answers);

                return $apply;
            });
        }

        return response()->json([
            "success" => true,
            "data" => $applies,
        ], 200, [], JSON_NUMERIC_CHECK);
    }

    public function qualify(Request $request, Apply $apply){

        if (empty($apply->qualification)) {
            $qualification = $apply->qualification()->create($request->all());
        }else{
            $qualification = $apply->qualification;
            $qualification->update($request->all());
        }

        return response()->json([
            "success" => true,
            "data" => $qualification,
        ], 200, [], JSON_NUMERIC_CHECK);        
    }
}
