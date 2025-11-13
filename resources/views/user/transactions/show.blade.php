@extends('layouts.user')

@section('content')
<div class="min-h-screen bg-slate-50 dark:bg-slate-950 px-4 sm:px-6 lg:px-8 py-8 transition-colors duration-300">
  <div class="max-w-5xl mx-auto space-y-6">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
      <div>
        <h1 class="text-3xl font-bold tracking-tight text-slate-900 dark:text-slate-100 flex items-center gap-2">
          🧾 Receipt
        </h1>
        <p class="text-sm text-slate-500 dark:text-slate-400">Transaction details and confirmation summary.</p>
      </div>

      <div class="flex flex-wrap items-center gap-3">
        <a href="{{ route('user.transactions') }}"
           class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2 text-slate-800 shadow hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-100 dark:hover:bg-slate-800">
          ← Back to Transactions
        </a>
        <button onclick="window.print()"
                class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-4 py-2 text-white shadow hover:bg-indigo-700">
          🖨️ Print
        </button>
      </div>
    </div>

    {{-- Summary Card --}}
    <div class="rounded-2xl border border-slate-200 bg-white shadow-xl dark:border-slate-800 dark:bg-slate-900 overflow-hidden">
      {{-- Top stripe --}}
      <div class="h-1 w-full bg-gradient-to-r from-indigo-500 via-blue-500 to-cyan-500"></div>

      <div class="p-6 sm:p-8 space-y-8">

        {{-- Reference + Date + Status --}}
        <div class="grid sm:grid-cols-3 gap-6 border-b border-slate-200 dark:border-slate-800 pb-6">
          <div>
            <div class="text-xs font-medium uppercase tracking-wider text-slate-500 dark:text-slate-400">Reference</div>
            <div class="mt-1 flex items-center gap-2">
              <code id="refText" class="rounded-lg bg-slate-100 px-2.5 py-1.5 text-sm text-slate-800 dark:bg-slate-800 dark:text-slate-200">{{ $t->reference }}</code>
              <button type="button" onclick="copyRef()"
                      class="rounded-lg border border-slate-200 px-3 py-1.5 text-xs text-slate-700 hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800">
                Copy
              </button>
            </div>
          </div>

          <div>
            <div class="text-xs font-medium uppercase tracking-wider text-slate-500 dark:text-slate-400">Date</div>
            <div class="mt-1 text-slate-900 dark:text-slate-100">{{ $t->created_at->format('Y-m-d H:i') }}</div>
          </div>

          <div>
            <div class="text-xs font-medium uppercase tracking-wider text-slate-500 dark:text-slate-400">Status</div>
            @php
              $badge = match($t->status){
                'completed' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300',
                'processing' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300',
                'pending' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300',
                'failed' => 'bg-rose-100 text-rose-700 dark:bg-rose-900/40 dark:text-rose-300',
                default => 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300'
              };
            @endphp
            <div class="mt-1">
              <span class="px-2.5 py-1 rounded-lg text-xs font-semibold {{ $badge }}">
                {{ ucfirst($t->status) }}
              </span>
            </div>
          </div>
        </div>

        {{-- Details + Totals --}}
        <div class="grid lg:grid-cols-3 gap-6">
          {{-- Details --}}
          <div class="lg:col-span-2">
            <div class="grid sm:grid-cols-2 gap-6">
              <div class="space-y-3">
                <div>
                  <div class="text-xs uppercase tracking-wider text-slate-500 dark:text-slate-400">Payment Method</div>
                  <div class="mt-1 font-medium text-slate-900 dark:text-slate-100 capitalize">{{ $t->method }}</div>
                </div>

                <div>
                  <div class="text-xs uppercase tracking-wider text-slate-500 dark:text-slate-400">Service</div>
                  <div class="mt-1 text-slate-900 dark:text-slate-100">{{ $t->service->name ?? '—' }}</div>
                </div>

                <div>
                  <div class="text-xs uppercase tracking-wider text-slate-500 dark:text-slate-400">Source</div>
                  <div class="mt-1 text-slate-900 dark:text-slate-100 break-words">{{ $t->source }}</div>
                </div>

                <div>
                  <div class="text-xs uppercase tracking-wider text-slate-500 dark:text-slate-400">Destination</div>
                  <div class="mt-1 text-slate-900 dark:text-slate-100 break-words">{{ $t->destination }}</div>
                </div>
              </div>

              <div class="space-y-3">
                <div>
                  <div class="text-xs uppercase tracking-wider text-slate-500 dark:text-slate-400">Source Currency</div>
                  <div class="mt-1 text-slate-900 dark:text-slate-100">{{ strtoupper($t->src_currency) }}</div>
                </div>

                <div>
                  <div class="text-xs uppercase tracking-wider text-slate-500 dark:text-slate-400">Destination Currency</div>
                  <div class="mt-1 text-slate-900 dark:text-slate-100">{{ strtoupper($t->dst_currency ?? $t->src_currency) }}</div>
                </div>

                <div>
                  <div class="text-xs uppercase tracking-wider text-slate-500 dark:text-slate-400">Exchange Rate</div>
                  <div class="mt-1 text-slate-900 dark:text-slate-100">
                    @if(!is_null($t->fx_rate))
                      1 {{ strtoupper($t->src_currency) }} = {{ number_format($t->fx_rate, 6) }} {{ strtoupper($t->dst_currency ?? $t->src_currency) }}
                    @else
                      —
                    @endif
                  </div>
                </div>

                <div>
                  <div class="text-xs uppercase tracking-wider text-slate-500 dark:text-slate-400">Processor</div>
                  <div class="mt-1 text-slate-900 dark:text-slate-100">{{ $t->processor ?? 'System' }}</div>
                </div>
              </div>
            </div>
          </div>

          {{-- Totals box --}}
          <div>
            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5 dark:border-slate-800 dark:bg-slate-900/50">
              <h3 class="text-sm font-semibold text-slate-700 dark:text-slate-200 mb-3">Payment Summary</h3>

              <dl class="space-y-2 text-sm">
                <div class="flex items-center justify-between">
                  <dt class="text-slate-500 dark:text-slate-400">Amount</dt>
                  <dd class="text-slate-900 dark:text-slate-100">
                    {{ strtoupper($t->dst_currency ?? $t->src_currency) }}
                    {{ number_format($t->amount_dst, 2) }}
                  </dd>
                </div>

                <div class="flex items-center justify-between">
                  <dt class="text-slate-500 dark:text-slate-400">Fee</dt>
                  <dd class="text-slate-900 dark:text-slate-100">
                    {{ strtoupper($t->src_currency) }}
                    {{ number_format($t->fee, 2) }}
                  </dd>
                </div>

                @if(!is_null($t->fx_rate))
                <div class="flex items-center justify-between">
                  <dt class="text-slate-500 dark:text-slate-400">FX Rate</dt>
                  <dd class="text-slate-900 dark:text-slate-100">
                    {{ number_format($t->fx_rate, 6) }}
                  </dd>
                </div>
                @endif
              </dl>

              <div class="mt-4 border-t border-slate-200 dark:border-slate-800 pt-3">
                <div class="flex items-center justify-between">
                  <span class="text-sm font-semibold text-slate-700 dark:text-slate-200">Total</span>
                  <span class="text-lg font-bold text-indigo-700 dark:text-indigo-300">
                    {{ strtoupper($t->src_currency) }}
                    {{ number_format(($t->amount_dst ?? 0) + ($t->fee ?? 0), 2) }}
                  </span>
                </div>
              </div>
            </div>
          </div>
        </div>

        {{-- Timeline --}}
        @if($t->events && $t->events->count())
          <div class="pt-2">
            <h3 class="text-base font-semibold text-slate-900 dark:text-slate-100 mb-3">Timeline</h3>
            <ol class="relative border-l-2 border-indigo-500 dark:border-indigo-400 pl-5 space-y-4">
              @foreach($t->events as $e)
                <li class="relative">
                  <span class="absolute -left-[9px] top-1 block h-3 w-3 rounded-full bg-indigo-600 dark:bg-indigo-400"></span>
                  <div class="text-sm text-slate-900 dark:text-slate-100">
                    <span class="font-semibold">{{ ucfirst($e->event) }}</span>
                    <span class="text-xs text-slate-500 dark:text-slate-400"> · {{ $e->created_at->format('Y-m-d H:i') }}</span>
                  </div>
                  @if($e->meta)
                    <div class="text-xs text-slate-600 dark:text-slate-300 mt-1 break-words">{{ $e->meta }}</div>
                  @endif
                </li>
              @endforeach
            </ol>
          </div>
        @endif

      </div>
    </div>

  </div>
</div>

{{-- Helpers --}}
<script>
  function copyRef(){
    const el = document.getElementById('refText');
    if(!el) return;
    const txt = el.innerText.trim();
    navigator.clipboard.writeText(txt).then(() => {
      // Tiny visual feedback
      el.classList.add('ring-2','ring-indigo-400');
      setTimeout(() => el.classList.remove('ring-2','ring-indigo-400'), 600);
    });
  }
</script>

{{-- Print styles --}}
<style>
@media print {
  body { background: #fff !important; color: #000 !important; }
  nav, header, footer, .no-print, [onclick], a[href] { display: none !important; }
  .shadow-xl, .rounded-2xl { box-shadow: none !important; }
  .border { border-color: #bbb !important; }
  .bg-white, .dark\:bg-slate-900 { background: #fff !important; }
  code { background: #f3f4f6 !important; color: #000 !important; }
}
</style>
@endsection
