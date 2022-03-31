<?php declare(strict_types = 1);

/**
 * Test: Service\AuthenticationService
 */

use Contributte\GopayInline\Client;
use Contributte\GopayInline\Config;
use Contributte\GopayInline\Exception\HttpException;
use Contributte\GopayInline\Service\AuthenticationService;
use Tester\Assert;

require __DIR__ . '/../../../bootstrap.php';

// Valid crendetials
test(function (): void {
	$client = new Client(new Config('1', '2', '3'));

	$service = Mockery::mock(AuthenticationService::class, [$client])
		->makePartial()
		->shouldAllowMockingProtectedMethods();
	$service->shouldReceive('doAuthorization');

	Assert::true($service->verify());
});

// Invalid credentials
test(function (): void {
	$client = new Client(new Config('1', '2', '3'));

	$service = Mockery::mock(AuthenticationService::class, [$client])
		->makePartial()
		->shouldAllowMockingProtectedMethods();
	$service->shouldReceive('doAuthorization')->andThrow(HttpException::class);

	Assert::false($service->verify());
});
