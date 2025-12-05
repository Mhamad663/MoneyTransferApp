@extends('layouts.agent')

@section('content')
<div class="p-6">

    <h1 class="text-3xl font-bold mb-6">Transactions</h1>

    @if(session('success'))
        <div class="mb-4 rounded-lg bg-green-100 text-green-800 px-4 py-2 text-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white shadow rounded-xl p-6 overflow-x-auto">

        <table class="w-full text-left border-collapse min-w-[700px]">
            <thead>
                <tr class="text-gray-600 border-b">
                    <th class="py-2">Sender</th>
                    <th class="py-2">Recipient</th>
                    <th class="py-2">Amount</th>
                    <th class="py-2">Status</th>
                    <th class="py-2">Date</th>
                    <th class="py-2 text-right">Action</th>
                </tr>
            </thead>

            <tbody>
            @foreach($transactions as $t)
                <tr class="border-b text-sm">
                    <td class="py-2">{{ $t->user->name ?? 'Unknown' }}</td>
                    <td class="py-2">{{ $t->beneficiary->name ?? 'Unknown' }}</td>
                    <td class="py-2">${{ number_format($t->amount_src, 2) }}</td>

                    {{-- Colored badge --}}
                    <td class="py-2">
                        <span class="px-2 py-1 rounded text-white text-xs
                            @if($t->status == 'pending') bg-yellow-500
                            @elseif($t->status == 'completed') bg-green-600
                            @elseif($t->status == 'failed') bg-red-600
                            @else bg-gray-500
                            @endif">
                            {{ ucfirst($t->status) }}
                        </span>
                    </td>

                    <td class="py-2">{{ $t->created_at->format('d/m/Y') }}</td>

                    {{-- Small inline form to update status --}}
                    <td class="py-2 text-right">
    <form method="POST" action="{{ route('agent.transactions.status', $t) }}"
          class="inline-flex items-center gap-2">
        @csrf
        <select name="status"
                class="border rounded px-2 py-1 text-sm">
            <option value="pending"   @selected($t->status === 'pending')>Pending</option>
            <option value="completed" @selected($t->status === 'completed')>Completed</option>
            <option value="cancelled" @selected($t->status === 'cancelled')>Cancelled</option>
        </select>
        <button type="submit"
                class="px-2 py-1 text-xs bg-blue-600 text-white rounded">
            Update
        </button>
    </form>
</td>

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
