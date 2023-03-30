<?php

namespace Apps;

use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    protected $connection = 'mysql';
    protected $table = 'roles';
    
    protected $fillable = ['name'];
}
