@extends('layouts.user')

@section('content')

@if($errors->any())
  <div class="mb-4 rounded-lg bg-red-100 dark:bg-red-900/40 text-red-700 dark:text-red-300 p-3 border border-red-200 dark:border-red-800">
    <ul class="list-disc ml-5 space-y-1">
      @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
    </ul>

  </div>
@endif


<form id="beneficiaryForm" method="POST" action="{{ route('user.beneficiaries.store') }}" 
      class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-6 shadow-md space-y-6 transition">
  @csrf

  <h2 class="text-lg font-semibold text-slate-800 dark:text-slate-100">Add New Beneficiary</h2>

  {{-- Basic Info --}}
  <div class="grid sm:grid-cols-2 gap-4">
    <div>
      <label class="form-label">Full Name <span class="text-red-500">*</span></label>
      <input name="name" id="name" class="form-input" required>
      <p class="error-msg hidden">Full name is required.</p>
    </div>
    <div>
      <label class="form-label">Country <span class="text-red-500">*</span></label>
      <input name="country" id="country" class="form-input" placeholder="Lebanon" required>
      <p class="error-msg hidden">Country is required.</p>
    </div>
  </div>

  {{-- Payout Method --}}
  <div>
    <label class="form-label">Payout Method <span class="text-red-500">*</span></label>
    <select name="payout_method" id="payout_method" class="form-input" required>
      <option value="">Select Method</option>
      <option value="bank">Bank</option>
      <option value="wallet">Platform Wallet</option>
      <option value="card">Card</option>
    </select>
    <p class="error-msg hidden">Please select a payout method.</p>
  </div>

  {{-- BANK FIELDS --}}
  <div id="bank_fields" class="grid sm:grid-cols-2 gap-4 hidden">
    <div>
      <label class="form-label">Bank Name <span class="text-red-500">*</span></label>
      <input name="bank_name" id="bank_name" class="form-input">
      <p class="error-msg hidden">Bank name is required.</p>
    </div>
    <div>
      <label class="form-label">Account Number <span class="text-red-500">*</span></label>
      <input name="account_number" id="account_number" class="form-input" pattern="\d{6,20}" title="6–20 digits">
      <p class="error-msg hidden">Enter a valid account number (6–20 digits).</p>
    </div>
    <div>
      <label class="form-label">IBAN</label>
      <input name="iban" id="iban" class="form-input" maxlength="34">
      <p class="error-msg hidden">Enter a valid IBAN.</p>
    </div>
    <div>
      <label class="form-label">SWIFT</label>
      <input name="swift" id="swift" class="form-input" maxlength="11">
    </div>
  </div>

  {{-- PLATFORM WALLET --}}
  <div id="platform_fields" class="hidden">
    <label class="form-label">Platform Wallet ID <span class="text-red-500">*</span></label>
    <input name="platform_wallet_id" id="platform_wallet_id" class="form-input" placeholder="WAL123456789">
    <p class="error-msg hidden">Enter a valid Platform Wallet ID (WAL followed by digits).</p>
  </div>

  {{-- CARD FIELDS --}}
  <div id="card_fields" class="grid sm:grid-cols-2 gap-4 hidden">
    <div>
      <label class="form-label">Receiver Name <span class="text-red-500">*</span></label>
      <input name="card_receiver_name" id="card_receiver_name" class="form-input" placeholder="John Doe">
      <p class="error-msg hidden">Receiver name is required.</p>
    </div>
    <div>
      <label class="form-label">Card Number <span class="text-red-500">*</span></label>
      <input name="card_number" id="card_number" class="form-input" placeholder="4242 4242 4242 4242" maxlength="19">
      <p class="error-msg hidden">Enter a valid card number.</p>
    </div>
  </div>

  {{-- Contact --}}
  <div class="grid sm:grid-cols-2 gap-4">
    <div>
      <label class="form-label">Email</label>
      <input name="email" id="email" type="email" class="form-input" placeholder="user@example.com">
      <p class="error-msg hidden">Enter a valid email.</p>
    </div>
    <div>
      <label class="form-label">Phone</label>
      <input name="phone" id="phone" class="form-input" placeholder="+961..." pattern="\+?\d{6,15}" title="6–15 digits with optional +">
      <p class="error-msg hidden">Enter a valid phone number.</p>
    </div>
  </div>

  {{-- Address & Notes --}}
  <div>
    <label class="form-label">Address</label>
    <input name="address" id="address" class="form-input">
  </div>
  <div>
    <label class="form-label">Notes</label>
    <textarea name="notes" id="notes" rows="3" class="form-input"></textarea>
  </div>

  {{-- Favorite Toggle --}}
  <label class="inline-flex items-center gap-2 text-slate-700 dark:text-slate-300">
    <input type="checkbox" name="is_favorite" value="1" class="rounded text-blue-600 focus:ring-blue-500">
    <span>Mark as favorite</span>
  </label>

  {{-- Buttons --}}
  <div class="pt-3 flex flex-wrap gap-3">
    <button type="submit" class="btn-primary">💾 Save</button>
    <a href="{{ route('user.beneficiaries.index') }}" 
       class="inline-flex items-center px-4 py-2 rounded-lg border border-slate-300 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 transition text-slate-700 dark:text-slate-200">
      Cancel
    </a>
  </div>
