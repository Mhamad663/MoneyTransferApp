@extends('layouts.user')

@section('content')
<div class="max-w-3xl mx-auto">
  <div class="mb-6">
    <h1 class="text-2xl font-semibold">Request Refund / Dispute</h1>
    <p class="text-sm text-slate-500 dark:text-slate-400">Reference: {{ $transfer->reference }}</p>
  </div>

  {{-- Transfer summary card --}}
  <div class="mb-6 grid sm:grid-cols-2 gap-4">
    <div class="rounded-2xl border dark:border-slate-800 bg-white dark:bg-slate-900 p-4">
      <div class="text-xs text-slate-500 dark:text-slate-400 uppercase tracking-wide">Amount</div>
      <div class="mt-1 text-lg font-semibold">
        {{ strtoupper($transfer->dst_currency) }} {{ number_format($transfer->amount_dst,2) }}
      </div>
    </div>
    <div class="rounded-2xl border dark:border-slate-800 bg-white dark:bg-slate-900 p-4">
      <div class="text-xs text-slate-500 dark:text-slate-400 uppercase tracking-wide">Created</div>
      <div class="mt-1 text-lg font-semibold">{{ $transfer->created_at->format('Y-m-d H:i') }}</div>
    </div>
  </div>

  <form method="POST" action="{{ route('user.refunds.store', $transfer) }}"
        class="bg-white dark:bg-slate-900 rounded-2xl border dark:border-slate-800 p-6 space-y-5">
    @csrf

    {{-- Type --}}
    <div>
      <label class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-2">Type</label>

      <div class="grid sm:grid-cols-2 gap-3">
        <label class="flex items-center gap-3 rounded-xl border dark:border-slate-700 p-3 cursor-pointer hover:bg-slate-50 dark:hover:bg-slate-800/50">
          <input type="radio" name="kind" value="refund" class="accent-blue-600" checked>
          <div>
            <div class="font-medium">Refund</div>
            <div class="text-xs text-slate-500 dark:text-slate-400">Request a return of funds to original source.</div>
          </div>
        </label>

        <label class="flex items-center gap-3 rounded-xl border dark:border-slate-700 p-3 cursor-pointer hover:bg-slate-50 dark:hover:bg-slate-800/50">
          <input type="radio" name="kind" value="dispute" class="accent-blue-600">
          <div>
            <div class="font-medium">Dispute</div>
            <div class="text-xs text-slate-500 dark:text-slate-400">Report an issue for manual investigation.</div>
          </div>
        </label>
      </div>
      @error('kind') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
    </div>

    {{-- Reason --}}
    <div>
      <label class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-1">Reason (optional)</label>
      <input name="reason" value="{{ old('reason') }}" placeholder="Duplicate charge, wrong amount, …"
             class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900
                    px-3 py-2 text-sm text-slate-800 dark:text-slate-100
                    focus:outline-none focus:ring-2 focus:ring-blue-500">
      @error('reason') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
    </div>

    {{-- Details --}}
    <div>
      <label class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-1">Details</label>
      <textarea name="details" rows="4" placeholder="Explain what happened and what you’re requesting…"
                class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900
                       px-3 py-2 text-sm text-slate-800 dark:text-slate-100
                       focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('details') }}</textarea>
      @error('details') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
    </div>

    <div class="flex items-center gap-3">
      <button class="inline-flex items-center gap-2 rounded-xl bg-blue-600 hover:bg-blue-700
                     text-white px-4 py-2 text-sm shadow-sm transition">
        Submit
      </button>
      <a href="{{ url()->previous() }}"
         class="text-sm text-slate-600 hover:text-slate-800 dark:text-slate-300 dark:hover:text-white transition">
        Cancel
      </a>
    </div>
  </form>

  <div class="mt-6 text-xs text-slate-500 dark:text-slate-400">
    * Requests are reviewed by an agent. If approved, refunds are returned to your original funding source
    (wallet/card/bank) and the transfer timeline is updated.
  </div>
</div>
@endsection
