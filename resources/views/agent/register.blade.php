{{-- resources/views/agent/auth/register.blade.php --}}
@extends('layouts.agent')

{{-- Public navbar at the top --}}
<x-public-navbar />

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-950 via-slate-950 to-slate-900
            pt-24 px-4 py-10 flex items-center justify-center">

    <div class="w-full max-w-6xl grid gap-10 md:grid-cols-2">

        {{-- LEFT: marketing copy --}}
        <div class="space-y-5 px-2">
            <div
                class="inline-flex items-center gap-2 rounded-full bg-emerald-500/10
                       px-3 py-1 text-[11px] text-emerald-300">
                <span class="h-1.5 w-1.5 rounded-full bg-emerald-400"></span>
                Become a trusted payout partner
            </div>

            <h1 class="text-3xl sm:text-4xl font-semibold text-slate-50 leading-tight">
                Join the <span class="text-emerald-400">MoneyTransfer</span> agent network.
            </h1>

            <p class="text-sm text-slate-400 max-w-md">
                Register your shop, start paying out transfers, and earn commissions
                on every transaction you process.
            </p>

            <ul class="space-y-3 text-sm text-slate-300">
                <li class="flex items-start gap-2">
                    <span class="mt-1 h-2 w-2 rounded-full bg-emerald-400"></span>
                    <span>Attract new walk-in customers with payout services.</span>
                </li>

                <li class="flex items-start gap-2">
                    <span class="mt-1 h-2 w-2 rounded-full bg-sky-400"></span>
                    <span>Real-time dashboard for cash-out requests and balances.</span>
                </li>

                <li class="flex items-start gap-2">
                    <span class="mt-1 h-2 w-2 rounded-full bg-purple-400"></span>
                    <span>Dedicated support and transparent reporting.</span>
                </li>
            </ul>
        </div>

        {{-- RIGHT: registration card --}}
        <div
            class="rounded-2xl border border-slate-800/80 bg-slate-900/80
                   shadow-xl shadow-slate-950/60 p-8 space-y-6">

            {{-- Header row --}}
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-slate-50">
                        Agent registration
                    </h2>
                    <p class="text-xs text-slate-400 mt-1">
                        Tell us about your store to get started.
                    </p>
                </div>

                <a href="{{ route('agent.login') }}"
                   class="text-xs text-sky-300 hover:text-sky-200">
                    Already registered?
                </a>
            </div>

            {{-- Error block --}}
            @if ($errors->any())
                <div
                    class="rounded-lg border border-red-500/40 bg-red-500/10
                           px-3 py-2 text-xs text-red-200">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('agent.register') }}" class="space-y-4">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[11px] font-medium text-slate-300 mb-1">
                            Store name
                        </label>
                        <input type="text" name="name" value="{{ old('name') }}"
                               class="w-full rounded-lg bg-slate-900/70 border border-slate-700
                                      px-3 py-2 text-sm text-slate-100 placeholder-slate-500
                                      focus:outline-none focus:ring-2 focus:ring-emerald-500/60
                                      focus:border-emerald-500/60"
                               required>
                    </div>

                    <div>
                        <label class="block text-[11px] font-medium text-slate-300 mb-1">
                            Phone
                        </label>
                        <input type="text" name="phone" value="{{ old('phone') }}"
                               class="w-full rounded-lg bg-slate-900/70 border border-slate-700
                                      px-3 py-2 text-sm text-slate-100 placeholder-slate-500
                                      focus:outline-none focus:ring-2 focus:ring-emerald-500/60
                                      focus:border-emerald-500/60"
                               required>
                    </div>

                    <div>
                        <label class="block text-[11px] font-medium text-slate-300 mb-1">
                            City
                        </label>
                        <input type="text" name="city" value="{{ old('city') }}"
                               class="w-full rounded-lg bg-slate-900/70 border border-slate-700
                                      px-3 py-2 text-sm text-slate-100
                                      focus:outline-none focus:ring-2 focus:ring-emerald-500/60
                                      focus:border-emerald-500/60"
                               required>
                    </div>

                    <div>
                        <label class="block text-[11px] font-medium text-slate-300 mb-1">
                            Country
                        </label>
                        <input type="text" name="country" value="{{ old('country') }}"
                               class="w-full rounded-lg bg-slate-900/70 border border-slate-700
                                      px-3 py-2 text-sm text-slate-100
                                      focus:outline-none focus:ring-2 focus:ring-emerald-500/60
                                      focus:border-emerald-500/60"
                               required>
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-[11px] font-medium text-slate-300 mb-1">
                            Address
                        </label>
                        <input type="text" name="address" value="{{ old('address') }}"
                               class="w-full rounded-lg bg-slate-900/70 border border-slate-700
                                      px-3 py-2 text-sm text-slate-100
                                      focus:outline-none focus:ring-2 focus:ring-emerald-500/60
                                      focus:border-emerald-500/60">
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-[11px] font-medium text-slate-300 mb-1">
                            Email
                        </label>
                        <input type="email" name="email" value="{{ old('email') }}"
                               class="w-full rounded-lg bg-slate-900/70 border border-slate-700
                                      px-3 py-2 text-sm text-slate-100
                                      focus:outline-none focus:ring-2 focus:ring-emerald-500/60
                                      focus:border-emerald-500/60"
                               required>
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-[11px] font-medium text-slate-300 mb-1">
                            Password
                        </label>
                        <input type="password" name="password"
                               class="w-full rounded-lg bg-slate-900/70 border border-slate-700
                                      px-3 py-2 text-sm text-slate-100
                                      focus:outline-none focus:ring-2 focus:ring-emerald-500/60
                                      focus:border-emerald-500/60"
                               required>
                    </div>
                </div>

                <button type="submit"
                        class="mt-1 w-full rounded-lg py-2.5 text-sm font-medium
                               bg-gradient-to-r from-emerald-500 to-sky-500
                               text-slate-950 shadow-md shadow-emerald-500/40
                               hover:from-emerald-400 hover:to-sky-400 transition-colors">
                    Register as agent
                </button>

                <p class="mt-3 text-[11px] leading-relaxed text-slate-500 text-center">
                    By registering as an agent you agree to our terms of service and privacy policy.
                </p>
            </form>
        </div>
    </div>
</div>
@endsection
