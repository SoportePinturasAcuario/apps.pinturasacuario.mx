<?php

namespace Apps\Models\Inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

// Models
use Apps\Models\Inventory\Team;
use Apps\Models\Inventory\Inventory;

class Capture extends Model
{
    use SoftDeletes;

    protected $connection = 'inventory';

    protected $fillable = [
        'upc',
        'code',
        'amount',
        'team_id',
        'reviewed',
        'position',
        'registered',
        'box_capacity',
        'description',
        'inventory_id',
        'change_applied',
        'change_requested',
        'string_of_changes',
        'reason_for_deleted',
    ];

    protected $casts = [
        'id' => 'int',
        'amount' => 'int',
        'upc' => 'string',
        'team_id' => 'int',
        'code' => 'string',
        'position' => 'string',
        'inventory_id' => 'int',
        'reviewed' => 'boolean',
        'registered' => 'boolean',
        'box_capacity' => 'integer',
        'description' => 'string',
    ];

    public function team(){
        return $this->belongsTo(Team::class);
    }   

    public function inventory(){
        return $this->belongsTo(Inventory::class);
    }       
}
