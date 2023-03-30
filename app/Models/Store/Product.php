<?php

namespace Apps\Models\Store;

use Illuminate\Database\Eloquent\Model;
use Apps\Models\Store\Category;
use Apps\Models\Store\Article;

// Traits
use Apps\Traits\Store\Product\Images;

class Product extends Model
{
    // Traits
    use Images;

    protected $connection = "store";

    public function getDefaultImages() {
        $this->images = $this->searchImages($this->getPaths($this));
    }    

    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function articles() {
        return $this->hasMany(Article::class, 'category_id', 'id');
    }    
}