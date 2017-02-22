<?php

namespace Markette\GopayInline\Api\Objects;

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
		return intval($this->count);
	}
		
	/**
	 * @return float
	 */
	public function getAmountInCents()
	{
		return round($this->amount * 100);
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
	 */	
	public function setName($name)
	{
		$this->name = $name;
	}

	/**
	 * @param float $amount
	 */	
	public function setAmount($amount)
	{
		$this->amount = $amount;
	}

	/**
	 * @param int $count
	 */
	public function setCount($count)
	{
		$this->count = intval($count);
	}

	/**
	 * @param string $type
	 */
	public function setType($type)
	{
		$this->type = $type;
	}

	/**
	 * @param int $vatRate
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
		
		if (($type = $this->getType())) {
			$data['type'] = $type;
		}

		if (($vatRate = $this->getVatRate())) {
			$data['vat_rate'] = $vatRate;
		}

		return $data;
	}

}
