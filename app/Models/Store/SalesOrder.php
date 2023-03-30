<?php

namespace Apps\Models\Store;

use Illuminate\Database\Eloquent\Model;

// Models
use Apps\Status;
use Apps\User;
use Apps\Models\Store\Customer;
use Apps\Models\Store\Article;
use Apps\Models\Store\ShippingMethod;
use Apps\Models\Store\Pivots\ArticleSalesOrder;

class SalesOrder extends Model
{
	protected $connection = 'store';

    protected $fillable = [
        'note',
        'user_id',
        'discount',
        'status_id',
        'exported_at',
        'customer_id',
        'shipping_method_id',
    ];

    protected $casts = [
        'user_id' => 'int', 
        'status_id' => 'int',
        'discount' => 'float',
        'customer_id' => 'int',
        'shipping_method_id' => 'int',
    ];   

    public function customer() {
    	return $this->belongsTo(Customer::class);
    }

    public function user() {
    	return $this->belongsTo(User::class);
    }  

    public function articles() {
        return $this->belongsToMany(Article::class)
        ->withPivot([
            'amount',
            'price',
            'discount',
            'base_price',
            'base_discount',
            'preferential_price',
            'preferential_discount'
        ]);
    }

    public function status() {
        return $this->belongsTo(Status::class);
    } 

    public function shipping_method() {
        return $this->belongsTo(ShippingMethod::class);
    }

    public function subTotal() {
        $articles = collect($this->articles);
        
        $subTotal = $articles->reduce(function($carry, $article){
            $price = $article['price'];

            if ($article['discount'] > 0) {
                $price = $price - (($price / 100) * $article['discount']);
            }

            return $carry + ($article['amount'] * $price);
        });

        $this->subTotal = number_format($subTotal, 2, '.','');
        return $this->subTotal;
    }

    public function hasDiscunt(){
        if (isset($this->discount) && $this->discount != null) {
            if ($this->discount > 0) {
                return true;
            }
        }        

        return false;
    }

    public function discountAmount() {
        $this->discountAmount = 0;

        if ($this->hasDiscunt()) {
            $discountAmount = ($this->subTotal / 100) * $this->discount;

            $this->discountAmount = number_format($discountAmount, 2, '.','');        }

        return $this->discountAmount;
    }

    public function ivaAmount() {
        $ivaAmount = ($this->subTotal - $this->discountAmount) * 0.16;

        $this->ivaAmount = number_format($ivaAmount, 2, '.','');
        return $this->ivaAmount;
    }

    public function total() {
        $total = ($this->subTotal - $this->discountAmount) + $this->ivaAmount;

        $this->total = number_format($total, 2, '.','');

        return $this->total;
    }
}
