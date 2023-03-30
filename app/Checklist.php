<?php

namespace Apps;

use Illuminate\Database\Eloquent\Model;

class Checklist extends Model
{
    protected $connection = "checklists";

    protected $fillable = [
    	'id',
		'tcirculacion',
		'placa',
		'nsello',
		'nconductor',
		'nlicencia',
		'peso',
		'clientes',
		
		'nseguro',
		'hcita',
		'gps',
		'ngps',
		'contactogps',
		'npackinglist',
		'fcosto',


		'hllegada',
		'hsalida',
		'estancia',
		'ruta_id',
		'unidad_id',
		'fletera_id',
		'cargador_id',
		'inspector_id',
		'transportista_id',
		'status_id',
    ];

    public function ruta(){
    	return $this->belongsTo(Ruta::class)->withTrashed();
    }

    public function fletera(){
    	return $this->belongsTo(Fletera::class)->withTrashed();
    }    

    public function cargador(){
    	return $this->belongsTo(Inspector::class)->withTrashed();
    }

    public function inspector(){
    	return $this->belongsTo(Inspector::class)->withTrashed();
    } 

    public function transportista(){
    	return $this->belongsTo(Transportista::class)->withTrashed();
    }

    public function unidad(){
    	return $this->belongsTo(Unidad::class)->withTrashed();
    }    

    public function facturas(){
    	return $this->hasMany(Factura::class);
    }    

    public function photos(){
		return $this->hasMany(CheckListFile::class);
    }

    public function total(){
    	$total = 0;

    	foreach ($this->facturas as $key => $factura) {
    		$total += $factura->monto;
    	}

    	return $total;
    }
}
