@extends('layouts.app')
@section('content')
<div class="max-w-2xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-3xl font-bold" style="color: var(--klub-primarna)">Sponzori</h1>
        <div class="text-sm">
            <a href="{{ route('sponsors.index', 'json') }}"
               class="{{ $format === 'json' ? 'font-bold underline' : 'opacity-70' }}">JSON</a> /
            <a href="{{ route('sponsors.index', 'xml') }}"
               class="{{ $format === 'xml' ? 'font-bold underline' : 'opacity-70' }}">XML</a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 text-green-800 rounded-lg p-3 mb-4 text-sm">{{ session('success') }}</div>
    @endif

    <p class="text-xs text-gray-400 mb-4">Podaci se čitaju i spremaju u datoteku
        <code>storage/app/sponsors.{{ $format }}</code></p>

    {{-- CREATE --}}
    <form method="POST" action="{{ route('sponsors.store', $format) }}"
          class="bg-white rounded-xl shadow p-4 mb-6 flex gap-2">
        @csrf
        <input name="name" placeholder="Naziv sponzora" class="flex-1 border rounded-lg px-3 py-2" required>
        <input name="url" placeholder="https://..." class="flex-1 border rounded-lg px-3 py-2">
        <button class="px-4 py-2 rounded-lg font-semibold text-white"
                style="background: var(--klub-primarna)">Dodaj</button>
    </form>

    {{-- READ + UPDATE + DELETE --}}
    @if(empty($sponsors))
        <p class="text-gray-500">Nema sponzora u {{ strtoupper($format) }} datoteci.</p>
    @else
        <div class="space-y-2">
            @foreach($sponsors as $s)
                <div class="bg-white rounded-lg shadow p-3">
                    <form method="POST" action="{{ route('sponsors.update', [$format, $s['id']]) }}"
                          class="flex gap-2 items-center">
                        @csrf @method('PUT')
                        <input name="name" value="{{ $s['name'] }}" class="flex-1 border rounded px-2 py-1">
                        <input name="url" value="{{ $s['url'] ?? '' }}" class="flex-1 border rounded px-2 py-1">
                        <button class="text-sm px-3 py-1 rounded bg-blue-600 text-white">Spremi</button>
                    </form>
                    <form method="POST" action="{{ route('sponsors.destroy', [$format, $s['id']]) }}"
                          class="mt-1 text-right">
                        @csrf @method('DELETE')
                        <button class="text-xs text-red-600 hover:underline">Obriši</button>
                    </form>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection