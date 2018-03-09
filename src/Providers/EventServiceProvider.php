<?php

namespace Laravel\Lumen\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Events\Dispatcher;
use Orchestra\Support\Providers\Traits\EventProvider;

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
     * Register the application's event listeners.
     *
     * @return void
     */
    public function boot(Dispatcher $events)
    {
        $this->registerEventListeners($events);
    }

    /**
     * {@inheritdoc}
     */
    public function register()
    {
        //
    }
}
