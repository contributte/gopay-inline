<?php

/**
 * Test: Api\Entity\PreauthorizedPayment
 */

use Contributte\GopayInline\Api\Entity\PreauthorizedPayment;
use Contributte\GopayInline\Api\Objects\Target;
use Tester\Assert;

require __DIR__ . '/../../../../bootstrap.php';

// Simple
test(function () {
	$payment = new PreauthorizedPayment();
	$payment->setTarget($target = new Target());
	$payment->setPreauthorization(TRUE);

	$array = $payment->toArray();
	Assert::true($array['preauthorization']);
});
