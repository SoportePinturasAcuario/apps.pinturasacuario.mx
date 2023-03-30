<?php

namespace Apps;

use Illuminate\Database\Eloquent\Model;

class Article extends Model {

	protected $connection = 'mysql';

    protected $fillable = ['code', 'description', 'unit_of_measurement', 'idnetsuite'];
}
