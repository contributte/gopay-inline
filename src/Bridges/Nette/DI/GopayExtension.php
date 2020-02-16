<?php declare(strict_types = 1);

namespace Contributte\GopayInline\Bridges\Nette\DI;

use Contributte\GopayInline\Client;
use Contributte\GopayInline\Config;
use Nette\DI\CompilerExtension;
use Nette\DI\Definitions\Statement;
use Nette\Schema\Elements\Type;
use Nette\Schema\Expect;
use Nette\Schema\Schema;

class GopayExtension extends CompilerExtension
{

	/**
	 * Register services
	 */
	public function loadConfiguration(): void
	{
		$config = $this->config;
		$builder = $this->getContainerBuilder();

		$builder->addDefinition($this->prefix('client'))
			->setFactory(Client::class, [
				new Statement(Config::class, [
					$config->goId,
					$config->clientId,
					$config->clientSecret,
					$config->test !== false ? Config::TEST : Config::PROD,
				]),
			]);
	}

	public function getConfigSchema(): Schema
	{
		return Expect::structure([
			'goId' => Expect::anyOf(new Type('string'), new Type('int'))->required(),
			'clientId' => Expect::anyOf(new Type('string'), new Type('int'))->required(),
			'clientSecret' => Expect::string()->required(),
			'test' => Expect::bool(true),
		]);
	}

}
