<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class SwitchSessionByPath
{
    public function handle($request, Closure $next)
    {
        $admin = $request->is('admin', 'admin/*');
        config(['session.cookie' => config($admin ? 'session.admin_cookie' : 'session.customer_cookie')]);
        config(['session.path' => $admin ? '/admin' : config('session.customer_path')]);
        Auth::shouldUse($admin ? 'admin' : 'web');
        // Name the store before StartSession reads the request's cookie.
        Session::driver()->setName(config('session.cookie'));

        return $next($request);
    }
}
