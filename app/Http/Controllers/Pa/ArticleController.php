<?php

namespace Apps\Http\Controllers\Pa;

use Apps\Article;
use Illuminate\Http\Request;
use Apps\Http\Controllers\Controller;
use Apps\Http\Requests\ArticleRequest;

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
        $articles = Article::all();
        
        return response()->json([
            "success" => true,
            "data" => $articles
        ], 200, [], JSON_NUMERIC_CHECK);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $this->validate($request, [
            'clasification' => 'in:pt' 
        ]);

        switch ($request->get('clasification')) {
            case 'mp':
                $data = $this->mp();
                break;
            case 'pt':
                $data = $this->pt();
                break;
            default:
                $data = [];
                break;
        }

        return response()->json([
            'data' => $data,
        ]);        
    }    

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ArticleRequest $request)
    {
        $article = Article::create($request->all());

        return response()->json([
            "success" => true,
            "data" => $article
        ], 200, [], JSON_NUMERIC_CHECK);        
    }

    /**
     * Display the specified resource.
     *
     * @param  \Apps\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function show(Article $article)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Apps\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Article $article)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Apps\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function destroy(Article $article)
    {
        //
    }
}
