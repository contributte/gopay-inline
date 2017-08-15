<?php

namespace Markette\GopayInline\Service;

use Markette\GopayInline\Api\Entity\Payment;
use Markette\GopayInline\Api\Entity\PreauthorizedPayment;
use Markette\GopayInline\Api\Entity\RecurrentPayment;
use Markette\GopayInline\Api\Entity\RecurringPayment;
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
	 * @param string $recurrencePaymentId
	 * @param RecurringPayment $payment
	 * @return Response
	 */
	public function createRecurringPayment($recurrencePaymentId, RecurringPayment $payment)
	{
		// Export payment to array
		$data = $payment->toArray();

		// Make request
		return $this->makeRequest('POST', 'payments/payment/' . $recurrencePaymentId . '/create-recurrence', $data);
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
	 * @param array $items Use in case you need to refund payment with EET
	 * @param array $eet Use in case you need to refund payment with EET
	 * @return Response
	 */
	public function refundPayment($id, $amount, $items = NULL, $eet = NULL)
	{
		// without EET
		if ($items === NULL || $eet === NULL) {
			return $this->makeRequest('POST', 'payments/payment/' . $id . '/refund', ['amount' => round($amount * 100)], Http::CONTENT_FORM);
		}

		// with EET
		$data = array_merge(
			['amount' => round($amount * 100)],
			['items' => $items],
			['eet' => $eet]
		);

		return $this->makeRequest('POST', 'payments/payment/' . $id . '/refund', $data, Http::CONTENT_JSON);
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

	/**
	 * @param int|float $id ID of payment for which we need list of EET receipts
	 * @return Response
	 */
	public function getEetReceipts($id)
	{
		// Make request
		return $this->makeRequest('GET', 'payments/payment/' . $id . '/eet-receipts');
	}

}
