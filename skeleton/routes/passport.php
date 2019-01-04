<?php

/*
|--------------------------------------------------------------------------
| OAuth2 API Routes
|--------------------------------------------------------------------------
*/

$app->router->group(['namespace' => 'Laravel\Passport\Http\Controllers'], function ($router) {
    $router->post('oauth/token', [
        'uses' => 'AccessTokenController@issueToken',
    ]);
});
