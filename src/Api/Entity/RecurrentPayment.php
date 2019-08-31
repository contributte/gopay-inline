<?php

namespace Contributte\GopayInline\Api\Entity;

use Contributte\GopayInline\Api\Objects\Recurrence;

class RecurrentPayment extends Payment
{

	/** @var Recurrence */
	protected $recurrence;

	/**
	 * @return Recurrence
	 */
	public function getRecurrence()
	{
		return $this->recurrence;
	}

	/**
	 * @param Recurrence $recurrence
	 * @return void
	 */
	public function setRecurrence(Recurrence $recurrence)
	{
		$this->recurrence = $recurrence;
	}

	/**
	 * ABSTRACT ****************************************************************
	 */

	/**
	 * @return array
	 */
	public function toArray()
	{
		$payment = parent::toArray();

		$recurrence = $this->getRecurrence();
		if ($recurrence) {
			$payment['recurrence'] = $recurrence->toArray();
		}

		return $payment;
	}

}
