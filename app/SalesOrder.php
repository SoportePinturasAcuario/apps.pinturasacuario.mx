<?php

namespace Apps;

use Illuminate\Database\Eloquent\Model;
use Apps\Models\Customers\Pivots\ArticleSalesOrder;
use Apps\Models\Customers\Article;

class SalesOrder extends Model
{
	protected $connection = 'claims';

    protected $fillable = [
        'note',
        'user_id',
        'status_id',
        'customer_id',
        'shipping_method_id',
    ];


    protected $casts = [
        'user_id' => 'int', 
        'status_id' => 'int',
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
        return $this->belongsToMany(Article::class)->using(ArticleSalesOrder::class);
    } 

    public function status() {
        return $this->belongsTo(Status::class);
    } 

    public function shipping_method() {
        return $this->belongsTo(Models\Customers\ShippingMethod::class);
    }     
}
