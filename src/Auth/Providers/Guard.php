<?php

namespace Laravel\Lumen\Auth\Providers;

use Dingo\Api\Routing\Route;
use Illuminate\Http\Request;
use Orchestra\Auth\AuthManager;
use Dingo\Api\Auth\Provider\Authorization;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class Guard extends Authorization
{
    /**
     * Illuminate authentication manager.
     *
     * @var \Orchestra\Auth\AuthManager
     */
    protected $auth;

    /**
     * The guard driver name.
     *
     * @var string
     */
    protected $guard = 'api';

    /**
     * Create a new basic provider instance.
     *
     * @param \Orchestra\Auth\AuthManager $auth
     */
    public function __construct(AuthManager $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Authenticate request with a JWT.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Dingo\Api\Routing\Route $route
     *
     * @return mixed
     */
    public function authenticate(Request $request, Route $route)
    {
        if (! $user = $this->auth->guard($this->guard)->user()) {
            throw new UnauthorizedHttpException('ApiToken', 'Unable to authenticate with invalid API key and token.');
        }

        return $user;
    }

    /**
     * Get the providers authorization method.
     *
     * Note: Not used directly, added just for contract requirement.
     *
     * @return string
     */
    public function getAuthorizationMethod()
    {
        return 'API_TOKEN';
    }
}
