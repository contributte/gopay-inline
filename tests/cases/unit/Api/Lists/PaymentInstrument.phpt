<?php

/**
 * Test: Api\Lists\PaymentInstrument
 */

use Markette\GopayInline\Api\Lists\PaymentInstrument;
use Tester\Assert;

require __DIR__ . '/../../../../bootstrap.php';

// All
test(function () {
	Assert::count(9, PaymentInstrument::all());
});
