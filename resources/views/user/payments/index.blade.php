@extends('layouts.user')

@section('content')
{{-- ===== HEADER ===== --}}
<div class="mb-6 flex flex-wrap items-center justify-between gap-3">
  <div>
    <h1 class="text-2xl font-semibold">Payment Methods</h1>
    <p class="text-sm text-slate-500 dark:text-slate-400">
      Manage your cards and bank accounts used for funding.
    </p>
  </div>
  <div class="flex items-center gap-2">
    <a href="{{ route('user.payments.create') }}"
       class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-4 py-2 text-white shadow hover:bg-indigo-700 transition">
      <span class="text-lg leading-none">＋</span> Add New
    </a>
    <a href="{{ route('stripe.add.card') }}"
       class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2 text-slate-800 shadow hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 dark:hover:bg-slate-800 transition">
      💳 Add Card (Stripe)
    </a>
  </div>
</div>

{{-- ===== ALERTS ===== --}}
@if(session('success'))
  <div class="mb-4 rounded-xl bg-emerald-50 px-4 py-3 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-200 shadow-sm">
    {{ session('success') }}
  </div>
@endif
@if(session('error'))
  <div class="mb-4 rounded-xl bg-rose-50 px-4 py-3 text-rose-800 dark:bg-rose-900/30 dark:text-rose-200 shadow-sm">
    {{ session('error') }}
  </div>
@endif

