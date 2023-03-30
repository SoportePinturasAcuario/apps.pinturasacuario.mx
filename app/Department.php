<?php

namespace Apps;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{	
    protected $connection = "mysql";   
    
	protected $fillable = ["id", "nombre"];
}
