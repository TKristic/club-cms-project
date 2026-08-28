<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Club;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class NewsController extends Controller
{
    public function index() {
        return News::published()
            ->latest('published_at')
            ->get(['id', 'title', 'slug', 'excerpt', 'published_at']);
    }

    public function show(News $news) {
        return $news->only(['id', 'title', 'slug', 'excerpt', 'body', 'published_at']);
    }

    public function store(Request $request) {
        if (! $request->user()->hasAnyRole(['admin', 'superadmin'])) {
            return response()->json(['message' => 'Nemate ovlasti za ovu akciju.'], 403);
        }

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'body'  => 'required|string',
        ]);

        $news = News::create([
            'club_id'      => Club::value('id') ?? 1,
            'user_id'      => $request->user()->id,
            'title'        => $data['title'],
            'slug'         => Str::slug($data['title']) . '-' . Str::random(4),
            'body'         => $data['body'],
            'published_at' => now(),
        ]);

        return response()->json($news, 201);
    }
}