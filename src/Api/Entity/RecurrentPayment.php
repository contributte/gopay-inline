<?php declare(strict_types = 1);

namespace Contributte\GopayInline\Api\Entity;

use Contributte\GopayInline\Api\Objects\Recurrence;

class RecurrentPayment extends Payment
{

	/** @var Recurrence|null */
	protected $recurrence;

	public function getRecurrence(): ?Recurrence
	{
		return $this->recurrence;
	}

	public function setRecurrence(Recurrence $recurrence): void
	{
		$this->recurrence = $recurrence;
	}

	/**
	 * ABSTRACT ****************************************************************
	 */

	/**
	 * @return mixed[]
	 */
	public function toArray(): array
	{
		$payment = parent::toArray();

		if ($this->recurrence) {
			$payment['recurrence'] = $this->recurrence->toArray();
		}

		return $payment;
	}

}
