<?php declare(strict_types = 1);

namespace Contributte\GopayInline\Api\Objects;

use Contributte\GopayInline\Api\Lists\TargetType;

class Target extends AbstractObject
{

	/** @var string */
	public $type = TargetType::ACCOUNT;

	/** @var float */
	public $goid;

	/**
	 * ABSTRACT ****************************************************************
	 */

	/**
	 * @return mixed[]
	 */
	public function toArray(): array
	{
		return [
			'type' => $this->type,
			'goid' => $this->goid,
		];
	}

}
