<?php

namespace Apps\Models\Claim;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model {
    protected $connection = 'claims';
    
    protected $fillable = [
        'id',
        'rfc',
        'name',
        'folio',
    ];
}
