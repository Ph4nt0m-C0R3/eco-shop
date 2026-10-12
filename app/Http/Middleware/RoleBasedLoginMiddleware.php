<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleBasedLoginMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Local login role checks and throttling run before authentication in LoginRequest.
        return $next($request);
    }
}
