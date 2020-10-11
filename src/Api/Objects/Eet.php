<?php

declare(strict_types=1);

namespace Contributte\GopayInline\Api\Objects;


use Contributte\GopayInline\Utils\Money;

final class Eet extends AbstractObject
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


	public function getSum(): float
	{
		return $this->sum;
	}


	public function setSum(float $sum): void
	{
		$this->sum = $sum;
	}


	public function getSumInCents(): float
	{
		return Money::toCents($this->getSum());
	}


	public function getTaxBase(): ?float
	{
		return $this->taxBase;
	}


	public function setTaxBase(float $taxBase): void
	{
		$this->taxBase = $taxBase;
	}


	public function getTaxBaseInCents(): float
	{
		return Money::toCents($this->getTaxBase());
	}


	public function getTax(): ?float
	{
		return $this->tax;
	}


	public function setTax(float $tax): void
	{
		$this->tax = $tax;
	}


	public function getTaxInCents(): float
	{
		return Money::toCents($this->getTax());
	}


	public function getTaxBaseReducedRateFirst(): ?float
	{
		return $this->taxBaseReducedRateFirst;
	}


	public function setTaxBaseReducedRateFirst(float $taxBaseReducedRateFirst): void
	{
		$this->taxBaseReducedRateFirst = $taxBaseReducedRateFirst;
	}


	public function getTaxBaseReducedRateFirstInCents(): float
	{
		return Money::toCents($this->getTaxBaseReducedRateFirst());
	}


	public function getTaxReducedRateFirst(): ?float
	{
		return $this->taxReducedRateFirst;
	}


	public function setTaxReducedRateFirst(float $taxReducedRateFirst): void
	{
		$this->taxReducedRateFirst = $taxReducedRateFirst;
	}


	public function getTaxReducedRateFirstInCents(): float
	{
		return Money::toCents($this->getTaxReducedRateFirst());
	}


	public function getTaxBaseReducedRateSecond(): ?float
	{
		return $this->taxBaseReducedRateSecond;
	}


	public function setTaxBaseReducedRateSecond(float $taxBaseReducedRateSecond): void
	{
		$this->taxBaseReducedRateSecond = $taxBaseReducedRateSecond;
	}


	public function getTaxBaseReducedRateSecondInCents(): float
	{
		return Money::toCents($this->getTaxBaseReducedRateSecond());
	}


	public function getTaxReducedRateSecond(): ?float
	{
		return $this->taxReducedRateSecond;
	}


	public function setTaxReducedRateSecond(float $taxReducedRateSecond): void
	{
		$this->taxReducedRateSecond = $taxReducedRateSecond;
	}


	public function getTaxReducedRateSecondInCents(): float
	{
		return Money::toCents($this->getTaxReducedRateSecond());
	}


	public function getCurrency(): string
	{
		return $this->currency;
	}


	public function setCurrency(string $currency): void
	{
		$this->currency = $currency;
	}


	public function getTaxBaseNoVat(): ?float
	{
		return $this->taxBaseNoVat;
	}


	public function setTaxBaseNoVat(float $taxBaseNoVat): void
	{
		$this->taxBaseNoVat = $taxBaseNoVat;
	}


	public function getSubsequentDrawing(): ?float
	{
		return $this->subsequentDrawing;
	}


	public function getSubsequentlyDrawn(): ?float
	{
		return $this->subsequentlyDrawn;
	}


	/**
	 * @return mixed[]
	 */
	public function toArray(): array
	{
		$return = [];
		$return['celk_trzba'] = $this->getSumInCents();
		$return['mena'] = $this->getCurrency();

		if ($this->getTaxBaseNoVat() !== null) {
			$return['zakl_nepodl_dph'] = $this->getTaxBaseNoVat();
		}
		if ($this->getTaxBase() !== null && $this->getTax() !== null) {
			$return['zakl_dan1'] = $this->getTaxBaseInCents();
			$return['dan1'] = $this->getTaxInCents();
		}
		if ($this->getTaxBaseReducedRateFirst() !== null && $this->getTaxReducedRateFirst() !== null) {
			$return['zakl_dan2'] = $this->getTaxBaseReducedRateFirstInCents();
			$return['dan2'] = $this->getTaxReducedRateFirstInCents();
		}
		if ($this->getTaxBaseReducedRateSecond() !== null && $this->getTaxReducedRateSecond() !== null) {
			$return['zakl_dan3'] = $this->getTaxBaseReducedRateSecondInCents();
			$return['dan3'] = $this->getTaxReducedRateSecondInCents();
		}
		if ($this->getSubsequentDrawing() !== null) {
			$return['urceno_cerp_zuct'] = $this->getSubsequentDrawing();
		}
		if ($this->getSubsequentlyDrawn() !== null) {
			$return['cerp_zuct'] = $this->getSubsequentlyDrawn();
		}

		return $return;
	}
}
