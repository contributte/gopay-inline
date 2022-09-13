<?php declare(strict_types = 1);

namespace Contributte\GopayInline\Service;

use Contributte\GopayInline\Api\Gateway;
use Contributte\GopayInline\Api\Lists\Scope;
use Contributte\GopayInline\Api\Token;
use Contributte\GopayInline\Client;
use Contributte\GopayInline\Exception\InvalidStateException;
use Contributte\GopayInline\Http\Http;
use Contributte\GopayInline\Http\HttpClient;
use Contributte\GopayInline\Http\Request;
use Contributte\GopayInline\Http\Response;

abstract class AbstractService
{

	/** @var callable[] */
	public $onRequest = [];

	/** @var callable[] */
	public $onAuthorization = [];

	/** @var Client */
	protected $client;

	/** @var mixed[] */
	protected $options = [
		CURLOPT_SSL_VERIFYPEER => false,
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
	 * Build request and execute him
	 *
	 * @param mixed[] $data
	 */
	protected function makeRequest(string $method, string $uri, ?array $data = null, ?string $contentType = Http::CONTENT_JSON): Response
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
		/** @var Token $token */
		$token = $this->client->getToken();

		$headers = [
			'Accept' => 'application/json',
			'Authorization' => 'Bearer ' . $token->accessToken,
			'Content-Type' => $contentType,
		];
		$request->setHeaders($headers);

		// Set-up opts
		$request->setOpts($this->options);

		// Set-up method
		switch ($method) {
			case HttpClient::METHOD_GET:
				$request->appendOpts([
					CURLOPT_HTTPGET => true,
				]);
				break;

			case HttpClient::METHOD_POST:
				$request->appendOpts([
					CURLOPT_POST => true,
					CURLOPT_POSTFIELDS => $contentType === Http::CONTENT_FORM ? http_build_query((array) $data) : json_encode($data),
				]);
				break;

			default:
				throw new InvalidStateException('Unsupported http method');
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
