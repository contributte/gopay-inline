<?php

/**
 * Test: Auth\Oauth2Client
 */

use Markette\GopayInline\Auth\Oauth2Client;
use Markette\GopayInline\Client;
use Markette\GopayInline\Exception\AuthorizationException;
use Markette\GopayInline\Http\HttpClient;
use Tester\Assert;

require __DIR__ . '/../../../bootstrap.php';

// Simple
test(function () {
	$client = Mockery::namedMock('Client1', Client::class);
	$client->shouldReceive('getClientId')->andReturn(1);
	$client->shouldReceive('getClientSecret')->andReturn(2);

	$response = Mockery::mock();
	$response->shouldReceive('getData')->andReturn(['foo' => 'bar']);

	$http = Mockery::mock(HttpClient::class);
	$http->shouldReceive('doRequest')->andReturn($response);

	$oauth2 = new Oauth2Client($client, $http);
	$response2 = $oauth2->authenticate(['scope' => 'foobar']);

	Assert::same($response, $response2);
});

// cURL error
test(function () {
	$client = Mockery::namedMock('Client2', Client::class);
	$client->shouldReceive('getClientId')->andReturn(1);
	$client->shouldReceive('getClientSecret')->andReturn(2);

	$response = Mockery::mock();
	$response->shouldReceive('getData')->andReturn(FALSE);
	$response->shouldReceive('getCode')->andReturn(404);

	$http = Mockery::mock(HttpClient::class);
	$http->shouldReceive('doRequest')->andReturn($response);

	$oauth2 = new Oauth2Client($client, $http);

	Assert::exception(function () use ($oauth2) {
		$oauth2->authenticate(['scope' => 'foobar']);
	}, AuthorizationException::class);
});

// Gopay error
test(function () {
	$client = Mockery::namedMock('Client3', Client::class);
	$client->shouldReceive('getClientId')->andReturn(1);
	$client->shouldReceive('getClientSecret')->andReturn(2);

	$response = Mockery::mock();
	$response->shouldReceive('getData')->andReturn((object) ['errors' => [0 => (object) ['error_code' => 500, 'scope' => 'G', 'field' => 'foobar', 'message' => 'foo foo foo']]]);

	$http = Mockery::mock(HttpClient::class);
	$http->shouldReceive('doRequest')->andReturn($response);

	$oauth2 = new Oauth2Client($client, $http);

	Assert::exception(function () use ($oauth2) {
		$oauth2->authenticate(['scope' => 'foobar']);
	}, AuthorizationException::class, '#500 (G)[foobar] foo foo foo');
});
