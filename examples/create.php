<?php

use Markette\GopayInline\Api\Entity\PaymentFactory;
use Markette\GopayInline\Client;
use Markette\GopayInline\Config;

// Load composer
require_once __DIR__ . '/../vendor/autoload.php';

$goId = '***FILL***';
$clientId = '***FILL***';
$clientSecret = '***FILL***';

// Create client
$client = new Client(new Config($goId, $clientId, $clientSecret));

// Payment data
$paymentData = [
    'payer' => [
        'default_payment_instrument' => 'BANK_ACCOUNT',
        'allowed_payment_instruments' => ['BANK_ACCOUNT'],
        'default_swift' => 'FIOBCZPP',
        'allowed_swifts' => ['FIOBCZPP', 'BREXCZPP'],
        'contact' => [
            'first_name' => 'Zbynek',
            'last_name' => 'Zak',
            'email' => 'zbynek.zak@gopay.cz',
            'phone_number' => '+420777456123',
            'city' => 'C.Budejovice',
            'street' => 'Plana 67',
            'postal_code' => '373 01',
            'country_code' => 'CZE',
        ],
    ],
    'amount' => 150,
    'currency' => 'CZK',
    'order_number' => '001',
    'order_description' => 'pojisteni01',
    'items' => [
        ['name' => 'item01', 'amount' => 50],
        ['name' => 'item02', 'amount' => 100],
    ],
    'additional_params' => [
        array('name' => 'invoicenumber', 'value' => '2015001003')
    ],
    'return_url' => 'http://www.eshop.cz/return',
    'notify_url' => 'http://www.eshop.cz/notify',
    'lang' => 'cs',
];

// Create payment request
$response = $client->payments->createPayment(PaymentFactory::create($paymentData));

var_dump($response);
