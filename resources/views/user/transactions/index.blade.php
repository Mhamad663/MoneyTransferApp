{{-- resources/views/user/transactions/index.blade.php --}}
@extends('layouts.user')

@section('content')
{{-- Header actions --}}
<div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between mb-6">
 {{--   <h1 class="text-xl font-semibold text-slate-800 dark:text-slate-100">Transactions</h1>--}}

  <div class="flex items-center gap-2">
    {{-- View refunds (list all refund/dispute requests) --}}
    <a href="{{ route('user.refunds.index') }}"
       class="inline-flex items-center gap-2 rounded-lg border border-slate-300 dark:border-slate-700
              px-3 py-2 text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7"
              d="M4 8h16M4 12h10M4 16h7"/>
      </svg>
      View refunds
    </a>

    {{-- Export PDF --}}
    <a href="{{ route('user.transactions.export.pdf') }}"
       class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 hover:bg-indigo-700
              text-white px-3 py-2 text-sm shadow-sm">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
        <path d="M4 3a2 2 0 00-2 2v8a2 2 0 002 2h6v-2H4V7h12v3h2V5a2 2 0 00-2-2H4z"/>
        <path d="M17 13l3 3-3 3v-2h-5v-2h5v-2z"/>
      </svg>
      Export PDF
    </a>
  </div>
</div>

