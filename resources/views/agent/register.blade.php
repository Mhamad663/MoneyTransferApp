@extends('layouts.agent')

@section('content')
<div class="flex justify-center items-center min-h-screen bg-gray-100">

    <div class="w-full max-w-lg bg-white shadow-xl rounded-xl p-8">
        <h2 class="text-2xl font-bold text-center mb-6">Agent Registration</h2>

        @if ($errors->any())
            <div class="bg-red-100 text-red-700 p-3 mb-4 rounded">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST">
            @csrf

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-600 mb-1">Store Name</label>
                    <input type="text" name="name" class="w-full border rounded-lg p-2" required>
                </div>

                <div>
                    <label class="block text-gray-600 mb-1">Phone</label>
                    <input type="text" name="phone" class="w-full border rounded-lg p-2" required>
                </div>

                <div>
                    <label class="block text-gray-600 mb-1">City</label>
                    <input type="text" name="city" class="w-full border rounded-lg p-2" required>
                </div>

                <div>
                    <label class="block text-gray-600 mb-1">Country</label>
                    <input type="text" name="country" class="w-full border rounded-lg p-2" required>
                </div>

                <div class="col-span-2">
                    <label class="block text-gray-600 mb-1">Address</label>
                    <input type="text" name="address" class="w-full border rounded-lg p-2">
                </div>

                <div class="col-span-2">
                    <label class="block text-gray-600 mb-1">Email</label>
                    <input type="email" name="email" class="w-full border rounded-lg p-2" required>
                </div>

                <div class="col-span-2">
                    <label class="block text-gray-600 mb-1">Password</label>
                    <input type="password" name="password" class="w-full border rounded-lg p-2" required>
                </div>
            </div>

            <button class="w-full mt-6 bg-green-600 text-white p-2 rounded-lg hover:bg-green-700">
                Register
            </button>
        </form>

    </div>

</div>
@endsection
