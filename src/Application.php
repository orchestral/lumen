<?php namespace Orchestra\Lumen;

use Orchestra\Config\FileLoader;
use Orchestra\Config\Repository;
use Illuminate\Filesystem\Filesystem;
use Orchestra\Auth\AuthServiceProvider;
use Orchestra\View\ViewServiceProvider;
use Laravel\Lumen\Application as BaseApplication;
use Orchestra\Auth\Passwords\PasswordResetServiceProvider;

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
            return $this->loadComponent('auth', AuthServiceProvider::class, 'auth');
        });

        $this->singleton('auth.driver', function () {
            return $this->loadComponent('auth', AuthServiceProvider::class, 'auth.driver');
        });

        $this->singleton('auth.password', function () {
            return $this->loadComponent('auth', PasswordResetServiceProvider::class, 'auth.password');
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
            return $this->loadComponent('view', ViewServiceProvider::class);
        });
    }
}
