<?php

declare(strict_types=1);

namespace Contributte\GopayInline\Auth;


use Contributte\GopayInline\Api\Gateway;
use Contributte\GopayInline\Client;
use Contributte\GopayInline\Exception\AuthorizationException;
use Contributte\GopayInline\Http\Http;
use Contributte\GopayInline\Http\Request;
use Contributte\GopayInline\Http\Response;

final class Oauth2Client implements Auth
{

	/** @var Client */
	private $client;

	/** @var Http */
	private $http;


	public function __construct(Client $client, Http $http)
	{
		$this->client = $client;
		$this->http = $http;
	}


	/**
	 * @param mixed[] $credentials
	 * @return Response
	 */
	public function authenticate(array $credentials): Response
	{
		$request = new Request;
		$request->setUrl(Gateway::getOauth2TokenUrl());
		$request->setHeaders([
			'Accept' => 'application/json',
			'Content-Type' => 'application/x-www-form-urlencoded',
		]);
		$request->setOpts([
			CURLOPT_SSL_VERIFYPEER => false, // TODO: [EA] Exposes a connection to MITM attacks. Use true (default) to stay safe.
			CURLOPT_POST => true,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_USERPWD => $this->client->getClientId() . ':' . $this->client->getClientSecret(),
			CURLOPT_POSTFIELDS => http_build_query([
				'grant_type' => 'client_credentials',
				'scope' => $credentials['scope'],
			]),
		]);
		$response = $this->http->doRequest($request);

		if ($response->getData() === null) {
			throw new AuthorizationException('cURL error: Authorization failed', $response->getCode());
		}
		if (isset($response->getData()['errors'])) { // GoPay errors
			$error = $response->getData()['errors'][0];
			throw new AuthorizationException(AuthorizationException::format($error), $error->error_code);
		}

		return $response;
	}
}
