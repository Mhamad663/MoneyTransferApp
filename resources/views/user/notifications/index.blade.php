@extends('layouts.user')

@section('content')
<div class="py-10">
  <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
    <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">
      <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">🔔 Notifications</h3>
        <form method="POST" action="{{ route('user.notifications.readAll') }}">
          @csrf
          <button type="submit"
                  class="px-3 py-1 bg-green-600 hover:bg-green-700 text-white text-sm rounded">
            Mark All as Read
          </button>
        </form>
      </div>

      @if(session('success'))
        <div class="mb-4 text-green-700 bg-green-100 border border-green-300 p-2 rounded">
          {{ session('success') }}
        </div>
      @endif

      @forelse($notifications as $n)
        @php $d = $n->data; @endphp
        <div class="border-b py-3 flex justify-between items-center {{ $n->read_at ? 'bg-gray-50' : 'bg-yellow-50' }}">
          <div>
            <p class="text-sm text-gray-800 dark:text-gray-200">
              {{ $d['message'] ?? 'No message' }}
            </p>
            <small class="text-gray-500 dark:text-gray-400">
              {{ $n->created_at->diffForHumans() }}
            </small>
          </div>

          @if(is_null($n->read_at))
            <form method="POST" action="{{ route('user.notifications.read', $n->id) }}">
              @csrf
              <button type="submit"
                      class="px-3 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700">
                Mark as Read
              </button>
            </form>
          @endif
        </div>
      @empty
        <p class="text-gray-500 text-center mt-4">No notifications found.</p>
      @endforelse

      <div class="mt-6">{{ $notifications->links() }}</div>
    </div>
  </div>
</div>
@endsection
