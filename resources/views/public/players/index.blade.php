@extends('layouts.app')
@section('content')
<div class="mb-8">
    <h1 class="text-4xl font-extrabold" style="color: var(--klub-primarna)">Momčad</h1>
    <div class="h-1 w-20 mt-3 rounded" style="background: var(--klub-sekundarna)"></div>
</div>

@foreach($categories as $category)
    <section class="mb-12">
        <h2 class="text-2xl font-bold mb-5 flex items-center gap-3">
            <span style="color: var(--klub-primarna)">{{ $category->name }}</span>
            <span class="text-sm font-normal text-gray-400">{{ $category->players->count() }} igrača</span>
        </h2>

        @if($category->players->isEmpty())
            <p class="text-gray-400 text-sm">Nema unesenih igrača.</p>
        @else
            <div class="grid gap-5 grid-cols-2 sm:grid-cols-3 lg:grid-cols-5">
                @foreach($category->players as $player)
                    <div class="group bg-white rounded-2xl shadow hover:shadow-xl transition overflow-hidden">
                        <div class="relative h-48 overflow-hidden">
                            @if($player->photo)
                                <img src="{{ asset('storage/'.$player->photo) }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-white text-5xl font-extrabold"
                                     style="background: linear-gradient(135deg, var(--klub-primarna), color-mix(in srgb, var(--klub-primarna) 60%, black))">
                                    {{ $player->jersey_number ?? '?' }}
                                </div>
                            @endif
                            @if($player->jersey_number)
                                <span class="absolute top-2 right-2 h-8 w-8 flex items-center justify-center rounded-full text-sm font-bold text-gray-900 shadow"
                                      style="background: var(--klub-sekundarna)">{{ $player->jersey_number }}</span>
                            @endif
                        </div>
                        <div class="p-3 text-center">
                            <p class="font-bold leading-tight">{{ $player->full_name }}</p>
                            <p class="text-xs text-gray-500 mt-1">
                                {{ $player->position }}@if($player->age) · {{ $player->age }} g.@endif
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </section>
@endforeach
@endsection