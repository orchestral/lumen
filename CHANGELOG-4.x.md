
# Changelog for v4.0

This changelog references the relevant changes (bug and security fixes) done to `orchestra/lumen`.

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

