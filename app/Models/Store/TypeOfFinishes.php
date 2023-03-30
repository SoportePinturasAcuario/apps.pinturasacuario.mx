<?php

namespace Apps\Models\Store;

use Illuminate\Database\Eloquent\Model;

class TypeOfFinishes extends Model
{
    protected $connection = 'store';

    protected $fillable = ['id', 'name'];
}
