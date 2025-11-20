<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class EnsureAgent
{
    public function handle($request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect('/agent/login');
        }

        if (Auth::user()->role !== 'agent') {
            return abort(403, 'Unauthorized');
        }

      /*  if (Auth::user()->agent->is_active == 0) {
            return abort(403, 'Your account is pending approval.');
        }*/

        return $next($request);
    }
}
