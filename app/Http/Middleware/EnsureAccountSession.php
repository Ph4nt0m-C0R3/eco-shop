<?php

namespace App\Http\Middleware;

use App\Support\AccountSecurity;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureAccountSession
{
    public function handle(Request $request, Closure $next)
    {
        if ($user = $request->user()) {
            $fresh = $user->fresh();
            $identity = $request->session()->get('auth_identity');
            if (!$fresh || !is_string($identity) || !hash_equals(AccountSecurity::fingerprint($fresh), $identity)) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return $request->expectsJson()
                    ? response()->json(['message' => __('auth-security.sign_in_again')], 401)
                    : redirect()->route($request->is('admin', 'admin/*') ? 'admin.login' : 'login')->with('status', __('auth-security.sign_in_again'));
            }
            Auth::setUser($fresh);
        }
        $response = $next($request);
        if ($request->is('admin/*', '*password*', 'login', 'register', 'verify-email*')) {
            $response->headers->set('Cache-Control', 'private, no-store');
        }
        return $response;
    }
}
