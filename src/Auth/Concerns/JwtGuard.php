<?php

namespace Laravel\Lumen\Auth\Concerns;

use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

trait JwtGuard
{
    /**
     * Bind authentication for API Token.
     *
     * @param  string  $name
     *
     * @return void
     */
    protected function bootJwtGuard(string $name = 'jwt'): void
    {
        Auth::viaRequest($name, static function ($request) {
            return \rescue(static function () {
                return JWTAuth::parseToken()->authenticate();
            });
        });
    }
}
