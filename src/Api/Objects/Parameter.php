<?php declare(strict_types = 1);

namespace Contributte\GopayInline\Api\Objects;

class Parameter extends AbstractObject
{

	/** @var string */
	public $name;

	/** @var mixed */
	public $value;

	/**
	 * ABSTRACT ****************************************************************
	 */

	/**
	 * @return mixed[]
	 */
	public function toArray(): array
	{
		return [
			'name' => $this->name,
			'value' => $this->value,
		];
	}

}
