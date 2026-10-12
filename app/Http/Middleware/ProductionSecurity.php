<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ProductionSecurity
{
    public function handle(Request $request, Closure $next): Response
    {
        if (config('app.env') !== 'production') {
            return $next($request);
        }

        $canonical = rtrim((string) config('app.url'), '/');
        $host = parse_url($canonical, PHP_URL_HOST);
        if (config('app.debug') || !config('session.secure')
            || !config('session.encrypt') || config('session.customer_cookie') === config('session.admin_cookie')
            || !$host || parse_url($canonical, PHP_URL_SCHEME) !== 'https') {
            return response('Service configuration unavailable.', 503);
        }
        if (strcasecmp($request->getHost(), $host) !== 0) {
            return response('Invalid request host.', 400);
        }
        if (!$request->isSecure()) {
            // Browsing can redirect; insecure writes must not reach application actions.
            return $request->isMethodSafe()
                ? redirect($canonical.$request->getRequestUri(), 308)
                : response('HTTPS is required.', 400);
        }

        $response = $next($request);
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        if (!$response->headers->has('Referrer-Policy')) $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Strict-Transport-Security', 'max-age=31536000');
        return $response;
    }
}
