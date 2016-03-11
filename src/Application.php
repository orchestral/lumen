<?php namespace Laravel\Lumen;

use Closure;
use Exception;
use Throwable;
use RuntimeException;
use FastRoute\Dispatcher;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Container\Container;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Exception\HttpResponseException;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Contracts\Foundation\Application as ApplicationContract;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class Application extends Container implements ApplicationContract, HttpKernelInterface
{
    use Concerns\CoreBindings,
        Concerns\FoundationSupports,
        Concerns\RegistersExceptionHandlers;

    /**
     * Indicates if the application has "booted".
     *
     * @var bool
     */
    protected $booted = false;

    /**
     * The array of booting callbacks.
     *
     * @var array
     */
    protected $bootingCallbacks = [];

    /**
     * The array of booted callbacks.
     *
     * @var array
     */
    protected $bootedCallbacks = [];

    /**
     * The array of terminating callbacks.
     *
     * @var array
     */
    protected $terminatingCallbacks = [];

    /**
     * Indicates if the class aliases have been registered.
     *
     * @var bool
     */
    protected static $aliasesRegistered = false;

    /**
     * The base path of the application installation.
     *
     * @var string
     */
    protected $basePath;

    /**
     * The storage path of the application installation.
     *
     * @var string
     */
    protected $storagePath;

    /**
     * The configuration path of the application installation.
     *
     * @var string
     */
    protected $configPath;

    /**
     * The resource path of the application installation.
     *
     * @var string
     */
    protected $resourcePath;

    /**
     * All of the loaded configuration files.
     *
     * @var array
     */
    protected $loadedConfigurations = [];

    /**
     * All of the routes waiting to be registered.
     *
     * @var array
     */
    protected $routes = [];

    /**
     * All of the named routes and URI pairs.
     *
     * @var array
     */
    public $namedRoutes = [];

    /**
     * The shared attributes for the current route group.
     *
     * @var array|null
     */
    protected $groupAttributes;

    /**
     * All of the global middleware for the application.
     *
     * @var array
     */
    protected $middleware = [];

    /**
     * All of the route specific middleware short-hands.
     *
     * @var array
     */
    protected $routeMiddleware = [];

    /**
     * The current route being dispatched.
     *
     * @var array
     */
    protected $currentRoute;

    /**
     * The loaded service providers.
     *
     * @var array
     */
    protected $loadedProviders = [];

    /**
     * The service binding methods that have been executed.
     *
     * @var array
     */
    protected $ranServiceBinders = [];

    /**
     * The FastRoute dispatcher.
     *
     * @var \FastRoute\Dispatcher
     */
    protected $dispatcher;

    /**
     * The application namespace.
     *
     * @var string
     */
    protected $namespace;

    /**
     * Create a new Lumen application instance.
     *
     * @param  string|null  $basePath
     */
    public function __construct($basePath = null)
    {
        date_default_timezone_set(env('APP_TIMEZONE', 'UTC'));

        $this->basePath = $basePath;
        $this->bootstrapContainer();
        $this->registerErrorHandling();
    }

    /**
     * Bootstrap the application container.
     *
     * @return void
     */
    protected function bootstrapContainer()
    {
        static::setInstance($this);

        $this->instance('app', $this);
        $this->instance('path', $this->path());
        $this->instance('path.config', $this->configPath ?: $this->basePath('resources/config'));
        $this->instance('path.storage', $this->storagePath());

        $this->registerContainerAliases();
    }

    /**
     * Get the version number of the application.
     *
     * @return string
     */
    public function version()
    {
        return 'Lumen (5.1.6) (Laravel Components 5.1.*)';
    }

    /**
     * Get or check the current application environment.
     *
     * @param  mixed
     *
     * @return string
     */
    public function environment()
    {
        $env = env('APP_ENV', 'production');

        if (func_num_args() > 0) {
            $patterns = is_array(func_get_arg(0)) ? func_get_arg(0) : func_get_args();

            foreach ($patterns as $pattern) {
                if (Str::is($pattern, $env)) {
                    return true;
                }
            }

            return false;
        }

        return $env;
    }

    /**
     * Determine if the application is currently down for maintenance.
     *
     * @return bool
     */
    public function isDownForMaintenance()
    {
        return file_exists($this->storagePath().'/framework/down');
    }

    /**
     * Register all of the configured providers.
     *
     * @return void
     */
    public function registerConfiguredProviders()
    {
        //
    }

    /**
     * Get the path to the cached "compiled.php" file.
     *
     * @return string
     */
    public function getCachedCompilePath()
    {
        throw new Exception(__FUNCTION__.' is not implemented by Lumen.');
    }

    /**
     * Get the path to the cached services.json file.
     *
     * @return string
     */
    public function getCachedServicesPath()
    {
        throw new Exception(__FUNCTION__.' is not implemented by Lumen.');
    }

    /**
     * Register a service provider with the application.
     *
     * @param  \Illuminate\Support\ServiceProvider|string  $provider
     * @param  array  $options
     * @param  bool   $force
     *
     * @return void
     */
    public function register($provider, $options = [], $force = false)
    {
        if (! $provider instanceof ServiceProvider) {
            $provider = new $provider($this);
        }

        if (array_key_exists($providerName = get_class($provider), $this->loadedProviders)) {
            return;
        }

        $this->loadedProviders[$providerName] = true;

        $provider->register();

        if (method_exists($provider, 'boot')) {
            $this->call([$provider, 'boot']);
        }
    }

    /**
     * Register a deferred provider and service.
     *
     * @param  string  $provider
     * @param  string|null  $service
     *
     * @return void
     */
    public function registerDeferredProvider($provider, $service = null)
    {
        return $this->register($provider);
    }

    /**
     * Boot the application's service providers.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->booted) {
            return;
        }

        // Once the application has booted we will also fire some "booted" callbacks
        // for any listeners that need to do work after this initial booting gets
        // finished. This is useful when ordering the boot-up processes we run.
        $this->fireAppCallbacks($this->bootingCallbacks);

        $this->booted = true;

        $this->fireAppCallbacks($this->bootedCallbacks);
    }

    /**
     * Register a new boot listener.
     *
     * @param  mixed  $callback
     *
     * @return void
     */
    public function booting($callback)
    {
        $this->bootingCallbacks[] = $callback;
    }

    /**
     * Register a new "booted" listener.
     *
     * @param  mixed  $callback
     *
     * @return void
     */
    public function booted($callback)
    {
        $this->bootedCallbacks[] = $callback;

        if ($this->booted) {
            $this->fireAppCallbacks([$callback]);
        }
    }

    /**
     * Register a terminating callback with the application.
     *
     * @param  \Closure  $callback
     *
     * @return $this
     */
    public function terminating(Closure $callback)
    {
        $this->terminatingCallbacks[] = $callback;

        return $this;
    }

    /**
     * Call the booting callbacks for the application.
     *
     * @param  array  $callbacks
     *
     * @return void
     */
    protected function fireAppCallbacks(array $callbacks)
    {
        foreach ($callbacks as $callback) {
            call_user_func($callback, $this);
        }
    }

    /**
     * Resolve the given type from the container.
     *
     * @param  string  $abstract
     * @param  array   $parameters
     *
     * @return mixed
     */
    public function make($abstract, array $parameters = [])
    {
        if (array_key_exists($abstract, $this->availableBindings) &&
            ! array_key_exists($this->availableBindings[$abstract], $this->ranServiceBinders)) {
            $this->{$method = $this->availableBindings[$abstract]}();

            $this->ranServiceBinders[$method] = true;
        }

        return parent::make($abstract, $parameters);
    }

    /**
     * Get the path to the application's language files.
     *
     * @return string
     */
    protected function getLanguagePath()
    {
        return $this->resourcePath('lang');
    }

    /**
     * Configure and load the given component and provider.
     *
     * @param  string  $config
     * @param  array|string  $providers
     * @param  string|null  $return
     *
     * @return mixed
     */
    public function loadComponent($config, $providers, $return = null)
    {
        $this->configure($config);

        foreach ((array) $providers as $provider) {
            $this->register($provider);
        }

        return $this->make($return ?: $config);
    }

    /**
     * Load a configuration file into the application.
     *
     * @param  string  $name
     *
     * @return void
     */
    public function configure($name)
    {
        if (isset($this->loadedConfigurations[$name])) {
            return;
        }

        $this->loadedConfigurations[$name] = true;

        $path = $this->getConfigurationPath($name);

        if (! is_null($path)) {
            $this->make('config')->set($name, require $path);
        }
    }

    /**
     * Get the path to the given configuration file.
     *
     * If no name is provided, then we'll return the path to the config folder.
     *
     * @param  string|null  $name
     *
     * @return string|null
     */
    public function getConfigurationPath($name = null)
    {
        if (is_null($name)) {
            return config_path();
        } elseif (file_exists($path = $this->basePath('lumen/config/').$name.'.php')) {
            return $path;
        }
    }

    /**
     * Register the facades for the application.
     *
     * @return $this
     */
    public function withFacades()
    {
        Facade::setFacadeApplication($this);

        if (! static::$aliasesRegistered) {
            static::$aliasesRegistered = true;

            class_alias('Illuminate\Support\Facades\App', 'App');
            class_alias('Illuminate\Support\Facades\Auth', 'Auth');
            class_alias('Illuminate\Support\Facades\Bus', 'Bus');
            class_alias('Illuminate\Support\Facades\DB', 'DB');
            class_alias('Illuminate\Support\Facades\Cache', 'Cache');
            class_alias('Illuminate\Support\Facades\Cookie', 'Cookie');
            class_alias('Illuminate\Support\Facades\Crypt', 'Crypt');
            class_alias('Illuminate\Support\Facades\Event', 'Event');
            class_alias('Illuminate\Support\Facades\Hash', 'Hash');
            class_alias('Illuminate\Support\Facades\Log', 'Log');
            class_alias('Illuminate\Support\Facades\Mail', 'Mail');
            class_alias('Illuminate\Support\Facades\Queue', 'Queue');
            class_alias('Illuminate\Support\Facades\Request', 'Request');
            class_alias('Illuminate\Support\Facades\Schema', 'Schema');
            class_alias('Illuminate\Support\Facades\Session', 'Session');
            class_alias('Illuminate\Support\Facades\Storage', 'Storage');
            class_alias('Illuminate\Support\Facades\Validator', 'Validator');
        }

        return $this;
    }

    /**
     * Load the Eloquent library for the application.
     *
     * @return $this
     */
    public function withEloquent()
    {
        $this->make('db');

        return $this;
    }

    /**
     * Register a set of routes with a set of shared attributes.
     *
     * @param  array  $attributes
     * @param  \Closure  $callback
     *
     * @return void
     */
    public function group(array $attributes, Closure $callback)
    {
        $parentGroupAttributes = $this->groupAttributes;

        if (isset($attributes['middleware']) && is_string($attributes['middleware'])) {
            $attributes['middleware'] = explode('|', $attributes['middleware']);
        }

        $this->groupAttributes = $attributes;

        call_user_func($callback, $this);

        $this->groupAttributes = $parentGroupAttributes;
    }

    /**
     * Register a route with the application.
     *
     * @param  string  $uri
     * @param  mixed  $action
     *
     * @return $this
     */
    public function get($uri, $action)
    {
        $this->addRoute('GET', $uri, $action);

        return $this;
    }

    /**
     * Register a route with the application.
     *
     * @param  string  $uri
     * @param  mixed  $action
     *
     * @return $this
     */
    public function post($uri, $action)
    {
        $this->addRoute('POST', $uri, $action);

        return $this;
    }

    /**
     * Register a route with the application.
     *
     * @param  string  $uri
     * @param  mixed  $action
     *
     * @return $this
     */
    public function put($uri, $action)
    {
        $this->addRoute('PUT', $uri, $action);

        return $this;
    }

    /**
     * Register a route with the application.
     *
     * @param  string  $uri
     * @param  mixed  $action
     *
     * @return $this
     */
    public function patch($uri, $action)
    {
        $this->addRoute('PATCH', $uri, $action);

        return $this;
    }

    /**
     * Register a route with the application.
     *
     * @param  string  $uri
     * @param  mixed  $action
     *
     * @return $this
     */
    public function delete($uri, $action)
    {
        $this->addRoute('DELETE', $uri, $action);

        return $this;
    }

    /**
     * Register a route with the application.
     *
     * @param  string  $uri
     * @param  mixed  $action
     *
     * @return $this
     */
    public function options($uri, $action)
    {
        $this->addRoute('OPTIONS', $uri, $action);

        return $this;
    }

    /**
     * Add a route to the collection.
     *
     * @param  string  $method
     * @param  string  $uri
     * @param  mixed  $action
     */
    public function addRoute($method, $uri, $action)
    {
        $action = $this->parseAction($action);

        if (isset($this->groupAttributes)) {
            if (isset($this->groupAttributes['prefix'])) {
                $uri = trim($this->groupAttributes['prefix'], '/').'/'.trim($uri, '/');
            }

            $action = $this->mergeGroupAttributes($action);
        }

        $uri = '/'.trim($uri, '/');

        if (isset($action['as'])) {
            $this->namedRoutes[$action['as']] = $uri;
        }

        $this->routes[$method.$uri] = ['method' => $method, 'uri' => $uri, 'action' => $action];
    }

    /**
     * Parse the action into an array format.
     *
     * @param  mixed  $action
     *
     * @return array
     */
    protected function parseAction($action)
    {
        if (is_string($action)) {
            return ['uses' => $action];
        } elseif (! is_array($action)) {
            return [$action];
        }

        if (isset($action['middleware']) && is_string($action['middleware'])) {
            $action['middleware'] = explode('|', $action['middleware']);
        }

        return $action;
    }

    /**
     * Merge the group attributes into the action.
     *
     * @param  array  $action
     *
     * @return array
     */
    protected function mergeGroupAttributes(array $action)
    {
        return $this->mergeNamespaceGroup(
            $this->mergeMiddlewareGroup($action)
        );
    }

    /**
     * Merge the namespace group into the action.
     *
     * @param  array  $action
     *
     * @return array
     */
    protected function mergeNamespaceGroup(array $action)
    {
        if (isset($this->groupAttributes['namespace']) && isset($action['uses'])) {
            $action['uses'] = $this->groupAttributes['namespace'].'\\'.$action['uses'];
        }

        return $action;
    }

    /**
     * Merge the middleware group into the action.
     *
     * @param  array  $action
     *
     * @return array
     */
    protected function mergeMiddlewareGroup($action)
    {
        if (isset($this->groupAttributes['middleware'])) {
            if (isset($action['middleware'])) {
                $action['middleware'] = array_merge($this->groupAttributes['middleware'], $action['middleware']);
            } else {
                $action['middleware'] = $this->groupAttributes['middleware'];
            }
        }

        return $action;
    }

    /**
     * Add new middleware to the application.
     *
     * @param  array  $middleware
     *
     * @return $this
     */
    public function middleware(array $middleware)
    {
        $this->middleware = array_unique(array_merge($this->middleware, $middleware));

        return $this;
    }

    /**
     * Define the route middleware for the application.
     *
     * @param  array  $middleware
     *
     * @return $this
     */
    public function routeMiddleware(array $middleware)
    {
        $this->routeMiddleware = array_merge($this->routeMiddleware, $middleware);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function handle(SymfonyRequest $request, $type = HttpKernelInterface::MASTER_REQUEST, $catch = true)
    {
        return $this->dispatch($request);
    }

    /**
     * Run the application and send the response.
     *
     * @param  SymfonyRequest|null  $request
     *
     * @return void
     */
    public function run($request = null)
    {
        $response = $this->dispatch($request);

        if ($response instanceof SymfonyResponse) {
            $response->send();
        } else {
            echo (string) $response;
        }

        if (count($this->middleware) > 0) {
            $this->callTerminableMiddleware($response);
        }
    }

    /**
     * Call the terminable middleware.
     *
     * @param  mixed  $response
     *
     * @return void
     */
    protected function callTerminableMiddleware($response)
    {
        $response = $this->prepareResponse($response);

        foreach ($this->middleware as $middleware) {
            $instance = $this->make($middleware);

            if (method_exists($instance, 'terminate')) {
                $instance->terminate($this->make('request'), $response);
            }
        }
    }

    /**
     * Dispatch the incoming request.
     *
     * @param  SymfonyRequest|null  $request
     *
     * @return Response
     */
    public function dispatch($request = null)
    {
        if ($request) {
            $this->instance('Illuminate\Http\Request', $request);
            $this->ranServiceBinders['registerRequestBindings'] = true;

            $method   = $request->getMethod();
            $pathInfo = $request->getPathInfo();
        } else {
            $method   = $this->getMethod();
            $pathInfo = $this->getPathInfo();
        }

        try {
            return $this->sendThroughPipeline($this->middleware, function () use ($method, $pathInfo) {
                if (isset($this->routes[$method.$pathInfo])) {
                    return $this->handleFoundRoute([true, $this->routes[$method.$pathInfo]['action'], []]);
                }

                return $this->handleDispatcherResponse(
                    $this->createDispatcher()->dispatch($method, $pathInfo)
                );
            });
        } catch (Exception $e) {
            return $this->sendExceptionToHandler($e);
        } catch (Throwable $e) {
            return $this->sendExceptionToHandler($e);
        }
    }

    /**
     * Create a FastRoute dispatcher instance for the application.
     *
     * @return Dispatcher
     */
    protected function createDispatcher()
    {
        return $this->dispatcher ?: \FastRoute\simpleDispatcher(function ($r) {
            foreach ($this->routes as $route) {
                $r->addRoute($route['method'], $route['uri'], $route['action']);
            }
        });
    }

    /**
     * Set the FastRoute dispatcher instance.
     *
     * @param  \FastRoute\Dispatcher  $dispatcher
     *
     * @return void
     */
    public function setDispatcher(Dispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * Handle the response from the FastRoute dispatcher.
     *
     * @param  array  $routeInfo
     *
     * @return mixed
     */
    protected function handleDispatcherResponse($routeInfo)
    {
        switch ($routeInfo[0]) {
            case Dispatcher::NOT_FOUND:
                throw new NotFoundHttpException();

            case Dispatcher::METHOD_NOT_ALLOWED:
                throw new MethodNotAllowedHttpException($routeInfo[1]);

            case Dispatcher::FOUND:
                return $this->handleFoundRoute($routeInfo);
        }
    }

    /**
     * Handle a route found by the dispatcher.
     *
     * @param  array  $routeInfo
     *
     * @return mixed
     */
    protected function handleFoundRoute($routeInfo)
    {
        $this->currentRoute = $routeInfo;

        $this['request']->setRouteResolver(function () {
            return $this->currentRoute;
        });

        $action = $routeInfo[1];

        // Pipe through route middleware...
        if (isset($action['middleware'])) {
            $middleware = $this->gatherMiddlewareClassNames($action['middleware']);

            return $this->prepareResponse($this->sendThroughPipeline($middleware, function () use ($routeInfo) {
                return $this->callActionOnArrayBasedRoute($routeInfo);
            }));
        }

        return $this->prepareResponse(
            $this->callActionOnArrayBasedRoute($routeInfo)
        );
    }

    /**
     * Call the Closure on the array based route.
     *
     * @param  array  $routeInfo
     *
     * @return mixed
     */
    protected function callActionOnArrayBasedRoute($routeInfo)
    {
        $action = $routeInfo[1];

        if (isset($action['uses'])) {
            return $this->prepareResponse($this->callControllerAction($routeInfo));
        }

        foreach ($action as $value) {
            if ($value instanceof Closure) {
                $closure = $value->bindTo(new Routing\Closure());
                break;
            }
        }

        try {
            return $this->prepareResponse($this->call($closure, $routeInfo[2]));
        } catch (HttpResponseException $e) {
            return $e->getResponse();
        }
    }

    /**
     * Call a controller based route.
     *
     * @param  array  $routeInfo
     *
     * @return mixed
     */
    protected function callControllerAction($routeInfo)
    {
        list($controller, $method) = explode('@', $routeInfo[1]['uses']);

        if (! method_exists($instance = $this->make($controller), $method)) {
            throw new NotFoundHttpException();
        }

        if ($instance instanceof Routing\Controller) {
            return $this->callLumenController($instance, $method, $routeInfo);
        } else {
            return $this->callControllerCallable(
                [$instance, $method], $routeInfo[2]
            );
        }
    }

    /**
     * Send the request through a Lumen controller.
     *
     * @param  mixed  $instance
     * @param  string  $method
     * @param  array  $routeInfo
     *
     * @return mixed
     */
    protected function callLumenController($instance, $method, $routeInfo)
    {
        $middleware = $instance->getMiddlewareForMethod(
            $method
        );

        if (count($middleware) > 0) {
            return $this->callLumenControllerWithMiddleware(
                $instance, $method, $routeInfo, $middleware
            );
        } else {
            return $this->callControllerCallable(
                [$instance, $method], $routeInfo[2]
            );
        }
    }

    /**
     * Send the request through a set of controller middleware.
     *
     * @param  mixed  $instance
     * @param  string  $method
     * @param  array  $routeInfo
     * @param  array  $middleware
     *
     * @return mixed
     */
    protected function callLumenControllerWithMiddleware($instance, $method, $routeInfo, $middleware)
    {
        $middleware = $this->gatherMiddlewareClassNames($middleware);

        return $this->sendThroughPipeline($middleware, function () use ($instance, $method, $routeInfo) {
            return $this->callControllerCallable(
                [$instance, $method], $routeInfo[2]
            );
        });
    }

    /**
     * Call the callable for a controller action with the given parameters.
     *
     * @param  array  $callable
     * @param  array $parameters
     *
     * @return mixed
     */
    protected function callControllerCallable(array $callable, array $parameters)
    {
        try {
            return $this->prepareResponse(
                $this->call($callable, $parameters)
            );
        } catch (HttpResponseException $e) {
            return $e->getResponse();
        }
    }

    /**
     * Gather the full class names for the middleware short-cut string.
     *
     * @param  string  $middleware
     *
     * @return array
     */
    protected function gatherMiddlewareClassNames($middleware)
    {
        $middleware = is_string($middleware) ? explode('|', $middleware) : (array) $middleware;

        return array_map(function ($name) {
            list($name, $parameters) = array_pad(explode(':', $name, 2), 2, null);

            return array_get($this->routeMiddleware, $name, $name).($parameters ? ':'.$parameters : '');
        }, $middleware);
    }

    /**
     * Send the request through the pipeline with the given callback.
     *
     * @param  array  $middleware
     * @param  \Closure  $then
     *
     * @return mixed
     */
    protected function sendThroughPipeline(array $middleware, Closure $then)
    {
        $shouldSkipMiddleware = $this->bound('middleware.disable') &&
                                        $this->make('middleware.disable') === true;

        if (count($middleware) > 0 && ! $shouldSkipMiddleware) {
            return (new Pipeline($this))
                ->send($this->make('request'))
                ->through($middleware)
                ->then($then);
        }

        return $then();
    }

    /**
     * Prepare the response for sending.
     *
     * @param  mixed  $response
     *
     * @return \Illuminate\Http\Response
     */
    public function prepareResponse($response)
    {
        if (! $response instanceof SymfonyResponse) {
            $response = new Response($response);
        } elseif ($response instanceof BinaryFileResponse) {
            $response = $response->prepare(Request::capture());
        }

        return $response;
    }

    /**
     * Get the current HTTP request method.
     *
     * @return string
     */
    protected function getMethod()
    {
        if (isset($_POST['_method'])) {
            return strtoupper($_POST['_method']);
        } else {
            return $_SERVER['REQUEST_METHOD'];
        }
    }

    /**
     * Get the current HTTP path info.
     *
     * @return string
     */
    public function getPathInfo()
    {
        $query = isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : '';

        return '/'.trim(str_replace('?'.$query, '', $_SERVER['REQUEST_URI']), '/');
    }

    /**
     * Get the application namespace.
     *
     * @return string
     *
     * @throws \RuntimeException
     */
    public function getNamespace()
    {
        if (! is_null($this->namespace)) {
            return $this->namespace;
        }

        $composer = json_decode(file_get_contents($this->basePath().'/composer.json'), true);

        foreach ((array) data_get($composer, 'autoload.psr-4') as $namespace => $path) {
            foreach ((array) $path as $pathChoice) {
                if (realpath($this->path()) == realpath($this->basePath().'/'.$pathChoice)) {
                    return $this->namespace = $namespace;
                }
            }
        }

        throw new RuntimeException('Unable to detect application namespace.');
    }

    /**
     * Get the path to the application "app" directory.
     *
     * @return string
     */
    public function path()
    {
        return $this->basePath.DIRECTORY_SEPARATOR.'app';
    }

    /**
     * Get the base path for the application.
     *
     * @param  string|null  $path
     *
     * @return string
     */
    public function basePath($path = null)
    {
        if (isset($this->basePath)) {
            return $this->basePath.($path ? '/'.$path : $path);
        }

        if ($this->runningInConsole()) {
            $this->basePath = getcwd();
        } else {
            $this->basePath = realpath(getcwd().'/../');
        }

        return $this->basePath($path);
    }

    /**
     * Get the storage path for the application.
     *
     * @param  string|null  $path
     *
     * @return string
     */
    public function storagePath($path = null)
    {
        if ($this->storagePath) {
            return $this->storagePath.($path ? '/'.$path : $path);
        }

        return $this->basePath().'/storage'.($path ? '/'.$path : $path);
    }

    /**
     * Set a custom storage path for the application.
     *
     * @param  string  $path
     *
     * @return $this
     */
    public function useStoragePath($path)
    {
        $this->storagePath = $path;

        $this['path.storage'] = $path;

        return $this;
    }

    /**
     * Get the database path for the application.
     *
     * @return string
     */
    public function databasePath()
    {
        return $this->basePath().'/database';
    }

    /**
     * Set a custom configuration path for the application.
     *
     * @param  string  $path
     *
     * @return $this
     */
    public function useConfigPath($path)
    {
        $this->configPath = $path;

        $this['path.config'] = $path;

        return $this;
    }

    /**
     * Get the resource path for the application.
     *
     * @param  string|null  $path
     *
     * @return string
     */
    public function resourcePath($path = null)
    {
        if ($this->resourcePath) {
            return $this->resourcePath.($path ? '/'.$path : $path);
        }

        return $this->basePath().'/resources'.($path ? '/'.$path : $path);
    }

    /**
     * Set a custom resource path for the application.
     *
     * @param  string  $path
     *
     * @return $this
     */
    public function useResourcePath($path)
    {
        $this->resourcePath = $path;

        return $this;
    }

    /**
     * Determine if the application is running in the console.
     *
     * @return bool
     */
    public function runningInConsole()
    {
        return php_sapi_name() == 'cli';
    }

    /**
     * Prepare the application to execute a console command.
     *
     * @return void
     */
    public function prepareForConsoleCommand()
    {
        $this->withFacades();

        $this->make('cache');
        $this->make('queue');

        $this->configure('database');

        $this->register('Illuminate\Database\MigrationServiceProvider');
        $this->register('Illuminate\Database\SeedServiceProvider');
        $this->register('Illuminate\Queue\ConsoleServiceProvider');
    }

    /**
     * Get the raw routes for the application.
     *
     * @return array
     */
    public function getRoutes()
    {
        return $this->routes;
    }

    /**
     * Set the cached routes.
     *
     * @param  array  $routes
     *
     * @return void
     */
    public function setRoutes(array $routes)
    {
        $this->routes = $routes;
    }

    /**
     * Get the HTML from the Lumen welcome screen.
     *
     * @return string
     */
    public function welcome()
    {
        return "<!DOCTYPE html>
            <html>
            <head>
                <title>Lumen</title>

                <link href='//fonts.googleapis.com/css?family=Lato:100' rel='stylesheet' type='text/css'>

                <style>
                    html, body {
                        height: 100%;
                    }

                    body {
                        margin: 0;
                        padding: 0;
                        width: 100%;
                        color: #B0BEC5;
                        display: table;
                        font-weight: 100;
                        font-family: 'Lato';
                    }

                    .container {
                        text-align: center;
                        display: table-cell;
                        vertical-align: middle;
                    }

                    .content {
                        text-align: center;
                        display: inline-block;
                    }

                    .title {
                        font-size: 96px;
                    }
                </style>
            </head>
            <body>
                <div class=\"container\">
                    <div class=\"content\">
                        <div class=\"title\">Lumen.</div>
                    </div>
                </div>
            </body>
            </html>
        ";
    }
}
