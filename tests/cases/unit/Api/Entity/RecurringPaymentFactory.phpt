<?php

/**
 * Test: Api\Entity\RecurringPaymentFactory
 */
use Contributte\GopayInline\Api\Entity\RecurringPayment;
use Contributte\GopayInline\Api\Entity\RecurringPaymentFactory;
use Contributte\GopayInline\Api\Lists\PaymentType;
use Contributte\GopayInline\Exception\ValidationException;
use Tester\Assert;

require __DIR__ . '/../../../../bootstrap.php';

// Required fields
test(function () {
	Assert::throws(function () {
		RecurringPaymentFactory::create([]);
	}, ValidationException::class, '%a%' . implode(', ', RecurringPaymentFactory::$required) . '%a%');
});

// Not allowed field
test(function () {
	$required = [
		'amount' => 1,
		'currency' => 2,
		'order_number' => 3,
		'order_description' => 4,
		'items' => 5,
	];
	$fields = [
		'foo' => 6,
		'bar' => 7,
	];
	Assert::throws(function () use ($required, $fields) {
		RecurringPaymentFactory::create(array_merge($required, $fields));
	}, ValidationException::class, '%a%' . implode(', ', array_keys($fields)) . '%a%');
});

// Simple payment
test(function () {
	$data = [
		'amount' => 550,
		'currency' => 'CZK',
		'order_number' => '001',
		'order_description' => 'pojisteni01',
		'items' => [
			['name' => 'item01', 'amount' => 50, 'count' => 2],
			['name' => 'item02', 'amount' => 100],
			['name' => 'item03', 'amount' => 150, 'vat_rate' => 21],
			['name' => 'item04', 'amount' => 200, 'type' => PaymentType::ITEM],
		],
		'additional_params' => [
			['name' => 'invoicenumber', 'value' => '2015001003'],
		],
	];

	$payment = RecurringPaymentFactory::create($data);
	Assert::type(RecurringPayment::class, $payment);
	Assert::equal(21, $payment->getItems()[2]->getVatRate());
	Assert::equal(PaymentType::ITEM, $payment->getItems()[3]->getType());
});

// Validate order price and items price
test(function () {
	$data = [
		'amount' => 200,
		'currency' => 2,
		'order_number' => 3,
		'order_description' => 4,
		'items' => [
			['name' => 'Item 01', 'amount' => 50, 'count' => 2],
			['name' => 'Item 01', 'amount' => 50],
		],
	];

	Assert::throws(function () use ($data) {
		RecurringPaymentFactory::create($data);
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
	];

	Assert::throws(function () use ($data) {
		RecurringPaymentFactory::create($data);
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
	];

	try {
		RecurringPaymentFactory::create($data, [RecurringPaymentFactory::V_PRICES => FALSE]);
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
		RecurringPaymentFactory::create($data, [RecurringPaymentFactory::V_SCHEME => FALSE]);
	} catch (Exception $e) {
		Assert::fail('Exception should not have been threw', $e, NULL);
	}
});
