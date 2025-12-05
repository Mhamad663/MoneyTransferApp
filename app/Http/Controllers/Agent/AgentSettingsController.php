<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Agent;

class AgentSettingsController extends Controller
{
    public function index()
    {
        $agent = Agent::where('user_id', Auth::id())->firstOrFail();
        return view('agent.settings.index', compact('agent'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'phone' => 'required|string',
            'address' => 'nullable|string',
            'city' => 'required|string',
            'country' => 'required|string',
        ]);

        $agent = Agent::where('user_id', Auth::id())->firstOrFail();
        $agent->update($request->only('name', 'phone', 'address', 'city', 'country'));

        return back()->with('success', 'Profile updated successfully.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->old_password, $user->password)) {
            return back()->withErrors(['old_password' => 'Incorrect old password']);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return back()->with('success', 'Password updated successfully.');
    }
}
