<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="theme-color" content="#04060b">
    <meta name="mobile-web-app-capable" content="yes">
    <title>Sign in &middot; {{ config('app.name', 'IMJPJ') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="deck-grain app-height overflow-hidden bg-ink-950 font-sans text-slate-300 antialiased">
    <div class="relative flex app-height flex-col overflow-y-auto overscroll-contain px-5 pb-[calc(env(safe-area-inset-bottom)_+_1.5rem)] pt-[calc(env(safe-area-inset-top)_+_3.5rem)]">

        <div class="pointer-events-none fixed -right-24 -top-24 h-72 w-72 rounded-full bg-signal-600/20 blur-3xl"></div>
        <div class="pointer-events-none fixed -bottom-28 -left-24 h-72 w-72 rounded-full bg-sky-500/10 blur-3xl"></div>

        <div class="relative flex flex-1 flex-col">
            <header class="animate-rise">
                <div class="grid h-14 w-14 place-items-center rounded-2xl bg-gradient-to-br from-signal-400 to-indigo-600 text-[15px] font-bold tracking-tight text-white shadow-[0_0_28px_-6px] shadow-signal-500/80">
                    IM
                </div>
                <h1 class="mt-5 text-[24px] font-semibold leading-tight tracking-tight text-white">Welcome back</h1>
                <p class="mt-1.5 text-[13.5px] leading-relaxed text-slate-400">
                    Sign in with your IMMS account to continue.
                </p>
            </header>

            @if ($errors->any())
                <div class="animate-rise mt-6 rounded-xl border border-rose-500/25 bg-rose-500/[0.08] px-4 py-3 text-[12.5px] leading-relaxed text-rose-200">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login.store') }}" class="animate-rise mt-6 space-y-4" style="animation-delay: 60ms">
                @csrf

                <div>
                    <label for="email" class="mb-1.5 block font-mono text-[9.5px] font-medium uppercase tracking-[0.16em] text-slate-500">
                        Email address
                    </label>
                    <input
                        id="email"
                        name="email"
                        type="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        autocomplete="username"
                        inputmode="email"
                        autocapitalize="none"
                        spellcheck="false"
                        placeholder="you@imjpj.my"
                        class="block w-full rounded-xl border border-white/10 bg-ink-900/60 px-4 py-3 text-[15px] text-white placeholder-slate-600 outline-none transition focus:border-signal-400 focus:ring-2 focus:ring-signal-500/40"
                    >
                </div>

                <div>
                    <label for="password" class="mb-1.5 block font-mono text-[9.5px] font-medium uppercase tracking-[0.16em] text-slate-500">
                        Password
                    </label>
                    <input
                        id="password"
                        name="password"
                        type="password"
                        required
                        autocomplete="current-password"
                        placeholder="••••••••"
                        class="block w-full rounded-xl border border-white/10 bg-ink-900/60 px-4 py-3 text-[15px] text-white placeholder-slate-600 outline-none transition focus:border-signal-400 focus:ring-2 focus:ring-signal-500/40"
                    >
                </div>

                <button
                    type="submit"
                    data-submit
                    class="flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-br from-signal-400 to-indigo-600 px-4 py-3.5 text-[14px] font-semibold text-white shadow-[0_0_28px_-8px] shadow-signal-500/90 transition active:scale-[0.99] disabled:opacity-70"
                >
                    <svg data-submit-spinner viewBox="0 0 24 24" fill="none" class="hidden h-4 w-4 animate-spin">
                        <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="2.5" class="opacity-25"/>
                        <path d="M21 12a9 9 0 0 0-9-9" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/>
                    </svg>
                    <span data-submit-label>Sign in</span>
                </button>
            </form>

            <div class="mt-auto pt-10 text-center">
                <p class="font-mono text-[9.5px] uppercase tracking-[0.14em] text-slate-700">
                    {{ parse_url(config('imms.api_url'), PHP_URL_HOST) ?: config('imms.api_url') }}
                </p>
                <p class="mt-1.5 text-[10.5px] text-slate-700">
                    Protected system for Institut Memandu JPJ.
                </p>
            </div>
        </div>
    </div>
</body>
</html>
