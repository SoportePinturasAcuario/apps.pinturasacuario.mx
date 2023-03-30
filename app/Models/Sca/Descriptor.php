<?php

namespace Apps\Models\Sca;

use Illuminate\Database\Eloquent\Model;

// Models
use Apps\Collaborator;

class Descriptor extends Model
{
    protected $connection = 'sca';

    protected $fillable = ['descriptor', 'collaborator_id'];

    protected $casts = [
        'descriptor' => 'Array'
    ];

    public function collaborator() {
        return $this->belongsTo(Collaborator::class);
    }    
}
