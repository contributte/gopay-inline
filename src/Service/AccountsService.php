<?php

declare(strict_types=1);

namespace Contributte\GopayInline\Service;


use Contributte\GopayInline\Http\Response;

class AccountsService extends AbstractService
{
	public function getAccountStatement(string $dateFrom, string $dateTo, string $currency, string $format): Response
	{
		$data = [
			'date_from' => $dateFrom, // format "yyyy-mm-dd"
			'date_to' => $dateTo, // format "yyyy-mm-dd"
			'currency' => $currency,
			'format' => $format,
			'goid' => $this->client->getGoId(),
		];

		return $this->makeRequest('POST', 'accounts/account-statement', $data);
	}
}
