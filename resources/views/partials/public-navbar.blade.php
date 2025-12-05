{{-- resources/views/partials/public-navbar.blade.php --}}
<header class="w-full bg-slate-950/95 backdrop-blur border-b border-slate-800">
    <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between gap-6">

            {{-- Logo / brand --}}
            <a href="{{ url('/') }}" class="flex items-center gap-3">
                <div class="h-8 w-8 rounded-full bg-sky-500 flex items-center justify-center text-xs font-semibold">
                    MT
                </div>
                <div class="leading-tight">
                    <div class="text-sm font-semibold text-slate-50">
                        MoneyTransfer
                    </div>
                    <div class="text-[11px] text-slate-400">
                        Send money in seconds
                    </div>
                </div>
            </a>

            {{-- Center nav links (desktop) --}}
            <nav class="hidden md:flex items-center gap-7 text-sm text-slate-300">
                <a href="{{ url('/') }}" class="nav-link">Home</a>
                <a href="#how-it-works" class="nav-link">How it works</a>
                <a href="#features" class="nav-link">Features</a>
                <a href="#pricing" class="nav-link">Pricing</a>
            </nav>

            {{-- Right actions --}}
            <div class="hidden md:flex items-center gap-3">
                {{-- Become an agent --}}
                <a href="{{ route('agent.register') }}"
                   class="px-3 py-1.5 text-xs sm:text-sm rounded-full border border-sky-500/70 text-sky-300 hover:bg-sky-500/10 transition">
                    Become an agent
                </a>

                {{-- Login --}}
                <a href="{{ route('login') }}"
                   class="px-3 py-1.5 text-xs sm:text-sm rounded-full border border-slate-600 text-slate-200 hover:bg-slate-800 transition">
                    Login
                </a>

                {{-- Register --}}
                <a href="{{ route('register') }}"
                   class="px-4 py-1.5 text-xs sm:text-sm rounded-full bg-sky-500 hover:bg-sky-400 text-slate-950 font-semibold shadow-sm transition">
                    Register
                </a>
            </div>

            {{-- Mobile: simple icon (you can expand later) --}}
            <button class="md:hidden inline-flex items-center justify-center rounded-md p-2 text-slate-300 hover:bg-slate-800">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7"
                          d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
        </div>
    </div>
</header>
