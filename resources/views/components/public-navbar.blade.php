{{-- resources/views/components/public-navbar.blade.php --}}
<nav class="fixed inset-x-0 top-0 z-50 bg-slate-950/85 backdrop-blur-md border-b border-slate-800">
    <div class="max-w-7xl mx-auto h-16 flex items-center justify-between px-4 sm:px-6 lg:px-8">

        {{-- Logo + tagline --}}
        <a href="/" class="flex items-center gap-3">
            <div
                class="h-9 w-9 rounded-full bg-gradient-to-tr from-sky-500 to-emerald-400
                       flex items-center justify-center text-xs font-semibold text-slate-950">
                MT
            </div>
            <div class="flex flex-col leading-tight">
                <span class="text-sm font-semibold text-slate-100 tracking-tight">
                    MoneyTransfer
                </span>
                <span class="text-[11px] text-slate-400">
                    Send money in seconds
                </span>
            </div>
        </a>

        {{-- Desktop links --}}
        <div class="hidden md:flex items-center gap-6 text-sm">

            <a href="/" class="text-slate-300 hover:text-white transition-colors">
                Home
            </a>
            <a href="#about" class="text-slate-300 hover:text-white transition-colors">
                About
            </a>
            <a href="#contact" class="text-slate-300 hover:text-white transition-colors">
                Contact
            </a>

            <a href="{{ route('login') }}"
               class="text-slate-300 hover:text-white transition-colors">
                User Login
            </a>
            <a href="{{ route('register') }}"
               class="text-slate-300 hover:text-white transition-colors">
                User Register
            </a>

            <a href="{{ route('agent.login') }}"
               class="px-4 py-1.5 rounded-full text-xs font-medium
                      border border-sky-500/70 text-sky-300
                      hover:bg-sky-500/10 hover:border-sky-400 transition-colors">
                Agent Login
            </a>

            <a href="{{ route('agent.register') }}"
               class="px-4 py-1.5 rounded-full text-xs font-medium
                      bg-gradient-to-r from-emerald-500 to-sky-500
                      text-slate-950 shadow-sm shadow-emerald-500/40
                      hover:from-emerald-400 hover:to-sky-400 transition-colors">
                Agent Register
            </a>
        </div>

        {{-- Mobile menu toggle --}}
        <button id="mobileMenuBtn"
                class="md:hidden inline-flex items-center justify-center h-9 w-9 rounded-full
                       border border-slate-700 text-slate-100">
            ☰
        </button>
    </div>

    {{-- Mobile menu --}}
    <div id="mobileMenu"
         class="hidden md:hidden bg-slate-950 border-t border-slate-800 px-4 py-3 space-y-2 text-sm">

        <a href="/" class="block text-slate-300 hover:text-white">
            Home
        </a>
        <a href="#about" class="block text-slate-300 hover:text-white">
            About
        </a>
        <a href="#contact" class="block text-slate-300 hover:text-white">
            Contact
        </a>

        <div class="h-px bg-slate-800 my-2"></div>

        <a href="{{ route('login') }}" class="block text-slate-300 hover:text-white">
            User Login
        </a>
        <a href="{{ route('register') }}" class="block text-slate-300 hover:text-white">
            User Register
        </a>

        <a href="{{ route('agent.login') }}" class="block text-sky-300 hover:text-sky-200">
            Agent Login
        </a>
        <a href="{{ route('agent.register') }}" class="block text-emerald-300 hover:text-emerald-200">
            Agent Register
        </a>
    </div>
</nav>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const btn   = document.getElementById('mobileMenuBtn');
        const menu  = document.getElementById('mobileMenu');
        if (!btn || !menu) return;

        btn.addEventListener('click', () => {
            menu.classList.toggle('hidden');
        });
    });
</script>
