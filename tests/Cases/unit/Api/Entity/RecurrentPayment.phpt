<?php declare(strict_types = 1);

/**
 * Test: Api\Entity\RecurrentPayment
 */

use Contributte\GopayInline\Api\Entity\RecurrentPayment;
use Contributte\GopayInline\Api\Lists\RecurrenceCycle;
use Contributte\GopayInline\Api\Objects\Recurrence;
use Contributte\GopayInline\Api\Objects\Target;
use Money\Money;
use Tester\Assert;

require __DIR__ . '/../../../../bootstrap.php';

// Simple
test(function (): void {
	$payment = new RecurrentPayment();
	$payment->setAmount(Money::CZK(0));
	$payment->setTarget($target = new Target());
	$payment->setRecurrence($recurrence = new Recurrence());

	$recurrence->cycle = RecurrenceCycle::DAY;
	$recurrence->period = 7;
	$recurrence->dateTo = date('Y-m-d');

	$array = $payment->toArray();
	Assert::equal($recurrence->toArray(), $array['recurrence']);
});
