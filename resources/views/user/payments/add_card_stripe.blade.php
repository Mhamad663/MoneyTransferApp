@extends('layouts.user')

@section('content')
<div class="max-w-2xl mx-auto">
  {{-- Header --}}
  <div class="mb-6 flex items-center justify-between">
    <div>
      <h1 class="text-2xl font-semibold text-slate-900 dark:text-slate-100">Add Card (Stripe)</h1>
      <p class="text-sm text-slate-500 dark:text-slate-400">
        Your card is saved securely with Stripe. We never store the full PAN on our servers.
      </p>
    </div>
  </div>

  {{-- Card --}}
  <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 shadow-sm">
    {{-- Optional: Cardholder name --}}
    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1" for="cardholder-name">
      Cardholder Name
    </label>
    <input id="cardholder-name" type="text" autocomplete="name"
           class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800
                  text-slate-900 dark:text-slate-100 px-3 py-2 focus:outline-none focus:ring-2
                  focus:ring-blue-500 mb-4" placeholder="e.g. Ali Abou Hamdan">

    {{-- Stripe Element --}}
    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
      Card Details
    </label>
    <div id="card-element"
         class="rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-3">
    </div>

    {{-- Error line --}}
    <p id="err" class="min-h-[1.25rem] mt-2 text-sm text-rose-600"></p>

    {{-- Actions --}}
    <div class="mt-4 flex items-center gap-3">
      <button id="save-btn"
              class="inline-flex items-center gap-2 rounded-xl bg-blue-600 hover:bg-blue-700
                     text-white px-4 py-2 disabled:opacity-60 disabled:cursor-not-allowed">
        <svg id="btn-spinner" class="hidden h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor"
                d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
        </svg>
        <span>Save Card</span>
      </button>

      <a href="{{ route('user.payments.index') }}"
         class="rounded-xl border border-slate-300 dark:border-slate-700 px-4 py-2
                text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800">
        Cancel
      </a>
    </div>
  </div>

  {{-- Small footnote --}}
  <p class="mt-4 text-xs text-slate-500 dark:text-slate-400">
    By adding a card you authorize us to store it with Stripe for future payments. You can remove it anytime.
  </p>
</div>

{{-- Page styles for buttons (kept minimal; Tailwind does most of it) --}}
<style>
  /* no custom colors here – Tailwind/dark classes handle theme */
</style>

<script src="https://js.stripe.com/v3/"></script>
<script>
  // --- Stripe setup ---
  const stripe = Stripe(@json($pk));

  // Pick a theme matching your app (uses <html class="dark">)
  const isDark = document.documentElement.classList.contains('dark')
                 || window.matchMedia('(prefers-color-scheme: dark)').matches;

  const elements = stripe.elements({
    appearance: {
      theme: isDark ? 'night' : 'stripe', // Stripe's built-in themes
      variables: {
        colorPrimary: '#2563eb',
        colorBackground: isDark ? '#0f172a' : '#ffffff',
        colorText: isDark ? '#e5e7eb' : '#0f172a',
        colorTextSecondary: isDark ? '#94a3b8' : '#64748b',
        colorDanger: '#ef4444',
        borderRadius: '12px'
      },
      rules: {
        '.Input': { padding: '12px' },
      }
    }
  });

  const card = elements.create('card', { hidePostalCode: true });
  card.mount('#card-element');

  // Live error from Element
  card.on('change', ({error}) => {
    setError(error ? error.message : '');
  });

  // Helpers
  const $btn = document.getElementById('save-btn');
  const $spin = document.getElementById('btn-spinner');
  function setLoading(on) {
    $btn.disabled = on;
    $spin.classList.toggle('hidden', !on);
  }
  function setError(msg) {
    document.getElementById('err').textContent = msg || '';
  }

  async function getSetupIntent() {
    const res = await fetch(@json(route('stripe.setup-intent')), {
      method: 'POST',
      headers: {'X-CSRF-TOKEN': @json(csrf_token()), 'X-Requested-With': 'XMLHttpRequest'}
    });
    if (!res.ok) throw new Error('Failed to create setup intent');
    return res.json();
  }

  async function storePaymentMethod(pm) {
    const res = await fetch(@json(route('stripe.store-pm')), {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': @json(csrf_token()),
        'X-Requested-With': 'XMLHttpRequest'
      },
      body: JSON.stringify({ payment_method: pm })
    });
    if (!res.ok) {
      const j = await res.json().catch(() => ({}));
      throw new Error(j.message || 'Failed to save card');
    }
  }

  // Submit
  document.getElementById('save-btn').addEventListener('click', async () => {
    setError('');
    setLoading(true);

    try {
      const name = document.getElementById('cardholder-name').value || undefined;
      const { client_secret } = await getSetupIntent();

      const result = await stripe.confirmCardSetup(client_secret, {
        payment_method: { card, billing_details: { name } }
      });

      if (result.error) {
        throw new Error(result.error.message);
      }

      await storePaymentMethod(result.setupIntent.payment_method);

      // redirect on success
      window.location = @json(route('user.payments.index'));
    } catch (e) {
      setError(e.message || 'Something went wrong. Please try again.');
    } finally {
      setLoading(false);
    }
  });

  // If user toggles site theme while on page, re-render the element theme
  const obs = new MutationObserver(() => {
    const nowDark = document.documentElement.classList.contains('dark');
    if (nowDark !== isDark) {
      elements.update({ appearance: { theme: nowDark ? 'night' : 'stripe' } });
    }
  });
  obs.observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });
</script>
@endsection
