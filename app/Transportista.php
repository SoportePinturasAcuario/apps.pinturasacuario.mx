<?php

namespace Apps;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transportista extends Model
{
    use SoftDeletes;
    protected $connection = 'checklists';
    protected $table = 'transportistas';    
    protected $fillable = ['name'];

    public function checklists(){
    	return $this->hasMany(Checklist::class);
    }
}
