@extends('layouts.agent')

@section('content')
<div class="flex h-screen bg-gray-100">

    <!-- SIDEBAR -->
    <aside class="w-64 bg-white shadow-lg">
        <div class="p-6 text-xl font-bold">AGENT</div>

        <nav class="mt-6">
            <a href="{{ route('agent.dashboard') }}" class="block px-6 py-3 hover:bg-gray-200">🏠 Dashboard</a>
            <a href="{{ route('agent.transactions') }}" class="block px-6 py-3 hover:bg-gray-200">💳 Transactions</a>
            <a href="{{ route('agent.requests') }}" class="block px-6 py-3 hover:bg-gray-200">🔄 Transfer Requests</a>
            <a href="{{ route('agent.payouts') }}" class="block px-6 py-3 hover:bg-gray-200">💳 Payouts</a>
            <a href="{{ route('agent.workinghours') }}" class="block px-6 py-3 hover:bg-gray-200">🕒 Working Hours</a>
            <a href="{{ route('agent.location') }}" class="block px-6 py-3 hover:bg-gray-200">📍 Map</a>
            <a href="{{ route('agent.settings') }}" class="block px-6 py-3 hover:bg-gray-200">⚙️ Settings</a>
        </nav>
    </aside>

    <!-- MAIN CONTENT -->
    <div class="flex-1 p-8 overflow-auto">

        <!-- HEADER -->
        <div class="flex justify-between items-center">
            <h1 class="text-3xl font-bold">Dashboard</h1>
            <div class="flex items-center space-x-3 text-gray-700">
                
               
            </div>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-4 gap-6 mt-6">
            <div class="p-6 bg-white rounded-xl shadow">
                <div class="text-gray-500">Total Revenue</div>
                <div class="text-3xl font-bold mt-2">${{ number_format($totalRevenue, 2) }}</div>
            </div>

            <div class="p-6 bg-white rounded-xl shadow">
                <div class="text-gray-500">Pending Transfers</div>
                <div class="text-3xl font-bold mt-2">{{ $pendingTransfers->count() }}</div>
            </div>

            <div class="p-6 bg-white rounded-xl shadow">
                <div class="text-gray-500">Completed Transfers</div>
                <div class="text-3xl font-bold mt-2">{{ $completedTransfers->count() }}</div>
            </div>

            <div class="p-6 bg-white rounded-xl shadow">
                <div class="text-gray-500">Cash-Out Requests</div>
                <div class="text-3xl font-bold mt-2">{{ $cashOutRequests }}</div>
            </div>
        </div>

        <!-- Incoming + Map -->
        <div class="grid grid-cols-3 gap-6 mt-6">

            <!-- Incoming Requests -->
            <div class="col-span-2 bg-white rounded-xl shadow p-6">
                <h2 class="text-xl font-bold mb-4">Incoming Transfer Requests</h2>

                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="text-gray-500 border-b">
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
                                <td class="py-2">{{ $t->user->name ?? 'Unknown' }}</td>
                                <td class="py-2">{{ $t->beneficiary->name ?? 'Unknown' }}</td>
                                <td class="py-2">${{ $t->amount_src }}</td>
                                <td class="py-2">{{ $t->destination }}</td>
                                <td>
                                    <button class="text-blue-600 hover:underline">Approve</button>
                                    <button class="text-red-600 hover:underline">Decline</button>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="py-3 text-gray-500 text-center">No pending transfers</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Map -->
            <div class="bg-white rounded-xl shadow p-6">
                <h2 class="text-xl font-bold mb-4">Your Location</h2>

                <div id="agentDashboardMap"
                    data-lat="{{ $agent->latitude ?? 33.88894 }}"
                    data-lng="{{ $agent->longitude ?? 35.49442 }}"
                    class="w-full h-64 bg-gray-200 rounded">
                </div>
            </div>

        </div>

        <!-- Recent + Notifications -->
        <div class="grid grid-cols-2 gap-6 mt-6">

            <!-- Recent Transactions -->
            <div class="bg-white rounded-xl shadow p-6">
                <h2 class="text-xl font-bold mb-4">Recent Transactions</h2>

                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="text-gray-500 border-b">
                            <th class="py-2">Date</th>
                            <th class="py-2">Recipient</th>
                            <th class="py-2">Amount</th>
                            <th class="py-2">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentTransactions as $t)
                            <tr class="border-b">
                                <td class="py-2">{{ $t->created_at->format('d/m/Y') }}</td>
                                <td class="py-2">{{ $t->beneficiary->name ?? 'Unknown' }}</td>
                                <td class="py-2">${{ $t->amount_src }}</td>
                                <td class="py-2">{{ ucfirst($t->status) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Notifications -->
            <div class="bg-white rounded-xl shadow p-6">
                <h2 class="text-xl font-bold mb-4">Notifications</h2>

                <ul class="space-y-2">
                    @forelse($notifications as $n)
                        <li>✔️ {{ $n->description }}</li>
                    @empty
                        <li class="text-gray-500">No notifications yet</li>
                    @endforelse
                </ul>
            </div>

        </div>

    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    const mapEl = document.getElementById('agentDashboardMap');
    if (!mapEl) return;

    const lat = parseFloat(mapEl.dataset.lat);
    const lng = parseFloat(mapEl.dataset.lng);

    const map = L.map('agentDashboardMap').setView([lat, lng], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 18,
    }).addTo(map);

    L.marker([lat, lng]).addTo(map)
        .bindPopup("Your Location")
        .openPopup();
});
</script>
@endsection
