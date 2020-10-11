<?php

declare(strict_types=1);

namespace Contributte\GopayInline\Api\Objects;


use Contributte\GopayInline\Utils\Money;

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

	/** @var float|null */
	public $taxBaseReducedRateFirst;

	/** @var float|null */
	public $taxReducedRateFirst;

	/** @var float|null */
	public $taxBaseReducedRateSecond;

	/** @var float|null */
	public $taxReducedRateSecond;

	/** @var string */
	public $currency;

	/** @var float|null */
	public $subsequentDrawing;

	/** @var float|null */
	public $subsequentlyDrawn;


	/**
	 * @return float
	 */
	public function getSum()
	{
		return $this->sum;
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
	 * @return float
	 */
	public function getSumInCents()
	{
		return Money::toCents($this->getSum());
	}


	/**
	 * @return float|null
	 */
	public function getTaxBase()
	{
		return $this->taxBase;
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
	 * @return float
	 */
	public function getTaxBaseInCents()
	{
		return Money::toCents($this->getTaxBase());
	}


	/**
	 * @return float|null
	 */
	public function getTax()
	{
		return $this->tax;
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
	 * @return float
	 */
	public function getTaxInCents()
	{
		return Money::toCents($this->getTax());
	}


	/**
	 * @return float|null
	 */
	public function getTaxBaseReducedRateFirst()
	{
		return $this->taxBaseReducedRateFirst;
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
	 * @return float
	 */
	public function getTaxBaseReducedRateFirstInCents()
	{
		return Money::toCents($this->getTaxBaseReducedRateFirst());
	}


	/**
	 * @return float|null
	 */
	public function getTaxReducedRateFirst()
	{
		return $this->taxReducedRateFirst;
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
	 * @return float
	 */
	public function getTaxReducedRateFirstInCents()
	{
		return Money::toCents($this->getTaxReducedRateFirst());
	}


	/**
	 * @return float|null
	 */
	public function getTaxBaseReducedRateSecond()
	{
		return $this->taxBaseReducedRateSecond;
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
	 * @return float
	 */
	public function getTaxBaseReducedRateSecondInCents()
	{
		return Money::toCents($this->getTaxBaseReducedRateSecond());
	}


	/**
	 * @return float|null
	 */
	public function getTaxReducedRateSecond()
	{
		return $this->taxReducedRateSecond;
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
	 * @param string $currency
	 * @return void
	 */
	public function setCurrency($currency)
	{
		$this->currency = $currency;
	}


	/**
	 * @return float|null
	 */
	public function getTaxBaseNoVat()
	{
		return $this->taxBaseNoVat;
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
	 * @return float|null
	 */
	public function getSubsequentDrawing()
	{
		return $this->subsequentDrawing;
	}


	/**
	 * @return float|null
	 */
	public function getSubsequentlyDrawn()
	{
		return $this->subsequentlyDrawn;
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

		if ($this->getTaxBaseNoVat() !== null) {
			$data['zakl_nepodl_dph'] = $this->getTaxBaseNoVat();
		}

		if ($this->getTaxBase() !== null && $this->getTax() !== null) {
			$data['zakl_dan1'] = $this->getTaxBaseInCents();
			$data['dan1'] = $this->getTaxInCents();
		}

		if ($this->getTaxBaseReducedRateFirst() !== null && $this->getTaxReducedRateFirst() !== null) {
			$data['zakl_dan2'] = $this->getTaxBaseReducedRateFirstInCents();
			$data['dan2'] = $this->getTaxReducedRateFirstInCents();
		}

		if ($this->getTaxBaseReducedRateSecond() !== null && $this->getTaxReducedRateSecond() !== null) {
			$data['zakl_dan3'] = $this->getTaxBaseReducedRateSecondInCents();
			$data['dan3'] = $this->getTaxReducedRateSecondInCents();
		}

		if ($this->getSubsequentDrawing() !== null) {
			$data['urceno_cerp_zuct'] = $this->getSubsequentDrawing();
		}

		if ($this->getSubsequentlyDrawn() !== null) {
			$data['cerp_zuct'] = $this->getSubsequentlyDrawn();
		}

		return $data;
	}

}
