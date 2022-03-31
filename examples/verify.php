<?php

use Contributte\GopayInline\Client;
use Contributte\GopayInline\Config;

// Load composer
require_once __DIR__ . '/../vendor/autoload.php';

$goId = '***FILL***';
$clientId = '***FILL***';
$clientSecret = '***FILL***';
$paymentId = '***FILL***';

// Create client
$client = new Client(new Config($goId, $clientId, $clientSecret));

// Create payment request
$response = $client->payments->verify($paymentId);

var_dump($response);
