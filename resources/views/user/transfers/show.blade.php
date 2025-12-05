@extends('layouts.user')

@section('content')
<h1 class="text-2xl font-semibold mb-4">Transfer Details</h1>

<div class="bg-white border rounded-xl p-6">
  <div class="grid sm:grid-cols-2 gap-4 text-sm">
    <div><span class="text-gray-500">Reference:</span> <span class="font-medium">{{ $transfer->reference }}</span></div>
    <div><span class="text-gray-500">Status:</span> <span class="font-medium capitalize">{{ $transfer->status }}</span></div>
    <div><span class="text-gray-500">Method:</span> <span class="font-medium capitalize">{{ $transfer->method }}</span></div>
    <div><span class="text-gray-500">Amount:</span> <span class="font-medium">${{ number_format($transfer->amount_src,2) }} {{ $transfer->src_currency }}</span></div>
    <div><span class="text-gray-500">From:</span> <span class="font-medium">{{ $transfer->source }}</span></div>
    <div><span class="text-gray-500">To:</span> <span class="font-medium">{{ $transfer->destination }}</span></div>
    <div><span class="text-gray-500">Fee:</span> <span class="font-medium">${{ number_format($transfer->fee,2) }}</span></div>
    <div><span class="text-gray-500">FX Rate:</span> <span class="font-medium">{{ $transfer->fx_rate }}</span></div>
    <div><span class="text-gray-500">Created:</span> <span class="font-medium">{{ $transfer->created_at->format('M d, Y H:i') }}</span></div>
  </div>

  <hr class="my-6">

  <h2 class="font-semibold mb-3">Events</h2>
  <ol class="relative border-s ps-4 space-y-3">
    @foreach($events as $e)
      <li>
        <div class="text-sm"><span class="font-medium">{{ ucfirst($e->event) }}</span> — <span class="text-gray-500">{{ $e->created_at->format('M d, Y H:i') }}</span></div>
        @if($e->meta)<div class="text-xs text-gray-600">{{ $e->meta }}</div>@endif
      </li>
    @endforeach
  </ol>
</div>
@endsection
