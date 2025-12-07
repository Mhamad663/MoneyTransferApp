
  <x-admin-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-100 leading-tight">
                Admin Dashboard
            </h2>
            <p class="text-xs text-gray-400">
                Manage platform, agents, compliance, and reports
            </p>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-950">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            {{-- Top cards --}}
            <div class="grid gap-4 md:grid-cols-3 lg:grid-cols-4">
    {{-- Overall operations / overview --}}
<a href="{{ route('admin.overview.index') }}"
   class="rounded-2xl bg-gradient-to-br from-sky-500/80 to-blue-700 p-4 shadow-lg
          hover:scale-[1.01] transition block">
    <p class="text-xs uppercase tracking-wide text-sky-100/80">
        Platform overview
    </p>
    <p class="mt-2 text-3xl font-semibold text-white">
        {{ $stats['total_users'] ?? 0 }}
    </p>
    <p class="mt-1 text-xs text-sky-100/80">
        Total users (senders / receivers / agents)
    </p>
</a>


                {{-- Pending agents --}}
                <a href="{{ route('admin.agents.index') }}"
                   class="rounded-2xl bg-gradient-to-br from-amber-500/80 to-orange-700 p-4 shadow-lg hover:scale-[1.01] transition">
                    <p class="text-xs uppercase tracking-wide text-amber-100/80">
                        Pending agents
                    </p>
                    <p class="mt-2 text-3xl font-semibold text-white">
                        {{ $stats['pending_agents'] ?? 0 }}
                    </p>
                    <p class="mt-1 text-xs text-amber-100/80">
                        Awaiting approval
                    </p>
                </a>

                {{-- Today transfers --}}
                <a href="{{ route('admin.transactions.index', ['scope' => 'today']) }}"
                   class="rounded-2xl bg-gradient-to-br from-emerald-500/80 to-teal-700 p-4 shadow-lg hover:scale-[1.01] transition">
                    <p class="text-xs uppercase tracking-wide text-emerald-100/80">
                        Today transfers
                    </p>
                    <p class="mt-2 text-3xl font-semibold text-white">
                        {{ $stats['today_transfers'] ?? 0 }}
                    </p>
                    <p class="mt-1 text-xs text-emerald-100/80">
                        Transactions processed today
                    </p>
                </a>

                {{-- Fraud alerts --}}
                <a href="{{ route('admin.fraud.index') }}"
                   class="rounded-2xl bg-gradient-to-br from-rose-500/80 to-red-700 p-4 shadow-lg hover:scale-[1.01] transition">
                    <p class="text-xs uppercase tracking-wide text-rose-100/80">
                        Fraud alerts
                    </p>
                    <p class="mt-2 text-3xl font-semibold text-white">
                        {{ $stats['fraud_alerts'] ?? 0 }}
                    </p>
                    <p class="mt-1 text-xs text-rose-100/80">
                        Flags to review
                    </p>
                </a>
            </div>

            <div class="grid gap-6 lg:grid-cols-3">
                {{-- Left: chart / recent transactions --}}
                <div class="lg:col-span-2 space-y-6">

{{-- Transfers chart --}}
<div class="rounded-2xl bg-slate-900/70 border border-slate-800 p-4">
    <div class="flex items-center justify-between mb-3">
        <h3 class="text-sm font-semibold text-gray-100">
            Transfers volume (last 7 days)
        </h3>
        <span class="text-xs text-gray-500">Daily count</span>
    </div>

    <div class="h-56">
        <canvas id="transfersChart" class="w-full h-full"></canvas>
    </div>
