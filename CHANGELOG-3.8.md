# Changelog for v3.8

This changelog references the relevant changes (bug and security fixes) done to `orchestra/lumen`.

## 3.8.8

Released: 2019-09-07

### Changes

* Tweak minimum version for `laravel/framework` and `laravie/api`.

### Fixes

* Fixes `Laravel\Lumen\Testing\Concerns\MakesHttpRequests::options()`.

## 3.8.7

Released: 2019-09-02

### Changes

* Set `$user` to `Illuminate\Http\Request::setUserResolver()` when user get authenticated via `Laravel\Lumen\Auth\Providers\Guard` implementations.
* Able to report exception via `Exception::report()` if the method exists.
* Able to render exception via `Exception::render()` if the method exists.
* Able to render exception if it implements `Illuminate\Contracts\Support\Responsable`.

## 3.8.6

Released: 2019-08-19

### Changes

* Use `static function` rather than `function` whenever possible, the PHP engine does not need to instantiate and later GC a `$this` variable for said closure.

## 3.8.5

Released: 2019-07-24

### Added

* Register Filesystem cloud disks into the container.

## 3.8.4

Released: 2019-07-01

### Changes

* Use `request` to get current version for `dingo/api`.

### Removed

* Remove `Dotenv\Environment\DotenvFactory` check as `vlucas/phpdotenv` is a required dependencies.

## 3.8.3

Released: 2019-05-24

#### Added

* Add `Laravel\Lumen\Application::useStoragePath()` method to register custom storage path.

## 3.8.2

Released: 2019-05-06

### Added

* Added `Laravel\Lumen\Http\ResponseFactory::stream()` method.

## 3.8.1

Released: 2019-04-04

### Fixes

* Fix compatibility based on changes made to Laravel Framework v5.8.9.

## 3.8.0

Released: 2019-03-26

### Changes

* Update support for Laravel Framework v5.8.

### Upgrade Guide

* Update the following files:
  - `skeleton/artisan`
  - `skeleton/bootstrap.php`
  - `skeleton/public/index.php`
