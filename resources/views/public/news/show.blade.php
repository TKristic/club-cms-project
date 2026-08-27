@extends('layouts.app')
@section('content')
<article class="max-w-3xl mx-auto">
    <a href="{{ route('news.index') }}" class="text-sm text-gray-400 hover:underline">← {{ __('messages.back_to_news') }}</a>

    <header class="mt-4 mb-6">
        <p class="text-xs font-semibold uppercase tracking-wide" style="color: var(--klub-sekundarna)">
            {{ $article->published_at->format('d.m.Y.') }} · {{ $article->author->name }}
        </p>
        <h1 class="text-4xl font-extrabold mt-2 leading-tight" style="color: var(--klub-primarna)">
            {{ $article->title }}
        </h1>
    </header>

    @if($article->featured_image)
        <img src="{{ asset('storage/'.$article->featured_image) }}"
             class="w-full rounded-2xl shadow-lg mb-8 max-h-[28rem] object-cover">
    @endif

    @if($article->excerpt)
        <p class="text-lg text-gray-500 font-medium mb-6 leading-relaxed">{{ $article->excerpt }}</p>
    @endif

    <div class="text-gray-800 leading-relaxed whitespace-pre-line text-[1.05rem]">{{ $article->body }}</div>
</article>
@endsection