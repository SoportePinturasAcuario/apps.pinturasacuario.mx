<?php

namespace Apps\Http\Controllers\Sca;

use Illuminate\Http\Request;
use Apps\Http\Controllers\Controller;

// Models
use Apps\Models\Sca\Checker;
use Apps\Models\Sca\Collaborator;
use Apps\Models\Sca\Descriptor;

// Requests
use Apps\Http\Requests\Sca\CheckerRequest;

class CheckerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $checkers = Checker::select(['id', 'name'])->with('groups:group_id,name')->get();

        return response()->json([
            'data' => $checkers
        ]);
    }

    /**
     * Display a listing of the resource with trashed.
     *
     * @return \Illuminate\Http\Response
     */
    public function index_with_trashed()
    {
        $checkers = Checker::withCount('groups')
        ->withTrashed()
        ->get();

        return response()->json([
            'data' => $checkers
        ]);
    }     

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CheckerRequest $request)
    {
        return response()->json([
            'data' => Checker::create($request->all()),
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Apps\Models\Sca\Checker  $checker
     * @return \Illuminate\Http\Response
     */
    public function show($checker_id)
    {
        $checker = Checker::with('groups')
        ->findOrFail($checker_id);

        $checker->makeVisible('key');

        return response()->json([
            'data' => $checker
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Apps\Models\Sca\Checker  $checker
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Checker $checker)
    {
        $checker->update($request->all());

        return response()->json([
            'data' => []
        ]);  
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Apps\Models\Sca\Checker  $checker
     * @return \Illuminate\Http\Response
     */
    public function destroy(Checker $checker)
    {
        $checker->delete();

        return response()->json([
            'data' => []
        ]); 
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param  \Apps\Models\Sca\Device  $checker
     * @return \Illuminate\Http\Response
     */
    public function restore($checker_id)
    {
        Checker::onlyTrashed()->find($checker_id)->restore();

        return response()->json([
            'data' => []
        ]); 
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @param  \Apps\Models\Sca\Checker  $checker
     * @return \Illuminate\Http\Response
     */
    public function handshake(Request $request, $checker_id)
    {
        $this->validate($request, [
            'key' => 'required',
        ]);

        $checker = Checker::findOrFail($checker_id);

        if ($checker->key != $request->get('key')) {
            return response()->json([
                'message' => "The given data was invalid.",
                'errors' => [
                    'key' => ['La "clave de instalación" es invalida.'],
                ]
            ], 422);
        }

        return response()->json([
            'success' => true,
        ]);  
    }

    public function initialize($checker_id) {
        $checker = Checker::with(['groups'])->findOrFail($checker_id);

        $checker->collaborators = [];

        foreach ($checker->groups as $key => $group) {
            $collaborator_ids = \DB::connection('sca')->table('collaborator_group')
            ->select('collaborator_id')
            ->where('group_id', $group->id)
            ->get();

            $collaborator_ids = array_column($collaborator_ids->toArray(), 'collaborator_id');

            $collaborators = Collaborator::with('descriptors')
            ->whereIn('id', $collaborator_ids)
            ->select('name', 'id', 'folio')
            ->get();

            $collaborators = $collaborators->reduce(function($acc, $collaborator) use($group) {
                if($collaborator->descriptors->count() > 0) {
                    
                    $collaborator->group_id = $group->id;

                    array_push($acc, $collaborator);
                }

                return $acc;
            }, []);

            $checker->collaborators = array_merge($checker->collaborators, $collaborators);
        }

        $checker = collect($checker)->except('groups', 'key', 'created_at', 'updated_at', 'deleted_at');

        return response()->json([
            'data' => $checker
        ]);
    }

    public function group_sync(Request $request, $checker_id) {

        $this->validate($request, [
            'groups' => 'array',
            'groups.*' => 'integer',
        ], [], ['groups' => '"grupos"']);

        $checker = Checker::findOrFail($checker_id);

        $checker->groups()->sync($request->get('groups'));

        return response()->json([
            'data' => $checker->groups
        ]);
    }  

    // Register
    public function register_photo_index(Request $request, $checker_id) {
        $current_date = date('Y-m-d');

        $deadline = date("Y-m-d", strtotime("$current_date- 7 days"));

        $checker = Checker::with([
            'registers' => function($query) use ($current_date, $deadline) {
                $query->where('registered_at', '>=', $deadline)->orderBy('registered_at', 'DESC');
            },
            'registers.type:id,name',
            'registers.event:id,name',
            'registers.checker:id,name',
            'registers.collaborator:id,folio,name,departamento_id',
            'registers.photo:id,name,path,fileable_id,fileable_type'
        ])->findOrFail($checker_id);
        
       
        return response()->json([
            'data' => $checker->registers,
        ]);
    }  
}
