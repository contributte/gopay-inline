<?php declare(strict_types = 1);

/**
 * Test: Service\PaymentService
 */

use Contributte\GopayInline\Api\Lists\Currency;
use Contributte\GopayInline\Api\Lists\Format;
use Contributte\GopayInline\Client;
use Contributte\GopayInline\Config;
use Contributte\GopayInline\Http\Response;
use Contributte\GopayInline\Service\AccountsService;
use Tester\Assert;

require __DIR__ . '/../../../bootstrap.php';

// Account statement works
test(function (): void {
	$client = new Client(new Config('1', '2', '3'));

	$service = Mockery::mock(AccountsService::class, [$client])
        ->makePartial()
        ->shouldAllowMockingProtectedMethods();
	$service->shouldReceive('makeRequest')->andReturn(new Response());

	Assert::type(Response::class, $service->getAccountStatement('2017-01-01', '2017-01-31', Currency::CZK, Format::ABO_A));
});
