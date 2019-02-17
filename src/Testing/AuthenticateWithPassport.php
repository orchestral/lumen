<?php

namespace Laravel\Lumen\Testing;

trait AuthenticateWithPassport
{
    /**
     * Setup passport.
     *
     * @return void
     */
    protected function setUpPassport()
    {
        $this->artisan('passport:keys')->run();
        $this->artisan('passport:install')->run();
    }

    /**
     * Get token from user.
     *
     * @param \Illuminate\Contracts\Auth\Authenticatable $user
     *
     * @return string
     */
    protected function authorizationBearerUsingPassport($user): string
    {
        $token = $user->createToken('Testing', ['*']);

        return 'Bearer '.$token->accessToken;
    }
}
