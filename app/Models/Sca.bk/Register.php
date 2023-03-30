<?php

namespace Apps\Models\Sca;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

// Models
use Apps\Models\Sca\Collaborator;
use Apps\Models\Sca\File;
use Apps\Models\Sca\Event;
use Apps\Models\Sca\Checker;
use Apps\Models\Sca\RegisterType;


class Register extends Model
{
    use SoftDeletes;

    protected $connection = 'sca';

    protected $fillable = [
        'collaborator_id', 
        'event_id',
        'type_id', 
        'group_id', 
        'checker_id', 
        'registered_at'
    ];

    public function collaborator() {
        return $this->belongsTo(Collaborator::class);
    }  

    public function type() {
        return $this->belongsTo(RegisterType::class);
    }  

    public function event() {
        return $this->belongsTo(Event::class);
    }

    public function photo() {
        return $this->morphOne(File::class, 'fileable');
    }  

    public function checker() {
        return $this->belongsTo(Checker::class);
    }  
}
