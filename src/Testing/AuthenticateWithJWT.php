<?php

namespace Laravel\Lumen\Testing;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Tymon\JWTAuth\Facades\JWTAuth;

trait AuthenticateWithJWT
{
    /**
     * Get token from user.
     *
     * @param \Tymon\JWTAuth\Contracts\JWTSubject $user
     *
     * @return string
     */
    protected function authorizationBearerUsingJwt(JWTSubject $user): string
    {
        return 'Bearer '.JWTAuth::fromUser($user);
    }

    /**
     * Get token from user.
     *
     * @param \Tymon\JWTAuth\Contracts\JWTSubject $user
     *
     * @return string
     *
     * @deprecated v3.5.1
     */
    protected function authorizationBearerFromUser(JWTSubject $user): string
    {
        return $this->authorizationBearerUsingJwt($user);
    }
}
