<?php

namespace Apps;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
	protected $connection = 'mysql';
    protected $fillable = ['id', 'name'];
}
