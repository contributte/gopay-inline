<?php declare(strict_types = 1);

namespace Contributte\GopayInline\Service;

use Contributte\GopayInline\Api\Entity\Payment;
use Contributte\GopayInline\Api\Entity\RecurrentPayment;
use Contributte\GopayInline\Api\Entity\RecurringPayment;
use Contributte\GopayInline\Http\Http;
use Contributte\GopayInline\Http\Response;

class PaymentsService extends AbstractPaymentService
{

	public function verify(string $id): Response
	{
		// Make request
		return $this->makeRequest('GET', 'payments/payment/' . $id, null, Http::CONTENT_FORM);
	}

	public function createPayment(Payment $payment): Response
	{
		// Pre-configure payment
		$this->preConfigure($payment);

		// Export payment to array
		$data = $payment->toArray();

		// Make request
		return $this->makeRequest('POST', 'payments/payment', $data);
	}

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

	public function cancelRecurrentPayment(string $recurrencePaymentId): Response
	{
		return $this->makeRequest('POST', 'payments/payment/' . $recurrencePaymentId . '/void-recurrence');
	}

	/**
	 * @param int|float $id
	 * @param mixed[] $items Use in case you need to refund payment with EET
	 * @param mixed[] $eet Use in case you need to refund payment with EET
	 */
	public function refundPayment($id, float $amount, ?array $items = null, ?array $eet = null): Response
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
	 * @param int|float    $id
	 * @param float|null   $amount
	 * @param mixed[]|null $items
	 */
	public function capturePayment($id, ?float $amount = null, ?array $items = null): Response
	{
		if ($amount === null || $items === null) {
			return $this->makeRequest('POST', 'payments/payment/' . $id . '/capture');
		}

		$data = array_merge(
			['amount' => round($amount * 100)],
			['items' => $items]
		);

		return $this->makeRequest('POST', 'payments/payment/' . $id . '/capture', $data);
	}

	public function getPaymentInstruments(string $currency): Response
	{
		// Make request
		return $this->makeRequest('GET', 'eshops/eshop/' . $this->client->getGoId() . '/payment-instruments/' . $currency, null, null);
	}

	/**
	 * @param int|float $id ID of payment for which we need list of EET receipts
	 */
	public function getEetReceipts($id): Response
	{
		// Make request
		return $this->makeRequest('GET', 'payments/payment/' . $id . '/eet-receipts');
	}

}
