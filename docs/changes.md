---
title: Lumen Change Log

---

## Version 3.1 {#v3-1}

### v3.1.2 {#v3-1-2}

* Allow getting path from `Laravel\Lumen\Application::getConfigurationPath()`.
* Add `App\Lumen\Http\Middleware\Cors` to default skeleton.

### v3.1.1 {#v3-1-1}

* Fixes unavailable `Laravel\Lumen\Application::isBooted()` and instead use `Laravel\Lumen\Application::$booted`.
* Only redirecting with `.htaccess` . 

### v3.1.0 {#v3-1-0}

* Initial release based on latest Lumen Framework v5.1.x.
