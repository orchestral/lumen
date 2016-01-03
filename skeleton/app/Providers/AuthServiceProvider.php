<?php namespace App\Lumen\Providers;

use Tymon\JWTAuth\Providers\LumenServiceProvider;

class AuthServiceProvider extends LumenServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $app->singleton('cookie', function ($app) {
            return $app->loadComponent('session', Illuminate\Cookie\CookieServiceProvider::class, 'cookie');
        });

        parent::register();
    }
}
