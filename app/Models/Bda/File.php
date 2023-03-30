<?php

namespace Apps\Models\Bda;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $connection = 'bda';

    protected $fillable = ['name', 'path', 'fileable_id', 'fileable_type'];

    public function imageGenerateUrl()
    {
        $this->url = env('APP_URL', "https://apps.pinturasacuario.mx") . "/storage/bda/$this->path";
    }
}
