---
title: Lumen Change Log

---

## Version 3.1 {#v3-1}

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
