@php $compact = $compact ?? false; @endphp
<div class="flex items-center justify-between gap-2">
    <div class="flex items-center gap-2 flex-1 min-w-0">
        @if($m['home_logo'])<img src="{{ $m['home_logo'] }}" class="h-7 w-7 object-contain shrink-0">@endif
        <span class="truncate {{ $compact ? 'text-sm' : 'font-semibold' }}">{{ $m['home'] }}</span>
    </div>

    <div class="text-center px-2 shrink-0">
        @if($m['played'])
            <span class="text-lg font-bold">{{ $m['res1'] }}:{{ $m['res2'] }}</span>
        @else
            <span class="text-gray-400 text-sm">vs</span>
        @endif
    </div>

    <div class="flex items-center gap-2 flex-1 min-w-0 justify-end">
        <span class="truncate text-right {{ $compact ? 'text-sm' : 'font-semibold' }}">{{ $m['away'] }}</span>
        @if($m['away_logo'])<img src="{{ $m['away_logo'] }}" class="h-7 w-7 object-contain shrink-0">@endif
    </div>
</div>
<p class="text-xs text-gray-400 mt-2 text-center">{{ $m['date'] }}</p>