<?php

namespace Apps\Models\Store\Pivots;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ArticlePriceList extends Pivot
{
    protected $connection = "store";

    protected $table = 'article_price_list';

    protected $fillable = ['preferential_price', 'preferential_discount'];

    protected $casts = [
        'preferential_price' => 'float',
        'preferential_discount' => 'float',
    ];
}
