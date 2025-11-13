@props(['brand'=>'Card','last4'=>'0000','exp_month'=>1,'exp_year'=>2030,'holder'=>null])

<div class="w-80 h-48 rounded-2xl p-4 text-white relative overflow-hidden"
     style="background: radial-gradient(120% 120% at 0% 0%,#3b82f6 0%,#1d4ed8 45%,#0f172a 100%);">
  <div class="flex justify-between">
    <div class="font-semibold text-lg">{{ $brand }}</div>
    <div class="text-sm opacity-90 tracking-widest">•••• •••• •••• {{ $last4 }}</div>
  </div>
  <div class="absolute left-4 bottom-8">
    <div class="text-[10px] uppercase opacity-70">Card Holder</div>
    <div class="font-medium">{{ $holder ?? auth()->user()->name }}</div>
  </div>
  <div class="absolute right-4 bottom-8 text-right">
    <div class="text-[10px] uppercase opacity-70">Expires</div>
    <div class="font-medium">{{ str_pad($exp_month,2,'0',STR_PAD_LEFT) }}/{{ $exp_year }}</div>
  </div>
</div>
