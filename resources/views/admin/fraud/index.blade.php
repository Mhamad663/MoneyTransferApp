<x-admin-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-100 leading-tight">
                    Fraud alerts / refund requests
                </h2>
                <p class="text-xs text-gray-400 mt-1">
                    Review refund and dispute requests opened by users.
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
            <div class="flex items-center justify-between text-xs mb-2">
                <div class="flex gap-2">
                    @php
                        $tabs = [
                            'open'         => 'Open',
                            'in_review'    => 'In review',
                            'resolved'     => 'Resolved',
                            'rejected'     => 'Rejected',
                            'flagged_fraud'=> 'Flagged as fraud',
                            'all'          => 'All',
                        ];
                    @endphp

                    @foreach($tabs as $key => $label)
                        <a href="{{ route('admin.fraud.index', ['status' => $key]) }}"
                           class="px-3 py-1 rounded-full border
                            {{ $status === $key
                                ? 'bg-rose-600 text-white border-rose-500'
                                : 'bg-slate-900 text-gray-300 border-slate-700 hover:bg-slate-800' }}">
                            {{ $label }}
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="rounded-2xl bg-slate-900/80 border border-slate-800 shadow-lg overflow-hidden">
                <div class="px-4 py-3 border-b border-slate-800 flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-gray-100">
                        Refund and dispute requests
                    </h3>
                    <p class="text-xs text-gray-500">
                        {{ $requests->total() }} records
                    </p>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-xs text-left text-gray-200">
                        <thead class="bg-slate-900/80 text-gray-400 border-b border-slate-800">
                            <tr>
                                <th class="px-4 py-2">Transfer</th>
                                <th class="px-4 py-2">User</th>
                                <th class="px-4 py-2">Kind</th>
                                <th class="px-4 py-2">Reason</th>
                                <th class="px-4 py-2">Status</th>
                                <th class="px-4 py-2">Created</th>
                                <th class="px-4 py-2">Update status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($requests as $r)
                                <tr class="border-b border-slate-800/80 hover:bg-slate-800/60">
                                    <td class="px-4 py-2">
                                        #{{ $r->transfer_id }}
                                        <div class="text-[11px] text-gray-400">
                                            {{ optional($r->transfer)->src_currency }}
                                            {{ optional($r->transfer)->amount_src }}
                                        </div>
                                    </td>
                                    <td class="px-4 py-2">
                                        {{ optional($r->user)->name ?? 'N A' }}
                                        <div class="text-[11px] text-gray-400">
                                            {{ optional($r->user)->email }}
                                        </div>
                                    </td>
                                    <td class="px-4 py-2 text-[11px] capitalize">
                                        {{ $r->kind }}
                                    </td>
                                    <td class="px-4 py-2 text-[11px] text-gray-300 max-w-xs">
                                        {{ $r->reason ?: 'No short reason provided' }}
                                        @if($r->details)
                                            <div class="text-[11px] text-gray-500 mt-1 line-clamp-2">
                                                {{ $r->details }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-4 py-2">
                                        @php
                                            $badge = match($r->status) {
                                                'open'          => 'bg-amber-500/10 text-amber-300 border-amber-500/40',
                                                'in_review'     => 'bg-sky-500/10 text-sky-300 border-sky-500/40',
                                                'resolved'      => 'bg-emerald-500/10 text-emerald-300 border-emerald-500/40',
                                                'rejected'      => 'bg-slate-500/10 text-slate-300 border-slate-500/40',
                                                'flagged_fraud' => 'bg-rose-500/10 text-rose-300 border-rose-500/40',
                                                default         => 'bg-slate-600/20 text-slate-200 border-slate-500/40',
                                            };
                                        @endphp
                                        <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[10px] border {{ $badge }}">
                                            {{ str_replace('_', ' ', ucfirst($r->status)) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-2 text-[11px] text-gray-400">
                                        {{ $r->created_at?->format('d M Y H:i') }}
                                    </td>
                                    <td class="px-4 py-2">
                                        <form action="{{ route('admin.fraud.updateStatus', $r) }}"
                                              method="POST" class="inline-block">
                                            @csrf
                                            <select name="status"
                                                    onchange="this.form.submit()"
                                                    class="bg-slate-800 text-gray-200 text-[11px] rounded-xl px-2 py-1 border border-slate-700">
                                                <option value="open"          {{ $r->status === 'open' ? 'selected' : '' }}>Open</option>
                                                <option value="in_review"     {{ $r->status === 'in_review' ? 'selected' : '' }}>In review</option>
                                                <option value="resolved"      {{ $r->status === 'resolved' ? 'selected' : '' }}>Resolved</option>
                                                <option value="rejected"      {{ $r->status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                                                <option value="flagged_fraud" {{ $r->status === 'flagged_fraud' ? 'selected' : '' }}>Flagged fraud</option>
                                            </select>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-4 py-6 text-center text-gray-500 text-sm">
                                        No refund or dispute requests for this filter.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($requests->hasPages())
                    <div class="px-4 py-3 border-t border-slate-800 bg-slate-950/80">
                        {{ $requests->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-admin-layout>
