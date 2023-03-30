<?php

namespace Apps\Models\Inventory;
use Illuminate\Database\Eloquent\Model;

// Models
use Apps\Models\Inventory\Capturist;
use Apps\Models\Inventory\Capture;
use Apps\Models\Inventory\Inventory;

class Team extends Model
{
    protected $connection = 'inventory';

    protected $fillable = ['name'];

    public function capturists() {
        return $this->belongsToMany(Capturist::class);
    }

    public function captures() {
        return $this->hasMany(Capture::class);
    }

    public function inventories() {
        return $this->belongsToMany(Inventory::class);
    }    
}
