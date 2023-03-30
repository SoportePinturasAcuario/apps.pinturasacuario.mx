<?php

namespace Apps\Models\Store;

use Illuminate\Database\Eloquent\Model;
use Apps\Models\Store\Pivots\ArticlePriceList;
use Apps\Models\Store\Article;
use Apps\Models\Store\Customer;

class PriceList extends Model
{
    protected $connection = "store";

    protected $fillable = ['id', 'name'];

    public function articles() {
        return $this->belongsToMany(Article::class)
        ->using(ArticlePriceList::class)
        ->withPivot('id', 'preferential_price', 'preferential_discount');
    }

    public function customers(){
        return $this->hasMany(Customer::class);
    }
}
