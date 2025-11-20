@extends('layouts.agent')

@section('content')
<div class="flex justify-center items-center min-h-screen bg-gray-100">

    <div class="w-full max-w-md bg-white shadow-xl rounded-xl p-8">
        <h2 class="text-2xl font-bold text-center mb-6">Agent Login</h2>

        @if ($errors->any())
            <div class="bg-red-100 text-red-700 p-3 mb-4 rounded">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST">
            @csrf

            <div class="mb-4">
                <label class="block text-gray-600 mb-1">Email</label>
                <input type="email" name="email"
                       class="w-full border rounded-lg p-2 focus:ring focus:ring-blue-300"
                       required />
            </div>

            <div class="mb-4">
                <label class="block text-gray-600 mb-1">Password</label>
                <input type="password" name="password"
                       class="w-full border rounded-lg p-2 focus:ring focus:ring-blue-300"
                       required />
            </div>

            <button class="w-full bg-blue-600 text-white p-2 rounded-lg hover:bg-blue-700">
                Login
            </button>
        </form>
    </div>

</div>
@endsection
