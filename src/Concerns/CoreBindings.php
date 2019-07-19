<?php

namespace Laravel\Lumen\Concerns;

use Monolog\Logger;
use Illuminate\Http\Request;
use Illuminate\Log\LogManager;
use Illuminate\Support\Composer;
use Orchestra\Config\FileLoader;
use Orchestra\Config\Repository;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LineFormatter;
use Illuminate\Filesystem\Filesystem;
use Laravel\Lumen\Http\ResponseFactory;
use Laravel\Lumen\Routing\UrlGenerator;
use Zend\Diactoros\Response as PsrResponse;
use Symfony\Bridge\PsrHttpMessage\Factory\DiactorosFactory;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

trait CoreBindings
{
    /**
     * The available container bindings and their respective load methods.
     *
     * @var array
     */
    public $availableBindings = [
        'auth' => 'registerAuthBindings',
        'auth.driver' => 'registerAuthBindings',
        'Illuminate\Auth\AuthManager' => 'registerAuthBindings',
        'Illuminate\Contracts\Auth\Guard' => 'registerAuthBindings',
        'Illuminate\Contracts\Auth\Access\Gate' => 'registerAuthBindings',
        'Orchestra\Auth\AuthManager' => 'registerAuthBindings',
        'orchestra.acl' => 'registerAuthorizationBindings',
        'orchestra.platform.acl' => 'registerAuthorizationBindings',
        'Orchestra\Authorization\Factory' => 'registerAuthorizationBindings',
        'Orchestra\Authorization\Authorization' => 'registerAuthorizationBindings',
        'Orchestra\Contracts\Authorization\Factory' => 'registerAuthorizationBindings',
        'Orchestra\Contracts\Authorization\Authorization' => 'registerAuthorizationBindings',
        'Illuminate\Contracts\Broadcasting\Broadcaster' => 'registerBroadcastingBindings',
        'Illuminate\Contracts\Broadcasting\Factory' => 'registerBroadcastingBindings',
        'Illuminate\Contracts\Bus\Dispatcher' => 'registerBusBindings',
        'cache' => 'registerCacheBindings',
        'cache.store' => 'registerCacheBindings',
        'Illuminate\Contracts\Cache\Factory' => 'registerCacheBindings',
        'Illuminate\Contracts\Cache\Repository' => 'registerCacheBindings',
        'Illuminate\Cache\CacheManager' => 'registerCacheBindings',
        'config' => 'registerConfigBindings',
        'composer' => 'registerComposerBindings',
        'cookie' => 'registerCookieBindings',
        'Illuminate\Contracts\Cookie\Factory' => 'registerCookieBindings',
        'Illuminate\Contracts\Cookie\QueueingFactory' => 'registerCookieBindings',
        'db' => 'registerDatabaseBindings',
        'Illuminate\Database\Connection' => 'registerDatabaseBindings',
        'Illuminate\Database\Eloquent\Factory' => 'registerDatabaseBindings',
        'filesystem' => 'registerFilesystemBindings',
        'Illuminate\Contracts\Filesystem\Factory' => 'registerFilesystemBindings',
        'encrypter' => 'registerEncrypterBindings',
        'Illuminate\Contracts\Encryption\Encrypter' => 'registerEncrypterBindings',
        'events' => 'registerEventBindings',
        'Illuminate\Contracts\Events\Dispatcher' => 'registerEventBindings',
        'files' => 'registerFilesBindings',
        'filesystem' => 'registerFilesBindings',
        'Illuminate\Contracts\Filesystem\Factory' => 'registerFilesBindings',
        'hash' => 'registerHashBindings',
        'hash.driver' => 'registerHashBindings',
        'Illuminate\Hashing\HashManager' => 'registerHashBindings',
        'Illuminate\Contracts\Hashing\Hasher' => 'registerHashBindings',
        'log' => 'registerLogBindings',
        'Psr\Log\LoggerInterface' => 'registerLogBindings',
        'mailer' => 'registerMailBindings',
        'Illuminate\Contracts\Mail\Mailer' => 'registerMailBindings',
        'orchestra.memory' => 'registerMemoryBindings',
        'Orchestra\Memory\MemoryManager' => 'registerMemoryBindings',
        'orchestra.platform.memory' => 'registerMemoryBindings',
        'Orchestra\Memory\Provider' => 'registerMemoryBindings',
        'Orchestra\Contracts\Memory\Provider' => 'registerMemoryBindings',
        'queue' => 'registerQueueBindings',
        'queue.connection' => 'registerQueueBindings',
        'queue.listener' => 'registerQueueBindings',
        'Illuminate\Contracts\Queue\Factory' => 'registerQueueBindings',
        'Illuminate\Contracts\Queue\Queue' => 'registerQueueBindings',
        'redis' => 'registerRedisBindings',
        'request' => 'registerRequestBindings',
        'router' => 'registerRouterBindings',
        'Psr\Http\Message\ServerRequestInterface' => 'registerPsrRequestBindings',
        'Psr\Http\Message\ResponseInterface' => 'registerPsrResponseBindings',
        'Illuminate\Contracts\Routing\ResponseFactory' => 'registerResponseFactoryBindings',
        'Illuminate\Http\Request' => 'registerRequestBindings',
        'session' => 'registerSessionBindings',
        'session.store' => 'registerSessionBindings',
        'Illuminate\Session\SessionManager' => 'registerSessionBindings',
        'Illuminate\Session\Store' => 'registerSessionBindings',
        'translator' => 'registerTranslationBindings',
        'url' => 'registerUrlGeneratorBindings',
        'Illuminate\Contracts\Routing\UrlGenerator' => 'registerUrlGeneratorBindings',
        'Laravel\Lumen\Routing\UrlGenerator' => 'registerUrlGeneratorBindings',
        'validator' => 'registerValidatorBindings',
        'Illuminate\Contracts\Validation\Factory' => 'registerValidatorBindings',
        'view' => 'registerViewBindings',
        'Illuminate\Contracts\View\Factory' => 'registerViewBindings',
    ];

