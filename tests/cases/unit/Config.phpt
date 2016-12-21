<?php

/**
 * Test: Config
 */

use Markette\GopayInline\Api\Gateway;
use Markette\GopayInline\Config;
use Tester\Assert;

require __DIR__ . '/../../bootstrap.php';

// Modes
test(function () {
	$config = new Config(1, 2, 3);
	Assert::equal(Config::TEST, $config->getMode());

	$config->setMode(Config::TEST);
	Assert::equal(Config::TEST, $config->getMode());

	$config->setMode(Config::PROD);
	Assert::equal(Config::PROD, $config->getMode());
});

// Gateway
test(function () {
	$config = new Config(1, 2, 3);
	Assert::equal(Config::TEST, $config->getMode());
	Assert::match('%a%sandbox%a%', Gateway::getOauth2TokenUrl());
	Assert::match('%a%sandbox%a%', Gateway::getBaseApiUrl());
	Assert::match('%a%sandbox%a%foobar', Gateway::getFullApiUrl('foobar'));

	$config->setMode(Config::PROD);
	Assert::equal(Config::PROD, $config->getMode());
	Assert::false(Assert::isMatching('%a%sandbox%a%', Gateway::getOauth2TokenUrl()));
	Assert::false(Assert::isMatching('%a%sandbox%a%', Gateway::getBaseApiUrl()));
	Assert::match('%a%foobar', Gateway::getFullApiUrl('foobar'));
});
