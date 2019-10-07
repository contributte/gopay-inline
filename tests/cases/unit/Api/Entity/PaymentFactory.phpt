<?php

/**
 * Test: Api\Entity\PaymentFactory
 */
use Contributte\GopayInline\Api\Entity\Payment;
use Contributte\GopayInline\Api\Entity\PaymentFactory;
use Contributte\GopayInline\Api\Lists\PaymentType;
use Contributte\GopayInline\Api\Lists\TargetType;
use Contributte\GopayInline\Api\Objects\Eet;
use Contributte\GopayInline\Exception\ValidationException;
use Tester\Assert;

require __DIR__ . '/../../../../bootstrap.php';

// Required fields
test(function () {
	Assert::throws(function () {
		PaymentFactory::create([]);
	}, ValidationException::class, '%a%' . implode(', ', PaymentFactory::$required) . '%a%');
});

// Not allowed field
test(function () {
	$required = [
		'amount' => 1,
		'currency' => 2,
		'order_number' => 3,
		'order_description' => 4,
		'items' => 5,
		'return_url' => 6,
		'notify_url' => 7,
	];
	$fields = [
		'foo' => 8,
		'bar' => 9,
	];
	Assert::throws(function () use ($required, $fields) {
		PaymentFactory::create(array_merge($required, $fields));
	}, ValidationException::class, '%a%' . implode(', ', array_keys($fields)) . '%a%');
});

// Simple payment
test(function () {
	$eet = [
		'celk_trzba' => 550,
		'zakl_dan1' => 100,
		'dan1' => 50,
		'zakl_dan2' => 300,
		'dan2' => 100,
	];
	$data = [
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
		'target' => [
			'goid' => 123456,
			'type' => TargetType::ACCOUNT,
		],
		'amount' => 550,
		'currency' => 'CZK',
		'order_number' => '001',
		'order_description' => 'pojisteni01',
		'items' => [
			['name' => 'item01', 'amount' => 100, 'count' => 2],
			['name' => 'item02', 'amount' => 100],
			['name' => 'item03', 'amount' => 150, 'vat_rate' => 21],
			['name' => 'item04', 'amount' => 200, 'type' => PaymentType::ITEM],
		],
		'eet' => $eet,
		'additional_params' => [
			['name' => 'invoicenumber', 'value' => '2015001003'],
		],
		'return_url' => 'http://www.eshop.cz/return',
		'notify_url' => 'http://www.eshop.cz/notify',
		'lang' => 'cs',
	];

	$payment = PaymentFactory::create($data);
	Assert::type(Payment::class, $payment);
	Assert::equal(21, $payment->getItems()[2]->getVatRate());
	Assert::equal(PaymentType::ITEM, $payment->getItems()[3]->getType());
	Assert::type(Eet::class, $payment->getEet());
	Assert::false($payment->isPreauthorization());
});

// Preauthorized payment
test(function () {
	$data = [
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
		'target' => [
			'goid' => 123456,
			'type' => TargetType::ACCOUNT,
		],
		'amount' => 550,
		'currency' => 'CZK',
		'order_number' => '001',
		'order_description' => 'pojisteni01',
		'items' => [
			['name' => 'item01', 'amount' => 100, 'count' => 2],
			['name' => 'item02', 'amount' => 100],
			['name' => 'item03', 'amount' => 150, 'vat_rate' => 21],
			['name' => 'item04', 'amount' => 200, 'type' => PaymentType::ITEM],
		],
		'preauthorization' => TRUE,
		'additional_params' => [
			['name' => 'invoicenumber', 'value' => '2015001003'],
		],
		'return_url' => 'http://www.eshop.cz/return',
		'notify_url' => 'http://www.eshop.cz/notify',
		'lang' => 'cs',
	];

	$payment = PaymentFactory::create($data);
	Assert::type(Payment::class, $payment);
	Assert::equal(21, $payment->getItems()[2]->getVatRate());
	Assert::equal(PaymentType::ITEM, $payment->getItems()[3]->getType());
	Assert::true($payment->isPreauthorization());
});

// Validate order price and items price
test(function () {
	$data = [
		'amount' => 200,
		'currency' => 2,
		'order_number' => 3,
		'order_description' => 4,
		'items' => [
			['name' => 'Item 01', 'amount' => 100, 'count' => 2],
			['name' => 'Item 01', 'amount' => 50],
		],
		'return_url' => 6,
		'notify_url' => 7,
	];

	Assert::throws(function () use ($data) {
		PaymentFactory::create($data);
	}, ValidationException::class, '%a% (200) %a% (150) %a%');
});

