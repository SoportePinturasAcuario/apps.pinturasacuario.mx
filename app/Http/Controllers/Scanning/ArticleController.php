<?php

namespace Apps\Http\Controllers\Scanning;

use Apps\Models\Scanning\Article;
use Illuminate\Http\Request;
use Apps\Http\Controllers\Controller;

// Request
use Apps\Http\Requests\Scanning\ArticleRequest;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        return response()->json([
            'data' => Article::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ArticleRequest $request) {

        foreach (array_chunk($request->get('articles'), 50) as $key => $row) {
            $articles = Article::insert($row);
        }

        return response()->json([
            'data' => $articles,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Apps\Models\Scanning\Article  $article
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
     * @param  \Apps\Models\Scanning\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function update(ArticleRequest $request, Article $article)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Apps\Models\Scanning\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function destroy(Article $article)
    {
        //
    }
}
