<?php

namespace Apps\Http\Controllers\Encuestas;

use Apps\Poll;
use Illuminate\Http\Request;
use Apps\Http\Controllers\Controller;

class PollController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \Apps\Poll  $poll
     * @return \Illuminate\Http\Response
     */
    public function show($poll_id){
        $poll = Poll::with("collaborators.department", "applies", "forms.questions")->find($poll_id);

        $data = collect(["id" => $poll->id, "name" => $poll->name, "collaborators" => collect([])]);

        $poll->collaborators->each(function($collaborator, $key) use($poll, $data){
            
            $applies = $collaborator->applies->map(function($apply){
                $progress = $apply->progress();
                $apply->qualification;
                $apply->formatDates();                

                $apply = collect($apply);

                $apply->put("progress", $progress);

                if ($apply->get("answers")) {
                    $apply->put("answers_count", count($apply->get("answers")));
                }

                return $apply->except("poll", "collaborator", "form", "answers");
            });

            $forms = $poll->forms->map(function($form) use ($applies){
                $form = collect($form);

                $form->put("questions_count", count($form->get("questions")));

                $form->put("apply", $applies->firstWhere("form_id", "==", $form->get("id")));

                return $form->except("questions");
            });

            $data["collaborators"]->push([
                "id" => $key + 1,
                "folio" => $collaborator->id,
                "name" => $collaborator->name,
                "department" => $collaborator->department,
                "apply" => $applies->firstWhere("poll_id", "==", $poll->id),
                "forms" => $forms,
            ]);
        });

        return response()->json([
            "success" => true,
            "data" => $data,
        ], 200, [], JSON_NUMERIC_CHECK);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Apps\Poll  $poll
     * @return \Illuminate\Http\Response
     */
    public function edit(Poll $poll)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Apps\Poll  $poll
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Poll $poll)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Apps\Poll  $poll
     * @return \Illuminate\Http\Response
     */
    public function destroy(Poll $poll)
    {
        //
    }


    /**
     * Display the specified resource.
     *
     * @param  \Apps\Poll  $poll
     * @return \Illuminate\Http\Response
     */
    public function applies($poll_id){
        
        $poll = Poll::with("applies.collaborator.department")->find($poll_id);

        $applies = $poll->applies->map(function($apply){

            $apply->progress = $apply->progress();


            $collaborator = collect($apply->collaborator)->except("applies");

            $apply = collect($apply)->except("poll");

            $apply->put("collaborator", $collaborator);

            return $apply;
        });

        $poll = collect($poll)->put("applies", $applies);

        return response()->json([
            "success" => true,
            "data" => $poll,
        ], 200, [], JSON_NUMERIC_CHECK);
    }    
}
