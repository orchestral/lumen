<?php

namespace Laravel\Lumen\Testing;

use Tymon\JWTAuth\Facades\JWTAuth;
use Orchestra\Foundation\Auth\User;

trait AuthenticateWithJWT
{
    /**
     * Get token from user.
     *
     * @param \Katsana\Model\User $user
     * @return string
     */
    protected function authorizationBearerFromUser(User $user)
    {
        return 'Bearer '.JWTAuth::fromUser($user);
    }
}
