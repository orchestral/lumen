<?php

require_once __DIR__.'/../vendor/autoload.php';

(new Laravel\Lumen\Bootstrap\LoadEnvironmentVariables(
    dirname(__DIR__)
))->bootstrap();

date_default_timezone_set(env('APP_TIMEZONE', 'UTC'));

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| Here we will load the environment and create the application instance
| that serves as the central piece of this framework. We'll use this
| application as an "IoC" container and router for this framework.
|
*/

$app = new Laravel\Lumen\Application(
    $_ENV['APP_BASE_PATH'] ?? dirname(__DIR__)
);

$app->withFacades(false);
$app->withFoundation();
$app->withEloquent();
// $app->withFacadeAliases();

/*
|--------------------------------------------------------------------------
| Register Container Bindings
|--------------------------------------------------------------------------
|
| Now we will register a few bindings in the service container. We will
| register the exception handler and the console kernel. You may add
| your own bindings here if you like or you can make another file.
|
*/

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    App\Lumen\Exceptions\Handler::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Lumen\Console\Kernel::class
);

/*
|--------------------------------------------------------------------------
| Register Middleware
|--------------------------------------------------------------------------
|
| Next, we will register the middleware with the application. These can
| be global middleware that run before and after each request into a
| route or middleware that'll be assigned to some specific routes.
|
*/

// $app->middleware([
//     App\Lumen\Http\Middleware\Cors::class,
// ]);

// $app->routeMiddleware([
//     'auth' => App\Lumen\Http\Middleware\Authenticate::class,
//     'throttle' => Laravel\Lumen\Http\Middleware\ThrottleRequests::class,
// ]);

/*
|--------------------------------------------------------------------------
| Register Service Providers
|--------------------------------------------------------------------------
|
| Here we will register all of the application's service providers which
| are used to bind services into the container. Service providers are
| totally optional, so you are not required to uncomment this line.
|
*/

$app->register(Laravel\Lumen\Providers\FoundationServiceProvider::class);
$app->register(Dingo\Api\Provider\LumenServiceProvider::class);
// $app->register(Tymon\JWTAuth\Providers\LumenServiceProvider::class);
$app->register(App\Lumen\Providers\AppServiceProvider::class);
$app->register(App\Lumen\Providers\AuthServiceProvider::class);
$app->register(App\Lumen\Providers\EventServiceProvider::class);

/*
|--------------------------------------------------------------------------
| Load The Application Routes
|--------------------------------------------------------------------------
|
| Next we will include the routes file so that they can all be added to
| the application. This will provide all of the URLs the application
| can respond to, as well as the controllers that may handle them.
|
*/

$app->router->get('/', function () use ($app) {
    return $app->version();
});

// require base_path('lumen/routes/api.php');

return $app;
