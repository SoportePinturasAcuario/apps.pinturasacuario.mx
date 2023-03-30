<?php

namespace Apps\Models\Sca;

use Illuminate\Database\Eloquent\Model;

// Models
use Apps\Models\Sca\Event;

class RegisterType extends Model
{
    protected $connection = 'sca';

    protected $table = 'register_types';

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
