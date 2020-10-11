<?php

declare(strict_types=1);

namespace Contributte\GopayInline\Api\Objects;


use Contributte\GopayInline\Utils\Money;

final class Item extends AbstractObject
{

	/** @var string */
	public $name;

	/** @var float */
	public $amount;

	/** @var int */
	public $count = 1;

	/** @var string|null */
	public $type;

	/** @var int|null */
	public $vatRate;


	public function getName(): string
	{
		return $this->name;
	}


	public function setName(string $name): void
	{
		$this->name = $name;
	}


	public function getAmount(): float
	{
		return $this->amount;
	}


	public function setAmount(float $amount): void
	{
		$this->amount = $amount;
	}


	public function getCount(): int
	{
		return $this->count;
	}


	public function setCount(int $count): void
	{
		$this->count = $count;
	}


	public function getAmountInCents(): float
	{
		return Money::toCents($this->getAmount());
	}


	public function getType(): ?string
	{
		return $this->type;
	}


	public function setType(string $type): void
	{
		$this->type = $type;
	}


	public function getVatRate(): ?int
	{
		return $this->vatRate;
	}


	public function setVatRate(int $vatRate): void
	{
		$this->vatRate = $vatRate;
	}


	/**
	 * @return mixed[]
	 */
	public function toArray(): array
	{
		$data = [];
		$data['name'] = $this->getName();
		$data['amount'] = $this->getAmountInCents();
		$data['count'] = $this->getCount();

		// NOT REQUIRED
		if (($type = $this->getType()) !== null) {
			$data['type'] = $type;
		}
		if (($vatRate = $this->getVatRate()) !== null) {
			$data['vat_rate'] = $vatRate;
		}

		return $data;
	}
}
