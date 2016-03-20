<?php

namespace Laravel\Lumen\Http\Middleware;

use RuntimeException;
use Illuminate\Routing\Middleware\ThrottleRequests as Throttle;

class ThrottleRequests extends Throttle
{
    /**
     * Resolve request signature.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return string
     */
    protected function resolveRequestSignature($request)
    {
        $route = $request->route()[1];

        if (! $route) {
            throw new RuntimeException('Unable to generate fingerprint. Route unavailable.');
        }

        return sha1(
            implode('|', $route['version']).
            '|'.$request->fullUrl().
            '|'.$request->ip()
        );
    }
}
