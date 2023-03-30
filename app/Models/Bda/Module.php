<?php

namespace Apps\Models\Bda;

use Illuminate\Database\Eloquent\Model;

// Modles
use Apps\Models\Bda\Resource;

class Module extends Model
{
    protected $connection = "bda";

    protected $fillable = ['name', 'course_id', 'index'];

    public function resources()
    {
        return $this->hasMany(Resource::class);
    }
}
