<?php

namespace Markette\GopayInline;

use Markette\GopayInline\Api\Gateway;

class Config
{

	// Modes
	const PROD = 'PROD';
	const TEST = 'TEST';

	/** @var float */
	private $goId;

	/** @var string */
	private $clientId;

	/** @var string */
	private $clientSecret;

	/** @var string */
	private $mode;

	/**
	 * @param float $goId
	 * @param string $clientId
	 * @param string $clientSecret
	 * @param string $mode
	 */
	public function __construct($goId, $clientId, $clientSecret, $mode = self::TEST)
	{
		$this->goId = $goId;
		$this->clientId = $clientId;
		$this->clientSecret = $clientSecret;
		$this->setMode($mode);
	}

	/**
	 * @return float
	 */
	public function getGoId()
	{
		return $this->goId;
	}

	/**
	 * @return string
	 */
	public function getClientId()
	{
		return $this->clientId;
	}

	/**
	 * @return string
	 */
	public function getClientSecret()
	{
		return $this->clientSecret;
	}

	/**
	 * @return string
	 */
	public function getMode()
	{
		return $this->mode;
	}

	/**
	 * @param string $mode
	 * @return void
	 */
	public function setMode($mode)
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
