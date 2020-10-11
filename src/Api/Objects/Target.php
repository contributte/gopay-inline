<?php

declare(strict_types=1);

namespace Contributte\GopayInline\Api\Objects;


use Contributte\GopayInline\Api\Lists\TargetType;

final class Target extends AbstractObject
{

	/** @var string */
	public $type = TargetType::ACCOUNT;

	/** @var float */
	public $goid;


	/**
	 * @deprecated use native getters
	 * @return mixed[]
	 */
	public function toArray(): array
	{
		return [
			'type' => $this->type,
			'goid' => $this->goid,
		];
	}


	public function getType(): string
	{
		return $this->type;
	}


	public function getGoid(): float
	{
		return $this->goid;
	}
}
