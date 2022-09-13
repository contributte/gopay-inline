<?php declare(strict_types = 1);

/**
 * Test: Api\Gateway
 */

use Contributte\GopayInline\Api\Gateway;
use Tester\Assert;

require __DIR__ . '/../../../bootstrap.php';

// Simple
test(function (): void {
	Gateway::init(Gateway::TEST);
	Assert::match('%a%api/oauth2/token', Gateway::getOauth2TokenUrl());
	Assert::match('%a%sandbox.gopay.com%a%', Gateway::getBaseApiUrl());
	Assert::match('%a%gw.sandbox.gopay%a%', Gateway::getFullApiUrl('foobar'));
	Assert::match('%a%gw.sandbox.gopay%a%foobar', Gateway::getFullApiUrl('foobar'));
	Assert::match('%a%gw.sandbox.gopay%a%foobar', Gateway::getFullApiUrl('foobar/'));
	Assert::match('%a%gw.sandbox.gopay.com%a%embed.js', Gateway::getInlineJsUrl());

	Gateway::init(Gateway::PROD);
	Assert::match('%a%api/oauth2/token', Gateway::getOauth2TokenUrl());
	Assert::match('%a%gate.gopay.cz%a%', Gateway::getBaseApiUrl());
	Assert::match('%a%gate.gopay%a%foobar', Gateway::getFullApiUrl('foobar'));
	Assert::match('%a%gate.gopay%a%foobar', Gateway::getFullApiUrl('foobar/'));
	Assert::match('%a%gate.gopay.cz%a%embed.js', Gateway::getInlineJsUrl());
});
