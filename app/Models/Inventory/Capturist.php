<?php

namespace Apps\Models\Inventory;

use Illuminate\Database\Eloquent\Model;

// Models
use Apps\Models\Inventory\Team;

class Capturist extends Model {
    protected $connection = 'inventory';

    protected $fillable = ['name'];

    public function teams() {
        return $this->belongsToMany(Team::class);
    }
}
