<?php

namespace Apps;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Classification extends Model
{
    use SoftDeletes;
    protected $connection = 'claims';
    // protected $table = 'fleteras';

    protected $fillable = ['name'];
    
    public function claims(){
    	return $this->hasMany(Claim::class);
    }
}
