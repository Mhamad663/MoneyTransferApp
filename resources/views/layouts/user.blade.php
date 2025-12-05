<!DOCTYPE html>
<html lang="{{ str_replace('_','-',app()->getLocale()) }}" class="h-full">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ config('app.name','Laravel') }}</title>
  @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="h-full bg-slate-50 text-slate-900 antialiased dark:bg-slate-950 dark:text-slate-100">
<div class="flex min-h-screen">

  {{-- ============ Sidebar ============ --}}
  <aside id="sidebar"
         class="fixed top-0 left-0 z-40 w-72 h-screen bg-slate-900 text-white flex flex-col transform -translate-x-full md:translate-x-0 transition-transform duration-200">
    {{-- Brand --}}
    <div class="h-16 flex items-center gap-3 px-6 border-b border-slate-800 shrink-0">
      <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-gradient-to-br from-indigo-500 to-blue-600">💸</div>
      <div class="text-lg font-semibold tracking-wide">Masref</div>
    </div>

    {{-- Nav --}}
    <nav class="mt-4 px-3 space-y-1 flex-1 overflow-y-auto">
      <a href="{{ route('user.dashboard') }}"
         class="flex items-center gap-3 px-3 py-2 rounded-lg group
               {{ request()->routeIs('user.dashboard') ? 'bg-indigo-600 text-white' : 'text-slate-200 hover:bg-slate-800' }}">
        <svg class="h-5 w-5 opacity-90" viewBox="0 0 24 24" fill="currentColor"><path d="M12 3l9 7-1.5 2L12 6 4.5 12 3 10l9-7zm-7 9.75L12 8l7 4.75V21h-5v-6H10v6H5v-8.25z"/></svg>
        <span>Dashboard</span>
      </a>

      <a href="{{ route('user.beneficiaries.index') }}"
         class="flex items-center gap-3 px-3 py-2 rounded-lg group
               {{ request()->routeIs('user.beneficiaries.*') ? 'bg-indigo-600 text-white' : 'text-slate-200 hover:bg-slate-800' }}">
        <svg class="h-5 w-5 opacity-90" viewBox="0 0 24 24" fill="currentColor"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5s-3 1.34-3 3 1.34 3 3 3zM8 11c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5C15 14.17 10.33 13 8 13zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg>
        <span>Beneficiaries</span>
      </a>




      <a href="{{ route('user.send') }}"
         class="flex items-center gap-3 px-3 py-2 rounded-lg group
               {{ request()->routeIs('user.send*') ? 'bg-indigo-600 text-white' : 'text-slate-200 hover:bg-slate-800' }}">
        <svg class="h-5 w-5 opacity-90" viewBox="0 0 24 24" fill="currentColor"><path d="M3 12l18-9-9 18-2-7-7-2z"/></svg>
        <span>Send Money</span>
      </a>

      

      <a href="{{ route('user.payments.index') }}"
         class="flex items-center gap-3 px-3 py-2 rounded-lg group
               {{ request()->routeIs('user.payments.*') ? 'bg-indigo-600 text-white' : 'text-slate-200 hover:bg-slate-800' }}">
        <svg class="h-5 w-5 opacity-90" viewBox="0 0 24 24" fill="currentColor"><path d="M2 6h20v12H2V6zm2 2v2h16V8H4zm0 6v2h10v-2H4z"/></svg>
        <span>Payment Methods</span>
      </a>

      <a href="{{ route('user.wallet') }}"
         class="flex items-center gap-3 px-3 py-2 rounded-lg group
               {{ request()->routeIs('user.wallet') ? 'bg-indigo-600 text-white' : 'text-slate-200 hover:bg-slate-800' }}">
        <svg class="h-5 w-5 opacity-90" viewBox="0 0 24 24" fill="currentColor"><path d="M21 7H5a2 2 0 00-2 2v8a2 2 0 002 2h16V7zm-4 6a2 2 0 110-4 2 2 0 010 4zM19 3H7a2 2 0 00-2 2v1h14V3z"/></svg>
        <span>My Wallet</span>
      </a>

      <a href="{{ route('user.transactions') }}"
         class="flex items-center gap-3 px-3 py-2 rounded-lg group
               {{ request()->routeIs('user.transactions*') ? 'bg-indigo-600 text-white' : 'text-slate-200 hover:bg-slate-800' }}">
        <svg class="h-5 w-5 opacity-90" viewBox="0 0 24 24" fill="currentColor"><path d="M4 3a2 2 0 00-2 2v9a3 3 0 003 3h10a1 1 0 001-1V6a3 3 0 00-3-3H4zM6 7h8v2H6V7zm0 4h6v2H6v-2z"/></svg>
        <span>Transaction History</span>
      </a>

      <a href="{{ route('user.transfers.live') }}"
         class="flex items-center gap-3 px-3 py-2 rounded-lg group
               {{ request()->routeIs('user.transfers.live') ? 'bg-indigo-600 text-white' : 'text-slate-200 hover:bg-slate-800' }}">
        <svg class="h-5 w-5 opacity-90" viewBox="0 0 24 24" fill="currentColor"><path d="M12 3a9 9 0 019 9h-2a7 7 0 10-7 7v2a9 9 0 010-18z"/></svg>
        <span>Live Transaction Status</span>
      </a>

      <a href="{{ route('user.fees-promotions') }}"
         class="flex items-center gap-3 px-3 py-2 rounded-lg group
               {{ request()->routeIs('user.fees-promotions') ? 'bg-indigo-600 text-white' : 'text-slate-200 hover:bg-slate-800' }}">
        <svg class="h-5 w-5 opacity-90" viewBox="0 0 24 24" stroke="currentColor" fill="none"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M7 7l10 10M7 7a2 2 0 110-4 2 2 0 010 4zm10 10a2 2 0 110 4 2 2 0 010-4z"/></svg>
        <span>Fees & Promotions</span>
      </a>

      <a href="{{ route('user.agents.map') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-slate-200 hover:bg-slate-800">
        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C8 2 4 6 4 10c0 5.25 7 12 8 12s8-6.75 8-12c0-4-4-8-8-8zm0 11a3 3 0 110-6 3 3 0 010 6z"/></svg>
        <span>Agents Map</span>
      </a>

      
    </nav>

    {{-- Logout --}}
    <div class="p-4 border-t border-slate-800 shrink-0">
      <form method="POST" action="{{ route('logout') }}">@csrf
        <button class="w-full rounded-lg bg-slate-800 py-2 text-sm hover:bg-slate-700">Log out</button>
      </form>
    </div>
  </aside>

  {{-- ============ Main Content ============ --}}
  <div class="flex-1 flex flex-col md:ml-72">
    {{-- Topbar --}}
    <header class="h-16 bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between px-4 sm:px-6 sticky top-0 z-30">
      <div class="flex items-center gap-3">

       {{-- <div> Welcome, {{ auth()->user()->name ?? 'User' }}!</div> --}}
        {{-- Mobile sidebar toggle --}}
        <button id="btnOpenSidebar" class="md:hidden inline-flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 dark:border-slate-700">
          <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M3 6h18v2H3V6zm0 5h18v2H3v-2zm0 5h18v2H3v-2z"/></svg>
        </button>
      </div>

      <div class="flex items-center gap-3">
        <div class="hidden lg:block">
          <input type="text" placeholder="Search…"
                 class="border rounded-lg px-3 py-2 w-72 bg-white/60 dark:bg-slate-900 dark:border-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        @php
  $unreadCount = Auth::user()->unreadNotifications()->count();
