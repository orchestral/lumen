<?php

namespace Laravel\Lumen\Providers;

use Laravel\Lumen\Foundation\Foundation;
use Laravel\Lumen\Foundation\RouteResolver;
use Illuminate\Contracts\Foundation\Application;
use Orchestra\Extension\ExtensionServiceProvider as ServiceProvider;

class FoundationServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        parent::register();

        $this->registerFoundation();
    }

    /**
     * Register the service provider for foundation.
     *
     * @return void
     */
    protected function registerFoundation()
    {
        $this->app['orchestra.installed'] = false;

        $this->app->singleton('orchestra.app', static function (Application $app) {
            return new Foundation($app, new RouteResolver($app));
        });
    }
}
