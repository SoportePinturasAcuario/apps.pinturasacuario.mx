<?php

namespace Apps;

use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
	protected $connection = 'nom';

    protected $fillable = ['id', 'question', 'form_id'];

    public function questions() {
		return $this->hasMany(Question::class);
    }

    public function apply() {
		return $this->hasOne(Apply::class);
    }

    public function applies() {
		return $this->hasMany(Apply::class);
    }  

    public function poll() {
        return $this->belongsTo(Poll::class);
    }  
}