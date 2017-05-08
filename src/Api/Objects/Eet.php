<?php

namespace Markette\GopayInline\Api\Objects;

use Markette\GopayInline\Utils\Money;

class Eet extends AbstractObject
{

	/** @var float */
	public $sum;

	/** @var float */
	public $taxBase;

	/** @var float */
	public $taxBaseNoVat;

	/** @var float */
	public $tax;

	/** @var float */
	public $taxBaseReducedRateFirst;

	/** @var float */
	public $taxReducedRateFirst;

	/** @var float */
	public $taxBaseReducedRateSecond;

	/** @var float */
	public $taxReducedRateSecond;

	/** @var string */
	public $currency;

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
	public function getSumInCents()
	{
		return Money::toCents($this->getSum());
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
	public function getTaxBaseInCents()
	{
		return Money::toCents($this->getTaxBase());
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
	public function getTaxInCents()
	{
		return Money::toCents($this->getTax());
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
	public function getTaxBaseReducedRateFirstInCents()
	{
		return Money::toCents($this->getTaxBaseReducedRateFirst());
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
	public function getTaxReducedRateFirstInCents()
	{
		return Money::toCents($this->getTaxReducedRateFirst());
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
	public function getTaxBaseReducedRateSecondInCents()
	{
		return Money::toCents($this->getTaxBaseReducedRateSecond());
	}

	/**
	 * @return float
	 */
	public function getTaxReducedRateSecond()
	{
		return $this->taxReducedRateSecond;
	}

	/**
	 * @return float
	 */
	public function getTaxReducedRateSecondInCents()
	{
		return Money::toCents($this->getTaxReducedRateSecond());
	}

	/**
	 * @return string
	 */
	public function getCurrency()
	{
		return $this->currency;
	}

	/**
	 * @return float
	 */
	public function getTaxBaseNoVat()
	{
		return $this->taxBaseNoVat;
	}

	/**
	 * @param float $sum
	 * @return void
	 */
	public function setSum($sum)
	{
		$this->sum = $sum;
	}

	/**
	 * @param float $taxBase
	 * @return void
	 */
	public function setTaxBase($taxBase)
	{
		$this->taxBase = $taxBase;
	}

	/**
	 * @param float $tax
	 * @return void
	 */
	public function setTax($tax)
	{
		$this->tax = $tax;
	}

	/**
	 * @param float $taxBaseReducedRateFirst
	 * @return void
	 */
	public function setTaxBaseReducedRateFirst($taxBaseReducedRateFirst)
	{
		$this->taxBaseReducedRateFirst = $taxBaseReducedRateFirst;
	}

	/**
	 * @param float $taxReducedRateFirst
	 * @return void
	 */
	public function setTaxReducedRateFirst($taxReducedRateFirst)
	{
		$this->taxReducedRateFirst = $taxReducedRateFirst;
	}

	/**
	 * @param float $taxBaseReducedRateSecond
	 * @return void
	 */
	public function setTaxBaseReducedRateSecond($taxBaseReducedRateSecond)
	{
		$this->taxBaseReducedRateSecond = $taxBaseReducedRateSecond;
	}

	/**
	 * @param float $taxReducedRateSecond
	 * @return void
	 */
	public function setTaxReducedRateSecond($taxReducedRateSecond)
	{
		$this->taxReducedRateSecond = $taxReducedRateSecond;
	}

	/**
	 * @param string $currency
	 * @return void
	 */
	public function setCurrency($currency)
	{
		$this->currency = $currency;
	}

	/**
	 * @param float $taxBaseNoVat
	 * @return void
	 */
	public function setTaxBaseNoVat($taxBaseNoVat)
	{
		$this->taxBaseNoVat = $taxBaseNoVat;
	}

	/**
	 * HELPERS *****************************************************************
	 */

	/**
	 * ABSTRACT ****************************************************************
	 */

	/**
	 * @return array
	 */
	public function toArray()
	{
		$data = [];
		$data['celk_trzba'] = $this->getSumInCents();
		$data['mena'] = $this->getCurrency();

		if ($this->getTaxBaseNoVat()) {
			$data['zakl_nepodl_dph'] = $this->getTaxBaseNoVat();
		}

		if ($this->getTaxBase() && $this->getTax()) {
			$data['zakl_dan1'] = $this->getTaxBaseInCents();
			$data['dan1'] = $this->getTaxInCents();
		}

		if ($this->getTaxBaseReducedRateFirst() && $this->getTaxReducedRateFirst()) {
			$data['zakl_dan2'] = $this->getTaxBaseReducedRateFirstInCents();
			$data['dan2'] = $this->getTaxReducedRateFirstInCents();
		}

		if ($this->getTaxBaseReducedRateSecond() && $this->getTaxReducedRateSecond()) {
			$data['zakl_dan3'] = $this->getTaxBaseReducedRateSecondInCents();
			$data['dan3'] = $this->getTaxReducedRateSecondInCents();
		}

		return $data;
	}

}
