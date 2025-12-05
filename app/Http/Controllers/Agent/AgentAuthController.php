<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Agent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AgentAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('agent.login');
    }

    public function login(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required'
    ]);

    // Attempt login
    if (!Auth::attempt($request->only('email', 'password'))) {
        return back()->withErrors(['email' => 'Invalid credentials']);
    }

    $user = Auth::user();

    // User must be agent
    if ($user->role !== 'agent') {
        Auth::logout();
        return back()->withErrors(['email' => 'This is not an agent account']);
    }

    // Make sure agent profile exists
    if (!$user->agent) {
        Auth::logout();
        return back()->withErrors(['email' => 'Agent profile not found']);
    }

    // MAIN CHECK → block login if not activated
    if ($user->agent->is_active == 0) {
        Auth::logout();
        return back()->withErrors(['email' => 'Your agent account is not activated yet']);
    }

    // Otherwise, login OK
    return redirect()->route('agent.dashboard');
}


    public function showRegisterForm()
    {
        return view('agent.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:6',
            'phone'    => 'required',
            'city'     => 'required',
            'country'  => 'required',
        ]);

        // Create user account
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'agent',
        ]);

        // Create agent profile with is_active = 0 (waiting approval)
        Agent::create([
            'user_id'       => $user->id,
            'name'          => $request->name,
            'address'       => $request->address ?? '',
            'city'          => $request->city,
            'country'       => $request->country,
            'phone'         => $request->phone,
            'latitude'      => null,
            'longitude'     => null,
            'opening_hours' => null,
            'is_active'     => 0,  // <- waiting admin approval
        ]);

        return redirect()
            ->route('agent.login')
            ->with('success', 'Registered! Your account is pending admin approval.');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('agent.login');
    }
}
