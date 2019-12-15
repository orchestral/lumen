<?php

namespace api;

use Laravel\Lumen\Http\Redirector;
use Laravel\Lumen\Http\ResponseFactory;

if (! \function_exists('api\redirect')) {
    /**
     * Get an instance of the redirector.
     *
     * @param  string|null  $to
     * @param  int  $status
     * @param  array  $headers
     * @param  bool  $secure
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
     * @param  int  $status
     * @param  array  $headers
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
     * @param  array  $parameters
     * @param  bool  $secure
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
     * @param  bool|null  $secure
     *
     * @return string
     */
    function url($path = null, array $parameters = [], $secure = null): string
    {
        return \app('url')->to($path, $parameters, $secure);
    }
}
