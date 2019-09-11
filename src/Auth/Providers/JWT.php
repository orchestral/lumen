<?php

namespace Laravel\Lumen\Auth\Providers;

use Dingo\Api\Routing\Route;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Dingo\Api\Auth\Provider\Authorization;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class JWT extends Authorization
{
    use Concerns\AuthorizationHelpers;

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
        try {
            if (! $user = JWTAuth::parseToken()->authenticate()) {
                throw $this->failedToAuthenticateUser();
            }
        } catch (JWTException $exception) {
            throw new UnauthorizedHttpException('JWTAuth', $exception->getMessage(), $exception);
        }

        $this->setUserResolverToRequest($request, $user);

        return $user;
    }

    /**
     * Get the providers authorization method.
     *
     * @return string
     */
    public function getAuthorizationMethod()
    {
        return 'bearer';
    }

    /**
     * Failed to authenticated user message.
     *
     * @return string
     */
    protected function failedToAuthenticateUserMessage(): string
    {
        return 'Unable to authenticate with invalid token.';
    }
}
