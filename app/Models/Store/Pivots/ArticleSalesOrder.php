<?php

namespace Apps\Models\Store\Pivots;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ArticleSalesOrder extends Pivot
{
	protected $connection = 'store';

	protected $table = 'article_sales_order';
}