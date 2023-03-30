<?php

namespace Apps\Models\Store;

use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    protected $connection = "store";

    protected $fillable  = ['name'];
}
