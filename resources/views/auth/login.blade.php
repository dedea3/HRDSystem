<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="form-label">Email Address</label>
            <div class="relative mt-1 border-slate-700 bg-slate-800/40 rounded-xl">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" /></svg>
                </div>
                <input id="email" class="form-input pl-10 block w-full" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="name@company.com">
            </div>
            <x-input-error :messages="$errors->get('email')" class="form-error" />
        </div>

        <!-- Password -->
        <div>
            <div class="flex items-center justify-between mb-1.5">
                <label for="password" class="block text-xs font-semibold uppercase tracking-wider text-slate-400">Password</label>
                @if (Route::has('password.request'))
                    <a class="text-xs font-semibold text-indigo-400 hover:text-indigo-300 transition-colors" href="{{ route('password.request') }}">
                        Forgot password?
                    </a>
                @endif
            </div>
            <div class="relative mt-1">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                </div>
                <input id="password" class="form-input pl-10 block w-full" type="password" name="password" required autocomplete="current-password" placeholder="••••••••">
            </div>
            <x-input-error :messages="$errors->get('password')" class="form-error" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center">
            <label for="remember_me" class="flex items-center gap-2 cursor-pointer">
                <input id="remember_me" type="checkbox" class="rounded h-4 w-4 border-slate-700 bg-slate-800 text-indigo-600 focus:ring-indigo-500/50 focus:ring-offset-slate-900" name="remember">
                <span class="text-sm font-medium text-slate-300">Remember me</span>
            </label>
        </div>

        <div class="pt-2">
            <button type="submit" class="btn btn-primary w-full shadow-indigo-900/50 py-2.5 text-base">
                Sign in to dashboard
            </button>
        </div>
        
        <div class="mt-4 text-center">
            <p class="text-xs text-slate-500">
                Demo Accounts:<br>
                admin@hrd.test / hrd@hrd.test / staff@hrd.test<br>
                Password: password
            </p>
        </div>
    </form>
</x-guest-layout>
