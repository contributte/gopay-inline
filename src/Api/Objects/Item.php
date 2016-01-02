<?php

namespace Markette\GopayInline\Api\Objects;

class Item extends AbstractObject
{
    /** @var string */
    public $name;

    /** @var float */
    public $amount;

    /** @var int */
    public $count = 1;

    /**
     * @return float
     */
    public function getAmountInCents()
    {
        return round($this->amount * 100);
    }

    /**
     * ABSTRACT ****************************************************************
     */

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'name' => $this->name,
            'amount' => $this->getAmountInCents(),
            'count' => $this->count
        ];
    }
}
