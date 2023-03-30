<?php

namespace Apps\Http\Controllers\Encuestas;

use Apps\Form;
use Illuminate\Http\Request;
use Apps\Http\Controllers\Controller;

class FormController extends Controller
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
     * @param  \Apps\Form  $form
     * @return \Illuminate\Http\Response
     */
    public function show(Form $form)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Apps\Form  $form
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Form $form)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Apps\Form  $form
     * @return \Illuminate\Http\Response
     */
    public function destroy(Form $form)
    {
        //
    }


    // Applices
    public function applies($form_id){

        $form = Form::with("applies.collaborator.department")->find($form_id);

        $applies = $form->applies->map(function($apply){
            $apply->progress = $apply->progress();

            return collect($apply)->except('form', 'answers');
        });

        $form = collect($form)->put('applies', $applies);

        return response()->json([
            "success" => true,
            "data" => $form,
        ], 200, [], JSON_NUMERIC_CHECK);
    }

    // All
    public function appliesAll($form_id){

        $form = Form::with("applies.collaborator.department", 'applies.answers.question')->find($form_id);

        return response()->json([
            "success" => true,
            "data" => $form,
        ], 200, [], JSON_NUMERIC_CHECK);
    }  
 
}
