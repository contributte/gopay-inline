<?php

declare(strict_types=1);

namespace Contributte\GopayInline\Api\Objects;

abstract class AbstractObject
{

	/**
	 * @return array
	 */
	abstract public function toArray();

}
