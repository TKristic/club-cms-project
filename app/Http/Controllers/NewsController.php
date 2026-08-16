<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\News;

class NewsController extends Controller
{
    

public function index()
{
    $news = News::published()->latest('published_at')->paginate(9);
    return view('public.news.index', compact('news'));
}

public function show(string $slug)
{
    $article = News::published()->where('slug', $slug)->firstOrFail();
    return view('public.news.show', compact('article'));
}
}
