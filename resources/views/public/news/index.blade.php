@extends('layouts.app')
@section('content')
<div class="mb-8">
    <h1 class="text-4xl font-extrabold" style="color: var(--klub-primarna)">Vijesti</h1>
    <div class="h-1 w-20 mt-3 rounded" style="background: var(--klub-sekundarna)"></div>
</div>

@if($news->isEmpty())
    <p class="text-gray-500">Trenutno nema objavljenih vijesti.</p>
@else
    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
        @foreach($news as $article)
            <a href="{{ route('news.show', $article->slug) }}"
               class="group block bg-white rounded-2xl shadow hover:shadow-xl transition overflow-hidden">
                <div class="h-48 overflow-hidden">
                    @if($article->featured_image)
                        <img src="{{ asset('storage/'.$article->featured_image) }}"
                             class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                    @else
                        <div class="w-full h-full" style="background: var(--klub-primarna)"></div>
                    @endif
                </div>
                <div class="p-5">
                    <p class="text-xs font-semibold uppercase tracking-wide" style="color: var(--klub-sekundarna)">
                        {{ $article->published_at->format('d.m.Y.') }}
                    </p>
                    <h2 class="font-bold text-lg mt-1 leading-snug group-hover:underline">{{ $article->title }}</h2>
                    <p class="text-sm text-gray-600 mt-2 line-clamp-3">{{ $article->excerpt }}</p>
                </div>
            </a>
        @endforeach
    </div>
    <div class="mt-10">{{ $news->links() }}</div>
@endif
@endsection