<?php

namespace Markette\GopayInline\Api\Objects;

class Recurrence extends AbstractObject
{

	/** @var string */
	public $cycle;

	/** @var float */
	public $period;

	/** @var string */
	public $dateTo;

	/**
	 * ABSTRACT ****************************************************************
	 */

	/**
	 * @return array
	 */
	public function toArray()
	{
		return [
			'recurrence_cycle' => $this->cycle,
			'recurrence_period' => $this->period,
			'recurrence_date_to' => $this->dateTo,
		];
	}

}
