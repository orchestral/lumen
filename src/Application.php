<?php namespace Orchestra\Lumen;

use Orchestra\Config\FileLoader;
use Orchestra\Config\Repository;
use Illuminate\Filesystem\Filesystem;
use Laravel\Lumen\Application as BaseApplication;

class Application extends BaseApplication
{
    /**
     * Register container bindings for the application.
     *
     * @return void
     */
    protected function registerAuthBindings()
    {
        $this->singleton('auth', function () {
            return $this->loadComponent('auth', 'Orchestra\Auth\AuthServiceProvider', 'auth');
        });

        $this->singleton('auth.driver', function () {
            return $this->loadComponent('auth', 'Orchestra\Auth\AuthServiceProvider', 'auth.driver');
        });

        $this->singleton('auth.password', function () {
            return $this->loadComponent('auth', 'Orchestra\Auth\Passwords\PasswordResetServiceProvider', 'auth.password');
        });
    }

    /**
     * Register container bindings for the application.
     *
     * @return void
     */
    protected function registerConfigBindings()
    {
        $loader = new FileLoader(new Filesystem(), $this->configPath ?: $this->resourcePath('config'));

        $app->instance('config', $config = new Repository($loader, $this->environment()));
    }

    /**
     * Register container bindings for the application.
     *
     * @return void
     */
    protected function registerViewBindings()
    {
        $this->singleton('view', function () {
            return $this->loadComponent('view', 'Orchestra\View\ViewServiceProvider');
        });
    }
}
