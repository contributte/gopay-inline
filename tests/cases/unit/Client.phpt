<?php declare(strict_types = 1);

/**
 * Test: Client
 */

use Contributte\GopayInline\Api\Token;
use Contributte\GopayInline\Auth\Auth;
use Contributte\GopayInline\Client;
use Contributte\GopayInline\Config;
use Contributte\GopayInline\Exception\GopayException;
use Contributte\GopayInline\Http\Http;
use Contributte\GopayInline\Http\Request;
use Contributte\GopayInline\Http\Response;
use Contributte\GopayInline\Service\AccountsService;
use Contributte\GopayInline\Service\PaymentsService;
use Tester\Assert;

require __DIR__ . '/../../bootstrap.php';

// Default Auth/Http
test(function (): void {
	$config = new Config('1', '2', '3');
	$mock = Mockery::mock(Client::class, [$config])
		->makePartial()
		->shouldAllowMockingProtectedMethods();

	Assert::type(Http::class, $mock->getHttp());
	Assert::type(Auth::class, $mock->getAuth());
});

// Token
test(function (): void {
	$config = new Config('1', '2', '3');
	$client = new Client($config);

	Assert::false($client->hasToken());
	Assert::null($client->getToken());

	$client->setToken($token = new Token());
	$token->accessToken = time();
	Assert::equal($token->accessToken, $client->getToken()->accessToken);

	$client->setToken($token = time() . time());
	Assert::equal($token, $client->getToken()->accessToken);
});

// Services
test(function (): void {
	$config = new Config('1', '2', '3');
	$client = new Client($config);

	Assert::type(PaymentsService::class, $client->createPaymentsService());
	Assert::type(PaymentsService::class, $client->payments);
	Assert::type(AccountsService::class, $client->createAccountsService());
	Assert::type(AccountsService::class, $client->accounts);
	Assert::null($client->random);
});

// Services (same service)
test(function (): void {
	$config = new Config('1', '2', '3');
	$client = new Client($config);

	$payments1 = $client->payments;
	$payments2 = $client->payments;
	$payments3 = $client->payments;

	Assert::same($payments1, $payments2);
	Assert::same($payments1, $payments3);

	$accounts1 = $client->accounts;
	$accounts2 = $client->accounts;
	$accounts3 = $client->accounts;

	Assert::same($accounts1, $accounts2);
	Assert::same($accounts1, $accounts3);

	Assert::null($client->random);
});

// Call without token
test(function (): void {
	$config = new Config('1', '2', '3');
	$client = new Client($config);

	Assert::throws(function () use ($client): void {
		$client->call(new Request());
	}, GopayException::class);
});

// Auth
test(function (): void {
	$config = new Config('1', '2', '3');
	$client = new Client($config);
	$token = '12345';

	$mock = Mockery::mock(Auth::class);
	$mock->shouldReceive('authenticate')->andReturnUsing(function () use ($token) {
		$r = new Response();
		$r->setData(['access_token' => $token]);

		return $r;
	});
	$client->setAuth($mock);
	$client->authenticate([]);

	Assert::equal($token, $client->getToken()->accessToken);
});

// Request
test(function (): void {
	$config = new Config('1', '2', '3');
	$client = new Client($config);
	$client->setToken(12345);
	$data = ['foo' => 'bar'];

	$mock = Mockery::mock(Http::class);
	$mock->shouldReceive('doRequest')->andReturnUsing(function () use ($data) {
		$r = new Response();
		$r->setData($data);

		return $r;
	});
	$client->setHttp($mock);
	$response = $client->call(new Request());

	Assert::type(Response::class, $response);
	Assert::equal($data, $response->getData());
});
