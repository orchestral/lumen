<?php

namespace Laravel\Lumen\Foundation;

use Illuminate\Contracts\Container\Container;
use Orchestra\Foundation\Foundation as BaseFoundation;

class Foundation extends BaseFoundation
{
    /**
     * Resolve application router.
     *
     * @param \Illuminate\Contracts\Container\Container $app
     *
     * @return mixed
     */
    protected function resolveApplicationRouter(Container $app)
    {
        if ($app->bound('api.router')) {
            return $app->make('api.router');
        }

        return $app;
    }
}
