<?php

namespace Apps;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Plist extends Model
{
    use SoftDeletes;

	protected $table = "lists";

    protected $fillable = [
    	"id",
		"transporte_nombre",
		"transporte_nplaca",
		"transporte_gps",
		"transporte_ngps",
		"transporte_tunidad",
		"transporte_fletera",
		"transporte_ntcirculacion",
		"ruta",
		"flete_costo",
		"transportista_nombre",
		"transportista_nlicencia",
		"transportista_nseguro",
		"numero_plist",
		"numero_sello",
		"nombre_cargado_por",
		"nombre_inspeccionado_por",
		"hora_llegada",
		"hora_salida",
		"estancia",
		"bills",
		"mercancia_valor",
		"mercancia_peso",
		"embarque_costo",
		"cliente_nombre",
		// "customer_id",
		"customers",
		"hora_cita",
		"status_id",
    ];

    public function photos(){
    	return $this->morphMany(File::class, 'fileable');
    }

    public function customer(){
    	return $this->belongsTo(Customer::class);
    }     

    public function totalFacturas(){
        $total = 0;

        foreach (json_decode($this->bills) as $key => $bill) {
        	$monto = str_replace([",", "."], "", $bill->monto);

            $total = $total + $monto;
        }

        $total = "$" . number_format($total, 2, '.', ',');

        return $total;
    }
}
