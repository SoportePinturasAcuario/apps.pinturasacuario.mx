<?php

namespace Apps\Http\Controllers\Inventory;

use Illuminate\Http\Request;
use Apps\Http\Controllers\Controller;

// Models
use Apps\Models\Inventory\Article;

// Requests
use Apps\Http\Requests\Inventory\ArticleRequest;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json([
            'data' => Article::orderBy('id', 'DESC')->get(),
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
            'data' => $article,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Apps\Models\Inventory\Article  $article
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
     * @param  \Apps\Models\Inventory\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function update(ArticleRequest $request, $article_id)
    {
        $article = Article::findOrFail($article_id);

        $article->update($request->all());

        return response()->json([
            'data' => $article,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Apps\Models\Inventory\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function destroy($article_id)
    {
        $article = Article::findOrFail($article_id);

        $article->delete();

        return response()->json([
            'data' => $article,
        ]);
    }
}
