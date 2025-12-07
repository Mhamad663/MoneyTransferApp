@extends('layouts.user')

@section('content')

@if(session('success'))
  <div class="mb-4 rounded bg-green-100 text-green-700 p-3 dark:bg-green-900/40 dark:text-green-400">
    {{ session('success') }}
  </div>
@endif
@if(session('error'))
  <div class="mb-4 rounded-xl bg-red-100 border border-red-400 text-red-700 px-4 py-3">
    {{ session('error') }}
  </div>
@endif

@if($errors->any())
  <div class="mb-4 rounded bg-red-100 text-red-700 p-3 dark:bg-red-900/40 dark:text-red-400">
    <ul class="list-disc ml-4">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
  </div>
@endif

{{-- Tabs + Code badge row --}}
<div class="mb-4 flex items-center justify-between gap-2">
  <div class="flex gap-2">
    <button data-tab="wallet" class="tab-btn active">👛 Wallet to Wallet</button>
    <button data-tab="bank" class="tab-btn">🏦 Bank Transfer</button>
    <button data-tab="card" class="tab-btn">💳 Card</button>
  </div>

  {{-- Selected code (service or promo) --}}
  @if(!empty($selectedServiceCode) || !empty($selectedPromoCode))
    <div class="flex items-center gap-2">
      <span class="text-xs text-slate-500 dark:text-slate-400">Code:</span>
      <span class="px-2 py-1 rounded-lg text-sm font-mono shadow-sm
                   {{ $selectedServiceCode ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300' : 'bg-green-50 text-green-700 dark:bg-green-900/40 dark:text-green-300' }}">
        {{ $selectedServiceCode ?? $selectedPromoCode }}
      </span>
    </div>
  @endif
</div>

{{-- Invalid service warning --}}
@if(!empty($selectedServiceCode) && empty($selectedServiceId))
  <div class="mb-4 rounded bg-amber-50 text-amber-800 p-3 dark:bg-amber-900/40 dark:text-amber-300">
    The selected service code is invalid or inactive. Please pick a service again from
    <a href="{{ route('user.fees-promotions') }}" class="underline">Fees & Promotions</a>.
  </div>
@endif

{{-- WALLET --}}
<div id="tab-wallet" class="tab-pane">
  <form method="POST" action="{{ route('user.send.wallet') }}" class="bg-white dark:bg-slate-900 border dark:border-slate-800 rounded-xl p-6 space-y-4 shadow-sm">
    @csrf
    <input type="hidden" name="service_code" value="{{ $selectedServiceCode }}">
    <input type="hidden" name="service_id" value="{{ $selectedServiceId }}">
    <input type="hidden" name="promo_code" value="{{ $selectedPromoCode }}">

    <label class="form-label">Saved Beneficiary (optional)</label>
    <select id="wallet_beneficiary" name="beneficiary_id" class="form-input">
      <option value="">— None —</option>
      @foreach($beneficiaries as $b)
        <option value="{{ $b->id }}" data-wallet="{{ $b->platform_wallet_id }}" data-name="{{ $b->name }}">
          {{ $b->name }} {{ $b->platform_wallet_id ? '('.$b->platform_wallet_id.')' : '' }}
        </option>
      @endforeach
    </select>

    <div class="grid sm:grid-cols-3 gap-4 mt-3">
      <div>
        <label class="form-label">Receiver Wallet ID</label>
        <input id="wallet_receiver" name="receiver_wallet_id" class="form-input" placeholder="WAL123456789">
      </div>
      <div>
        <label class="form-label">Amount (USD)</label>
        <input name="amount" type="number" step="0.01" min="0" class="form-input" required>
      </div>
      <div>
        <label class="form-label">Your Wallet</label>
        <input class="form-input bg-gray-50 dark:bg-slate-800" value="{{ $wallet->wallet_id }} | ${{ number_format($wallet->balance,2) }}" disabled>
      </div>
      <div class="sm:col-span-3">
        <label class="form-label">Note (optional)</label>
        <input name="note" class="form-input" placeholder="For rent">
      </div>
    </div>

    <div class="flex items-center justify-between pt-1">
      <small class="text-slate-500 dark:text-slate-400">Fee will be applied based on the selected service.</small>
      <button class="btn-primary">Transfer</button>
    </div>
  </form>
</div>

{{-- BANK --}}
<div id="tab-bank" class="tab-pane hidden">
  <form method="POST" action="{{ route('user.send.bank') }}" class="bg-white dark:bg-slate-900 border dark:border-slate-800 rounded-xl p-6 space-y-4 shadow-sm">
    @csrf
    <input type="hidden" name="service_code" value="{{ $selectedServiceCode }}">
    <input type="hidden" name="service_id" value="{{ $selectedServiceId }}">
    <input type="hidden" name="promo_code" value="{{ $selectedPromoCode }}">

    <h2 class="text-lg font-semibold mb-2">Bank Transfer</h2>
    <p class="text-sm text-gray-500 dark:text-slate-400 mb-3">
      Select a saved beneficiary to auto-fill details, or enter them manually.
    </p>

    <label class="form-label">Saved Beneficiary (optional)</label>
    <select id="bank_beneficiary" name="beneficiary_id" class="form-input">
      <option value="">— None —</option>
      @foreach($beneficiaries as $b)
        <option 
          value="{{ $b->id }}" 
          data-name="{{ $b->name }}" 
          data-iban="{{ $b->iban }}" 
          data-swift="{{ $b->swift }}">
          {{ $b->name }} {{ $b->iban ? '(' . $b->iban . ')' : '' }}
        </option>
      @endforeach
    </select>

    <div class="grid sm:grid-cols-2 gap-4 mt-3">
      <div>
        <label class="form-label">Receiver Full Name</label>
        <input id="bank_name" name="receiver_name" class="form-input" placeholder="John Doe">
      </div>
      <div>
        <label class="form-label">IBAN</label>
        <input id="bank_iban" name="iban" class="form-input" placeholder="GB29NWBK60161331926819">
      </div>
      <div>
        <label class="form-label">SWIFT (optional)</label>
        <input id="bank_swift" name="swift" class="form-input" placeholder="NWBKGB2L">
      </div>
      <div>
        <label class="form-label">Amount</label>
        <input id="bank_amount" name="amount" type="number" step="0.01" min="0" class="form-input" required>
      </div>
      <div>
        <label class="form-label">Source Currency</label>
        <input id="src_currency" name="src_currency" class="form-input" value="USD" readonly>
      </div>
      <div>
        <label class="form-label">Destination Currency</label>
        <select id="dst_currency" name="dst_currency" class="form-input">
          <option value="USD" selected>USD</option>
          <option value="EUR">EUR</option>
        </select>
      </div>
    </div>

    {{-- Conversion result --}}
    <div id="conversionInfo" class="mt-2 text-sm text-blue-600 dark:text-blue-400 hidden"></div>

    <div class="flex items-center justify-between pt-1">
      <small class="text-slate-500 dark:text-slate-400">
        Fee will be applied based on the selected service.
      </small>
      <button class="btn-primary mt-3">Create Bank Transfer (Pending)</button>
    </div>
  </form>
</div>

{{-- CARD  --}}
<div id="tab-card" class="tab-pane hidden">
  <form method="POST" action="{{ route('user.send.card') }}"
        class="bg-white dark:bg-slate-900 border dark:border-slate-800 rounded-xl p-6 space-y-4 shadow-sm"
        id="cardTransferForm">
    @csrf

    {{-- Hidden fields (service/promo codes) --}}
    <input type="hidden" name="service_code" value="{{ $selectedServiceCode }}">
    <input type="hidden" name="service_id"   value="{{ $selectedServiceId }}">
    <input type="hidden" name="promo_code"   value="{{ $selectedPromoCode }}">

    <h2 class="text-lg font-semibold mb-2 text-slate-800 dark:text-slate-100">Card Transfer</h2>
    <p class="text-sm text-gray-500 dark:text-slate-400 mb-3">
      Enter receiver name, card number, and amount. The system will automatically calculate conversion if needed.
    </p>

    {{-- Beneficiary (optional) --}}
    <label class="form-label">Saved Beneficiary (optional)</label>
    <select id="card_beneficiary" name="beneficiary_id" class="form-input">
      <option value="">— None —</option>
      @foreach($beneficiaries as $b)
        <option value="{{ $b->id }}" data-name="{{ $b->name }}">{{ $b->name }}</option>
      @endforeach
    </select>

    <div class="grid sm:grid-cols-2 gap-4 mt-3">
      <div>
        <label class="form-label">Receiver Full Name</label>
        <input id="card_name" name="receiver_name" class="form-input" placeholder="Sarah Lee" required>
      </div>

      <div>
        <label class="form-label">Card Number</label>
        <input id="card_number" name="card_number" class="form-input" placeholder="4242 4242 4242 4242"
               maxlength="19" inputmode="numeric" required>
      </div>

      <div>
        <label class="form-label">Amount (USD)</label>
        <input id="amountInput" name="amount" type="number" step="0.01" min="1" class="form-input" required>
      </div>

      <div>
        <label class="form-label">Currency</label>
        <select id="currencySelect" name="currency" class="form-input">
          <option value="USD" selected>USD</option>
          <option value="EUR">EUR</option>
        </select>
      </div>
    </div>

    {{-- Result display --}}
    <div id="rateResult" class="text-sm text-slate-600 dark:text-slate-400 mt-2"></div>

    <div class="flex items-center justify-between pt-1">
      <small class="text-slate-500 dark:text-slate-400">Fee will be applied based on the selected service.</small>
      <button class="btn-primary mt-3">Create Card Transfer (Pending)</button>
    </div>
  </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const cardBeneficiary = document.getElementById('card_beneficiary');
  const cardName = document.getElementById('card_name');
  const currencySelect = document.getElementById('currencySelect');
  const amountInput = document.getElementById('amountInput');
  const rateResult = document.getElementById('rateResult');
  const cardNumber = document.getElementById('card_number');

  // auto-fill name from saved beneficiary
  cardBeneficiary?.addEventListener('change', e => {
    const opt = e.target.selectedOptions[0];
    cardName.value = opt.dataset.name || '';
  });

  // format card number as groups of 4 digits
  cardNumber.addEventListener('input', e => {
    let val = e.target.value.replace(/\D/g, '');
    val = val.substring(0, 16); // 16 digits max
    e.target.value = val.replace(/(\d{4})(?=\d)/g, '$1 ').trim();
  });

  // fixed exchange rate for example
  const USD_TO_EUR = 0.91;
  const EUR_TO_USD = 1.10;

  function updateConversion() {
    const amount = parseFloat(amountInput.value) || 0;
    const currency = currencySelect.value;
    if (amount <= 0) {
      rateResult.textContent = '';
      return;
    }

    if (currency === 'EUR') {
      const converted = (amount * USD_TO_EUR).toFixed(2);
      rateResult.innerHTML = `💱 ${amount} USD ≈ ${converted} EUR (Rate: ${USD_TO_EUR})`;
    } else {
      const converted = (amount * EUR_TO_USD).toFixed(2);
      rateResult.innerHTML = `💱 ${amount} EUR ≈ ${converted} USD (Rate: ${EUR_TO_USD})`;
    }
  }

  amountInput.addEventListener('input', updateConversion);
  currencySelect.addEventListener('change', updateConversion);
});
</script>