</div>


                    {{-- Recent transactions --}}
                    <div class="rounded-2xl bg-slate-900/70 border border-slate-800 p-4">
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="text-sm font-semibold text-gray-100">
                                Recent transactions
                            </h3>
                            <a href="{{ route('admin.transactions.index') }}"
                               class="text-xs text-indigo-400 hover:text-indigo-300">
                                View all
                            </a>
                        </div>

                        <table class="min-w-full text-xs text-left text-gray-300">
                            <thead class="border-b border-slate-800 text-gray-400">
                                <tr>
                                    <th class="py-2 pr-4">ID</th>
                                    <th class="py-2 pr-4">Sender</th>
                                    <th class="py-2 pr-4">Amount</th>
                                    <th class="py-2 pr-4">Status</th>
                                    <th class="py-2 pr-4">Created</th>
                                </tr>
                            </thead>
                            <tbody>
    @forelse(($recentTransfers ?? []) as $t)
        <tr class="border-b border-slate-900">
            <td class="py-2 pr-4">#{{ $t->id }}</td>

            <td class="py-2 pr-4">
                {{ optional($t->user)->name ?? 'N/A' }}
                <div class="text-[11px] text-gray-500">
                    {{ optional($t->user)->email ?? '' }}
                </div>
            </td>

            <td class="py-2 pr-4">
                {{ number_format($t->amount_src, 2) }}
                {{ $t->src_currency }}
            </td>

            <td class="py-2 pr-4">
                @php $status = $t->status; @endphp
                <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[10px]
                    @if($status === 'completed')
                        bg-emerald-500/10 text-emerald-300 border border-emerald-500/40
                    @elseif($status === 'pending')
                        bg-amber-500/10 text-amber-300 border border-amber-500/40
                    @else
                        bg-rose-500/10 text-rose-300 border border-rose-500/40
                    @endif">
                    {{ ucfirst($status) }}
                </span>
            </td>

            <td class="py-2 pr-4 text-gray-400">
                {{ optional($t->created_at)->format('d M H:i') }}
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="5" class="py-4 text-center text-gray-500">
                No transfers yet.
            </td>
        </tr>
    @endforelse
</tbody>

                        </table>
                    </div>
                </div>

                {{-- Right: quick actions --}}
                <div class="space-y-6">
                    {{-- Exchange rates & fees --}}
                    <div class="rounded-2xl bg-slate-900/70 border border-slate-800 p-4">
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="text-sm font-semibold text-gray-100">
                                Currency rates & fees
                            </h3>
                            <a href="{{ route('admin.services.index') }}"
                               class="text-xs text-indigo-400 hover:text-indigo-300">
                                Manage
                            </a>
                        </div>
                        <p class="text-xs text-gray-400 mb-3">
                            Quickly review the current exchange rates and fee tiers.
                        </p>
                        <ul class="space-y-1 text-xs text-gray-300">
                            <li>• {{ $stats['currencies'] ?? 0 }} currencies configured</li>
                            <li>• {{ $stats['fee_tiers'] ?? 0 }} fee structures</li>
                        </ul>
                    </div>

                    {{-- Support & chatbot --}}
                    <div class="rounded-2xl bg-slate-900/70 border border-slate-800 p-4">
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="text-sm font-semibold text-gray-100">
                                Customer support
                            </h3>
                            <a href="{{ route('admin.support.index') }}"
                               class="text-xs text-indigo-400 hover:text-indigo-300">
                                Open panel
                            </a>
                        </div>
                        <p class="text-xs text-gray-400 mb-2">
                            View open tickets, chat with users, and supervise the support chatbot.
                        </p>
                        <p class="text-xs text-gray-400">
                            Open tickets: <span class="font-semibold text-indigo-300">
                                {{ $stats['open_tickets'] ?? 0 }}
                            </span>
                        </p>
                    </div>

                    {{-- Reports --}}
                    <div class="rounded-2xl bg-slate-900/70 border border-slate-800 p-4">
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="text-sm font-semibold text-gray-100">
                                Reports & analytics
                            </h3>
                            <a href="{{ route('admin.reports.index') }}"
                               class="text-xs text-indigo-400 hover:text-indigo-300">
                                Generate
                            </a>
                        </div>
                        <p class="text-xs text-gray-400">
                            Export platform usage, transaction volumes, agent performance,
                            and user feedback in PDF/CSV for audits and decision making.
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </div>
    {{-- Simple Chart.js line chart for last 7 days --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    (function () {
        const ctx = document.getElementById('transfersChart');
        if (!ctx) return;

        const labels = @json($chart['labels'] ?? []);
        const data   = @json($chart['data'] ?? []);

        new Chart(ctx, {
            type: 'line', // or 'bar'
            data: {
                labels: labels,
                datasets: [{
                    label: 'Transfers',
                    data: data,
                    borderWidth: 2,
                    tension: 0.3,
                    fill: true,
                    // colors tuned for dark background
                    borderColor: 'rgba(56, 189, 248, 1)',       // sky-400
                    backgroundColor: 'rgba(56, 189, 248, 0.15)' // sky-400/15
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        ticks: { color: '#9CA3AF', font: { size: 11 } },
                        grid:  { color: 'rgba(55, 65, 81, 0.4)' }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: { color: '#9CA3AF', font: { size: 11 }, precision: 0 },
                        grid:  { color: 'rgba(55, 65, 81, 0.4)' }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: '#020617',
                        borderColor: '#1D4ED8',
                        borderWidth: 1,
                        titleColor: '#E5E7EB',
                        bodyColor: '#E5E7EB'
                    }
                }
            }
        });
    })();
</script>

</x-admin-layout>
