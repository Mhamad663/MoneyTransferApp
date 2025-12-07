<x-admin-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-100 leading-tight">
                Agents & Partner Stores
            </h2>
            <p class="text-xs text-gray-400">
                Review registrations and approve or suspend agents.
            </p>
            <a href="{{ route('admin.dashboard') }}"
               class="inline-flex items-center gap-2 rounded-full bg-slate-800 hover:bg-slate-700
                      text-xs text-gray-100 px-4 py-2 border border-slate-700 transition">
                Back to dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-950">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Flash message --}}
            @if (session('success'))
                <div class="rounded-xl bg-emerald-500/10 border border-emerald-500/40 px-4 py-3 text-sm text-emerald-200">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Filters / summary row  --}}
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div>
                    <h3 class="text-sm font-semibold text-gray-100">
                        Registered agents
                    </h3>
                    <p class="text-xs text-gray-400">
                        Showing {{ $agents->firstItem() ?? 0 }}–{{ $agents->lastItem() ?? 0 }}
                        of {{ $agents->total() }} agents.
                    </p>
                </div>
                <div class="flex items-center gap-2 text-xs text-gray-400">
                    <span class="inline-flex items-center rounded-full bg-emerald-500/10 px-2 py-0.5 text-emerald-300 border border-emerald-500/40">
                        Active
                    </span>
                    <span class="inline-flex items-center rounded-full bg-amber-500/10 px-2 py-0.5 text-amber-300 border border-amber-500/40">
                        Pending / Suspended
                    </span>
                </div>
            </div>

            {{-- Table --}}
            <div class="overflow-hidden rounded-2xl bg-slate-900/80 border border-slate-800 shadow-lg">
                <table class="min-w-full text-sm text-left text-gray-200">
                    <thead class="bg-slate-900/90 border-b border-slate-800 text-xs uppercase tracking-wide text-gray-400">
                        <tr>
                            <th class="px-4 py-3">Agent</th>
                            <th class="px-4 py-3">Email</th>
                            <th class="px-4 py-3 hidden md:table-cell">Location</th>
                            <th class="px-4 py-3 hidden sm:table-cell">Phone</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($agents as $agent)
                            <tr class="border-b border-slate-800/80 hover:bg-slate-800/40 transition">
                                {{-- Name --}}
                                <td class="px-4 py-3">
                                    <div class="flex flex-col">
                                        <span class="font-semibold text-gray-100">
                                            {{ $agent->name }}
                                        </span>
                                        <span class="text-[11px] text-gray-400">
                                            #{{ $agent->id }}
                                        </span>
                                    </div>
                                </td>

                                {{-- Email --}}
                                <td class="px-4 py-3">
                                    <span class="text-xs text-gray-300">
                                        {{ optional($agent->user)->email ?? '—' }}
                                    </span>
                                </td>

                                {{-- Location --}}
                                <td class="px-4 py-3 hidden md:table-cell text-xs text-gray-300">
                                    {{ $agent->city }}, {{ $agent->country }}
                                </td>

                                {{-- Phone --}}
                                <td class="px-4 py-3 hidden sm:table-cell text-xs text-gray-300">
                                    {{ $agent->phone ?: '—' }}
                                </td>

                                {{-- Status --}}
                                <td class="px-4 py-3">
                                    @if ($agent->is_active)
                                        <span class="inline-flex items-center rounded-full bg-emerald-500/10 px-2 py-0.5 text-[11px] font-medium text-emerald-300 border border-emerald-500/40">
                                            Active
                                        </span>
                                    @else
                                        <span class="inline-flex items-center rounded-full bg-amber-500/10 px-2 py-0.5 text-[11px] font-medium text-amber-300 border border-amber-500/40">
                                            Pending / Suspended
                                        </span>
                                    @endif
                                </td>

                                {{-- Actions --}}
                                <td class="px-4 py-3 text-right">
                                    <div class="inline-flex items-center gap-2">
                                        @if (! $agent->is_active)
                                            <form method="POST" action="{{ route('admin.agents.approve', $agent) }}">
                                                @csrf
                                                <button
                                                    class="rounded-full bg-emerald-600 hover:bg-emerald-500 text-white text-xs px-3 py-1 transition">
                                                    Approve
                                                </button>
                                            </form>
                                        @else
                                            <form method="POST" action="{{ route('admin.agents.suspend', $agent) }}">
                                                @csrf
                                                <button
                                                    class="rounded-full bg-rose-600 hover:bg-rose-500 text-white text-xs px-3 py-1 transition">
                                                    Suspend
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-6 text-center text-sm text-gray-400">
                                    No agents found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            
            <div class="flex justify-end">
                {{ $agents->links() }}
            </div>

        </div>
    </div>
</x-admin-layout>
