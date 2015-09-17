<?php

namespace Markette\GopayInline\Api\Entity;

use Markette\GopayInline\Api\Objects\Recurrence;

class RecurrentPayment extends Payment
{

    /** @var Recurrence */
    protected $recurrence;

    /**
     * @return Recurrence
     */
    public function getRecurrence()
    {
        return $this->recurrence;
    }

    /**
     * @param Recurrence $recurrence
     */
    public function setRecurrence(Recurrence $recurrence)
    {
        $this->recurrence = $recurrence;
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

        if (($recurrence = $this->getRecurrence())) {
            $payment['recurrence'] = $recurrence->toArray();
        }

        return $payment;
    }
}