    /**
     * A custom callback used to configure Monolog.
     *
     * @var callable|null
     */
    protected $monologConfigurator;

    /**
     * Register the core container aliases.
     *
     * @return void
     */
    protected function registerContainerAliases()
    {
        $this->aliases = [
            'Illuminate\Contracts\Foundation\Application' => 'app',
            'Illuminate\Contracts\Auth\Factory' => 'auth',
            'Illuminate\Contracts\Auth\Guard' => 'auth.driver',
            'Illuminate\Contracts\Auth\PasswordBroker' => 'auth.password',
            'Illuminate\Auth\AuthManager' => 'auth',
            'Orchestra\Auth\AuthManager' => 'auth',
            'Illuminate\Contracts\Cache\Factory' => 'cache',
            'Illuminate\Cache\CacheManager' => 'cache',
            'Illuminate\Contracts\Cache\Repository' => 'cache.store',
            'Illuminate\Contracts\Config\Repository' => 'config',
            'Illuminate\Contracts\Cookie\Factory' => 'cookie',
            'Illuminate\Contracts\Cookie\QueueingFactory' => 'cookie',
            'Illuminate\Container\Container' => 'app',
            'Illuminate\Contracts\Container\Container' => 'app',
            'Laravel\Lumen\Application' => 'app',
            'Illuminate\Database\ConnectionResolverInterface' => 'db',
            'Illuminate\Database\DatabaseManager' => 'db',
            'Illuminate\Database\Connection' => 'db.connection',
            'Illuminate\Contracts\Encryption\Encrypter' => 'encrypter',
            'Illuminate\Contracts\Events\Dispatcher' => 'events',
            'Illuminate\Contracts\Filesystem\Factory' => 'filesystem',
            'Illuminate\Hashing\HashManager' => 'hash',
            'Illuminate\Contracts\Hashing\Hasher' => 'hash.driver',
            'log' => 'Psr\Log\LoggerInterface',
            'Illuminate\Contracts\Mail\Mailer' => 'mailer',
            'Orchestra\Authorization\Factory' => 'orchestra.acl',
            'Orchestra\Contracts\Authorization\Factory' => 'orchestra.acl',
            'Orchestra\Memory\MemoryManager' => 'orchestra.memory',
            'Orchestra\Authorization\Authorization' => 'orchestra.platform.acl',
            'Orchestra\Contracts\Authorization\Authorization' => 'orchestra.platform.acl',
            'Orchestra\Memory\Provider' => 'orchestra.platform.memory',
            'Orchestra\Contracts\Memory\Provider' => 'orchestra.platform.memory',
            'Illuminate\Contracts\Queue\Factory' => 'queue',
            'Illuminate\Contracts\Queue\Queue' => 'queue.connection',
            'Illuminate\Redis\Database' => 'redis',
            'Illuminate\Contracts\Redis\Database' => 'redis',
            'request' => 'Illuminate\Http\Request',
            'Laravel\Lumen\Routing\Router' => 'router',
            'Illuminate\Session\SessionManager' => 'session',
            'Illuminate\Session\Store' => 'session.store',
            'Illuminate\Contracts\Translation\Translator' => 'translator',
            'Illuminate\Contracts\Routing\UrlGenerator' => 'url',
            'Laravel\Lumen\Routing\UrlGenerator' => 'url',
            'Illuminate\Contracts\Validation\Factory' => 'validator',
            'Illuminate\Contracts\View\Factory' => 'view',
        ];
    }