// Validate items name
test(function () {
	$data = [
		'amount' => 200,
		'currency' => 2,
		'order_number' => 3,
		'order_description' => 4,
		'items' => [
			['amount' => 50],
		],
		'return_url' => 6,
		'notify_url' => 7,
	];

	Assert::throws(function () use ($data) {
		PaymentFactory::create($data);
	}, ValidationException::class, "Item's name can't be empty or null.");
});

// Turn off validators
test(function () {
	// Invalid total price and items price
	$data = [
		'amount' => 200,
		'currency' => 2,
		'order_number' => 3,
		'order_description' => 4,
		'items' => [
			['name' => 'Item 01', 'amount' => 50],
			['name' => 'Item 02', 'amount' => 50],
		],
		'return_url' => 6,
		'notify_url' => 7,
	];

	try {
		PaymentFactory::create($data, [PaymentFactory::V_PRICES => FALSE]);
	} catch (Exception $e) {
		Assert::fail('Exception should not have been threw', $e, NULL);
	}

	// Invalid scheme
	$data = [
		'amount' => 100,
		'currency' => 2,
		'order_number' => 3,
		'order_description' => 4,
		'items' => [
			['amount' => 50],
			['amount' => 50],
		],
		'return_url' => 6,
		'notify_url' => 7,
		'x_unknown' => 1234,
		'y_foobar' => 5678,
	];

	try {
		PaymentFactory::create($data, [PaymentFactory::V_SCHEME => FALSE]);
	} catch (Exception $e) {
		Assert::fail('Exception should not have been threw', $e, NULL);
	}
});

// Validate EET sum and EET tax sum
test(function () {
	$data = [
		'amount' => 200,
		'currency' => 2,
		'order_number' => 3,
		'order_description' => 4,
		'items' => [
			['name' => 'Item 01', 'amount' => 150, 'count' => 3],
			['name' => 'Item 01', 'amount' => 50],
		],
		'return_url' => 6,
		'notify_url' => 7,
		'eet' => [
			'celk_trzba' => 200,
			'zakl_dan1' => 80,
			'dan1' => 30,
			'zakl_dan2' => 50,
			'dan2' => 20,
		],
	];

	Assert::throws(function () use ($data) {
		PaymentFactory::create($data);
	}, ValidationException::class, '%a% (200) %a% (180) %a%');
});

// Validate EET sum and order sum
test(function () {
	$data = [
		'amount' => 100,
		'currency' => 2,
		'order_number' => 3,
		'order_description' => 4,
		'items' => [
			['name' => 'Item 01', 'amount' => 100, 'count' => 2],
		],
		'return_url' => 6,
		'notify_url' => 7,
		'eet' => [
			'celk_trzba' => 110,
			'zakl_dan1' => 80,
			'dan1' => 30,
		],
	];

	Assert::throws(function () use ($data) {
		PaymentFactory::create($data);
	}, ValidationException::class, '%a% (110) %a% (100) %a%');
});

// Validate EET sum and order sum (double/float)
test(function () {
	$data = [
		'amount' => 174.0,
		'currency' => 2,
		'order_number' => 3,
		'order_description' => 4,
		'items' => [
			['name' => 'x', 'amount' => 174.0],
		],
		'return_url' => 6,
		'notify_url' => 7,
		'eet' => [
			'celk_trzba' => 174.0,
			'zakl_dan1' => 143.80165289256,
			'dan1' => 30.198347107438,
		],
	];

	try {
		PaymentFactory::create($data);
	} catch (ValidationException $e) {
		Assert::fail('EET sum and EET tax should be equal');
	}
});

// Validate EET sum and order sum (item without VAT)
test(function () {
	$data = [
		'amount' => 274.0,
		'currency' => 2,
		'order_number' => 3,
		'order_description' => 4,
		'items' => [
			['name' => 'x', 'amount' => 274.0],
		],
		'return_url' => 6,
		'notify_url' => 7,
		'eet' => [
			'celk_trzba' => 274.0,
			'zakl_nepodl_dph' => 100.0,
			'zakl_dan1' => 143.80165289256,
			'dan1' => 30.198347107438,
		],
	];
	try {
		PaymentFactory::create($data);
	} catch (ValidationException $e) {
		Assert::fail('EET sum and EET tax should be equal with item with no VAT');
	}
});
