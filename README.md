Lumen
==============

[![Join the chat at https://gitter.im/orchestral/lumenate](https://badges.gitter.im/Join%20Chat.svg)](https://gitter.im/orchestral/lumenate?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)

This repository contains the core code of the Orchestra Lumen. If you want to build an application using Orchestra Platform, visit [the main repository](https://github.com/orchestral/platform).

[![Latest Stable Version](https://img.shields.io/github/release/orchestral/lumen.svg?style=flat-square)](https://packagist.org/packages/orchestra/lumen)
[![Total Downloads](https://img.shields.io/packagist/dt/orchestra/lumen.svg?style=flat-square)](https://packagist.org/packages/orchestra/lumen)
[![MIT License](https://img.shields.io/packagist/l/orchestra/lumen.svg?style=flat-square)](https://packagist.org/packages/orchestra/lumen)
[![Build Status](https://img.shields.io/travis/orchestral/lumen/3.1.svg?style=flat-square)](https://travis-ci.org/orchestral/lumen)
[![Scrutinizer Quality Score](https://img.shields.io/scrutinizer/g/orchestral/lumen/3.1.svg?style=flat-square)](https://scrutinizer-ci.com/g/orchestral/lumen/)

* [Installation]

### Installation

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
}
```