    /**
     * Register container bindings for the application.
     *
     * @return void
     */
    protected function registerAuthBindings()
    {
        $this->singleton('auth', static function ($app) {
            return $app->loadComponent('auth', 'Orchestra\Auth\AuthServiceProvider', 'auth');
        });

        $this->singleton('auth.driver', static function ($app) {
            return $app->loadComponent(
                'auth', 'Orchestra\Auth\AuthServiceProvider', 'auth.driver'
            );
        });

        $this->singleton('Illuminate\Contracts\Auth\Access\Gate', static function ($app) {
            return $app->loadComponent(
                'auth', 'Orchestra\Auth\AuthServiceProvider', 'Illuminate\Contracts\Auth\Access\Gate'
            );
        });
    }

    /**
     * Register container bindings for the application.
     *
     * @return void
     */
    protected function registerAuthorizationBindings()
    {
        $this->register('Orchestra\Authorization\AuthorizationServiceProvider');

        $this->singleton('orchestra.platform.acl', static function ($app) {
            $acl = $app->make('orchestra.acl')->make('orchestra');

            $acl->attach($app->make('orchestra.platform.memory'));

            return $acl;
        });
    }

    /**
     * Register container bindings for the application.
     *
     * @return void
     */
    protected function registerBroadcastingBindings()
    {
        $this->singleton('Illuminate\Contracts\Broadcasting\Factory', static function ($app) {
            return $app->loadComponent(
                'broadcasting', 'Illuminate\Broadcasting\BroadcastServiceProvider', 'Illuminate\Contracts\Broadcasting\Factory'
            );
        });

        $this->singleton('Illuminate\Contracts\Broadcasting\Broadcaster', static function ($app) {
            return $app->loadComponent(
                'broadcasting', 'Illuminate\Broadcasting\BroadcastServiceProvider', 'Illuminate\Contracts\Broadcasting\Broadcaster'
            );
        });
    }

    /**
     * Register container bindings for the application.
     *
     * @return void
     */
    protected function registerBusBindings()
    {
        $this->singleton('Illuminate\Contracts\Bus\Dispatcher', static function ($app) {
            $app->register('Illuminate\Bus\BusServiceProvider');

            return $app->make('Illuminate\Contracts\Bus\Dispatcher');
        });
    }

    /**
     * Register container bindings for the application.
     *
     * @return void
     */
    protected function registerCacheBindings()
    {
        $this->singleton('cache', static function ($app) {
            return $app->loadComponent('cache', 'Illuminate\Cache\CacheServiceProvider');
        });

        $this->singleton('cache.store', static function ($app) {
            return $app->loadComponent(
                'cache', 'Illuminate\Cache\CacheServiceProvider', 'cache.store'
            );
        });
    }

    /**
     * Register container bindings for the application.
     *
     * @return void
     */
    protected function registerConfigBindings()
    {
        $loader = new FileLoader(new Filesystem(), $this->resourcePath('config'));

        $this->singleton('config', static function ($app) use ($loader) {
            return new Repository($loader, $app->environment());
        });
    }

    /**
     * Register container bindings for the application.
     *
     * @return void
     */
    protected function registerComposerBindings()
    {
        $this->singleton('composer', static function ($app) {
            return new Composer($app->make('files'), $app->basePath());
        });
    }

