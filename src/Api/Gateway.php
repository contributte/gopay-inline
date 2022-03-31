<?php declare(strict_types = 1);

namespace Contributte\GopayInline\Api;

class Gateway
{

	// Modes
	public const TEST = 'TEST';
	public const PROD = 'PROD';

	/** @var string */
	private static $mode;

	public static function init(string $mode): void
	{
		self::$mode = $mode;
	}

	public static function getOauth2TokenUrl(): string
	{
		return self::$mode === self::PROD ? 'https://gate.gopay.cz/api/oauth2/token' : 'https://gw.sandbox.gopay.com/api/oauth2/token';
	}

	public static function getBaseApiUrl(): string
	{
		return self::$mode === self::PROD ? 'https://gate.gopay.cz/api' : 'https://gw.sandbox.gopay.com/api';
	}

	public static function getFullApiUrl(string $uri): string
	{
		return self::$mode === self::PROD ? 'https://gate.gopay.cz/api/' . trim($uri, '/') : 'https://gw.sandbox.gopay.com/api/' . trim($uri, '/');
	}

	public static function getInlineJsUrl(): string
	{
		return self::$mode === self::PROD ? 'https://gate.gopay.cz/gp-gw/js/embed.js' : 'https://gw.sandbox.gopay.com/gp-gw/js/embed.js';
	}

}
