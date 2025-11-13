@extends('layouts.user')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

  {{-- Header --}}
  <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-3">
    <h1 class="text-2xl font-semibold text-slate-800 dark:text-slate-100 flex items-center gap-2">
      <span class="relative flex h-4 w-4">
        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
        <span class="relative inline-flex rounded-full h-4 w-4 bg-green-500"></span>
      </span>
      Live Transfers
    </h1>
    <button onclick="loadLive()"
      class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium rounded-lg 
             bg-blue-600 text-white hover:bg-blue-700 transition-all 
             dark:bg-blue-500 dark:hover:bg-blue-600 shadow">
      🔄 Refresh
    </button>
  </div>

  <div class="text-sm text-slate-600 dark:text-slate-400 mb-4">
    Monitoring all <span class="text-blue-600 dark:text-blue-400 font-medium">Processing</span> and 
    <span class="text-rose-600 dark:text-rose-400 font-medium">Failed</span> transfers — updates every <b>7s</b>.
  </div>

  {{-- Last refresh --}}
  <div id="last-refresh" class="text-xs text-slate-500 dark:text-slate-400 mb-2">Last refresh: —</div>

  {{-- Desktop Table --}}
  <div class="hidden md:block bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-sm overflow-hidden">
    <table class="w-full text-sm">
      <thead class="bg-slate-50 dark:bg-slate-800/60">
        <tr class="text-slate-600 dark:text-slate-300 uppercase text-xs tracking-wide">
          <th class="py-3 px-4 text-left">Reference</th>
          <th class="py-3 px-4 text-left">Method</th>
          <th class="py-3 px-4 text-left">Service</th>
          <th class="py-3 px-4 text-left">Amount</th>
          <th class="py-3 px-4 text-left">Status</th>
          <th class="py-3 px-4 text-left">Created</th>
          <th class="py-3 px-4 text-left">Updated</th>
          <th class="py-3 px-4 text-right">Action</th>
        </tr>
      </thead>
      <tbody id="live-body" class="divide-y divide-slate-100 dark:divide-slate-800">
        <tr><td colspan="8" class="py-8 text-center text-slate-500 dark:text-slate-400">Loading…</td></tr>
      </tbody>
    </table>
  </div>

  {{-- Mobile Cards --}}
  <div id="live-cards" class="md:hidden space-y-4">
    <div class="rounded-xl border dark:border-slate-800 bg-white dark:bg-slate-900 p-4 text-center text-slate-500 dark:text-slate-400">
      Loading…
    </div>
  </div>
</div>

{{-- Styles --}}
<style>
  .badge {
    display: inline-block;
    padding: .25rem .6rem;
    border-radius: .375rem;
    font-size: .75rem;
    font-weight: 600;
  }
  .badge-processing {
    background: #fef3c7;
    color: #92400e;
  }
  .badge-failed {
    background: #fee2e2;
    color: #991b1b;
  }
  @media (prefers-color-scheme: dark) {
    .badge-processing {
      background: rgba(251, 191, 36, .15);
      color: #fcd34d;
    }
    .badge-failed {
      background: rgba(239, 68, 68, .15);
      color: #fca5a5;
    }
  }
</style>

{{-- Script --}}
<script>
const bodyEl = document.getElementById('live-body');
const cardsEl = document.getElementById('live-cards');
const lastEl = document.getElementById('last-refresh');

async function loadLive() {
  try {
    const res = await fetch('{{ route('user.transfers.live.data') }}', { headers: {'X-Requested-With': 'XMLHttpRequest'} });
    const json = await res.json();
    const rows = json.items;

    if (!rows.length) {
      bodyEl.innerHTML = `<tr><td colspan="8" class="py-8 text-center text-slate-500 dark:text-slate-400">No processing or failed transfers.</td></tr>`;
      cardsEl.innerHTML = `<div class="rounded-xl border dark:border-slate-800 bg-white dark:bg-slate-900 p-4 text-center text-slate-500 dark:text-slate-400">
        No processing or failed transfers.
      </div>`;
    } else {
      // Desktop rows
      bodyEl.innerHTML = rows.map(r => `
        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition">
          <td class="py-2 px-4 font-mono text-xs text-slate-700 dark:text-slate-200">${r.reference}</td>
          <td class="py-2 px-4 capitalize text-slate-700 dark:text-slate-200">${r.method}</td>
          <td class="py-2 px-4 text-slate-700 dark:text-slate-200">${r.service}</td>
          <td class="py-2 px-4 font-medium text-slate-800 dark:text-slate-100">${r.amount}</td>
          <td class="py-2 px-4">
            ${r.status === 'Processing'
              ? `<span class='badge badge-processing'>Processing</span>`
              : `<span class='badge badge-failed'>Failed</span>`}
          </td>
          <td class="py-2 px-4 text-slate-600 dark:text-slate-400">${r.created_at}</td>
          <td class="py-2 px-4 text-slate-600 dark:text-slate-400">${r.updated_at}</td>
          <td class="py-2 px-4 text-right">
            <a href="${r.show_url}"
               class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium
                      rounded-lg border border-blue-600 text-blue-600 hover:bg-blue-600 hover:text-white
                      dark:border-blue-400 dark:text-blue-400 dark:hover:bg-blue-500 dark:hover:text-white
                      transition-all duration-200 shadow-sm">
              <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='1.5' stroke='currentColor' class='w-4 h-4'>
                <path stroke-linecap='round' stroke-linejoin='round' d='M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z' />
              </svg>
              Details
            </a>
          </td>
        </tr>
      `).join('');

      // Mobile cards
      cardsEl.innerHTML = rows.map(r => `
        <div class="rounded-2xl border dark:border-slate-800 bg-white dark:bg-slate-900 p-4 space-y-2 shadow-sm">
          <div class="flex justify-between items-center">
            <div class="text-xs text-slate-500 dark:text-slate-400">Ref: ${r.reference}</div>
            ${r.status === 'Processing'
              ? `<span class='badge badge-processing'>Processing</span>`
              : `<span class='badge badge-failed'>Failed</span>`}
          </div>
          <div class="font-medium text-slate-800 dark:text-slate-100">${r.service}</div>
          <div class="text-sm text-slate-600 dark:text-slate-400 capitalize">${r.method}</div>
          <div class="text-sm font-semibold text-slate-800 dark:text-slate-100">${r.amount}</div>
          <div class="flex justify-between text-xs text-slate-500 dark:text-slate-400">
            <span>Created: ${r.created_at}</span>
            <span>Updated: ${r.updated_at}</span>
          </div>
          <div class="pt-2 text-right">
            <a href="${r.show_url}"
               class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium
                      rounded-lg bg-blue-600 text-white hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600
                      transition-all duration-200 shadow-sm">
              <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='1.5' stroke='currentColor' class='w-4 h-4'>
                <path stroke-linecap='round' stroke-linejoin='round' d='M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z' />
              </svg>
              Details
            </a>
          </div>
        </div>
      `).join('');
    }

    lastEl.textContent = 'Last refresh: ' + new Date().toLocaleTimeString();
  } catch (e) {
    console.error(e);
  }
}

loadLive();
setInterval(loadLive, 7000);
</script>
@endsection