<script>
document.addEventListener('DOMContentLoaded', async function () {
  const amountInput = document.getElementById('amountInput');
  const currencySelect = document.getElementById('currencySelect');
  const rateResult = document.getElementById('rateResult');
  let rates = null;

  // Load live exchange rates from backend
  async function loadRates() {
    try {
      const res = await fetch('{{ route("user.getRates") }}');
      const data = await res.json();
      if (data.success) {
        rates = data.rates; 
      } else {
        rateResult.textContent = ' Unable to fetch live rates.';
      }
    } catch (err) {
      console.error(err);
      rateResult.textContent = ' Network error loading rates.';
    }
  }

  await loadRates();

  // Perform conversion based on live rates
  function updateConversion() {
    if (!rates) return;
    const amount = parseFloat(amountInput.value) || 0;
    const currency = currencySelect.value;
    if (amount <= 0) {
      rateResult.textContent = '';
      return;
    }

    if (currency === 'EUR') {
      // user entered in USD → show EUR equivalent
      const eurUsd = rates.EUR_USD || 1.15;
      const converted = (amount * eurUsd).toFixed(2);
      rateResult.innerHTML = `💱 ${amount} EUR ≈ ${converted} USD (Live Rate: ${eurUsd})`;
    } else if (currency === 'USD') {
      const eurUsd = rates.EUR_USD || 1.15;
      const converted = (amount / eurUsd).toFixed(2);
      rateResult.innerHTML = `💱 ${amount} USD ≈ ${converted} EUR (Live Rate: ${eurUsd})`;
    } else if (currency === 'LBP') {
      const usdLbp = rates.USD_LBP || 89500;
      const converted = (amount * usdLbp).toFixed(2);
      rateResult.innerHTML = `💱 ${amount} USD ≈ ${converted} LBP (Live Rate: ${usdLbp.toLocaleString()})`;
    }
  }

  amountInput.addEventListener('input', updateConversion);
  currencySelect.addEventListener('change', updateConversion);
});
</script>



