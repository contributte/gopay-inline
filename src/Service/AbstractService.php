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
		CURLOPT_SSL_VERIFYPEER => false, // TODO: [EA] Exposes a connection to MITM attacks. Use true (default) to stay safe.
		CURLOPT_RETURNTRANSFER => true,
	];


	public function __construct(Client $client)
	{
		$this->client = $client;
	}


	protected function doAuthorization(string $scope = Scope::PAYMENT_ALL): string
	{
		// Invoke events
		$this->trigger('onAuthorization', [$scope]);

		return $this->client->authenticate(['scope' => $scope]);
	}


	/**
	 * Build request and execute him.
	 *
	 * @param mixed[] $data
	 * @return Response
	 */
	protected function makeRequest(string $method, string $uri, array $data = null, ?string $contentType = Http::CONTENT_JSON): Response
	{
		$this->trigger('onRequest', [$method, $uri, $data]);

		if ($this->client->hasToken() === false) {
			$this->doAuthorization();
		}

		$request = new Request;
		$request->setUrl(Gateway::getFullApiUrl($uri));
		$request->setHeaders([
			'Accept' => 'application/json',
			'Authorization' => 'Bearer ' . $this->client->getToken()->accessToken,
			'Content-Type' => $contentType,
		]);
		$request->setOpts($this->options);

		if ($method === HttpClient::METHOD_GET) {
			$request->appendOpts([
				CURLOPT_HTTPGET => true,
			]);
		} elseif ($method === HttpClient::METHOD_POST) {
			$request->appendOpts([
				CURLOPT_POST => true,
				CURLOPT_POSTFIELDS => $contentType === Http::CONTENT_FORM ? http_build_query($data) : json_encode($data),
			]);
		} else {
			throw new InvalidStateException('Unsupported HTTP method, because "' . $method . '" given. Did you mean "GET" or "POST"?');
		}

		return $this->client->call($request);
	}


	/**
	 * @param mixed[] $data
	 */
	protected function trigger(string $event, array $data): void
	{
		foreach ($this->{$event} as $callback) {
			call_user_func_array($callback, $data);
		}
	}
}
