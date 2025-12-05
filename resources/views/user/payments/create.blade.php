@extends('layouts.user')

@section('content')
<div class="min-h-screen px-4 py-10 bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 transition-colors duration-300">
  <div class="mx-auto w-full max-w-4xl">

    {{-- Header + PCI note --}}
    <div class="mb-6 flex items-start justify-between gap-4">
      {{--  --}}<div>
        <h1 class="text-2xl sm:text-3xl font-semibold">Payment Methods</h1>
        <p class="text-sm text-slate-500 dark:text-slate-400">Add a card or bank account for funding.</p>
      </div>
      <a href="{{ route('stripe.add.card') }}"
         class="inline-flex items-center gap-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 text-sm shadow-sm">
        <span>Use Stripe (Recommended)</span>
      </a>
    </div>

    {{-- Tabs --}}
    <div class="mb-6 flex justify-center">
      <div class="inline-flex rounded-2xl bg-slate-200 dark:bg-slate-800 p-1 shadow-inner">
        <button data-tab="card"
                class="tab-btn active inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-medium
                       text-slate-700 dark:text-slate-200 transition-colors">
          <span>💳</span><span>Card</span>
        </button>
        <button data-tab="bank"
                class="tab-btn inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-medium
                       text-slate-700 dark:text-slate-200 transition-colors">
          <span>🏦</span><span>Bank</span>
        </button>
      </div>
    </div>

    {{-- CARD TAB --}}
    <div id="tab-card" class="tab-pane">
      <div class="rounded-2xl border border-slate-200/70 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 sm:p-8 shadow-sm">
        <h2 class="text-lg sm:text-xl font-semibold mb-1 flex items-center gap-2">
          <span class="text-blue-600 dark:text-blue-400">•</span> Add Credit or Debit Card
        </h2>
        <p class="text-xs text-slate-500 dark:text-slate-400 mb-6">
          Stored securely in your database. For production, use Stripe Elements to avoid handling PAN data directly.
        </p>

        <form method="POST" action="{{ route('user.payments.card.store') }}" class="space-y-6">
          @csrf

          <div class="grid sm:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium mb-1">Cardholder Name</label>
              <input name="holder_name" required placeholder="John Doe"
                     class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-950
                            text-slate-900 dark:text-slate-100 px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500" />
            </div>

            <div>
              <label class="block text-sm font-medium mb-1">Brand</label>
              <select name="brand" required
                      class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-950
                             text-slate-900 dark:text-slate-100 px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option>Visa</option>
                <option>MasterCard</option>
                <option>Amex</option>
                <option>Discover</option>
              </select>
            </div>

            <div class="sm:col-span-2">
              <label class="block text-sm font-medium mb-1">Card Number</label>
              <div class="relative">
                <input id="card_number" name="number" inputmode="numeric" autocomplete="cc-number" required
                       placeholder="4242 4242 4242 4242"
                       class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-950
                              text-slate-900 dark:text-slate-100 px-3 py-2.5 pr-14 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                <div class="absolute inset-y-0 right-2 flex items-center">
                  <span id="brandHint"
                        class="text-xs text-slate-500 dark:text-slate-400 px-2 py-1 rounded-lg">
                    —
                  </span>
                </div>
              </div>
              <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">We’ll store the full number; keep this environment private.</p>
            </div>

            <div>
              <label class="block text-sm font-medium mb-1">Expiry Month</label>
              <input name="exp_month" type="number" min="1" max="12" required placeholder="MM"
                     class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-950
                            text-slate-900 dark:text-slate-100 px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500" />
            </div>

            <div>
              <label class="block text-sm font-medium mb-1">Expiry Year</label>
              <input name="exp_year" type="number" min="2024" required placeholder="YYYY"
                     class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-950
                            text-slate-900 dark:text-slate-100 px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500" />
            </div>

            <div>
              <label class="block text-sm font-medium mb-1">CVV</label>
              <div class="relative">
                <input id="cvv" name="cvv" type="password" required placeholder="***" maxlength="4" inputmode="numeric"
                       class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-950
                              text-slate-900 dark:text-slate-100 px-3 py-2.5 pr-12 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                <button type="button" id="cvvToggle"
                        class="absolute inset-y-0 right-2 my-auto h-8 px-3 text-xs rounded-lg
                               bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-200">
                  Show
                </button>
              </div>
            </div>
          </div>

          <div class="flex items-center justify-end gap-3">
            <a href="{{ route('user.payments.index') }}"
               class="rounded-xl border border-slate-300 dark:border-slate-700 px-4 py-2 text-sm
                      text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800">
              Cancel
            </a>
            <button class="rounded-xl bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 text-sm shadow-sm">
              💾 Save Card
            </button>
          </div>
        </form>
      </div>
    </div>

    {{-- BANK TAB --}}
    <div id="tab-bank" class="tab-pane hidden">
      <div class="rounded-2xl border border-slate-200/70 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 sm:p-8 shadow-sm">
        <h2 class="text-lg sm:text-xl font-semibold mb-1 flex items-center gap-2">
          <span class="text-emerald-600 dark:text-emerald-400">•</span> Add Bank Account
        </h2>
        <p class="text-xs text-slate-500 dark:text-slate-400 mb-6">You can enter IBAN or a local account number.</p>

        <form method="POST" action="{{ route('user.payments.bank.store') }}" class="space-y-6">
          @csrf
          <div class="grid sm:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium mb-1">Bank Name</label>
              <input name="bank_name" required placeholder="Byblos Bank"
                     class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-950
                            text-slate-900 dark:text-slate-100 px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500" />
            </div>

            <div>
              <label class="block text-sm font-medium mb-1">IBAN (optional)</label>
              <input name="iban" placeholder="GB29NWBK60161331926819"
                     class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-950
                            text-slate-900 dark:text-slate-100 px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500" />
            </div>

            <div class="sm:col-span-2">
              <label class="block text-sm font-medium mb-1">Account Number (optional)</label>
              <input name="account_number" placeholder="1234567890"
                     class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-950
                            text-slate-900 dark:text-slate-100 px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500" />
            </div>
          </div>

          <div class="flex items-center justify-end gap-3">
            <a href="{{ route('user.payments.index') }}"
               class="rounded-xl border border-slate-300 dark:border-slate-700 px-4 py-2 text-sm
                      text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800">
              Cancel
            </a>
            <button class="rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2 text-sm shadow-sm">
              💾 Save Bank
            </button>
          </div>
        </form>
      </div>
    </div>

  </div>
