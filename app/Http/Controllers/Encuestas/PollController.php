<?php

namespace Apps\Http\Controllers\Encuestas;

use Illuminate\Http\Request;
use Apps\Http\Controllers\Controller;

// Models
use Apps\Poll;

class PollController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $polls = Poll::all();

        return response()->json([
            'data' => $polls
        ]);
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
    public function show($poll_id)
    {
        $poll = Poll::with(['forms' => function($query) {
            return $query->withCount('questions');
        }])->findOrFail($poll_id);

        return response()->json([
            'data' => $poll
        ]);
    }

    public function collaborator_index($poll_id){
        $poll = Poll::with([
            "forms.questions",
            "applies.answers",
            "applies.qualification",
            "collaborators.department",
            "collaborators.branchoffice",
        ])->find($poll_id);

        $data = collect(["id" => $poll->id, "name" => $poll->name, "collaborators" => collect([])]);

        $poll->collaborators->each(function($collaborator, $key) use($poll, $data){
            
            $applies = $collaborator->applies->map(function($apply) {
                
                $progress = $apply->progress();

                $answers_count = $apply->answers->count();

                $apply->formatDates();  

                $apply = collect($apply);

                $apply->put("progress", $progress);

                $apply->put("answers_count", $answers_count);

                return $apply->except("poll","collaborator", "form", 'answers');
            });

            $forms = $poll->forms->map(function($form) use ($applies){
                $form = collect($form);

                $form->put("questions_count", count($form->get("questions")));

                $form->put("apply", $applies->firstWhere("form_id", "==", $form->get("id")));

                return $form->except("questions");
            });

            $data["collaborators"]->push([
                "forms" => $forms,
                "folio" => $collaborator->id,
                "name" => $collaborator->name,
                'department' => $collaborator->department,
                'branchoffice' => $collaborator->branchoffice,
                'branch_office_id' => $collaborator->sucursal_id,
                'department_id' => $collaborator->departamento_id,
                "apply" => $applies->firstWhere("poll_id", "==", $poll->id),
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
        
        $poll = Poll::with("applies.collaborator.department", 'applies.qualification')->find($poll_id);

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


    // Qualification    
    public function qualification($poll_id){
        $poll = Poll::with()->findOrFail($poll_id);
    }
}
