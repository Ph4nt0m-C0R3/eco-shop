<?php

namespace App\Http\Middleware;

use App\Services\AdminMfa;
use Closure;
use Illuminate\Http\Request;

class RequireAdminMfa
{
    public function handle(Request $request, Closure $next)
    {
        if ($user = $request->user()) {
            if ($user->isAdmin() && !$request->routeIs('admin.mfa.*', 'admin.logout') && !AdminMfa::verified($request, $user)) {
                $route = $user->two_factor_confirmed_at ? 'admin.mfa.challenge' : 'admin.mfa.setup';
                return $request->expectsJson()
                    ? response()->json(['message' => __('auth-security.mfa_required'), 'redirect' => route($route)], 403)
                    : redirect()->route($route);
            }
        }
        return $next($request);
    }
}
