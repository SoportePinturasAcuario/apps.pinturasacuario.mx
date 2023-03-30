<?php

namespace Apps;

use Illuminate\Database\Eloquent\Model;

class Option extends Model

{
	protected $connection = 'nom';

    protected $fillable = ["id", "option"];

    public function question(){
		return $this->belongsTo(Question::class);
    }	    
}
