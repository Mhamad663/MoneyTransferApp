@extends('layouts.agent')

@section('content')
<div class="p-6">

    <h1 class="text-3xl font-bold mb-6">Transactions</h1>

    <div class="bg-white shadow rounded-xl p-6">

        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="text-gray-600 border-b">
                    <th class="py-2">Sender</th>
                    <th class="py-2">Recipient</th>
                    <th class="py-2">Amount</th>
                    <th class="py-2">Status</th>
                    <th class="py-2">Date</th>
                </tr>
            </thead>

            <tbody>
            @foreach($transactions as $t)
                <tr class="border-b">
                    <td class="py-2">{{ $t->user->name ?? 'Unknown' }}</td>
                    <td class="py-2">{{ $t->beneficiary->name ?? 'Unknown' }}</td>
                    <td class="py-2">${{ number_format($t->amount_src, 2) }}</td>
                    <td class="py-2">
                        <span class="px-2 py-1 rounded text-white
                            @if($t->status == 'pending') bg-yellow-500
                            @elseif($t->status == 'completed') bg-green-600
                            @else bg-gray-500
                            @endif">
                            {{ ucfirst($t->status) }}
                        </span>
                    </td>
                    <td class="py-2">{{ $t->created_at->format('d/m/Y') }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <div class="mt-4">
            {{ $transactions->links() }}
        </div>

    </div>

</div>
@endsection
