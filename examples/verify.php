<?php

use Markette\GopayInline\Client;
use Markette\GopayInline\Config;

// Load composer
require_once __DIR__ . '/../vendor/autoload.php';

$goId = '***FILL***';
$goClient = '***FILL***';
$goSecret = '***FILL***';
$paymentId = '***FILL***';

// Create client
$client = new Client(new Config($goId, $goClient, $goSecret));

// Create payment request
$response = $client->payments->verify($paymentId);

var_dump($response);
