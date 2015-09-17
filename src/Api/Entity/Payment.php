<?php

namespace Markette\GopayInline\Api\Entity;

use Markette\GopayInline\Api\Objects\Item;
use Markette\GopayInline\Api\Objects\Parameter;
use Markette\GopayInline\Api\Objects\Payer;
use Markette\GopayInline\Api\Objects\Target;

class Payment extends AbstractEntity
{

    /** @var Payer */
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

    /** @var string */
    protected $lang;

    /**
     * @return Payer
     */
    public function getPayer()
    {
        return $this->payer;
    }

    /**
     * @param Payer $payer
     */
    public function setPayer(Payer $payer)
    {
        $this->payer = $payer;
    }

    /**
     * @return Target
     */
    public function getTarget()
    {
        return $this->target;
    }

    /**
     * @param Target $target
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
     * @return float
     */
    public function getAmountInCents()
    {
        return round($this->getAmount() * 100);
    }

    /**
     * @param float $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
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
     */
    public function setItems(array $items)
    {
        $this->items = $items;
    }

    /**
     * @param Item $item
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
     */
    public function setParameters(array $parameters)
    {
        $this->parameters = $parameters;
    }

    /**
     * @param Parameter $parameter
     */
    public function addParameter(Parameter $parameter)
    {
        $this->parameters[] = $parameter;
    }

    /**
     * @return string
     */
    public function getLang()
    {
        return $this->lang;
    }

    /**
     * @param string $lang
     */
    public function setLang($lang)
    {
        $this->lang = $lang;
    }

    /**
     * HELPERS *****************************************************************
     */

    /**
     * @param mixed|Item[] $items
     * @return array
     */
    protected function formatItems($items)
    {
        if (!$items) return [];

        // Format items
        return array_map(function (Item $item) {
            return $item->toArray();
        }, $items);
    }

    /**
     * @param mixed|Parameter[] $parameters
     * @return array
     */
    protected function formatParameters($parameters)
    {
        if (!$parameters) return [];

        // Format items
        return array_map(function (Parameter $param) {
            return $param->toArray();
        }, $parameters);
    }

    /**
     * ABSTRACT ****************************************************************
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

        if (($payer = $this->getPayer())) {
            $data['payer'] = $payer->toArray();
        }

        if (($parameters = $this->getParameters())) {
            $data['additional_params'] = $this->formatParameters($parameters);
        }

        if (($lang = $this->getLang())) {
            $data['lang'] = $lang;
        }

        return $data;
    }

}
