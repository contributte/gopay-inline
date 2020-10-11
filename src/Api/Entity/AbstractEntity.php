<?php

declare(strict_types=1);

namespace Contributte\GopayInline\Api\Entity;

abstract class AbstractEntity
{

	/**
	 * @return array
	 */
	abstract public function toArray();

}
