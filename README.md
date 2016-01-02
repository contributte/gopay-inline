# Markette :: GopayInline

[![Build Status](https://img.shields.io/travis/Markette/GopayInline.svg?style=flat-square)](https://travis-ci.org/Markette/GopayInline)
[![Code coverage](https://img.shields.io/coveralls/Markette/GopayInline.svg?style=flat-square)](https://coveralls.io/r/Markette/GopayInline)
[![Downloads latests](https://img.shields.io/packagist/dt/markette/gopay-inline.svg?style=flat-square)](https://packagist.org/packages/markette/gopay-inline)
[![Latest stable](https://img.shields.io/packagist/v/markette/gopay-inline.svg?style=flat-square)](https://packagist.org/packages/markette/gopay-inline)
[![HHVM Status](https://img.shields.io/hhvm/markette/gopay-inline.svg?style=flat-square)](http://hhvm.h4cc.de/package/markette/gopay-inline)

## Prolog

This library has no dependencies on other `php` libraries. It provides easy-to-use API for communication with `GoPay REST API v3`, known as **GoPay Inline**.

## Install

Install **Markette/GopayInline** over composer.

```sh
$ composer require markette/gopay-inline
```

## Requirements

From GoPay you need:

* **GoID**
* **ClientID**
* **ClientSecret**

On server you need:

* PHP >= 5.5
* cURL

## Resources / Docs

* Webpage ([https://www.gopaygate.com](https://www.gopaygate.com))
* Offical resources in EN ([https://doc.gopay.com/en/](https://doc.gopay.com/en/))
* Offical resources in CZ ([https://doc.gopay.com/cs/](https://doc.gopay.com/cs/))

## Examples

All you can find in [examples folder](https://github.com/Markette/GopayInline/blob/master/examples).

## Library

There are 3 main parts of this library.

### 1) Client

A core class holding credentials, token, authenticator and http client. It could make authentication and requests to endpoints.

### 2) HttpClient

Delegates all requests / responses to IO. All requests go over `cURL`. There is a place for other implementation, go for it.

### 3) Services

Services provide easy-to-use API for creating and verifying payments.

## Supported API

* Verify payments (`$client->payments->verify(..)`)
* Standard payments (`$client->payments->createPayment(..)`)
* Recurrent payments (`$client->payments->createRecurrentPayment(..)`)
* Preauthorized payments (`$client->payments->createPreauthorizedPayment(..)`)

## Usage

### Authentication

First you need set up client with credentials.

```php
use Markette\GopayInline\Client;
use Markette\GopayInline\Config;

$goId = '***FILL***';
$clientId = '***FILL***';
$clientSecret = '***FILL***';

// TEST MODE
$client = new Client(new Config($goId, $clientId, $clientSecret));
$client = new Client(new Config($goId, $clientId, $clientSecret, $mode = Config::TEST));

// PROD MODE
$client = new Client(new Config($goId, $clientId, $clientSecret, $mode = Config::PROD));
```

Then you have to authenticate with oauth2 authority server on GoPay.

For only creating payments use `Scope::PAYMENT_CREATE`, for the rest `Scope::PAYMENT_ALL`.

```php
use Markette\GopayInline\Api\Lists\Scope;

$token = $client->authenticate(Scope::PAYMENT_CREATE);
```

Heureka! We have token, let's make some API request.

### Creating payment request

This example of payment data was copied from official documentation.

```php
// Payment data
$payment = [
    'payer' => [
        'default_payment_instrument' => 'BANK_ACCOUNT',
        'allowed_payment_instruments' => ['BANK_ACCOUNT'],
        'default_swift' => 'FIOBCZPP',
        'allowed_swifts' => ['FIOBCZPP', 'BREXCZPP'],
        'contact' => [
            'first_name' => 'Zbynek',
            'last_name' => 'Zak',
            'email' => 'zbynek.zak@gopay.cz',
            'phone_number' => '+420777456123',
            'city' => 'C.Budejovice',
            'street' => 'Plana 67',
            'postal_code' => '373 01',
            'country_code' => 'CZE',
        ],
    ],
    'amount' => 150,
    'currency' => 'CZK',
    'order_number' => '001',
    'order_description' => 'pojisteni01',
    'items' => [
        ['name' => 'item01', 'amount' => 50, 'count' => 2],
        ['name' => 'item02', 'amount' => 100],
    ],
    'additional_params' => [
        array('name' => 'invoicenumber', 'value' => '2015001003')
    ],
    'return_url' => 'http://www.your-url.tld/return',
    'notify_url' => 'http://www.your-url.tld/notify',
    'lang' => 'cs',
];

// Create payment request
$response = $client->payments->createPayment(PaymentFactory::create($payment));
$data = $response->getData();
```

`$client->payments` returns `PaymentsService`, you can create this service also by `$client->createPaymentsService()`.

`PaymentsService::createPayment` need object of `Payment`, you can set-up it manually by yourself or via `PaymentFactory`.
But over PaymentFactory, there is parameters validation and price validation.

#### Tips

You cannot combine more **payment instruments** (according to GoPay Gateway implementation). So, you should create payment
only with one **payment instrument**, for example only with `BANK_ACCOUNT` or `PAYMENT_CARD`.

#### For ALL payment instruments

```php
use Markette\GopayInline\Api\Lists\PaymentInstrument;

$payment['payer']['allowed_payment_instruments']= PaymentInstrument::all();
```

#### For ALL / CZ / SK swift codes

Use `allowed_swifts` and `default_swift` only with `BANK_ACCOUNT`.

```php
use Markette\GopayInline\Api\Lists\SwiftCode;

$payment['payer']['allowed_swifts']= SwiftCode::all();
// or
$payment['payer']['allowed_swifts']= SwiftCode::cz();
// or
$payment['payer']['allowed_swifts']= SwiftCode::sk();
```

### Process payment

Now we have a response with payment information. There's same data as we send it before and also **new** `$gw_url`. It's in response data.

```php
if ($response->isSuccess()) {
    // ...
}
```

```php
$data = $response->getData();
$url = $data['gw_url'];

$url = $response->data['gw_url'];
$url = $response->gw_url;
$url = $response['gw_url'];

// Redirect to URL
// ...

// Send over AJAX to inline variant
// ...
```

In case of inline variant you can use prepared [javascript](https://github.com/Markette/GopayInline/blob/master/client-side).

@TODO

### Verify payment (check state)

All you need is `$paymentId`. Response is always the same.

```php
// Verify payment
$response = $client->payments->verify($paymentId);
```

## Bridges

### Nette

Fill your credentials in config.

```yaml
extensions:
    gopay: Markette\GopayInline\Bridges\Nette\GopayExtension
    
gopay:
    goId: ***
    clientId: ***
    clientSecret: ***
    test: on / off
```

Inject `Client` into your services / presenters;

```php
use Markette\GopayInline\Client;

/** @var Client @inject */
public $gopay;
```

## Class model

### Request

It contains information for cURL.

* `$url`
* `$headers`
* `$options`
* `$data`

### Response

It contains information after execution request. It could be success or failed.

* `$data`
* `$headers`
* `$code`
* `$error`
