Allows you to seamlessly send data to a Statsd server from within your Laravel application.

[![Packagist License](https://poser.pugx.org/barryvdh/laravel-debugbar/license.png)](http://choosealicense.com/licenses/mit/)
[![Build Status](https://travis-ci.org/Spiria-Digital/laravel-statsd.svg?branch=master)](https://travis-ci.org/Spiria-Digital/laravel-statsd)

Installation
============

Add `Spiria-Digital\laravel-statsd` package with composer

```php
composer require Spiria-Digital/laravel-statsd:5.5.1
```

Update your packages with `composer update` or install with `composer install`.

**Laravel >5.5**

Since version 5.5, Laravel support Package Auto-Discovery, which means that Service Provider and aliases can be added automatically through the package composer file. Therefore, once Composer has installed or updated your packages Laravel-Statsd should be registered as a service provider and should provide the `Statsd` Facade.

**Laravel <5.4**

If you are using an older verison of Laravel you must add the provider and the alias manually. 

You can register Statsd with Laravel by opening up app/config/app.php and adding the providers key in the `providers` array:

```php
'Spiria-Digital\Statsd\StatsdServiceProvider'
```

You will also need to register the facade so that you can access it within your application. To do this add the following to your `aliases` in app/config/app.php:

```php
'Statsd' => 'Spiria-Digital\Statsd\Facades\Statsd'
```

Configuration
=============

Statsd configuration file can be extended by creating `app/config/statsd.php`. You can find the default configuration file under `vendor/Spiria-Digital/laravel-statsd/config/config.php`.

You can quickly publish a configuration file by running the following Artisan command.

```
$ php artisan config:publish Spiria-Digital/laravel-statsd
```

Usage
=====

Laravel-Statsd exposes the following functions to send data to Statsd:

```php
Statsd::timing($key, $time);
```

```php
Statsd::gauge($key, $value);
```

```php
Statsd::set($key, $value);
```

```php
Statsd::increment($key);
```

```php
Statsd::decrement($key);
```

```php
Statsd::updateCount($key, $delta);
```

The data is automatically sent to Statsd at the end of Laravels life-cycle, but you can force data to be sent with:

```php
Statsd::send()
```

Note: Data will only be sent to Statsd if your environment matches the environments defined in the config file.

This package is an updated version of rcrowe/laravel-statsd and has been tested with laravel 5.4+ only. It does not currently support Lumen.