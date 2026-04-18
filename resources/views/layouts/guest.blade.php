<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'HRD System') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full bg-slate-950 font-[Inter] text-slate-100 antialiased flex flex-col justify-center items-center p-4 relative overflow-hidden">

    {{-- Background decoration --}}
    <div class="absolute top-0 left-0 w-full h-full overflow-hidden -z-10 pointer-events-none">
        <div class="absolute -top-[10%] -left-[10%] w-[50%] h-[50%] rounded-full bg-indigo-600/10 blur-[120px]"></div>
        <div class="absolute bottom-[10%] right-[10%] w-[40%] h-[40%] rounded-full bg-violet-600/10 blur-[100px]"></div>
    </div>

    <div class="w-full max-w-md">
        {{-- Logo --}}
        <div class="mb-8 flex flex-col justify-center items-center gap-4">
            <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-indigo-600 shadow-xl shadow-indigo-900/50">
                <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <div class="text-center">
                <h1 class="text-2xl font-bold text-white tracking-tight">HRD System</h1>
                <p class="text-sm text-slate-400">Sign in to your account</p>
            </div>
        </div>

        {{-- Form Box --}}
        <div class="card p-8 shadow-2xl shadow-black/50">
            {{ $slot }}
        </div>
        
        <p class="mt-8 text-center text-xs text-slate-500">
            &copy; {{ date('Y') }} HRD System. All rights reserved.
        </p>
    </div>

</body>
</html>
