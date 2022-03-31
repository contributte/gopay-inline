<?php declare(strict_types = 1);

namespace Contributte\GopayInline;

use Contributte\GopayInline\Api\Gateway;

class Config
{

	// Modes
	public const PROD = 'PROD';
	public const TEST = 'TEST';

	/** @var string */
	private $goId;

	/** @var string */
	private $clientId;

	/** @var string */
	private $clientSecret;

	/** @var string */
	private $mode;

	public function __construct(string $goId, string $clientId, string $clientSecret, string $mode = self::TEST)
	{
		$this->goId = $goId;
		$this->clientId = $clientId;
		$this->clientSecret = $clientSecret;
		$this->setMode($mode);
	}

	public function getGoId(): string
	{
		return $this->goId;
	}

	public function getClientId(): string
	{
		return $this->clientId;
	}

	public function getClientSecret(): string
	{
		return $this->clientSecret;
	}

	public function getMode(): string
	{
		return $this->mode;
	}

	public function setMode(string $mode): void
	{
		if ($mode === self::PROD) {
			Gateway::init(Gateway::PROD);
			$this->mode = self::PROD;
		} else {
			Gateway::init(Gateway::TEST);
			$this->mode = self::TEST;
		}
	}

}
