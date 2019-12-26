<?php

namespace App\Lumen\Auth\Providers;

use Illuminate\Http\Request;
use Laravel\Passport\Exceptions\OAuthServerException;
use League\OAuth2\Server\Exception\OAuthServerException as LeagueException;

class Passport extends Authorization
{
    /**
     * The guard driver name.
     *
     * @var string
     */
    protected $guard = 'api';

    /**
     * Get the providers authorization method.
     *
     * Note: Not used directly, added just for contract requirement.
     */
    public function getAuthorizationMethod(): string
    {
        return 'bearer';
    }

    /**
     * Authenticate user from request.
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    protected function authenticateUserFromRequest(Request $request)
    {
        try {
            return parent::authenticateUserFromRequest($request);
        } catch (OAuthServerException | LeagueException $exception) {
            throw $this->failedToAuthenticateUser();
        }
    }

    /**
     * Failed to authenticated user message.
     */
    protected function failedToAuthenticateUserMessage(): string
    {
        return 'Unable to authenticate with access token.';
    }
}
