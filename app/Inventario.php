<?php

namespace Apps;

use Illuminate\Database\Eloquent\Model;

class Inventario extends Model
{
    protected $table = 'inventario';

    protected $fillable = [
		'um',
		'factor',
		'codigo',
		'familia',
		'descripcion',
		// 'stock',
    ];
}
