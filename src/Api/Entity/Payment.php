<?php declare(strict_types = 1);

namespace Contributte\GopayInline\Api\Entity;

use Contributte\GopayInline\Api\Objects\Eet;
use Contributte\GopayInline\Api\Objects\Item;
use Contributte\GopayInline\Api\Objects\Parameter;
use Contributte\GopayInline\Api\Objects\Payer;
use Contributte\GopayInline\Api\Objects\Target;
use Contributte\GopayInline\Exception\InvalidStateException;
use Money\Money;

class Payment extends AbstractEntity
{

	/** @var Payer|null */
	protected $payer;

	/** @var Target */
	protected $target;

	/** @var Money */
	protected $amount;

	/** @var string|null */
	protected $orderNumber;

	/** @var string|null */
	protected $orderDescription;

	/** @var Item[] */
	protected $items = [];

	/** @var string|null */
	protected $returnUrl;

	/** @var string|null */
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

	public function getTarget(): Target
	{
		return $this->target;
	}

	public function hasTarget(): bool
	{
		return $this->target !== null;
	}

	public function setTarget(Target $target): void
	{
		$this->target = $target;
	}

	public function getAmount(): Money
	{
		return $this->amount;
	}

	public function getAmountInCents(): string
	{
		return $this->amount->getAmount();
	}

	public function setAmount(Money $amount): void
	{
		$this->amount = $amount;
	}

	/**
	 * @return non-empty-string
	 */
	public function getCurrency(): string
	{
		/** @var string $code */
		$code = $this->amount->getCurrency()->getCode();

		if ($code === '') {
			throw new InvalidStateException('Currency code cannot be empty');
		}

		return $code;
	}

	public function getOrderNumber(): ?string
	{
		return $this->orderNumber;
	}

	public function setOrderNumber(string $orderNumber): void
	{
		$this->orderNumber = $orderNumber;
	}

	public function getOrderDescription(): ?string
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

	public function getReturnUrl(): ?string
	{
		return $this->returnUrl;
	}

	public function setReturnUrl(string $url): void
	{
		$this->returnUrl = $url;
	}

	public function getNotifyUrl(): ?string
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
		$this->preauthorization = boolval($preauth);
	}

	/**
	 * HELPERS *****************************************************************
	 */

	/**
	 * @param Item[] $items
	 * @return mixed[]
	 */
	protected function formatItems(array $items): array
	{
		// Format items
		return array_map(function (Item $item) {
			return $item->toArray();
		}, $items);
	}

	/**
	 * @param Parameter[] $parameters
	 * @return mixed[]
	 */
	protected function formatParameters(array $parameters): array
	{
		return array_map(function (Parameter $param) {
			return $param->toArray();
		}, $parameters);
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

		$data['target'] = $this->target->toArray();

		$data['amount'] = $this->getAmountInCents();
		$data['currency'] = $this->getCurrency();

		$data['order_number'] = $this->getOrderNumber();
		$data['order_description'] = $this->getOrderDescription();

		$data['items'] = $this->formatItems($this->getItems());

		$data['callback'] = [];
		$data['callback']['return_url'] = $this->getReturnUrl();
		$data['callback']['notification_url'] = $this->getNotifyUrl();

		// NOT REQUIRED ====================================

		$payer = $this->getPayer();
		if ($payer !== null) {
			$data['payer'] = $payer->toArray();
		}

		$parameters = $this->getParameters();
		if (count($parameters) > 0) {
			$data['additional_params'] = $this->formatParameters($parameters);
		}

		$lang = $this->getLang();
		if ($lang !== null) {
			$data['lang'] = $lang;
		}

		$eet = $this->getEet();
		if ($eet !== null) {
			$data['eet'] = $eet->toArray();
		}

		$preauth = $this->isPreauthorization();
		if ($preauth) {
			$data['preauthorization'] = $preauth;
		}

		return $data;
	}

}
