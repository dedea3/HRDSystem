<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'HRD System') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-950 font-[Inter] text-slate-100 antialiased min-h-screen relative selection:bg-indigo-500/30">

    {{-- Background blobs --}}
    <div class="absolute top-0 left-0 w-full h-full overflow-hidden -z-10 pointer-events-none">
        <div class="absolute top-0 left-1/4 w-[40rem] h-[40rem] rounded-full bg-indigo-600/10 blur-[120px]"></div>
        <div class="absolute bottom-0 right-1/4 w-[30rem] h-[30rem] rounded-full bg-violet-600/10 blur-[100px]"></div>
    </div>

    {{-- Navbar --}}
    <header class="absolute top-0 w-full p-6 lg:p-10 z-20">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-600 shadow-xl shadow-indigo-900/50">
                    <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <span class="text-xl font-bold tracking-tight text-white">HRD System</span>
            </div>

            <nav>
                @auth
                    <a href="{{ url('/dashboard') }}" class="btn btn-primary">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-primary shadow-indigo-900/40">Log in</a>
                @endauth
            </nav>
        </div>
    </header>

    {{-- Hero --}}
    <main class="relative z-10 flex min-h-screen items-center justify-center px-6 lg:px-8">
        <div class="mx-auto max-w-3xl text-center">
            
            <div class="mb-8 flex justify-center">
                <span class="badge badge-info px-4 py-1.5 text-sm rounded-full border border-indigo-700/50 bg-indigo-900/30 text-indigo-300 ring-1 ring-inset ring-indigo-500/20 shadow-[0_0_20px_rgba(99,102,241,0.2)]">
                    Versi 1.0 Terbaru
                </span>
            </div>

            <h1 class="text-5xl font-extrabold tracking-tight text-white sm:text-7xl mb-6 leading-tight drop-shadow-2xl">
                Platform <span class="bg-gradient-to-r from-indigo-400 to-violet-400 bg-clip-text text-transparent">Manajemen SDM</span> Modern
            </h1>
            
            <p class="mt-4 text-lg leading-relaxed text-slate-400 sm:text-xl">
                Kelola data karyawan, jam absensi harian, otomatisasi pencatatan cuti, hingga pembuatan slip gaji dalam satu platform yang terintegrasi dan responsif.
            </p>
            
            <div class="mt-10 flex flex-col items-center justify-center gap-4 sm:flex-row">
                @auth
                    <a href="{{ url('/dashboard') }}" class="btn btn-primary text-base px-8 py-3.5 shadow-[0_0_30px_rgba(99,102,241,0.3)]">
                        Masuk ke Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-primary text-base px-8 py-3.5 shadow-[0_0_30px_rgba(99,102,241,0.3)]">
                        Login Karyawan
                    </a>
                @endauth
            </div>
            
            <div class="mt-20 grid grid-cols-2 lg:grid-cols-4 gap-8 border-t border-slate-800/50 pt-10 text-slate-500">
                <div class="text-center">
                    <p class="text-sm font-semibold text-slate-300">Data Karyawan</p>
                    <p class="text-xs mt-1">Sentralisasi data dan jabatan</p>
                </div>
                <div class="text-center">
                    <p class="text-sm font-semibold text-slate-300">Absensi Harian</p>
                    <p class="text-xs mt-1">Check In/Out realtime</p>
                </div>
                <div class="text-center">
                    <p class="text-sm font-semibold text-slate-300">Manajemen Cuti</p>
                    <p class="text-xs mt-1">Sistem approval otomatis</p>
                </div>
                <div class="text-center">
                    <p class="text-sm font-semibold text-slate-300">Slip Gaji</p>
                    <p class="text-xs mt-1">Generate hitungan payload</p>
                </div>
            </div>

        </div>
    </main>

</body>
</html>
