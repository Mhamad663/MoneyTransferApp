@extends('layouts.user')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">

  {{-- Flash Messages --}}
  @if(session('success'))
    <div class="rounded-lg bg-emerald-50 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-200 p-4 text-sm animate-fadeIn">
      {{ session('success') }}
    </div>
  @endif
  @if(session('error'))
    <div class="rounded-lg bg-rose-50 text-rose-700 dark:bg-rose-900/30 dark:text-rose-200 p-4 text-sm animate-fadeIn">
      {{ session('error') }}
    </div>
  @endif

  {{--  WALLET HEADER --}}
  <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-blue-600 via-indigo-600 to-purple-600 p-8 text-white shadow-xl">
    <div class="absolute inset-0 opacity-20 bg-[radial-gradient(circle_at_top_left,_#93c5fd,_transparent_50%)]"></div>

    <div class="relative z-10 flex flex-col md:flex-row justify-between md:items-center gap-5">
      <div>
        <h1 class="text-2xl md:text-3xl font-semibold tracking-tight">Masref Wallet</h1>
        <p class="text-blue-100 text-sm md:text-base">Manage your money anywhere, anytime.</p>
      </div>

      <div class="text-right">
        <p class="text-blue-100 text-xs md:text-sm">Wallet ID</p>
        <p class="font-mono font-semibold text-lg md:text-xl">{{ $wallet->wallet_id }}</p>
      </div>
    </div>

    {{-- Balance Display --}}
    <div class="relative z-10 mt-6 flex flex-col sm:flex-row sm:items-end justify-between gap-4">
      <div>
        <p class="text-blue-100 text-sm">Current Balance</p>
        <p id="wallet-balance" class="text-5xl md:text-6xl font-bold tracking-tight transition-all duration-300">
          ${{ number_format($wallet->balance, 2) }}
        </p>
      </div>

      <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
        <button onclick="scrollToForm('topup-form')"
          class="rounded-xl bg-white/20 hover:bg-white/30 text-white backdrop-blur-md px-4 py-2.5 text-sm md:text-base font-medium transition">
          💳 Top Up
        </button>
        <button onclick="scrollToForm('transfer-form')"
          class="rounded-xl bg-emerald-500 hover:bg-emerald-600 text-white px-4 py-2.5 text-sm md:text-base font-medium transition shadow-md">
          🔁 Transfer
        </button>
      </div>
    </div>
  </div>

  {{-- Top-Up Form --}}
  <div id="topup-form" class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 md:p-8 shadow-sm hover:shadow-md transition">
    <h2 class="text-lg md:text-xl font-semibold text-slate-800 dark:text-slate-100 mb-4 flex items-center gap-2">
      💵 Add Funds
    </h2>
    <form method="POST" action="{{ route('user.wallet.topup') }}" class="flex flex-col sm:flex-row gap-3 sm:items-center">
      @csrf
      <input type="number" step="0.01" name="amount"
        class="w-full sm:w-52 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800
        text-slate-800 dark:text-slate-100 px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
        placeholder="Enter amount" required>
      <button
        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-lg bg-blue-600 hover:bg-blue-700
        dark:bg-blue-500 dark:hover:bg-blue-600 text-white px-4 py-2 font-medium transition">
        ➕ Add Funds
      </button>
    </form>
  </div>

  {{-- Transfer Form --}}
  <div id="transfer-form" class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 md:p-8 shadow-sm hover:shadow-md transition">
    <h2 class="text-lg md:text-xl font-semibold text-slate-800 dark:text-slate-100 mb-4 flex items-center gap-2">
      🔄 Wallet to Wallet Transfer
    </h2>
    <form method="POST" action="{{ route('user.wallet.transfer') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-3">
      @csrf
      <input type="text" name="wallet_id"
        class="rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800
        text-slate-800 dark:text-slate-100 px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
        placeholder="Receiver Wallet ID" required>
      <input type="number" step="0.01" name="amount"
        class="rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800
        text-slate-800 dark:text-slate-100 px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
        placeholder="Amount" required>
      <button
        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-lg bg-emerald-600 hover:bg-emerald-700
        dark:bg-emerald-500 dark:hover:bg-emerald-600 text-white px-4 py-2 font-medium transition">
        🚀 Send
      </button>
    </form>
  </div>

  {{--  Transactions --}}
  <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 md:p-8 shadow-sm">
    <h2 class="text-lg md:text-xl font-semibold text-slate-800 dark:text-slate-100 mb-4 flex items-center gap-2">
      📜 Recent Transactions
    </h2>

    
    <div class="hidden md:block overflow-x-auto">
      <table class="min-w-full text-sm">
        <thead class="bg-slate-50 dark:bg-slate-800/60 text-slate-600 dark:text-slate-300">
          <tr>
            <th class="py-2 px-3 text-left">Type</th>
            <th class="py-2 px-3 text-left">Amount</th>
            <th class="py-2 px-3 text-left">Status</th>
            <th class="py-2 px-3 text-left">Date</th>
          </tr>
        </thead>
        <tbody>
          @forelse($transactions as $tx)
            <tr class="border-t border-slate-200 dark:border-slate-800 hover:bg-slate-50 dark:hover:bg-slate-800/40 transition">
              <td class="py-2 px-3 capitalize text-slate-700 dark:text-slate-200">{{ $tx->tx_type }}</td>
              <td class="py-2 px-3 font-semibold text-blue-600 dark:text-blue-400">${{ number_format($tx->amount, 2) }}</td>
              <td class="py-2 px-3">
                @if($tx->status === 'completed')
                  <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300">Completed</span>
                @elseif($tx->status === 'pending')
                  <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-300">Pending</span>
                @else
                  <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-rose-100 text-rose-700 dark:bg-rose-900/30 dark:text-rose-300">Failed</span>
                @endif
              </td>
              <td class="py-2 px-3 text-slate-600 dark:text-slate-400">{{ $tx->created_at->format('M d, Y H:i') }}</td>
            </tr>
          @empty
            <tr><td colspan="4" class="py-4 text-center text-slate-500 dark:text-slate-400">No transactions yet.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>

    {{-- Mobile Cards --}}
    <div class="md:hidden space-y-3">
      @forelse($transactions as $tx)
        <div class="border border-slate-200 dark:border-slate-700 rounded-xl p-4 bg-slate-50 dark:bg-slate-800/40 hover:shadow-md transition">
          <div class="flex justify-between items-center">
            <span class="font-medium capitalize text-slate-800 dark:text-slate-100">{{ $tx->tx_type }}</span>
            <span class="text-xs text-slate-500 dark:text-slate-400">{{ $tx->created_at->format('M d, Y H:i') }}</span>
          </div>
          <div class="mt-2 text-lg font-semibold text-blue-600 dark:text-blue-400">${{ number_format($tx->amount, 2) }}</div>
          <div class="mt-2">
            @if($tx->status === 'completed')
              <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300">Completed</span>
            @elseif($tx->status === 'pending')
              <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-300">Pending</span>
            @else
              <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-rose-100 text-rose-700 dark:bg-rose-900/30 dark:text-rose-300">Failed</span>
            @endif
          </div>
        </div>
      @empty
        <div class="text-center text-slate-500 dark:text-slate-400 py-4">No transactions yet.</div>
      @endforelse
    </div>
  </div>
</div>

{{-- Animation --}}
<script>
function scrollToForm(id){ document.getElementById(id)?.scrollIntoView({ behavior:'smooth' }); }

document.addEventListener('DOMContentLoaded', () => {
  const balance = document.getElementById('wallet-balance');
  @if(session('success') && isset($wallet->balance))
    balance.classList.add('animate-balanceFlash');
    setTimeout(() => balance.classList.remove('animate-balanceFlash'), 1000);
  @endif
});
</script>

<style>
@keyframes balanceFlash {
  0% { color:#10b981; transform:scale(1.02); }
  50% { color:#34d399; transform:scale(1.05); }
  100% { color:white; transform:scale(1); }
}
.animate-balanceFlash { animation: balanceFlash 1s ease-in-out; }
@keyframes fadeIn { from {opacity:0; transform:translateY(6px);} to {opacity:1; transform:translateY(0);} }
.animate-fadeIn { animation: fadeIn 0.5s ease-in-out; }
</style>
@endsection
