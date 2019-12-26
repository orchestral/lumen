<?php

namespace Laravel\Lumen\Auth\Concerns;

use Exception;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

trait JwtGuard
{
    /**
     * Bind authentication for API Token.
     *
     * @return void
     */
    protected function bootJwtGuard(): void
    {
        Auth::viaRequest('jwt', function ($request) {
            try {
                if (! $user = JWTAuth::parseToken()->authenticate()) {
                    return;
                }
            } catch (Exception $e) {
                return;
            }

            return $user;
        });
    }
}
