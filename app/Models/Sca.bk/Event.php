<?php

namespace Apps\Models\Sca;

use Illuminate\Database\Eloquent\Model;

// Models
use Apps\Models\Sca\RegisterType;

class Event extends Model
{
    protected $connection = 'sca';

    protected $fillable = ['id', 'name', 'color'];

    public function registerTypes() {
        return $this->hasMany(RegisterType::class, 'event_id', 'id');
    }
}
