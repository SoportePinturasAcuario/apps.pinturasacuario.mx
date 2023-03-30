<?php

namespace Apps;

use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{
    protected $connection = 'checklists';
    protected $table = 'facturas';
    protected $fillable = ['folio', 'monto', 'checklist_id', 'customer_id'];

    public function customer(){
    	return $this->belongsTo(Customer::class);
    }
}
