<x-admin-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-100 leading-tight">
                    Transfer Services
                </h2>
                <p class="text-xs text-gray-400 mt-1">
                    Configure methods and fee structures used for transfers.
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-950">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Existing services --}}
            <div class="rounded-2xl bg-slate-900/80 border border-slate-800 shadow-lg overflow-hidden">
                <div class="px-4 py-3 border-b border-slate-800 flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-gray-100">
                        Available transfer services
                    </h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-xs text-left text-gray-200">
                        <thead class="bg-slate-900/80 text-gray-400 border-b border-slate-800">
                            <tr>
                                <th class="px-4 py-2">Code</th>
                                <th class="px-4 py-2">Name</th>
                                <th class="px-4 py-2">Method</th>
                                <th class="px-4 py-2">Fee %</th>
                                <th class="px-4 py-2">Fixed fee</th>
                                <th class="px-4 py-2">Speed</th>
                                <th class="px-4 py-2">Active</th>
                                <th class="px-4 py-2 text-right">Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                        @forelse($services as $service)
                            <tr class="border-b border-slate-800/80 hover:bg-slate-800/40">

                                <td class="px-4 py-2 text-[11px] text-indigo-300">
                                    {{ $service->code }}
                                </td>

                                <form method="POST" action="{{ route('admin.services.update', $service) }}">
                                    @csrf
                                    @method('PUT')

                                    <td class="px-4 py-2">
                                        <input type="text" name="name"
                                               value="{{ $service->name }}"
                                               class="w-40 bg-slate-800 border border-slate-700 rounded-md px-2 py-1 text-xs">
                                    </td>

                                    <td class="px-4 py-2">
                                        <input type="text" name="method"
                                               value="{{ $service->method }}"
                                               class="w-24 bg-slate-800 border border-slate-700 rounded-md px-2 py-1 text-xs">
                                    </td>

                                    <td class="px-4 py-2">
                                        <input type="number" step="0.01" name="fee_percent"
                                               value="{{ $service->fee_percent }}"
                                               class="w-20 bg-slate-800 border border-slate-700 rounded-md px-2 py-1 text-xs">
                                    </td>

                                    <td class="px-4 py-2">
                                        <input type="number" step="0.01" name="fixed_fee"
                                               value="{{ $service->fixed_fee }}"
                                               class="w-24 bg-slate-800 border border-slate-700 rounded-md px-2 py-1 text-xs">
                                    </td>

                                    <td class="px-4 py-2">
                                        <input type="text" name="speed"
                                               value="{{ $service->speed }}"
                                               class="w-24 bg-slate-800 border border-slate-700 rounded-md px-2 py-1 text-xs">
                                    </td>

                                    <td class="px-4 py-2">
                                        <label class="inline-flex items-center text-[11px] text-gray-300">
                                            <input type="checkbox" name="active" value="1"
                                                   class="rounded border-slate-600 bg-slate-800 text-indigo-500"
                                                   {{ $service->active ? 'checked' : '' }}>
                                            <span class="ml-1">Active</span>
                                        </label>
                                    </td>

                                    <td class="px-4 py-2 text-right flex items-center justify-end gap-3">
                                        <button class="px-3 py-1 rounded-full bg-indigo-600 text-white text-[11px]">
                                            Save
                                        </button>
                                </form>

                                        <form method="POST"
                                              action="{{ route('admin.services.destroy', $service) }}"
                                              onsubmit="return confirm('Delete this service?');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="text-[11px] text-rose-400 hover:text-rose-300">
                                                Delete
                                            </button>
                                        </form>
                                    </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-4 py-6 text-center text-gray-500 text-sm">
                                    No services configured yet.
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Create new service --}}
            <div class="rounded-2xl bg-slate-900/80 border border-slate-800 shadow-lg p-4">
                <h3 class="text-sm font-semibold text-gray-100 mb-3">
                    Add new transfer service
                </h3>

                <form method="POST" action="{{ route('admin.services.store') }}"
                      class="flex flex-wrap items-center gap-3 text-xs">
                    @csrf

                    <input type="text" name="name" placeholder="Service name"
                           class="w-48 bg-slate-800 border border-slate-700 rounded-md px-3 py-2" required>

                    <input type="text" name="method" placeholder="Method (wallet, bank…)"
                           class="w-40 bg-slate-800 border border-slate-700 rounded-md px-3 py-2" required>

                    <input type="number" step="0.01" name="fee_percent" placeholder="Fee %"
                           class="w-28 bg-slate-800 border border-slate-700 rounded-md px-3 py-2" required>

                    <input type="number" step="0.01" name="fixed_fee" placeholder="Fixed fee"
                           class="w-28 bg-slate-800 border border-slate-700 rounded-md px-3 py-2" required>

                    <input type="text" name="speed" placeholder="Speed (Instant, 1–3 days)"
                           class="w-40 bg-slate-800 border border-slate-700 rounded-md px-3 py-2">

                    <label class="inline-flex items-center text-gray-300">
                        <input type="checkbox" name="active" value="1"
                               class="rounded border-slate-600 bg-slate-800 text-indigo-500" checked>
                        <span class="ml-1">Active</span>
                    </label>

                    <button class="ml-auto px-4 py-2 rounded-full bg-indigo-600 text-white">
                        Create service
                    </button>
                </form>
            </div>

        </div>
    </div>
</x-admin-layout>
