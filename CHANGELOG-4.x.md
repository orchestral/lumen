# Changelog for v4.x

This changelog references the relevant changes (bug and security fixes) done to `orchestra/lumen`.

## 4.5.0

Released: 2020-02-13

### Changes

* Updated the PSR17 RequestFactory and PSR7 Response classes to use `nyholm/psr7`. If `nyholm/psr7` is not present, default back to `Symfony\Bridge\PsrHttpMessage\Factory\PsrHttpFactory` & `Zend\Diactoros\ServerRequestFactory` respectively.

## 4.4.1

Released: 2020-02-02

### Changes

* Register redis container aliases.

### Fixed

* Fixes request instance to `Laravel\Lumen\Http\Request` when using Console.

## 4.4.0

Released: 2020-01-05

### Changes

* Improves eloquent hot-swap implementation.

## 4.3.1

Released: 2019-12-27

### Changes

* Remove redundant `env()` request.
* Performance improvements to `Laravel\Lumen\Application::runningInConsole()`.

## 4.3.0

Released: 2019-12-26

### Added

* Added to skeleton:
    - `App\Lumen\Auth\Providers\Authorization`.
    - `App\Lumen\Auth\Providers\Passport`.
    - Ignores `Laravel\Passport\Exceptions\OAuthServerException` from Lumen exception report handler.

### Changes

* Changes to skeleton:
    - Boot `Laravie\Authen\BootAuthenProvider` by default via `App\Lumen\Providers\AuthServiceProvider`.
* Stop inappropriate `env()` lookup in `Laravel\Lumen\Exceptions\Handler`.
* Register `Orchestra\Foundation\Testing\Concerns\WithInstallation` to `Laravel\Lumen\Testing\TestCase`.

## 4.2.1

Released: 2019-11-03

### Fixed

* Load `app` configuration when bootstrapping `db` component to allow faker configuration is available.

## 4.2.0

Released: 2019-10-11

### Changes

* Update support for Laravel Framework v6.2+.
* Improves support for locale support under `Laravel\Lumen\Application` by properly implements `setLocale()`, `getLocale()` and `isLocale()`.

## 4.1.0

Released: 2019-10-03

### Changes

* `Laravel\Lumen\Application::hasBeenBootstrapped()` now default to return `true` instead of throwing an `Exception`.
* `Laravel\Lumen\Application::loadDeferredProviders()` now does return `void` (does nothing) instead of throwing an `Exception`.

## 4.0.0

Released: 2019-09-14

### Added

* Add `Laravel\Lumen\Auth\Providers\Concerns\AuthorizationHelpers` trait.

### Changes

* Update support for Laravel Framework v6.x.
* Mark `Laravel\Lumen\Auth\Providers\Guard` as `abstract class`.
* Improves configuration paths resolution on Lumen.

### Deprecated

* Deprecate `Laravel\Lumen\Application::getConfigurationPath()` method.

