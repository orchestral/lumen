Lumen Framework for Orchestra Platform
==============

[![Join the chat at https://gitter.im/orchestral/lumenate](https://badges.gitter.im/Join%20Chat.svg)](https://gitter.im/orchestral/lumenate?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)

This repository contains the core code of the Orchestra Lumen. If you want to build an application using Orchestra Platform, visit [the main repository](https://github.com/orchestral/platform).

[![Latest Stable Version](https://img.shields.io/github/release/orchestral/lumen.svg?style=flat-square)](https://packagist.org/packages/orchestra/lumen)
[![Total Downloads](https://img.shields.io/packagist/dt/orchestra/lumen.svg?style=flat-square)](https://packagist.org/packages/orchestra/lumen)
[![MIT License](https://img.shields.io/packagist/l/orchestra/lumen.svg?style=flat-square)](https://packagist.org/packages/orchestra/lumen)
[![Build Status](https://img.shields.io/travis/orchestral/lumen/3.2.svg?style=flat-square)](https://travis-ci.org/orchestral/lumen)
[![Scrutinizer Quality Score](https://img.shields.io/scrutinizer/g/orchestral/lumen/3.2.svg?style=flat-square)](https://scrutinizer-ci.com/g/orchestral/lumen/)

* [Installation](#installation)
* [API Routing](#api-routing)
* [JWT Authentication](#jwt-authentication)

## Installation

First, install the Lumenate installer and make sure that the global Composer `bin` directory is within your system's `$PATH`:

    composer global require "orchestra/lumenate=^0.1"

From within a working Orchestra Platform project, run the following command:

    lumenate install

After installing Lumen, you can also opt to add the base Lumen application skeleton under `lumen` folder, you can do this by running:

    lumenate make

You can also choose to add new path to autoload to detect `lumen/app` using PSR-4 or use a single `app` directory.

```json
{
    "autoload": {
        "psr-4": {
            "App\\Lumen\\": "lumen/app/",
            "App\\": "app/",
        }
    },
    "autoload-dev": {
        "classmap": [
            "lumen/tests/LumenTestCase.php",
            "tests/TestCase.php"
        ]
    },

    "prefer-stable": true,
    "minimum-stability": "dev"
}
```

> It is recommended for you to set `"prefer-stable": true` and `"minimum-stability": "dev"` as both `dingo/api` and `tymon/jwt-auth` doesn't have a stable release for latest Lumen yet.

## API Routing

Install `dingo/api` via the command line:

    composer require "dingo/api=~1.0"

Next, enable the following service provider from `lumen/bootstrap.php`:

```php
$app->register(Dingo\Api\Provider\LumenServiceProvider::class);
```

Finally, you can use `lumen/api.php` to register available routes for your API. To do this first you need to require the file from `lumen/bootstrap.php`:

```php
require __DIR__.'/api.php';
```

## JWT Authentication

Install `tymon/jwt-auth` via the command line:

    composer require "tymon/jwt-auth=~0.6"

Next, enable the following service providers from `lumen/bootstrap.php`:

```php
$app->register(Tymon\JWTAuth\Providers\LumenServiceProvider::class);

// ...

$app->register(App\Lumen\Providers\AuthServiceProvider::class);
```

Next, we need to create a secret key for JWT:

    php lumen/artisan jwt:secret

This would add `JWT_SECRET` value to your main `.env` file.

Finally you can extends the default `App\User` model to support `Tymon\JWTAuth\Contracts\JWTSubject`:

```php
<?php namespace App;

use App\Lumen\User as Eloquent;

class User extends Eloquent
{
    //
}
