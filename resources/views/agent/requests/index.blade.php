@extends('layouts.agent')

@section('content')
<div class="p-6">

    <h1 class="text-3xl font-bold mb-6">Transfer Requests</h1>

    @if(session('success'))
        <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white shadow rounded-xl p-6">

        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b text-gray-600">
                    <th class="py-2">Sender</th>
                    <th class="py-2">Recipient</th>
                    <th class="py-2">Amount</th>
                    <th class="py-2">Destination</th>
                    <th class="py-2">Action</th>
                </tr>
            </thead>

            <tbody>
            @forelse($pendingTransfers as $t)
                <tr class="border-b">
                    <td class="py-2">{{ $t->user->name }}</td>
                    <td class="py-2">{{ $t->beneficiary->name }}</td>
                    <td class="py-2">${{ number_format($t->amount_src, 2) }}</td>
                    <td class="py-2">{{ $t->destination }}</td>
                    <td class="py-2">

                        <form action="{{ route('agent.requests.approve', $t->id) }}" method="POST" class="inline">
                            @csrf
                            <button class="text-blue-600 hover:underline">Approve</button>
                        </form>

                        <form action="{{ route('agent.requests.decline', $t->id) }}" method="POST" class="inline ml-3">
                            @csrf
                            <button class="text-red-600 hover:underline">Decline</button>
                        </form>

                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center text-gray-500 py-3">No pending transfers</td>
                </tr>
            @endforelse
            </tbody>
        </table>

    </div>

</div>
@endsection
