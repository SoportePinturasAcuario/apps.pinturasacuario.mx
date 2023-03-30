<?php

namespace Apps\Models\Sca\Pivots;

use Illuminate\Database\Eloquent\Relations\Pivot;

class CollaboratorGroup extends Pivot
{
    protected $connection = 'sca';

    protected $table = 'collaborator_group';
}
