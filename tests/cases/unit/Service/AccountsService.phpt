<?php

/**
 * Test: Service\PaymentService
 */

use Markette\GopayInline\Api\Lists\Currency;
use Markette\GopayInline\Api\Lists\Format;
use Markette\GopayInline\Client;
use Markette\GopayInline\Config;
use Markette\GopayInline\Service\AccountsService;
use Tester\Assert;

require __DIR__ . '/../../../bootstrap.php';

// Account statement works
test(function () {
	$client = new Client(new Config(1, 2, 3));

	$service = Mockery::mock(AccountsService::class, [$client])
			->makePartial()
			->shouldAllowMockingProtectedMethods();
	$service->shouldReceive('makeRequest')->andReturn(TRUE);

	Assert::true($service->getAccountStatement('2017-01-01', '2017-01-31', Currency::CZK, Format::ABO_A));
});
