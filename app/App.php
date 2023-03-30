<?php

namespace Apps;

use Illuminate\Database\Eloquent\Model;

class App extends Model
{
    protected $connection = 'mysql';
    protected $table = 'apps';
    
    protected $fillable = ['name', 'icon', 'url'];
}
