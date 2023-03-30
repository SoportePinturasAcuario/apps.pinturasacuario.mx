<?php

namespace Apps\Models\Sca;

use Illuminate\Database\Eloquent\Model;

// Models
use Apps\Models\Sca\Collaborator;

class BranchOffice extends Model
{
    protected $connection = "mysql";

    protected $table = "branchoffices";

    public function collaborators() {
        return $this->hasMany(collaborator::class, 'sucursal_id', 'id');
    }
}
