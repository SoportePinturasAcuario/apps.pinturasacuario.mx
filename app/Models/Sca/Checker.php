<?php

namespace Apps\Models\Sca;
use Illuminate\Database\Eloquent\SoftDeletes;


use Illuminate\Database\Eloquent\Model;

// Models
use Apps\BranchOffice;
use Apps\Models\Sca\Group;
use Apps\Models\Sca\Register;

class Checker extends Model
{
    use SoftDeletes;

    protected $connection = 'sca';
    
    protected $fillable = ['name', 'key', 'configuration'];

    protected $casts = [
        'configuration' => 'json'
    ];

    protected $hidden = ['key'];

    public function groups() {
        return $this->belongsToMany(Group::class, 'checker_group', 'checker_id', 'group_id');
    }

    public function registers() {
        return $this->hasMany(Register::class);
    }
}
