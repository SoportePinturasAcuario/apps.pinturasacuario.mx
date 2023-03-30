<?php

namespace Apps\Models\Bda;

use Illuminate\Database\Eloquent\Model;

// Models
use Apps\Collaborator;
use Apps\Models\Bda\File;
use Apps\Models\Bda\Module;
use Apps\Models\Bda\Resource;

class Course extends Model
{
    protected $connection = "bda";

    protected $fillable = ['title', 'subtitle', 'description', 'public_access'];

    protected $casts = [
        'public_access' => 'boolean',
    ];

    public function image()
    {
        return $this->morphOne(File::class, 'fileable');
    }

    public function modules()
    {
        return $this->hasMany(Module::class);
    }

    public function collaborators()
    {
        return $this->belongsToMany(Collaborator::class);
    }

    public function files()
    {
        return $this->morphMany(File::class, 'fileable');
    }
}
