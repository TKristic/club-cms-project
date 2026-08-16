@extends('layouts.app')
@section('content')
<a href="{{ route('gallery.index') }}" class="text-sm text-gray-400 hover:underline">← Sve galerije</a>
<h1 class="text-4xl font-extrabold mt-3 mb-2" style="color: var(--klub-primarna)">{{ $gallery->title }}</h1>
@if($gallery->description)
    <p class="text-gray-600 mb-6 max-w-2xl">{{ $gallery->description }}</p>
@endif

@if($gallery->media->isEmpty())
    <p class="text-gray-500">Galerija je prazna.</p>
@else
    <div class="grid gap-3 grid-cols-2 sm:grid-cols-3 lg:grid-cols-4">
        @foreach($gallery->media as $m)
            <a href="{{ asset('storage/'.$m->path) }}" target="_blank"
               class="group block rounded-xl overflow-hidden shadow hover:shadow-lg transition">
                <div class="aspect-square overflow-hidden">
                    <img src="{{ asset('storage/'.$m->path) }}"
                         class="w-full h-full object-cover group-hover:scale-110 transition duration-300">
                </div>
                @if($m->caption)
                    <p class="p-2 text-xs text-gray-500 bg-white">{{ $m->caption }}</p>
                @endif
            </a>
        @endforeach
    </div>
@endif
@endsection