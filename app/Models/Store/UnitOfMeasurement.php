<?php

namespace Apps\Models\Store;

use Illuminate\Database\Eloquent\Model;

class UnitOfMeasurement extends Model
{
    protected $connection = 'store';

    protected $table = 'measurement_units';

    protected $fillable = ['id', 'name'];
}
