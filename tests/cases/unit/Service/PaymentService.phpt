<?php declare(strict_types = 1);

/**
 * Test: Service\PaymentService
 */

use Contributte\GopayInline\Api\Entity\Payment;
use Contributte\GopayInline\Api\Entity\RecurrentPayment;
use Contributte\GopayInline\Api\Entity\RecurringPayment;
use Contributte\GopayInline\Api\Lists\Currency;
use Contributte\GopayInline\Api\Lists\PaymentType;
use Contributte\GopayInline\Api\Lists\TargetType;
use Contributte\GopayInline\Api\Objects\Eet;
use Contributte\GopayInline\Api\Objects\Item;
use Contributte\GopayInline\Api\Objects\Target;
use Contributte\GopayInline\Client;
use Contributte\GopayInline\Config;
use Contributte\GopayInline\Http\Http;
use Contributte\GopayInline\Http\Response;
use Contributte\GopayInline\Service\PaymentsService;
use Money\Money;
use Tester\Assert;

require __DIR__ . '/../../../bootstrap.php';

// Fill payment target
test(function (): void {
	$client = new Client(new Config('1', '2', '3'));

	$paymentTypes = [
		'createPayment' => new Payment(),
		'createRecurrentPayment' => new RecurrentPayment(),
	];

	foreach ($paymentTypes as $paymentType => $payment) {
		/** @var Payment|RecurrentPayment $payment */
		$payment->setAmount(Money::CZK(100));
		$service = Mockery::mock(PaymentsService::class, [$client])
			->makePartial()
			->shouldAllowMockingProtectedMethods();
		$service->shouldReceive('makeRequest')->andReturn(new Response());

		Assert::type(Response::class, $service->{$paymentType}($payment));
		Assert::type(Money::class, $payment->getAmount());
		Assert::equal('100', $payment->getAmount()->getAmount());
		Assert::equal('1', $payment->getTarget()->goid);
		Assert::equal(TargetType::ACCOUNT, $payment->getTarget()->type);
	}
});

// Fill recurring payment target
test(function (): void {
	$client = new Client(new Config('1', '2', '3'));
	$recurrencePaymentId = 'abcdefghijklmno';

	$payment = new RecurringPayment();
	$payment->setAmount(Money::CZK(100));
	$service = Mockery::mock(PaymentsService::class, [$client])
		->makePartial()
		->shouldAllowMockingProtectedMethods();
	$service->shouldReceive('makeRequest')->andReturn(new Response());

	Assert::type(Response::class, $service->createRecurringPayment($recurrencePaymentId, $payment));
	Assert::type(Money::class, $payment->getAmount());
	Assert::equal('100', $payment->getAmount()->getAmount());
});

// Verify payment
test(function (): void {
	$urlRef = null;
	$service = Mockery::mock(PaymentsService::class)
		->makePartial()
		->shouldAllowMockingProtectedMethods();

	$service->shouldReceive('makeRequest')
		->once()
		->with(
			Mockery::any(),
			Mockery::on(function ($uri) use (&$urlRef) {
				$urlRef = $uri;

				return true;
			}),
			Mockery::any(),
			Mockery::any()
		)
		->andReturn(new Response());

	Assert::type(Response::class, $service->verify('150'));
	Assert::match('%a%150', $urlRef);
});

// Payment instruments
test(function (): void {
	$client = new Client(new Config('1', '2', '3'));
	$client->setToken('12345');

	$urlRef = null;

	$service = Mockery::mock(PaymentsService::class, [$client])
		->makePartial()
		->shouldAllowMockingProtectedMethods();

	$service->shouldReceive('makeRequest')
		->once()
		->with(
			Mockery::any(),
			Mockery::on(function ($uri) use (&$urlRef) {
				$urlRef = $uri;

				return true;
			}),
			Mockery::any(),
			Mockery::any()
		)
		->andReturn(new Response());

	Assert::type(Response::class, $service->getPaymentInstruments(Currency::CZK));
	Assert::match('eshops/eshop/1/payment-instruments/CZK', $urlRef);
});

