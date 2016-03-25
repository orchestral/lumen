---
title: Lumen Change Log

---

## Version 3.1 {#v3-1}

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
* Fix welcome page alignment. ([tom-wilson](https://github.com/tom-wilson))
* Add `Laravel\Lumen\Http\ResponseFactory` basic unit tests. ([jmatosp](https://github.com/jmatosp))

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
