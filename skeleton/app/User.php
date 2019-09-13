<?php

namespace App\Lumen;

use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Orchestra\Model\User as Eloquent;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Eloquent implements AuthorizableContract, JWTSubject
{
    use Authorizable;

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
