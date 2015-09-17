<?php

namespace Markette\GopayInline\Http;

use Markette\GopayInline\Client;
use Markette\GopayInline\Exception\HttpException;

class HttpClient
{

    /** Http methods */
    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';

    /** @var Client */
    protected $client;

    /** @var Curl */
    protected $io;

    /**
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->io = new Curl();
    }

    /**
     * @return Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @return Curl
     */
    public function getIo()
    {
        return $this->io;
    }

    /**
     * @param Curl $io
     */
    public function setIo($io)
    {
        $this->io = $io;
    }

    /**
     * API *********************************************************************
     */

    /**
     * Take request and execute him
     *
     * @param Request $request
     * @return Response
     */
    public function doRequest(Request $request)
    {
        $response = $this->io->call($request);
        if (!$response) {
            // cURL error
            throw new HttpException('Request failed');
        } else if (isset($response->getData()->errors)) {
            // GoPay errors
            $error = $response->getData()->errors[0];
            throw new HttpException(HttpException::format($error), $error->error_code);
        }

        return $response;
    }

}
