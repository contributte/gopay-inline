<?php

/**
 * Test: Api\Entity\RecurrentPayment
 */

use Contributte\GopayInline\Api\Entity\RecurrentPayment;
use Contributte\GopayInline\Api\Lists\RecurrenceCycle;
use Contributte\GopayInline\Api\Objects\Recurrence;
use Contributte\GopayInline\Api\Objects\Target;
use Tester\Assert;

require __DIR__ . '/../../../../bootstrap.php';

// Simple
test(function () {
	$payment = new RecurrentPayment();
	$payment->setTarget($target = new Target());
	$payment->setRecurrence($recurrence = new Recurrence());

	$recurrence->cycle = RecurrenceCycle::DAY;
	$recurrence->period = 7;
	$recurrence->dateTo = date('Y-m-d');

	$array = $payment->toArray();
	Assert::equal($recurrence->toArray(), $array['recurrence']);
});
