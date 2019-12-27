<?php

namespace App\Lumen\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Lumen\Auth\Concerns\JwtGuard;
use Laravie\Authen\BootAuthenProvider;

class AuthServiceProvider extends ServiceProvider
{
    use BootAuthenProvider,
        JwtGuard;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Boot the authentication services for the application.
     *
     * @return void
     */
    public function boot()
    {
        $this->bootAuthenProvider();

        // Here you may define how you wish users to be authenticated for your Lumen
        // application. The callback which receives the incoming request instance
        // should return either a User instance or null. You're free to obtain
        // the User instance via an API token or any other method necessary.

        // $this->bootJwtGuard();
    }
}
