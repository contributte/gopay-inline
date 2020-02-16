<?php

use Contributte\GopayInline\Api\Entity\PaymentFactory;
use Contributte\GopayInline\Api\Lists\Currency;
use Contributte\GopayInline\Api\Lists\Language;
use Contributte\GopayInline\Api\Lists\PaymentInstrument;
use Contributte\GopayInline\Api\Lists\SwiftCode;
use Contributte\GopayInline\Client;
use Contributte\GopayInline\Config;

// Load composer
require_once __DIR__ . '/../vendor/autoload.php';

$goId = '***FILL***';
$clientId = '***FILL***';
$clientSecret = '***FILL***';

// Create client
$client = new Client(new Config($goId, $clientId, $clientSecret));

// Payment data
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
	'amount' => \Money\Money::CZK(50000),
	'order_number' => '001',
	'order_description' => 'some order',
	'items' => [
		['name' => 'item01', 'amount' => \Money\Money::CZK(40000)],
		['name' => 'item02', 'amount' => \Money\Money::CZK(13000)],
		['name' => 'item03', 'amount' => \Money\Money::CZK(7000)],
	],
	'eet' => [
		'celk_trzba' => \Money\Money::CZK(50000),
		'zakl_dan1' => \Money\Money::CZK(35000),
		'dan1' => \Money\Money::CZK(5000),
		'zakl_dan2' => \Money\Money::CZK(8000),
		'dan2' => \Money\Money::CZK(2000),
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

// Create payment request
$response = $client->payments->createPayment(PaymentFactory::create($payment));

var_dump($response);
