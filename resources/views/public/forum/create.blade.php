@extends('layouts.app')
@section('content')
<div class="max-w-xl mx-auto">
    <h1 class="text-3xl font-bold mb-6" style="color: var(--klub-primarna)">{{ __('messages.new_topic') }}</h1>

    @if($errors->any())
        <div class="bg-red-100 text-red-800 rounded-lg p-4 mb-6 text-sm">
            @foreach($errors->all() as $e) <div>{{ $e }}</div> @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('forum.storeTopic') }}" class="bg-white rounded-xl shadow p-6 space-y-4">
        @csrf
        <input name="title" value="{{ old('title') }}" placeholder="{{ __('messages.topic_title') }}"
               class="w-full border rounded-lg px-3 py-2" required>
        <textarea name="body" rows="6" placeholder="{{ __('messages.your_message') }}"
                  class="w-full border rounded-lg px-3 py-2" required>{{ old('body') }}</textarea>
        <button class="w-full py-2 rounded-lg font-semibold text-white"
                style="background: var(--klub-primarna)">{{ __('messages.publish_topic') }}</button>
    </form>
</div>
@endsection