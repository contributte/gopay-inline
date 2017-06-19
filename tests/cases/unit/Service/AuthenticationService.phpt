<?php

/**
 * Test: Service\AuthenticationService
 */

use Markette\GopayInline\Client;
use Markette\GopayInline\Config;
use Markette\GopayInline\Exception\HttpException;
use Markette\GopayInline\Service\AuthenticationService;
use Tester\Assert;

require __DIR__ . '/../../../bootstrap.php';

// Valid crendetials
test(function () {
	$client = new Client(new Config(1, 2, 3));

	$service = Mockery::mock(AuthenticationService::class, [$client])
			->makePartial()
			->shouldAllowMockingProtectedMethods();
	$service->shouldReceive('doAuthorization');

	Assert::true($service->verifyCredentials());
});

// Invalid credentials
test(function () {
	$client = new Client(new Config(1, 2, 3));

	$service = Mockery::mock(AuthenticationService::class, [$client])
			->makePartial()
			->shouldAllowMockingProtectedMethods();
	$service->shouldReceive('doAuthorization')->andThrow(HttpException::class);;

	Assert::false($service->verifyCredentials());
});
