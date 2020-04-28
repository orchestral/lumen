# Changelog for 5.x

This changelog references the relevant changes (bug and security fixes) done to `orchestra/lumen`.

## 5.0.2

Released: 2020-04-29

### Fixes

* Fixes typo in method name under `Laravel\Lumen\Concerns\RegistersExceptionHandlers`.

## 5.0.1

Released: 2020-04-20

### Changes

* Use BindingResolutionException to signal problem with container resolution.

## 5.0.0

Released: 2020-04-03

### Changes

* Update support for Laravel Framework v7.
* Change helpers namespace from `api` to `Laravel\Lumen`.

### Removed

* Remove following methods from `Laravel\Lumen\Application`:
    - `configurationIsCached()`.
    - `detectEnvironment()`.
    - `environmentFile()`.
    - `environmentFilePath()`.
    - `environmentPath()`.
    - `getCachedRoutesPath()`.
    - `getConfigurationPath()`.
    - `loadEnvironmentFrom()`.
    - `routesAreCached()`.
