<?php

namespace Apps;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $table = "items";

    protected $fillable = [
		'codigo',
		'descripcion',
		'rack',
		'posicion',
		'equipo',
		'um',
		'factor',
		'familia',
		'detalles',
		'cantidad',
		'cambios',
		'registrado',
		'revisado',
	];

	protected $casts = [
        'equipo' => 'int',
        'posicion' => 'string',
        'factor' => 'int',
        'cantidad' => 'int',
        'registrado' => 'int',
        'revisado' => 'int',   
    ];	
}
