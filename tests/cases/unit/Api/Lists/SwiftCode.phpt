<?php

/**
 * Test: Api\Lists\SwiftCode
 */

use Contributte\GopayInline\Api\Lists\SwiftCode;
use Tester\Assert;

require __DIR__ . '/../../../../bootstrap.php';

// All
test(function () {
	Assert::count(34, SwiftCode::all());
});

// CZ
test(function () {
	Assert::count(16, SwiftCode::cz());
});

// SK
test(function () {
	Assert::count(18, SwiftCode::sk());
});
