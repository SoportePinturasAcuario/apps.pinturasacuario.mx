<?php

namespace Apps\Http\Controllers\Sca;

use Illuminate\Http\Request;
use Apps\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

// Models
use Apps\Models\Sca\Register;
use Apps\Models\Sca\Checker;
use Apps\Models\Sca\Collaborator;
use Apps\Models\Sca\BranchOffice;
use Apps\Models\Sca\Pivots\CollaboratorGroup;

// Requests
use Apps\Http\Requests\Sca\RegisterRequest;

class RegisterController extends Controller
{
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index()
    {
        $registers = Register::with(['collaborator.descriptors'])->get();

        return response()->json([
            'data' => $registers,
        ]);
    }

    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function store(RegisterRequest $request)
    {
        $register = Register::create($request->all());

        $register->collaborator;

        return response()->json([
            'data' => $register,
        ]);
    }

    /**
    * Display the specified resource.
    *
    * @param  \Apps\Models\Sca\Register  $register
    * @return \Illuminate\Http\Response
    */
    public function show($register_id) {
        $register = Register::with([
            'type:id,name,color',
            'event:id,name,color',
            'checker:id,name',
            'collaborator.groups',
            'collaborator:id,folio,name',
            'photo:id,name,path,fileable_id,fileable_type'
        ])->findOrFail($register_id);

        return response()->json([
            'data' => $register,
        ]);
    }

    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \Apps\Models\Sca\Register  $register
    * @return \Illuminate\Http\Response
    */
    public function update(RegisterRequest $request, $register_id) {

        $register = Register::findOrFail($register_id);

        $register->update($request->all());

        $register->type;

        return response()->json([
            'data' => $register,
        ]);        
    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  \Apps\Models\Sca\Register  $register
    * @return \Illuminate\Http\Response
    */
    public function destroy($register_id)
    {
        $register = Register::findOrFail($register_id);

        $register->delete();

        return response()->json([
            'data' => [],
        ]);
    }

    public function store_massive(Request $request)
    {
        $this->validate($request, [
            'registers' => 'required|array|min:1|max:5000',
            'registers.*.collaborator_id' => 'required|numeric',
            'registers.*.type_id' => 'required|numeric',
            'registers.*.registered_at' => 'required|date',
            'registers.*.checker_id' => 'required|numeric',
            'registers.*.group_id' => 'required|numeric',
        ]);

        $created_at = Carbon::now();

        $registers = array_map(function($item) use($created_at)  {
            $item['created_at'] = $created_at;

            return $item;
        }, $request->get('registers'));

        Register::insert($registers);

        return response()->json([
            'message' => "Success",
            'data' => count($registers),
        ]);
    }

    // Photo
    public function photo_store(Request $request, $register_id) {
        
        $register = Register::findOrFail($register_id);

        if ($register->collaborator_id != 2481) {

            $photo = $request->get('photo');

            $data = base64_decode(substr($photo, strpos($photo, ',') + 1));

            $name = $register_id . "_" . time() . ".png";

            $path = "/storage/sca/record/$name";

            Storage::disk('local')->put($path,  $data);

            $register->photo()->create([
                'name' => $name, 
                'path' => $path, 
                'type_id' => 1,
            ]);
        }

        return response()->json([
            'data' => [],
        ]);
    }

    // Query
    public function query(Request $request) {
        $this->validate($request, [
            'date_end' => 'required|date',
            'date_start' => 'required|date',
            'department_id' => 'nullable|numeric',
            'collaborator_id' => 'nullable|numeric',
            'branch_office_id' => 'nullable|numeric',
        ], [], [
            'date_start' => '"Fecha inicio"',
            'date_end' => '"Fecha final"',
            'branch_office_id' => '"Sucursal"',
        ]);


        if ($request->has('collaborator_id')) {
            $collaborator_ids = [$request->get('collaborator_id')];
        } else {
            $where = [];

            if ($request->has('department_id')) {
                $where['departamento_id'] = $request->get('department_id');
            }

            if ($request->has('branch_office_id')) {
                $where['sucursal_id'] = $request->get('branch_office_id');
            }

            $collaborator_ids = Collaborator::select(['id'])->where($where)->get()->pluck('id');
        }

        $data = Register::with([
            'event:id,name,color',
            'type:id,name,color',
            'checker:id,name',
            'collaborator:id,folio,name',
            'collaborator.branch_office:id,name',
            'collaborator.department:id,nombre',
            'collaborator:id,folio,name,sucursal_id,departamento_id',
            'photo:fileable_id,fileable_type,id,path',
        ])
        ->whereIn('collaborator_id', $collaborator_ids)
        ->whereBetween('registered_at', ["{$request->get('date_start')} 00:00:00", "{$request->get('date_end')} 23:59:59"])
        ->orderBy('registered_at', 'DESC')
        ->get();        

        return response()->json([
            'data' => $data,
        ]);
    }

    public function report(Request $request) {
        $this->validate($request, [
            'date_end' => 'required|date',
            'date_start' => 'required|date',
            'with_trashed' => 'nullable|boolean',
            'department_id' => 'nullable|numeric',            
            'branch_office_id' => 'nullable|numeric',
        ], [], [
            'date_end' => '"fecha final"',
            'date_start' => '"fecha inicio"',
            'with_trashed' => '"incluir bajas"',
            'branch_office_id' => '"sucursal"',
            'department_id' => '"departamento"',
        ]);

        $where = [];

        $with_trashed = $request->has('with_trashed') ? $request->get('with_trashed') : false;

        if ($request->has('branch_office_id')) {
            $where = array_merge($where, ['sucursal_id' => $request->get('branch_office_id')]);
        }

        if ($request->has('department_id')) {
            $where = array_merge($where, ['departamento_id' => $request->get('department_id')]);
        }

        $date_start = $request->get('date_start');

        $date_end = $request->get('date_end');

        $dates = [$date_start];

        while (end($dates) < $date_end) {
            array_push($dates, date("Y-m-d", strtotime(end($dates) . "+ 1 days")));
        }

        $collaborators = Collaborator::select(['id', 'name', 'folio', 'sucursal_id', 'departamento_id', 'deleted_at'])
        ->with(['department:id,nombre', 'branch_office:id,name'])
        ->where($where)
        ->withTrashed($with_trashed)
        ->get();

        $registers = Register::with(['event:id,name,color', 'event.registerTypes:id,name,event_id,type'])
        ->whereIn('collaborator_id', $collaborators->map(function($collaborator) {return $collaborator->id; })->toArray())
        ->whereBetween('registered_at', ["$date_start 00:00:00", "$date_end 23:59:59"])
        ->get();

        $dates = array_map(function($date) use($registers) {
            return [
                'date' => $date,
                'events' => $registers->whereBetween('registered_at', ["$date 00:00:00", "$date 59:59:59"])->map(function($register) {
                    return $register->event;
                })->unique()->sortBy('id')->values()->toArray()
            ];
        }, $dates);

        return response()->json([
            'data' => $collaborators->map(function($collaborator) use ($registers, $dates) {
                $collaborator_registers = $registers->where('collaborator_id', $collaborator->id);

                $collaborator->dates = array_map(function($item) use($collaborator, $collaborator_registers) {
                    $date = $item['date'];

                    return [
                        'date' => $date,
                        'events' => $collaborator->getEvents(
                            $item['events'],
                            $collaborator_registers->whereBetween('registered_at', ["$date 00:00:00", "$date 59:59:59"])
                        )
                    ];
                }, $dates);

                return $collaborator;
            }),
        ]);       
    } 
}
