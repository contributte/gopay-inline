<?php

declare(strict_types=1);

namespace Contributte\GopayInline\Api\Objects;


final class Payer extends AbstractObject
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
	 * @return mixed[]
	 */
	public function toArray(): array
	{
		$return = [];
		if ($this->allowedPaymentInstruments !== null) {
			$return['allowed_payment_instruments'] = $this->allowedPaymentInstruments;
		}
		if ($this->defaultPaymentInstrument !== null) {
			$return['default_payment_instrument'] = $this->defaultPaymentInstrument;
		}
		if ($this->defaultSwift !== null) {
			$return['default_swift'] = $this->defaultSwift;
		}
		if ($this->allowedSwifts !== null) {
			$return['allowed_swifts'] = $this->allowedSwifts;
		}
		if ($this->contact !== null) {
			$return['contact'] = $this->contact->toArray();
		}

		return $return;
	}
}
