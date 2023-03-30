<?php

namespace Apps;

use Illuminate\Database\Eloquent\Model;

class Poll extends Model
{
	protected $connection = 'nom';
	
    protected $table = "polls";

    protected $fillable = ['id', 'name', 'description'];

 //    protected $fillable = [
 //    	'sexo',
	// 	'edad',
	// 	'ec',
	// 	'rturno',
	// 	'ocupacion',
	// 	'nexperiencia',
	// 	'meses',
	// 	'description',
	// 	'collaborator_id',
	// 	'departamento_id',
	// 	'tipopuesto_id',
	// 	'tipocontratacion_id',
	// 	'tipopersonal_id',
	// 	'tipojornada_id',
	// 	'tipoacademico_id',
	// 	'status_id',
	// 	'n_si',
	// 	'n_no'
	// ];


	public function collaborators(){
		return $this->belongsToMany(Collaborator::class);
	}

	public function estadoCivil(){
		return $this->hasOne(Tipoestadocivil::class, "id", "ec", "tipoestadosciviles");

		// 1) Modelo relacionado
		// 2) Lavve primaria del modelo relacionado	
		// 3) Llave foranea de este modelo 'Poll'
		// 4) Nombre de la tabla relacionada
	}

	public function departamento(){
		return $this->hasOne(Departamento::class, "id", "departamento_id", "departamentos");
	}

	public function tipoContratacion(){
		return $this->hasOne(Tipocontratacion::class, "id", "tipocontratacion_id", "tipocontrataciones");
	}

	public function tipoPuesto(){
		return $this->hasOne(Tipopuesto::class, "id", "tipopuesto_id", "tipopuesto");
	}

	public function tipoPersonal(){
		return $this->hasOne(Tipopersonal::class, "id", "tipopersonal_id", "tipopersonal");
	}

	public function tipoJornada(){
		return $this->hasOne(Tipojornada::class, "id", "tipojornada_id", "tipojornadas");
	}

	public function forms(){
		return $this->hasMany(Form::class);
	}

	public function applices(){
		return $this->hasMany(Apply::class);
	}				

	public function applies(){
		return $this->hasMany(Apply::class);
	}				
}