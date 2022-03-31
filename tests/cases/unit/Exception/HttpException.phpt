<?php declare(strict_types = 1);

/**
 * Test: Exception/HttpException
 */

use Contributte\GopayInline\Exception\HttpException;
use Tester\Assert;

require __DIR__ . '/../../../bootstrap.php';

// All data
test(function (): void {
	$error = (object) [
		'field' => 'foobar',
		'error_code' => 400,
		'scope' => 'validation',
		'message' => 'Invalid field',
	];

	Assert::equal('#400 (validation) [foobar] Invalid field', HttpException::format($error));
});

// Without field
test(function (): void {
	$error = (object) [
		'error_code' => 400,
		'scope' => 'validation',
		'message' => 'Invalid field',
	];

	Assert::equal('#400 (validation) Invalid field', HttpException::format($error));
});

// Without field
test(function (): void {
	$error = (object) [
		'error_code' => 400,
		'message' => 'Invalid field',
	];

	Assert::equal('#400 Invalid field', HttpException::format($error));
});

// Without message, with description
test(function (): void {
	$error = (object) [
		'error_code' => 400,
		'scope' => 'validation',
		'description' => 'Invalid field long',
	];

	Assert::equal('#400 (validation) Invalid field long', HttpException::format($error));
});

// With message and description
test(function (): void {
	$error = (object) [
		'scope' => 'G',
		'error_code' => 111,
		'error_name' => 'INVALID',
		'message' => 'Wrong value.',
		'description' => 'eshop with goId=1234567890 was not found',
	];

	Assert::equal('#111 (G) Wrong value: eshop with goId=1234567890 was not found', HttpException::format($error));
});
