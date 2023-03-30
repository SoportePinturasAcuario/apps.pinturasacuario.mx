<?php

namespace Apps\Http\Controllers\store;

use Apps\Models\Store\Product;
use Illuminate\Http\Request;
use Apps\Http\Controllers\Controller;

// Traits
use Apps\Traits\Store\Product\Images;

class ProductController extends Controller
{
    use Images;
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::with('category', 'articles')->orderBy('category_id', 'ASC')->get();

        $products = $this->getDefaultImages($products);

        return response()->json([
            'data' => $products,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \Apps\Models\Store\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show($product_id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Apps\Models\Store\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Apps\Models\Store\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //
    }

    // Articles
    public function article_index($product_id) {

        $product = Product::with([
            'category',
            'articles.color',
            'articles.category.category',
            'articles.unitOfMeasurement',
            'articles.type_of_finish'
        ])->find($product_id);

        $product->getDefaultImages();

        return response()->json([
            'data' => $product,
        ]);
    }
}