</form>

{{-- Styles --}}
<style>
  .form-label{display:block;font-size:.9rem;color:#475569;margin-bottom:.25rem}
  .dark .form-label{color:#cbd5e1}
  .form-input{width:100%;border:1px solid #e5e7eb;border-radius:.6rem;padding:.6rem .8rem;background:#fff;color:#1e293b}
  .dark .form-input{background:#1e293b;border-color:#334155;color:#e2e8f0}
  .form-input:focus{outline:none;border-color:#2563eb;box-shadow:0 0 0 3px rgba(37,99,235,0.2)}
  .btn-primary{background:linear-gradient(to right,#2563eb,#1e40af);color:#fff;border-radius:.6rem;padding:.6rem 1rem;font-weight:500}
  .btn-primary:hover{background:linear-gradient(to right,#1d4ed8,#1e3a8a)}
  .error-msg{color:#dc2626;font-size:.8rem;margin-top:.25rem}
  .hidden{display:none}
</style>

{{-- JS --}}
<script>
const method = document.getElementById('payout_method');
const bank = document.getElementById('bank_fields');
const platform = document.getElementById('platform_fields');
const card = document.getElementById('card_fields');
const form = document.getElementById('beneficiaryForm');

function toggleFields() {
  bank.classList.add('hidden');
  platform.classList.add('hidden');
  card.classList.add('hidden');
  if (method.value === 'bank') bank.classList.remove('hidden');
  if (method.value === 'wallet') platform.classList.remove('hidden');
  if (method.value === 'card') card.classList.remove('hidden');
}
method.addEventListener('change', toggleFields);
toggleFields();

// Card formatting
const cardInput = document.getElementById('card_number');
cardInput?.addEventListener('input', e => {
  e.target.value = e.target.value.replace(/\D/g, '').replace(/(.{4})/g, '$1 ').trim();
});

// Luhn check for card
function validCard(num){
  const n = num.replace(/\s/g,'');
  if(n.length < 13 || n.length > 19) return false;
  let sum=0,alt=false;
  for(let i=n.length-1;i>=0;i--){
    let d=parseInt(n.charAt(i));
    if(alt){d*=2;if(d>9)d-=9;}sum+=d;alt=!alt;
  }
  return sum%10===0;
}

function showError(input, msg){
  const p = input.parentElement.querySelector('.error-msg');
  if(p){p.textContent=msg;p.classList.remove('hidden');}
}

form.addEventListener('submit', e=>{
  let valid=true;
  document.querySelectorAll('.error-msg').forEach(p=>p.classList.add('hidden'));

  const name=document.getElementById('name');
  if(!name.value.trim()){showError(name,'Full name is required.'); valid=false;}

  const country=document.getElementById('country');
  if(!country.value.trim()){showError(country,'Country is required.'); valid=false;}

  if(!method.value){showError(method,'Please select a payout method.'); valid=false;}

  // Bank validation
  if(method.value==='bank'){
    const bankName=document.getElementById('bank_name');
    const acc=document.getElementById('account_number');
    const iban=document.getElementById('iban');
    if(!bankName.value.trim()){showError(bankName,'Bank name is required.'); valid=false;}
    if(!/^\d{6,20}$/.test(acc.value)){showError(acc,'Enter a valid account number (6–20 digits).'); valid=false;}
    if(iban.value && !/^[A-Z0-9]{15,34}$/.test(iban.value)){showError(iban,'Enter a valid IBAN.'); valid=false;}
  }

  // Wallet validation
  if(method.value==='wallet'){
    const id=document.getElementById('platform_wallet_id');
    if(!/^WAL\d{6,}$/.test(id.value)){showError(id,'Enter a valid Platform Wallet ID (WAL followed by digits).'); valid=false;}
  }

  // Card validation
  if(method.value==='card'){
    const name=document.getElementById('card_receiver_name');
    const num=document.getElementById('card_number');
    if(!name.value.trim()){showError(name,'Receiver name is required.'); valid=false;}
    if(!validCard(num.value)){showError(num,'Enter a valid card number.'); valid=false;}
  }

  const email=document.getElementById('email');
  if(email.value && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value)){
    showError(email,'Enter a valid email.'); valid=false;
  }

  const phone=document.getElementById('phone');
  if(phone.value && !/^\+?\d{6,15}$/.test(phone.value)){
    showError(phone,'Enter a valid phone number.'); valid=false;
  }

  if(!valid) e.preventDefault();
});
</script>
@endsection
