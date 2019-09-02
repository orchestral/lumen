Lumen Framework for Orchestra Platform
==============

This repository contains the core code of the Orchestra Lumen. If you want to build an application using Orchestra Platform, visit [the main repository](https://github.com/orchestral/platform).

[![Build Status](https://travis-ci.org/orchestral/lumen.svg?branch=4.x)](https://travis-ci.org/orchestral/lumen)
[![Latest Stable Version](https://poser.pugx.org/orchestra/lumen/version)](https://packagist.org/packages/orchestra/lumen)
[![Total Downloads](https://poser.pugx.org/orchestra/lumen/downloads)](https://packagist.org/packages/orchestra/lumen)
[![Latest Unstable Version](https://poser.pugx.org/orchestra/lumen/v/unstable)](//packagist.org/packages/orchestra/lumen)
[![License](https://poser.pugx.org/orchestra/lumen/license)](https://packagist.org/packages/orchestra/lumen)

* [Installation](#installation)
* [API Routing](#api-routing)
* [JWT Authentication](#jwt-authentication)

## Installation

First, install the Lumenate installer and make sure that the global Composer `bin` directory is within your system's `$PATH`:

    composer global require "orchestra/lumenate=~0.4"

From within a working Orchestra Platform project, run the following command:

    lumenate install

After installing Lumen, you can also opt to add the base Lumen application skeleton under `lumen` folder, you can do this by running:

    lumenate make

You can also choose to add new path to autoload to detect `lumen/app` using PSR-4 or use a single `app` directory.

```json
{
    "autoload": {
        "psr-4": {
            "App\\": "app/",
        }
    },
    "autoload-dev": {
        "classmap": [
            "tests/LumenTestCase.php",
            "tests/TestCase.php"
        ]
    },

    "prefer-stable": true,
    "minimum-stability": "dev"
}
```

> It is recommended for you to set `"prefer-stable": true` and `"minimum-stability": "dev"` as both `laravie/api` and `tymon/jwt-auth` doesn't have a stable release for latest Lumen yet.

## API Routing

Dingo API is preinstall with Lumen. To start using it you just need to uncomment the following from `lumen/bootstrap.php`:

```php
require base_path('routes/api.php');
```

## JWT Authentication

Install `tymon/jwt-auth` via the command line:

    composer require "tymon/jwt-auth=^1.0"

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
<?php 

namespace App;

use App\Lumen\User as Eloquent;

class User extends Eloquent
{
    //
}
```
