<?php declare(strict_types = 1);

/**
 * Test: Http\Request
 */

use Contributte\GopayInline\Http\Request;
use Tester\Assert;

require __DIR__ . '/../../../bootstrap.php';

// Empty request
test(function (): void {
	$r = new Request();

	Assert::null($r->getUrl());
	Assert::equal([], $r->getData());
	Assert::equal([], $r->getHeaders());
	Assert::equal([], $r->getOpts());
});
// Simple request
test(function (): void {
	$r = new Request();
	$r->setData($data = ['foo' => 'bar']);
	$r->setHeaders($headers = ['foo1' => 'bar1']);
	$r->setOpts($opts = ['foo2' => 'bar2']);
	$r->setUrl($url = 'www.foo.bar');

	Assert::equal($data, $r->getData());
	Assert::equal($headers, $r->getHeaders());
	Assert::equal($opts, $r->getOpts());
	Assert::equal($url, $r->getUrl());
});

// Append headers/opts
test(function (): void {
	$r = new Request();

	Assert::equal([], $r->getHeaders());
	Assert::equal([], $r->getOpts());

	$r->appendHeaders(['h' => '1']);
	$r->appendOpts(['o' => '1']);
	Assert::equal(['h' => '1'], $r->getHeaders());
	Assert::equal(['o' => '1'], $r->getOpts());

	$r->appendHeaders(['h2' => '2']);
	$r->appendOpts(['o2' => '2']);
	Assert::equal(['h' => '1', 'h2' => '2'], $r->getHeaders());
	Assert::equal(['o' => '1', 'o2' => '2'], $r->getOpts());

	$r->addHeader('h3', '3');
	$r->addOpt('o3', '3');
	Assert::equal(['h' => '1', 'h2' => '2', 'h3' => '3'], $r->getHeaders());
	Assert::equal(['o' => '1', 'o2' => '2', 'o3' => '3'], $r->getOpts());
});
