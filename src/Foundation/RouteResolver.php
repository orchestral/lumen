<?php

namespace Laravel\Lumen\Foundation;

use Orchestra\Foundation\RouteResolver as BaseRouteResolver;

class RouteResolver extends BaseRouteResolver
{
    /**
     * {@inheritdoc}
     */
    protected function generateRouteByName(string $name, string $default)
    {
        $prefix = '/';

        // Orchestra Platform routing is managed by `orchestra/foundation::handles`
        // and can be manage using configuration.
        if (! in_array($name, ['api', 'app'])) {
            return parent::generateRouteByName($name, $default);
        }

        return $this->app->make('orchestra.extension.url')
                    ->handle($prefix)
                    ->setBaseUrl(config($name === 'api' ? 'app.api' : 'app.url'));
    }
}
