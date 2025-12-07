@extends('layouts.user')

@section('content')
{{-- ===== HEADER (Keep User Info) ===== --}}
{{--  <div class="flex items-center justify-between bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 px-6 py-3 rounded-t-xl mb-6 shadow-sm">
  <h1 class="text-lg font-semibold text-slate-800 dark:text-slate-100">
    Welcome, {{ Auth::user()->name ?? 'User' }} 👋
  </h1>
  <div class="flex items-center gap-3">
    <a href="{{ route('profile.edit') }}" 
       class="hidden sm:inline-flex items-center gap-2 rounded-lg border px-3 py-2 text-sm hover:bg-slate-50 dark:border-slate-700 dark:hover:bg-slate-800 transition">
      <span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-gradient-to-br from-indigo-500 to-blue-600 text-white text-sm font-semibold">
        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
      </span>
      <span class="truncate max-w-[10rem] dark:text-slate-200">{{ Auth::user()->name }}</span>
    </a>
  </div>
</div>--}}

{{-- ===== PAGE TITLE ===== --}}


{{--ERROR ALERT ]--}}
@if($errors->any())
  <div class="mb-4 rounded-lg bg-red-100 dark:bg-red-900/40 text-red-700 dark:text-red-300 p-3 border border-red-200 dark:border-red-800">
    <ul class="list-disc ml-5 space-y-1">
      @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
    </ul>
  </div>
@endif

{{--  EDIT FORM --}}
<form method="POST" action="{{ route('user.beneficiaries.update',$b) }}" 
      class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-6 shadow-md space-y-6 transition">
  @csrf @method('PATCH')

  <h2 class="text-2xl font-semibold mb-4 text-slate-800 dark:text-slate-100">Edit Beneficiary</h2>
  {{-- Basic Info --}}
  <div class="grid sm:grid-cols-2 gap-4">
    <div>
      <label class="form-label">Full Name</label>
      <input name="name" class="form-input" value="{{ old('name',$b->name) }}" required>
    </div>
    <div>
      <label class="form-label">Country</label>
      <input name="country" class="form-input" value="{{ old('country',$b->country) }}">
    </div>
  </div>

  {{-- Payout Method --}}
  <div>
    <label class="form-label">Payout Method</label>
    <select name="payout_method" id="payout_method" class="form-input" required>
      <option value="bank" @selected(old('payout_method',$b->payout_method)==='bank')>Bank</option>
      <option value="wallet" @selected(old('payout_method',$b->payout_method)==='wallet')>Platform Wallet</option>
      <option value="card" @selected(old('payout_method',$b->payout_method)==='card')>Card</option>
    </select>
  </div>

  {{-- BANK --}}
  <div id="bank_fields" class="grid sm:grid-cols-2 gap-4">
    <div><label class="form-label">Bank Name</label><input name="bank_name" class="form-input" value="{{ old('bank_name',$b->bank_name) }}"></div>
    <div><label class="form-label">Account Number</label><input name="account_number" class="form-input" value="{{ old('account_number',$b->account_number) }}"></div>
    <div><label class="form-label">IBAN</label><input name="iban" class="form-input" value="{{ old('iban',$b->iban) }}"></div>
    <div><label class="form-label">SWIFT</label><input name="swift" class="form-input" value="{{ old('swift',$b->swift) }}"></div>
  </div>

  {{-- WALLET --}}
  <div id="wallet_fields" class="grid sm:grid-cols-2 gap-4 hidden">
    <div><label class="form-label">Platform Wallet ID</label>
      <input name="platform_wallet_id" class="form-input" placeholder="WAL123456" value="{{ old('platform_wallet_id',$b->platform_wallet_id) }}">
    </div>
  </div>

  {{-- CARD --}}
  <div id="card_fields" class="grid sm:grid-cols-2 gap-4 hidden">
    <div><label class="form-label">Receiver Name</label>
      <input name="card_receiver_name" class="form-input" placeholder="John Doe" value="{{ old('card_receiver_name',$b->card_receiver_name) }}">
    </div>
    <div><label class="form-label">Card Number</label>
      <input name="card_number" class="form-input" placeholder="4242 4242 4242 4242" value="{{ old('card_number',$b->card_number) }}">
    </div>
  </div>

  {{-- Contact --}}
  <div class="grid sm:grid-cols-2 gap-4">
    <div><label class="form-label">Email</label><input name="email" type="email" class="form-input" value="{{ old('email',$b->email) }}"></div>
    <div><label class="form-label">Phone</label><input name="phone" class="form-input" value="{{ old('phone',$b->phone) }}"></div>
  </div>

  {{-- Address --}}
  <div><label class="form-label">Address</label><input name="address" class="form-input" value="{{ old('address',$b->address) }}"></div>

  {{-- Notes --}}
  <div><label class="form-label">Notes</label><textarea name="notes" rows="3" class="form-input">{{ old('notes',$b->notes) }}</textarea></div>

  {{-- Favorite --}}
  <label class="inline-flex items-center gap-2 text-slate-700 dark:text-slate-300">
    <input type="checkbox" name="is_favorite" value="1" class="rounded text-blue-600 focus:ring-blue-500"
           {{ old('is_favorite',$b->is_favorite) ? 'checked' : '' }}>
    <span>Mark as favorite</span>
  </label>

  {{-- Buttons --}}
  <div class="pt-3 flex flex-wrap gap-3">
    <button class="btn-primary">💾 Update</button>
    <a href="{{ route('user.beneficiaries.index') }}" 
       class="inline-flex items-center px-4 py-2 rounded-lg border border-slate-300 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 transition text-slate-700 dark:text-slate-200">
      Cancel
    </a>
  </div>
</form>

{{--  STYLES  --}}
<style>
  .form-label {display:block;font-size:.9rem;color:#475569;margin-bottom:.25rem}
  .dark .form-label {color:#cbd5e1}
  .form-input {
    width:100%;border:1px solid #e5e7eb;border-radius:.6rem;padding:.6rem .8rem;
    background-color:#fff;color:#1e293b
  }
  .dark .form-input {
    background-color:#1e293b;border-color:#334155;color:#e2e8f0
  }
  .form-input:focus {
    outline:none;border-color:#2563eb;box-shadow:0 0 0 3px rgba(37,99,235,.2)
  }
  .btn-primary {
    background:linear-gradient(to right,#2563eb,#1e40af);
    color:#fff;border-radius:.6rem;padding:.6rem 1rem;font-weight:500;
    transition:background .2s
  }
  .btn-primary:hover{background:linear-gradient(to right,#1d4ed8,#1e3a8a)}
  @media(max-width:640px){form{padding:1.25rem}.form-label{font-size:.85rem}}
</style>

{{--  JS  --}}
<script>
  const method = document.getElementById('payout_method');
  const bank = document.getElementById('bank_fields');
  const wallet = document.getElementById('wallet_fields');
  const card = document.getElementById('card_fields');
  function toggleFields() {
    bank.classList.add('hidden'); wallet.classList.add('hidden'); card.classList.add('hidden');
    if (method.value === 'bank') bank.classList.remove('hidden');
    if (method.value === 'wallet') wallet.classList.remove('hidden');
    if (method.value === 'card') card.classList.remove('hidden');
  }
  method.addEventListener('change', toggleFields);
  toggleFields();
</script>
@endsection
