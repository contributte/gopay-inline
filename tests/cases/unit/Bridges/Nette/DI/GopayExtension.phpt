<?php

/**
 * Test: Bridges/Nette/DI/GopayExtension
 */

use Markette\GopayInline\Bridges\Nette\DI\GopayExtension;
use Markette\GopayInline\Client;
use Nette\DI\Compiler;
use Nette\DI\Container;
use Nette\DI\ContainerLoader;
use Nette\Utils\AssertionException;
use Tester\Assert;

require __DIR__ . '/../../../../../bootstrap.php';

// Create container
test(function () {
    Assert::throws(function () {
        $loader = new ContainerLoader(TEMP_DIR);
        $class = $loader->load('c1', function (Compiler $compiler) {
            $compiler->addExtension('gopay', new GopayExtension());
        });
    }, AssertionException::class);
});

// Configuration (pass parameters DEVELOPMENT)
test(function () {
    $loader = new ContainerLoader(TEMP_DIR);
    $class = $loader->load('c2', function (Compiler $compiler) {
        $compiler->addConfig(['gopay' => ['goId' => 1, 'clientId' => 2, 'clientSecret' => '3']]);
        $compiler->addExtension('gopay', new GopayExtension());
    });
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
    $class = $loader->load('c3', function (Compiler $compiler) {
        $compiler->addConfig(['gopay' => ['goId' => 11, 'clientId' => 22, 'clientSecret' => '33', 'test' => FALSE]]);
        $compiler->addExtension('gopay', new GopayExtension());
    });
    /** @var Container $container */
    $container = new $class;

    /** @var Client $client */
    $client = $container->getByType(Client::class);

    Assert::equal(11, $client->getGoId());
    Assert::equal(22, $client->getClientId());
    Assert::equal('33', $client->getClientSecret());
});
