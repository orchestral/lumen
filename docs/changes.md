---
title: Lumen Change Log

---

## Version 3.2 {#v3-2}

### v3.2.6 {#v3-2-6}

* Improved the exception handler. ([@GrahamCampbell](https://github.com/GrahamCampbell))
* Add `Laravel\Lumen\Application::runningUnitTests()`.

### v3.2.5 {#v3-2-5}

* Allow middleware to replace route info. ([@sw-double](https://github.com/sw-double))
* Add `bindAuthForApiToken()` and `bindAuthForJwtToken()` method under `App\Lumen\Providers\AuthServiceProvider`.
* Add `Laravel\Lumen\Auth\Providers\Guard` class for Illuminate Auth authentication for dingo/api.
* `Laravel\Lumen\Concerns\RoutesRequests::addRoute()` now accepts multiple methods. ([@m1](https://github.com/m1))

### v3.2.4 {#v3-2-4}

* Add `Laravel\Lumen\Application::withFacadeAliases()` method.
* Add `Laravel\Lumen\Auth\Providers\JWT` class for JWT authentication for dingo/api.
* Add `Laravel\Lumen\Concerns\CoreBindings::registerResponseFactoryBindings()` method.
* Add `Laravel\Lumen\Http\Middleware\Cors` middleware.
* Add `Laravel\Lumen\Http\Middleware\ThrottleRequests` middleware.
* Add support for route group suffix.

### v3.2.3 {#v3-2-3}

* Add support for PSR-7 requests and responses. ([@matthew-james](https://github.com/matthew-james))
* Add `Laravel\Lumen\Routing\UrlGenerator` bindings. ([@matthew-james](https://github.com/matthew-james))

### v3.2.2 {#v3-2-2}

* Return an `Illuminate\Http\Response` from the exception handler. ([@matthew-james](https://github.com/matthew-james))
* Fixes auth guard driver from `api` to `jwt`.
* Move Orchestra Platform integration to `Laravel\Lumen\Concerns\FoundationSupports` trait.
* Add default `App\Lumen\User` model for JWT integration.
* Add `dingo/api` and `tymon/jwt-auth` config by default.

### v3.2.1 {#v3-2-1}

* Register binding for `cache.store`. ([@dschniepp](https://github.com/dschniepp))
* Fixes missing import of `\Exception` in `Laravel\Lumen\Testing\TestCase`. ([@dschniepp](https://github.com/dschniepp))

### v3.2.0 {#v3-2-0}

* Update support for Lumen Framework v5.2.x.
* Convert `Laravel\Lumen\Application::loadComponent()` visibility to `public`.
* Add `Laravel\Lumen\Application::withFoundation()` to boot basic Orchestra Platform support.
* Allow method chaining on `withFacades()`, `withEloquent()` and `withFoundation()`.
* Improves integration with `tymon/jwt-auth`.
* Add `symfony/polyfill-php56` to add support `hash_equals()` on < PHP 5.6.

## Version 3.1 {#v3-1}

### v3.1.9 {#v3-1-9}

* Improved the exception handler. ([@GrahamCampbell](https://github.com/GrahamCampbell))

### v3.1.8 {#v3-1-8}

* Add default `App\Lumen\User` model.
* Improves `lumen/bootstrap.php`.
* Add default `api.php` and `jwt.php` config.
* Add `Laravel\Lumen\Concerns\FoundationSupports` trait.

### v3.1.7 {#v3-1-7}

* Allow middleware specification to use `|` as separator, or passing an array.
* Convert `Laravel\Lumen\Application::loadComponent()` visibility to `public`.
* Include example of JWT Auth integration.

### v3.1.6 {#v3-1-6}

* Simplify `composer.json` dependencies.
* Refactor code to match upcoming 3.2 releases.
* Fix welcome page alignment. ([@tom-wilson](https://github.com/tom-wilson))
* Add `Laravel\Lumen\Http\ResponseFactory` basic unit tests. ([@jmatosp](https://github.com/jmatosp))

### v3.1.5 {#v3-1-5}

* Add `Laravel\Lumen\Application::withFoundation()` to boot basic Orchestra Platform support.
* Allow method chaining on `withFacades()`, `withEloquent()` and `withFoundation()`.

### v3.1.4 {#v3-1-4}

* Add `symfony/polyfill-php56` to add support `hash_equals()` on < PHP 5.6.

### v3.1.3 {#v3-1-3}

* Fixes `php artisan serve` command to load the correct path.
* Use `hash_equal()` method by default for comparing CSRF token.
* Load `DotEnv` by default.
* Clean-up unused stub.

### v3.1.2 {#v3-1-2}

* Allow getting path from `Laravel\Lumen\Application::getConfigurationPath()`.
* Add `App\Lumen\Http\Middleware\Cors` to default skeleton.

### v3.1.1 {#v3-1-1}

* Fixes unavailable `Laravel\Lumen\Application::isBooted()` and instead use `Laravel\Lumen\Application::$booted`.
* Only redirecting with `.htaccess` . 

### v3.1.0 {#v3-1-0}

* Initial release based on latest Lumen Framework v5.1.x.
