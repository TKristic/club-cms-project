@extends('layouts.app')
@section('content')
<div class="max-w-2xl mx-auto">
    <a href="{{ route('forum.index') }}" class="text-sm text-gray-400 hover:underline">← Sve teme</a>
    <h1 class="text-3xl font-bold mt-3 mb-6" style="color: var(--klub-primarna)">{{ $topic->title }}</h1>

    <div class="space-y-4">
        @foreach($topic->posts as $post)
            <div class="bg-white rounded-xl shadow p-4">
                <p class="text-xs text-gray-400 mb-2">
                    {{ $post->author->name }} · {{ $post->created_at->format('d.m.Y. H:i') }}
                </p>
                <p class="whitespace-pre-line text-gray-800">{{ $post->body }}</p>
            </div>
        @endforeach
    </div>

    @auth
        <form method="POST" action="{{ route('forum.storePost', $topic) }}"
              class="bg-white rounded-xl shadow p-4 mt-6 space-y-3">
            @csrf
            <textarea name="body" rows="3" placeholder="Odgovori..."
                      class="w-full border rounded-lg px-3 py-2" required></textarea>
            <button class="px-4 py-2 rounded-lg font-semibold text-white"
                    style="background: var(--klub-primarna)">Odgovori</button>
        </form>
    @else
        <p class="text-sm text-gray-500 mt-6">
            <a href="{{ route('login') }}" class="underline">Prijavi se</a> za odgovor.
        </p>
    @endauth
</div>
@endsection