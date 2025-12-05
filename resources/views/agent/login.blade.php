@extends('layouts.public')

@section('content')

{{-- Navbar --}}
<x-public-navbar />

<div class="min-h-screen pt-24 flex items-center justify-center bg-slate-950 px-4 py-10">

    <div class="w-full max-w-4xl grid md:grid-cols-2 gap-10">
        {{-- Branding left --}}
        <div class="fade-in space-y-4 hidden md:block">
            <div class="inline-flex items-center gap-2 rounded-full bg-blue-500/10 px-3 py-1 text-xs text-blue-300">
                <span class="h-1.5 w-1.5 rounded-full bg-blue-400"></span>
                Agent Portal
            </div>

            <h1 class="text-3xl font-semibold text-slate-50">
                Welcome back, <span class="text-blue-400">Agent</span>.
            </h1>

            <p class="text-sm text-slate-400 max-w-md">
                Log in to manage payouts, transfers, and customer requests.
            </p>
        </div>

        {{-- Login Card --}}
        <div class="form-card rounded-2xl p-8 fade-in">
            <h2 class="text-xl font-semibold text-slate-50 mb-4">Agent Login</h2>

            @if ($errors->any())
                <div class="mb-4 rounded-lg border border-red-500/40 bg-red-500/10 px-3 py-2 text-xs text-red-300">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('agent.login') }}" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-xs text-slate-300 mb-1">Email</label>
                    <input type="email" name="email" class="input w-full px-3 py-2 rounded-lg" required />
                </div>

                <div>
                    <label class="block text-xs text-slate-300 mb-1">Password</label>
                    <input type="password" name="password" class="input w-full px-3 py-2 rounded-lg" required />
                </div>

                <button class="btn-gradient w-full py-2.5 rounded-lg mt-2">
                    Login
                </button>
            </form>
        </div>

    </div>
</div>
@endsection
