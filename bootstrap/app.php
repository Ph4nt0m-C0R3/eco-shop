<?php

use Illuminate\Foundation\Application;
use App\Http\Middleware\UserMiddleware;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\SuperAdminMiddleware;
use App\Http\Middleware\RoleBasedLoginMiddleware;
use App\Http\Middleware\SetLocale;
use App\Http\Middleware\ByPassMiddleware;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;



return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {

        // Global middleware
        $middleware->web(append: [
            \App\Http\Middleware\SwitchSessionByPath::class,
            SetLocale::class,
            \App\Http\Middleware\UpdateLastOnline::class,
        ]);

        $middleware->alias([
            'superadmin' => SuperAdminMiddleware::class,
            'admin' => AdminMiddleware::class,
            'user' => UserMiddleware::class,
            'role.login' => RoleBasedLoginMiddleware::class,
            'bypass' => BypassMiddleware::class,
        ]);

    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
