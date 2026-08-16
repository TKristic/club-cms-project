@extends('layouts.app')
@section('content')
<h1 class="text-3xl font-bold mb-6" style="color: var(--klub-primarna)">Rezultati i raspored</h1>

<section class="mb-10">
    <h2 class="text-xl font-semibold mb-4">Nadolazeće utakmice</h2>
    @if($upcoming->isEmpty())
        <p class="text-gray-400 text-sm">Nema zakazanih utakmica.</p>
    @else
        <div class="space-y-2">
            @foreach($upcoming as $f)
                <div class="bg-white rounded-lg shadow p-4 flex items-center justify-between">
                    <div>
                        <span class="text-xs px-2 py-0.5 rounded text-white"
                              style="background: var(--klub-primarna)">{{ $f->category->name }}</span>
                        <span class="font-semibold ml-2">
                            {{ $f->is_home ? $club->name : $f->opponent }}
                            <span class="text-gray-400">vs</span>
                            {{ $f->is_home ? $f->opponent : $club->name }}
                        </span>
                    </div>
                    <div class="text-sm text-gray-500">{{ $f->kickoff_at->format('d.m.Y. H:i') }}</div>
                </div>
            @endforeach
        </div>
    @endif
</section>

<section>
    <h2 class="text-xl font-semibold mb-4">Rezultati</h2>
    @if($results->isEmpty())
        <p class="text-gray-400 text-sm">Još nema odigranih utakmica.</p>
    @else
        <div class="space-y-2">
            @foreach($results as $f)
                <div class="bg-white rounded-lg shadow p-4 flex items-center justify-between">
                    <div>
                        <span class="text-xs px-2 py-0.5 rounded text-white"
                              style="background: var(--klub-primarna)">{{ $f->category->name }}</span>
                        <span class="font-semibold ml-2">
                            {{ $f->is_home ? $club->name : $f->opponent }}
                            <span class="mx-1 font-bold">{{ $f->display_score }}</span>
                            {{ $f->is_home ? $f->opponent : $club->name }}
                        </span>
                    </div>  
                    <div class="text-sm text-gray-500">
                        {{ $f->kickoff_at->format('d.m.Y.') }}
                        @if($f->competition) · {{ $f->competition }}@endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</section>
@endsection