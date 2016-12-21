<?php

/**
 * Test: Exception/HttpException
 */

use Markette\GopayInline\Exception\HttpException;
use Tester\Assert;

require __DIR__ . '/../../../bootstrap.php';

// All data
test(function () {
	$error = (object) [
		'field' => 'foobar',
		'error_code' => 400,
		'scope' => 'validation',
		'message' => 'Invalid field',
	];

	Assert::equal('#400 (validation) [foobar] Invalid field', HttpException::format($error));
});

// Without field
test(function () {
	$error = (object) [
		'error_code' => 400,
		'scope' => 'validation',
		'message' => 'Invalid field',
	];

	Assert::equal('#400 (validation) Invalid field', HttpException::format($error));
});

// Without field
test(function () {
	$error = (object) [
		'error_code' => 400,
		'message' => 'Invalid field',
	];

	Assert::equal('#400 Invalid field', HttpException::format($error));
});

// Without message, with description
test(function () {
	$error = (object) [
		'error_code' => 400,
		'scope' => 'validation',
		'description' => 'Invalid field long',
	];

	Assert::equal('#400 (validation) Invalid field long', HttpException::format($error));
});