// No-fill payment target
test(function (): void {
	$client = new Client(new Config('1', '2', '3'));
	$payment = new Payment();
	$payment->setAmount(Money::CZK(100));
	$payment->setTarget($target = new Target());
	$target->goid = '99';

	$service = Mockery::mock(PaymentsService::class, [$client])
		->makePartial()
		->shouldAllowMockingProtectedMethods();
	$service->shouldReceive('makeRequest')->andReturn(new Response());

	Assert::type(Response::class, $service->createPayment($payment));
	Assert::type(Money::class, $payment->getAmount());
	Assert::equal('100', $payment->getAmount()->getAmount());
	Assert::equal('99', $payment->getTarget()->goid);
});

// Refund payment
test(function (): void {
	$client = new Client(new Config('1', '2', '3'));
	$service = Mockery::mock(PaymentsService::class, [$client])
		->makePartial()
		->shouldAllowMockingProtectedMethods();
	$service->shouldReceive('makeRequest')
		->with('POST', 'payments/payment/99/refund', ['amount' => (float) 12345], Http::CONTENT_FORM)
		->andReturn(new Response());

	Assert::type(Response::class, $service->refundPayment(99, 123.45));
});

// Refund payment when EET is defined, see https://doc.gopay.com/cs/#refundace-platby-(storno)24
test(function (): void {
	$item = new Item();
	$item->setType(PaymentType::ITEM);
	$item->setName('lodicky');
	// $item->setProductUrl not implemented yet
	// $item->setEan not implemented yet
	$item->setAmount(Money::CZK(-119990));
	$item->setCount(1);
	$item->setVatRate(21);
	$items = [$item->toArray()];

	$eet = new Eet();
	$eet->setSum(Money::CZK(-119990));
	$eet->setTaxBase(Money::CZK(-99165));
	$eet->setTax(Money::CZK(-20825));
	$eet = $eet->toArray();

	$client = new Client(new Config('1', '2', '3'));
	$service = Mockery::mock(PaymentsService::class, [$client])
		->makePartial()
		->shouldAllowMockingProtectedMethods();
	$service->shouldReceive('makeRequest')
		->with(
			'POST',
			'payments/payment/99/refund',
			[
				'amount' => 119990,
				'items' => [
					[
						'type' => 'ITEM',
						'name' => 'lodicky',
						//'product_url' => 'https://www.eshop.cz/boty/damske/lodicky-cervene',
						//'ean' => 1234567890123,
						'amount' => -119990,
						'count' => 1,
						'vat_rate' => 21,
					],
				],
				'eet' => [
					'celk_trzba' => -119990,
					'zakl_dan1' => -99165,
					'dan1' => -20825,
					'mena' => Currency::CZK,
				],
			],
			Http::CONTENT_JSON
		)
		->andReturn(new Response());

	Assert::type(Response::class, $service->refundPayment(99, 1199.90, $items, $eet));
});

// Get EET receipts
test(function (): void {
	$client = new Client(new Config('1', '2', '3'));
	$service = Mockery::mock(PaymentsService::class, [$client])
		->makePartial()
		->shouldAllowMockingProtectedMethods();
	$service->shouldReceive('makeRequest')
		->with('GET', 'payments/payment/99/eet-receipts')
		->andReturn(new Response());

	Assert::type(Response::class, $service->getEetReceipts(99));
});

// Capture payment
test(function (): void {
	$client = new Client(new Config('1', '2', '3'));
	$response = new Response();
	$response->setData([
		'id' => 10001,
		'status' => 'FINISHED',
	]);
	$service = Mockery::mock(PaymentsService::class, [$client])
		->makePartial()
		->shouldAllowMockingProtectedMethods();
	$service->shouldReceive('makeRequest')
		->with('POST', 'payments/payment/10001/capture', ['amount' => 3150.])
		->andReturn($response);

	Assert::type(Response::class, $service->capturePayment(10001, 31.5));
});