{{--  Styles  --}}
<style>
  .form-label {
    display:block;
    font-size:.85rem;
    color:#475569;
    margin-bottom:.25rem;
  }
  .dark .form-label { color:#cbd5e1; }

  .form-input {
    width:100%;
    border:1px solid #e5e7eb;
    border-radius:.6rem;
    padding:.6rem .8rem;
    background-color:#fff;
    color:#0f172a;
  }
  .form-input:focus {
    outline:none;
    border-color:#2563eb;
    box-shadow:0 0 0 1px #2563eb;
  }
  .dark .form-input {
    background-color:#0f172a;
    border-color:#334155;
    color:#e2e8f0;
  }
  .dark .form-input::placeholder { color:#64748b; }

  .btn-primary {
    background:#2563eb;
    color:#fff;
    border-radius:.6rem;
    padding:.6rem 1rem;
    transition:.2s;
  }
  .btn-primary:hover { background:#1e4fd6; }

  .tab-btn {
    background:#eef2ff;
    border-radius:.6rem;
    padding:.45rem .8rem;
    font-weight:500;
    color:#1e293b;
    transition:.2s;
  }
  .tab-btn:hover { background:#e0e7ff; }
  .tab-btn.active { background:#2563eb; color:#fff; }
  .dark .tab-btn { background:#1e293b; color:#cbd5e1; }
  .dark .tab-btn:hover { background:#334155; }
  .dark .tab-btn.active { background:#2563eb; color:#fff; }

  .tab-pane.hidden { display:none; }
</style>

{{--  Script  --}}
<script>
  // Tabs switcher
  document.querySelectorAll('.tab-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
      const id = btn.dataset.tab;
      document.querySelectorAll('.tab-pane').forEach(p => p.classList.add('hidden'));
      document.getElementById('tab-' + id).classList.remove('hidden');
    });
  });

  // Auto-fill BANK
  document.addEventListener('DOMContentLoaded', () => {
  const dstCurrency = document.getElementById('dst_currency');
  const amountInput = document.getElementById('bank_amount');
  const infoBox = document.getElementById('conversionInfo');

  
  const rates = { USD: 1, EUR: 0.92 }; 

  function updateConversion() {
    const amount = parseFloat(amountInput.value);
    const target = dstCurrency.value;

    if (!amount || isNaN(amount)) {
      infoBox.classList.add('hidden');
      return;
    }

    const converted = (amount * rates[target]).toFixed(2);

    if (target === 'USD') {
      infoBox.textContent = `💵 No conversion applied — amount remains $${converted} USD.`;
    } else {
      infoBox.textContent = `💶 Converted amount: €${converted} (Rate: 1 USD = ${rates.EUR} EUR)`;
    }

    infoBox.classList.remove('hidden');
  }

  // Update whenever amount or currency changes
  dstCurrency.addEventListener('change', updateConversion);
  amountInput.addEventListener('input', updateConversion);
});

  // Auto-fill CARD
  document.getElementById('card_beneficiary')?.addEventListener('change', e => {
    const opt = e.target.selectedOptions[0];
    document.getElementById('card_name').value = opt.dataset.name || '';
  });

  // Auto-fill WALLET receiver ID
  document.getElementById('wallet_beneficiary')?.addEventListener('change', e => {
    const opt = e.target.selectedOptions[0];
    const wallet = opt?.dataset?.wallet || '';
    const input = document.getElementById('wallet_receiver');
    input.value = wallet;
    input.readOnly = !!wallet;
    if (!wallet) input.readOnly = false;
  });
</script>

@endsection
