<?php declare(strict_types = 1);

/**
 * Test: Api\Entity\PaymentFactory
 */

use Contributte\GopayInline\Api\Entity\Payment;
use Contributte\GopayInline\Api\Entity\PaymentFactory;
use Contributte\GopayInline\Api\Lists\PaymentType;
use Contributte\GopayInline\Api\Lists\TargetType;
use Contributte\GopayInline\Api\Objects\Eet;
use Contributte\GopayInline\Exception\ValidationException;
use Money\Money;
use Tester\Assert;

require __DIR__ . '/../../../../bootstrap.php';

// Required fields
test(function (): void {
	Assert::throws(function (): void {
		PaymentFactory::create([]);
	}, ValidationException::class, '%a%' . implode(', ', PaymentFactory::$required) . '%a%');
});

// Required callback fields
test(function () {
	Assert::throws(function () {
		PaymentFactory::create([
			'amount' => 200,
			'currency' => 2,
			'order_number' => 3,
			'order_description' => 4,
			'items' => [],
			'callback' => [
				'return_url' => '6',
			],
		]);
	}, ValidationException::class, 'Missing keys "notify_url" in callback definition');
});

// Not allowed field
test(function (): void {
	$required = [
		'amount' => Money::CZK(1),
		'order_number' => '3',
		'order_description' => '4',
		'items' => 5,
		'callback' => [
			'return_url' => 'http://www.eshop.cz/return',
			'notify_url' => 'http://www.eshop.cz/notify',
		],
	];
	$fields = [
		'foo' => 8,
		'bar' => 9,
	];
	Assert::throws(function () use ($required, $fields): void {
		PaymentFactory::create(array_merge($required, $fields));
	}, ValidationException::class, '%a%' . implode(', ', array_keys($fields)) . '%a%');
});

