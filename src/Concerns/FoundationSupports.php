<?php

namespace Laravel\Lumen\Concerns;

use Orchestra\Foundation\Bootstrap\UserAccessPolicy;

trait FoundationSupports
{
    /**
     * Bootstrap Orchestra Platform Foundation.
     *
     * @return $this
     */
    public function withFoundation()
    {
        (new UserAccessPolicy())->bootstrap($this);

        $this->registerMemoryBindings();
        $this->registerAuthorizationBindings();

        return $this;
    }

    /**
     * Get the path to the lumen directory.
     *
     * @param  string|null  $path
     *
     * @return string
     */
    public function lumenPath($path = null)
    {
        return $this->basePath('lumen'.($path ? '/'.$path : $path));
    }

    /**
     * Get the base path for the application.
     *
     * @param  string|null  $path
     *
     * @return string
     */
    abstract public function basePath($path = null);

    /**
     * Register container bindings for the application.
     *
     * @return void
     */
    abstract public function registerAuthorizationBindings();

    /**
     * Register container bindings for the application.
     *
     * @return void
     */
    abstract public function registerMemoryBindings();
}
