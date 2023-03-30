<?php

namespace Apps\Http\Controllers;

use Apps\Poll;
use Illuminate\Http\Request;

// Models
use Apps\Collaborator;

// Requests
use Apps\Http\Requests\CollaboratorRequest;

class CollaboratorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $collaborators = Collaborator::with(
            'emails',
            'department',
            'branchoffice'
        )->get();

        return response()->json([
            "success" => true,
            "data" => $collaborators
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
    public function store(CollaboratorRequest $request){

        $collaborator = Collaborator::create(array_map('strtoupper', $request->all()));
       
        return response()->json([
            'data' => $collaborator,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Apps\Collaborator  $collaborator
     * @return \Illuminate\Http\Response
     */
    public function show($collaborator_id)
    {  
        return response()->json([
            'data' => Collaborator::with(['department:id,nombre', 'branchoffice:id,name'])
            ->withTrashed()
            ->findOrFail($collaborator_id),
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Apps\Collaborator  $collaborator
     * @return \Illuminate\Http\Response
     */
    public function show_with_everything($collaborator_id)
    {
        return response()->json([
            'data' => Collaborator::with(['department:id,nombre', 'branchoffice:id,name'])
            ->withTrashed()
            ->findOrFail($collaborator_id),
        ]);
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
    public function update(CollaboratorRequest $request, $collaborator_id)
    {
        $collaborator = Collaborator::withTrashed()->findOrFail($collaborator_id);

        if (!empty($collaborator->deleted_at)) {
              return response()->json([
                'success' => false,
                'message' => 'Unprocessable Entity',
                'errors' => [
                    'collaborator' => ['No es posible realizar la acción solicitada. Debido a que el registro actualmente eliminado.']
                ]
            ], 422); 
        }

        $collaborator->update($request->all());

        return response()->json([
            'data' => $collaborator,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Apps\Collaborator  $collaborator
     * @return \Illuminate\Http\Response
     */
    public function destroy($collaborator_id)
    {
        $collaborator = Collaborator::findOrFail($collaborator_id);

        $collaborator->delete();

        return response()->json([
            'data' => [],
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Apps\Collaborator  $collaborator
     * @return \Illuminate\Http\Response
     */
    public function restore($collaborator_id)
    {
        Collaborator::withTrashed()->findOrFail($collaborator_id)->restore();

        return response()->json([
            'data' => [],
        ]);
    }    

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Apps\Collaborator  $collaborator
     * @return \Illuminate\Http\Response
     */
    public function polls($collaborator_id)
    {
        $collaborator = Collaborator::with('polls', 'applices')->find($collaborator_id);

        $applices = $collaborator->applices;

        $polls = $collaborator->polls->map(function($poll) use ($applices) {
        
            $apply = collect($applices->first(function($apply) use($poll){
                if ($apply->poll_id == $poll->id) {

                    $apply->progress = $apply->progress();

                    return $apply;
                }
            }));
            
            if ($apply->isEmpty()) {
                $poll->apply = null;

                $poll->progress = 0;
            }else{
                $poll->progress = $apply->get('progress');
                
                $poll->apply = $apply->except(['poll', 'collaborator']);
            }
            
            return $poll;
        });

        $collaborator->polls = $polls;

        $collaborator = collect($collaborator);

        $collaborator = $collaborator->except(['applices']);

        return response()->json([
            "success" => true,
            "data" => $collaborator
        ], 200, [], JSON_NUMERIC_CHECK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Apps\Collaborator  $collaborator
     * @return \Illuminate\Http\Response
     */
    public function poll_forms($collaborator_id, $poll_id)
    {
        $collaborator = Collaborator::findOrFail($collaborator_id);

        $applices = $collaborator->applices;

        $poll = Poll::with('forms')->find($poll_id);

        $forms = $poll->forms->map(function($form) use ($applices){

            $apply = collect($applices->first(function($apply) use($form){
                if ($apply->form_id == $form->id) {

                    $apply->progress = $apply->progress();
                    
                    $apply->answersCount = $apply->answers->count();

                    return $apply;
                }
            }));

            $form->questionsCount = $form->questions->count();

            if ($apply->isEmpty()) {
                $form->apply = null;
                $form->progress = 0;                
            }else{
                $form->apply = $apply->except(['form', 'answers']);
                $form->progress = $apply->get('progress');
            }

            $form = collect($form)->except(['questions']);

            return $form;
        });

        $poll = collect($poll);

        $poll->put('forms', $forms);

        return response()->json([
            "success" => true,
            "data" => $poll
        ], 200, [], JSON_NUMERIC_CHECK);
    }
}
