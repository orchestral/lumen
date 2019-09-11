<?php

namespace Laravel\Lumen\Auth\Providers\Concerns;

use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

trait AuthorizationHelpers
{
    /**
     * Set user resolver to request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     *
     * @return void
     */
    protected function setUserResolverToRequest(Request $request, $user): void
    {
        $request->setUserResolver(static function () use ($user) {
            return $user;
        });
    }

    /**
     * Marked as failed to authenticated user.
     *
     * @return \Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException
     */
    protected function failedToAuthenticateUser(): UnauthorizedHttpException
    {
        return new UnauthorizedHttpException(
            \get_class($this), $this->failedToAuthenticateUserMessage()
        );
    }

    /**
     * Failed to authenticated user message.
     *
     * @return string
     */
    abstract protected function failedToAuthenticateUserMessage(): string;
}
