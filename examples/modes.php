<?php

use Markette\GopayInline\Client;
use Markette\GopayInline\Config;

// Load composer
require_once __DIR__ . '/../vendor/autoload.php';

// PRODUCTION ========================================================
$goId = '***FILL***';
$goClient = '***FILL***';
$goSecret = '***FILL***';
$mode = Config::PROD;

// Create client
$client = new Client(new Config($goId, $goClient, $goSecret, $mode));

// DEVELOPMENT / TESTING =============================================
$goId = '***FILL***';
$goClient = '***FILL***';
$goSecret = '***FILL***';
$mode = Config::TEST;

// Create client
$client = new Client(new Config($goId, $goClient, $goSecret)); // see default value
$client = new Client(new Config($goId, $goClient, $goSecret, $mode));
