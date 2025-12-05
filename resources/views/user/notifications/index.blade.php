{{-- resources/views/user/notifications/index.blade.php --}}
@extends('layouts.user')

@section('content')
<div class="py-10">
  <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

    {{-- Card wrapper --}}
    <div class="bg-slate-950/80 border border-slate-800 rounded-2xl shadow-xl overflow-hidden">

      {{-- Header --}}
      <div class="flex items-center justify-between px-5 sm:px-6 py-4 border-b border-slate-800 bg-slate-900/80">
        <div>
          <h3 class="text-sm font-semibold tracking-wide text-slate-100">
            Notifications
          </h3>
          <p class="text-xs text-slate-400 mt-1">
            Stay up to date with your transfer status and account activity.
          </p>
        </div>

        @if($notifications->count() > 0)
          <form method="POST" action="{{ route('user.notifications.readAll') }}">
            @csrf
            <button type="submit"
                    class="inline-flex items-center rounded-full bg-emerald-600 px-3 py-1.5 text-xs font-medium text-white
                           hover:bg-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/70">
              Mark all as read
            </button>
          </form>
        @endif
      </div>

      {{-- Flash message --}}
      @if(session('success'))
        <div class="px-5 sm:px-6 py-3 bg-emerald-500/10 border-b border-emerald-500/40 text-xs text-emerald-200">
          {{ session('success') }}
        </div>
      @endif

      {{-- List --}}
      @forelse($notifications as $n)
        @php
          $d      = $n->data;
          $status = $d['status'] ?? null;
          $isUnread = is_null($n->read_at);
        @endphp

        <div class="@if($loop->first) border-t @endif border-slate-800/80">
          <div class="flex items-start gap-4 px-5 sm:px-6 py-4
                      {{ $isUnread ? 'bg-slate-900/80 ring-1 ring-sky-500/30' : 'bg-slate-950/60' }}
                      hover:bg-slate-900 transition-colors duration-150">

            {{-- Status dot --}}
            <span class="mt-1.5 inline-flex h-2.5 w-2.5 rounded-full
                        {{ $isUnread ? 'bg-sky-400' : 'bg-slate-500' }}"></span>

            {{-- Main text --}}
            <div class="flex-1 min-w-0 space-y-1">
              <div class="flex flex-wrap items-baseline gap-2">

                <p class="text-sm text-slate-100 leading-snug">
                  {{ $d['message'] ?? 'Notification' }}
                </p>

                {{-- Status badge if available --}}
                @if($status)
                  <span @class([
                      'inline-flex items-center rounded-full px-2.5 py-0.5 text-[11px] font-medium border',
                      'bg-emerald-500/10 text-emerald-300 border-emerald-500/40' => $status === 'completed',
                      'bg-amber-500/10 text-amber-300 border-amber-500/40'   => in_array($status, ['processing','pending']),
                      'bg-rose-500/10 text-rose-300 border-rose-500/40'       => $status === 'failed',
                      'bg-slate-600/20 text-slate-200 border-slate-500/40'    => ! in_array($status, ['completed','processing','pending','failed']),
                  ])>
                    {{ ucfirst($status) }}
                  </span>
                @endif
              </div>

              {{-- Meta info --}}
              <div class="text-[11px] text-slate-400 flex flex-wrap items-center gap-2">
                @if(!empty($d['reference']))
                  <span class="truncate">
                    Ref: <span class="font-mono text-slate-300">{{ $d['reference'] }}</span>
                  </span>
                @endif
                <span class="opacity-70">· {{ $n->created_at->diffForHumans() }}</span>
              </div>
            </div>

            {{-- Actions --}}
            <div class="flex flex-col items-end gap-2">

              {{-- Optional "Open" link if transfer_id is provided --}}
              @if(!empty($d['transfer_id']))
                <a href="{{ route('user.transfers.show', $d['transfer_id']) }}"
                   class="text-[11px] font-medium text-sky-300 hover:text-sky-200 hover:underline">
                  View transfer
                </a>
              @endif

              @if($isUnread)
                <form method="POST" action="{{ route('user.notifications.read', $n->id) }}">
                  @csrf
                  <button type="submit"
                          class="inline-flex items-center rounded-full bg-slate-800 px-2.5 py-1 text-[11px] font-medium
                                 text-slate-100 hover:bg-slate-700 focus:outline-none focus:ring-1 focus:ring-slate-500">
                    Mark as read
                  </button>
                </form>
              @endif
            </div>
          </div>
        </div>
      @empty
        <div class="px-5 sm:px-6 py-10 text-center text-sm text-slate-400">
          You do not have any notifications yet.
        </div>
      @endforelse

      {{-- Pagination --}}
      @if($notifications->hasPages())
        <div class="px-5 sm:px-6 py-4 border-t border-slate-800 bg-slate-950/70">
          {{ $notifications->links() }}
        </div>
      @endif
    </div>

  </div>
</div>
@endsection
