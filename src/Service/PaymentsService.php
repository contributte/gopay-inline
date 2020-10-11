<?php

declare(strict_types=1);

namespace Contributte\GopayInline\Service;


use Contributte\GopayInline\Api\Entity\Payment;
use Contributte\GopayInline\Api\Entity\RecurrentPayment;
use Contributte\GopayInline\Api\Entity\RecurringPayment;
use Contributte\GopayInline\Http\Http;
use Contributte\GopayInline\Http\Response;

class PaymentsService extends AbstractPaymentService
{

	/**
	 * @param int|float $id
	 * @return Response
	 */
	public function verify($id)
	{
		// Make request
		return $this->makeRequest('GET', 'payments/payment/' . $id, null, Http::CONTENT_FORM);
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
	 * @param int|float $id
	 * @param float $amount
	 * @param array $items Use in case you need to refund payment with EET
	 * @param array $eet Use in case you need to refund payment with EET
	 * @return Response
	 */
	public function refundPayment($id, $amount, $items = null, $eet = null)
	{
		// without EET
		if ($items === null || $eet === null) {
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
	 * @param int|float $id
	 * @param float $amount
	 * @return Response
	 */
	public function capturePayment($id, $amount = null)
	{
		$data = [];

		if ($amount !== null) {
			$data['amount'] = round($amount * 100);
		}

		return $this->makeRequest('POST', 'payments/payment/' . $id . '/capture', $data);
	}


	/**
	 * @param string $currency
	 * @return Response
	 */
	public function getPaymentInstruments($currency)
	{
		// Make request
		return $this->makeRequest('GET', 'eshops/eshop/' . $this->client->getGoId() . '/payment-instruments/' . $currency, null, null);
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
