<?php

/**
 * Test: Api\Entity\RecurrentPayment
 */

use Markette\GopayInline\Api\Entity\RecurrentPayment ;
use Markette\GopayInline\Api\Objects\Target;
use Markette\GopayInline\Api\Objects\Recurrence;
use Markette\GopayInline\Api\Lists\RecurrenceCycle;
use Tester\Assert;

require __DIR__ . '/../../../../bootstrap.php';

// Simple
test(function () {
    $payment = new RecurrentPayment ();
    $payment->setTarget($target = new Target());
    $payment->setRecurrence($recurrence = new Recurrence());

    $recurrence->cycle = RecurrenceCycle::DAY;
    $recurrence->period = 7;
    $recurrence->dateTo = date('Y-m-d');

    $array = $payment->toArray();
    Assert::equal($recurrence->toArray(), $array['recurrence']);
});

