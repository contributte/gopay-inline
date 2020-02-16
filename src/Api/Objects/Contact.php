<?php declare(strict_types = 1);

namespace Contributte\GopayInline\Api\Objects;

class Contact extends AbstractObject
{

	/** @var string|NULL */
	public $firstname;

	/** @var string|NULL */
	public $lastname;

	/** @var string|NULL */
	public $email;

	/** @var string|NULL */
	public $phone;

	/** @var string|NULL */
	public $city;

	/** @var string|NULL */
	public $street;

	/** @var string|NULL */
	public $zip;

	/** @var string|NULL */
	public $country;

	/**
	 * ABSTRACT ****************************************************************
	 */

	/**
	 * @return mixed[]
	 */
	public function toArray(): array
	{
		$data = [];

		if ($this->firstname !== null) {
			$data['first_name'] = $this->firstname;
		}

		if ($this->lastname !== null) {
			$data['last_name'] = $this->lastname;
		}

		if ($this->email !== null) {
			$data['email'] = $this->email;
		}

		if ($this->phone !== null) {
			$data['phone_number'] = $this->phone;
		}

		if ($this->city !== null) {
			$data['city'] = $this->city;
		}

		if ($this->street !== null) {
			$data['street'] = $this->street;
		}

		if ($this->zip !== null) {
			$data['postal_code'] = $this->zip;
		}

		if ($this->country !== null) {
			$data['country_code'] = $this->country;
		}

		return $data;
	}

}
