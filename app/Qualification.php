<?php

namespace Apps;

use Illuminate\Database\Eloquent\Model;

class Qualification extends Model
{
	protected $connection = "nom";
    protected $fillable = ["scor", 'level', "apply_id"];

    public function apply(){
    	return $this->belongsTo(Apply::class);
    }
}
