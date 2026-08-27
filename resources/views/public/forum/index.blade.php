@extends('layouts.app')
@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-3xl font-bold" style="color: var(--klub-primarna)">{{ __('messages.forum_title') }}</h1>
    @auth
        <a href="{{ route('forum.create') }}" class="px-4 py-2 rounded-lg font-semibold text-white"
           style="background: var(--klub-primarna)">{{ __('messages.new_topic') }}</a>
    @else
        <a href="{{ route('login') }}" class="text-sm underline">{{ __('messages.login_to_post') }}</a>
    @endauth
</div>

@if($topics->isEmpty())
    <p class="text-gray-500">{{ __('messages.no_topics') }}</p>
@else
    <div class="bg-white rounded-xl shadow divide-y">
        @foreach($topics as $topic)
            <a href="{{ route('forum.show', $topic) }}" class="flex items-center justify-between p-4 hover:bg-gray-50">
                <div>
                    <p class="font-semibold">{{ $topic->title }}</p>
                    <p class="text-xs text-gray-400">{{ $topic->author->name }} · {{ $topic->created_at->format('d.m.Y.') }}</p>
                </div>
                <span class="text-sm text-gray-500">{{ $topic->posts_count }} {{ __('messages.replies') }}</span>
            </a>
        @endforeach
    </div>
    <div class="mt-6">{{ $topics->links() }}</div>
@endif
@endsection