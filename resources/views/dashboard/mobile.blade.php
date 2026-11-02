@extends('layouts.mobile')

@section('title', 'Overview')

@section('content')
    <div class="space-y-4">

        {{-- ── Hero ────────────────────────────────────────────────────────── --}}
        <section class="animate-rise" style="animation-delay: 30ms">
            <div class="relative overflow-hidden rounded-2xl border border-white/[0.07] bg-gradient-to-br from-ink-800/80 via-ink-850/60 to-ink-900/40 p-5">
                <div class="pointer-events-none absolute -right-10 -top-16 h-40 w-40 rounded-full bg-signal-500/20 blur-3xl"></div>
                <div class="pointer-events-none absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-signal-400/50 to-transparent"></div>

                <div class="relative">
                    <p class="font-mono text-[10px] font-medium uppercase tracking-[0.2em] text-signal-300/80">
                        {{ $greeting }}
                    </p>
                    <h1 class="mt-1.5 truncate text-[20px] font-semibold leading-tight tracking-tight text-white">
                        {{ $user['name'] ?? 'Operator' }}
                    </h1>
                    <p class="mt-0.5 truncate font-mono text-[11.5px] text-slate-500">{{ $user['email'] ?? '' }}</p>

                    <div class="mt-3.5 flex flex-wrap items-center gap-1.5">
                        @forelse ($roles as $role)
                            <span class="inline-flex items-center gap-1.5 rounded-full border border-signal-400/25 bg-signal-500/[0.1] px-2.5 py-0.5 text-[11px] font-medium text-signal-200">
                                <span class="h-1.5 w-1.5 rounded-full bg-signal-400"></span>
                                {{ $role }}
                            </span>
                        @empty
                            <span class="rounded-full border border-amber-400/25 bg-amber-400/[0.08] px-2.5 py-0.5 text-[11px] font-medium text-amber-300">
                                No role assigned
                            </span>
                        @endforelse
                    </div>

                    <div class="mt-4 flex items-center justify-between border-t border-white/[0.06] pt-3.5">
                        <span class="font-mono text-[10px] uppercase tracking-[0.14em] text-slate-600">
                            {{ $user['status'] ?? 'unknown' }}
                        </span>
                        <span class="font-mono text-[10px] text-slate-600">
                            @if (! empty($user['last_login_at']))
                                seen {{ \Illuminate\Support\Carbon::parse($user['last_login_at'])->diffForHumans() }}
                            @else
                                first sign-in
                            @endif
                        </span>
                    </div>
                </div>
            </div>
        </section>

        {{-- ── Stat tiles ──────────────────────────────────────────────────── --}}
        <section class="grid grid-cols-2 gap-3">
            @foreach ($stats as $index => $stat)
                <div
                    class="animate-rise rounded-xl border border-white/[0.07] bg-ink-850/50 p-3.5"
                    style="animation-delay: {{ 80 + ($index * 45) }}ms"
                >
                    <p class="font-mono text-[9.5px] font-medium uppercase tracking-[0.16em] text-slate-500">
                        {{ $stat['label'] }}
                    </p>
                    <p class="mt-2 font-mono text-[22px] font-medium leading-none tabular-nums text-slate-100">
                        {{ $stat['value'] }}
                    </p>
                    <p class="mt-1.5 truncate text-[10.5px] text-slate-500">{{ $stat['caption'] }}</p>
                </div>
            @endforeach
        </section>

        {{-- ── Roles ───────────────────────────────────────────────────────── --}}
        {{-- The API hands back role slugs rather than full records, so the rows
             show the slug and its humanised label. --}}
        <section class="animate-rise" style="animation-delay: 280ms">
            <div class="rounded-2xl border border-white/[0.07] bg-ink-850/40">
                <header class="flex items-center justify-between border-b border-white/[0.06] px-4 py-3">
                    <div class="flex items-center gap-2">
                        <h2 class="text-[12.5px] font-semibold tracking-tight text-slate-200">Roles</h2>
                        <span class="rounded-md bg-white/[0.05] px-1.5 py-0.5 font-mono text-[9.5px] text-slate-400">
                            {{ count($roles) }}
                        </span>
                    </div>
                    <span class="font-mono text-[9.5px] uppercase tracking-[0.14em] text-slate-600">RBAC</span>
                </header>

                <div class="divide-y divide-white/[0.05]">
                    @forelse ($roles as $role)
                        <article class="flex items-center gap-3 px-4 py-3.5">
                            <span class="grid h-8 w-8 shrink-0 place-items-center rounded-lg border border-signal-400/20 bg-signal-500/[0.08] text-signal-300">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4">
                                    <path d="M12 3l7 3v5.5c0 4.3-3 7.6-7 9-4-1.4-7-4.7-7-9V6z"/><path d="M9.5 12l1.8 1.8L15 10"/>
                                </svg>
                            </span>

                            <h3 class="min-w-0 flex-1 truncate text-[13px] font-medium text-slate-100">
                                {{ \Illuminate\Support\Str::headline($role) }}
                            </h3>

                            <span class="shrink-0 rounded border border-white/[0.08] px-1.5 py-0.5 font-mono text-[9.5px] text-slate-400">
                                {{ $role }}
                            </span>
                        </article>
                    @empty
                        <div class="px-4 py-8 text-center">
                            <p class="text-[12.5px] text-slate-500">This account has no roles yet.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </section>

        {{-- ── Module readiness ────────────────────────────────────────────── --}}
        <section class="animate-rise" style="animation-delay: 340ms">
            <div class="rounded-2xl border border-white/[0.07] bg-ink-850/40">
                <header class="flex items-center justify-between border-b border-white/[0.06] px-4 py-3">
                    <h2 class="text-[12.5px] font-semibold tracking-tight text-slate-200">Module readiness</h2>
                    <span class="font-mono text-[9.5px] uppercase tracking-[0.14em] text-slate-600">platform</span>
                </header>

                <ul class="grid grid-cols-2 gap-px bg-white/[0.05] p-px">
                    @foreach ($modules as $module)
                        @php($isLive = $module['status'] === 'live')
                        <li class="flex items-center gap-2 bg-ink-850/80 px-3 py-2.5">
                            <span class="h-1.5 w-1.5 shrink-0 rounded-full {{ $isLive ? 'bg-emerald-400' : 'bg-slate-600' }}"></span>
                            <span class="truncate text-[11.5px] {{ $isLive ? 'font-medium text-slate-200' : 'text-slate-500' }}">
                                {{ $module['label'] }}
                            </span>
                            @if ($isLive)
                                <span class="ml-auto font-mono text-[8.5px] uppercase tracking-[0.1em] text-emerald-400/80">live</span>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
        </section>

        {{-- ── Permissions ────────────────────────────────────────────────────── --}}
        <section class="animate-rise space-y-2" style="animation-delay: 400ms">
            <div class="flex items-center justify-between px-1">
                <h2 class="text-[12.5px] font-semibold tracking-tight text-slate-200">Effective permissions</h2>
                <span class="font-mono text-[9.5px] uppercase tracking-[0.14em] text-slate-600">
                    {{ $permissions->count() }} · {{ $permissionGroupCount }} {{ \Illuminate\Support\Str::plural('group', $permissionGroupCount) }}
                </span>
            </div>

            @forelse ($permissionGroups as $group => $groupPermissions)
                <details class="group overflow-hidden rounded-xl border border-white/[0.07] bg-ink-850/40">
                    <summary class="flex cursor-pointer list-none items-center gap-2 px-4 py-3 transition-colors duration-150 active:bg-white/[0.03]">
                        <span class="font-mono text-[10px] font-medium uppercase tracking-[0.16em] text-signal-300/80">
                            {{ $group }}
                        </span>
                        <span class="rounded-md bg-white/[0.05] px-1.5 py-0.5 font-mono text-[9.5px] text-slate-400">
                            {{ $groupPermissions->count() }}
                        </span>
                        <span class="ml-auto h-px flex-1 bg-white/[0.05]"></span>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" class="h-3.5 w-3.5 shrink-0 text-slate-500 transition-transform duration-200 group-open:rotate-180">
                            <path d="M6 9l6 6 6-6"/>
                        </svg>
                    </summary>

                    <div class="flex flex-wrap gap-1.5 border-t border-white/[0.05] px-4 pb-3.5 pt-3">
                        @foreach ($groupPermissions as $permission)
                            <span class="inline-flex items-center gap-1.5 rounded-md border border-white/[0.07] bg-ink-800/60 px-2 py-1">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" class="h-2.5 w-2.5 text-signal-300/70">
                                    <path d="M9 12l2 2 4-4"/><circle cx="12" cy="12" r="9"/>
                                </svg>
                                <span class="font-mono text-[10.5px] text-slate-400">{{ $permission }}</span>
                            </span>
                        @endforeach
                    </div>
                </details>
            @empty
                <div class="rounded-xl border border-white/[0.07] bg-ink-850/40 px-4 py-10 text-center">
                    <p class="text-[12.5px] text-slate-500">No permissions are granted through this account's roles.</p>
                    <p class="mt-1 font-mono text-[10.5px] text-slate-600">Assign a role to inherit its permission set.</p>
                </div>
            @endforelse
        </section>
    </div>
@endsection
