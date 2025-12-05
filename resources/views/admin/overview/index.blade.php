{{-- resources/views/admin/overview/index.blade.php --}}
<x-admin-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-100 leading-tight">
                    Platform overview
                </h2>
                <p class="text-xs text-gray-400 mt-1">
                    High level view of users, agents and transfers on the platform.
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
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            {{-- Top stats cards --}}
            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                {{-- Total users --}}
                <div class="rounded-2xl bg-gradient-to-br from-sky-500/80 to-blue-700 p-4 shadow-lg">
                    <p class="text-[11px] uppercase tracking-wide text-sky-100/80">
                        Total users
                    </p>
                    <p class="mt-2 text-3xl font-semibold text-white">
                        {{ $stats['total_users'] ?? 0 }}
                    </p>
                    <p class="mt-1 text-xs text-sky-100/80">
                        Senders, receivers and agents
                    </p>
                </div>

                {{-- Agents --}}
                <div class="rounded-2xl bg-gradient-to-br from-indigo-500/80 to-purple-700 p-4 shadow-lg">
                    <p class="text-[11px] uppercase tracking-wide text-indigo-100/80">
                        Agents
                    </p>
                    <p class="mt-2 text-3xl font-semibold text-white">
                        {{ $stats['total_agents'] ?? 0 }}
                    </p>
                    <p class="mt-1 text-xs text-indigo-100/80">
                        {{ $stats['active_agents'] ?? 0 }} active /
                        {{ $stats['pending_agents'] ?? 0 }} pending
                    </p>
                </div>

                {{-- Transfers --}}
                <div class="rounded-2xl bg-gradient-to-br from-emerald-500/80 to-teal-700 p-4 shadow-lg">
                    <p class="text-[11px] uppercase tracking-wide text-emerald-100/80">
                        Transfers
                    </p>
                    <p class="mt-2 text-3xl font-semibold text-white">
                        {{ $stats['total_transfers'] ?? 0 }}
                    </p>
                    <p class="mt-1 text-xs text-emerald-100/80">
                        Total transfers created on the platform
                    </p>
                </div>

                {{-- Placeholder for future metric --}}
                <div class="rounded-2xl bg-gradient-to-br from-rose-500/80 to-orange-600 p-4 shadow-lg">
                    <p class="text-[11px] uppercase tracking-wide text-rose-100/80">
                        Daily activity
                    </p>
                    <p class="mt-2 text-3xl font-semibold text-white">
                        {{ $stats['today_transfers'] ?? 0 }}
                    </p>
                    <p class="mt-1 text-xs text-rose-100/80">
                        Transfers created today
                    </p>
                </div>
            </div>

            {{-- Second row: user breakdown and quick numbers --}}
            <div class="grid gap-6 lg:grid-cols-3">
                {{-- User breakdown --}}
                <div class="lg:col-span-2 rounded-2xl bg-slate-900/80 border border-slate-800 p-5">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-sm font-semibold text-gray-100">
                            User base breakdown
                        </h3>
                        <span class="text-[11px] text-gray-500">
                            Simple overview of who is using the platform
                        </span>
                    </div>

                    <div class="grid md:grid-cols-3 gap-4 text-xs text-gray-300">
                        <div class="rounded-xl bg-slate-950/40 border border-slate-800 p-3">
                            <p class="text-[11px] text-gray-400 uppercase mb-1">Registered users</p>
                            <p class="text-xl font-semibold text-gray-100">
                                {{ $stats['total_users'] ?? 0 }}
                            </p>
                            <p class="mt-1 text-[11px] text-gray-500">
                                All accounts created on the platform.
                            </p>
                        </div>

                        <div class="rounded-xl bg-slate-950/40 border border-slate-800 p-3">
                            <p class="text-[11px] text-gray-400 uppercase mb-1">Agents</p>
                            <p class="text-xl font-semibold text-gray-100">
                                {{ $stats['active_agents'] ?? 0 }} active
                            </p>
                            <p class="mt-1 text-[11px] text-gray-500">
                                Partner locations available to serve users.
                            </p>
                        </div>

                        <div class="rounded-xl bg-slate-950/40 border border-slate-800 p-3">
                            <p class="text-[11px] text-gray-400 uppercase mb-1">Pending agents</p>
                            <p class="text-xl font-semibold text-gray-100">
                                {{ $stats['pending_agents'] ?? 0 }}
                            </p>
                            <p class="mt-1 text-[11px] text-gray-500">
                                Awaiting review and activation by the team.
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Small info card --}}
                <div class="space-y-4">
                    <div class="rounded-2xl bg-slate-900/80 border border-slate-800 p-4">
                        <h3 class="text-sm font-semibold text-gray-100 mb-2">
                            Quick numbers
                        </h3>
                        <ul class="space-y-1 text-xs text-gray-300">
                            <li>• Users created last 24 hours:
                                <span class="font-semibold">
                                    {{ $stats['users_last_24h'] ?? 0 }}
                                </span>
                            </li>
                            <li>• Transfers last 7 days:
                                <span class="font-semibold">
                                    {{ $stats['transfers_last_7d'] ?? 0 }}
                                </span>
                            </li>
                        </ul>
                    </div>

                    <div class="rounded-2xl bg-slate-900/80 border border-slate-800 p-4">
                        <h3 class="text-sm font-semibold text-gray-100 mb-2">
                            Shortcuts
                        </h3>
                        <div class="flex flex-col gap-2 text-xs">
                            <a href="{{ route('admin.transactions.index') }}"
                               class="inline-flex items-center justify-between rounded-lg bg-slate-800/80 hover:bg-slate-700
                                      px-3 py-2 text-gray-100 transition">
                                <span>View all transfers</span>
                                <span class="text-[11px] text-gray-400">Open list</span>
                            </a>

                            <a href="{{ route('admin.agents.index') }}"
                               class="inline-flex items-center justify-between rounded-lg bg-slate-800/80 hover:bg-slate-700
                                      px-3 py-2 text-gray-100 transition">
                                <span>Manage agents</span>
                                <span class="text-[11px] text-gray-400">Approve or suspend</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Latest users and latest agents --}}
            <div class="grid gap-6 lg:grid-cols-2">
                {{-- Latest users --}}
                <div class="rounded-2xl bg-slate-900/80 border border-slate-800 p-4">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-sm font-semibold text-gray-100">
                            Latest users
                        </h3>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full text-xs text-left text-gray-300">
                            <thead class="border-b border-slate-800 text-gray-400">
                                <tr>
                                    <th class="py-2 pr-4">Name</th>
                                    <th class="py-2 pr-4">Email</th>
                                    <th class="py-2 pr-4">Registered</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($latestUsers as $u)
                                    <tr class="border-b border-slate-800/80">
                                        <td class="py-2 pr-4">{{ $u->name }}</td>
                                        <td class="py-2 pr-4 text-gray-400">{{ $u->email }}</td>
                                        <td class="py-2 pr-4 text-gray-400">
                                            {{ $u->created_at?->format('d M Y H:i') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="py-4 text-center text-gray-500">
                                            No users yet.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Latest agents --}}
                <div class="rounded-2xl bg-slate-900/80 border border-slate-800 p-4">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-sm font-semibold text-gray-100">
                            Latest agents
                        </h3>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full text-xs text-left text-gray-300">
                            <thead class="border-b border-slate-800 text-gray-400">
                                <tr>
                                    <th class="py-2 pr-4">Name</th>
                                    <th class="py-2 pr-4">City</th>
                                    <th class="py-2 pr-4">Status</th>
                                    <th class="py-2 pr-4">Created</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($latestAgents as $a)
                                    <tr class="border-b border-slate-800/80">
                                        <td class="py-2 pr-4">{{ $a->name }}</td>
                                        <td class="py-2 pr-4 text-gray-400">{{ $a->city ?? '—' }}</td>
                                        <td class="py-2 pr-4">
                                            @if($a->is_active)
                                                <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[10px]
                                                             bg-emerald-500/10 text-emerald-300 border border-emerald-500/40">
                                                    Active
                                                </span>
                                            @else
                                                <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[10px]
                                                             bg-amber-500/10 text-amber-300 border border-amber-500/40">
                                                    Pending
                                                </span>
                                            @endif
                                        </td>
                                        <td class="py-2 pr-4 text-gray-400">
                                            {{ $a->created_at?->format('d M Y H:i') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="py-4 text-center text-gray-500">
                                            No agents yet.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-admin-layout>
