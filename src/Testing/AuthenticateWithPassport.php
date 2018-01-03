<?php

namespace Laravel\Lumen\Testing;

trait AuthenticateWithPassport
{
    /**
     * Passport has been installed.
     *
     * @var bool
     */
    protected $passportHasBeenInstalled = false;

    /**
     * Setup passport.
     *
     * @return void
     */
    protected function setUpPassport()
    {
        $this->artisan('passport:keys');
        $this->artisan('passport:install');
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
