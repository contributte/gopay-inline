<?php

use Contributte\GopayInline\Api\Lists\Scope;
use Contributte\GopayInline\Client;
use Contributte\GopayInline\Config;

// Load composer
require_once __DIR__ . '/../vendor/autoload.php';

$goId = '***FILL***';
$clientId = '***FILL***';
$clientSecret = '***FILL***';

// Create client
$client = new Client(new Config($goId, $clientId, $clientSecret));

// Authenticate
// Scopes:
// - payment-create
// - payment-all
$token = $client->authenticate(['scope' => Scope::PAYMENT_CREATE]);

var_dump($token);
