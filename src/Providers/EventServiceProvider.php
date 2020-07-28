<?php

namespace Laravel\Lumen\Providers;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\ServiceProvider;
use Orchestra\Support\Providers\Concerns\EventProvider;

class EventServiceProvider extends ServiceProvider
{
    use EventProvider;

    /**
     * The event handler mappings for the application.
     *
     * @var array
     */
    protected $listen = [];

    /**
     * The subscriber classes to register.
     *
     * @var array
     */
    protected $subscribe = [];

    /**
     * {@inheritdoc}
     */
    public function register()
    {
        //
    }

    /**
     * Register the application's event listeners.
     *
     * @return void
     */
    public function boot(Dispatcher $events)
    {
        $this->registerEventListeners($events);
    }

    /**
     * Get the events and handlers.
     *
     * @return array
     */
    public function listens()
    {
        return $this->listen;
    }
}
