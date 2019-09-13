<?php

namespace App\Lumen\Providers;

use App\Lumen\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthServiceProvider extends ServiceProvider
{
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
        // Here you may define how you wish users to be authenticated for your Lumen
        // application. The callback which receives the incoming request instance
        // should return either a User instance or null. You're free to obtain
        // the User instance via an API token or any other method necessary.

        // $this->bindAuthForApiToken();
        // $this->bindAuthForJwtToken();
    }

    /**
     * Bind authentication for API Token.
     *
     * @return void
     */
    protected function bindAuthForApiToken()
    {
        Auth::viaRequest('api', function ($request) {
            if ($request->input('api_token')) {
                return User::where('api_token', $request->input('api_token'))->first();
            }
        });
    }

    /**
     * Bind authentication for JWT Token.
     *
     * @return void
     */
    protected function bindAuthForJwtToken()
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
