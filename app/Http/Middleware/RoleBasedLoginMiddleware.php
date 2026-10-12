<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class RoleBasedLoginMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Rate-limit ADMIN login only
        if ($request->routeIs('admin.login.store')) {

            $key = Str::lower($request->input('email')) . '|' . $request->ip();

            // Allow 5 attempts per minute
            if (RateLimiter::tooManyAttempts($key, 5)) {
                return redirect()->route('admin.login')
                    ->withErrors(['email' => 'Invalid credentials.']);
            }

            RateLimiter::hit($key, 60);
        }

        // Let authentication happen
        $response = $next($request);

        // After login, check role
        if (Auth::check()) {
            $user = Auth::user();

            // Block USER from admin login
            if ($request->routeIs('admin.login.store') && $user->role === 'user') {
                Auth::logout();

                return redirect()->route('admin.login')
                    ->withErrors(['email' => 'Invalid credentials.']);
            }

            // Block ADMIN/SUPERADMIN from normal login
            if ($request->routeIs('login') && in_array($user->role, ['admin', 'superadmin'])) {
                Auth::logout();

                return redirect()->route('login')
                    ->withErrors(['email' => 'Invalid credentials.']);
            }
        }

        return $response;
    }
}
