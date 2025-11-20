@extends('layouts.agent')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Working Hours</h1>

    @if(session('success'))
        <div class="p-3 bg-green-200 text-green-900 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('agent.workinghours.update') }}" method="POST">
        @csrf

        @php
            $days = ['monday','tuesday','wednesday','thursday','friday','saturday','sunday'];
        @endphp

        <table class="w-full">
            @foreach($days as $day)
                <tr class="border-b">
                    <td class="capitalize py-3 w-32">{{ $day }}</td>

                    <td>
                        <input type="time"
                               name="hours[{{ $day }}][open]"
                               value="{{ $hours[$day]['open'] ?? '' }}"
                               class="border p-1 rounded">
                    </td>

                    <td>
                        <input type="time"
                               name="hours[{{ $day }}][close]"
                               value="{{ $hours[$day]['close'] ?? '' }}"
                               class="border p-1 rounded">
                    </td>

                    <td>
                        <label>
                            <input type="checkbox"
                                   name="hours[{{ $day }}][closed]"
                                   {{ isset($hours[$day]['closed']) ? 'checked' : '' }}>
                            Closed
                        </label>
                    </td>
                </tr>
            @endforeach
        </table>

        <button class="mt-4 px-4 py-2 bg-blue-600 text-white rounded">
            Save Hours
        </button>
    </form>
</div>
@endsection
