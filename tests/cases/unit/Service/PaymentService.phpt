<?php

/**
 * Test: Service\PaymentService
 */

use Markette\GopayInline\Api\Entity\Payment;
use Markette\GopayInline\Api\Entity\PreauthorizedPayment;
use Markette\GopayInline\Api\Entity\RecurrentPayment;
use Markette\GopayInline\Api\Lists\Currency;
use Markette\GopayInline\Api\Lists\PaymentType;
use Markette\GopayInline\Api\Lists\TargetType;
use Markette\GopayInline\Api\Objects\Eet;
use Markette\GopayInline\Api\Objects\Item;
use Markette\GopayInline\Api\Objects\Target;
use Markette\GopayInline\Client;
use Markette\GopayInline\Config;
use Markette\GopayInline\Http\Http;
use Markette\GopayInline\Service\PaymentsService;
use Tester\Assert;

require __DIR__ . '/../../../bootstrap.php';

// Fill payment target
test(function () {
	$client = new Client(new Config(1, 2, 3));

	$paymentTypes = [
		'createPayment' => new Payment(),
		'createRecurrentPayment' => new RecurrentPayment(),
		'createPreauthorizedPayment' => new PreauthorizedPayment(),
	];

	foreach ($paymentTypes as $paymentType => $payment) {
		$payment->setAmount(100);
		$service = Mockery::mock(PaymentsService::class, [$client])
			->makePartial()
			->shouldAllowMockingProtectedMethods();
		$service->shouldReceive('makeRequest')->andReturn(TRUE);

		Assert::true($service->{$paymentType}($payment));
		Assert::equal(100, $payment->getAmount());
		Assert::equal(1, $payment->getTarget()->goid);
		Assert::equal(TargetType::ACCOUNT, $payment->getTarget()->type);
	}
});

// Verify payment
test(function () {
	$urlRef = NULL;
	$service = Mockery::mock(PaymentsService::class)
		->makePartial()
		->shouldAllowMockingProtectedMethods();

	$service->shouldReceive('makeRequest')
		->once()
		->with(
			Mockery::any(),
			Mockery::on(function ($uri) use (&$urlRef) {
				$urlRef = $uri;

				return TRUE;
			}),
			Mockery::any(),
			Mockery::any()
		)
		->andReturn(TRUE);

	Assert::true($service->verify(150));
	Assert::match('%a%150', $urlRef);
});

// Payment instruments
test(function () {
	$client = new Client(new Config(1, 2, 3));
	$client->setToken('12345');

	$urlRef = NULL;

	$service = Mockery::mock(PaymentsService::class, [$client])
		->makePartial()
		->shouldAllowMockingProtectedMethods();

	$service->shouldReceive('makeRequest')
		->once()
		->with(
			Mockery::any(),
			Mockery::on(function ($uri) use (&$urlRef) {
				$urlRef = $uri;

				return TRUE;
			}),
			Mockery::any(),
			Mockery::any()
		)
		->andReturn(TRUE);

	Assert::true($service->getPaymentInstruments(Currency::CZK));
	Assert::match('eshops/eshop/1/payment-instruments/CZK', $urlRef);
});

// No-fill payment target
test(function () {
	$client = new Client(new Config(1, 2, 3));
	$payment = new Payment();
	$payment->setAmount(100);
	$payment->setTarget($target = new Target());
	$target->goid = 99;

	$service = Mockery::mock(PaymentsService::class, [$client])
		->makePartial()
		->shouldAllowMockingProtectedMethods();
	$service->shouldReceive('makeRequest')->andReturn(TRUE);

	Assert::true($service->createPayment($payment));
	Assert::equal(100, $payment->getAmount());
	Assert::equal(99, $payment->getTarget()->goid);
});

// Refund payment
test(function () {
	$client = new Client(new Config(1, 2, 3));
	$service = Mockery::mock(PaymentsService::class, [$client])
		->makePartial()
		->shouldAllowMockingProtectedMethods();
	$service->shouldReceive('makeRequest')
		->with('POST', 'payments/payment/99/refund', ['amount' => (float) 12345], Http::CONTENT_FORM)
		->andReturn(TRUE);

	Assert::true($service->refundPayment(99, 123.45));
});

// Refund payment when EET is defined, see https://doc.gopay.com/cs/#refundace-platby-(storno)24
test(function () {
	$item = new Item();
	$item->setType(PaymentType::ITEM);
	$item->setName('lodicky');
	// $item->setProductUrl not implemented yet
	// $item->setEan not implemented yet
	$item->setAmount(-1199.90);
	$item->setCount(1);
	$item->setVatRate(21);

	$eet = new Eet();
	$eet->setSum(-1199.90);
	$eet->setTaxBase(-991.65);
	$eet->setTax(-208.25);
	$eet->setCurrency(Currency::CZK);

	$payment = new Payment();
	$payment->addItem($item);
	$payment->setEet($eet);

	$client = new Client(new Config(1, 2, 3));
	$service = Mockery::mock(PaymentsService::class, [$client])
		->makePartial()
		->shouldAllowMockingProtectedMethods();
	$service->shouldReceive('makeRequest')
		->with(
			'POST',
			'payments/payment/99/refund',
			[
				'amount' => (float) 119990,
				'items' => [ 0 => [
					'name' => 'lodicky',
					//'product_url' => 'https://www.eshop.cz/boty/damske/lodicky-cervene',
					//'ean' => 1234567890123,
					'amount' => (float) - 119990,
					'count' => 1,
					'type' => 'ITEM',
					'vat_rate' => 21,
				]],
				'eet' => [
					'celk_trzba' => (float) - 119990,
					'mena' => Currency::CZK,
					'zakl_dan1' => (float) - 99165,
					'dan1' => (float) - 20825,
				],
			],
			Http::CONTENT_JSON
		)
		->andReturn(TRUE);

	Assert::true($service->refundPayment(99, 1199.90, $payment));
});
