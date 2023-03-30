<?php

namespace Apps\Http\Controllers\Store;

use Illuminate\Http\Request;
use Apps\Models\Store\PriceList;
use Apps\Http\Controllers\Controller;
use Apps\Models\Store\Pivots\ArticlePriceList;

use Apps\Http\Requests\Store\ArticlePriceListRequest;
use Apps\Http\Requests\Store\PriceListRequest;
use Apps\Models\Store\Customer;

// Traits
use Apps\Traits\Pa\Article\Search;

class PriceListController extends Controller
{
    use Search;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $priceLists = PriceList::withCount('articles', 'customers')->orderBy('name','ASC')->get();

        return response()->json([
            'data' => $priceLists,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PriceListRequest $request)
    {
        $priceList = PriceList::create($request->all());

        return response()->json([
            'data' => $priceList,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Apps\Models\Store\PriceList  $priceList
     * @return \Illuminate\Http\Response
     */
    public function show($price_list_id)
    {
        $priceList = PriceList::with('customers', 'articles.category.category', 'articles.product')->find($price_list_id);

        $priceList->articles = $priceList->articles->map(function($article){
            $article->categories = $this->mapArticleCategories($article);
            return $article;
        });        

        return response()->json([
            'data' => $priceList,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Apps\Models\Store\PriceList  $priceList
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PriceList $priceList)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Apps\Models\Store\PriceList  $priceList
     * @return \Illuminate\Http\Response
     */
    public function destroy(PriceList $priceList)
    {
        //
    }


    // ArticlePriceList
    public function article_price_list_update(ArticlePriceListRequest $request, $price_list_id) {
        $this->validate($request, [
            'article_price_list.*.id' => 'required|integer',
        ]);

        $data = [];

        foreach ($request->get('article_price_list') as $key => $item){
            $article_price_list = ArticlePriceList::find($item['id']);

            $article_price_list->update($item);

            array_push($data, $article_price_list);
        }

        return response()->json([
            'data' => $data,
        ]);
    }

    // ArticlePriceList Sync
    public function import(ArticlePriceListRequest $request, $price_list_id) {
        $priceList = PriceList::findOrFail($price_list_id);

        $toSync = array_reduce($request->get('article_price_list'), function($acc, $item) {
            $acc[$item['id']] = [
                'preferential_price' => $item['preferential_price'],
                'preferential_discount' => $item['preferential_discount'],
            ];
            return $acc;
        });

        $nonexistent = collect($toSync)
        ->except($this->forEcommerce()
        ->pluck('id')
        ->toArray())
        ->keys();

        $results = array_map(function($array) {
            return collect($array)->values();
        }, $priceList->articles()->sync($toSync));

        return response()->json([
            'data' => array_merge($results, [
                'nonexistent' => $nonexistent,
            ]),
        ]);
    }

    public function customer_store(Request $request, $price_list_id) {
        $this->validate($request, [
            'customer_id' => 'required|integer',
        ]);

        $customer = Customer::findOrFail($request->get('customer_id'));

        $customer->priceList()->associate($price_list_id);

        $customer->save();

        return response()->json([
            'data' => $customer,
        ]);
    } 


    public function customer_delete(Request $request, $price_list_id, $customer_id) {
        $customer = Customer::findOrFail($customer_id);

        $customer->priceList()->dissociate();

        $customer->save();

        return response()->json([
            'data' => $customer,
        ]);
    }     
}
