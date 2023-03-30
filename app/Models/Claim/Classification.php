<?php

namespace Apps\Models\Claim;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Apps\Models\Claim\Claim;

class Classification extends Model
{
    use SoftDeletes;
    protected $connection = 'claims';

    protected $fillable = ['name'];
    
    public function claims(){
    	return $this->hasMany(Claim::class);
    }
}