@endphp

<a href="{{ route('user.notifications.index') }}"
   class="relative inline-flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 hover:bg-slate-50 dark:border-slate-700 dark:hover:bg-slate-800 transition">
  <svg class="h-5 w-5 text-slate-700 dark:text-slate-200" viewBox="0 0 24 24" fill="currentColor">
    <path d="M12 22a2 2 0 002-2H10a2 2 0 002 2zm6-6V11a6 6 0 10-12 0v5L4 18v1h16v-1l-2-2z"/>
  </svg>

  {{-- Red Dot if Unread Notifications Exist --}}
  @if($unreadCount > 0)
    <span id="notifDot"
          class="absolute top-1 right-1 block h-3 w-3 rounded-full bg-red-500 ring-2 ring-white dark:ring-gray-900 transition"></span>
  @else
    <span id="notifDot" class="hidden absolute top-1 right-1 block h-3 w-3 rounded-full bg-red-500 ring-2 ring-white dark:ring-gray-900"></span>
  @endif
</a>

        <button id="modeToggle"
                class="inline-flex h-9 px-3 items-center justify-center rounded-lg border border-slate-200 hover:bg-slate-50 dark:border-slate-700 dark:hover:bg-slate-800">
          <span class="light-only">🌙</span>
          <span class="dark-only hidden">☀️</span>
        </button>

        <a href="{{ route('user.profile.edit') }}"
           class="hidden sm:inline-flex items-center gap-2 rounded-lg border px-3 py-2 text-sm hover:bg-slate-50 dark:border-slate-700 dark:hover:bg-slate-800">
          <span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-gradient-to-br from-indigo-500 to-blue-600">🧑‍💼</span>
          <span class="max-w-[10rem] truncate">{{ auth()->user()->name ?? 'Profile' }}</span>
        </a>
      </div>
    </header>

    {{-- Page content --}}
    <main class="p-4 sm:p-6">
      @yield('content')
    </main>
  </div>
</div>

{{-- ===== Helpers ===== --}}
<style>
  .dark .dark-only { display: inline; }
  .dark .light-only { display: none; }
</style>

<script>
  // Mobile sidebar open/close
  const sidebar = document.getElementById('sidebar');
  const btnOpen = document.getElementById('btnOpenSidebar');
  function openSidebar(){ sidebar.classList.remove('-translate-x-full'); }
  function closeSidebar(){ sidebar.classList.add('-translate-x-full'); }
  btnOpen?.addEventListener('click', openSidebar);
  document.addEventListener('click', (e) => {
    if (window.innerWidth >= 768) return;
    const inside = sidebar.contains(e.target) || (btnOpen && btnOpen.contains(e.target));
    if (!inside) closeSidebar();
  });
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
  const html   = document.documentElement;
  const toggle = document.getElementById('modeToggle');
  if (!toggle) return; // no button, nothing to do

  const lightIcon = toggle.querySelector('.light-only');
  const darkIcon  = toggle.querySelector('.dark-only');

  function applyDark(isDark) {
    if (isDark) {
      html.classList.add('dark');
      lightIcon?.classList.add('hidden');
      darkIcon?.classList.remove('hidden');
    } else {
      html.classList.remove('dark');
      lightIcon?.classList.remove('hidden');
      darkIcon?.classList.add('hidden');
    }
  }

  // read initial state from localStorage (default = light)
  let isDark = localStorage.getItem('theme.dark') === '1';
  applyDark(isDark);

  toggle.addEventListener('click', function () {
    isDark = !isDark;
    localStorage.setItem('theme.dark', isDark ? '1' : '0');
    applyDark(isDark);
  });
});
</script>

<script>
document.addEventListener('DOMContentLoaded', () => {
  // Listen for custom event from notification page
  window.addEventListener('notificationsUpdated', () => {
    const dot = document.getElementById('notifDot');
    if (dot) dot.classList.add('hidden');
  });
});
</script>


</body>
</html>
