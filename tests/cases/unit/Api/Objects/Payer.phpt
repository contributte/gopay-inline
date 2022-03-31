<?php declare(strict_types = 1);

/**
 * Test: Api\Lists\Objects\Payer
 */

use Contributte\GopayInline\Api\Objects\Contact;
use Contributte\GopayInline\Api\Objects\Payer;
use Tester\Assert;

require __DIR__ . '/../../../../bootstrap.php';

// Simple
test(function (): void {
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
