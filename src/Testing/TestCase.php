<?php

namespace Laravel\Lumen\Testing;

use Mockery;
use Exception;
use Illuminate\Support\Facades\Facade;
use Illuminate\Contracts\Auth\Authenticatable;
use PHPUnit\Framework\TestCase as BaseTestCase;
use Illuminate\Contracts\Console\Kernel as ConsoleKernel;

abstract class TestCase extends BaseTestCase
{
    use Concerns\MakesHttpRequests,
        Concerns\Testing;

    /**
     * The base URL to use while testing the application.
     *
     * @var string
     */
    protected $baseUrl = 'http://localhost';

    /**
     * Creates the application.
     *
     * Needs to be implemented by subclasses.
     *
     * @return \Symfony\Component\HttpKernel\HttpKernelInterface
     */
    abstract public function createApplication();

    /**
     * Refresh the application instance.
     *
     * @return void
     */
    protected function refreshApplication()
    {
        putenv('APP_ENV=testing');

        Facade::clearResolvedInstances();

        $this->app = $this->createApplication();

        $this->app->boot();
    }

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp()
    {
        $this->setUpTheTestEnvironment();
    }

    /**
     * Boot the testing helper traits.
     *
     * @return array
     */
    protected function setUpTraits()
    {
        return $this->setUpTheTestEnvironmentTraits();
    }

    /**
     * Clean up the testing environment before the next test.
     *
     * @return void
     */
    protected function tearDown()
    {
        $this->tearDownTheTestEnvironment();
    }

    /**
     * Assert that a given where condition exists in the database.
     *
     * @param  string  $table
     * @param  array  $data
     * @param  string|null $onConnection
     *
     * @return $this
     */
    protected function seeInDatabase($table, array $data, $onConnection = null)
    {
        $count = $this->app->make('db')->connection($onConnection)->table($table)->where($data)->count();

        $this->assertGreaterThan(0, $count, sprintf(
            'Unable to find row in database table [%s] that matched attributes [%s].', $table, json_encode($data)
        ));

        return $this;
    }

    /**
     * Assert that a given where condition does not exist in the database.
     *
     * @param  string  $table
     * @param  array  $data
     * @param  string|null $onConnection
     *
     * @return $this
     */
    protected function missingFromDatabase($table, array $data, $onConnection = null)
    {
        return $this->notSeeInDatabase($table, $data, $onConnection);
    }

    /**
     * Assert that a given where condition does not exist in the database.
     *
     * @param  string  $table
     * @param  array  $data
     * @param  string|null $onConnection
     *
     * @return $this
     */
    protected function notSeeInDatabase($table, array $data, $onConnection = null)
    {
        $count = $this->app->make('db')->connection($onConnection)->table($table)->where($data)->count();

        $this->assertEquals(0, $count, sprintf(
            'Found unexpected records in database table [%s] that matched attributes [%s].', $table, json_encode($data)
        ));

        return $this;
    }

    /**
     * Specify a list of events that should be fired for the given operation.
     *
     * These events will be mocked, so that handlers will not actually be executed.
     *
     * @param  array|string  $events
     *
     * @return $this
     */
    public function expectsEvents($events)
    {
        $events = is_array($events) ? $events : func_get_args();

        $mock = Mockery::spy('Illuminate\Contracts\Events\Dispatcher');

        $mock->shouldReceive('fire', 'dispatch')->andReturnUsing(function ($called) use (&$events) {
            foreach ($events as $key => $event) {
                if ((is_string($called) && $called === $event) ||
                    (is_string($called) && is_subclass_of($called, $event)) ||
                    (is_object($called) && $called instanceof $event)) {
                    unset($events[$key]);
                }
            }
        });

        $this->beforeApplicationDestroyed(function () use (&$events) {
            if ($events) {
                throw new Exception(
                    'The following events were not fired: ['.implode(', ', $events).']'
                );
            }
        });

        $this->app->instance('events', $mock);

        return $this;
    }

    /**
     * Mock the event dispatcher so all events are silenced.
     *
     * @return $this
     */
    protected function withoutEvents()
    {
        $mock = Mockery::mock('Illuminate\Contracts\Events\Dispatcher');

        $mock->shouldReceive('fire', 'dispatch');

        $this->app->instance('events', $mock);

        return $this;
    }

    /**
     * Specify a list of jobs that should be dispatched for the given operation.
     *
     * These jobs will be mocked, so that handlers will not actually be executed.
     *
     * @param  array|string  $jobs
     *
     * @return $this
     */
    protected function expectsJobs($jobs)
    {
        $jobs = is_array($jobs) ? $jobs : func_get_args();

        unset($this->app->availableBindings['Illuminate\Contracts\Bus\Dispatcher']);

        $mock = Mockery::mock('Illuminate\Bus\Dispatcher[dispatch]', [$this->app]);

        foreach ($jobs as $job) {
            $mock->shouldReceive('dispatch')->atLeast()->once()
                ->with(Mockery::type($job));
        }

        $this->app->instance(
            'Illuminate\Contracts\Bus\Dispatcher', $mock
        );

        return $this;
    }

    /**
     * Mock the job dispatcher so all jobs are silenced and collected.
     *
     * @return $this
     */
    protected function withoutJobs()
    {
        unset($this->app->availableBindings['Illuminate\Contracts\Bus\Dispatcher']);

        $mock = Mockery::mock('Illuminate\Bus\Dispatcher[dispatch]', [$this->app]);

        $mock->shouldReceive('dispatch')->andReturnUsing(function ($dispatched) {
            $this->dispatchedJobs[] = $dispatched;
        });

        $this->app->instance(
            'Illuminate\Contracts\Bus\Dispatcher', $mock
        );

        return $this;
    }

    /**
     * Set the currently logged in user for the application.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  string|null  $driver
     *
     * @return $this
     */
    public function actingAs(Authenticatable $user, $driver = null)
    {
        $this->be($user, $driver);

        return $this;
    }

    /**
     * Set the currently logged in user for the application.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  string|null  $driver
     *
     * @return void
     */
    public function be(Authenticatable $user, $driver = null)
    {
        $this->app['auth']->guard($driver)->setUser($user);
    }

    /**
     * Call artisan command and return code.
     *
     * @param string  $command
     * @param array   $parameters
     *
     * @return int
     */
    public function artisan($command, $parameters = [])
    {
        return $this->code = $this->app[ConsoleKernel::class]->call($command, $parameters);
    }
}
