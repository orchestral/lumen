<?php

namespace App\Lumen\Auth\Providers;

use Laravel\Lumen\Auth\Providers\Guard;

abstract class Authorization extends Guard
{
    /**
     * Get auth authentication guard.
     *
     * @return \Illuminate\Auth\RequestGuard
     */
    public function getAuthGuard()
    {
        return $this->auth;
    }
}
