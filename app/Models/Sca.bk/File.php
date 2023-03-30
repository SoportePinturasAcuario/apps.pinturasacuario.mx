<?php

namespace Apps\Models\Sca;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $connection = 'sca';

    protected $fillable = ['name', 'path', 'type_id', 'fileable_id', 'fileable_type'];
}
