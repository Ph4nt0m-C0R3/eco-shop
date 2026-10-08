<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        // 1. Logged-in user locale (highest priority)
        if (Auth::check() && Auth::user()->locale) {
            app()->setLocale(Auth::user()->locale);
            session(['locale' => Auth::user()->locale]);
        }
        // 2. Guest session locale
        elseif (session()->has('locale')) {
            app()->setLocale(session('locale'));
        }
        // 3. Fallback
        else {
            app()->setLocale(config('app.locale'));
        }

        return $next($request);
    }
}
