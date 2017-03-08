<?php

namespace Markette\GopayInline\Service;

use Markette\GopayInline\Http\Response;

class AccountsService extends AbstractService
{

	/**
	 * @param string $date_from yyyy-mm-dd
	 * @param string $date_to yyyy-mm-dd
	 * @param string $currency
	 * @param string $format
	 * @return Response
	 */
	public function getAccountStatement($date_from, $date_to, $currency, $format)
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
