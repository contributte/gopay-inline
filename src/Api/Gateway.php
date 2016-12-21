<?php

namespace Markette\GopayInline\Api;

class Gateway
{

	// Modes
	const TEST = 'TEST';
	const PROD = 'PROD';

	/** @var string */
	private static $mode;

	/**
	 * @param string $mode
	 * @return void
	 */
	public static function init($mode)
	{
		self::$mode = $mode;
	}

	/**
	 * @return string
	 */
	public static function getOauth2TokenUrl()
	{
		if (self::$mode === self::PROD) {
			return 'https://gate.gopay.cz/api/oauth2/token';
		} else {
			return 'https://gw.sandbox.gopay.com/api/oauth2/token';
		}
	}

	/**
	 * @return string
	 */
	public static function getBaseApiUrl()
	{
		if (self::$mode === self::PROD) {
			return 'https://gate.gopay.cz/api';
		} else {
			return 'https://gw.sandbox.gopay.com/api';
		}
	}

	/**
	 * @param string $uri
	 * @return string
	 */
	public static function getFullApiUrl($uri)
	{
		if (self::$mode === self::PROD) {
			return 'https://gate.gopay.cz/api/' . trim($uri, '/');
		} else {
			return 'https://gw.sandbox.gopay.com/api/' . trim($uri, '/');
		}
	}

	/**
	 * @return string
	 */
	public static function getInlineJsUrl()
	{
		if (self::$mode === self::PROD) {
			return 'https://gate.gopay.cz/gp-gw/js/embed.js';
		} else {
			return 'https://gw.sandbox.gopay.com/gp-gw/js/embed.js';
		}
	}

}
