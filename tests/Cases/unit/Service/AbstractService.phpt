<?php declare(strict_types = 1);

/**
 * Test: Service\AbstractService
 */

namespace Tests\Cases\Unit\Service;

use Contributte\GopayInline\Api\Token;
use Contributte\GopayInline\Client;
use Contributte\GopayInline\Exception\InvalidStateException;
use Contributte\GopayInline\Http\Http;
use Contributte\GopayInline\Http\Request;
use Contributte\GopayInline\Http\Response;
use Contributte\GopayInline\Service\AbstractService;
use Mockery;
use RuntimeException;
use Tester\Assert;

require __DIR__ . '/../../../bootstrap.php';

// phpcs:disable Squiz.Classes.ClassFileName.NoMatch
class DummyService extends AbstractService
{

	/**
	 * @param mixed[]|NULL $data
	 */
	public function makeRequest(string $method, string $uri, ?array $data = null, ?string $contentType = Http::CONTENT_JSON): Response
	{
		return parent::makeRequest($method, $uri, $data, $contentType);
	}

}

// No token
test(function (): void {
	$client = Mockery::namedMock('Client1', Client::class);
	$client->shouldReceive('hasToken')->andReturn(false);
	$client->shouldReceive('authenticate')->andThrow(RuntimeException::class);

	$service = Mockery::mock(DummyService::class, [$client])->makePartial();
	$service->shouldAllowMockingProtectedMethods();

	Assert::throws(function () use ($service): void {
		$service->makeRequest('GET', 'test');
	}, RuntimeException::class);
});

// Simple get
test(function (): void {
	/** @var Request $request */
	$request = null;

	$client = Mockery::namedMock('Client2', Client::class);
	$client->shouldReceive('hasToken')->andReturn(false);
	$client->shouldReceive('authenticate');
	$client->shouldReceive('getToken')->andReturn(Token::create(['accessToken' => '12345']));
	$client->shouldReceive('call')->andReturnUsing(function (Request $req) use (&$request) {
		$request = $req;
		return new Response();
	});

	$service = new DummyService($client);

	$service->makeRequest('GET', 'foobar');
	Assert::match('%a%foobar', $request->getUrl());
	Assert::true(array_key_exists(CURLOPT_HTTPGET, $request->getOpts()));
});

// Simple post
test(function (): void {
	/** @var Request $request */
	$request = null;

	$client = Mockery::namedMock('Client3', Client::class);
	$client->shouldReceive('hasToken')->andReturn(true);
	$client->shouldReceive('getToken')->andReturn(Token::create(['accessToken' => '12345']));
	$client->shouldReceive('call')->andReturnUsing(function (Request $req) use (&$request) {
		$request = $req;
		return new Response();
	});

	$service = new DummyService($client);
	$data = ['foo' => 1, 'bar' => 2];

	$service->makeRequest('POST', 'foobar', $data);
	Assert::match('%a%foobar', $request->getUrl());
	Assert::true(array_key_exists(CURLOPT_POST, $request->getOpts()));
	Assert::same($data, json_decode($request->getOpts()[CURLOPT_POSTFIELDS], true));
});

// Invalid method
test(function (): void {
	/** @var Request $request */
	$request = null;

	$client = Mockery::namedMock('Client3', Client::class);
	$client->shouldReceive('hasToken')->andReturn(true);
	$client->shouldReceive('getToken')->andReturn(Token::create(['accessToken' => '12345']));
	$client->shouldReceive('call')->andReturnUsing(function (Request $req) use (&$request) {
		$request = $req;
		return new Response();
	});

	$service = new DummyService($client);

	Assert::throws(function () use ($service): void {
		$service->makeRequest('FUCK', 'foobar');
	}, InvalidStateException::class);
});
