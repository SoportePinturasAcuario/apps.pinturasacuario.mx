<?php

namespace Apps;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Fletera extends Model
{
    use SoftDeletes;
    protected $connection = 'checklists';
    protected $table = 'fleteras';

    protected $fillable = ['name'];
    
    public function checklists(){
    	return $this->hasMany(Checklist::class);
    }
}
