@extends('layouts.user')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">

  {{--  SERVICES SECTION  --}}
  <section class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-sm">
    <div class="p-5 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between flex-wrap gap-3">
      <h2 class="text-xl font-semibold text-slate-800 dark:text-slate-100 flex items-center gap-2">
        💸 Transfer Services
      </h2>
      <span class="text-sm text-slate-500 dark:text-slate-400">Available money transfer options</span>
    </div>

    {{-- Table for medium+ screens --}}
    <div class="hidden md:block overflow-x-auto">
      <table class="w-full text-sm">
        <thead class="bg-slate-50 dark:bg-slate-800/50 text-slate-600 dark:text-slate-300 uppercase text-xs tracking-wide">
          <tr>
            <th class="py-3 px-4 text-left">Code</th>
            <th class="py-3 px-4 text-left">Service</th>
            <th class="py-3 px-4 text-left">Method</th>
            <th class="py-3 px-4 text-left">Fee %</th>
            <th class="py-3 px-4 text-left">Fixed Fee</th>
            <th class="py-3 px-4 text-left">Speed</th>
            <th class="py-3 px-4 text-right">Action</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
          @foreach($services as $s)
          <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition">
            <td class="py-3 px-4 font-mono text-blue-700 dark:text-blue-400">{{ $s->code }}</td>
            <td class="py-3 px-4 text-slate-700 dark:text-slate-200">{{ $s->name }}</td>
            <td class="py-3 px-4 capitalize text-slate-600 dark:text-slate-300">{{ $s->method }}</td>
            <td class="py-3 px-4 text-blue-600 dark:text-blue-400 font-medium">{{ number_format($s->fee_percent,2) }}%</td>
            <td class="py-3 px-4 text-slate-700 dark:text-slate-300">${{ number_format($s->fixed_fee,2) }}</td>
            <td class="py-3 px-4 capitalize text-slate-700 dark:text-slate-300">
              {{ str_replace(['_','days','day'],[' ','Days','Day'],$s->speed) }}
            </td>
            <td class="py-3 px-4 text-right">
              <a href="{{ route('user.send', ['service_code' => $s->code]) }}"
                 class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium 
                        rounded-lg bg-blue-600 text-white hover:bg-blue-700
                        dark:bg-blue-500 dark:hover:bg-blue-600 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                  <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.262 1.367l5-1.429 2.23 3.573a1 1 0 001.806-.588v-5.418l5-1.429a1 1 0 00.49-1.644l-7-7a1 1 0 00-.999-.432z"/>
                </svg>
                Use Service
              </a>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    {{-- Mobile view --}}
    <div class="md:hidden p-5 space-y-4">
      @foreach($services as $s)
      <div class="rounded-2xl border dark:border-slate-800 bg-slate-50 dark:bg-slate-900 p-4 shadow-sm">
        <div class="flex justify-between items-center">
          <span class="font-semibold text-slate-800 dark:text-slate-100">{{ $s->name }}</span>
          <span class="font-mono text-xs text-blue-500 dark:text-blue-400">{{ $s->code }}</span>
        </div>
        <div class="mt-2 text-sm text-slate-600 dark:text-slate-300 capitalize">{{ $s->method }}</div>
        <div class="mt-1 text-xs text-slate-500 dark:text-slate-400">
          Speed: {{ str_replace(['_','days','day'],[' ','Days','Day'],$s->speed) }}
        </div>
        <div class="mt-2 flex justify-between text-sm font-medium text-slate-700 dark:text-slate-200">
          <span>{{ number_format($s->fee_percent,2) }}% + ${{ number_format($s->fixed_fee,2) }}</span>
        </div>
        <div class="mt-3 text-right">
          <a href="{{ route('user.send', ['service_code' => $s->code]) }}"
             class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium rounded-lg 
                    bg-blue-600 text-white hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
              <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.262 1.367l5-1.429 2.23 3.573a1 1 0 001.806-.588v-5.418l5-1.429a1 1 0 00.49-1.644l-7-7a1 1 0 00-.999-.432z"/>
            </svg>
            Use
          </a>
        </div>
      </div>
      @endforeach
    </div>
  </section>

  {{--  PROMOTIONS SECTION  --}}
  <section class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-sm">
    <div class="p-5 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between flex-wrap gap-3">
      <h2 class="text-xl font-semibold text-slate-800 dark:text-slate-100 flex items-center gap-2">
        🎁 Active Promotions
      </h2>
      <span class="text-sm text-slate-500 dark:text-slate-400">Exclusive offers and discounts</span>
    </div>

    {{-- Table view --}}
    <div class="hidden md:block overflow-x-auto">
      <table class="w-full text-sm">
        <thead class="bg-slate-50 dark:bg-slate-800/50 text-slate-600 dark:text-slate-300 uppercase text-xs tracking-wide">
          <tr>
            <th class="py-3 px-4 text-left">Code</th>
            <th class="py-3 px-4 text-left">Title</th>
            <th class="py-3 px-4 text-left">Discount</th>
            <th class="py-3 px-4 text-left">Validity</th>
            <th class="py-3 px-4 text-right">Action</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
          @foreach($promotions as $p)
          <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition">
            <td class="py-3 px-4 font-mono text-emerald-700 dark:text-emerald-400">{{ $p->code }}</td>
            <td class="py-3 px-4 text-slate-700 dark:text-slate-200">{{ $p->title }}</td>
            <td class="py-3 px-4 text-emerald-600 dark:text-emerald-400 font-semibold">{{ number_format($p->discount_percent,2) }}%</td>
            <td class="py-3 px-4 text-slate-600 dark:text-slate-400">
              {{ \Carbon\Carbon::parse($p->start_date)->format('M d, Y') }}
              — {{ \Carbon\Carbon::parse($p->end_date)->format('M d, Y') }}
            </td>
            <td class="py-3 px-4 text-right">
              <a href="{{ route('user.send', ['promo_code' => $p->code]) }}"
                 class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium 
                        rounded-lg bg-emerald-600 text-white hover:bg-emerald-700
                        dark:bg-emerald-500 dark:hover:bg-emerald-600 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                  <path d="M2.5 9A1.5 1.5 0 014 7.5h12A1.5 1.5 0 0117.5 9V11H2.5V9zM2 12h7v6H4a2 2 0 01-2-2v-4zm9 0h7v4a2 2 0 01-2 2h-5v-6zM6 5a2 2 0 114 0v1H6V5zm4 0a2 2 0 114 0v1h-4V5z"/>
                </svg>
                Use Promotion
              </a>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    {{-- Mobile view --}}
    <div class="md:hidden p-5 space-y-4">
      @foreach($promotions as $p)
      <div class="rounded-2xl border dark:border-slate-800 bg-slate-50 dark:bg-slate-900 p-4 shadow-sm">
        <div class="flex justify-between items-center">
          <span class="font-semibold text-slate-800 dark:text-slate-100">{{ $p->title }}</span>
          <span class="font-mono text-xs text-emerald-600 dark:text-emerald-400">{{ $p->code }}</span>
        </div>
        <div class="mt-2 text-sm text-slate-700 dark:text-slate-300">
          Discount: <span class="font-semibold text-emerald-600 dark:text-emerald-400">{{ number_format($p->discount_percent,2) }}%</span>
        </div>
        <div class="mt-1 text-xs text-slate-500 dark:text-slate-400">
          Valid: {{ \Carbon\Carbon::parse($p->start_date)->format('M d, Y') }} — {{ \Carbon\Carbon::parse($p->end_date)->format('M d, Y') }}
        </div>
        <div class="mt-3 text-right">
          <a href="{{ route('user.send', ['promo_code' => $p->code]) }}"
             class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium rounded-lg 
                    bg-emerald-600 text-white hover:bg-emerald-700 dark:bg-emerald-500 dark:hover:bg-emerald-600 transition">
            🎁 Use
          </a>
        </div>
      </div>
      @endforeach
    </div>
  </section>
</div>
@endsection
