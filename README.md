# ![Laravel-Esign](https://socialify.git.ci/XNXKTech/laravel-esign/image?font=Bitter&language=1&logo=https://avatars.githubusercontent.com/u/94216091?s=200&v=4&owner=1&pattern=Circuit%20Board&theme=Light)

[![Tests](https://github.com/XNXKTech/laravel-esign/actions/workflows/tests.yml/badge.svg)](https://github.com/XNXKTech/laravel-esign/actions/workflows/tests.yml)
![PHP from Packagist](https://img.shields.io/packagist/php-v/xnxktech/laravel-esign?style=flat-square)
![Packagist Version](https://img.shields.io/packagist/v/xnxktech/laravel-esign?style=flat-square)
![GitHub release (latest by date)](https://img.shields.io/github/v/release/xnxktech/laravel-esign?style=flat-square)
![GitHub last commit (branch)](https://img.shields.io/github/last-commit/xnxktech/laravel-esign/main?style=flat-square)
![GitHub Release Date](https://img.shields.io/github/release-date/xnxktech/laravel-esign?style=flat-square)
[![LICENSE](https://img.shields.io/badge/License-Anti%20996-blue.svg?style=flat-square)](https://github.com/996icu/996.ICU/blob/master/LICENSE)
[![LICENSE](https://img.shields.io/badge/License-Apache--2.0-green.svg?style=flat-square)](LICENSE-APACHE)
[![996.icu](https://img.shields.io/badge/Link-996.icu-red.svg?style=flat-square)](https://996.icu)

## Installation

```bash
$ composer require xnxktech/laravel-esign
```

## Configuration

generate config file

```bash
$ php artisan vendor:publish --provider="XNXK\LaravelEsign\ServiceProvider"
```

## Usage

The package will auto use environment variables, Put them in your .env as the following, obviously and respectively.

```env
ESIGN_APPID=
ESIGN_SECRET=
ESIGN_SERVER=
ESIGN_NOTIFY_URL=
```

Lastly, you can using Esign class in controller use namespace top of that file

```php

use XNXK\LaravelEsign\Esign;

$data = (new Esign)->account()->queryPersonalAccountByThirdId();
```

or if you want a simple, you can use esign function:

```php

esign()->account()->queryPersonalAccountByThirdId();
```

### Organization

```php
esign()->account()->createOrganizeAccount($orgThirdPartyUserId, 'b5b9c524fa254c0fbf2150c98b87ac11', $name);
```

## License

The code in this repository, unless otherwise noted, is under the terms of both the [Anti 996](https://github.com/996icu/996.ICU/blob/master/LICENSE) License and the [Apache License (Version 2.0)]().