    /**
     * Register container bindings for the application.
     *
     * @return void
     */
    protected function registerCookieBindings()
    {
        $this->singleton('cookie', static function ($app) {
            return $app->loadComponent(
                'session', 'Illuminate\Cookie\CookieServiceProvider', 'cookie'
            );
        });
    }

    /**
     * Register container bindings for the application.
     *
     * @return void
     */
    protected function registerDatabaseBindings()
    {
        $this->singleton('db', static function ($app) {
            return $app->loadComponent(
                'database', [
                    'Illuminate\Database\DatabaseServiceProvider',
                    'Illuminate\Pagination\PaginationServiceProvider',
                ], 'db'
            );
        });
    }

    /**
     * Register container bindings for the application.
     *
     * @return void
     */
    protected function registerEncrypterBindings()
    {
        $this->singleton('encrypter', static function ($app) {
            return $app->loadComponent(
                'app', 'Illuminate\Encryption\EncryptionServiceProvider', 'encrypter'
            );
        });
    }

    /**
     * Register container bindings for the application.
     *
     * @return void
     */
    protected function registerEventBindings()
    {
        $this->singleton('events', static function ($app) {
            $app->register('Illuminate\Events\EventServiceProvider');

            return $app->make('events');
        });
    }

    /**
     * Register container bindings for the application.
     *
     * @return void
     */
    protected function registerErrorBindings()
    {
        if (! $this->bound('Illuminate\Contracts\Debug\ExceptionHandler')) {
            $this->singleton(
                'Illuminate\Contracts\Debug\ExceptionHandler', 'Laravel\Lumen\Exceptions\Handler'
            );
        }
    }

    /**
     * Register container bindings for the application.
     *
     * @return void
     */
    protected function registerFilesBindings()
    {
        $this->singleton('files', static function () {
            return new Filesystem();
        });

        $this->singleton('filesystem', static function ($app) {
            return $app->loadComponent(
                'filesystems', 'Illuminate\Filesystem\FilesystemServiceProvider', 'filesystem'
            );
        });
    }

    /**
     * Register container bindings for the application.
     *
     * @return void
     */
    protected function registerFilesystemBindings()
    {
        $this->singleton('filesystem', static function ($app) {
            return $app->loadComponent('filesystems', 'Illuminate\Filesystem\FilesystemServiceProvider', 'filesystem');
        });
    }

    /**
     * Register container bindings for the application.
     *
     * @return void
     */
    protected function registerHashBindings()
    {
        $this->singleton('hash', static function ($app) {
            $app->register('Illuminate\Hashing\HashServiceProvider');

            return $app->make('hash');
        });
    }

    /**
     * Register container bindings for the application.
     *
     * @return void
     */
    protected function registerLogBindings()
    {
        $this->singleton('Psr\Log\LoggerInterface', static function ($app) {
            $app->configure('logging');

            return new LogManager($app);
        });
    }

    /**
     * Register container bindings for the application.
     *
     * @return void
     */
    protected function registerMemoryBindings()
    {
        $this->register('Orchestra\Memory\MemoryServiceProvider');

        $this->singleton('orchestra.platform.memory', static function ($app) {
            return $app->make('orchestra.memory')->makeOrFallback();
        });
    }

    /**
     * Get the Monolog handler for the application.
     *
     * @return \Monolog\Handler\AbstractHandler
     */
    protected function getMonologHandler()
    {
        return (new StreamHandler(storage_path('logs/lumen.log'), Logger::DEBUG))
                            ->setFormatter(new LineFormatter(null, null, true, true));
    }

    /**
     * Register container bindings for the application.
     *
     * @return void
     */
    protected function registerMailBindings()
    {
        $this->singleton('mailer', static function ($app) {
            $app->configure('services');

            return $app->loadComponent('mail', 'Illuminate\Mail\MailServiceProvider', 'mailer');
        });
    }

    /**
     * Register container bindings for the PSR-7 request implementation.
     *
     * @return void
     */
    protected function registerPsrRequestBindings()
    {
        $this->singleton('Psr\Http\Message\ServerRequestInterface', static function ($app) {
            return (new DiactorosFactory())->createRequest($app->make('request'));
        });
    }

