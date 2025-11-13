@extends('layouts.user')

@section('content')
<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
  <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
    <h1 class="text-2xl font-semibold text-slate-800 dark:text-slate-100">
      💸 My Refund / Dispute Requests
    </h1>

    <a href="{{ route('user.transactions') }}"
       class="inline-flex items-center gap-2 rounded-xl px-4 py-2 text-sm font-medium
              bg-blue-600 text-white hover:bg-blue-700
              dark:bg-blue-500 dark:hover:bg-blue-600
              transition-all duration-200">
      ← Back to Transactions
    </a>
  </div>

  @if(session('success'))
    <div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-800
                dark:border-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-100">
      ✅ {{ session('success') }}
    </div>
  @endif

  {{-- DESKTOP/TABLET: table (hidden on small) --}}
  <div class="hidden md:block">
    <div class="rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 overflow-hidden">
      {{-- horizontal scroll for mid viewports --}}
      <div class="overflow-x-auto">
        {{-- prevent squishing: set a sensible min width --}}
        <table class="min-w-[880px] w-full text-sm">
          <thead class="bg-slate-50 dark:bg-slate-800/60">
            <tr class="text-slate-600 dark:text-slate-300 uppercase text-xs tracking-wide">
              <th class="px-5 py-3 text-left">Reference</th>
              <th class="px-5 py-3 text-left">Type</th>
              <th class="px-5 py-3 text-left">Reason</th>
              <th class="px-5 py-3 text-left">Status</th>
              <th class="px-5 py-3 text-left">Created</th>
            </tr>
          </thead>
          <tbody>
            @forelse($requests as $r)
              @php
                $status = $r->status;
                $pill = match(true) {
                  in_array($status, ['open','under_review']) => 'bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-100',
                  in_array($status, ['approved','refunded']) => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-100',
                  $status === 'rejected' => 'bg-rose-100 text-rose-800 dark:bg-rose-900/40 dark:text-rose-100',
                  default => 'bg-slate-100 text-slate-800 dark:bg-slate-700 dark:text-slate-200',
                };
              @endphp
              <tr class="border-t border-slate-100 dark:border-slate-800 hover:bg-slate-50 dark:hover:bg-slate-800/60 transition-colors">
                <td class="px-5 py-3 font-medium">
                  <a href="{{ route('user.transactions.show', $r->transfer) }}"
                     class="text-blue-600 hover:underline dark:text-blue-400">
                    {{ $r->transfer->reference }}
                  </a>
                </td>
                <td class="px-5 py-3 capitalize text-slate-700 dark:text-slate-200">
                  {{ $r->kind }}
                </td>
                <td class="px-5 py-3 text-slate-600 dark:text-slate-300 max-w-[360px] truncate">
                  {{ $r->reason ?: '—' }}
                </td>
                <td class="px-5 py-3">
                  <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $pill }}">
                    {{ str_replace('_',' ', ucfirst($status)) }}
                  </span>
                </td>
                <td class="px-5 py-3 text-slate-600 dark:text-slate-300 whitespace-nowrap">
                  {{ $r->created_at->format('Y-m-d H:i') }}
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="5" class="px-5 py-8 text-center text-slate-500 dark:text-slate-400">
                  No refund or dispute requests yet.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  {{-- MOBILE: cards (hidden on md+) --}}
  <div class="md:hidden space-y-3">
    @forelse($requests as $r)
      @php
        $status = $r->status;
        $chip = match(true) {
          in_array($status, ['open','under_review']) => 'bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-100',
          in_array($status, ['approved','refunded']) => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-100',
          $status === 'rejected' => 'bg-rose-100 text-rose-800 dark:bg-rose-900/40 dark:text-rose-100',
          default => 'bg-slate-100 text-slate-800 dark:bg-slate-700 dark:text-slate-200',
        };
      @endphp
      <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-4 shadow-sm hover:shadow-md transition-all">
        <div class="flex items-center justify-between gap-3">
          <a href="{{ route('user.transactions.show', $r->transfer) }}"
             class="font-semibold text-blue-600 dark:text-blue-400 break-all">
            {{ $r->transfer->reference }}
          </a>
          <span class="text-xs capitalize px-2 py-0.5 rounded-lg bg-slate-100 text-slate-700 dark:bg-slate-700 dark:text-slate-200 shrink-0">
            {{ $r->kind }}
          </span>
        </div>

        @if($r->reason)
          <div class="mt-2 text-sm text-slate-600 dark:text-slate-300">
            {{ $r->reason }}
          </div>
        @endif

        <div class="mt-3 grid grid-cols-2 items-center gap-2">
          <span class="inline-flex justify-center md:justify-start items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $chip }}">
            {{ str_replace('_',' ', ucfirst($status)) }}
          </span>
          <span class="text-right text-xs text-slate-500 dark:text-slate-400">
            {{ $r->created_at->format('Y-m-d H:i') }}
          </span>
        </div>
      </div>
    @empty
      <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 text-center text-slate-500 dark:text-slate-400">
        No refund or dispute requests yet.
      </div>
    @endforelse
  </div>

  <div class="mt-6">{{ $requests->links() }}</div>
</div>
@endsection