// Simple payment
test(function (): void {
	$eet = [
		'celk_trzba' => Money::CZK(550),
		'zakl_dan1' => Money::CZK(100),
		'dan1' => Money::CZK(50),
		'zakl_dan2' => Money::CZK(300),
		'dan2' => Money::CZK(100),
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
		'amount' => Money::CZK(550),
		'order_number' => '001',
		'order_description' => 'pojisteni01',
		'items' => [
			['name' => 'item01', 'amount' => Money::CZK(50), 'count' => 2],
			['name' => 'item02', 'amount' => Money::CZK(100)],
			['name' => 'item03', 'amount' => Money::CZK(150), 'vat_rate' => 21],
			['name' => 'item04', 'amount' => Money::CZK(200), 'type' => PaymentType::ITEM],
		],
		'eet' => $eet,
		'additional_params' => [
			['name' => 'invoicenumber', 'value' => '2015001003'],
		],
		'callback' => [
			'return_url' => 'http://www.eshop.cz/return',
			'notify_url' => 'http://www.eshop.cz/notify',
		],
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
test(function (): void {
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
		'amount' => Money::CZK(550),
		'order_number' => '001',
		'order_description' => 'pojisteni01',
		'items' => [
			['name' => 'item01', 'amount' => Money::CZK(50), 'count' => 2],
			['name' => 'item02', 'amount' => Money::CZK(100)],
			['name' => 'item03', 'amount' => Money::CZK(150), 'vat_rate' => 21],
			['name' => 'item04', 'amount' => Money::CZK(200), 'type' => PaymentType::ITEM],
		],
		'preauthorization' => true,
		'additional_params' => [
			['name' => 'invoicenumber', 'value' => '2015001003'],
		],
		'callback' => [
			'return_url' => 'http://www.eshop.cz/return',
			'notify_url' => 'http://www.eshop.cz/notify',
		],
		'lang' => 'cs',
	];

	$payment = PaymentFactory::create($data);
	Assert::type(Payment::class, $payment);
	Assert::equal(21, $payment->getItems()[2]->getVatRate());
	Assert::equal(PaymentType::ITEM, $payment->getItems()[3]->getType());
	Assert::true($payment->isPreauthorization());
});

// Validate order price and items price
test(function (): void {
	$data = [
		'amount' => Money::CZK(200),
		'order_number' => '3',
		'order_description' => '4',
		'items' => [
			['name' => 'Item 01', 'amount' => Money::CZK(50), 'count' => 2],
			['name' => 'Item 01', 'amount' => Money::CZK(50)],
		],
		'callback' => [
			'return_url' => '6',
			'notify_url' => '7',
		],
	];

	Assert::throws(function () use ($data): void {
		PaymentFactory::create($data);
	}, ValidationException::class, '%a% (200) %a% (150) %a%');
});

// Validate items name
test(function (): void {
	$data = [
		'amount' => Money::CZK(200),
		'order_number' => '3',
		'order_description' => '4',
		'items' => [
			['amount' => Money::CZK(50)],
		],
		'callback' => [
			'return_url' => '6',
			'notify_url' => '7',
		],
	];

	Assert::throws(function () use ($data): void {
		PaymentFactory::create($data);
	}, ValidationException::class, "Item's name can't be empty or null.");
});

// Turn off validators
test(function (): void {
	// Invalid total price and items price
	$data = [
		'amount' => Money::CZK(200),
		'order_number' => '3',
		'order_description' => '4',
		'items' => [
			['name' => 'Item 01', 'amount' => Money::CZK(50)],
			['name' => 'Item 02', 'amount' => Money::CZK(50)],
		],
		'callback' => [
			'return_url' => '6',
			'notify_url' => '7',
		],
	];

	try {
		PaymentFactory::create($data, [PaymentFactory::V_PRICES => false]);
	} catch (Throwable $e) {
		Assert::fail('Exception should not have been threw', $e, null);
	}

	// Invalid scheme
	$data = [
		'amount' => Money::CZK(100),
		'order_number' => '3',
		'order_description' => '4',
		'items' => [
			['amount' => Money::CZK(50)],
			['amount' => Money::CZK(50)],
		],
		'callback' => [
			'return_url' => '6',
			'notify_url' => '7',
		],
		'x_unknown' => 1234,
		'y_foobar' => 5678,
	];

	try {
		PaymentFactory::create($data, [PaymentFactory::V_SCHEME => false]);
	} catch (Throwable $e) {
		Assert::fail('Exception should not have been threw', $e, null);
	}
});

// Validate EET sum and EET tax sum
test(function (): void {
	$data = [
		'amount' => Money::CZK(200),
		'order_number' => '3',
		'order_description' => '4',
		'items' => [
			['name' => 'Item 01', 'amount' => Money::CZK(50), 'count' => 3],
			['name' => 'Item 01', 'amount' => Money::CZK(50)],
		],
		'callback' => [
			'return_url' => '6',
			'notify_url' => '7',
		],
		'eet' => [
			'celk_trzba' => Money::CZK(200),
			'zakl_dan1' => Money::CZK(80),
			'dan1' => Money::CZK(30),
			'zakl_dan2' => Money::CZK(50),
			'dan2' => Money::CZK(20),
		],
	];

	Assert::throws(function () use ($data): void {
		PaymentFactory::create($data);
	}, ValidationException::class, '%a% (200) %a% (180) %a%');
});

// Validate EET sum and order sum
test(function (): void {
	$data = [
		'amount' => Money::CZK(100),
		'order_number' => '3',
		'order_description' => '4',
		'items' => [
			['name' => 'Item 01', 'amount' => Money::CZK(50), 'count' => 2],
		],
		'callback' => [
			'return_url' => '6',
			'notify_url' => '7',
		],
		'eet' => [
			'celk_trzba' => Money::CZK(110),
			'zakl_dan1' => Money::CZK(80),
			'dan1' => Money::CZK(30),
		],
	];

	Assert::throws(function () use ($data): void {
		PaymentFactory::create($data);
	}, ValidationException::class, '%a% (110) %a% (100) %a%');
});

// Validate EET sum and order sum (double/float)
test(function (): void {
	$data = [
		'amount' => Money::CZK(17400),
		'order_number' => '3',
		'order_description' => '4',
		'items' => [
			['name' => 'x', 'amount' => Money::CZK(17400)],
		],
		'callback' => [
			'return_url' => '6',
			'notify_url' => '7',
		],
		'eet' => [
			'celk_trzba' => Money::CZK(17400),
			'zakl_dan1' => Money::CZK(14380),
			'dan1' => Money::CZK(3020),
		],
	];

	try {
		PaymentFactory::create($data);
	} catch (ValidationException $e) {
		Assert::fail('EET sum and EET tax should be equal');
	}
});

// Validate EET sum and order sum (item without VAT)
test(function (): void {
	$data = [
		'amount' => Money::CZK(27400),
		'order_number' => '3',
		'order_description' => '4',
		'items' => [
			['name' => 'x', 'amount' => Money::CZK(27400)],
		],
		'callback' => [
			'return_url' => '6',
			'notify_url' => '7',
		],
		'eet' => [
			'celk_trzba' => Money::CZK(27400),
			'zakl_nepodl_dph' => Money::CZK(10000),
			'zakl_dan1' => Money::CZK(14380),
			'dan1' => Money::CZK(3020),
		],
	];
	try {
		PaymentFactory::create($data);
	} catch (ValidationException $e) {
		Assert::fail('EET sum and EET tax should be equal with item with no VAT');
	}
});
