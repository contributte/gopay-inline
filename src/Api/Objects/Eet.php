<?php declare(strict_types = 1);

namespace Contributte\GopayInline\Api\Objects;

use Money\Currency;
use Money\Money;

class Eet extends AbstractObject
{

	/** @var Money */
	public $sum;

	/** @var Money|null */
	public $taxBase;

	/** @var Money|null */
	public $taxBaseNoVat;

	/** @var Money|null */
	public $tax;

	/** @var Money|null */
	public $taxBaseReducedRateFirst;

	/** @var Money|null */
	public $taxReducedRateFirst;

	/** @var Money|null */
	public $taxBaseReducedRateSecond;

	/** @var Money|null */
	public $taxReducedRateSecond;

	/** @var Money|null */
	public $subsequentDrawing;

	/** @var Money|null */
	public $subsequentlyDrawn;

	/** @var string */
	public $currency;

	public function getSum(): Money
	{
		return $this->sum;
	}

	public function getSumInCents(): string
	{
		return $this->getSum()->getAmount();
	}

	public function getTaxBase(): ?Money
	{
		return $this->taxBase;
	}

	public function getTaxBaseInCents(): ?string
	{
		return $this->taxBase ? $this->taxBase->getAmount() : null;
	}

	public function getTax(): ?Money
	{
		return $this->tax;
	}

	public function getTaxInCents(): ?string
	{
		return $this->tax ? $this->tax->getAmount() : null;
	}

	public function getTaxBaseReducedRateFirst(): ?Money
	{
		return $this->taxBaseReducedRateFirst;
	}

	public function getTaxBaseReducedRateFirstInCents(): ?string
	{
		return $this->taxBaseReducedRateFirst ? $this->taxBaseReducedRateFirst->getAmount() : null;
	}

	public function getTaxReducedRateFirst(): ?Money
	{
		return $this->taxReducedRateFirst;
	}

	public function getTaxReducedRateFirstInCents(): ?string
	{
		return $this->taxReducedRateFirst ? $this->taxReducedRateFirst->getAmount() : null;
	}

	public function getTaxBaseReducedRateSecond(): ?Money
	{
		return $this->taxBaseReducedRateSecond;
	}

	public function getTaxBaseReducedRateSecondInCents(): ?string
	{
		return $this->taxBaseReducedRateSecond ? $this->taxBaseReducedRateSecond->getAmount() : null;
	}

	public function getTaxReducedRateSecond(): ?Money
	{
		return $this->taxReducedRateSecond;
	}

	public function getTaxReducedRateSecondInCents(): ?string
	{
		return $this->taxReducedRateSecond ? $this->taxReducedRateSecond->getAmount() : null;
	}

	public function getCurrency(): string
	{
		return $this->sum->getCurrency()->getCode();
	}

	public function getTaxBaseNoVat(): ?Money
	{
		return $this->taxBaseNoVat;
	}

	public function getSubsequentDrawing(): ?Money
	{
		return $this->subsequentDrawing;
	}

	public function getSubsequentDrawingInCents(): ?string
	{
		return $this->subsequentDrawing ? $this->subsequentDrawing->getAmount() : null;
	}

	public function getSubsequentlyDrawn(): ?Money
	{
		return $this->subsequentlyDrawn;
	}

	public function getSubsequentlyDrawnInCents(): ?Money
	{
		return $this->subsequentlyDrawn ? $this->subsequentlyDrawn->getAmount() : null;
	}

	public function setSum(Money $sum): void
	{
		$this->sum = $sum;
	}

	public function setTaxBase(Money $taxBase): void
	{
		$this->taxBase = $taxBase;
	}

	public function setTax(Money $tax): void
	{
		$this->tax = $tax;
	}

	public function setTaxBaseReducedRateFirst(Money $taxBaseReducedRateFirst): void
	{
		$this->taxBaseReducedRateFirst = $taxBaseReducedRateFirst;
	}

	public function setTaxReducedRateFirst(Money $taxReducedRateFirst): void
	{
		$this->taxReducedRateFirst = $taxReducedRateFirst;
	}

	public function setTaxBaseReducedRateSecond(Money $taxBaseReducedRateSecond): void
	{
		$this->taxBaseReducedRateSecond = $taxBaseReducedRateSecond;
	}

	public function setTaxReducedRateSecond(Money $taxReducedRateSecond): void
	{
		$this->taxReducedRateSecond = $taxReducedRateSecond;
	}

	public function setTaxBaseNoVat(Money $taxBaseNoVat): void
	{
		$this->taxBaseNoVat = $taxBaseNoVat;
	}

	public function getTotal(): Money
	{
		$total = new Money(0, new Currency($this->getCurrency()));

		if ($this->tax) {
			$total = $total->add($this->tax);
		}

		if ($this->taxBaseNoVat) {
			$total = $total->add($this->taxBaseNoVat);
		}

		if ($this->taxBase) {
			$total = $total->add($this->taxBase);
		}

		if ($this->taxBaseReducedRateFirst) {
			$total = $total->add($this->taxBaseReducedRateFirst);
		}

		if ($this->taxReducedRateFirst) {
			$total = $total->add($this->taxReducedRateFirst);
		}

		if ($this->taxBaseReducedRateSecond) {
			$total = $total->add($this->taxBaseReducedRateSecond);
		}

		if ($this->taxReducedRateSecond) {
			$total = $total->add($this->taxReducedRateSecond);
		}

		if ($this->subsequentDrawing) {
			$total = $total->add($this->subsequentDrawing);
		}

		if ($this->subsequentlyDrawn) {
			$total = $total->add($this->subsequentlyDrawn);
		}

		return $total;
	}

	/**
	 * @return mixed[]
	 */
	public function toArray(): array
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

		if ($this->getSubsequentDrawing() !== NULL) {
			$data['urceno_cerp_zuct'] = $this->getSubsequentDrawingInCents();
		}

		if ($this->getSubsequentlyDrawn() !== NULL) {
			$data['cerp_zuct'] = $this->getSubsequentlyDrawnInCents();
		}

		return $data;
	}

}
