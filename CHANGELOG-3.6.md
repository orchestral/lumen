# Changelog for v3.6

This changelog references the relevant changes (bug and security fixes) done to `orchestra/lumen`.

## 3.6.1

Released: 2018-05-24

### Fixes

* Replace deprecated `Orchestra\Extension\RouteGenerator` with `orchestra.extension.url`.

## 3.6.0

Released: 2018-05-21

### Added

* Added `Laravel\Lumen\Testing\WithoutEvents`.
* Added `Laravel\Lumen\Testing\WithoutMiddleware`.
* Add support for `head()` method to `Laravel\Lument\Routing\Router`.
* Add support for url generation on console requests.

### Changes

* Update support for Laravel Framework v5.6.
* Only bind request if it's not already bound.
* Use `$memory->makeOrFallback()` to integrate Orchestra ACL with Lumen.
