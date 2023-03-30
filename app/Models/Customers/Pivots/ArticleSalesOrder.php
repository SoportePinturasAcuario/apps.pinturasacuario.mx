<?php

namespace Apps\Models\Customers\Pivots;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ArticleSalesOrder extends Pivot
{
	protected $connection = 'claims';

	protected $table = 'article_sales_order';
}