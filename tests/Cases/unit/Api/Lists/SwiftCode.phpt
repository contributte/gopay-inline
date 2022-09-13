<?php declare(strict_types = 1);

/**
 * Test: Api\Lists\SwiftCode
 */

use Contributte\GopayInline\Api\Lists\SwiftCode;
use Tester\Assert;

require __DIR__ . '/../../../../bootstrap.php';

// All
test(function (): void {
	Assert::count(14, SwiftCode::all());
});

// CZ
test(function (): void {
	Assert::count(8, SwiftCode::cz());
});

// SK
test(function (): void {
	Assert::count(5, SwiftCode::sk());
});
