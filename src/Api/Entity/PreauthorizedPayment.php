<?php

namespace Markette\GopayInline\Api\Entity;

class PreauthorizedPayment extends Payment
{

    /** @var bool */
    protected $preauthorization;

    /**
     * @return boolean
     */
    public function isPreauthorization()
    {
        return $this->preauthorization;
    }

    /**
     * @param boolean $preauth
     */
    public function setPreauthorization($preauth)
    {
        $this->preauthorization = boolval($preauth);
    }

    /**
     * ABSTRACT ****************************************************************
     */

    /**
     * @return array
     */
    public function toArray()
    {
        $payment = parent::toArray();

        if (($preauth = $this->isPreauthorization())) {
            $payment['preauthorization'] = $preauth;
        }

        return $payment;
    }
}
