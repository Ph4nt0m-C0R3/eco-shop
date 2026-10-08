<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            // Not logged in → admin login
            return redirect()->route('admin.login');
        }

        if (!in_array(Auth::user()->role, ['admin', 'superadmin'])) {
            // Logged in but not admin
            return back();
        }

        return $next($request);
    }
}
