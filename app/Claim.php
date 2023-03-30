<?php

namespace Apps;

use Illuminate\Database\Eloquent\Model;

class Claim extends Model
{
	protected $connection = 'claims';

    protected $fillable =[
		'date',
		'lot',
		'bill',
		'folio',
		'title',
		'status_id',
		'posted_at',
		'customer_id',
		'description',
		'firebase_doc',
		'customer_email',
		'collaborator_id',
		'classification_id',
    ];

    public function files(){
        return $this->morphMany(FileClaim::class, 'fileable');
    }

    public function customer(){
    	return $this->belongsTo(Customer::class);
    }

    public function posts(){
    	return $this->morphMany(Post::class, 'postable');
    }   

    public function collaborator(){
    	return $this->belongsTo(Collaborator::class);
    } 

    public function classification(){
    	return $this->belongsTo(Classification::class);
    } 
}
