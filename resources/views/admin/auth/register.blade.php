{{-- resources/views/admin/auth/register.blade.php --}}
@extends('layouts.admin-auth')

@section('content')
<div class="min-h-screen flex items-center justify-center px-4 py-10">
    <div class="mx-auto w-full max-w-5xl grid gap-10 md:grid-cols-2">

        {{-- LEFT: copy / features --}}
        <div class="space-y-4 text-left">
            <div class="inline-flex items-center gap-2 rounded-full bg-sky-500/10 px-3 py-1 text-xs text-sky-300">
                <span class="h-1.5 w-1.5 rounded-full bg-sky-400"></span>
                Masref Admin • New administrator
            </div>

            <h1 class="text-3xl sm:text-4xl font-semibold text-slate-50 leading-tight">
                Create a new<br>
                <span class="text-sky-400">Masref admin account.</span>
            </h1>

            <p class="text-sm text-slate-400 max-w-md">
                Set up a secure administrator profile to manage users, agents, fees,
                services, and fraud monitoring from a single control panel.
            </p>

            <ul class="space-y-3 text-sm text-slate-300">
                <li class="flex items-start gap-2">
                    <span class="mt-1 h-2 w-2 rounded-full bg-emerald-400"></span>
                    <span>Granular access to transactions and refunds.</span>
                </li>
                <li class="flex items-start gap-2">
                    <span class="mt-1 h-2 w-2 rounded-full bg-sky-400"></span>
                    <span>Agent and service configuration in real time.</span>
                </li>
                <li class="flex items-start gap-2">
                    <span class="mt-1 h-2 w-2 rounded-full bg-purple-400"></span>
                    <span>Audit-ready oversight and security controls.</span>
                </li>
            </ul>
        </div>

        {{-- RIGHT: registration card --}}
        <div class="rounded-2xl bg-slate-900/80 border border-slate-800 shadow-2xl p-8 backdrop-blur">

            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-xl font-semibold text-slate-50">Admin registration</h2>
                    <p class="mt-1 text-xs text-slate-400">
                        Create a secure administrator account.
                    </p>
                </div>
                <a href="{{ route('admin.login') }}"
                   class="text-xs text-sky-300 hover:text-sky-200">
                    Back to login
                </a>
            </div>

            @if ($errors->any())
                <div class="mb-4 rounded-lg border border-red-500/40 bg-red-500/10 px-3 py-2 text-xs text-red-200">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST"
                  action="{{ route('admin.register.post') }}"
                  class="space-y-4">
                @csrf

                {{-- Full name --}}
                <div>
                    <label for="name" class="block text-xs font-medium text-slate-300 mb-1">
                        Full name
                    </label>
                    <input id="name"
                           name="name"
                           type="text"
                           value="{{ old('name') }}"
                           required
                           autofocus
                           class="mt-1 block w-full rounded-lg border border-slate-700 bg-slate-900/60
                                  px-3 py-2 text-sm text-slate-100 placeholder-slate-500
                                  focus:border-sky-500 focus:ring-sky-500">
                </div>

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-xs font-medium text-slate-300 mb-1">
                        Email
                    </label>
                    <input id="email"
                           name="email"
                           type="email"
                           value="{{ old('email') }}"
                           required
                           class="mt-1 block w-full rounded-lg border border-slate-700 bg-slate-900/60
                                  px-3 py-2 text-sm text-slate-100 placeholder-slate-500
                                  focus:border-sky-500 focus:ring-sky-500">
                </div>

                {{-- Password --}}
                <div>
                    <label for="password" class="block text-xs font-medium text-slate-300 mb-1">
                        Password
                    </label>
                    <input id="password"
                           name="password"
                           type="password"
                           required
                           class="mt-1 block w-full rounded-lg border border-slate-700 bg-slate-900/60
                                  px-3 py-2 text-sm text-slate-100 placeholder-slate-500
                                  focus:border-sky-500 focus:ring-sky-500">
                </div>

                {{-- Confirm password --}}
                <div>
                    <label for="password_confirmation" class="block text-xs font-medium text-slate-300 mb-1">
                        Confirm password
                    </label>
                    <input id="password_confirmation"
                           name="password_confirmation"
                           type="password"
                           required
                           class="mt-1 block w-full rounded-lg border border-slate-700 bg-slate-900/60
                                  px-3 py-2 text-sm text-slate-100 placeholder-slate-500
                                  focus:border-sky-500 focus:ring-sky-500">
                </div>

                <button type="submit"
                        class="w-full mt-2 inline-flex items-center justify-center rounded-lg
                               bg-gradient-to-r from-emerald-500 via-sky-500 to-blue-600
                               px-4 py-2.5 text-sm font-semibold text-white shadow-lg
                               hover:brightness-110 focus:outline-none focus:ring-2
                               focus:ring-sky-500 focus:ring-offset-2 focus:ring-offset-slate-950
                               transition">
                    Create admin account
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
