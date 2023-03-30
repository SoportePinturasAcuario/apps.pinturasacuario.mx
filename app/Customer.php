<?php

namespace Apps;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model {
    
	protected $connection = 'mysql';
	
    protected $fillable = [
    	'rfc',
    	'id',
    	'rs',
    	'name',
    	'folio',
    	'active',
        'discount',
        'idnetsuite',
    	'created_by',
    ];
}
