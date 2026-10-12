<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\TrustProxies;
use Illuminate\Http\Request;

class TrustDeploymentProxies extends TrustProxies
{
    protected $headers = Request::HEADER_X_FORWARDED_FOR
        | Request::HEADER_X_FORWARDED_PORT
        | Request::HEADER_X_FORWARDED_PROTO;

    protected function setTrustedProxyIpAddresses(Request $request)
    {
        // Avoid implicit wildcard trust and ignore forwarded hosts completely.
        $proxies = config('deployment.trusted_proxies', []);
        if (array_intersect($proxies, ['*', '**']) || array_filter($proxies, fn ($proxy) => preg_match('~/0+$~', $proxy))) {
            $proxies = [];
        }
        $request::setTrustedProxies($proxies, $this->headers);
    }
}
