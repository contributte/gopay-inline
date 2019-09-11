<?php

namespace Contributte\GopayInline\Bridges\Nette\DI;

use Contributte\GopayInline\Client;
use Contributte\GopayInline\Config;
use Nette\DI\CompilerExtension;
use Nette\DI\Definitions\Statement;
use Nette\Utils\Validators;

class GopayExtension extends CompilerExtension
{

	/** @var mixed[] */
	private $defaults = [
		'goId' => NULL,
		'clientId' => NULL,
		'clientSecret' => NULL,
		'test' => TRUE,
	];

	/**
	 * Register services
	 *
	 * @return void
	 */
	public function loadConfiguration()
	{
		$config = $this->validateConfig($this->defaults);
		$builder = $this->getContainerBuilder();

		Validators::assertField($config, 'goId', 'string|number');
		Validators::assertField($config, 'clientId', 'string|number');
		Validators::assertField($config, 'clientSecret', 'string');
		Validators::assertField($config, 'test', 'bool');

		$definition = $builder->addDefinition($this->prefix('client'));
		$configArguments = [
			$config['goId'],
			$config['clientId'],
			$config['clientSecret'],
			$config['test'] !== FALSE ? Config::TEST : Config::PROD,
		];

		if (class_exists(Statement::class)) { // Nette 3.0
			$definition->setFactory(Client::class, [
				new Statement(Config::class, $configArguments),
			]);
		} else { // Deprecated support for Nette 2.*
			$definition->setClass(Client::class, [
				new \Nette\DI\Statement(Config::class, $configArguments),
			]);
		}
	}

}
