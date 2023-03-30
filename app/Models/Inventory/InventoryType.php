<?php

namespace Apps\Models\Inventory;

use Illuminate\Database\Eloquent\Model;

class InventoryType extends Model
{
    protected $connection = 'inventory';

    protected $table = 'inventory_types';

    protected $fillable = ['id', 'name'];
}
