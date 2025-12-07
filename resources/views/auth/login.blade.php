
@extends('layouts.public')

@section('content')
<div class="min-h-screen flex items-center justify-center px-4 py-10">
    <div class="mx-auto w-full max-w-5xl grid gap-10 md:grid-cols-2">

        
        <div class="fade-in space-y-4 px-2">
            <div class="inline-flex items-center gap-2 rounded-full bg-sky-500/10 px-3 py-1 text-xs text-sky-300">
                <span class="h-1.5 w-1.5 rounded-full bg-sky-400"></span>
                MoneyTransfer • Secure login
            </div>

            <h1 class="text-3xl sm:text-4xl font-semibold text-slate-50">
                Welcome back to <span class="text-sky-400">MoneyTransfer</span>.
            </h1>

            <p class="text-sm text-slate-400 max-w-md">
                Log in to track transfers, manage your wallet, and access your dashboard safely.
            </p>

            <ul class="space-y-3 text-sm text-slate-300">
                <li class="flex items-start gap-2">
                    <span class="mt-1 h-2 w-2 rounded-full bg-emerald-400"></span>
                    <span>Real-time transfer tracking.</span>
                </li>
                <li class="flex items-start gap-2">
                    <span class="mt-1 h-2 w-2 rounded-full bg-sky-400"></span>
                    <span>Instant notifications on every step.</span>
                </li>
                <li class="flex items-start gap-2">
                    <span class="mt-1 h-2 w-2 rounded-full bg-purple-400"></span>
                    <span>Secure authentication and fraud protection.</span>
                </li>
            </ul>
        </div>

        {{-- RIGHT SIDE — login card --}}
        <div class="form-card rounded-2xl p-8 fade-in">

            {{-- Title + Register link --}}
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-xl font-semibold text-slate-50">Log in to your account</h2>
                    <p class="text-xs text-slate-400 mt-1">Access your personal dashboard.</p>
                </div>
                <a href="{{ route('register') }}" class="text-xs text-sky-300 hover:text-sky-200">
                    Create account
                </a>
            </div>

            {{-- SESSION STATUS --}}
            @if (session('status'))
                <div class="mb-3 text-sm text-emerald-400">
                    {{ session('status') }}
                </div>
            @endif

            {{-- LOGIN FORM --}}
            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-xs font-medium text-slate-300 mb-1">Email</label>
                    <input id="email" name="email" type="email" required autofocus
                           value="{{ old('email') }}"
                           class="input block w-full rounded-lg px-3 py-2 text-sm">
                    <x-input-error :messages="$errors->get('email')" class="mt-1 text-xs" />
                </div>

                {{-- Password --}}
                <div>
                    <label for="password" class="block text-xs font-medium text-slate-300 mb-1">Password</label>
                    <input id="password" name="password" type="password" required autocomplete="current-password"
                           class="input block w-full rounded-lg px-3 py-2 text-sm">
                    <x-input-error :messages="$errors->get('password')" class="mt-1 text-xs" />
                </div>

                {{-- Remember Me --}}
                <div class="flex items-center justify-between mt-1">
                    <label class="flex items-center gap-2 text-xs text-slate-400">
                        <input type="checkbox" name="remember" class="rounded border-slate-700 bg-slate-900 text-sky-500 focus:ring-sky-500">
                        Remember me
                    </label>

                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}"
                           class="text-xs text-sky-300 hover:text-sky-200">
                            Forgot password?
                        </a>
                    @endif
                </div>

                {{-- Button --}}
                <button type="submit" class="btn-gradient w-full py-2.5 rounded-lg mt-3">
                    Log in
                </button>
            </form>
{{-- SEPARATOR --}}
<div class="relative my-6">
    <div class="border-t border-slate-700"></div>
    <span class="absolute left-1/2 -translate-x-1/2 -top-3 bg-slate-900 px-3 text-xs text-slate-400">
        or continue via
    </span>
</div>
            {{-- SOCIAL LOGIN BUTTONS --}}
<div class="mt-6 grid grid-cols-2 gap-4">

    {{-- GOOGLE BUTTON --}}
    <a href="{{ route('google.redirect') }}"
       class="flex items-center justify-center gap-2 rounded-xl py-2.5
              bg-slate-800/50 border border-slate-700
              text-slate-200 text-sm font-medium
              hover:bg-slate-800 hover:border-sky-500 hover:shadow-lg
              hover:shadow-sky-500/20 transition duration-200">
        <img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg"
             class="h-5 w-5" alt="">
        <span>Google</span>
    </a>

    {{-- FACEBOOK BUTTON --}}
    <a href="{{ route('facebook.redirect') }}"
       class="flex items-center justify-center gap-2 rounded-xl py-2.5
              bg-slate-800/50 border border-slate-700
              text-slate-200 text-sm font-medium
              hover:bg-slate-800 hover:border-blue-500 hover:shadow-lg
              hover:shadow-blue-500/20 transition duration-200">
        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="#3b82f6">
            <path d="M22 12a10 10 0 1 0-11.6 9.9v-7h-2.4V12h2.4V9.7c0-2.4 1.4-3.8 3.6-3.8
                     1 0 2 .2 2 .2v2.2h-1.1c-1.1 0-1.5.7-1.5 1.4V12h2.6l-.4 2.9h-2.2v7A10 10
                     0 0 0 22 12z" />
        </svg>
        <span>Facebook</span>
    </a>

</div>

        </div>
    </div>
</div>
@endsection
