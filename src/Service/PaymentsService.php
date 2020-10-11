<?php

declare(strict_types=1);

namespace Contributte\GopayInline\Service;


use Contributte\GopayInline\Api\Entity\Payment;
use Contributte\GopayInline\Api\Entity\RecurrentPayment;
use Contributte\GopayInline\Api\Entity\RecurringPayment;
use Contributte\GopayInline\Http\Http;
use Contributte\GopayInline\Http\Response;

final class PaymentsService extends AbstractPaymentService
{

	/**
	 * @param int|float $id
	 * @return Response
	 */
	public function verify($id): Response
	{
		// Make request
		return $this->makeRequest('GET', 'payments/payment/' . $id, null, Http::CONTENT_FORM);
	}


	/**
	 * @param Payment $payment
	 * @return Response
	 */
	public function createPayment(Payment $payment): Response
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
	public function createRecurrentPayment(RecurrentPayment $payment): Response
	{
		// Pre-configure payment
		$this->preConfigure($payment);

		// Export payment to array
		$data = $payment->toArray();

		// Make request
		return $this->makeRequest('POST', 'payments/payment', $data);
	}


	public function createRecurringPayment(string $recurrencePaymentId, RecurringPayment $payment): Response
	{
		// Export payment to array
		$data = $payment->toArray();

		// Make request
		return $this->makeRequest('POST', 'payments/payment/' . $recurrencePaymentId . '/create-recurrence', $data);
	}


	/**
	 * @param int|float $id
	 * @param mixed[] $items Use in case you need to refund payment with EET
	 * @param mixed[] $eet Use in case you need to refund payment with EET
	 * @return Response
	 */
	public function refundPayment($id, float $amount, ?array $items = null, ?array $eet = null): Response
	{
		if ($items === null || $eet === null) { // // without EET
			return $this->makeRequest('POST', 'payments/payment/' . $id . '/refund', ['amount' => round($amount * 100)], Http::CONTENT_FORM);
		}

		$data = array_merge( // // with EET
			['amount' => round($amount * 100)],
			['items' => $items],
			['eet' => $eet]
		);

		return $this->makeRequest('POST', 'payments/payment/' . $id . '/refund', $data, Http::CONTENT_JSON);
	}


	/**
	 * @param int|float $id
	 * @return Response
	 */
	public function capturePayment($id, float $amount = null): Response
	{
		$data = [];
		if ($amount !== null) {
			$data['amount'] = round($amount * 100);
		}

		return $this->makeRequest('POST', 'payments/payment/' . $id . '/capture', $data);
	}


	public function getPaymentInstruments(string $currency): Response
	{
		// Make request
		return $this->makeRequest('GET', 'eshops/eshop/' . $this->client->getGoId() . '/payment-instruments/' . $currency, null, null);
	}


	/**
	 * @param int|float $id ID of payment for which we need list of EET receipts
	 * @return Response
	 */
	public function getEetReceipts($id): Response
	{
		// Make request
		return $this->makeRequest('GET', 'payments/payment/' . $id . '/eet-receipts');
	}
}