{{-- Desktop table --}}
<div class="hidden md:block bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl">
  <div class="overflow-x-auto">
    <table class="w-full text-sm">
      <thead class="text-left bg-slate-50 dark:bg-slate-800/50 text-slate-600 dark:text-slate-300">
        <tr>
          <th class="py-3 px-3">Reference</th>
          <th class="py-3 px-3">Method</th>
          <th class="py-3 px-3">Service</th>
          <th class="py-3 px-3">Receiver</th>
          <th class="py-3 px-3">Amount</th>
          <th class="py-3 px-3">Fee</th>
          <th class="py-3 px-3">Total</th>
          <th class="py-3 px-3">Status</th>
          <th class="py-3 px-3">Created</th>
          <th class="py-3 px-3">Refund</th>
          <th class="py-3 px-3 text-right">Action</th>
        </tr>
      </thead>

      <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
        @forelse($items as $row)
          <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-800/40">
            <td class="py-3 px-3 font-mono text-xs text-slate-600 dark:text-slate-300">{{ $row->reference }}</td>
            <td class="py-3 px-3 capitalize text-slate-800 dark:text-slate-100">{{ $row->method }}</td>
            <td class="py-3 px-3 text-slate-700 dark:text-slate-200">{{ $row->service->name ?? '—' }}</td>
            <td class="py-3 px-3 text-slate-700 dark:text-slate-200">
              {{ $row->beneficiary->name ?? $row->destination }}
            </td>
            <td class="py-3 px-3 text-slate-800 dark:text-slate-100">
              {{ strtoupper($row->src_currency) }} {{ number_format($row->amount_dst,2) }}
            </td>
            <td class="py-3 px-3 text-slate-700 dark:text-slate-200">{{ number_format($row->fee,2) }}</td>
            <td class="py-3 px-3 font-medium text-slate-900 dark:text-white">
              {{ strtoupper($row->src_currency) }} {{ number_format($row->amount_dst + $row->fee,2) }}
            </td>
            <td class="py-3 px-3">
              <span class="inline-block px-2 py-0.5 rounded text-xs
                          bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300">
                {{ ucfirst($row->status) }}
              </span>
            </td>
            <td class="py-3 px-3 text-slate-600 dark:text-slate-300">{{ $row->created_at->format('Y-m-d H:i') }}</td>

            {{-- Refund column --}}
                     <td class="py-2 px-3">
                @if($row->has_refund_request ?? false)
                        <span class="inline-flex items-center gap-1 text-xs px-2 py-1 rounded-full 
                     bg-slate-200 text-slate-700 
                     dark:bg-slate-700 dark:text-slate-300">
                               Requested
                                 </span>
                 @else
                        <a href="{{ route('user.refunds.create', $row->id) }}"
                      class="inline-flex items-center gap-1 text-xs font-medium px-3 py-1.5 rounded-full
                             bg-red-600 text-white hover:bg-red-700 
                            shadow-sm transition-all duration-150">
                          {{--  <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor"> --}}
                         <path d="M10 3a7 7 0 100 14A7 7 0 0010 3zM9 7h2v4H9V7zm0 6h2v2H9v-2z"/>
                        </svg>
                          Request
                 </a>
                     @endif
                    </td>


            {{-- Actions --}}
            <td class="py-2 px-3 text-right space-x-2">

    {{-- Receipt Button --}}
    <a href="{{ route('user.transactions.show',$row) }}"
       class="inline-flex items-center gap-1 text-xs font-medium px-3 py-1.5 rounded-full
              bg-blue-50 text-blue-700 hover:bg-blue-100
              dark:bg-blue-900/30 dark:text-blue-300 dark:hover:bg-blue-900/50
              transition-all duration-150 shadow-sm">

        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none"
             viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round"
                d="M12 6v12m-6-6h12" />
        </svg>

        Receipt
    </a>

    {{-- PDF Button --}}
    <a href="{{ route('user.transactions.pdf',$row) }}"
       class="inline-flex items-center gap-1 text-xs font-medium px-3 py-1.5 rounded-full
              bg-slate-100 text-slate-700 hover:bg-slate-200
              dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700
              transition-all duration-150 shadow-sm">

        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="currentColor"
             viewBox="0 0 20 20">
          <path d="M4 3a2 2 0 00-2 2v8a2 2 0 002 2h6v-2H4V7h12v3h2V5a2 2 0 00-2-2H4z"/>
          <path d="M17 13l3 3-3 3v-2h-5v-2h5v-2z"/>
        </svg>

        PDF
    </a>

</td>

          </tr>
        @empty
          <tr>
            <td colspan="11" class="py-10 text-center text-slate-500 dark:text-slate-400">
              No completed transactions.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="px-4 py-3 border-t border-slate-100 dark:border-slate-800">
    {{ $items->links() }}
  </div>
</div>

{{-- Mobile cards --}}
<div class="md:hidden space-y-3">
  @forelse($items as $row)
    <div class="rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-4">
      <div class="flex items-start justify-between gap-3">
        <div>
          <div class="font-mono text-xs text-slate-500 dark:text-slate-400">{{ $row->reference }}</div>
          <div class="mt-1 text-sm text-slate-800 dark:text-slate-100">
            <span class="capitalize">{{ $row->method }}</span> •
            {{ $row->service->name ?? '—' }}
          </div>
          <div class="text-xs text-slate-500 dark:text-slate-400">
            {{ $row->created_at->format('Y-m-d H:i') }}
          </div>
        </div>

        <span class="shrink-0 inline-block px-2 py-0.5 rounded text-xs
                     bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300">
          {{ ucfirst($row->status) }}
        </span>
      </div>

      <div class="mt-3 grid grid-cols-2 gap-2 text-sm">
        <div class="text-slate-500 dark:text-slate-400">Receiver</div>
        <div class="text-slate-800 dark:text-slate-100 text-right">
          {{ $row->beneficiary->name ?? $row->destination }}
        </div>

        <div class="text-slate-500 dark:text-slate-400">Amount</div>
        <div class="text-slate-800 dark:text-slate-100 text-right">
          {{ strtoupper($row->src_currency) }} {{ number_format($row->amount_dst,2) }}
        </div>

        <div class="text-slate-500 dark:text-slate-400">Total</div>
        <div class="text-slate-900 dark:text-white text-right font-medium">
          {{ strtoupper($row->src_currency) }} {{ number_format($row->amount_dst + $row->fee,2) }}
        </div>
      </div>

      <div class="mt-3 flex items-center justify-between">
        @if($row->has_refund_request ?? false)
          <span class="text-xs px-2 py-1 rounded bg-slate-200 text-slate-700 dark:bg-slate-700 dark:text-slate-200">
            Requested
          </span>
        @else
          <a href="{{ route('user.refunds.create', $row->id) }}"
             class="text-xs bg-red-600 text-white px-2 py-1 rounded hover:bg-red-700">
            Request Refund
          </a>
        @endif

        <div class="text-sm space-x-3">
          <a href="{{ route('user.transactions.show',$row) }}"
             class="text-blue-600 dark:text-blue-400 hover:underline">Receipt</a>
          <a href="{{ route('user.transactions.pdf',$row) }}"
             class="text-slate-700 dark:text-slate-200 hover:underline">PDF</a>
        </div>
      </div>
    </div>
  @empty
    <div class="text-center text-slate-500 dark:text-slate-400">
      No completed transactions.
    </div>
  @endforelse

  <div>
    {{ $items->links() }}
  </div>
</div>
@endsection
