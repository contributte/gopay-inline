<?php declare(strict_types = 1);

namespace Contributte\GopayInline\Http;

use Contributte\GopayInline\Exception\HttpException;

class HttpClient implements Http
{

	/** @var Io */
	protected $io;

	public function getIo(): Io
	{
		if (!$this->io) {
			$this->io = new Curl();
		}

		return $this->io;
	}

	public function setIo(Io $io): void
	{
		$this->io = $io;
	}

	/**
	 * API *********************************************************************
	 */

	/**
	 * Take request and execute him
	 */
	public function doRequest(Request $request): Response
	{
		$response = $this->getIo()->call($request);
		if (!$response->isSuccess()) {
			// cURL error
			throw new HttpException('Request failed');
		}

		if (isset($response->data['errors'])) {
			// GoPay errors
			$error = $response->data['errors'][0];
			throw new HttpException(HttpException::format($error), $error->error_code);
		}

		return $response;
	}

}
