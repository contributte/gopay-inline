<?php declare(strict_types = 1);

/**
 * Test: Http\HttpClient
 */

use Contributte\GopayInline\Exception\HttpException;
use Contributte\GopayInline\Http\HttpClient;
use Contributte\GopayInline\Http\Io;
use Contributte\GopayInline\Http\Request;
use Contributte\GopayInline\Http\Response;
use Tester\Assert;

require __DIR__ . '/../../../bootstrap.php';

// FALSE response
test(function (): void {
	$response = new Response();
	$response->setError('Error');

	$io = Mockery::mock(Io::class);
	$io->shouldReceive('call')->andReturn($response);
	$http = new HttpClient();
	$http->setIo($io);

	Assert::throws(function () use ($http): void {
		$http->doRequest(new Request());
	}, HttpException::class);
});

// Error response
test(function (): void {
	$error = (object) ['error_code' => 500, 'scope' => 'S', 'field' => 'F', 'message' => 'M'];
	$io = Mockery::mock(Io::class);
	$io->shouldReceive('call')->andReturnUsing(function () use ($error) {
		$r = new Response();
		$r->setData(['errors' => [$error]]);

		return $r;
	});
	$http = new HttpClient();
	$http->setIo($io);

	Assert::throws(function () use ($http): void {
		$http->doRequest(new Request());
	}, HttpException::class, HttpException::format($error));
});

// Success response
test(function (): void {
	$data = ['a' => 'b'];
	$io = Mockery::mock(Io::class);
	$io->shouldReceive('call')->andReturnUsing(function () use ($data) {
		$r = new Response();
		$r->setData($data);

		return $r;
	});
	$http = new HttpClient();
	$http->setIo($io);

	Assert::same($data, $http->doRequest(new Request())->data);
});
