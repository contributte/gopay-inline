<?php

/**
 * Test: Api\Lists\PaymentInstrument
 */

use Contributte\GopayInline\Api\Lists\PaymentInstrument;
use Tester\Assert;

require __DIR__ . '/../../../../bootstrap.php';

// All
test(function () {
	Assert::count(10, PaymentInstrument::all());
});
