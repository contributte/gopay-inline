<?php declare(strict_types = 1);

namespace Contributte\GopayInline\Service;

use Contributte\GopayInline\Http\Response;

class AccountsService extends AbstractService
{

	/**
	 * @param string $date_from yyyy-mm-dd
	 * @param string $date_to yyyy-mm-dd
	 */
	public function getAccountStatement(string $date_from, string $date_to, string $currency, string $format): Response
	{
		$data = [
			'date_from' => $date_from,
			'date_to' => $date_to,
			'currency' => $currency,
			'format' => $format,
			'goid' => $this->client->getGoId(),
		];

		return $this->makeRequest('POST', 'accounts/account-statement', $data);
	}

}
