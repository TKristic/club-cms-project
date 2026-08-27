@extends('layouts.app')
@section('content')
<div class="max-w-xl mx-auto">
    <h1 class="text-3xl font-bold mb-6" style="color: var(--klub-primarna)">{{ __('messages.enroll_title') }}</h1>

    @if(session('success'))
        <div class="bg-green-100 text-green-800 rounded-lg p-4 mb-6">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="bg-red-100 text-red-800 rounded-lg p-4 mb-6">
            <ul class="list-disc list-inside text-sm">
                @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('enroll.review') }}" class="bg-white rounded-xl shadow p-6 space-y-4">
        @csrf
        <h2 class="font-semibold text-gray-700">{{ __('messages.enroll_title') }}</h2>
        <input name="child_first_name" value="{{ old('child_first_name') }}" placeholder="{{ __('messages.child_first_name') }}"
               class="w-full border rounded-lg px-3 py-2" required>
        <input name="child_last_name" value="{{ old('child_last_name') }}" placeholder="{{ __('messages.child_last_name') }}"
               class="w-full border rounded-lg px-3 py-2" required>
        <input type="date" name="child_birth_date" value="{{ old('child_birth_date') }}"
               class="w-full border rounded-lg px-3 py-2" required>

        <h2 class="font-semibold text-gray-700 pt-2">{{ __('messages.enroll_parent_info') }}</h2>
        <input name="parent_name" value="{{ old('parent_name') }}" placeholder="{{ __('messages.parent_name') }}"
               class="w-full border rounded-lg px-3 py-2" required>
        <input type="email" name="parent_email" value="{{ old('parent_email') }}" placeholder="{{ __('messages.parent_email') }}"
               class="w-full border rounded-lg px-3 py-2" required>
        <input name="parent_phone" value="{{ old('parent_phone') }}" placeholder="{{ __('messages.parent_phone') }}"
               class="w-full border rounded-lg px-3 py-2" required>
        <textarea name="note" placeholder="Napomena (nije obavezno)"
                  class="w-full border rounded-lg px-3 py-2">{{ old('note') }}</textarea>

        <button class="w-full py-2 rounded-lg font-semibold text-gray-900"
                style="background: var(--klub-sekundarna)">{{ __('messages.enroll_review') }} →</button>
    </form>
</div>
@endsection