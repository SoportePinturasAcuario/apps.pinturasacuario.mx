<?php

namespace Apps;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
	protected $connection = 'mysql';

    protected $fillable = ["name","path","type", "fileable_id", "extension"];

}
