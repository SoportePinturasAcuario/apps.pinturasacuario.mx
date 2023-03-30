<?php

namespace Apps\Models\Scanning;

use Illuminate\Database\Eloquent\Model;

class Article extends Model {
    protected $connection = "scanning";

    protected $fillable = ['id', 'code', 'description', 'upc'];
}