@extends('layouts.user')

@section('content')


{{-- ===== Quick Summary Cards ===== --}}
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">


  {{-- Wallet Balance --}}
  <div class="rounded-2xl p-5 text-white shadow-sm"
       style="background: radial-gradient(100% 100% at 0% 0%, #60a5fa 0%, #2563eb 60%, #0f172a 100%);">
    <div class="text-sm opacity-90">Wallet Balance</div>
    <div class="mt-2 text-3xl font-semibold">${{ number_format($balance, 2) }}</div>
  </div>

  {{-- Total Transfers --}}
  <div class="rounded-2xl p-5 text-white shadow-sm"
       style="background: radial-gradient(100% 100% at 0% 0%, #34d399 0%, #059669 60%, #064e3b 100%);">
    <div class="text-sm opacity-90">Total Transfers</div>
    <div class="mt-2 text-3xl font-semibold">{{ $totalTransfers }}</div>
  </div>

  {{-- Pending --}}
  <div class="rounded-2xl p-5 text-white shadow-sm"
       style="background: radial-gradient(100% 100% at 0% 0%, #f59e0b 0%, #d97706 60%, #7c2d12 100%);">
    <div class="text-sm opacity-90">Pending</div>
    <div class="mt-2 text-3xl font-semibold">{{ $pendingTransfers }}</div>
  </div>

  {{-- Beneficiaries --}}
  <div class="rounded-2xl p-5 text-white shadow-sm"
       style="background: radial-gradient(100% 100% at 0% 0%, #ec4899 0%, #be185d 60%, #500724 100%);">
    <div class="text-sm opacity-90">Beneficiaries</div>
    <div class="mt-2 text-3xl font-semibold">{{ $beneficiariesCount }}</div>
  </div>
</div>






