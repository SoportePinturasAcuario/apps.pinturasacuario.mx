<?php

namespace Apps\Models\Store;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

// use Apps\Models\Store\Pivots\ArticlePriceList;
use Apps\User;
use Apps\Models\Store\Article;
use Apps\Models\Store\SalesOrder;
use Apps\Models\Store\PriceList;

class Customer extends Model {
    
    use SoftDeletes;
    
    protected $connection = 'store';
    
    protected $fillable = [
        'id',
        'rfc',
        'name',
        'folio',
        'discount',
        'price_list_id',
    ];

    protected $casts = [
        'id' => 'integer',
        'rfc' => 'string',
        'name' => 'string',
        'folio' => 'integer',
        'discount' => 'float',
        'price_list_id' => 'integer',
    ];   

    public function user() {
        return $this->morphOne(User::class, 'userable');
    }

    public function salesOrders() {
        return $this->hasMany(SalesOrder::class);
    }

    public function priceList(){
        return $this->belongsTo(PriceList::class);
    }

    public function articles() {
        return $this->belongsToMany(Article::class)->withPivot('preferential_price','preferential_discount');
    }    
}
