<?php

namespace api;

use Laravel\Lumen\Http\Redirector;
use Laravel\Lumen\Http\ResponseFactory;
use Laravel\Lumen\Routing\UrlGenerator;

if (! function_exists('api\redirect')) {
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
    function redirect($to = null, $status = 302, $headers = [], $secure = null)
    {
        $redirector = new Redirector(app());

        if (is_null($to)) {
            return $redirector;
        }

        return $redirector->to($to, $status, $headers, $secure);
    }
}

if (! function_exists('api\response')) {
    /**
     * Return a new response from the application.
     *
     * @param  object|string  $content
     * @param  int     $status
     * @param  array   $headers
     *
     * @return \Illuminate\Http\Response|\Laravel\Lumen\Http\ResponseFactory
     */
    function response($content = '', $status = 200, array $headers = [])
    {
        $factory = new ResponseFactory();

        if (func_num_args() === 0) {
            return $factory;
        }

        return $factory->make($content, $status, $headers);
    }
}

if (! function_exists('api\route')) {
    /**
     * Generate a URL to a named route.
     *
     * @param  string  $name
     * @param  array   $parameters
     * @param  bool    $secure
     *
     * @return string
     */
    function route($name, $parameters = [], $secure = null)
    {
        return (new UrlGenerator(app()))
                    ->route($name, $parameters, $secure);
    }
}

if (! function_exists('api\url')) {
    /**
     * Generate a url for the application.
     *
     * @param  string  $path
     * @param  mixed   $parameters
     * @param  bool    $secure
     *
     * @return string
     */
    function url($path = null, $parameters = [], $secure = null)
    {
        return (new UrlGenerator(app()))->to($path, $parameters, $secure);
    }
}
