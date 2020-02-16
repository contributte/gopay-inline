# Contributte GopayInline

## Prolog

This library has no dependencies on other `php` libraries. It provides easy-to-use API for communication with `GoPay REST API v3`, known as **GoPay Inline**.

## Install

Install **Markette/GopayInline** over composer.

```sh
$ composer require markette/gopay-inline
```

Why is the package still called Markette? Because we don't want to break other people's projects (for now).

## Migrating to v2

- Parameters `goId`, `clientId` and `clientSecret` in `GopayInline\Config` are strings. You may need to change your `config.neon` file.
- `Payment` works with `Money` object.

## Requirements

From GoPay you need:

* **GoID**
* **ClientID**
* **ClientSecret**

On server you need:

* PHP >= 5.6
* cURL

## Resources / Docs

* Webpage ([https://www.gopay.com](https://www.gopay.com))
* Offical resources in EN ([https://doc.gopay.com/en/](https://doc.gopay.com/en/))
* Offical resources in CZ ([https://doc.gopay.com/cs/](https://doc.gopay.com/cs/))

## Examples

All you can find in [examples folder](https://github.com/Contributte/GopayInline/blob/master/examples).

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
use Contributte\GopayInline\Client;
use Contributte\GopayInline\Config;

$goId = 'GoID';
$clientId = 'ClientID';
$clientSecret = 'ClientSecret';

// TEST MODE
$client = new Client(new Config($goId, $clientId, $clientSecret));
$client = new Client(new Config($goId, $clientId, $clientSecret, $mode = Config::TEST));

// PROD MODE
$client = new Client(new Config($goId, $clientId, $clientSecret, $mode = Config::PROD));
```

Then you have to authenticate with oauth2 authority server on GoPay.

For only creating payments use `Scope::PAYMENT_CREATE`, for the rest `Scope::PAYMENT_ALL`.

```php
use Contributte\GopayInline\Api\Lists\Scope;

$token = $client->authenticate(['scope' => Scope::PAYMENT_CREATE]);
```

Heureka! We have token, let's make some API request.

### Creating payment request

This example of payment data was copied from official documentation.

```php
use Contributte\GopayInline\Api\Entity\PaymentFactory;
use Contributte\GopayInline\Api\Lists\Currency;
use Contributte\GopayInline\Api\Lists\Language;
use Contributte\GopayInline\Api\Lists\PaymentInstrument;
use Contributte\GopayInline\Api\Lists\SwiftCode;

$payment = [
	'payer' => [
		'default_payment_instrument' => PaymentInstrument::BANK_ACCOUNT,
		'allowed_payment_instruments' => [PaymentInstrument::BANK_ACCOUNT],
		'default_swift' => SwiftCode::FIO_BANKA,
		'allowed_swifts' => [SwiftCode::FIO_BANKA, SwiftCode::MBANK],
		'contact' => [
			'first_name' => 'John',
			'last_name' => 'Doe',
			'email' => 'johndoe@contributte.org',
			'phone_number' => '+420123456789',
			'city' => 'Prague',
			'street' => 'Contributte 123',
			'postal_code' => '123 45',
			'country_code' => 'CZE',
		],
	],
	'amount' => 50000,
	'currency' => Currency::CZK,
	'order_number' => '001',
	'order_description' => 'some order',
	'items' => [
		['name' => 'item01', 'amount' => 40000],
		['name' => 'item02', 'amount' => 13000],
		['name' => 'item03', 'amount' => 7000],
	],
	'eet' => [
		'celk_trzba' => 50000,
		'zakl_dan1' => 35000,
		'dan1' => 5000,
		'zakl_dan2' => 8000,
		'dan2' => 2000,
		'mena' => Currency::CZK,
	],
	'additional_params' => [
		['name' => 'invoicenumber', 'value' => '2017001'],
	],
	'callback' => [
		'return_url' => 'http://www.myeshop.cz/api/gopay/return',
		'notify_url' => 'http://www.myeshop.cz/api/gopay/notify',
	],
	'lang' => Language::CZ,
];
```

```php
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
use Contributte\GopayInline\Api\Lists\PaymentInstrument;

$payment['payer']['allowed_payment_instruments']= PaymentInstrument::all();
```

#### For ALL / CZ / SK swift codes

Use `allowed_swifts` and `default_swift` only with `BANK_ACCOUNT`.

```php
use Contributte\GopayInline\Api\Lists\SwiftCode;

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

In case of inline variant you can use prepared [javascript](https://github.com/Contributte/GopayInline/blob/master/client-side) (under development at this moment).

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
    gopay: Contributte\GopayInline\Bridges\Nette\DI\GopayExtension

gopay:
    goId: ***
    clientId: ***
    clientSecret: ***
    test: on / off
```

Inject `Client` into your services / presenters;

```php
use Contributte\GopayInline\Client;

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
