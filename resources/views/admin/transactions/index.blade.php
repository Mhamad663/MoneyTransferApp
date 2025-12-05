{{-- resources/views/admin/transactions/index.blade.php --}}
<x-admin-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-100 leading-tight">
                    Transactions
                </h2>
                <p class="text-xs text-gray-400 mt-1">
                    Monitor all money transfers and review their status.
                </p>
            </div>

            {{-- Scope/status pills + search --}}
            <div class="flex items-center gap-3 text-xs">
                @php
                    $scopes = [
                        'all'   => 'All',
                        'today' => 'Today',
                        'week'  => 'This week',
                    ];
                @endphp

                @foreach($scopes as $key => $label)
                    <a href="{{ route('admin.transactions.index', [
                            'scope'  => $key,
                            'status' => $status,
                            'search' => $search,
                        ]) }}"
                       class="px-3 py-1 rounded-full border
                              {{ $scope === $key
                                  ? 'bg-indigo-600 text-white border-indigo-500'
                                  : 'bg-slate-900 text-gray-300 border-slate-700 hover:bg-slate-800' }}">
                        {{ $label }}
                    </a>
                @endforeach

                {{-- Status + search form --}}
                <form method="GET"
                      action="{{ route('admin.transactions.index') }}"
                      class="flex items-center gap-2">
                    <input type="hidden" name="scope" value="{{ $scope }}">

                    <select name="status"
                            class="bg-slate-900 border border-slate-700 text-gray-200 text-xs rounded-full px-3 py-1">
                        <option value="">All statuses</option>
                        @foreach(['pending','processing','completed','failed','cancelled'] as $st)
                            <option value="{{ $st }}" {{ $status === $st ? 'selected' : '' }}>
                                {{ ucfirst($st) }}
                            </option>
                        @endforeach
                    </select>

                    <input type="text"
                           name="search"
                           value="{{ $search }}"
                           placeholder="Search reference / user / beneficiary"
                           class="bg-slate-900 border border-slate-700 text-gray-200 text-xs rounded-full px-3 py-1 w-56">

                    <button class="px-3 py-1 rounded-full bg-indigo-600 text-white text-xs">
                        Filter
                    </button>
                </form>
            </div>
            <a href="{{ route('admin.dashboard') }}"
               class="inline-flex items-center gap-2 rounded-full bg-slate-800 hover:bg-slate-700
                      text-xs text-gray-100 px-4 py-2 border border-slate-700 transition">
                Back to dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-950">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="rounded-2xl bg-slate-900/80 border border-slate-800 shadow-lg overflow-hidden">
                <div class="px-4 py-3 border-b border-slate-800 flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-gray-100">
                        Transactions list
                    </h3>
                    <p class="text-xs text-gray-500">
                        Showing {{ $transfers->firstItem() ?? 0 }}–{{ $transfers->lastItem() ?? 0 }}
                        of {{ $transfers->total() }} records
                    </p>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-xs text-left text-gray-200">
                        <thead class="bg-slate-900/80 text-gray-400 border-b border-slate-800">
                            <tr>
                                <th class="px-4 py-2">ID</th>
                                <th class="px-4 py-2">Sender</th>
                                <th class="px-4 py-2">Beneficiary</th>
                                <th class="px-4 py-2">Route</th>
                                <th class="px-4 py-2">Amount</th>
                                <th class="px-4 py-2">Status</th>
                                <th class="px-4 py-2">Created at</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse($transfers as $t)
                            @php
                                $statusVal = strtolower($t->status ?? '');
                                $badgeClasses = match ($statusVal) {
                                    'completed'              => 'bg-emerald-500/10 text-emerald-300 border-emerald-500/40',
                                    'pending', 'processing'  => 'bg-amber-500/10 text-amber-300 border-amber-500/40',
                                    'failed', 'cancelled'    => 'bg-rose-500/10 text-rose-300 border-rose-500/40',
                                    default                  => 'bg-slate-600/20 text-slate-200 border-slate-500/40',
                                };
                            @endphp

                            <tr class="border-b border-slate-800/80 hover:bg-slate-800/60">
                                <td class="px-4 py-2 text-gray-300">
                                    #{{ $t->id }}
                                </td>

                                <td class="px-4 py-2">
                                    <div class="flex flex-col">
                                        <span class="text-gray-100 text-[11px]">
                                            {{ $t->user->name ?? 'N/A' }}
                                        </span>
                                        <span class="text-gray-400 text-[11px]">
                                            {{ $t->user->email ?? '' }}
                                        </span>
                                    </div>
                                </td>

                                <td class="px-4 py-2 text-[11px] text-gray-200">
                                    {{ $t->beneficiary->name ?? '—' }}
                                </td>

                                <td class="px-4 py-2 text-[11px] text-gray-300">
                                    {{ $t->source }} → {{ $t->destination }}
                                </td>

                                <td class="px-4 py-2 text-[11px]">
                                    <span class="font-semibold text-gray-100">
                                        {{ number_format($t->amount_src, 2) }}
                                    </span>
                                    <span class="text-gray-400">
                                        {{ $t->src_currency }}
                                    </span>
                                </td>

                                {{-- Status + inline dropdown to change it --}}
                                <td class="px-4 py-2">
                                    <div class="flex items-center gap-2">
                                        <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[10px] border {{ $badgeClasses }}">
                                            {{ ucfirst($statusVal ?: 'unknown') }}
                                        </span>

                                        <form action="{{ route('admin.transactions.updateStatus', $t->id) }}"
                                              method="POST"
                                              class="inline-block">
                                            @csrf
                                            <select name="status"
                                                    onchange="this.form.submit()"
                                                    class="bg-slate-800 text-gray-200 text-[10px] rounded-xl px-2 py-1 border border-slate-700">
                                                <option value="pending"    {{ $t->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                                <option value="processing" {{ $t->status == 'processing' ? 'selected' : '' }}>Processing</option>
                                                <option value="completed"  {{ $t->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                                <option value="failed"     {{ $t->status == 'failed' ? 'selected' : '' }}>Failed</option>
                                                <option value="cancelled"  {{ $t->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                            </select>
                                        </form>
                                    </div>
                                </td>

                                <td class="px-4 py-2 text-[11px] text-gray-400 whitespace-nowrap">
                                    {{ $t->created_at?->format('d M Y H:i') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-6 text-center text-gray-500 text-sm">
                                    No transactions found for this filter.
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                @if($transfers->hasPages())
                    <div class="px-4 py-3 border-t border-slate-800 bg-slate-950/80">
                        {{ $transfers->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-admin-layout>
