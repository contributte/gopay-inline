<?php

namespace Markette\GopayInline\Api\Objects;

class ElectronicPaymentRegister extends AbstractObject
{
	/** @var float */
	private $sum;

	/** @var float */
	private $taxBase;

	/** @var float */
	private $tax;

	/** @var float */
	private $taxBaseReducedRateFirst;

	/** @var float */
	private $taxReducedRateFirst;

	/** @var float */
	private $taxBaseReducedRateSecond;

	/** @var float */
	private $taxReducedRateSecond;

	/** @var string */
	private $currency;

	/**
	 * @return float
	 */
	public function getSum()
	{
		return $this->sum;
	}

	/**
	 * @return float
	 */
	public function getTaxBase()
	{
		return $this->taxBase;
	}

	/**
	 * @return float
	 */
	public function getTax()
	{
		return $this->tax;
	}

	/**
	 * @return float
	 */
	public function getTaxBaseReducedRateFirst()
	{
		return $this->taxBaseReducedRateFirst;
	}

	/**
	 * @return float
	 */
	public function getTaxReducedRateFirst()
	{
		return $this->taxReducedRateFirst;
	}

	/**
	 * @return float
	 */
	public function getTaxBaseReducedRateSecond()
	{
		return $this->taxBaseReducedRateSecond;
	}

	/**
	 * @return float
	 */
	public function getTaxReducedRateSecond()
	{
		return $this->taxReducedRateSecond;
	}

	/**
	 * @return string
	 */
	public function getCurrency()
	{
		return $this->currency;
	}

	/**
	 * @param float $sum
	 */
	public function setSum($sum)
	{
		$this->sum = $sum;
	}

	/**
	 * @param float $taxBase
	 */
	public function setTaxBase($taxBase)
	{
		$this->taxBase = $taxBase;
	}

	/**
	 * @param float $tax
	 */
	public function setTax($tax)
	{
		$this->tax = $tax;
	}

	/**
	 * @param float $taxBaseReducedRateFirst
	 */
	public function setTaxBaseReducedRateFirst($taxBaseReducedRateFirst)
	{
		$this->taxBaseReducedRateFirst = $taxBaseReducedRateFirst;
	}

	/**
	 * @param float $taxReducedRateFirst
	 */
	public function setTaxReducedRateFirst($taxReducedRateFirst)
	{
		$this->taxReducedRateFirst = $taxReducedRateFirst;
	}

	/**
	 * @param float $taxBaseReducedRateSecond
	 */
	public function setTaxBaseReducedRateSecond($taxBaseReducedRateSecond)
	{
		$this->taxBaseReducedRateSecond = $taxBaseReducedRateSecond;
	}

	/**
	 * @param float $taxReducedRateSecond
	 */
	public function setTaxReducedRateSecond($taxReducedRateSecond)
	{
		$this->taxReducedRateSecond = $taxReducedRateSecond;
	}

	/**
	 * @param string $currency
	 */
	public function setCurrency($currency)
	{
		$this->currency = $currency;
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
		$data['celk_trzba'] = $this->getSum();
		$data['mena'] = $this->getCurrency();

		if ($this->getTaxBase() && $this->getTax()) {
			$data['zakl_dan1'] = $this->getTaxBase();
			$data['dan1'] = $this->getTax();
		}

		if ($this->getTaxBaseReducedRateFirst() && $this->getTaxReducedRateFirst()) {
			$data['zakl_dan2'] = $this->getTaxBaseReducedRateFirst();
			$data['dan2'] = $this->getTaxReducedRateFirst();
		}
		
		if ($this->getTaxBaseReducedRateSecond() && $this->getTaxReducedRateSecond()) {
			$data['zakl_dan3'] = $this->getTaxBaseReducedRateSecond();
			$data['dan3'] = $this->getTaxReducedRateSecond();
		}

		return $data;
	}

}
