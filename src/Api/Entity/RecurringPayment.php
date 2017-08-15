<?php

namespace Markette\GopayInline\Api\Entity;

class RecurringPayment extends Payment
{

	/**
	 * ABSTRACT ****************************************************************
	 */

	/**
	 * @return array
	 */
	public function toArray()
	{
		$data = [];

		$data['amount'] = $this->getAmountInCents();
		$data['currency'] = $this->getCurrency();

		$data['order_number'] = $this->getOrderNumber();
		$data['order_description'] = $this->getOrderDescription();

		$data['items'] = $this->formatItems($this->getItems());

		$parameters = $this->getParameters();
		if ($parameters) {
			$data['additional_params'] = $this->formatParameters($parameters);
		}
		return $data;
	}

}
