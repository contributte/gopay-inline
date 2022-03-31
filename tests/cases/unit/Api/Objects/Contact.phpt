<?php declare(strict_types = 1);

/**
 * Test: Api\Lists\Objects\Contact
 */

use Contributte\GopayInline\Api\Objects\Contact;
use Tester\Assert;

require __DIR__ . '/../../../../bootstrap.php';

// Simple
test(function (): void {
	$contact = new Contact();
	$contact->firstname = 'foo1';
	$contact->lastname = 'foo2';
	$contact->email = 'foo3';
	$contact->phone = 'foo4';
	$contact->city = 'foo5';
	$contact->street = 'foo6';
	$contact->zip = 'foo7';
	$contact->country = 'foo8';

	$array = $contact->toArray();

	Assert::equal('foo1', $array['first_name']);
	Assert::equal('foo2', $array['last_name']);
	Assert::equal('foo3', $array['email']);
	Assert::equal('foo4', $array['phone_number']);
	Assert::equal('foo5', $array['city']);
	Assert::equal('foo6', $array['street']);
	Assert::equal('foo7', $array['postal_code']);
	Assert::equal('foo8', $array['country_code']);
});
