<?php

namespace Apps;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
  protected $connection = 'nom';
  
	protected $fillable = ['id', 'question', 'form_id'];

    public function form(){
		return $this->belongsTo(Form::class);
    }	

    public function options(){
		return $this->belongsToMany(Option::class)->withPivot('option_id', 'question_id', 'form_id', 'value', 'question_index');
    }    

   	public function answer(){
   		return $this->hasOne(Answer::class);
   	}
}
