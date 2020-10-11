<?php

declare(strict_types=1);

namespace Contributte\GopayInline\Api\Entity;


use Contributte\GopayInline\Api\Objects\Eet;
use Contributte\GopayInline\Api\Objects\Item;
use Contributte\GopayInline\Api\Objects\Parameter;
use Contributte\GopayInline\Api\Objects\Payer;
use Contributte\GopayInline\Api\Objects\Target;
use Contributte\GopayInline\Utils\Money;

class Payment extends AbstractEntity
{

	/** @var Payer|null */
	protected $payer;

	/** @var Target */
	protected $target;

	/** @var float */
	protected $amount;

	/** @var string */
	protected $currency;

	/** @var string */
	protected $orderNumber;

	/** @var string */
	protected $orderDescription;

	/** @var Item[] */
	protected $items = [];

	/** @var string */
	protected $returnUrl;

	/** @var string */
	protected $notifyUrl;

	/** @var Parameter[] */
	protected $parameters = [];

	/** @var string|null */
	protected $lang;

	/** @var Eet|null */
	protected $eet;

	/** @var bool */
	protected $preauthorization = false;


	public function getPayer(): ?Payer
	{
		return $this->payer;
	}


	public function setPayer(Payer $payer): void
	{
		$this->payer = $payer;
	}


	public function getTarget(): ?Target
	{
		return $this->target;
	}


	public function setTarget(Target $target): void
	{
		$this->target = $target;
	}


	public function getAmount(): float
	{
		return $this->amount;
	}


	public function setAmount(float $amount): void
	{
		$this->amount = $amount;
	}


	public function getAmountInCents(): float
	{
		return Money::toCents($this->getAmount());
	}


	public function getCurrency(): string
	{
		return $this->currency;
	}


	public function setCurrency(string $currency): void
	{
		$this->currency = $currency;
	}


	public function getOrderNumber(): string
	{
		return $this->orderNumber;
	}


	public function setOrderNumber(string $orderNumber): void
	{
		$this->orderNumber = $orderNumber;
	}


	public function getOrderDescription(): string
	{
		return $this->orderDescription;
	}


	public function setOrderDescription(string $description): void
	{
		$this->orderDescription = $description;
	}


	/**
	 * @return Item[]
	 */
	public function getItems(): array
	{
		return $this->items;
	}


	/**
	 * @param Item[] $items
	 */
	public function setItems(array $items): void
	{
		$this->items = $items;
	}


	public function addItem(Item $item): void
	{
		$this->items[] = $item;
	}


	public function getReturnUrl(): string
	{
		return $this->returnUrl;
	}


	public function setReturnUrl(string $url): void
	{
		$this->returnUrl = $url;
	}


	public function getNotifyUrl(): string
	{
		return $this->notifyUrl;
	}


	public function setNotifyUrl(string $url): void
	{
		$this->notifyUrl = $url;
	}


	/**
	 * @return Parameter[]
	 */
	public function getParameters(): array
	{
		return $this->parameters;
	}


	/**
	 * @param Parameter[] $parameters
	 */
	public function setParameters(array $parameters): void
	{
		$this->parameters = $parameters;
	}


	public function addParameter(Parameter $parameter): void
	{
		$this->parameters[] = $parameter;
	}


	public function getLang(): ?string
	{
		return $this->lang;
	}


	public function setLang(string $lang): void
	{
		$this->lang = $lang;
	}


	public function getEet(): ?Eet
	{
		return $this->eet;
	}


	public function setEet(Eet $eet): void
	{
		$this->eet = $eet;
	}


	public function isPreauthorization(): bool
	{
		return $this->preauthorization;
	}


	public function setPreauthorization(bool $preauth): void
	{
		$this->preauthorization = $preauth;
	}


	/**
	 * @return mixed[]
	 */
	public function toArray(): array
	{
		$return = [];
		$return['target'] = $this->target->toArray();
		$return['amount'] = $this->getAmountInCents();
		$return['currency'] = $this->getCurrency();
		$return['order_number'] = $this->getOrderNumber();
		$return['order_description'] = $this->getOrderDescription();
		$return['items'] = $this->formatItems($this->getItems());
		$return['callback'] = [];
		$return['callback']['return_url'] = $this->getReturnUrl();
		$return['callback']['notification_url'] = $this->getNotifyUrl();

		if (($payer = $this->getPayer()) !== null) {
			$return['payer'] = $payer->toArray();
		}
		if (\count($parameters = $this->getParameters()) > 0) {
			$return['additional_params'] = $this->formatParameters($parameters);
		}
		if (($lang = $this->getLang()) !== null) {
			$return['lang'] = $lang;
		}
		if (($eet = $this->getEet()) !== null) {
			$return['eet'] = $eet->toArray();
		}
		if (($preAuth = $this->isPreauthorization())) {
			$return['preauthorization'] = $preAuth;
		}

		return $return;
	}


	/**
	 * @param mixed|Item[] $items
	 * @return mixed[]|Item[]
	 */
	protected function formatItems($items): array
	{
		if (\is_array($items) === false) {
			return [];
		}

		return array_map(static function (Item $item): array {
			return $item->toArray();
		}, $items);
	}


	/**
	 * @param mixed|Parameter[] $parameters
	 * @return mixed[]|Parameter[]
	 */
	protected function formatParameters($parameters): array
	{
		if (is_array($parameters) === false) {
			return [];
		}

		return array_map(static function (Parameter $param): array {
			return $param->toArray();
		}, $parameters);
	}
}