    /**
     * Register container bindings for the PSR-7 response implementation.
     *
     * @return void
     */
    protected function registerPsrResponseBindings()
    {
        $this->singleton('Psr\Http\Message\ResponseInterface', static function () {
            return new PsrResponse();
        });
    }

    /**
     * Register container bindings for the application.
     *
     * @return void
     */
    protected function registerQueueBindings()
    {
        $this->singleton('queue', static function ($app) {
            return $app->loadComponent('queue', 'Illuminate\Queue\QueueServiceProvider', 'queue');
        });

        $this->singleton('queue.listener', static function ($app) {
            return $app->loadComponent('queue', 'Illuminate\Queue\QueueServiceProvider', 'queue.listener');
        });

        $this->singleton('queue.connection', static function ($app) {
            return $app->loadComponent('queue', 'Illuminate\Queue\QueueServiceProvider', 'queue.connection');
        });
    }

    /**
     * Register container bindings for the application.
     *
     * @return void
     */
    protected function registerRedisBindings()
    {
        $this->singleton('redis', static function ($app) {
            return $app->loadComponent('database', 'Illuminate\Redis\RedisServiceProvider', 'redis');
        });
    }

    /**
     * Register container bindings for the application.
     *
     * @return void
     */
    protected function registerRequestBindings()
    {
        $this->singleton('Illuminate\Http\Request', function () {
            return $this->prepareRequest(Request::capture());
        });
    }

    /**
     * Register container bindings for the application.
     *
     * @return void
     */
    protected function registerRouterBindings()
    {
        $this->singleton('router', static function ($app) {
            return $app->router;
        });
    }

    /**
     * Register container bindings for the application.
     *
     * @return void
     */
    protected function registerResponseFactoryBindings()
    {
        $this->singleton('Illuminate\Contracts\Routing\ResponseFactory', static function () {
            return new ResponseFactory();
        });
    }

    /**
     * Register container bindings for the application.
     *
     * @return void
     */
    protected function registerSessionBindings()
    {
        $this->singleton('session', static function ($app) {
            return $app->loadComponent('session', 'Illuminate\Session\SessionServiceProvider');
        });

        $this->singleton('session.store', static function ($app) {
            return $app->loadComponent('session', 'Illuminate\Session\SessionServiceProvider', 'session.store');
        });
    }

    /**
     * Register container bindings for the application.
     *
     * @return void
     */
    protected function registerTranslationBindings()
    {
        $this->singleton('translator', function ($app) {
            $app->configure('app');

            $app->instance('path.lang', $this->getLanguagePath());

            $app->register('Orchestra\Translation\TranslationServiceProvider');

            return $app->make('translator');
        });
    }

    /**
     * Register container bindings for the application.
     *
     * @return void
     */
    protected function registerUrlGeneratorBindings()
    {
        $this->singleton('url', static function ($app) {
            return new UrlGenerator($app);
        });
    }

    /**
     * Register container bindings for the application.
     *
     * @return void
     */
    protected function registerValidatorBindings()
    {
        $this->singleton('validator', static function ($app) {
            $app->register('Illuminate\Validation\ValidationServiceProvider');

            return $app->make('validator');
        });
    }

    /**
     * Register container bindings for the application.
     *
     * @return void
     */
    protected function registerViewBindings()
    {
        $this->singleton('view', static function ($app) {
            return $app->loadComponent('view', 'Orchestra\View\ViewServiceProvider');
        });
    }

    /**
     * Prepare the given request instance for use with the application.
     *
     * @param  \Symfony\Component\HttpFoundation\Request  $request
     *
     * @return \Illuminate\Http\Request
     */
    protected function prepareRequest(SymfonyRequest $request)
    {
        if (! $request instanceof Request) {
            $request = Request::createFromBase($request);
        }

        $request->setUserResolver(function ($guard = null) {
            return $this->make('auth')->guard($guard)->user();
        })->setRouteResolver(function () {
            return $this->currentRoute;
        });

        return $request;
    }

    /**
     * Define a callback to be used to configure Monolog.
     *
     * @param  callable  $callback
     *
     * @return $this
     */
    public function configureMonologUsing(callable $callback)
    {
        $this->monologConfigurator = $callback;

        return $this;
    }
}
