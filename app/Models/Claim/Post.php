<?php

namespace Apps\Models\Claim;

use Illuminate\Database\Eloquent\Model;
use Apps\Collaborator;

class Post extends Model
{
	protected $connection = 'claims';
	
    protected $fillable = ['title', 'post', 'type' ,'type_id', 'collaborator_id', 'representative_status_id'];

    public function collaborator(){
    	return $this->belongsTo(Collaborator::class);
    }

    public function files(){
        return $this->morphMany(FileClaim::class, 'fileable');    	
    }    
}
