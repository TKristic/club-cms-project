@extends('layouts.app')

@section('content')
{{-- HERO --}}
<section class="rounded-3xl overflow-hidden mb-10 relative"
         style="background: linear-gradient(135deg, var(--klub-primarna), color-mix(in srgb, var(--klub-primarna) 70%, black));">
    <div class="px-8 py-14 sm:py-20 text-center text-white relative z-10">
        @if($club?->logo)
            <img src="{{ asset('storage/'.$club->logo) }}" alt="{{ $club->name }}"
                 class="h-24 w-24 mx-auto mb-5 object-contain drop-shadow-lg">
        @endif
        <h1 class="text-4xl sm:text-5xl font-extrabold tracking-tight">{{ $club?->name }}</h1>
        <p class="mt-3 text-lg opacity-90">{{ __('messages.official_site') }}</p>
        <div class="mt-8 flex flex-wrap gap-3 justify-center">
            <a href="/vijesti" class="px-6 py-3 rounded-xl font-semibold text-gray-900 shadow-lg"
               style="background: var(--klub-sekundarna)">{{ __('messages.latest_news') }}</a>
            <a href="/upis" class="px-6 py-3 rounded-xl font-semibold bg-white/15 hover:bg-white/25 backdrop-blur border border-white/30">
                {{ __('messages.join_club') }}
            </a>
        </div>
    </div>
</section>

@if(count($upcoming) || count($recent))
<section class="grid gap-4 sm:grid-cols-2 mb-12">
    @if($next = ($upcoming[0] ?? null))
        <div class="bg-white rounded-2xl shadow p-5">
            <p class="text-xs font-semibold uppercase tracking-wide text-gray-400 mb-3">{{ __('messages.next_match') }}</p>
            @include('public.partials.match', ['m' => $next])
        </div>
    @endif
    @if($last = ($recent[0] ?? null))
        <div class="bg-white rounded-2xl shadow p-5">
            <p class="text-xs font-semibold uppercase tracking-wide text-gray-400 mb-3">{{ __('messages.previous_match') }}</p>
            @include('public.partials.match', ['m' => $last])
        </div>
    @endif
</section>
@endif

@if($weather && ($next = ($upcoming[0] ?? null)))
    <div class="bg-white rounded-2xl shadow p-4 mb-12 flex items-center justify-center gap-3 text-gray-700">
        <span class="text-3xl">{{ $weather['icon'] }}</span>
        <div class="text-center">
            <p class="text-sm text-gray-400">{{ __('messages.forecast_for_match') }} ({{ $next['date'] }})</p>
            <p class="font-semibold">{{ $weather['temp'] }}°C · {{ $weather['description'] }}</p>
        </div>
    </div>
@endif

@if($news->isNotEmpty())
<section class="mb-12">
    <div class="flex items-center justify-between mb-5">
        <h2 class="text-2xl font-bold" style="color: var(--klub-primarna)">{{ __('messages.latest_news') }}</h2>
        <a href="/vijesti" class="text-sm text-gray-500 hover:underline">{{ __('messages.all_news') }} →</a>
    </div>
    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
        @foreach($news as $article)
            <a href="{{ route('news.show', $article->slug) }}"
               class="block bg-white rounded-2xl shadow hover:shadow-lg transition overflow-hidden">
                @if($article->featured_image)
                    <img src="{{ asset('storage/'.$article->featured_image) }}" class="w-full h-40 object-cover">
                @else
                    <div class="w-full h-40" style="background: var(--klub-primarna)"></div>
                @endif
                <div class="p-4">
                    <p class="text-xs text-gray-400">{{ $article->published_at->format('d.m.Y.') }}</p>
                    <h3 class="font-semibold mt-1">{{ $article->title }}</h3>
                    <p class="text-sm text-gray-600 mt-2 line-clamp-2">{{ $article->excerpt }}</p>
                </div>
            </a>
        @endforeach
    </div>
</section>
@endif

@if($hns['ok'] ?? false)
<section class="grid gap-8 lg:grid-cols-5 mb-12">
    <div class="lg:col-span-3">
        <h2 class="text-2xl font-bold mb-4" style="color: var(--klub-primarna)">{{ __('messages.standings') }}</h2>
        <div class="bg-white rounded-2xl shadow overflow-hidden">
            <p class="px-4 py-2 text-xs text-gray-500 border-b">{{ $hns['standings']['title'] }}</p>
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-gray-400 text-xs">
                        <th class="text-left px-3 py-2 font-medium">#</th>
                        <th class="text-left px-2 py-2 font-medium">{{ __('messages.col_club') }}</th>
                        <th class="px-2 py-2 font-medium">{{ __('messages.col_played') }}</th>
                        <th class="px-2 py-2 font-medium">{{ __('messages.col_goaldiff') }}</th>
                        <th class="px-3 py-2 font-medium text-right">{{ __('messages.col_points') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($hns['standings']['rows'] as $row)
                        @php $isOurs = $row['club_id'] === $clubHnsId; @endphp
                        <tr class="border-t {{ $isOurs ? 'font-bold' : '' }}"
                            style="{{ $isOurs ? 'background: color-mix(in srgb, var(--klub-sekundarna) 25%, white);' : '' }}">
                            <td class="px-3 py-2 text-gray-500">{{ $row['position'] }}</td>
                            <td class="px-2 py-2">
                                <span class="inline-flex items-center gap-2">
                                    @if($row['logo'])<img src="{{ $row['logo'] }}" class="h-5 w-5 object-contain">@endif
                                    {{ $row['club'] }}
                                </span>
                            </td>
                            <td class="px-2 py-2 text-center text-gray-500">{{ $row['played'] }}</td>
                            <td class="px-2 py-2 text-center text-gray-500">{{ $row['gdiff'] }}</td>
                            <td class="px-3 py-2 text-right font-semibold">{{ $row['points'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="lg:col-span-2">
        <h2 class="text-2xl font-bold mb-4" style="color: var(--klub-primarna)">{{ __('messages.schedule') }}</h2>
        <div class="space-y-3">
            @forelse($upcoming as $m)
                <div class="bg-white rounded-xl shadow p-3">
                    @include('public.partials.match', ['m' => $m, 'compact' => true])
                </div>
            @empty
                <p class="text-gray-400 text-sm">{{ __('messages.no_upcoming') }}</p>
            @endforelse
        </div>
    </div>
</section>
@elseif(isset($hns['error']))
    <p class="text-center text-sm text-gray-400 mb-12">{{ __('messages.results_unavailable') }}</p>
@endif
@endsection