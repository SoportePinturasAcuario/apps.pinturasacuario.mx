<?php

namespace Apps\Http\Controllers\Store;

use Illuminate\Http\Request;
use Apps\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

// Models
use Apps\Models\Store\Article;
use Apps\Models\Store\PriceList;

// Requests
use Apps\Http\Requests\Store\ArticleRequest;

// Traits
use Apps\Traits\Pa\Article\Search;

class ArticleController extends Controller
{
    use Search;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $articles = $this->forEcommerce();

        // $articles = $this->getDefaultImages($articles);

        return response()->json([
            'data' => $articles
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ArticleRequest $request) {
        $date = date('Y-m-d H:i:s');

        $articles = array_map(function($article) use($date) {
            $article['created_at'] = $date;

            return $article;

        }, $request->get('articles'));

        $data = Article::insert($articles);

        return response()->json([
            'data' => $data,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Apps\Models\Store\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function show($article_id)
    {
        $article = $this->forEcommerce()->firstWhere('id', $article_id);

        $article->getDefaultImages();

        return response()->json([
            'data' => $article
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Apps\Models\Store\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function update(ArticleRequest $request, Article $article)  {
        
        $article->update($request->all());

        return response()->json([
            'data' => $article,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Apps\Models\Store\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function destroy(Article $article)
    {
        //
    }

    public function all_withtrashed() {
        return response()->json([
            'data' => $this->articlesAllWithTrashed(),
        ]);
    }

    public function nonexistence() {
        $articles = Article::all(['id'])->map(function($article) {
            return $article->id;
        });

        $in_price_lists = collect(DB::connection('store')->select('
            SELECT `article_price_list`.`article_id` AS id
            FROM `article_price_list`
            GROUP BY `article_price_list`.`article_id`
        '))->map(function($item) {
            return $item->id;
        });

        return response()->json([
            'data' => $in_price_lists->diff($articles)->unique()->sort()->values(),
        ]);
    }


    public function unusable() {
        $articles = Article::all(['id'])->map(function($article) {
            return $article->id;
        });

        $in_sales_orders = collect(DB::connection('store')->select('
            SELECT `article_sales_order`.`article_id` AS id
            FROM `article_sales_order`
            GROUP BY `article_sales_order`.`article_id`
        '))->map(function($item) {
            return $item->id;
        });

        $in_price_lists = collect(DB::connection('store')->select('
            SELECT `article_price_list`.`article_id` AS id
            FROM `article_price_list`
            GROUP BY `article_price_list`.`article_id`
        '))->map(function($item) {
            return $item->id;
        });

        return response()->json([
            'data' => $articles->diff($in_sales_orders->concat($in_price_lists)->unique())->sort()->values(),
        ]);        
    }
}
