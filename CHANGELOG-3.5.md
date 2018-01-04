# Changelog for v3.5

This changelog references the relevant changes (bug and security fixes) done to `orchestra/lumen`.

## 3.5.1

Released: 2018-01-04

### Added

* Add refresh migration command. ([@mul14](https://github.com/mul14))
* Provide `dispatchNow()` along with `dispatch()` function under `Laravel\Lumen\Routing\ProvidesConvenienceMethods` trait. ([@maxklenk](https://github.com/maxklenk))

### Changes

* Don't convert `Jsonable` data for `JsonResponse` prematurely. ([@gaaf](https://github.com/gaaf))

### Fixes

* Fix forced scheme URL generation. ([@gazben](https://github.com/gazben))

## 3.5.0

Released: 2017-11-27

### Changes

* Update support for Laravel Framework v5.5.
* Separate `Laravel\Lumen\Application` routing feature to `Laravel\Lumen\Routing\Router`.
