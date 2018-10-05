<?php

namespace App\Http\Controllers;

use App\Article;
use Illuminate\Http\Request;
use App\Http\Resources\Article as ArticleResource;

class ArticleController extends Controller
{
   
    public function index()
    {
       $articles = Article::orderBy('id','DESC')->paginate(5);
        
       // Return collection of articles as a resource
       return ArticleResource::collection($articles);
    }
 
    public function show($id)
    {
        // Get article
        $article = Article::findOrFail($id);

        // Return single article as a resource
        return new ArticleResource($article);
    }

    public function store(Request $request)
    {
        $article = new Article;

        $article->title = $request->input('title');
        $article->body = $request->input('body');

        if($article->save()) {
            return new ArticleResource($article);
        }
    }

    public function update(Request $request, $id)
    {
        $article = Article::findOrFail($id);
        $article->title = $request->input('title');
        $article->body = $request->input('body');

        if($article->update()) {
            return new ArticleResource($article);
        }
    }

    public function destroy($id)
    {
        $article = Article::findOrFail($id);
        if($article->delete()) {
            return new ArticleResource($article);
        }    
    }
}
