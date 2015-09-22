<?php

namespace Markette\GopayInline\Service;

use Markette\GopayInline\Api\Gateway;
use Markette\GopayInline\Api\Lists\Scope;
use Markette\GopayInline\Client;
use Markette\GopayInline\Exception\InvalidStateException;
use Markette\GopayInline\Http\HttpClient;
use Markette\GopayInline\Http\Request;
use Markette\GopayInline\Http\Response;

abstract class AbstractService
{

    /** @var Client */
    protected $client;

    /**
     * @param Client $client
     */
    function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param string $scope
     * @return Response
     */
    protected function doAuthorization($scope = Scope::PAYMENT_ALL)
    {
        return $this->client->authenticate($scope);
    }

    /**
     * Build request and execute him
     *
     * @param string $method
     * @param string $url
     * @param array $data
     * @return Response
     */
    protected function makeRequest($method, $url, array $data = NULL)
    {
        // Verify that client is authenticated
        if (!$this->client->hasToken()) {
            // Do authorization
            $this->doAuthorization();
        }

        $request = new Request();

        // Set-up URL
        $request->setUrl(Gateway::getBaseApiUrl() . '/' . trim($url, '/'));

        // Set-up headers
        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->client->getToken()->accessToken,
        ];
        $request->setHeaders($headers);

        // Set-up opts
        $opts = [
            CURLOPT_SSL_VERIFYPEER => FALSE,
            CURLOPT_RETURNTRANSFER => TRUE,
        ];
        $request->setOpts($opts);

        // Set-up method
        switch ($method) {

            // GET =========================================
            case HttpClient::METHOD_GET:
                $request->appendHeaders([
                    'Content-Type' => 'application/x-www-form-urlencoded',
                ]);
                $request->appendOpts([
                    CURLOPT_HTTPGET => TRUE,
                ]);

                break;

            // POST ========================================
            case HttpClient::METHOD_POST:
                $request->appendHeaders([
                    'Content-Type' => 'application/json',
                ]);
                $request->appendOpts([
                    CURLOPT_POST => TRUE,
                    CURLOPT_POSTFIELDS => json_encode($data),
                ]);

                break;

            default:
                throw new InvalidStateException('Unsupported http method');
        }

        return $this->client->call($request);
    }

}
