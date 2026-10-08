<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Config;

class SwitchSessionByPath
{
    public function handle($request, Closure $next)
    {
        if ($request->is('admin/*')) {
            config(['session.cookie' => 'admin_session']);
        } elseif ($request->is('user/*') || $request->is('login')) {
            config(['session.cookie' => 'user_session']);
        } else {
            config(['session.cookie' => 'guest_session']);
        }

        return $next($request);
    }
}
