@extends('layouts.agent')

@section('content')
<div class="max-w-3xl mx-auto py-8">

  <h1 class="text-2xl font-semibold mb-4">Wallet top up</h1>

  @if(session('success'))
    <div class="mb-4 rounded-xl bg-emerald-50 border border-emerald-200 px-4 py-2 text-sm text-emerald-700">
      {{ session('success') }}
    </div>
  @endif

  {{-- Step 1: search by wallet id --}}
  <div class="bg-white rounded-xl shadow p-5 mb-6">
    <form method="POST" action="{{ route('agent.wallet.lookup') }}" class="flex flex-col sm:flex-row gap-3 items-end">
      @csrf
      <div class="w-full sm:flex-1">
        <label class="block text-xs font-medium text-slate-600 mb-1">Wallet id</label>
        <input type="text" name="wallet_id"
               value="{{ old('wallet_id', $wallet->wallet_id ?? '') }}"
               class="w-full border rounded-lg px-3 py-2 text-sm">
        @error('wallet_id')
          <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
        @enderror
      </div>

      <button type="submit"
              class="px-4 py-2 rounded-lg bg-indigo-600 text-white text-sm font-semibold hover:bg-indigo-700">
        Search wallet
      </button>
    </form>
  </div>

  @isset($wallet)
    {{-- Step 2: show user info and topup form --}}
    <div class="bg-white rounded-xl shadow p-6 space-y-4">
      <div class="flex justify-between items-center">
        <div>
          <h2 class="text-lg font-semibold">Wallet details</h2>
          <p class="text-sm text-slate-500">
            Holder: <span class="font-medium">{{ $wallet->user->name ?? 'Unknown user' }}</span><br>
            Email:  <span class="font-mono">{{ $wallet->user->email ?? '—' }}</span>
          </p>
        </div>
        <div class="text-right">
          <div class="text-xs text-slate-500">Current balance</div>
          <div class="text-2xl font-semibold text-slate-900">
            {{ number_format($wallet->balance, 2) }}
          </div>
        </div>
      </div>

      <hr class="border-slate-200">

      <form method="POST" action="{{ route('agent.wallet.topup') }}" class="flex flex-col sm:flex-row gap-3 items-end">
        @csrf
        <input type="hidden" name="wallet_id" value="{{ $wallet->wallet_id }}">

        <div class="w-full sm:flex-1">
          <label class="block text-xs font-medium text-slate-600 mb-1">Amount to add</label>
          <input type="number" step="0.01" min="1" name="amount"
                 value="{{ old('amount') }}"
                 class="w-full border rounded-lg px-3 py-2 text-sm">
          @error('amount')
            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
          @enderror
        </div>

        <button type="submit"
                class="px-5 py-2.5 rounded-lg bg-emerald-600 text-white text-sm font-semibold hover:bg-emerald-700">
          Add balance
        </button>
      </form>
    </div>
  @endisset

</div>
@endsection
