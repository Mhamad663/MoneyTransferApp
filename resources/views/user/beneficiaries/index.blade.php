@extends('layouts.user')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-5">
  <h1 class="text-lg font-semibold text-slate-800 dark:text-slate-100">My Beneficiaries</h1>
  <a href="{{ route('user.beneficiaries.create') }}"
     class="inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg shadow-sm transition-all">
     <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
     Add New
  </a>
</div>

{{-- Flash message --}}
@if(session('success'))
  <div class="mb-4 rounded-lg bg-green-100 dark:bg-green-900/40 text-green-700 dark:text-green-300 p-3 border border-green-200 dark:border-green-800">
    {{ session('success') }}
  </div>
@endif

{{-- Table container --}}
<div class="overflow-x-auto bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-sm">
  <table class="min-w-full text-sm text-slate-700 dark:text-slate-200">
    <thead class="bg-slate-50 dark:bg-slate-800/60">
      <tr>
        <th class="text-left px-4 py-3 font-medium">Name</th>
        <th class="text-left px-4 py-3 font-medium">Method</th>
        <th class="text-left px-4 py-3 font-medium">Country</th>
        <th class="text-center px-4 py-3 font-medium">Favorite</th>
        <th class="text-right px-4 py-3 font-medium">Actions</th>
      </tr>
    </thead>
    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
      @forelse($items as $b)
        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition">
          <td class="px-4 py-3 font-medium break-words">{{ $b->name }}</td>
          <td class="px-4 py-3 capitalize break-words">{{ str_replace('_',' ', $b->payout_method) }}</td>
          <td class="px-4 py-3 break-words">{{ $b->country }}</td>
          <td class="px-4 py-3 text-center">
            @if($b->is_favorite)
              <span class="text-yellow-400 text-lg">⭐</span>
            @else
              <span class="text-slate-400">—</span>
            @endif
          </td>
          <td class="px-4 py-3 text-right flex justify-end items-center gap-2 flex-wrap">

            {{-- Favorite/Unfavorite --}}
            <form method="POST" action="{{ route('user.beneficiaries.favorite',$b) }}" class="inline">@csrf
              <button class="px-3 py-1.5 rounded-lg border border-slate-300 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 transition text-sm">
                {{ $b->is_favorite ? 'Unfavorite' : 'Favorite' }}
              </button>
            </form>

            {{-- Edit button --}}
            <a href="{{ route('user.beneficiaries.edit',$b) }}"
               class="inline-flex items-center gap-1 bg-emerald-600 hover:bg-emerald-700 text-white px-3 py-1.5 rounded-lg text-sm shadow-sm transition">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2v-5M18.5 2.5l3 3L12 15l-4 1 1-4 9.5-9.5z"/>
              </svg>
              Edit
            </a>

            {{-- Delete button (opens modal) --}}
            <button type="button"
                    onclick="openDeleteModal({{ $b->id }}, '{{ $b->name }}')"
                    class="inline-flex items-center gap-1 bg-red-600 hover:bg-red-700 text-white px-3 py-1.5 rounded-lg text-sm shadow-sm transition">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 4h6m-7 0h8m-8 0a2 2 0 012-2h4a2 2 0 012 2z"/>
              </svg>
              Delete
            </button>
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="5" class="p-5 text-center text-slate-500 dark:text-slate-400">
            No beneficiaries yet.
          </td>
        </tr>
      @endforelse
    </tbody>
  </table>
</div>

{{-- Pagination --}}
@if ($items->hasPages())
  <div class="mt-4">
    {{ $items->links('vendor.pagination.tailwind') }}
  </div>
@endif

{{-- Delete Confirmation Modal --}}
<div id="deleteModal" class="fixed inset-0 bg-black/60 dark:bg-black/70 hidden z-50 flex items-center justify-center p-4">
  <div class="bg-white dark:bg-slate-900 rounded-xl p-6 w-full max-w-md shadow-xl border border-slate-200 dark:border-slate-800">
    <h2 class="text-lg font-semibold text-slate-800 dark:text-slate-100 mb-3">Confirm Deletion</h2>
    <p id="deleteMessage" class="text-slate-600 dark:text-slate-300 mb-6 text-sm"></p>
    <div class="flex justify-end gap-3">
      <button onclick="closeDeleteModal()"
              class="px-4 py-2 rounded-lg border border-slate-300 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 text-sm transition">
        Cancel
      </button>
      <form id="deleteForm" method="POST" class="inline">
        @csrf @method('DELETE')
        <button class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm transition">Delete</button>
      </form>
    </div>
  </div>
</div>

{{-- Scripts --}}
<script>
  function openDeleteModal(id, name) {
    const modal = document.getElementById('deleteModal');
    const form = document.getElementById('deleteForm');
    const msg = document.getElementById('deleteMessage');

    msg.textContent = `Are you sure you want to delete beneficiary “${name}”?`;
    form.action = `/user/beneficiaries/${id}`; // auto routes to delete
    modal.classList.remove('hidden');
  }

  function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
  }

  // Close modal on backdrop click
  document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) closeDeleteModal();
  });
</script>

<style>
  @media (max-width: 640px) {
    table thead { display: none; }
    table tbody tr {
      display: block;
      margin-bottom: 1rem;
      border: 1px solid #e2e8f0;
      border-radius: .75rem;
      overflow: hidden;
    }
    table td {
      display: flex;
      justify-content: space-between;
      padding: .75rem 1rem;
      font-size: .9rem;
      border-bottom: 1px solid #e5e7eb;
    }
    table td:last-child { border-bottom: none; }
    table td::before {
      content: attr(data-label);
      font-weight: 600;
      color: #475569;
    }
  }
</style>
@endsection
