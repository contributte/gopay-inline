<?php declare(strict_types = 1);

namespace Contributte\GopayInline\Bridges\Nette\DI;

use Contributte\GopayInline\Client;
use Contributte\GopayInline\Config;
use Nette\DI\CompilerExtension;
use Nette\DI\Definitions\Statement;
use Nette\Schema\Elements\Type;
use Nette\Schema\Expect;
use Nette\Schema\Schema;
use stdClass;

class GopayExtension extends CompilerExtension
{

	/**
	 * Register services
	 */
	public function loadConfiguration(): void
	{
		/** @var stdClass $config */
		$config = (object) $this->getConfig();
		$builder = $this->getContainerBuilder();

		if (!class_exists(Schema::class)) {
			$this->validate($config);
		}

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

	private function validate(stdClass $config): void
	{
		if (!isset($config->goId)) {
			throw new InvalidConfigurationException(sprintf('Missing %s.goId configuration option.', $this->name));
		}

		if (!isset($config->clientId)) {
			throw new InvalidConfigurationException(sprintf('Missing %s.clientId configuration option.', $this->name));
		}

		if (!isset($config->clientSecret)) {
			throw new InvalidConfigurationException(sprintf('Missing %s.clientSecret configuration option.', $this->name));
		}

		if (!isset($config->test)) {
			$config->test = true;
		}
	}

}
