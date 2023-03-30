<?php

namespace Apps\Models\Bda;

use Illuminate\Database\Eloquent\Model;

// Models
use Apps\Models\Bda\Course;
use Apps\Collaborator AS CollaboratorAbstract;

class Collaborator extends CollaboratorAbstract
{
        public function courses()
    {
        return $this->belongsToMany(Course::class);
    }
}
