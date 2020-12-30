<?php declare(strict_types = 1);

namespace Contributte\GopayInline\Api\Entity;

class RecurringPayment extends Payment
{

	/**
	 * @return mixed[]
	 */
	public function toArray(): array
	{
		$data = [];

		$data['amount'] = $this->getAmountInCents();
		$data['currency'] = $this->getCurrency();

		$data['order_number'] = $this->getOrderNumber();
		$data['order_description'] = $this->getOrderDescription();

		$data['items'] = $this->formatItems($this->getItems());

		$parameters = $this->getParameters();
		if (count($parameters) > 0) {
			$data['additional_params'] = $this->formatParameters($parameters);
		}

		return $data;
	}

}
