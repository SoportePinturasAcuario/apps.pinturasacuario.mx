<?php

namespace Apps\Models\Sca;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

// Models
use Apps\Department;
use Apps\BranchOffice;
use Apps\Models\Sca\Register;
use Apps\Models\Sca\Group;
use Apps\Models\Sca\Descriptor;
use Apps\Models\Sca\Turn;

class Collaborator extends Model
{
    use SoftDeletes;
    
    protected $connection = 'rh';

    protected $fillable = ['name', 'sexo', 'departamento_id', 'sucursal_id', 'folio'];

    protected $casts = [
        'gender_id' => 'int',
        'department_id' => 'int',
        'branch_office_id' => 'int',
    ];

    public function department() {
        return $this->belongsTo(Department::class, 'departamento_id', 'id');
    }

    public function branch_office() {
        return $this->belongsTo(BranchOffice::class, 'sucursal_id', 'id');
    }  

    public function gender() {
        return $this->belongsTo(Gender::class);
    } 

    public function descriptors() {
        return $this->hasMany(Descriptor::class);
    }     

    public function registers() {
        return $this->hasMany(Register::class);
    }   

    public function groups() {
        return $this->belongsToMany(Group::class);
    }

    public function turns() {
        return $this->belongsToMany(Turn::class)->withPivot(['start', 'end', 'created_at']);
    }     

    public function turn() {
        return $this->belongsToMany(Turn::class)->withPivot(['start', 'end']);
    }     

    public function getEvents($registers) {
        if ($this->turns->count() == 0) {
            return [];
        }

        $events = $this->turns->first()->events;

        return $events->map(function($event) use($registers) {
            $event = collect($event);

            $items = ['start', 'end'];

            foreach ($event['register_types'] as $key => $register_type) {

                $register = $registers->first(function($register) use($event, $register_type) {
                    return $register->event_id == $event['id'] && $register->type_id == $register_type['id'];
                });

                $event->put($items[$key], $register ? $register : null);

                // $event->put("$items[$key]_register", $register ? $register : null);
            }

            return $event;
        });
    }
}




        // $this->turn = [
        //     'id' => 1,
        //     'name' => 'Turno administrativo',
        //     'events' => [
        //         [
        //             'id' => 1,
        //             'name' => 'Jornada',
        //             'color' => '#1D9C43',
        //             'register_types' => [
        //                 [
        //                     'id' => 1,
        //                     'event_id' => 1,
        //                     'name' => 'Entrada',
        //                     'color' => '#1D9C43',
        //                 ],
                        
        //                 [
        //                     'id' => 2,
        //                     'event_id' => 1,
        //                     'name' => 'Salida',
        //                     'color' => '#404040',
        //                 ],
        //             ],

        //             'pivot' => [
        //                 'duration' => 9.5,
        //                 'start' => '08:00',
        //                 'end' => '17:30',

        //                 'start_strict_after' => true,
        //                 'start_strict_before' => true,
                        
        //                 'duration_strict' => false,

        //                 'end_strict_after' => false,
        //                 'end_strict_before' => true,
        //             ]
        //         ],
        //         [
        //             'id' => 2,
        //             'name' => 'Comida',
        //             'color' => 'orange',
        //             'register_types' => [
        //                 [
        //                     'id' => 3,
        //                     'event_id' => 2,
        //                     'name' => 'Salida',
        //                     'color' => '#404040',
        //                 ],
        //                 [
        //                     'id' => 4,
        //                     'event_id' => 2,
        //                     'name' => 'Regreso',
        //                     'color' => '#1D9C43',
        //                 ],
        //             ],
        //             'pivot' => [
        //                 'duration' => 1.0,
        //                 'start' => '13:00',
        //                 'end' => '14:00',

        //                 'start_strict_after' => false,
        //                 'start_strict_before' => false,
                        
        //                 'duration_strict' => true,

        //                 'end_strict_after' => false,
        //                 'end_strict_before' => false,
        //             ]
        //         ],
        //     ]
        // ];

        // $events = collect($this['turn']['events']);