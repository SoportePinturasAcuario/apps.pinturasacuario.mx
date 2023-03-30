<?php

namespace Apps\Models\Inventory;

use Illuminate\Database\Eloquent\Model;
// Models
use Apps\Models\Inventory\Inventory;
use Apps\Models\Inventory\Status;
use Apps\Models\Inventory\Capture;
use Apps\Models\Inventory\InventoryType;

class Inventory extends Model
{
    protected $connection = 'inventory';

    protected $fillable = [
        'name',
        'end_date',
        'status_id',
        'start_date',
        'type_id',
        'search_by',
        'amount_by',
        'alter_capture_config'
    ];

    protected $casts = [
        'alter_capture_config' => 'boolean',
    ];

    public function teams() {
        return $this->belongsToMany(Team::class);
    }

    public function status() {
        return $this->belongsTo(Status::class);
    }

    public function captures() {
        return $this->hasMany(Capture::class);
    }    

    public function type() {
        return $this->belongsTo(InventoryType::class);
    }        
}
