<x-admin-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-100 leading-tight">
                    Reports & analytics
                </h2>
                <p class="text-xs text-gray-400 mt-1">
                    Analyse transfers volume, methods and currencies for a given period.
                </p>
            </div>
            <a href="{{ route('admin.dashboard') }}"
               class="inline-flex items-center gap-2 rounded-full bg-slate-800 hover:bg-slate-700
                      text-xs text-gray-100 px-4 py-2 border border-slate-700 transition">
                Back to dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-950">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Filters --}}
            <form method="GET"
                  class="rounded-2xl bg-slate-900/80 border border-slate-800 p-4 flex flex-wrap gap-4 items-end">
                <div>
                    <label class="block text-xs font-medium text-gray-400 mb-1">From date</label>
                    <input type="date" name="from_date" value="{{ $from }}"
                           class="bg-slate-950 border border-slate-700 text-gray-100 text-sm rounded-xl px-3 py-2">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-400 mb-1">To date</label>
                    <input type="date" name="to_date" value="{{ $to }}"
                           class="bg-slate-950 border border-slate-700 text-gray-100 text-sm rounded-xl px-3 py-2">
                </div>
                <button class="px-4 py-2 rounded-xl bg-indigo-600 text-white text-sm font-medium shadow">
                    Update
                </button>
            </form>

            {{-- KPIs --}}
            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                <div class="rounded-2xl bg-slate-900/80 border border-slate-800 p-4">
                    <p class="text-xs text-gray-400">Total transfers</p>
                    <p class="mt-2 text-2xl font-semibold text-white">
                        {{ number_format($kpis['total_transfers']) }}
                    </p>
                </div>
                <div class="rounded-2xl bg-slate-900/80 border border-slate-800 p-4">
                    <p class="text-xs text-gray-400">Total volume (source)</p>
                    <p class="mt-2 text-2xl font-semibold text-white">
                        {{ number_format($kpis['total_volume'], 2) }}
                    </p>
                </div>
                <div class="rounded-2xl bg-slate-900/80 border border-slate-800 p-4">
                    <p class="text-xs text-gray-400">Average amount</p>
                    <p class="mt-2 text-2xl font-semibold text-white">
                        {{ number_format($kpis['avg_amount'], 2) }}
                    </p>
                </div>
                <div class="rounded-2xl bg-slate-900/80 border border-slate-800 p-4">
                    <p class="text-xs text-gray-400">Success ratio</p>
                    @php
                        $total = max(1, $kpis['total_transfers']);
                        $successRate = round(($kpis['completed'] / $total) * 100);
                    @endphp
                    <p class="mt-2 text-2xl font-semibold text-white">
                        {{ $successRate }}%
                    </p>
                    <p class="text-[11px] text-gray-500 mt-1">
                        Completed: {{ $kpis['completed'] }} · Failed: {{ $kpis['failed'] }}
                    </p>
                </div>
            </div>

            <div class="grid gap-6 lg:grid-cols-2">
                {{-- By currency --}}
                <div class="rounded-2xl bg-slate-900/80 border border-slate-800 p-4">
                    <h3 class="text-sm font-semibold text-gray-100 mb-3">Volume by currency</h3>
                    <table class="w-full text-xs text-left text-gray-300">
                        <thead class="border-b border-slate-800 text-gray-400">
                        <tr>
                            <th class="py-2 pr-4">Currency</th>
                            <th class="py-2 pr-4">Transfers</th>
                            <th class="py-2 pr-4">Total volume</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($byCurrency as $row)
                            <tr class="border-b border-slate-900">
                                <td class="py-2 pr-4">{{ strtoupper($row->src_currency) }}</td>
                                <td class="py-2 pr-4">{{ number_format($row->cnt) }}</td>
                                <td class="py-2 pr-4">{{ number_format($row->volume, 2) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="py-4 text-center text-gray-500">No data.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- By method --}}
                <div class="rounded-2xl bg-slate-900/80 border border-slate-800 p-4">
                    <h3 class="text-sm font-semibold text-gray-100 mb-3">Volume by method</h3>
                    <table class="w-full text-xs text-left text-gray-300">
                        <thead class="border-b border-slate-800 text-gray-400">
                        <tr>
                            <th class="py-2 pr-4">Method</th>
                            <th class="py-2 pr-4">Transfers</th>
                            <th class="py-2 pr-4">Total volume</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($byMethod as $row)
                            <tr class="border-b border-slate-900">
                                <td class="py-2 pr-4 capitalize">{{ $row->method ?? '—' }}</td>
                                <td class="py-2 pr-4">{{ number_format($row->cnt) }}</td>
                                <td class="py-2 pr-4">{{ number_format($row->volume, 2) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="py-4 text-center text-gray-500">No data.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-admin-layout>
