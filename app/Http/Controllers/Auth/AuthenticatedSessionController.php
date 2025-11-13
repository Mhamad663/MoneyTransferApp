<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // Authenticate user credentials
        $request->authenticate();

        // Regenerate session to prevent fixation attacks
        $request->session()->regenerate();

        // Get the authenticated user
        $user = Auth::user();

        // ✅ Redirect based on role
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard')
                ->with('success', 'Welcome back, Admin!');
        }

        if ($user->role === 'agent') {
            return redirect()->route('agent.dashboard')
                ->with('success', 'Welcome Agent! Manage your transfers.');
        }

        // Default redirect for normal users
        return redirect()->route('user.dashboard')
            ->with('success', 'Welcome to your dashboard!');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')
            ->with('success', 'You have been logged out successfully.');
    }
}
