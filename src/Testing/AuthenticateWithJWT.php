<?php

namespace Laravel\Lumen\Testing;

use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Contracts\JWTSubject;

trait AuthenticateWithJWT
{
    /**
     * Get token from user.
     *
     * @param \Tymon\JWTAuth\Contracts\JWTSubject $user
     * @return string
     */
    protected function authorizationBearerFromUser(JWTSubject $user)
    {
        return 'Bearer '.JWTAuth::fromUser($user);
    }
}
