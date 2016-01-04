<?php

namespace Markette\GopayInline\Service;

use Markette\GopayInline\Api\Entity\Payment;
use Markette\GopayInline\Api\Entity\PreauthorizedPayment;
use Markette\GopayInline\Api\Entity\RecurrentPayment;
use Markette\GopayInline\Http\Http;
use Markette\GopayInline\Http\Response;

class PaymentsService extends AbstractPaymentService
{

    /**
     * @param int|float $id
     * @return Response
     */
    public function verify($id)
    {
        // Make request
        return $this->makeRequest('GET', 'payments/payment/' . $id, NULL, Http::CONTENT_FORM);
    }

    /**
     * @param Payment $payment
     * @return Response
     */
    public function createPayment(Payment $payment)
    {
        // Pre-configure payment
        $this->preConfigure($payment);

        // Export payment to array
        $data = $payment->toArray();

        // Make request
        return $this->makeRequest('POST', 'payments/payment', $data);
    }

    /**
     * @param RecurrentPayment $payment
     * @return Response
     */
    public function createRecurrentPayment(RecurrentPayment $payment)
    {
        // Pre-configure payment
        $this->preConfigure($payment);

        // Export payment to array
        $data = $payment->toArray();

        // Make request
        return $this->makeRequest('POST', 'payments/payment', $data);
    }

    /**
     * @param PreauthorizedPayment $payment
     * @return Response
     */
    public function createPreauthorizedPayment(PreauthorizedPayment $payment)
    {
        // Pre-configure payment
        $this->preConfigure($payment);

        // Export payment to array
        $data = $payment->toArray();

        // Make request
        return $this->makeRequest('POST', 'payments/payment', $data);
    }

    /**
     * @param int|float $id
     * @param float $amount
     * @return Response
     */
    public function refundPayment($id, $amount)
    {
        // Make request
        return $this->makeRequest('POST', 'payments/payment/' . $id . '/refund', ['amount' => round($amount * 100)], Http::CONTENT_FORM);
    }

}
