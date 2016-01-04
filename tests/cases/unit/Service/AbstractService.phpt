<?php

/**
 * Test: Service\AbstractService
 */

use Markette\GopayInline\Client;
use Markette\GopayInline\Exception\InvalidStateException;
use Markette\GopayInline\Http\Http;
use Markette\GopayInline\Http\Request;
use Markette\GopayInline\Service\AbstractService;
use Tester\Assert;

require __DIR__ . '/../../../bootstrap.php';

class DummyService extends AbstractService
{

    public function makeRequest($method, $uri, array $data = NULL, $contentType = Http::CONTENT_JSON)
    {
        return parent::makeRequest($method, $uri, $data, $contentType);
    }
}

// No token
test(function () {
    $client = Mockery::namedMock('Client1', Client::class);
    $client->shouldReceive('hasToken')->andReturn(FALSE);
    $client->shouldReceive('authenticate')->andThrow(RuntimeException::class);

    $service = Mockery::mock(DummyService::class, [$client])->makePartial();
    $service->shouldAllowMockingProtectedMethods();

    Assert::throws(function () use ($service) {
        $service->makeRequest('GET', 'test');
    }, RuntimeException::class);
});

// Simple get
test(function () {
    $client = Mockery::namedMock('Client2', Client::class);
    $client->shouldReceive('hasToken')->andReturn(FALSE);
    $client->shouldReceive('authenticate');
    $client->shouldReceive('getToken')->andReturn((object)['accessToken' => 12345]);
    $client->shouldReceive('call')->andReturnUsing(function (Request $request) {
        return $request;
    });

    $service = Mockery::mock(DummyService::class, [$client]);

    /** @var Request $request */
    $request = $service->makeRequest('GET', 'foobar');
    Assert::match('%a%foobar', $request->getUrl());
    Assert::true(in_array(CURLOPT_HTTPGET, $request->getOpts()));
});

// Simple post
test(function () {
    $client = Mockery::namedMock('Client3', Client::class);
    $client->shouldReceive('hasToken')->andReturn(TRUE);
    $client->shouldReceive('getToken')->andReturn((object)['accessToken' => 12345]);
    $client->shouldReceive('call')->andReturnUsing(function (Request $request) {
        return $request;
    });

    $service = Mockery::mock(DummyService::class, [$client]);
    $data = ['foo' => 1, 'bar' => 2];

    /** @var Request $request */
    $request = $service->makeRequest('POST', 'foobar', $data);
    Assert::match('%a%foobar', $request->getUrl());
    Assert::true(in_array(CURLOPT_POST, $request->getOpts()));
    Assert::same($data, json_decode($request->getOpts()[CURLOPT_POSTFIELDS], TRUE));
});

// Invalid method
test(function () {
    $client = Mockery::namedMock('Client3', Client::class);
    $client->shouldReceive('hasToken')->andReturn(TRUE);
    $client->shouldReceive('getToken')->andReturn((object)['accessToken' => 12345]);
    $client->shouldReceive('call')->andReturnUsing(function (Request $request) {
        return $request;
    });

    $service = Mockery::mock(DummyService::class, [$client]);

    Assert::throws(function () use ($service) {
        $service->makeRequest('FUCK', 'foobar');
    }, InvalidStateException::class);
});
