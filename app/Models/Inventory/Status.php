<?php

namespace Apps\Models\Inventory;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    protected $connection = 'inventory';

    protected $fillable = ['id', 'name', 'color'];   
}
