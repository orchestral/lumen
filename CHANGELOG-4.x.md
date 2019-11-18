# Changelog for v4.x

This changelog references the relevant changes (bug and security fixes) done to `orchestra/lumen`.

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

