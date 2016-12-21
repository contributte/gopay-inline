<?php

namespace Markette\GopayInline\Api\Objects;

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
	 * @return array
	 */
	public function toArray()
	{
		$data = [];

		if ($this->allowedPaymentInstruments) {
			$data['allowed_payment_instruments'] = $this->allowedPaymentInstruments;
		}

		if ($this->defaultPaymentInstrument) {
			$data['default_payment_instrument'] = $this->defaultPaymentInstrument;
		}

		if ($this->defaultSwift) {
			$data['default_swift'] = $this->defaultSwift;
		}

		if ($this->allowedSwifts) {
			$data['allowed_swifts'] = $this->allowedSwifts;
		}

		if ($this->contact) {
			$data['contact'] = $this->contact->toArray();
		}

		return $data;
	}

}
