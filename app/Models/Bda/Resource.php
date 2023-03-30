<?php

namespace Apps\Models\Bda;

use Illuminate\Database\Eloquent\Model;

// Models
use Apps\Models\Bda\File;

class Resource extends Model
{
    protected $fillable = ['name', 'description', 'module_id', 'type_id', 'index'];

    protected $connection = 'bda';

    protected $casts = [
        'description' => 'string'
    ];    

    public function file() {
        return $this->morphOne(File::class, 'fileable');
    }

    public function files() {
        return $this->morphMany(File::class, 'fileable');
    }    
}
