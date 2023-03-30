<?php

namespace Apps;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
	protected $connection = 'nom';

	protected $fillable = ["question_id", "option_id", "apply_id"];
    
    public function option(){
    	return $this->belongsTo(Option::class);
    }

    public function question(){
    	return $this->belongsTo(Question::class);
    }    
}
