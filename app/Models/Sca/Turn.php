<?php

namespace Apps\Models\Sca;

use Illuminate\Database\Eloquent\Model;

// Models
use Apps\Models\Sca\Collaborator;

class Turn extends Model
{
    protected $connection = 'sca';

    protected $fillable = ['id', 'name', 'note'];

    public function events() {
        return $this->belongsToMany(Event::class)->withPivot([
            'duration',
            'start',
            'end',
            'start_strict_before',
            'start_strict_after',
            'duration_strict',
            'end_strict_before',
            'end_strict_after',
        ]);
    }

    public function collaborators() {
        return $this->belongsToMany(Collaborator::class)->withPivot(['start', 'end']);
    }
}
