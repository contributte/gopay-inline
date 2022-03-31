<?php declare(strict_types = 1);

/**
 * Test: Api\Entity\RecurringPayment
 */

use Contributte\GopayInline\Api\Entity\RecurringPayment;
use Contributte\GopayInline\Api\Objects\Parameter;
use Contributte\GopayInline\Api\Objects\Payer;
use Contributte\GopayInline\Api\Objects\Target;
use Money\Money;
use Tester\Assert;

require __DIR__ . '/../../../../bootstrap.php';

// Simple
test(function (): void {
	$payment = new RecurringPayment();
	$payment->setAmount(Money::CZK(0));
	$payment->setTarget($target = new Target());
	$payment->setPayer($payer = new Payer());
	$payment->setLang('CZ');

	$array = $payment->toArray();
	Assert::equal(false, isset($array['target']));
	Assert::equal(false, isset($array['payer']));
	Assert::equal(false, isset($array['lang']));
});

// Parameters
test(function (): void {
	$payment = new RecurringPayment();
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
