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
	 * @param Payment $payment Use in case you need to refund payment with EET
	 * @return Response
	 */
	public function refundPayment($id, $amount, Payment $payment = NULL)
	{
		// without EET
		if (is_null($payment)) {
			return $this->makeRequest('POST', 'payments/payment/' . $id . '/refund', ['amount' => round($amount * 100)], Http::CONTENT_FORM);
		}

		$this->preConfigure($payment);

		// with EET
		$data = $payment->toArray();
		$usedata = array_merge(['amount' => round($amount * 100)], ['items' => $data['items']], ['eet' => $data['eet']]);

		return $this->makeRequest('POST', 'payments/payment/' . $id . '/refund', $usedata, Http::CONTENT_JSON);
	}

	/**
	 * @param string $currency
	 * @return Response
	 */
	public function getPaymentInstruments($currency)
	{
		// Make request
		return $this->makeRequest('GET', 'eshops/eshop/' . $this->client->getGoId() . '/payment-instruments/' . $currency, NULL, NULL);
	}

}
