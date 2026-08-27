@extends('layouts.app')
@section('content')
<div class="max-w-md mx-auto">
    <h1 class="text-3xl font-bold mb-6" style="color: var(--klub-primarna)">{{ __('messages.register_title') }}</h1>

    @if($errors->any())
        <div class="bg-red-100 text-red-800 rounded-lg p-4 mb-6 text-sm">
            @foreach($errors->all() as $e) <div>{{ $e }}</div> @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}" class="bg-white rounded-xl shadow p-6 space-y-4">
        @csrf
        <input name="name" value="{{ old('name') }}" placeholder="{{ __('messages.name') }}"
               class="w-full border rounded-lg px-3 py-2" required>
        <input type="email" name="email" value="{{ old('email') }}" placeholder="{{ __('messages.email') }}"
               class="w-full border rounded-lg px-3 py-2" required>
        <input type="password" name="password" placeholder="{{ __('messages.password') }}"
               class="w-full border rounded-lg px-3 py-2" required>
        <input type="password" name="password_confirmation" placeholder="{{ __('messages.password_confirm') }}"
               class="w-full border rounded-lg px-3 py-2" required>
        <button class="w-full py-2 rounded-lg font-semibold text-white"
                style="background: var(--klub-primarna)">{{ __('messages.do_register') }}</button>
    </form>
</div>
@endsection