<?php

/**
 * Test: Service/PaymentService
 */

use Markette\GopayInline\Api\Entity\Payment;
use Markette\GopayInline\Api\Entity\PreauthorizedPayment;
use Markette\GopayInline\Api\Entity\RecurrentPayment;
use Markette\GopayInline\Api\Lists\TargetType;
use Markette\GopayInline\Api\Objects\Target;
use Markette\GopayInline\Client;
use Markette\GopayInline\Config;
use Markette\GopayInline\Service\PaymentsService;
use Tester\Assert;

require __DIR__ . '/../../../bootstrap.php';

// Fill payment target
test(function () {
    $client = new Client(new Config(1, 2, 3));

    $paymentTypes = [
        'createPayment' => new Payment(),
        'createRecurrentPayment' => new RecurrentPayment(),
        'createPreauthorizedPayment' => new PreauthorizedPayment(),
    ];

    foreach ($paymentTypes as $paymentType => $payment) {
        $payment->setAmount(100);
        $service = Mockery::mock(PaymentsService::class, [$client])
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();
        $service->shouldReceive('makeRequest')->andReturn(TRUE);

        Assert::true($service->{$paymentType}($payment));
        Assert::equal(100, $payment->getAmount());
        Assert::equal(1, $payment->getTarget()->goid);
        Assert::equal(TargetType::ACCOUNT, $payment->getTarget()->type);
    }
});

// Verify payment
test(function () {
    $urlRef = NULL;
    $service = Mockery::mock(PaymentsService::class)
        ->makePartial()
        ->shouldAllowMockingProtectedMethods();

    $service->shouldReceive('makeRequest')
        ->once()
        ->with(
            Mockery::any(),
            Mockery::on(function ($uri) use (&$urlRef) {
                $urlRef = $uri;
                return TRUE;
            })
        )
        ->andReturn(TRUE);

    Assert::true($service->verify(150));
    Assert::match('%a%150', $urlRef);
});

// No-fill payment target
test(function () {
    $client = new Client(new Config(1, 2, 3));
    $payment = new Payment();
    $payment->setAmount(100);
    $payment->setTarget($target = new Target());
    $target->goid = 99;

    $service = Mockery::mock(PaymentsService::class, [$client])
        ->makePartial()
        ->shouldAllowMockingProtectedMethods();
    $service->shouldReceive('makeRequest')->andReturn(TRUE);

    Assert::true($service->createPayment($payment));
    Assert::equal(100, $payment->getAmount());
    Assert::equal(99, $payment->getTarget()->goid);
});