<?php

namespace Apps\Models\Pivots;

use Illuminate\Database\Eloquent\Relations\Pivot;

class CollaboratorExtension extends Pivot
{
    protected $connection = "ti";
}
