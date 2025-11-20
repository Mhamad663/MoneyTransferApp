@extends('layouts.agent')

@section('content')
<div class="p-8">

    <h1 class="text-3xl font-bold mb-6">Settings</h1>

    @if(session('success'))
        <div class="p-3 mb-4 bg-green-100 text-green-800 rounded">
            {{ session('success') }}
        </div>
    @endif

    <!-- PROFILE SETTINGS -->
    <div class="bg-white shadow rounded-lg p-6 mb-8">
        <h2 class="text-xl font-semibold mb-4">Profile Information</h2>

        <form method="POST" action="{{ route('agent.settings.update') }}">
            @csrf

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="font-medium">Name</label>
                    <input type="text" name="name" value="{{ $agent->name }}"
                           class="w-full border p-2 rounded">
                </div>

                <div>
                    <label class="font-medium">Phone</label>
                    <input type="text" name="phone" value="{{ $agent->phone }}"
                           class="w-full border p-2 rounded">
                </div>

                <div>
                    <label class="font-medium">City</label>
                    <input type="text" name="city" value="{{ $agent->city }}"
                           class="w-full border p-2 rounded">
                </div>

                <div>
                    <label class="font-medium">Country</label>
                    <input type="text" name="country" value="{{ $agent->country }}"
                           class="w-full border p-2 rounded">
                </div>

                <div class="col-span-2">
                    <label class="font-medium">Address</label>
                    <input type="text" name="address" value="{{ $agent->address }}"
                           class="w-full border p-2 rounded">
                </div>
            </div>

            <button class="mt-4 px-4 py-2 bg-blue-600 text-white rounded">Save</button>
        </form>
    </div>

    <!-- PASSWORD SETTINGS -->
    <div class="bg-white shadow rounded-lg p-6">
        <h2 class="text-xl font-semibold mb-4">Change Password</h2>

        <form method="POST" action="{{ route('agent.settings.password') }}">
            @csrf

            <div class="mb-4">
                <label class="font-medium">Old Password</label>
                <input type="password" name="old_password" class="w-full border p-2 rounded">
            </div>

            <div class="mb-4">
                <label class="font-medium">New Password</label>
                <input type="password" name="new_password" class="w-full border p-2 rounded">
            </div>

            <div class="mb-4">
                <label class="font-medium">Confirm Password</label>
                <input type="password" name="new_password_confirmation"
                       class="w-full border p-2 rounded">
            </div>

            <button class="px-4 py-2 bg-blue-600 text-white rounded">Update Password</button>
        </form>
    </div>

</div>
@endsection
