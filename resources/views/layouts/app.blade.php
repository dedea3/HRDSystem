<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'HRD System') }} - {{ $title }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="h-full bg-slate-950 font-[Inter] text-slate-100 antialiased">

<div x-data="{ sidebarOpen: false }" class="flex h-full">

    {{-- ══════════ SIDEBAR ══════════ --}}
    {{-- Overlay mobile --}}
    <div x-show="sidebarOpen" x-cloak @click="sidebarOpen=false"
         class="fixed inset-0 z-20 bg-black/60 lg:hidden"></div>

    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
           class="fixed inset-y-0 left-0 z-30 w-64 transform transition-transform duration-300 ease-in-out
                  lg:static lg:translate-x-0 flex flex-col
                  bg-gradient-to-b from-slate-900 to-slate-950 border-r border-slate-800">

        {{-- Logo --}}
        <div class="flex h-16 shrink-0 items-center gap-3 px-6 border-b border-slate-800">
            <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-indigo-600 shadow-lg shadow-indigo-900">
                <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-sm font-bold text-white">HRD System</p>
                <p class="text-xs text-slate-400">Manajemen SDM</p>
            </div>
        </div>

        {{-- Nav --}}
        <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1">

            {{-- Dashboard --}}
            @include('components.sidebar-link', [
                'route' => 'dashboard',
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>',
                'label' => 'Dashboard',
            ])

            {{-- Karyawan (Admin/HRD) --}}
            @if(auth()->user()->hasAnyRole(['admin','hrd']))
            <div class="pt-2">
                <p class="px-3 pb-1 text-xs font-semibold text-slate-500 uppercase tracking-wider">Manajemen</p>
                @include('components.sidebar-link', [
                    'route' => 'employees.index',
                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>',
                    'label' => 'Karyawan',
                ])
                @include('components.sidebar-link', [
                    'route' => 'attendance.index',
                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>',
                    'label' => 'Absensi',
                ])
                @include('components.sidebar-link', [
                    'route' => 'leave.index',
                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>',
                    'label' => 'Manajemen Cuti',
                ])
                @include('components.sidebar-link', [
                    'route' => 'holiday.index',
                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>',
                    'label' => 'Hari Libur',
                ])
                @include('components.sidebar-link', [
                    'route' => 'salary.index',
                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>',
                    'label' => 'Penggajian',
                ])
                @include('components.sidebar-link', [
                    'route' => 'salary.slips',
                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>',
                    'label' => 'Slip Gaji',
                ])
            </div>
            @endif

            {{-- Staff menu --}}
            @if(auth()->user()->hasRole('staff'))
            <div class="pt-2">
                <p class="px-3 pb-1 text-xs font-semibold text-slate-500 uppercase tracking-wider">Saya</p>
                @include('components.sidebar-link', [
                    'route' => 'attendance.my',
                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>',
                    'label' => 'Absensi Saya',
                ])
                @include('components.sidebar-link', [
                    'route' => 'leave.my',
                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>',
                    'label' => 'Cuti Saya',
                ])
                @include('components.sidebar-link', [
                    'route' => 'salary.my',
                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>',
                    'label' => 'Slip Gaji',
                ])
            </div>
            @endif

        </nav>

        {{-- User info --}}
        <div class="shrink-0 border-t border-slate-800 p-4">
            <div class="flex items-center gap-3">
                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-indigo-600 text-sm font-bold text-white">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="min-w-0 flex-1">
                    <p class="truncate text-sm font-semibold text-white">{{ auth()->user()->name }}</p>
                    <p class="truncate text-xs text-indigo-400">{{ auth()->user()->primary_role_label }}</p>
                </div>
                <a href="{{ route('profile.edit') }}" class="shrink-0 text-slate-400 hover:text-white transition-colors">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </a>
            </div>
        </div>
    </aside>

    {{-- ══════════ MAIN ══════════ --}}
    <div class="flex flex-1 flex-col min-w-0 overflow-hidden">

        {{-- Topbar --}}
        <header class="flex h-16 shrink-0 items-center justify-between border-b border-slate-800 bg-slate-900/80 backdrop-blur px-4 lg:px-6">
            <div class="flex items-center gap-3">
                {{-- Hamburger --}}
                <button @click="sidebarOpen=!sidebarOpen" class="lg:hidden text-slate-400 hover:text-white">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                <h1 class="text-lg font-semibold text-white">{{ $title }}</h1>
            </div>

            <div class="flex items-center gap-3">
                {{-- Date/Time --}}
                <div class="hidden sm:flex items-center gap-2 rounded-lg bg-slate-800 px-3 py-1.5">
                    <svg class="h-4 w-4 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="text-xs text-slate-300" id="clock">{{ now()->format('d M Y, H:i') }}</span>
                </div>
                {{-- Logout --}}
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="flex items-center gap-2 rounded-lg bg-slate-800 px-3 py-1.5 text-xs text-slate-300 hover:bg-red-900/50 hover:text-red-400 transition-colors">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        Keluar
                    </button>
                </form>
            </div>
        </header>

        {{-- Flash messages --}}
        <div class="px-4 lg:px-6 pt-4 space-y-2">
            @if(session('success'))
            <div id="flash-success" class="flex items-center gap-3 rounded-xl border border-emerald-700/50 bg-emerald-900/30 px-4 py-3 text-sm text-emerald-300">
                <svg class="h-5 w-5 shrink-0 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('success') }}
            </div>
            @endif
            @if(session('error'))
            <div id="flash-error" class="flex items-center gap-3 rounded-xl border border-rose-700/50 bg-rose-900/30 px-4 py-3 text-sm text-rose-300">
                <svg class="h-5 w-5 shrink-0 text-rose-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('error') }}
            </div>
            @endif
        </div>

        {{-- Page content --}}
        <main class="flex-1 overflow-y-auto p-4 lg:p-6">
            {{ $slot }}
        </main>
    </div>
</div>

<script>
// Live clock
function updateClock() {
    const el = document.getElementById('clock');
    if (!el) return;
    const now = new Date();
    const options = { day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' };
    el.textContent = now.toLocaleDateString('id-ID', options).replace('.', ':');
}
setInterval(updateClock, 60000);

// Auto-hide flash messages
setTimeout(() => {
    ['flash-success','flash-error'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.style.display = 'none';
    });
}, 5000);
</script>
@stack('scripts')
</body>
</html>
