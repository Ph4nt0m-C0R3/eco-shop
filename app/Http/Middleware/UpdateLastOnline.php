<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UpdateLastOnline
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();

            // Only update if null or more than 1 min ago (avoid DB overload)
            if (!$user->last_online_at || $user->last_online_at->diffInSeconds(now()) > 60) {
                $user->update(['last_online_at' => now()]);
            }
        }

        return $next($request);
    }
}
