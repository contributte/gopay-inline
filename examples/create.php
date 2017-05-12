<?php

use Markette\GopayInline\Api\Entity\PaymentFactory;
use Markette\GopayInline\Api\Lists\Currency;
use Markette\GopayInline\Api\Lists\Language;
use Markette\GopayInline\Api\Lists\PaymentInstrument;
use Markette\GopayInline\Api\Lists\SwiftCode;
use Markette\GopayInline\Client;
use Markette\GopayInline\Config;

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
			'email' => 'johndoe@markette.org',
			'phone_number' => '+420123456789',
			'city' => 'Prague',
			'street' => 'Markette 123',
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
	'return_url' => 'http://www.myeshop.cz/api/gopay/return',
	'notify_url' => 'http://www.myeshop.cz/api/gopay/notify',
	'lang' => Language::CZ,
];

// Create payment request
$response = $client->payments->createPayment(PaymentFactory::create($payment));

var_dump($response);
