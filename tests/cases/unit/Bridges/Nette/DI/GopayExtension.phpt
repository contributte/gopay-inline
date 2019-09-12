<?php

/**
 * Test: Bridges\Nette\DI\GopayExtension
 */

use Contributte\GopayInline\Bridges\Nette\DI\GopayExtension;
use Contributte\GopayInline\Client;
use Nette\DI\Compiler;
use Nette\DI\Container;
use Nette\DI\ContainerLoader;
use Nette\DI\InvalidConfigurationException;
use Tester\Assert;

require __DIR__ . '/../../../../../bootstrap.php';

// Create container
test(function () {
	Assert::throws(function () {
		$loader = new ContainerLoader(TEMP_DIR);
		$class = $loader->load(function (Compiler $compiler) {
			$compiler->addExtension('gopay', new GopayExtension());
		}, 'c1');
	}, InvalidConfigurationException::class);
});

// Configuration (pass parameters DEVELOPMENT)
test(function () {
	$loader = new ContainerLoader(TEMP_DIR);
	$class = $loader->load(function (Compiler $compiler) {
		$compiler->addConfig(['gopay' => ['goId' => 1, 'clientId' => 2, 'clientSecret' => '3']]);
		$compiler->addExtension('gopay', new GopayExtension());
	}, 'c2');
	/** @var Container $container */
	$container = new $class;

	/** @var Client $client */
	$client = $container->getByType(Client::class);

	Assert::equal(1, $client->getGoId());
	Assert::equal(2, $client->getClientId());
	Assert::equal('3', $client->getClientSecret());
});

// Configuration (pass parameters PRODUCTION)
test(function () {
	$loader = new ContainerLoader(TEMP_DIR);
	$class = $loader->load(function (Compiler $compiler) {
		$compiler->addConfig(['gopay' => ['goId' => 11, 'clientId' => 22, 'clientSecret' => '33', 'test' => FALSE]]);
		$compiler->addExtension('gopay', new GopayExtension());
	}, 'c3');
	/** @var Container $container */
	$container = new $class;

	/** @var Client $client */
	$client = $container->getByType(Client::class);

	Assert::equal(11, $client->getGoId());
	Assert::equal(22, $client->getClientId());
	Assert::equal('33', $client->getClientSecret());
});
