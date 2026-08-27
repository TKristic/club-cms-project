@extends('layouts.app')
@section('content')
<div class="mb-8">
    <h1 class="text-4xl font-extrabold" style="color: var(--klub-primarna)">{{ __('messages.gallery_title') }}</h1>
    <div class="h-1 w-20 mt-3 rounded" style="background: var(--klub-sekundarna)"></div>
</div>

@if($galleries->isEmpty())
    <p class="text-gray-500">{{ __('messages.no_galleries') }}</p>
@else
    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
        @foreach($galleries as $gallery)
            @php $cover = $gallery->media->first(); @endphp
            <a href="{{ route('gallery.show', $gallery) }}"
               class="group block bg-white rounded-2xl shadow hover:shadow-xl transition overflow-hidden">
                <div class="h-52 overflow-hidden relative">
                    @if($cover)
                        <img src="{{ asset('storage/'.$cover->path) }}"
                             class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                    @else
                        <div class="w-full h-full" style="background: var(--klub-primarna)"></div>
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                    <div class="absolute bottom-3 left-4 text-white">
                        <h2 class="font-bold text-lg">{{ $gallery->title }}</h2>
                        <p class="text-xs opacity-90">{{ $gallery->media_count }} {{ __('messages.images_count') }}</p>
                    </div>
                </div>
            </a>
        @endforeach
    </div>
@endif
@endsection