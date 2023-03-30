<?php

namespace Apps\Models\Forms;

use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
    protected $connection = 'nom';
    protected $table = 'polls';

    protected $fillable = [
        'id',
        'name',
        'description',
    ];
}
