<?php

/**
 * Test: Api\Entity\Payment
 */

use Markette\GopayInline\Api\Entity\Payment;
use Markette\GopayInline\Api\Objects\Item;
use Markette\GopayInline\Api\Objects\Parameter;
use Markette\GopayInline\Api\Objects\Payer;
use Markette\GopayInline\Api\Objects\Target;
use Markette\GopayInline\Api\Objects\Contact;
use Tester\Assert;

require __DIR__ . '/../../../../bootstrap.php';

// Simple
test(function () {
    $payment = new Payment();
    $payment->setTarget($target = new Target());
    $payment->setPayer($payer = new Payer());
    $payment->setLang('CZ');

    $array = $payment->toArray();
    Assert::equal($target->toArray(), $array['target']);
    Assert::equal($payer->toArray(), $array['payer']);
    Assert::equal('CZ', $array['lang']);
});

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
    $payment->addItem($i = new Item());
    $i->amount = 200;

    $array = $payment->toArray();
    Assert::count(2, $array['items']);
    Assert::equal(floatval(10000), $array['items'][0]['amount']);
    Assert::equal(floatval(20000), $array['items'][1]['amount']);

    $payment->setItems([]);
    Assert::count(0, $payment->getItems());
});

// Parameters
test(function () {
    $payment = new Payment();
    $payment->setTarget(new Target());
    $payment->addParameter($p = new Parameter());
    $p->name = 'foo';
    $p->value = 'bar';

    $array = $payment->toArray();
    Assert::count(1, $array['additional_params']);
    Assert::equal('foo', $array['additional_params'][0]['name']);
    Assert::equal('bar', $array['additional_params'][0]['value']);

    $payment->setParameters([]);
    Assert::count(0, $payment->getParameters());
});
