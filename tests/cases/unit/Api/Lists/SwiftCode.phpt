<?php

/**
 * Test: Api\Lists\SwiftCode
 */

use Markette\GopayInline\Api\Lists\SwiftCode;
use Tester\Assert;

require __DIR__ . '/../../../../bootstrap.php';

// All
test(function () {
	Assert::count(15, SwiftCode::all());
});

// CZ
test(function () {
	Assert::count(7, SwiftCode::cz());
});

// SK
test(function () {
	Assert::count(8, SwiftCode::sk());
});
