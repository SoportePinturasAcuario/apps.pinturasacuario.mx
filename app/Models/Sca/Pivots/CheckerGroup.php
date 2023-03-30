<?php

namespace Apps\Models\Sca\Pivots;

use Illuminate\Database\Eloquent\Relations\Pivot;

class CheckerGroup extends Pivot
{
    protected $connection = 'sca';

    protected $table = 'checker_group';
}
