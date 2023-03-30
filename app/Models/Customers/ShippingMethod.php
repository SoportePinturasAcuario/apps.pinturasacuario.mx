<?php

namespace Apps\Models\Customers;

use Illuminate\Database\Eloquent\Model;

class ShippingMethod extends Model
{
    protected $connection = "mysql";
    
    protected $fillable = ['name', 'idnetsuite'];
}
