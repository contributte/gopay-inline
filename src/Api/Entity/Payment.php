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

	/** @var Payer|NULL */
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

	/** @var string|NULL */
	protected $lang;

	/** @var Eet|NULL */
	protected $eet;

	/** @var bool */
	protected $preauthorization = false;


	/**
	 * @return Payer|NULL
	 */
	public function getPayer()
	{
		return $this->payer;
	}


	/**
	 * @param Payer $payer
	 * @return void
	 */
	public function setPayer(Payer $payer)
	{
		$this->payer = $payer;
	}


	/**
	 * @return Target|null
	 */
	public function getTarget()
	{
		return $this->target;
	}


	/**
	 * @param Target $target
	 * @return void
	 */
	public function setTarget(Target $target)
	{
		$this->target = $target;
	}


	/**
	 * @return float
	 */
	public function getAmount()
	{
		return $this->amount;
	}


	/**
	 * @param float $amount
	 * @return void
	 */
	public function setAmount($amount)
	{
		$this->amount = $amount;
	}


	/**
	 * @return float
	 */
	public function getAmountInCents()
	{
		return Money::toCents($this->getAmount());
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
	 * @return string
	 */
	public function getOrderNumber()
	{
		return $this->orderNumber;
	}


	/**
	 * @param string $orderNumber
	 * @return void
	 */
	public function setOrderNumber($orderNumber)
	{
		$this->orderNumber = $orderNumber;
	}


	/**
	 * @return string
	 */
	public function getOrderDescription()
	{
		return $this->orderDescription;
	}


	/**
	 * @param string $description
	 * @return void
	 */
	public function setOrderDescription($description)
	{
		$this->orderDescription = $description;
	}


	/**
	 * @return Item[]
	 */
	public function getItems()
	{
		return $this->items;
	}


	/**
	 * @param Item[] $items
	 * @return void
	 */
	public function setItems(array $items)
	{
		$this->items = $items;
	}


	/**
	 * @param Item $item
	 * @return void
	 */
	public function addItem(Item $item)
	{
		$this->items[] = $item;
	}


	/**
	 * @return string
	 */
	public function getReturnUrl()
	{
		return $this->returnUrl;
	}


	/**
	 * @param string $url
	 * @return void
	 */
	public function setReturnUrl($url)
	{
		$this->returnUrl = $url;
	}


	/**
	 * @return string
	 */
	public function getNotifyUrl()
	{
		return $this->notifyUrl;
	}


	/**
	 * @param string $url
	 * @return void
	 */
	public function setNotifyUrl($url)
	{
		$this->notifyUrl = $url;
	}


	/**
	 * @return Parameter[]
	 */
	public function getParameters()
	{
		return $this->parameters;
	}


	/**
	 * @param Parameter[] $parameters
	 * @return void
	 */
	public function setParameters(array $parameters)
	{
		$this->parameters = $parameters;
	}


	/**
	 * @param Parameter $parameter
	 * @return void
	 */
	public function addParameter(Parameter $parameter)
	{
		$this->parameters[] = $parameter;
	}


	/**
	 * @return string|NULL
	 */
	public function getLang()
	{
		return $this->lang;
	}


	/**
	 * @param string $lang
	 * @return void
	 */
	public function setLang($lang)
	{
		$this->lang = $lang;
	}


	/**
	 * @return Eet|NULL
	 */
	public function getEet()
	{
		return $this->eet;
	}


	/**
	 * @param Eet $eet
	 * @return void
	 */
	public function setEet(Eet $eet)
	{
		$this->eet = $eet;
	}


	/**
	 * @return boolean
	 */
	public function isPreauthorization()
	{
		return $this->preauthorization;
	}


	/**
	 * @param boolean $preauth
	 * @return void
	 */
	public function setPreauthorization($preauth)
	{
		$this->preauthorization = boolval($preauth);
	}

	/**
	 * HELPERS *****************************************************************
	 */


	/**
	 * @return array
	 */
	public function toArray()
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


	/**
	 * @param mixed|Item[] $items
	 * @return array
	 */
	protected function formatItems($items)
	{
		if (!is_array($items))
			return [];

		// Format items
		return array_map(function (Item $item) {
			return $item->toArray();
		}, $items);
	}

	/**
	 * ABSTRACT ****************************************************************
	 */


	/**
	 * @param mixed|Parameter[] $parameters
	 * @return array
	 */
	protected function formatParameters($parameters)
	{
		if (!is_array($parameters))
			return [];

		// Format items
		return array_map(function (Parameter $param) {
			return $param->toArray();
		}, $parameters);
	}

}
