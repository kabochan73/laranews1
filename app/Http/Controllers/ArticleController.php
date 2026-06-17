<?php

namespace App\Http\Controllers;

use App\Models\Article;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::latest('published_at')->get();
        $popular = Article::orderByDesc('liked_count')->take(5)->get();
        return view('articles.index', compact('articles', 'popular'));
    }
}
