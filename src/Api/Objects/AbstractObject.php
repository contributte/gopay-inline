<?php declare(strict_types = 1);

namespace Contributte\GopayInline\Api\Objects;

abstract class AbstractObject
{

	/**
	 * @return mixed[]
	 */
	abstract public function toArray(): array;

}
