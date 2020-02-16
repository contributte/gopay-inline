<?php declare(strict_types = 1);

/**
 * Test: Http\Response
 */

use Contributte\GopayInline\Http\Response;
use Tester\Assert;

require __DIR__ . '/../../../bootstrap.php';

// Empty response
test(function (): void {
	$r = new Response();

	Assert::null($r->getCode());
	Assert::null($r->getError());

	Assert::type('array', $r->getHeaders());
	Assert::count(0, $r->getHeaders());

	Assert::type('array', $r->getData());
	Assert::count(0, $r->getData());
});

// Simple response
test(function (): void {
	$r = new Response();

	$r->setData($data = ['a' => 'b']);
	$r->setHeaders($headers = ['h' => 1]);
	$r->setCode($code = 200);

	Assert::true($r->isSuccess());
	$r->setError($error = 'Some error');
	Assert::false($r->isSuccess());

	Assert::equal($error, $r->getError());
	Assert::equal($data, $r->getData());
	Assert::equal($headers, $r->getHeaders());
	Assert::equal($code, $r->getCode());
});

// Array access
test(function (): void {
	$r = new Response();
	$r->setData($data = ['a' => 'b']);

	Assert::equal($data['a'], $r['a']);
	Assert::true($r->offsetExists('a'));
	Assert::false($r->offsetExists('b'));

	$r['b'] = 1;
	Assert::equal(1, $r['b']);
	$r->offsetUnset('b');
	Assert::false(isset($r['b']));
	Assert::false(isset($r['c']));

	Assert::error(function () use ($r): void {
		$a = $r['c'];
	}, E_NOTICE);

	$r->setData(null);
	Assert::count(0, $r->getData());
	Assert::false(isset($r['c']));
	Assert::null($r['c']);
});

// Countable
test(function (): void {
	$r = new Response();
	Assert::count(0, $r);

	$r->setData($data = ['a' => 'b']);
	Assert::count(1, $r);
});

// Iterator
test(function (): void {
	$r = new Response();
	$r->setData($data = ['a' => 'b']);
	Assert::equal($data, iterator_to_array($r));
});

// Magic methods
test(function (): void {
	$r = new Response();
	$r->setCode($code = 200);
	$r->setData($data = ['a' => 'b']);

	Assert::equal($data, $r->data);
	Assert::equal($data['a'], $r->data['a']);
	Assert::equal($data['a'], $r->a);
	Assert::equal($code, $r->code);
});
