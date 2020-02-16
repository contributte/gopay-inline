<?php declare(strict_types = 1);

namespace Contributte\GopayInline\Api\Objects;

use Money\Money;

class Item extends AbstractObject
{

	/** @var string|null */
	public $name;

	/** @var Money */
	public $amount;

	/** @var int */
	public $count = 1;

	/** @var string|null */
	public $type;

	/** @var int|null */
	public $vatRate;

	public function getName(): ?string
	{
		return $this->name;
	}

	public function getAmount(): Money
	{
		return $this->amount;
	}

	public function getCount(): int
	{
		return $this->count;
	}

	public function getAmountInCents(): string
	{
		return $this->amount->getAmount();
	}

	public function getType(): ?string
	{
		return $this->type;
	}

	public function getVatRate(): ?int
	{
		return $this->vatRate;
	}

	public function setName(string $name): void
	{
		$this->name = $name;
	}

	public function setAmount(Money $amount): void
	{
		$this->amount = $amount;
	}

	public function setCount(int $count): void
	{
		$this->count = $count;
	}

	public function setType(string $type): void
	{
		$this->type = $type;
	}

	public function setVatRate(int $vatRate): void
	{
		$this->vatRate = $vatRate;
	}

	/**
	 * ABSTRACT ****************************************************************
	 */

	/**
	 * @return mixed[]
	 */
	public function toArray(): array
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
