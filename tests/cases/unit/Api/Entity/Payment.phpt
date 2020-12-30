<?php declare(strict_types = 1);

/**
 * Test: Api\Entity\Payment
 */

use Contributte\GopayInline\Api\Entity\Payment;
use Contributte\GopayInline\Api\Objects\Item;
use Contributte\GopayInline\Api\Objects\Parameter;
use Contributte\GopayInline\Api\Objects\Payer;
use Contributte\GopayInline\Api\Objects\Target;
use Money\Money;
use Tester\Assert;

require __DIR__ . '/../../../../bootstrap.php';

// Simple
test(function (): void {
	$payment = new Payment();
	$payment->setAmount(Money::CZK(0));
	$payment->setTarget($target = new Target());
	$payment->setPayer($payer = new Payer());
	$payment->setLang('CZ');

	$array = $payment->toArray();
	Assert::equal($target->toArray(), $array['target']);
	Assert::equal($payer->toArray(), $array['payer']);
	Assert::equal('CZ', $array['lang']);
});

// Amount
test(function (): void {
	$payment = new Payment();

	$payment->setAmount(Money::CZK(10000));
	Assert::equal('10000', $payment->getAmountInCents());

	$payment->setAmount(Money::CZK(10050));
	Assert::equal('10050', $payment->getAmountInCents());

	$payment->setAmount(Money::CZK(100555));
	Assert::equal('100555', $payment->getAmountInCents());
});

// Items
test(function (): void {
	$payment = new Payment();
	$payment->setTarget(new Target());
	$payment->setAmount(Money::CZK(30000));
	$payment->addItem($i = new Item());
	$i->amount = Money::CZK(10000);
	$payment->addItem($i = new Item());
	$i->amount = Money::CZK(20000);

	$array = $payment->toArray();
	Assert::count(2, $array['items']);
	Assert::equal('10000', $array['items'][0]['amount']);
	Assert::equal('20000', $array['items'][1]['amount']);

	$payment->setItems([]);
	Assert::count(0, $payment->getItems());
});

// Parameters
test(function (): void {
	$payment = new Payment();
	$payment->setTarget(new Target());
	$payment->setAmount(Money::CZK(0));
	$payment->addParameter($p = new Parameter());
	$p->name = 'foo';
	$p->value = 'bar';

	$array = $payment->toArray();
	Assert::count(1, $array['additional_params']);
	Assert::equal('foo', $array['additional_params'][0]['name']);
	Assert::equal('bar', $array['additional_params'][0]['value']);

	$payment->setParameters([]);
	Assert::count(0, $payment->getParameters());
});

// Preauthorized
test(function (): void {
	$payment = new Payment();
	$payment->setTarget($target = new Target());
	$payment->setAmount(Money::CZK(0));
	$payment->setPreauthorization(true);

	$array = $payment->toArray();
	Assert::true($array['preauthorization']);
});
