<?php

namespace api;

use PhpOption\Option;
use Laravel\Lumen\Http\Redirector;
use Dotenv\Environment\DotenvFactory;
use Laravel\Lumen\Http\ResponseFactory;
use Dotenv\Environment\Adapter\PutenvAdapter;
use Dotenv\Environment\Adapter\EnvConstAdapter;
use Dotenv\Environment\Adapter\ServerConstAdapter;

if (! \function_exists('env')) {
    /**
     * Gets the value of an environment variable.
     *
     * @param  string  $key
     * @param  mixed   $default
     *
     * @return mixed
     */
    function env($key, $default = null)
    {
        static $variables;

        if ($variables === null) {
            $variables = (new DotenvFactory([new EnvConstAdapter(), new PutenvAdapter(), new ServerConstAdapter()]))
                ->createImmutable();
        }

        return Option::fromValue($variables->get($key))
            ->map(static function ($value) {
                switch (\strtolower($value)) {
                    case 'true':
                    case '(true)':
                        return true;
                    case 'false':
                    case '(false)':
                        return false;
                    case 'empty':
                    case '(empty)':
                        return '';
                    case 'null':
                    case '(null)':
                        return;
                }

                return $value;
            })
            ->getOrCall(static function () use ($default) {
                return \value($default);
            });
    }
}

if (! \function_exists('api\redirect')) {
    /**
     * Get an instance of the redirector.
     *
     * @param  string|null  $to
     * @param  int     $status
     * @param  array   $headers
     * @param  bool    $secure
     *
     * @return \Laravel\Lumen\Http\Redirector|\Illuminate\Http\RedirectResponse
     */
    function redirect($to = null, int $status = 302, array $headers = [], $secure = null)
    {
        $redirector = new Redirector(\app());

        if (\is_null($to)) {
            return $redirector;
        }

        return $redirector->to($to, $status, $headers, $secure);
    }
}

if (! \function_exists('api\response')) {
    /**
     * Return a new response from the application.
     *
     * @param  object|string  $content
     * @param  int     $status
     * @param  array   $headers
     *
     * @return \Illuminate\Http\Response|\Laravel\Lumen\Http\ResponseFactory
     */
    function response($content = '', int $status = 200, array $headers = [])
    {
        $factory = new ResponseFactory();

        if (\func_num_args() === 0) {
            return $factory;
        }

        return $factory->make($content, $status, $headers);
    }
}

if (! \function_exists('api\route')) {
    /**
     * Generate a URL to a named route.
     *
     * @param  string  $name
     * @param  array   $parameters
     * @param  bool    $secure
     *
     * @return string
     */
    function route($name, array $parameters = [], $secure = null): string
    {
        return \app('url')->route($name, $parameters, $secure);
    }
}

if (! \function_exists('api\url')) {
    /**
     * Generate a url for the application.
     *
     * @param  string  $path
     * @param  array  $parameters
     * @param  bool  $secure
     *
     * @return string
     */
    function url($path = null, array $parameters = [], $secure = null): string
    {
        return \app('url')->to($path, $parameters, $secure);
    }
}
