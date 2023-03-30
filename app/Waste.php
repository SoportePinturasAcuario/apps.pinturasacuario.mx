<?php

namespace Apps;

use Illuminate\Database\Eloquent\Model;

class Waste extends Model
{
    protected $fillable = [
		'id',
		'ordencompra',
		'factura',
		'para',
		'de',
		'comentarios',
		'status_id',
		'items',
		'type'
    ];

    public function from(){
        return $this->belongsTo(Collaborator::class, 'de', 'id');
    }

    public function to(){
        return $this->belongsTo(Collaborator::class, 'para', 'id');
    }    

    public function photos(){
    	return $this->morphMany(File::class, 'fileable');
    }

    public function approvals(){
    	return $this->morphMany(Approval::class, 'approvalable');
    }

    public function isApproved(){
    	$approvals = $this->approvals;

    	if ($approvals->count() == 0) {
    		return false;
    	}	    	

    	$approvalsApproved = $approvals->filter(function($approval, $key){
    		return $approval->status_id == 2;
    	});

    	if ($approvals->count() == $approvalsApproved->count()) {
    		return true;
    	}

    	return false;
    }

    public function wasReviewed(){
    	$approvals = $this->approvals;

    	if ($approvals->count() == 0) {
    		return false;
    	}

    	$approvalsReviewed = $approvals->filter(function($approval, $key){
    		return $approval->status_id != 1;
    	});

    	if ($approvals->count() == $approvalsReviewed->count()) {
    		return true;
    	}

    	return false;
    }    
}
