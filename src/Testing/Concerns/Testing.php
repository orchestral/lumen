<?php

namespace Laravel\Lumen\Testing\Concerns;

use Illuminate\Support\Carbon;
use Mockery;
use Orchestra\Foundation\Testing\Concerns\WithInstallation;
use Orchestra\Foundation\Testing\Installation;

trait Testing
{
    /**
     * The application instance.
     *
     * @var \Laravel\Lumen\Application
     */
    protected $app;

    /**
     * The callbacks that should be run after the application is created.
     *
     * @var array
     */
    protected $afterApplicationCreatedCallbacks = [];

    /**
     * The callbacks that should be run before the application is destroyed.
     *
     * @var array
     */
    protected $beforeApplicationDestroyedCallbacks = [];

    /**
     * Indicates if we have made it through the base setUp function.
     *
     * @var bool
     */
    protected $setUpHasRun = false;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    final protected function setUpTheTestEnvironment()
    {
        if (! $this->app) {
            $this->refreshApplication();
        }

        $this->setUpTraits();

        foreach ($this->afterApplicationCreatedCallbacks as $callback) {
            \call_user_func($callback);
        }

        $this->setUpHasRun = true;
    }

    /**
     * Clean up the testing environment before the next test.
     *
     * @return void
     */
    final protected function tearDownTheTestEnvironment()
    {
        if (\class_exists('Mockery')) {
            if (($container = Mockery::getContainer()) !== null) {
                $this->addToAssertionCount($container->mockery_getExpectationCount());
            }

            Mockery::close();
        }

        if ($this->app) {
            foreach ($this->beforeApplicationDestroyedCallbacks as $callback) {
                \call_user_func($callback);
            }

            $this->app->flush();

            $this->app = null;
        }

        $this->setUpHasRun = false;

        Carbon::setTestNow();

        $this->afterApplicationCreatedCallbacks = [];
        $this->beforeApplicationDestroyedCallbacks = [];
    }

    /**
     * Boot the testing helper traits.
     *
     * @return array
     */
    final protected function setUpTheTestEnvironmentTraits(): array
    {
        $uses = \array_flip(\class_uses_recursive(get_class($this)));

        if (isset($uses[WithInstallation::class]) && isset($uses[Installation::class])) {
            $this->beginInstallation();
        }

        if (isset($uses[DatabaseMigrations::class])) {
            $this->runDatabaseMigrations();
        }

        if (isset($uses[DatabaseTransactions::class])) {
            $this->beginDatabaseTransaction();
        }

        if (isset($uses[WithoutMiddleware::class])) {
            $this->disableMiddlewareForAllTests();
        }

        if (isset($uses[WithoutEvents::class])) {
            $this->disableEventsForAllTests();
        }

        return $uses;
    }

    /**
     * Register a callback to be run after the application is created.
     *
     * @param  callable  $callback
     *
     * @return void
     */
    protected function afterApplicationCreated(callable $callback)
    {
        $this->afterApplicationCreatedCallbacks[] = $callback;

        if ($this->setUpHasRun) {
            \call_user_func($callback);
        }
    }

    /**
     * Register a callback to be run before the application is destroyed.
     *
     * @param  callable  $callback
     *
     * @return void
     */
    protected function beforeApplicationDestroyed(callable $callback)
    {
        \array_unshift($this->beforeApplicationDestroyedCallbacks, $callback);
    }

    /**
     * Boot the testing helper traits.
     *
     * @return array
     */
    abstract protected function setUpTraits();

    /**
     * Refresh the application instance.
     *
     * @return void
     */
    abstract protected function refreshApplication();
}
