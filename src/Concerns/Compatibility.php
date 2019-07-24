<?php

namespace Laravel\Lumen\Concerns;

use Closure;
use Exception;

trait Compatibility
{
    /**
     * The resource path of the application installation.
     *
     * @var string
     */
    protected $resourcePath;

    /**
     * Register all of the configured providers.
     *
     * @return void
     */
    public function registerConfiguredProviders()
    {
        return;
    }

    /**
     * Get the path to the cached services.json file.
     *
     * @return string
     */
    public function getCachedServicesPath()
    {
        throw new Exception(__FUNCTION__.' is not implemented by Lumen.');
    }

    /**
     * Get the path to the cached extension.json file.
     *
     * @return string
     */
    public function getCachedExtensionServicesPath()
    {
        return $this->basePath('bootstrap/cache/extension.php');
    }

    /**
     * Get the path to the cached packages.php file.
     *
     * @return string
     */
    public function getCachedPackagesPath()
    {
        throw new Exception(__FUNCTION__.' is not implemented by Lumen.');
    }

    /**
     * Get the path to the bootstrap directory.
     *
     * @param  string  $path Optionally, a path to append to the bootstrap path
     *
     * @return string
     */
    public function bootstrapPath($path = '')
    {
        return $this->basePath.DIRECTORY_SEPARATOR.'bootstrap'.($path ? DIRECTORY_SEPARATOR.$path : $path);
    }

    /**
     * Get the path to the application configuration files.
     *
     * @param  string  $path Optionally, a path to append to the config path
     *
     * @return string
     */
    public function configPath($path = '')
    {
        return \config_path($path);
    }

    /**
     * Set the current application locale.
     *
     * @param  string  $locale
     *
     * @return void
     */
    public function setLocale($locale)
    {
        throw new Exception(__FUNCTION__.' is not implemented by Lumen.');
    }

    /**
     * Get the path to the environment file directory.
     *
     * @return string
     */
    public function environmentPath()
    {
        throw new Exception(__FUNCTION__.' is not implemented by Lumen.');
    }

    /**
     * Determine if the application configuration is cached.
     *
     * @return bool
     */
    public function configurationIsCached()
    {
        return false;
    }

    /**
     * Determine if the application events are cached.
     *
     * @return bool
     */
    public function eventsAreCached()
    {
        return false;
    }

    /**
     * Get the path to the configuration cache file.
     *
     * @return string
     */
    public function getCachedConfigPath()
    {
        return $this->bootstrapPath('cache/config.php');
    }

    /**
     * Run the given array of bootstrap classes.
     *
     * @param  array  $bootstrappers
     *
     * @return void
     */
    public function bootstrapWith(array $bootstrappers)
    {
        throw new Exception(__FUNCTION__.' is not implemented by Lumen.');
    }

    /**
     * Detect the application's current environment.
     *
     * @param  \Closure  $callback
     *
     * @return string
     */
    public function detectEnvironment(Closure $callback)
    {
        throw new Exception(__FUNCTION__.' is not implemented by Lumen.');
    }

    /**
     * Get the environment file the application is using.
     *
     * @return string
     */
    public function environmentFile()
    {
        throw new Exception(__FUNCTION__.' is not implemented by Lumen.');
    }

    /**
     * Get the fully qualified path to the environment file.
     *
     * @return string
     */
    public function environmentFilePath()
    {
        throw new Exception(__FUNCTION__.' is not implemented by Lumen.');
    }

    /**
     * Get the path to the routes cache file.
     *
     * @return string
     */
    public function getCachedRoutesPath()
    {
        return $this->bootstrapPath('cache/routes.php');
    }

    /**
     * Get the current application locale.
     *
     * @return string
     */
    public function getLocale()
    {
        throw new Exception(__FUNCTION__.' is not implemented by Lumen.');
    }

    /**
     * Determine if the application has been bootstrapped before.
     *
     * @return bool
     */
    public function hasBeenBootstrapped()
    {
        throw new Exception(__FUNCTION__.' is not implemented by Lumen.');
    }

    /**
     * Load and boot all of the remaining deferred providers.
     *
     * @return void
     */
    public function loadDeferredProviders()
    {
        throw new Exception(__FUNCTION__.' is not implemented by Lumen.');
    }

    /**
     * Set the environment file to be loaded during bootstrapping.
     *
     * @param  string  $file
     *
     * @return $this
     */
    public function loadEnvironmentFrom($file)
    {
        throw new Exception(__FUNCTION__.' is not implemented by Lumen.');
    }
}
