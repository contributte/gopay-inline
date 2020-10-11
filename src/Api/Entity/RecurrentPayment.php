<?php

declare(strict_types=1);

namespace Contributte\GopayInline\Api\Entity;


use Contributte\GopayInline\Api\Objects\Recurrence;

final class RecurrentPayment extends Payment
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
	 * @return mixed[]
	 */
	public function toArray(): array
	{
		$return = parent::toArray();
		if (($recurrence = $this->getRecurrence()) !== null) {
			$return['recurrence'] = $recurrence->toArray();
		}

		return $return;
	}
}
