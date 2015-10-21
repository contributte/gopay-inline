<?php

use Markette\GopayInline\Client;
use Markette\GopayInline\Config;

// Load composer
require_once __DIR__ . '/../vendor/autoload.php';

// PRODUCTION ========================================================
$goId = '***FILL***';
$clientId = '***FILL***';
$clientSecret = '***FILL***';
$mode = Config::PROD;

// Create client
$client = new Client(new Config($goId, $clientId, $clientSecret, $mode));

// DEVELOPMENT / TESTING =============================================
$goId = '***FILL***';
$clientId = '***FILL***';
$clientSecret = '***FILL***';
$mode = Config::TEST;

// Create client
$client = new Client(new Config($goId, $clientId, $clientSecret)); // see default value
$client = new Client(new Config($goId, $clientId, $clientSecret, $mode));