{{-- ===== SIDE BY SIDE LAYOUT ===== --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-10">

  {{-- ===== CARDS SECTION ===== --}}
  <section class="flex flex-col">
    <div class="flex items-end justify-between">
      <div>
        <h2 class="text-xl font-semibold">Cards</h2>
        <p class="text-sm text-slate-500 dark:text-slate-400">Your saved cards.</p>
      </div>
      <div class="text-xs text-slate-400 dark:text-slate-500">{{ $cards->total() }} total</div>
    </div>

    <div class="mt-6 flex flex-col gap-6">
      @forelse($cards as $c)
        <div class="flex flex-col rounded-2xl border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-900 hover:shadow-lg hover:-translate-y-1 transition-transform duration-300">

          {{-- CARD VISUAL --}}
          <div class="relative w-full max-w-[340px] h-[200px] rounded-2xl overflow-hidden shadow-xl mx-auto text-white"
               style="background: linear-gradient(145deg, #1e3a8a 0%, #2563eb 50%, #60a5fa 100%);">

            {{-- reflection --}}
            <div class="absolute inset-0 bg-gradient-to-tr from-white/10 to-transparent opacity-25"></div>

            {{-- holographic effect --}}
            <div class="absolute right-6 top-6 w-10 h-10 rounded-full bg-gradient-to-tr from-indigo-400 via-purple-300 to-pink-300 opacity-40 blur-sm"></div>

            {{-- chip --}}
            <div class="absolute left-6 top-6 w-10 h-7 rounded-md bg-gradient-to-b from-yellow-200 to-yellow-600 shadow-md border border-yellow-400"></div>

            {{-- balance --}}
            <div class="absolute left-6 top-[4.5rem] text-3xl font-bold">$ {{ number_format($c->balance ?? 0, 0) }}</div>

            {{-- last 4 digits --}}
            <div class="absolute right-6 top-[4.7rem] text-sm tracking-widest opacity-80">•••• {{ $c->last4 }}</div>

            {{-- holder --}}
            <div class="absolute bottom-10 left-6">
              <div class="text-[10px] uppercase opacity-70">Card Holder</div>
              <div class="font-medium text-sm">{{ auth()->user()->name }}</div>
            </div>

            {{-- expiry --}}
            <div class="absolute bottom-10 right-6 text-right">
              <div class="text-[10px] uppercase opacity-70">Expires</div>
              <div class="font-medium text-sm">{{ str_pad($c->exp_month,2,'0',STR_PAD_LEFT) }}/{{ $c->exp_year }}</div>
            </div>

            {{-- brand --}}
            <div class="absolute right-6 bottom-5 text-right">
              @if(strtolower($c->brand) === 'visa')
                <span class="block text-lg font-bold tracking-tight text-yellow-300">VISA</span>
              @elseif(strtolower($c->brand) === 'mastercard')
                <div class="flex justify-end gap-1">
                  <span class="w-4 h-4 rounded-full bg-red-500 opacity-90"></span>
                  <span class="w-4 h-4 rounded-full bg-orange-400 opacity-90 -ml-2"></span>
                </div>
              @else
                <span class="block text-sm opacity-75">{{ strtoupper($c->brand) }}</span>
              @endif
            </div>

            <div class="absolute inset-0 rounded-2xl ring-1 ring-white/10"></div>
          </div>

          {{-- ACTION BUTTONS --}}
          <div class="mt-4 flex flex-wrap items-center justify-between gap-3">
            @if($c->is_default)
              <span class="inline-flex items-center gap-1 rounded-full bg-indigo-100 px-2 py-1 text-xs font-medium text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-200">
                ✓ Default
              </span>
            @else
              <form method="POST" action="{{ route('user.payments.default',$c) }}">
                @csrf
                <button class="text-xs font-medium text-indigo-700 hover:underline dark:text-indigo-300">
                  Set Default
                </button>
              </form>
            @endif

            <div class="flex gap-3">
              <button onclick="openDetails({{ $c->id }})"
                class="inline-flex items-center gap-1 rounded-lg border border-slate-200 px-3 py-1.5 text-sm text-slate-700 hover:bg-slate-50 dark:border-slate-700 dark:text-slate-100 dark:hover:bg-slate-800 transition">
                🔍 Details
              </button>

              <button
                class="inline-flex items-center gap-1 rounded-lg bg-rose-600 px-3 py-1.5 text-sm text-white hover:bg-rose-700 transition"
                data-remove-id="{{ $c->id }}"
                data-remove-label="{{ ucfirst($c->brand) }} •••• {{ $c->last4 }}"
                data-remove-url="{{ route('user.payments.destroy',$c) }}"
                onclick="openRemove(this)">
                🗑 Remove
              </button>
            </div>
          </div>
        </div>
      @empty
        <div class="rounded-xl border border-dashed border-slate-300 p-6 text-sm text-slate-500 dark:border-slate-700 dark:text-slate-400">
          No cards yet.
        </div>
      @endforelse
    </div>

    <div class="mt-6">{{ $cards->withQueryString()->links() }}</div>
  </section>


  {{-- ===== BANKS SECTION ===== --}}
  <section class="flex flex-col">
    <div class="flex items-end justify-between">
      <div>
        <h2 class="text-xl font-semibold">Bank Accounts</h2>
        <p class="text-sm text-slate-500 dark:text-slate-400">Your linked bank accounts.</p>
      </div>
      <div class="text-xs text-slate-400 dark:text-slate-500">{{ $banks->total() }} total</div>
    </div>

    <div class="mt-6 flex flex-col gap-6">
      @forelse($banks as $b)
        <div class="flex flex-col rounded-2xl border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-900 hover:shadow-lg hover:-translate-y-1 transition-transform duration-300">
          
          {{-- BANK CARD VISUAL --}}
          <div class="relative w-full max-w-[340px] h-[200px] rounded-2xl overflow-hidden shadow-xl mx-auto text-white"
               style="background: linear-gradient(145deg, #065f46 0%, #10b981 50%, #34d399 100%);">
            
            <div class="absolute inset-0 bg-gradient-to-tr from-white/10 to-transparent opacity-25"></div>
            <div class="absolute right-6 top-6 w-10 h-10 rounded-full bg-gradient-to-tr from-emerald-400 via-lime-300 to-teal-200 opacity-40 blur-sm"></div>

            <div class="absolute top-6 left-6 right-6 flex justify-between items-center">
              <div class="text-xl font-semibold truncate">{{ $b->bank_name ?? 'Bank' }}</div>
              <div class="text-sm tracking-widest opacity-80 truncate">
                {{ $b->iban ?? $b->account_number ?? '****2004' }}
              </div>
            </div>

            <div class="absolute left-6 top-[4.5rem] text-3xl font-bold tracking-wide drop-shadow-sm">
              ${{ number_format($b->balance ?? 0, 0) }}
            </div>

            <div class="absolute bottom-10 left-6">
              <div class="text-[10px] uppercase opacity-75 tracking-wider">Account Holder</div>
              <div class="font-medium text-sm">{{ auth()->user()->name }}</div>
            </div>

            <div class="absolute bottom-10 right-6 text-right">
              <div class="text-[10px] uppercase opacity-75 tracking-wider">Type</div>
              <div class="font-medium text-sm">Bank Account</div>
            </div>

            <div class="absolute bottom-3 right-5 text-4xl font-bold opacity-10 select-none">🏦</div>
            <div class="absolute inset-0 rounded-2xl ring-1 ring-white/10"></div>
          </div>

          {{-- ACTIONS --}}
          <div class="mt-4 flex flex-wrap items-center justify-between gap-3">
            @if($b->is_default)
              <span class="inline-flex items-center gap-1 rounded-full bg-indigo-100 px-2 py-1 text-xs font-medium text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-200">
                ✓ Default
              </span>
            @else
              <form method="POST" action="{{ route('user.payments.default',$b) }}">
                @csrf
                <button class="text-xs font-medium text-indigo-700 hover:underline dark:text-indigo-300">
                  Set Default
                </button>
              </form>
            @endif

            <div class="flex gap-3">
              <button onclick="openDetails({{ $b->id }})"
                class="inline-flex items-center gap-1 rounded-lg border border-slate-200 px-3 py-1.5 text-sm text-slate-700 hover:bg-slate-50 dark:border-slate-700 dark:text-slate-100 dark:hover:bg-slate-800 transition">
                🔍 Details
              </button>

              <button
                class="inline-flex items-center gap-1 rounded-lg bg-rose-600 px-3 py-1.5 text-sm text-white hover:bg-rose-700 transition"
                data-remove-id="{{ $b->id }}"
                data-remove-label="{{ $b->bank_name ?? 'Bank' }} {{ $b->iban ?? $b->account_number }}"
                data-remove-url="{{ route('user.payments.destroy',$b) }}"
                onclick="openRemove(this)">
                🗑 Remove
              </button>
            </div>
          </div>
        </div>
      @empty
        <div class="rounded-xl border border-dashed border-slate-300 p-6 text-sm text-slate-500 dark:border-slate-700 dark:text-slate-400">
          No banks yet.
        </div>
      @endforelse
    </div>

    <div class="mt-6">{{ $banks->withQueryString()->links() }}</div>
  </section>
</div>

{{-- ===== DETAILS MODAL ===== --}}
<div id="detailsModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm p-4">
  <div class="w-full max-w-3xl rounded-2xl bg-white p-6 shadow-xl dark:bg-slate-900">
    <div class="mb-4 flex items-center justify-between">
      <h3 class="text-lg font-semibold">Payment Method Details</h3>
      <button class="text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200"
              onclick="closeDetails()">✕</button>
    </div>
    <div id="detailsBody" class="space-y-4 text-sm text-slate-800 dark:text-slate-100"></div>
    <div class="mt-6 text-right">
      <button class="rounded-xl border border-slate-300 px-4 py-2 text-slate-700 hover:bg-slate-50 dark:border-slate-700 dark:text-slate-100 dark:hover:bg-slate-800"
              onclick="closeDetails()">Close</button>
    </div>
  </div>
</div>

{{-- ===== REMOVE MODAL ===== --}}
<div id="removeModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm p-4">
  <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-xl dark:bg-slate-900">
    <h3 class="text-lg font-semibold">Remove payment method</h3>
    <p id="removeText" class="mt-2 text-sm text-slate-600 dark:text-slate-300"></p>
    <div class="mt-5 flex items-center justify-end gap-3">
      <button onclick="closeRemove()"
              class="rounded-xl border border-slate-300 px-4 py-2 text-slate-700 hover:bg-slate-50 dark:border-slate-700 dark:text-slate-100 dark:hover:bg-slate-800">
        Cancel
      </button>
      <form id="removeForm" method="POST">
        @csrf @method('DELETE')
        <button class="rounded-xl bg-rose-600 px-4 py-2 text-white hover:bg-rose-700">Remove</button>
      </form>
    </div>
  </div>
</div>

{{-- ===== JS ===== --}}
<script>
async function openDetails(id){
  const url = @json(route('user.payments.details','__ID__')).replace('__ID__', id);
  const res = await fetch(url, { headers: { 'Accept':'application/json' }});
  const d = await res.json();

  let body = '';
  if (d.type === 'card') {
    body = `
      <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
        <div><div class="text-xs text-slate-500">TYPE</div><div class="mt-1">Card</div></div>
        <div><div class="text-xs text-slate-500">BRAND</div><div class="mt-1">${d.brand ?? '-'}</div></div>
        <div><div class="text-xs text-slate-500">LAST 4</div><div class="mt-1">${d.last4 ?? '-'}</div></div>
        <div><div class="text-xs text-slate-500">EXPIRY</div><div class="mt-1">${String(d.exp_month ?? '').padStart(2,'0')}/${d.exp_year ?? ''}</div></div>
        <div class="md:col-span-2">
          <div class="text-xs text-slate-500">FULL NUMBER</div>
          <div class="mt-2 flex items-center gap-2">
            <code class="rounded-lg bg-slate-100 px-3 py-1.5 dark:bg-slate-800">${d.number ? d.number.replace(/(.{4})/g,'$1 ').trim() : '—'}</code>
            <button id="copyBtn" class="rounded-lg border border-slate-300 px-3 py-1.5 hover:bg-slate-50 dark:border-slate-700 dark:hover:bg-slate-800" onclick="copyText('${d.number ?? ''}')">Copy</button>
          </div>
        </div>
      </div>`;
  } else {
    const full = d.full_iban || d.full_account || '';
    const label = d.full_iban ? 'FULL IBAN' : 'FULL ACCOUNT';
    body = `
      <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
        <div><div class="text-xs text-slate-500">TYPE</div><div class="mt-1">Bank</div></div>
        <div><div class="text-xs text-slate-500">BANK</div><div class="mt-1">${d.bank_name ?? '-'}</div></div>
        <div class="md:col-span-2">
          <div class="text-xs text-slate-500">${label}</div>
          <div class="mt-2 flex items-center gap-2">
            <code class="rounded-lg bg-slate-100 px-3 py-1.5 dark:bg-slate-800">${(full || '—').replace(/(.{4})/g,'$1 ').trim()}</code>
            <button id="copyBtn" class="rounded-lg border border-slate-300 px-3 py-1.5 hover:bg-slate-50 dark:border-slate-700 dark:hover:bg-slate-800" onclick="copyText('${full}')">Copy</button>
          </div>
        </div>
      </div>`;
  }

  document.getElementById('detailsBody').innerHTML = body;
  const m = document.getElementById('detailsModal');
  m.classList.remove('hidden'); m.classList.add('flex');
}

function closeDetails(){
  const m = document.getElementById('detailsModal');
  m.classList.add('hidden'); m.classList.remove('flex');
}

function openRemove(el){
  const label = el.dataset.removeLabel || '';
  const url   = el.dataset.removeUrl;
  document.getElementById('removeText').innerText = `Are you sure you want to remove “${label}”? This cannot be undone.`;
  const form = document.getElementById('removeForm');
  form.setAttribute('action', url);
  const m = document.getElementById('removeModal');
  m.classList.remove('hidden'); m.classList.add('flex');
}

function closeRemove(){
  const m = document.getElementById('removeModal');
  m.classList.add('hidden'); m.classList.remove('flex');
}

function copyText(txt){
  if (!txt) return;
  navigator.clipboard.writeText(txt).then(()=>{
    const btn = document.getElementById('copyBtn');
    if (btn) { btn.innerText='Copied!'; setTimeout(()=>btn.innerText='Copy',1200); }
  });
}
</script>
@endsection