</div>

{{-- Scripts --}}
<script>
  // Tabs
  document.querySelectorAll('.tab-btn').forEach((btn) => {
    btn.addEventListener('click', () => {
      document.querySelectorAll('.tab-btn').forEach(b => {
        b.classList.remove('active');
        b.classList.remove('bg-blue-600','text-white');
      });
      btn.classList.add('active','bg-blue-600','text-white');

      document.querySelectorAll('.tab-pane').forEach(p => p.classList.add('hidden'));
      document.getElementById('tab-' + btn.dataset.tab).classList.remove('hidden');
    });
  });

  // Card number formatting + simple brand hint
  const cn = document.getElementById('card_number');
  const brandHint = document.getElementById('brandHint');
  const detect = (digits) => {
    if (/^4/.test(digits)) return 'Visa';
    if (/^5[1-5]/.test(digits) || /^2(2[2-9]|[3-6]\d|7[0-1]|720)/.test(digits)) return 'Mastercard';
    if (/^3[47]/.test(digits)) return 'Amex';
    if (/^6(011|5)/.test(digits)) return 'Discover';
    return '—';
  };
  cn?.addEventListener('input', function () {
    let v = this.value.replace(/\D/g, '');
    // Limit length: Amex 15, else 16
    const max = /^3[47]/.test(v) ? 15 : 16;
    v = v.slice(0, max);
    // spacing
    const spaced = /^3[47]/.test(v)
      ? v.replace(/(\d{4})(\d{6})(\d{0,5})/, (_, a, b, c) => [a,b,c].filter(Boolean).join(' '))
      : v.replace(/(\d{4})(?=\d)/g, '$1 ');
    this.value = spaced;
    brandHint.textContent = detect(v);
  });

  // CVV show/hide
  const cvv = document.getElementById('cvv');
  const cvvToggle = document.getElementById('cvvToggle');
  cvvToggle?.addEventListener('click', () => {
    const showing = cvv.type === 'text';
    cvv.type = showing ? 'password' : 'text';
    cvvToggle.textContent = showing ? 'Show' : 'Hide';
  });
</script>

{{-- Minimal tab active styling fallback (no @apply) --}}
<style>
  .tab-btn.active{ box-shadow: 0 1px 0 0 rgba(0,0,0,.03) inset; }
</style>
@endsection

