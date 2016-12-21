<?php

namespace Markette\GopayInline\Api\Objects;

use Markette\GopayInline\Api\Lists\TargetType;

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
	 * @return array
	 */
	public function toArray()
	{
		return [
			'type' => $this->type,
			'goid' => $this->goid,
		];
	}

}
