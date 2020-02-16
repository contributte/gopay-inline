<?php declare(strict_types = 1);

namespace Contributte\GopayInline\Api\Lists;

class SwiftCode
{

	const OTHERS = 'OTHERS';

	// cz
	public const CESKA_SPORITELNA = 'GIBACZPX';
	public const CSOB = 'CEKOCZPP';
	public const ERA = 'CEKOCZPP-ERA';
	public const FIO_BANKA = 'FIOBCZPP';
	public const KOMERCNI_BANKA = 'KOMBCZPP';
	public const MBANK = 'BREXCZPP';
	public const RAIFFEISENBANK = 'RZBCCZPP';
	public const UNICREDIT_BANK_CZ = 'BACXCZPP';

	// sk
	public const POSTOVA_BANKA = 'POBNSKBA';
	public const SLOVENSKA_SPORITELNA = 'GIBASKBX';
	public const TATRA_BANKA = 'TATRSKBX';
	public const UNICREDIT_BANK_SK = 'UNCRSKBX';

	/**
	 * @return string[]
	 */
	public static function all(): array
	{
		return array_merge([self::OTHERS], self::cz(), self::sk());
	}

	/**
	 * @return string[]
	 */
	public static function cz(): array
	{
		return [
			self::CESKA_SPORITELNA,
			self::CSOB,
			self::ERA,
			self::FIO_BANKA,
			self::KOMERCNI_BANKA,
			self::MBANK,
			self::RAIFFEISENBANK,
			self::UNICREDIT_BANK_CZ,
		];
	}

	/**
	 * @return string[]
	 */
	public static function sk(): array
	{
		return [
			self::POSTOVA_BANKA,
			self::SLOVENSKA_SPORITELNA,
			self::TATRA_BANKA,
			self::UNICREDIT_BANK_SK,
		];
	}

}
