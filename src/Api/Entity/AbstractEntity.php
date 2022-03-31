<?php declare(strict_types = 1);

namespace Contributte\GopayInline\Api\Entity;

abstract class AbstractEntity
{

	/**
	 * @return mixed[]
	 */
	abstract public function toArray(): array;

}
