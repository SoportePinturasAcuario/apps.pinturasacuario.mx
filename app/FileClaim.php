<?php

namespace Apps;

use Illuminate\Database\Eloquent\Model;

class FileClaim extends Model
{
    protected $connection = 'claims';
    protected $table = 'files';

    protected $fillable = ['name', 'gdriveid', 'url', 'extension', 'fileable_id'];
}
