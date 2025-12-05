<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminAuthController extends Controller
{
    // --- LOGIN ---

    public function showLoginForm()
    {
        return view('admin.auth.login');   // resources/views/admin/auth/login.blade.php
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (! Auth::attempt($request->only('email', 'password'))) {
            return back()->withErrors(['email' => 'Invalid credentials']);
        }

        if (Auth::user()->role !== 'admin') {     // only admins allowed here
            Auth::logout();
            return back()->withErrors(['email' => 'This is not an admin account']);
        }

        return redirect()->route('admin.dashboard');
    }

    // --- REGISTRATION (ADMIN ONLY) ---

    public function showRegisterForm()
    {
        return view('admin.auth.register');  // resources/views/admin/auth/register.blade.php
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|max:255|unique:users,email',
            'password' => 'required|confirmed|min:6',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'admin',   // 👈 VERY IMPORTANT
        ]);

        // auto-login new admin or just redirect
        Auth::login($user);

        return redirect()->route('admin.dashboard');
    }

    // --- LOGOUT ---

    public function logout()
    {
        Auth::logout();
        return redirect()->route('admin.login');
    }
}
