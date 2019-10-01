# Changelog for v3.7

This changelog references the relevant changes (bug and security fixes) done to `orchestra/lumen`.

## 3.7.2

Released: 2019-03-11

### Changes

* Improve performance by prefixing all global functions calls with `\` to skip the look up and resolve process and go straight to the global function.

### Fixes

* Fixes incorrect request type during testing. When testing, the `call` method provides an instance of the Laravel Request class while this should be Lumen's own Request class.

## 3.7.1

Released: 2019-02-13

### Changes

* Add signed route ability to Lumen.

## 3.7.0

Released: 2018-12-25

### Added

* Added `Laravel\Lumen\Bus\PendingDispatch`.
* Added `Laravel\Lumen\Http\Request`.

### Changes

* Update support for Laravel Framework v5.7.
* Set the application url when running tests.
* Make the fingerprint method compatible with Lumen.
* Ensure the response object is prepared before returning it.