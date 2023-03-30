<?php

namespace Apps\Models\Store;

use Apps\Models\Store\Category;
use Illuminate\Database\Eloquent\Model;
use Apps\Models\Store\UnitOfMeasurement;

use Apps\Models\Store\Color;
use Apps\Models\Store\Product;
use Apps\Models\Store\TypeOfFinishes;

// Traits
use Apps\Traits\Pa\Article\Search;

class Article extends Model
{
    use Search;

	protected $connection = 'store';

    protected $fillable = [
		'id',
		'code',
		'active',
		'weight',
		'quality',
		'special',
		'color_id',
		'contents',
		'base_price',
		'category_id',
		'description',
		'box_capacity',
		'base_discount',
		'type_of_finish_id',
		'available_ecommerce',
		'unit_of_measurement_id',
	];

	protected $casts = [
		'id' => 'integer',
		'code' => 'string',
		'base_price' => 'float',
		'weight' => 'float',
		'base_discount' => 'float',
		'contents' => 'float',
		'quality' => 'integer',
		'special' => 'boolean',
		'description' => 'string',
		'category_id' => 'integer',
		'box_capacity' => 'integer',
		'available_ecommerce' => 'integer',
		'unit_of_measurement_id' => 'integer',
	];	

	public function category() {
		return $this->belongsTo(Category::class);
	}

	public function product() {
		return $this->belongsTo(Product::class, 'category_id', 'id');
	}	

	public function getDefaultImages() {
		$this->images = $this->searchImages($this->getPaths($this));
	}

	public function unitOfMeasurement(){
		return $this->belongsTo(UnitOfMeasurement::class);
	}

	public function color(){
		return $this->belongsTo(Color::class);
	}

	public function type_of_finish() {
		return $this->belongsTo(TypeOfFinishes::class);
	}
}