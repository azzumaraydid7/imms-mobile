@php
    // The API payload is the source of truth; the session copy keeps the chrome
    // populated while a request is in flight.
    $currentUser = $user ?? session('imms.user', []);
    $roleSlugs = $currentUser['roles'] ?? [];
    $displayName = $currentUser['name'] ?? 'Operator';

    $nativeShell = (bool) config('nativephp-internal.running');

    $overview = config('navigation.overview');
    $sections = config('navigation.sections', []);

    $overviewActive = request()->routeIs($overview['route']);

    // Bottom bar = Overview, the pinned shortcuts, then the "More" sheet.
    $tabItems = [[
        'label' => $overview['label'],
        'icon' => $overview['icon'],
        'href' => Route::has($overview['route']) ? route($overview['route']) : null,
        'active' => $overviewActive,
    ]];

    $shortcutActive = false;

    foreach (config('navigation.tabs', []) as $tab) {
        $isLive = $tab['route'] !== null && Route::has($tab['route']);
        $isActive = $isLive && request()->routeIs($tab['route']);

        $shortcutActive = $shortcutActive || $isActive;

        $tabItems[] = [
            'label' => $tab['label'],
            'icon' => $tab['icon'],
            'href' => $isLive ? route($tab['route']) : null,
            'active' => $isActive,
        ];
    }

    $moreActive = ! $overviewActive && ! $shortcutActive;
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#04060b">
    <meta name="mobile-web-app-capable" content="yes">
    <title>@yield('title', 'Overview') &middot; {{ config('app.name', 'IMJPJ') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="deck-grain app-height overflow-hidden bg-ink-950 font-sans text-slate-300 antialiased">
    <div class="relative z-10 flex app-height flex-col">

        {{-- ── App bar ─────────────────────────────────────────────────────── --}}
        <header class="relative z-30 shrink-0 border-b border-white/[0.06] bg-ink-900/70 pt-[env(safe-area-inset-top)] backdrop-blur-xl">
            <div class="flex h-14 items-center gap-3 px-4">
                <span class="grid h-8 w-8 shrink-0 place-items-center rounded-lg bg-gradient-to-br from-signal-400 to-indigo-600 text-[10px] font-bold tracking-tight text-white shadow-[0_0_18px_-4px] shadow-signal-500/70">IM</span>

                <div class="min-w-0 flex-1">
                    <p class="truncate text-[13.5px] font-semibold leading-tight tracking-tight text-slate-100">
                        @yield('title', 'Overview')
                    </p>
                    <p class="truncate font-mono text-[9.5px] uppercase tracking-[0.16em] text-slate-600">
                        Institut Memandu JPJ
                    </p>
                </div>

                <span class="flex shrink-0 items-center gap-1.5 rounded-full border border-white/[0.07] bg-white/[0.03] px-2.5 py-1">
                    <span class="h-1.5 w-1.5 rounded-full {{ $nativeShell ? 'bg-emerald-400 animate-beacon' : 'bg-amber-400' }}"></span>
                    <span class="font-mono text-[9.5px] uppercase tracking-[0.14em] text-slate-400">
                        {{ $nativeShell ? 'shell' : 'web' }}
                    </span>
                </span>
            </div>
        </header>

        {{-- ── Content ─────────────────────────────────────────────────────── --}}
        <main class="deck-scroll min-h-0 flex-1 overflow-y-auto overscroll-contain">
            <div class="px-4 pb-8 pt-4">
                @yield('content')
            </div>
        </main>

        {{-- ── Tab bar ─────────────────────────────────────────────────────── --}}
        <nav class="relative z-40 shrink-0 border-t border-white/[0.07] bg-ink-900/85 pb-[env(safe-area-inset-bottom)] backdrop-blur-xl">
            <div class="flex h-[60px] items-stretch">
                @foreach ($tabItems as $item)
                    @if ($item['href'])
                        <a
                            href="{{ $item['href'] }}"
                            @if ($item['active']) aria-current="page" @endif
                            class="relative flex flex-1 flex-col items-center justify-center gap-1 transition-colors duration-150 {{ $item['active'] ? 'text-signal-300' : 'text-slate-500 active:text-slate-300' }}"
                        >
                            @if ($item['active'])
                                <span class="absolute top-0 h-[2px] w-8 rounded-b-full bg-signal-400 shadow-[0_0_12px_1px] shadow-signal-500/60"></span>
                            @endif
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" class="h-[19px] w-[19px]">{!! $item['icon'] !!}</svg>
                            <span class="text-[10px] font-medium tracking-tight">{{ $item['label'] }}</span>
                        </a>
                    @else
                        <div
                            aria-disabled="true"
                            class="relative flex flex-1 cursor-not-allowed flex-col items-center justify-center gap-1 text-slate-600 opacity-60"
                        >
                            <span class="absolute right-3 top-2.5 h-1.5 w-1.5 rounded-full bg-slate-700"></span>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" class="h-[19px] w-[19px]">{!! $item['icon'] !!}</svg>
                            <span class="text-[10px] font-medium tracking-tight">{{ $item['label'] }}</span>
                        </div>
                    @endif
                @endforeach

                {{-- More --}}
                <button
                    type="button"
                    data-sheet-open
                    aria-controls="module-sheet"
                    class="relative flex flex-1 flex-col items-center justify-center gap-1 transition-colors duration-150 {{ $moreActive ? 'text-signal-300' : 'text-slate-500 active:text-slate-300' }}"
                >
                    @if ($moreActive)
                        <span class="absolute top-0 h-[2px] w-8 rounded-b-full bg-signal-400 shadow-[0_0_12px_1px] shadow-signal-500/60"></span>
                    @endif
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-[19px] w-[19px]">
                        <path d="M5 12h.01M12 12h.01M19 12h.01"/>
                    </svg>
                    <span class="text-[10px] font-medium tracking-tight">More</span>
                </button>
            </div>
        </nav>
    </div>

    {{-- ── "More" sheet ────────────────────────────────────────────────────── --}}
    <div id="module-sheet" data-sheet hidden class="fixed inset-0 z-[60]">
        <button
            type="button"
            data-sheet-close
            aria-label="Close modules"
            class="absolute inset-0 h-full w-full cursor-default bg-ink-950/70 backdrop-blur-sm"
        ></button>

        <div class="deck-scroll absolute inset-x-0 bottom-0 max-h-[80%] overflow-y-auto rounded-t-[26px] border-t border-white/10 bg-ink-850 shadow-[0_-24px_60px_-20px_rgba(0,0,0,0.9)] animate-rise">
            <div class="flex justify-center pt-3">
                <span class="h-1 w-10 rounded-full bg-white/15"></span>
            </div>

            <div class="px-4 pb-[calc(env(safe-area-inset-bottom)_+_1.5rem)] pt-3">
                <div class="flex items-center justify-between">
                    <h2 class="text-[14px] font-semibold tracking-tight text-slate-100">All modules</h2>
                    <button
                        type="button"
                        data-sheet-close
                        aria-label="Close"
                        class="grid h-8 w-8 place-items-center rounded-lg text-slate-500 transition-colors duration-150 hover:bg-white/[0.06] hover:text-slate-200"
                    >
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4">
                            <path d="M6 6l12 12M18 6 6 18"/>
                        </svg>
                    </button>
                </div>

                @foreach ($sections as $section)
                    <span class="mb-1.5 mt-5 block font-mono text-[9.5px] font-medium uppercase tracking-[0.18em] text-slate-600">
                        {{ $section['label'] }}
                    </span>

                    <div class="grid grid-cols-2 gap-2">
                        @foreach ($section['items'] as $item)
                            @php($isLive = $item['route'] !== null && Route::has($item['route']))

                            @if ($isLive)
                                <a
                                    href="{{ route($item['route']) }}"
                                    class="flex items-center gap-2.5 rounded-xl border border-white/[0.07] bg-ink-800/60 px-3 py-2.5 text-[12.5px] font-medium text-slate-300 transition-colors duration-150 active:bg-signal-500/[0.12]"
                                >
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" class="h-[17px] w-[17px] shrink-0 text-signal-300">{!! $item['icon'] !!}</svg>
                                    <span class="truncate">{{ $item['label'] }}</span>
                                </a>
                            @else
                                <div class="flex cursor-not-allowed items-center gap-2.5 rounded-xl border border-white/[0.05] bg-ink-800/25 px-3 py-2.5 text-[12.5px] font-medium text-slate-500">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" class="h-[17px] w-[17px] shrink-0 text-slate-600">{!! $item['icon'] !!}</svg>
                                    <span class="truncate">{{ $item['label'] }}</span>
                                    <span class="ml-auto shrink-0 rounded border border-white/[0.07] px-1 py-0.5 font-mono text-[8.5px] uppercase tracking-[0.1em] text-slate-600">
                                        soon
                                    </span>
                                </div>
                            @endif
                        @endforeach
                    </div>
                @endforeach

                {{-- Session --}}
                <div class="mt-6 h-px bg-white/[0.06]"></div>

                <div class="mt-4 flex items-center gap-3">
                    <span class="grid h-10 w-10 shrink-0 place-items-center rounded-full border border-white/10 bg-ink-800 text-[12px] font-semibold text-slate-300">
                        {{ strtoupper(substr($displayName, 0, 2)) }}
                    </span>

                    <div class="min-w-0 flex-1">
                        <p class="truncate text-[13px] font-medium text-slate-200">{{ $displayName }}</p>
                        <p class="truncate font-mono text-[10px] uppercase tracking-[0.12em] text-slate-600">
                            {{ $roleSlugs === [] ? 'no role' : implode(' · ', $roleSlugs) }}
                        </p>
                    </div>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button
                            type="submit"
                            class="flex items-center gap-2 rounded-xl border border-rose-500/20 bg-rose-500/[0.08] px-3.5 py-2 text-[12.5px] font-medium text-rose-300 transition-colors duration-150 active:bg-rose-500/[0.18]"
                        >
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" class="h-[15px] w-[15px]">
                                <path d="M15 4h3a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2h-3"/><path d="M10 17l-5-5 5-5"/><path d="M5 12h10"/>
                            </svg>
                            Sign out
                        </button>
                    </form>
                </div>

                <p class="mt-4 text-center font-mono text-[9.5px] uppercase tracking-[0.14em] text-slate-700">
                    Laravel {{ app()->version() }} · PHP {{ PHP_VERSION }}
                </p>
            </div>
        </div>
    </div>
</body>
</html>
