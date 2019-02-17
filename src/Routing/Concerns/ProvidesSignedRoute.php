<?php

namespace Laravel\Lumen\Routing\Concerns;

use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\InteractsWithTime;

trait ProvidesSignedRoute
{
    use InteractsWithTime;

    /**
     * The encryption key resolver callable.
     *
     * @var callable
     */
    protected $keyResolver;

    /**
     * Create a signed route URL for a named route.
     *
     * @param  string  $name
     * @param  array  $parameters
     * @param  \DateTimeInterface|int  $expiration
     * @param  bool|null  $secure
     *
     * @return string
     */
    public function signedRoute($name, $parameters = [], $expiration = null, $secure = null)
    {
        $parameters = $this->formatParameters($parameters);

        if ($expiration) {
            $parameters = $parameters + ['expires' => $this->availableAt($expiration)];
        }

        \ksort($parameters);

        $key = \call_user_func($this->keyResolver);

        return $this->route($name, $parameters + [
            'signature' => \hash_hmac('sha256', $this->route($name, $parameters, $secure), $key),
        ], $secure);
    }

    /**
     * Create a temporary signed route URL for a named route.
     *
     * @param  string  $name
     * @param  \DateTimeInterface|int  $expiration
     * @param  array  $parameters
     * @param  bool  $absolute
     *
     * @return string
     */
    public function temporarySignedRoute($name, $expiration, $parameters = [], $absolute = true)
    {
        return $this->signedRoute($name, $parameters, $expiration, $absolute);
    }

    /**
     * Determine if the given request has a valid signature.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return bool
     */
    public function hasValidSignature(Request $request)
    {
        $url = $request->url();

        $original = rtrim($url.'?'.Arr::query(
            Arr::except($request->query(), 'signature')
        ), '?');

        $expires = $request->query()['expires'] ?? null;

        $signature = \hash_hmac('sha256', $original, \call_user_func($this->keyResolver));

        return  \hash_equals($signature, (string) $request->query('signature', '')) &&
               ! ($expires && Carbon::now()->getTimestamp() > $expires);
    }

    /**
     * Set the encryption key resolver.
     *
     * @param  callable  $keyResolver
     *
     * @return $this
     */
    public function setKeyResolver(callable $keyResolver)
    {
        $this->keyResolver = $keyResolver;

        return $this;
    }

    /**
     * Format the array of URL parameters.
     *
     * @param  mixed|array  $parameters
     *
     * @return array
     */
    abstract public function formatParameters($parameters);

    /**
     * Get the URL to a named route.
     *
     * @param  string  $name
     * @param  mixed   $parameters
     * @param  bool|null  $secure
     *
     * @return string
     *
     * @throws \InvalidArgumentException
     */
    abstract public function route($name, $parameters = [], $secure = null);
}