{{-- =========================
     MAIN GRID
   ========================= --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
  {{-- =========================
       ANALYTICS (Chart)
     ========================= --}}
  {{-- =========================
     LEFT SIDE: Chart + Live Status
   ========================= --}}
<div class="lg:col-span-2 space-y-6">

  {{-- ===== Send & Receive Chart ===== --}}
  <div class="bg-white dark:bg-slate-900 rounded-2xl border dark:border-slate-800 p-6 overflow-hidden shadow-sm hover:shadow-md transition-all">
    <div class="flex items-center justify-between mb-4">
      <h2 class="font-semibold text-base sm:text-lg text-slate-800 dark:text-slate-100">
        Send & Receive
      </h2>
      <div class="text-xs text-slate-500">Last 6 months</div>
    </div>

    <div class="relative w-full h-64 sm:h-72 md:h-80">
      <canvas id="spendReceiveChart" aria-label="Send & Receive chart" role="img"></canvas>
    </div>
  </div>

  {{-- ===== Live Status (moved below chart) ===== --}}
  <div class="bg-white dark:bg-slate-900 rounded-2xl border dark:border-slate-800 p-6 shadow-sm hover:shadow-md transition-all">
    <div class="flex items-center justify-between mb-3">
      <h2 class="font-semibold text-slate-800 dark:text-slate-100 flex items-center gap-2">
        <i class="fa-solid fa-bolt text-amber-500"></i>
        Live Transfers
      </h2>
      <a href="{{ route('user.transfers.live') }}" class="btn-secondary text-xs">View all</a>
    </div>

    @forelse(($alerts ?? []) as $a)
      <div class="flex items-center justify-between py-2 border-b last:border-b-0 dark:border-slate-800">
        <div class="text-sm">
          <div class="font-medium">
            {{ ucfirst($a->method) }}
            — {{ strtoupper($a->dst_currency) }} {{ number_format($a->amount_dst, 2) }}
          </div>
          <div class="text-xs text-slate-500 break-all">{{ $a->reference }}</div>
        </div>
        <span class="text-xs rounded-full px-2 py-1 font-medium
          @class([
            'bg-amber-100 text-amber-700' => in_array($a->status, ['processing','pending']),
            'bg-red-100 text-red-700' => $a->status === 'failed',
            'bg-emerald-100 text-emerald-700' => $a->status === 'completed',
          ])">
          {{ ucfirst($a->status) }}
        </span>
      </div>
    @empty
      <div class="text-sm text-slate-500">
        All clear. No processing or failed transfers.
      </div>
    @endforelse
  </div>
</div>


  {{-- =========================
       RIGHT SIDEBAR
     ========================= --}}
  <div class="space-y-6">
    {{-- Notifications --}}
    <div class="rounded-2xl border bg-white dark:bg-slate-900 dark:border-slate-800 p-5">
      <div class="flex items-center justify-between mb-3">
        <h3 class="font-semibold">Recent Notifications</h3>
        <a href="{{ route('user.notifications.index') }}" class="btn-secondary text-xs">View all</a>

      </div>
      <ul class="divide-y dark:divide-slate-800 max-h-72 overflow-y-auto">
        @forelse(($latestNotifs ?? []) as $n)
          @php $d = $n->data; @endphp
          <li class="py-3 flex items-start gap-3">
            <span class="mt-0.5 inline-flex w-2 h-2 rounded-full {{ is_null($n->read_at) ? 'bg-blue-500' : 'bg-slate-300' }}"></span>
            <div class="flex-1">
              <div class="text-sm">
                <span class="font-medium">{{ ucfirst($d['status'] ?? 'Update') }}</span>
                — {{ strtoupper($d['currency'] ?? 'USD') }} {{ number_format($d['amount'] ?? 0,2) }}
              </div>
              <div class="text-xs text-slate-500">
                Ref: {{ $d['reference'] ?? '—' }} · {{ $n->created_at->diffForHumans() }}
              </div>
            </div>
            <a class="text-xs text-blue-600 hover:underline"
               href="{{ isset($d['transfer_id']) ? route('user.transfers.show', $d['transfer_id']) : route('user.notifications') }}">
              Open
            </a>
          </li>
        @empty
          <li class="py-8 text-center text-sm text-slate-500">You’re all caught up 🎉</li>
        @endforelse
      </ul>
    </div>

    {{-- =========================
         EXCHANGE RATES (Refresh stays clickable)
       ========================= --}}
    <div class="rounded-2xl p-6 bg-gradient-to-br from-white via-slate-50 to-slate-100 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900 border dark:border-slate-800 shadow-sm hover:shadow-md transition-all">
      <div class="flex items-center justify-between mb-4">
        <div class="flex items-center gap-3">
          <div class="p-2 rounded-full bg-blue-100 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-2.21 0-4 1.79-4 4v1h8v-1c0-2.21-1.79-4-4-4zM6 12v4h12v-4M9 16v1a3 3 0 006 0v-1" />
            </svg>
          </div>
          <h2 class="font-semibold text-slate-800 dark:text-slate-100 text-lg">Exchange Rates</h2>
        </div>

        {{-- NOTE: button remains enabled; we de-bounce in JS but never disable it --}}
        <button id="refreshRates"
                class="text-xs font-medium text-blue-600 dark:text-blue-400 hover:underline focus:outline-none"
                aria-live="polite" aria-label="Refresh exchange rates">
          Refresh
        </button>
      </div>

      {{-- Rate list --}}
      <div id="exchangeRates" class="space-y-4 text-sm font-medium">
        {{-- EUR -> USD --}}
        <div class="flex items-center justify-between flex-wrap gap-2">
          <div class="flex items-center gap-3">
            <div class="w-6 h-4 rounded-sm overflow-hidden border">
              <img src="https://flagcdn.com/w20/eu.png" alt="EU flag" class="w-full h-full object-cover">
            </div>
            <span class="text-slate-600 dark:text-slate-300">1 EUR</span>
          </div>
          <div class="flex items-center gap-2">
            <span id="eurUsdRate" class="text-slate-900 dark:text-slate-100 text-base font-semibold">—</span>
            <span class="text-slate-500 text-xs">USD</span>
            <span id="eurUsdArrow" class="text-xs"></span>
          </div>
        </div>

        {{-- USD -> LBP --}}
        <div class="flex items-center justify-between flex-wrap gap-2">
          <div class="flex items-center gap-3">
            <div class="w-6 h-4 rounded-sm overflow-hidden border">
              <img src="https://flagcdn.com/w20/us.png" alt="US flag" class="w-full h-full object-cover">
            </div>
            <span class="text-slate-600 dark:text-slate-300">1 USD</span>
          </div>
          <div class="flex items-center gap-2">
            <span id="usdLbpRate" class="text-slate-900 dark:text-slate-100 text-base font-semibold">—</span>
            <span class="text-slate-500 text-xs">LBP</span>
            <span id="usdLbpArrow" class="text-xs"></span>
          </div>
        </div>
      </div>

      <div class="mt-5 text-xs text-slate-500 italic text-right" id="rateUpdatedAt">
        Updated {{ now()->format('H:i') }}
      </div>
    </div>

   
     {{-- Live Status 
    <div class="bg-white dark:bg-slate-900 rounded-2xl border dark:border-slate-800 p-6">
      <div class="flex items-center justify-between mb-3">
        <h2 class="font-semibold">Live Status</h2>
        <a href="{{ route('user.transfers.live') }}" class="text-xs text-blue-600 hover:underline">View all</a>
      </div>

      @forelse(($alerts ?? []) as $a)
        <div class="flex items-center justify-between py-2 border-b last:border-b-0 dark:border-slate-800">
          <div class="text-sm">
            <div class="font-medium">
              {{ ucfirst($a->method) }}
              — {{ strtoupper($a->dst_currency) }} {{ number_format($a->amount_dst,2) }}
            </div>
            <div class="text-xs text-slate-500 break-all">{{ $a->reference }}</div>
          </div>
          <span class="text-xs rounded-full px-2 py-1
            @class([
              'bg-amber-100 text-amber-700' => in_array($a->status, ['processing','pending']),
              'bg-red-100 text-red-700' => $a->status === 'failed',
              'bg-emerald-100 text-emerald-700' => $a->status === 'completed',
            ])">
            {{ ucfirst($a->status) }}
          </span>
        </div>
      @empty
        <div class="text-sm text-slate-500">All clear. No processing/failed transfers.</div>
      @endforelse
    </div>--}}

    {{-- Reviews & Rating (kept; average shown here, not in top cards) --}}
    <div class="bg-white dark:bg-slate-900 rounded-2xl border dark:border-slate-800 p-6">
  <div class="flex items-center justify-between flex-wrap gap-2">
    <h2 class="font-semibold">Rate Our Service</h2>
   {{--   <div class="text-sm text-slate-500">Average: <strong id="avgRating">{{ $avgRating ?? '—' }}</strong> / 5</div>--}}
  </div>

  <form id="ratingForm" class="mt-4">
    @csrf
    <div class="flex items-center gap-2 flex-wrap">
      <input type="hidden" name="score" id="ratingScore" value="{{ $myRating->score ?? 0 }}">
      @for($i=1;$i<=5;$i++)
        <button type="button" data-star="{{ $i }}" class="star-btn text-2xl sm:text-xl transition" aria-label="rate {{ $i }}">⭐</button>
      @endfor
    </div>
    <textarea id="commentBox" name="comment" rows="2" placeholder="Optional comment…"
              class="mt-3 w-full border rounded-xl px-3 py-2 dark:bg-slate-900 dark:border-slate-700 text-sm">{{ '' }}</textarea>
    <div class="mt-3 flex items-center gap-3 flex-wrap">
      <button type="submit" class="rounded-xl bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 text-sm">Save Rating</button>
      <div id="ratingMsg" class="text-sm text-green-600 hidden">✔️ Saved!</div>
    </div>
  </form>
</div>

  </div>
</div>

{{-- =========================
     SCRIPTS
   ========================= --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  // -------- Dark/Light toggle (safe if the button isn't present)
  (function() {
    const html   = document.documentElement;
    const toggle = document.getElementById('modeToggle');
    const apply  = (on) => {
      if (on) {
        html.classList.add('dark');
        toggle?.querySelector('.light-only')?.classList.add('hidden');
        toggle?.querySelector('.dark-only')?.classList.remove('hidden');
      } else {
        html.classList.remove('dark');
        toggle?.querySelector('.light-only')?.classList.remove('hidden');
        toggle?.querySelector('.dark-only')?.classList.add('hidden');
      }
    };
    let saved = localStorage.getItem('theme.dark') === '1';
    apply(saved);
    if (toggle) {
      toggle.addEventListener('click', () => {
        saved = !saved;
        localStorage.setItem('theme.dark', saved ? '1' : '0');
        apply(saved);
      });
    }
  })();

  // -------- Chart.js (responsive height; compact)
  (function() {
    const labels  = @json($labels ?? []);
    const spend   = @json($spend ?? []);
    const receive = @json($receive ?? []);
    const ctx     = document.getElementById('spendReceiveChart').getContext('2d');

    new Chart(ctx, {
      type: 'line',
      data: {
        labels,
        datasets: [
          {
            label: 'Spend',
            data: spend,
            borderColor: '#ef4444',
            backgroundColor: 'rgba(239,68,68,0.10)',
            borderWidth: 2,
            pointRadius: 2.5,
            tension: 0.35,
            fill: true
          },
          {
            label: 'Receive',
            data: receive,
            borderColor: '#3b82f6',
            backgroundColor: 'rgba(59,130,246,0.10)',
            borderWidth: 2,
            pointRadius: 2.5,
            tension: 0.35,
            fill: true
          }
        ]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,   // uses parent div height
        layout: { padding: 8 },
        animation: { duration: 1100, easing: 'easeOutQuart' },
        plugins: {
          legend: { labels: { color: '#6b7280' } },
          tooltip: { mode: 'index', intersect: false }
        },
        interaction: { mode: 'nearest', axis: 'x', intersect: false },
        scales: {
          x: {
            ticks: { color: '#9ca3af', font: { size: 11 } },
            grid:  { color: 'rgba(156,163,175,0.15)' }
          },
          y: {
            beginAtZero: true,
            ticks: { color: '#9ca3af', font: { size: 11 } },
            grid:  { color: 'rgba(156,163,175,0.15)' }
          }
        }
      }
    });
  })();

  // -------- Stars rating UI
  (function() {
  const form = document.getElementById('ratingForm');
  const scoreInput = document.getElementById('ratingScore');
  const commentBox = document.getElementById('commentBox');
  const msg = document.getElementById('ratingMsg');
  const avgDisplay = document.getElementById('avgRating');
  const stars = Array.from(document.querySelectorAll('.star-btn'));

  // Initial star color setup
  const paint = (n) => stars.forEach((s, i) => s.style.filter = (i < n ? 'grayscale(0%)' : 'grayscale(100%)'));
  paint(Number(scoreInput.value || 0));

  // Hover + click logic
  stars.forEach(btn => {
    btn.addEventListener('mouseenter', () => paint(Number(btn.dataset.star)));
    btn.addEventListener('mouseleave', () => paint(Number(scoreInput.value || 0)));
    btn.addEventListener('click', () => {
      scoreInput.value = btn.dataset.star;
      paint(Number(btn.dataset.star));
    });
  });

  // AJAX submission to prevent reload
  form.addEventListener('submit', async (e) => {
    e.preventDefault();

    const formData = new FormData(form);

    try {
      const res = await fetch("{{ route('user.reviews.store') }}", {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': form.querySelector('input[name=_token]').value },
        body: formData
      });

      if (!res.ok) throw new Error('Network error');
      const html = await res.text(); // Accept Laravel’s normal response

      // Reset form visually
      commentBox.value = '';
      scoreInput.value = 0;
      paint(0);

      // Show temporary success message
      msg.classList.remove('hidden');
      msg.textContent = '✔️ Rating saved successfully!';
      setTimeout(() => msg.classList.add('hidden'), 3000);

    } catch (err) {
      alert('⚠️ Failed to save rating. Please try again.');
    }
  });
})();

  // -------- Exchange Rates (button never disabled; de-bounced; 8s timeout)
  (function() {
    const btn   = document.getElementById('refreshRates');
    const eurEl = document.getElementById('eurUsdRate');
    const lbpEl = document.getElementById('usdLbpRate');
    const aEU   = document.getElementById('eurUsdArrow');
    const aLBP  = document.getElementById('usdLbpArrow');
    const when  = document.getElementById('rateUpdatedAt');

    if (!btn || !eurEl || !lbpEl) return;

    let inFlight = false;
    let lastEur  = null;
    let lastLbp  = null;

    function arrow(el, oldV, newV) {
      if (oldV === null || newV === null) { el.textContent = ''; el.className='text-xs'; return; }
      if (newV > oldV)  { el.textContent = '▲'; el.className='text-xs text-green-600'; }
      else if (newV < oldV) { el.textContent = '▼'; el.className='text-xs text-red-600'; }
      else { el.textContent = '—'; el.className='text-xs text-slate-400'; }
    }

    async function fetchRates() {
      if (inFlight) return;           // de-bounce but keep button clickable
      inFlight = true;

      // soft spinner text; button remains clickable
      const orig = btn.textContent;
      btn.textContent = 'Refreshing…';

      // optimistic UI
      eurEl.textContent = '…';
      lbpEl.textContent = '…';
      aEU.textContent = ''; aLBP.textContent = '';

      // 8s fetch timeout so UI never hangs
      const ctrl = new AbortController();
      const t = setTimeout(() => ctrl.abort(), 8000);

      try {
        const res  = await fetch("{{ route('exchange.rates') }}", { signal: ctrl.signal, cache: 'no-store' });
        const data = await res.json();
        if (!data || !data.success) throw new Error(data?.message || 'Failed');

        const eurUsd = Number(data.rates?.EUR_USD ?? NaN);
        const usdLbp = Number(data.rates?.USD_LBP ?? NaN);

        // arrows + values
        arrow(aEU,  lastEur, eurUsd);
        arrow(aLBP, lastLbp, usdLbp);
        lastEur = isFinite(eurUsd) ? eurUsd : lastEur;
        lastLbp = isFinite(usdLbp) ? usdLbp : lastLbp;

        eurEl.textContent = isFinite(eurUsd) ? eurUsd.toFixed(4) : '—';
        lbpEl.textContent = isFinite(usdLbp) ? usdLbp.toLocaleString(undefined, { maximumFractionDigits: 2 }) : '—';
        when.textContent  = 'Updated ' + new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
      } catch (e) {
        // graceful failure; keep button usable
        eurEl.textContent = '—';
        lbpEl.textContent = '—';
        aEU.textContent = ''; aLBP.textContent = '';
        console.error('Exchange rate fetch error:', e);
      } finally {
        clearTimeout(t);
        btn.textContent = orig;
        inFlight = false;
      }
    }

    btn.addEventListener('click', fetchRates);
    // initial fetch on load
    fetchRates();
    // refresh every 10 minutes
    setInterval(fetchRates, 10 * 60 * 1000);
  })();
</script>

{{-- =========================
     SMALL RESPONSIVE TWEAKS
   ========================= --}}
<style>
  @media (max-width: 640px) {
    h2 { font-size: 1rem !important; }
    .text-3xl { font-size: 1.6rem !important; }
    .p-6 { padding: 1.25rem !important; }
  }

  .dark .dark-only { display: inline; }
  .dark .light-only { display: none; }

  .btn-primary {
    @apply inline-flex items-center justify-center rounded-xl 
           bg-gradient-to-r from-blue-600 to-indigo-600 
           text-white font-semibold text-sm px-4 py-2.5 
           shadow-md hover:from-blue-700 hover:to-indigo-700 
           hover:shadow-lg focus:outline-none focus:ring-2 
           focus:ring-blue-400 focus:ring-offset-1 
           transition-all duration-200;
  }

  .btn-secondary {
    @apply inline-flex items-center justify-center 
           text-blue-600 dark:text-blue-400 
           font-medium text-sm hover:text-indigo-600 
           dark:hover:text-indigo-300 
           hover:underline transition-colors duration-150;
  }

  .btn-refresh {
    @apply inline-flex items-center justify-center text-xs 
           text-blue-600 dark:text-blue-400 font-medium 
           hover:text-indigo-600 dark:hover:text-indigo-300 
           hover:scale-105 active:scale-95 
           transition-transform duration-150 ease-out;
  }
</style>




@endsection
