<?php

namespace Apps\Models\Store;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $connection = 'store';

    protected $fillable = ['id', 'name', 'category_id'];

    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function categories() {
        return $this->hasMany(Category::class);
    }    
}
