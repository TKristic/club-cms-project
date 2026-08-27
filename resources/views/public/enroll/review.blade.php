@extends('layouts.app')
@section('content')
<div class="max-w-xl mx-auto">
    <h1 class="text-3xl font-bold mb-6" style="color: var(--klub-primarna)">{{ __('messages.enroll_review') }}</h1>

    <div class="bg-white rounded-xl shadow p-6 space-y-2 mb-4">
        <p><strong>Dijete:</strong> {{ $data['child_first_name'] }} {{ $data['child_last_name'] }}
           ({{ \Carbon\Carbon::parse($data['child_birth_date'])->format('d.m.Y.') }})</p>
        <p><strong>Roditelj:</strong> {{ $data['parent_name'] }}</p>
        <p><strong>E-mail:</strong> {{ $data['parent_email'] }}</p>
        <p><strong>Telefon:</strong> {{ $data['parent_phone'] }}</p>
        @if(!empty($data['note']))
            <p><strong>Napomena:</strong> {{ $data['note'] }}</p>
        @endif
    </div>

    <div class="flex gap-3">
        {{-- Natrag na uređivanje --}}
        <a href="{{ route('enroll.create') }}"
           class="px-4 py-2 rounded-lg border text-gray-600">← {{ __('messages.enroll_back') }}</a>

        {{-- Potvrda → konačno spremanje, podaci se prenose skrivenim poljima --}}
        <form method="POST" action="{{ route('enroll.store') }}" class="flex-1">
            @csrf
            @foreach($data as $key => $value)
                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
            @endforeach
            <button class="w-full py-2 rounded-lg font-semibold text-white"
                    style="background: var(--klub-primarna)">{{ __('messages.enroll_confirm') }} ✓</button>
        </form>
    </div>
</div>
@endsection