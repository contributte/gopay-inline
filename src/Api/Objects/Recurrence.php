<?php

declare(strict_types=1);

namespace Contributte\GopayInline\Api\Objects;


final class Recurrence extends AbstractObject
{

	/** @var string */
	public $cycle;

	/** @var float */
	public $period;

	/** @var string */
	public $dateTo;


	/**
	 * @return mixed[]
	 */
	public function toArray(): array
	{
		return [
			'recurrence_cycle' => $this->cycle,
			'recurrence_period' => $this->period,
			'recurrence_date_to' => $this->dateTo,
		];
	}


	public function getCycle(): string
	{
		return $this->cycle;
	}


	public function getPeriod(): float
	{
		return $this->period;
	}


	public function getDateTo(): string
	{
		return $this->dateTo;
	}
}
