<?php

declare(strict_types=1);

namespace Contributte\GopayInline\Service;


use Contributte\GopayInline\Api\Gateway;
use Contributte\GopayInline\Api\Lists\Scope;
use Contributte\GopayInline\Client;
use Contributte\GopayInline\Exception\InvalidStateException;
use Contributte\GopayInline\Http\Http;
use Contributte\GopayInline\Http\HttpClient;
use Contributte\GopayInline\Http\Request;
use Contributte\GopayInline\Http\Response;

abstract class AbstractService
{

	/** @var array */
	public $onRequest = [];

	/** @var array */
	public $onAuthorization = [];

	/** @var Client */
	protected $client;

	/** @var array */
	protected $options = [
		CURLOPT_SSL_VERIFYPEER => false,
		CURLOPT_RETURNTRANSFER => true,
	];


	/**
	 * @param Client $client
	 */
	public function __construct(Client $client)
	{
		$this->client = $client;
	}


	/**
	 * @param string $scope
	 * @return string
	 */
	protected function doAuthorization($scope = Scope::PAYMENT_ALL)
	{
		// Invoke events
		$this->trigger('onAuthorization', [$scope]);

		return $this->client->authenticate(['scope' => $scope]);
	}


	/**
	 * Build request and execute him
	 *
	 * @param string $method
	 * @param string $uri
	 * @param array $data
	 * @param string|NULL $contentType
	 * @return Response
	 */
	protected function makeRequest($method, $uri, array $data = null, $contentType = Http::CONTENT_JSON)
	{
		// Invoke events
		$this->trigger('onRequest', [$method, $uri, $data]);

		// Verify that client is authenticated
		if (!$this->client->hasToken()) {
			// Do authorization
			$this->doAuthorization();
		}

		$request = new Request();

		// Set-up URL
		$request->setUrl(Gateway::getFullApiUrl($uri));

		// Set-up headers
		$headers = [
			'Accept' => 'application/json',
			'Authorization' => 'Bearer ' . $this->client->getToken()->accessToken,
			'Content-Type' => $contentType,
		];
		$request->setHeaders($headers);

		// Set-up opts
		$request->setOpts($this->options);

		// Set-up method
		switch ($method) {

			// GET =========================================
			case HttpClient::METHOD_GET:
				$request->appendOpts([
					CURLOPT_HTTPGET => true,
				]);

				break;

			// POST ========================================
			case HttpClient::METHOD_POST:
				$request->appendOpts([
					CURLOPT_POST => true,
					CURLOPT_POSTFIELDS => $contentType === Http::CONTENT_FORM ? http_build_query($data) : json_encode($data),
				]);

				break;

			default:
				throw new InvalidStateException('Unsupported http method');
		}

		return $this->client->call($request);
	}


	/**
	 * @param string $event
	 * @param array $data
	 * @return void
	 */
	protected function trigger($event, array $data)
	{
		foreach ($this->{$event} as $callback) {
			call_user_func_array($callback, $data);
		}
	}

}
