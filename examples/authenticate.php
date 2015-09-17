<?php

use Markette\GopayInline\Api\Lists\Scope;
use Markette\GopayInline\Client;
use Markette\GopayInline\Config;

// Load composer
require_once __DIR__ . '/../vendor/autoload.php';

$goId = '***FILL***';
$goClient = '***FILL***';
$goSecret = '***FILL***';

// Create client
$client = new Client(new Config($goId, $goClient, $goSecret));

// Authenticate
// Scopes:
// - payment-create
// - payment-all
$token = $client->authenticate(Scope::PAYMENT_CREATE);

var_dump($response);
