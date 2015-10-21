<?php

/**
 * Test: Http\HttpClient
 */

use Markette\GopayInline\Exception\HttpException;
use Markette\GopayInline\Http\Curl;
use Markette\GopayInline\Http\HttpClient;
use Markette\GopayInline\Http\Io;
use Markette\GopayInline\Http\Request;
use Markette\GopayInline\Http\Response;
use Tester\Assert;

require __DIR__ . '/../../../bootstrap.php';

// Io
test(function () {
    $http = new HttpClient();
    Assert::type(Io::class, $http->getIo());
});
// FALSE response
test(function () {
    $io = Mockery::mock(Curl::class);
    $io->shouldReceive('call')->andReturn(FALSE);
    $http = new HttpClient();
    $http->setIo($io);

    Assert::throws(function () use ($http) {
        $http->doRequest(new Request());
    }, HttpException::class);
});

// Error response
test(function () {
    $error = (object)['error_code' => 500, 'scope' => 'S', 'field' => 'F', 'message' => 'M'];
    $io = Mockery::mock(Curl::class);
    $io->shouldReceive('call')->andReturnUsing(function () use ($error) {
        $r = new Response();
        $r->setData(['errors' => [$error]]);
        return $r;
    });
    $http = new HttpClient();
    $http->setIo($io);

    Assert::throws(function () use ($http, $error) {
        $http->doRequest(new Request());
    }, HttpException::class, HttpException::format($error));
});

// Success response
test(function () {
    $data = ['a' => 'b'];
    $io = Mockery::mock(Curl::class);
    $io->shouldReceive('call')->andReturnUsing(function () use ($data) {
        $r = new Response();
        $r->setData($data);
        return $r;
    });
    $http = new HttpClient();
    $http->setIo($io);

    Assert::same($data, $http->doRequest(new Request())->data);
});
