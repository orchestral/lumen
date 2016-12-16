<?php

namespace Laravel\Lumen\Foundation;

use Illuminate\Contracts\Foundation\Application;
use Orchestra\Foundation\Foundation as BaseFoundation;

class Foundation extends BaseFoundation
{
    /**
     * Resolve application router.
     *
     * @param \Illuminate\Contracts\Foundation\Application $app
     *
     * @return mixed
     */
    protected function resolveApplicationRouter(Application $app)
    {
        if ($app->bound('api.router')) {
            return $app->make('api.router');
        }

        return $app;
    }
}
