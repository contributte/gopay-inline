<?php

/**
 * Test: Api\Lists\Objects\Payer
 */

use Markette\GopayInline\Api\Objects\Contact;
use Markette\GopayInline\Api\Objects\Payer;
use Tester\Assert;

require __DIR__ . '/../../../../bootstrap.php';

// Simple
test(function () {
	$payer = new Payer();
	$payer->allowedPaymentInstruments = [1, 2, 3];
	$payer->defaultPaymentInstrument = 1;
	$payer->allowedSwifts = [4, 5, 6];
	$payer->defaultSwift = 4;
	$payer->contact = $contact = new Contact();

	$array = $payer->toArray();

	Assert::equal([1, 2, 3], $array['allowed_payment_instruments']);
	Assert::equal(1, $array['default_payment_instrument']);
	Assert::equal([4, 5, 6], $array['allowed_swifts']);
	Assert::equal(4, $array['default_swift']);
	Assert::equal($contact->toArray(), $array['contact']);
});
