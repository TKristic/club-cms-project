@extends('layouts.app')
@section('content')
<div class="max-w-xl mx-auto">
    <h1 class="text-3xl font-bold mb-6" style="color: var(--klub-primarna)">{{ __('messages.contact_title') }}</h1>

    @if(session('success'))
        <div class="bg-green-100 text-green-800 rounded-lg p-4 mb-6">{{ session('success') }}</div>
    @endif

    @if($club?->contact_email || $club?->contact_phone)
        <div class="bg-white rounded-xl shadow p-4 mb-6 text-sm text-gray-600">
            @if($club->contact_email)<p>✉ {{ $club->contact_email }}</p>@endif
            @if($club->contact_phone)<p>☎ {{ $club->contact_phone }}</p>@endif
            @if($club->address)<p>📍 {{ $club->address }}</p>@endif
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-100 text-red-800 rounded-lg p-4 mb-6">
            <ul class="list-disc list-inside text-sm">
                @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('contact.store') }}" class="bg-white rounded-xl shadow p-6 space-y-4">
        @csrf
        <input name="name" value="{{ old('name') }}" placeholder="{{ __('messages.name') }}"
               class="w-full border rounded-lg px-3 py-2" required>
        <input type="email" name="email" value="{{ old('email') }}" placeholder="{{ __('messages.email') }}"
               class="w-full border rounded-lg px-3 py-2" required>
        <input name="subject" value="{{ old('subject') }}" placeholder="{{ __('messages.subject') }}"
               class="w-full border rounded-lg px-3 py-2">
        <textarea name="message" rows="5" placeholder="{{ __('messages.message') }}"
                  class="w-full border rounded-lg px-3 py-2" required>{{ old('message') }}</textarea>
        <button class="w-full py-2 rounded-lg font-semibold text-white"
                style="background: var(--klub-primarna)">{{ __('messages.submit') }}</button>
    </form>
</div>
@endsection