# Evoliz PHP

[//]: # (@Todo : Put some badges : https://poser.pugx.org/)
[![PHP Version Require](http://poser.pugx.org/evoliz/evoliz-php/require/php)](https://packagist.org/packages/evoliz/evoliz-php)
[![Latest Stable Version](http://poser.pugx.org/evoliz/evoliz-php/v)](https://packagist.org/packages/evoliz/evoliz-php)
[![Total Downloads](http://poser.pugx.org/evoliz/evoliz-php/downloads)](https://packagist.org/packages/evoliz/evoliz-php)
[![License](http://poser.pugx.org/evoliz/evoliz-php/license)](https://packagist.org/packages/evoliz/evoliz-php)

The Evoliz PHP library provides convenient access to the Evoliz API from
applications written in the PHP language. It includes a pre-defined set of
classes for API resources that initialize themselves dynamically from API
responses which makes it compatible with a wide range of versions of the Evoliz
API.

## Requirements

PHP 8.0.0 and later.

## Composer

You can install the package via [Composer](http://getcomposer.org/). Run the following command:

```bash
composer require evoliz/evoliz-php
```

To use the package, use Composer's [autoload](https://getcomposer.org/doc/01-basic-usage.md#autoloading):

```php
require_once('vendor/autoload.php');
```

## Dependencies

The package require the following extensions in order to work properly:

-   [`guzzlehttp/guzzle`](https://packagist.org/packages/guzzlehttp/guzzle), from release 7.0
-   [`json`](https://secure.php.net/manual/en/book.json.php)

If you use Composer, these dependencies should be handled automatically.

## Getting Started

Simple usage looks like:

```php
$config = new Evoliz\Client\Config('YOUR_COMPANYID', 'YOUR_PUBLIC_KEY', 'YOUR_SECRET_KEY');
$clientRepository = new ClientRepository($config);

$client = $clientRepository->create(new Client([
    'name' => 'Evoliz',
    'type' => 'Particulier',
    'address' => [
        'postcode' => '83130',
        'town' => 'La Garde',
        'iso2' => 'FR'
    ]
]));
```

### Integration examples

You can find all the examples for each endpoint in the [examples folder](https://github.com/evoliz/evoliz-php/tree/master/examples).

## Documentation

See the [PHP API docs](https://evoliz.io/documentation).

See the [API changelog](https://evoliz.io/changelog) to see all the changes made to the API.

## Legacy Version Support

### PHP < 8.0.0

If you are using a version earlier than PHP 8.0.0, you need to upgrade your environment to use Evoliz.

## Development

Get [Composer](https://getcomposer.org/).

Install dependencies:

```bash
composer install
```

Install dependencies as mentioned above (which will resolve [PHPUnit](http://packagist.org/packages/phpunit/phpunit)), then you can run the test suite:

```bash
./vendor/bin/phpunit
```

Disable Guzzle SSL verification:

```php
HttpClient::setInstance(['verify' => false], [], true);
```
