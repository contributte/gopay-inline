<?php

namespace Markette\GopayInline\Api\Objects;

class Contact extends AbstractObject
{

	/** @var string */
	public $firstname;

	/** @var string */
	public $lastname;

	/** @var string */
	public $email;

	/** @var string */
	public $phone;

	/** @var string */
	public $city;

	/** @var string */
	public $street;

	/** @var string */
	public $zip;

	/** @var string */
	public $country;

	/**
	 * ABSTRACT ****************************************************************
	 */

	/**
	 * @return array
	 */
	public function toArray()
	{
		$data = [];

		if ($this->firstname) {
			$data['first_name'] = $this->firstname;
		}

		if ($this->lastname) {
			$data['last_name'] = $this->lastname;
		}

		if ($this->email) {
			$data['email'] = $this->email;
		}

		if ($this->phone) {
			$data['phone_number'] = $this->phone;
		}

		if ($this->city) {
			$data['city'] = $this->city;
		}

		if ($this->street) {
			$data['street'] = $this->street;
		}

		if ($this->zip) {
			$data['postal_code'] = $this->zip;
		}

		if ($this->country) {
			$data['country_code'] = $this->country;
		}

		return $data;
	}

}
