<?php declare(strict_types = 1);

namespace Contributte\GopayInline\Api\Objects;

class Payer extends AbstractObject
{

	/** @var string[] */
	public $allowedPaymentInstruments = [];

	/** @var string */
	public $defaultPaymentInstrument;

	/** @var string[] */
	public $allowedSwifts = [];

	/** @var string */
	public $defaultSwift;

	/** @var Contact */
	public $contact;

	/**
	 * ABSTRACT ****************************************************************
	 */

	/**
	 * @return mixed[]
	 */
	public function toArray(): array
	{
		$data = [];

		if ($this->allowedPaymentInstruments !== null) {
			$data['allowed_payment_instruments'] = $this->allowedPaymentInstruments;
		}

		if ($this->defaultPaymentInstrument !== null) {
			$data['default_payment_instrument'] = $this->defaultPaymentInstrument;
		}

		if ($this->defaultSwift !== null) {
			$data['default_swift'] = $this->defaultSwift;
		}

		if ($this->allowedSwifts !== null) {
			$data['allowed_swifts'] = $this->allowedSwifts;
		}

		if ($this->contact !== null) {
			$data['contact'] = $this->contact->toArray();
		}

		return $data;
	}

}
