<?php

declare(strict_types=1);

namespace Contributte\GopayInline\Http;


use Contributte\GopayInline\Exception\HttpException;

final class HttpClient implements Http
{

	/** @var Io */
	protected $io;


	public function getIo(): Io
	{
		if ($this->io === null) {
			$this->io = new Curl();
		}

		return $this->io;
	}


	public function setIo(Io $io): void
	{
		$this->io = $io;
	}


	public function doRequest(Request $request): Response
	{
		if (($response = $this->getIo()->call($request)) === null || $response->getError() !== null) {
			throw new HttpException('cURL error: Request failed');
		}
		if (isset($response->getData()['errors'])) { // GoPay errors
			$error = $response->getData()['errors'][0];
			throw new HttpException('GoPay HTTP error: ' . HttpException::format($error), $error['error_code'] ?? 500);
		}

		return $response;
	}
}
