<?php declare(strict_types=1);

namespace Contributte\GopayInline\Bridges\Nette\DI;

class InvalidConfigurationException extends \LogicException
{

}

if (!class_exists('Nette\DI\InvalidConfigurationException')) {
	class_alias(InvalidConfigurationException::class, 'Nette\DI\InvalidConfigurationException');
}
