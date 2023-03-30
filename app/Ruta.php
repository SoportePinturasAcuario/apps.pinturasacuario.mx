<?php

namespace Apps;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ruta extends Model
{
    use SoftDeletes;
    protected $connection = 'checklists';
    protected $table = 'rutas';
    protected $fillable = ['name'];

    public function checklists(){
    	return $this->hasMany(Checklist::class);
    }    
}
