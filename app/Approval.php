<?php

namespace Apps;

use Illuminate\Database\Eloquent\Model;

class Approval extends Model
{
    protected $fillable = ['approving_user_id', 'status_id', 'details'];

    public function authorizingUser(){
    	return $this->belongsTo(User::class, 'approving_user_id', 'id');
    } 

    public function waste(){
    	return $this->morphTo(Waste::class, 'approvalable_type', 'approvalable_id');
    }
}
