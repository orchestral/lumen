# Changelog

This changelog references the relevant changes (bug and security fixes) done to `orchestra/lumen`.

## 3.4.4

Released: 2017-10-02

### Added

* Allow to automatically handle Orchestra Platform installation during testing when given `Orchestra\Foundation\Testing\Installation`.

## 3.4.3

Released: 2017-09-28

### Added

* Add `Laravel\Lumen\Testing\AuthenticateWithJWT`.
* Add `path.database` service location.

### Changes

* Flush application properties on `Laravel\Lumen\Application::flush()` method.
* Add Orchestra Platform services on `Laravel\Lumen\Application::prepareForConsoleCommand()`.

## 3.4.2

Released: 2017-09-26

### Added

* Add default `phpunit.xml` for skeleton.

### Changes

* Add `queue.listener` to be accessible by `Laravel\Lumen\Application`.
* `Laravel\Lumen\Console\Kernel` shouldn't load aliases by default.

## 3.4.1

Released: 2017-08-17

### Changes

* Use `PHPUnit\Framework\Assert` instead of `PHPUnit_Framework_Assert`.
* Support `DatabaseTransactions::connectionsToTransact` feature in Lumen. ([@xcept10n](https://github.com/xcept10n))

## 3.4.0

Released: 2017-05-30

### Changes

* Update support for Laravel Framework v5.4.
