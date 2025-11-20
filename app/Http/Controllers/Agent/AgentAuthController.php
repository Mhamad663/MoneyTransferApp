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

        if (!Auth::attempt($request->only('email', 'password'))) {
            return back()->withErrors(['email' => 'Invalid credentials']);
        }

        if (Auth::user()->role !== 'agent') {
            Auth::logout();
            return back()->withErrors(['email' => 'This is not an agent account']);
        }

     /*   if (!Auth::user()->agent || Auth::user()->agent->is_active == 0) {
            Auth::logout();
            return back()->withErrors(['email' => 'Your agent account is pending admin approval']);
        }*/

        return redirect()->route('agent.dashboard');
    }

    public function showRegisterForm()
    {
        return view('agent.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'phone' => 'required',
            'city' => 'required',
            'country' => 'required',
        ]);

        // Create User
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),  
        ]);
$user->role = 'agent';
$user->save();
        // Create Agent Profile
        Agent::create([
            'user_id' => $user->id,
            'name' => $request->name,
            'name' => $request->name,
            'address' => $request->address ?? '',
            'city' => $request->city,
            'country' => $request->country,
            'phone' => $request->phone,
            'latitude' => null,
            'longitude' => null,
            'opening_hours' => null,
            'is_active' => 1, // admin must approve
        ]);

        return redirect()->route('agent.login')->with('success', 'Registered! Waiting for admin approval.');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('agent.login');
    }
}
