<?php

namespace App\Lumen\Http\Controllers;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Laravel\Lumen\Http\ResponseFactory;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\JWTAuth;

/**
 * User authentication is handled via JSON Web Tokens (JWT).
 *
 * @Resource("Auth", uri="/auth")
 */
class AuthController extends Controller
{
    /**
     * Authenticate the user.
     *
     * API endpoint should return valid JWT credential to be used in following request.
     *
     * Once you have the token, you can append it in other API request (in the request header).
     *
     *     Authorization: Bearer xxx
     *
     * @Post(uri="/")
     * @Request({"email": "hello@orchestraplatform.com", "password": "foobar"}, contentType="application/json")
     * @Transaction({
     *     @Response(200, body={"token": "xxx"}),
     *     @Response(401, body={"error": "invalid_credentials"})
     * })
     */
    public function authenticate(Request $request, ResponseFactory $response, JWTAuth $auth)
    {
        // grab credentials from the request
        $credentials = $request->only('email', 'password');

        try {
            // attempt to verify the credentials and create a token for the user
            if (! $token = $auth->attempt($credentials)) {
                return $response->json(['error' => 'invalid_credentials'], 401);
            }
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return $response->json(['error' => 'could_not_create_token'], 500);
        }

        // all good so return the token
        return $response->json(compact('token'));
    }

    /**
     * Deauthenticate the user.
     *
     * Logout the user and remove any cache associated to the user.
     *
     *     Authorization: Bearer xxx
     *
     * @Delete(uri="/")
     * @Request(contentType="application/json")
     * @Transaction({
     *     @Response(200),
     * })
     */
    public function deauthenticate(ResponseFactory $response, Guard $guard, JWTAuth $auth)
    {
        $guard->logout();
        $auth->invalidate();

        return $response->json([]);
    }

    /**
     * Refresh user token.
     *
     * @Get(uri="refresh")
     * @Request(contentType="application/json")
     * @Transaction({
     *     @Response(200, body={"token": "xxx"}),
     * })
     */
    public function refresh(ResponseFactory $response, JWTAuth $auth)
    {
        try {
            $token = $auth->parseToken()->refresh();
        } catch (JWTException $e) {
            throw new UnauthorizedHttpException('jwt-auth', $e->getMessage());
        }

        return $response->json(['token' => $token]);
    }
}
