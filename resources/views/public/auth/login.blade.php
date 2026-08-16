@extends('layouts.app')
@section('content')
<div class="max-w-md mx-auto">
    <h1 class="text-3xl font-bold mb-6" style="color: var(--klub-primarna)">Prijava</h1>

    @if($errors->any())
        <div class="bg-red-100 text-red-800 rounded-lg p-4 mb-6 text-sm">
            @foreach($errors->all() as $e) <div>{{ $e }}</div> @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="bg-white rounded-xl shadow p-6 space-y-4">
        @csrf
        <input type="email" name="email" value="{{ old('email') }}" placeholder="E-mail"
               class="w-full border rounded-lg px-3 py-2" required>
        <input type="password" name="password" placeholder="Lozinka"
               class="w-full border rounded-lg px-3 py-2" required>
        <label class="flex items-center gap-2 text-sm text-gray-600">
            <input type="checkbox" name="remember"> Zapamti me
        </label>
        <button class="w-full py-2 rounded-lg font-semibold text-white"
                style="background: var(--klub-primarna)">Prijavi se</button>
    </form>
    <p class="text-sm text-gray-500 mt-4 text-center">
        Nemaš račun? <a href="{{ route('register') }}" class="underline">Registriraj se</a>
    </p>
</div>
@endsection