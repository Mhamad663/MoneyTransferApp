@props(['bank_name'=>'Bank','iban'=>null,'account'=>null])

<div class="w-80 h-36 rounded-xl p-4 bg-white border shadow-sm text-slate-800">
  <div class="text-sm text-gray-500">{{ $bank_name }}</div>
  <div class="mt-6 font-semibold text-lg">{{ $iban ?? $account ?? '••••' }}</div>
  <div class="text-xs text-gray-500 mt-2">Bank account (masked)</div>
</div>
