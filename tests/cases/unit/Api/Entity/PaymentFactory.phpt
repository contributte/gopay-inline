<?php

/**
 * Test: Api\Entity\PaymentFactory
 */

use Markette\GopayInline\Api\Entity\Payment;
use Markette\GopayInline\Api\Entity\PaymentFactory;
use Markette\GopayInline\Api\Lists\TargetType;
use Markette\GopayInline\Exception\ValidationException;
use Tester\Assert;

require __DIR__ . '/../../../../bootstrap.php';

// Required fields
test(function () {
    Assert::throws(function () {
        PaymentFactory::create([]);
    }, ValidationException::class, '%a%' . implode(', ', PaymentFactory::$required) . '%a%');
});

// Not allowed field
test(function () {
    $required = [
        'amount' => 1,
        'currency' => 2,
        'order_number' => 3,
        'order_description' => 4,
        'items' => 5,
        'return_url' => 6,
        'notify_url' => 7,
    ];
    $fields = [
        'foo' => 8,
        'bar' => 9,
    ];
    Assert::throws(function () use ($required, $fields) {
        PaymentFactory::create(array_merge($required, $fields));
    }, ValidationException::class, '%a%' . implode(', ', array_keys($fields)) . '%a%');
});

// Simple payment
test(function () {
    $data = [
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
        'target' => [
            'goid' => 123456,
            'type' => TargetType::ACCOUNT,
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

    $payment = PaymentFactory::create($data);
    Assert::type(Payment::class, $payment);
});

// Validate order price and items price
test(function () {
    $data = [
        'amount' => 200,
        'currency' => 2,
        'order_number' => 3,
        'order_description' => 4,
        'items' => [
            ['amount' => 50],
            ['amount' => 50]
        ],
        'return_url' => 6,
        'notify_url' => 7,
    ];

    Assert::throws(function () use ($data) {
        PaymentFactory::create($data);
    }, ValidationException::class, '%a% (200) %a% (100) %a%');
});

