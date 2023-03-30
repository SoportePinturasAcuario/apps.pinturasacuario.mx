<?php

namespace Apps\Http\Controllers\Sca\Reports;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Apps\Http\Controllers\Controller;
use Carbon\Carbon;

// Models
use Apps\Models\Sca\Group;
use Apps\Models\Sca\Checker;
use Apps\Models\Sca\Register;
use Apps\Models\Sca\Collaborator;

class IntidentialController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->validate($request, [
            'checker_id' => 'required|numeric|exists:sca.checkers,id',
            'intidential_id' => 'required|integer|between:1,2',
            'date_start' => 'required|date',
            'date_end' => 'nullable|date',
        ],[],[
            'checker_id' => '"checador"',
            'intidential_id' => '"típo de incidencia"',
            'date_start' => '"fecha inicio"',
            'date_end' => '"fecha final"',
        ]);

        $data = DB::connection('sca')->select("
            SELECT `collaborator_group`.`collaborator_id` AS 'id'
            FROM `collaborator_group`
            JOIN `groups` ON `groups`.`id` = `collaborator_group`.`group_id`
            JOIN `checker_group` ON `checker_group`.`group_id` = `groups`.`id`
            WHERE `checker_group`.`checker_id` = " . $request->get('checker_id')
        );

        $collaborator_ids = array_column($data, 'id');

        $collaborators = Collaborator::select(['folio', 'id', 'name'])->whereIn('id', $collaborator_ids)->get();

        $dates = [$request->get('date_start'), $request->has('date_end') ? $request->get('date_end') : $request->get('date_start')];

        $registers = Register::where([['checker_id', $request->get('checker_id')]])
        ->whereBetween('registered_at', $dates)
        ->with(['collaborator:id,name', 'type:id,name'])
        ->orderBy('id', 'ASC')
        ->get();

        $dates = [$request->get('date_start')];

        for ($i=0; $i < date_diff(date_create($request->get('date_start')), date_create($request->get('date_end')))->days; $i++) { 
            array_push($dates, Carbon::parse($dates[$i])->addDay()->toDateString());
        }

        $collaborators = $collaborators->map(function($collaborator) use ($request, $registers, $dates) {

            $intidentials = [];

            foreach ($dates as $key => $date) {
                $register = $registers->first(function($register) use ($collaborator, $request, $date) {
                    
                    $registered_at = explode(" ", $register->registered_at);

                    if ($register->collaborator_id == $collaborator->id 
                        && $register->type_id == 1 
                        && $registered_at[0] == $date) {
                        return $register;
                    }
                });

                $intidentials[$date] = $register ? 'Asistencia' : 'Falta';
            }

            $collaborator->intidentials = $intidentials;

            return $collaborator;
        });

        return response()->json([
            'data' => $collaborators,
        ]);
    }
}
