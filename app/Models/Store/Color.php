<?php

namespace Apps\Models\Store;

use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    protected $connection = 'store';


    protected $fillable = ['name', 'hex'];
}
