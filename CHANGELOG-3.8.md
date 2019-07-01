# Changelog for v3.8

This changelog references the relevant changes (bug and security fixes) done to `orchestra/lumen`.

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
