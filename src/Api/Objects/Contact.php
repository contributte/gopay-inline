<?php

declare(strict_types=1);

namespace Contributte\GopayInline\Api\Objects;


final class Contact extends AbstractObject
{

	/** @var string|null */
	public $firstname;

	/** @var string|null */
	public $lastname;

	/** @var string|null */
	public $email;

	/** @var string|null */
	public $phone;

	/** @var string|null */
	public $city;

	/** @var string|null */
	public $street;

	/** @var string|null */
	public $zip;

	/** @var string|null */
	public $country;


	/**
	 * @return mixed[]
	 */
	public function toArray(): array
	{
		$return = [];

		if ($this->firstname !== null) {
			$return['first_name'] = $this->firstname;
		}
		if ($this->lastname !== null) {
			$return['last_name'] = $this->lastname;
		}
		if ($this->email !== null) {
			$return['email'] = $this->email;
		}
		if ($this->phone !== null) {
			$return['phone_number'] = $this->phone;
		}
		if ($this->city !== null) {
			$return['city'] = $this->city;
		}
		if ($this->street !== null) {
			$return['street'] = $this->street;
		}
		if ($this->zip !== null) {
			$return['postal_code'] = $this->zip;
		}
		if ($this->country !== null) {
			$return['country_code'] = $this->country;
		}

		return $return;
	}
}
