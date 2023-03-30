<?php

namespace Apps\Models\Sca;

use Illuminate\Database\Eloquent\Model;

// Models
use Apps\Collaborator;
use Apps\Models\Sca\Checker;

// Pivots
use Apps\Models\Sca\Pivots\CollaboratorGroup;

class Group extends Model
{
    protected $connection = 'sca';

    protected $fillable = ['name'];

    public function checkers() {
        return $this->belongsToMany(Checker::class, 'checker_group', 'group_id', 'checker_id');
    }

    public function collaborators() {
        return $this->belongsToMany(Collaborator::class);
    }
}
