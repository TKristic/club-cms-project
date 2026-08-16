<?php

namespace App\Http\Controllers;

use App\Models\Club;
use App\Models\ForumTopic;
use Illuminate\Http\Request;

class ForumController extends Controller
{
    public function index()
    {
        $topics = ForumTopic::withCount('posts')->with('author')->latest()->paginate(15);
        return view('public.forum.index', compact('topics'));
    }

    public function create()
    {
        return view('public.forum.create');
    }

    public function storeTopic(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'body'  => 'required|string|max:5000',
        ]);

        $topic = ForumTopic::create([
            'club_id' => Club::value('id') ?? 1,
            'user_id' => auth()->id(),
            'title'   => $data['title'],
        ]);

        $topic->posts()->create(['user_id' => auth()->id(), 'body' => $data['body']]);

        return redirect()->route('forum.show', $topic);
    }

    public function show(ForumTopic $topic)
    {
        $topic->load(['author', 'posts.author']);
        return view('public.forum.show', compact('topic'));
    }

    public function storePost(Request $request, ForumTopic $topic)
    {
        $data = $request->validate(['body' => 'required|string|max:5000']);
        $topic->posts()->create(['user_id' => auth()->id(), 'body' => $data['body']]);

        return redirect()->route('forum.show', $topic);
    }
}
