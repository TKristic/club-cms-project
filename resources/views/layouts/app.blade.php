<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $club?->name ?? 'Klub CMS' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --klub-primarna: {{ $club?->primary_color ?? '#1e3a8a' }};
            --klub-sekundarna: {{ $club?->secondary_color ?? '#f59e0b' }};
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-900 min-h-screen flex flex-col">
    <header class="sticky top-0 z-50 shadow-sm" style="background: var(--klub-primarna)">
        <div class="max-w-6xl mx-auto px-4">
            <div class="flex items-center justify-between h-16 text-white">
                <a href="/" class="flex items-center gap-3 font-bold text-lg shrink-0">
                    @if($club?->logo)
                        <img src="{{ asset('storage/'.$club->logo) }}" class="h-9 w-9 object-contain">
                    @endif
                    <span class="hidden sm:inline">{{ $club?->name }}</span>
                </a>

                <nav class="hidden lg:flex items-center gap-1 text-sm font-medium">
                    @php
                        $links = [
                            '/' => 'home', '/vijesti' => 'news', '/momcad' => 'team',
                            '/galerija' => 'gallery', '/forum' => 'forum', '/upis' => 'enroll', '/kontakt' => 'contact',
                        ];
                    @endphp
                    @foreach($links as $url => $key)
                        <a href="{{ $url }}"
                        class="px-3 py-2 rounded-lg hover:bg-white/15 transition {{ request()->is(trim($url,'/') ?: '/') ? 'bg-white/20' : '' }}">
                            {{ __('messages.' . $key) }}
                        </a>
                    @endforeach
                </nav>

                <div class="flex items-center gap-3 text-sm">
                        <div class="flex items-center gap-1">
                            <a href="{{ route('locale.set', 'hr') }}"
                            class="px-2 py-1 rounded {{ app()->getLocale() === 'hr' ? 'bg-white/25 font-semibold' : 'opacity-70 hover:opacity-100' }}">HR</a>
                            <a href="{{ route('locale.set', 'en') }}"
                            class="px-2 py-1 rounded {{ app()->getLocale() === 'en' ? 'bg-white/25 font-semibold' : 'opacity-70 hover:opacity-100' }}">EN</a>
                        </div>

                    @auth
                        <span class="hidden sm:inline opacity-90">{{ auth()->user()->name }}</span>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="px-3 py-1.5 rounded-lg bg-white/15 hover:bg-white/25">{{ __('messages.logout')}}</button>
                        </form>
                    @else
                        <a href="/prijava" class="px-3 py-1.5 rounded-lg bg-white/15 hover:bg-white/25">{{ __('messages.login')}}</a>
                    @endauth
                </div>
            </div>

            <nav class="lg:hidden flex gap-1 overflow-x-auto pb-3 text-sm text-white/90">
                @foreach($links as $url => $key)
                    <a href="{{ $url }}" class="px-3 py-1.5 rounded-lg bg-white/10 whitespace-nowrap">{{ __('messages.' . $key) }}</a>
                @endforeach
            </nav>
        </div>
    </header>

    <main class="max-w-6xl mx-auto px-4 py-8 w-full flex-1">
        @yield('content')
    </main>

    <footer class="text-white/90 mt-12" style="background: color-mix(in srgb, var(--klub-primarna) 85%, black)">
        <div class="max-w-6xl mx-auto px-4 py-8 grid gap-6 sm:grid-cols-3 text-sm">
            <div>
                <div class="flex items-center gap-2 font-bold text-white mb-2">
                    @if($club?->logo)<img src="{{ asset('storage/'.$club->logo) }}" class="h-8 w-8 object-contain">@endif
                    {{ $club?->name }}
                </div>
                <p class="opacity-75">{{ __('messages.footer_tagline') }}</p>
            </div>
            <div>
                <p class="font-semibold text-white mb-2">{{ __('messages.contact') }}</p>
                @if($club?->contact_email)<p>✉ {{ $club->contact_email }}</p>@endif
                @if($club?->contact_phone)<p>☎ {{ $club->contact_phone }}</p>@endif
                @if($club?->address)<p>📍 {{ $club->address }}</p>@endif
            </div>
            <div>
                <p class="font-semibold text-white mb-2">{{ __('messages.quick_links') }}</p>
                <a href="/vijesti" class="block hover:underline">{{ __('messages.news') }}</a>
                <a href="/momcad" class="block hover:underline">{{ __('messages.team') }}</a>
                <a href="/upis" class="block hover:underline">{{ __('messages.join_club') }}</a>
            </div>
        </div>
        <div class="border-t border-white/10 py-4 text-center text-xs opacity-60">
            © {{ date('Y') }} {{ $club?->name }}. {{ __('messages.rights_reserved') }}
        </div>
    </footer>
</body>
</html>