<?php

/**
 * Test: Api\Entity\Payment
 */

use Markette\GopayInline\Api\Entity\Payment;
use Tester\Assert;
use Markette\GopayInline\Api\Objects\Item;
use Markette\GopayInline\Api\Objects\Target;

require __DIR__ . '/../../../../bootstrap.php';

// Amount
test(function () {
    $payment = new Payment();

    $payment->setAmount(100);
    Assert::equal(100, $payment->getAmount());
    Assert::equal(floatval(10000), $payment->getAmountInCents());

    $payment->setAmount(100.5);
    Assert::equal(100.5, $payment->getAmount());
    Assert::equal(floatval(10050), $payment->getAmountInCents());

    $payment->setAmount(100.555);
    Assert::equal(100.555, $payment->getAmount());
    Assert::equal(floatval(10056), $payment->getAmountInCents());

    $payment->setAmount(100.54);
    Assert::equal(100.54, $payment->getAmount());
    Assert::equal(floatval(10054), $payment->getAmountInCents());
});

// Items
test(function () {
    $payment = new Payment();
    $payment->setTarget(new Target());
    $payment->addItem($i = new Item());
    $i->amount = 100;

    $array = $payment->toArray();
    Assert::equal(floatval(10000), $array['items'][0]['amount']);
});
