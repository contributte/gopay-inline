<?php

namespace Markette\GopayInline\Api\Objects;

use Markette\GopayInline\Utils\Money;

class Item extends AbstractObject
{

	/** @var string */
	public $name;

	/** @var float */
	public $amount;

	/** @var int */
	public $count = 1;

	/** @var string */
	public $type;

	/** @var int */
	public $vatRate;

	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @return float
	 */
	public function getAmount()
	{
		return $this->amount;
	}

	/**
	 * @return int
	 */
	public function getCount()
	{
		return $this->count;
	}

	/**
	 * @return float
	 */
	public function getAmountInCents()
	{
		return Money::toCents($this->getAmount());
	}

	/**
	 * @return string
	 */
	public function getType()
	{
		return $this->type;
	}

	/**
	 * @return int
	 */
	public function getVatRate()
	{
		return $this->vatRate;
	}

	/**
	 * @param string $name
	 * @return void
	 */
	public function setName($name)
	{
		$this->name = $name;
	}

	/**
	 * @param float $amount
	 * @return void
	 */
	public function setAmount($amount)
	{
		$this->amount = $amount;
	}

	/**
	 * @param int $count
	 * @return void
	 */
	public function setCount($count)
	{
		$this->count = intval($count);
	}

	/**
	 * @param string $type
	 * @return void
	 */
	public function setType($type)
	{
		$this->type = $type;
	}

	/**
	 * @param int $vatRate
	 * @return void
	 */
	public function setVatRate($vatRate)
	{
		$this->vatRate = $vatRate;
	}

	/**
	 * ABSTRACT ****************************************************************
	 */

	/**
	 * @return array
	 */
	public function toArray()
	{
		$data = [];
		$data['name'] = $this->getName();
		$data['amount'] = $this->getAmountInCents();
		$data['count'] = $this->getCount();

		// NOT REQUIRED ====================================

		$type = $this->getType();
		if ($type) {
			$data['type'] = $type;
		}

		$vatRate = $this->getVatRate();
		if ($vatRate) {
			$data['vat_rate'] = $vatRate;
		}

		return $data;
	}

}
