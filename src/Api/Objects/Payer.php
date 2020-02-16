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

		if ($this->allowedPaymentInstruments !== NULL) {
			$data['allowed_payment_instruments'] = $this->allowedPaymentInstruments;
		}

		if ($this->defaultPaymentInstrument !== NULL) {
			$data['default_payment_instrument'] = $this->defaultPaymentInstrument;
		}

		if ($this->defaultSwift !== NULL) {
			$data['default_swift'] = $this->defaultSwift;
		}

		if ($this->allowedSwifts !== NULL) {
			$data['allowed_swifts'] = $this->allowedSwifts;
		}

		if ($this->contact !== NULL) {
			$data['contact'] = $this->contact->toArray();
		}

		return $data;
	}

}
