<?php

namespace Apps\Models\Inventory;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $connection = 'inventory';

    protected $fillable = ['code', 'description', 'idnetsuite', 'upc', 'box_capacity'];

    protected $casts = [
        'id' => 'int',
        'code' => 'string',
        'description' => 'string',
        'idnetsuite' => 'string',
    ];      
}
