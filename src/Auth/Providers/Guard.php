<?php

namespace Laravel\Lumen\Auth\Providers;

use Dingo\Api\Routing\Route;
use Illuminate\Http\Request;
use Illuminate\Auth\AuthManager;
use Dingo\Api\Auth\Provider\Authorization;

abstract class Guard extends Authorization
{
    use Concerns\AuthorizationHelpers;

    /**
     * Illuminate authentication manager.
     *
     * @var \Illuminate\Contracts\Auth\Guard
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
     * @param \Illuminate\Auth\AuthManager $auth
     */
    public function __construct(AuthManager $auth)
    {
        $this->auth = $auth->guard($this->guard);
    }

    /**
     * Authenticate request with a Illuminate Guard.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Dingo\Api\Routing\Route $route
     *
     * @return mixed
     */
    public function authenticate(Request $request, Route $route)
    {
        if (\is_null($user = $this->authenticateUserFromRequest($request))) {
            throw $this->failedToAuthenticateUser();
        }

        $this->setUserResolverToRequest($request, $user);

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

    /**
     * Authenticate user from request.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    protected function authenticateUserFromRequest(Request $request)
    {
        return $this->auth->user();
    }

    /**
     * Failed to authenticated user message.
     *
     * @return string
     */
    protected function failedToAuthenticateUserMessage(): string
    {
        return 'Unable to authenticate with invalid API key and token.';
    }
}
