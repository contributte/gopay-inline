<?php

declare(strict_types=1);

namespace Contributte\GopayInline\Api\Lists;


final class SwiftCode
{
	public const OTHERS = 'OTHERS';

	// cz
	public const AIR_BANK = 'AIRACZPP';

	public const CESKA_SPORITELNA = 'GIBACZPX';

	public const CSOB = 'CEKOCZPP';

	public const EQUA_BANK = 'EQBKCZPP';

	public const ERA = 'CEKOCZPP-ERA';

	public const EXPO_BANK = 'EXPNCZPP';

	public const FIO_BANKA = 'FIOBCZPP';

	public const HELLO_BANK = 'BPPFCZP1';

	public const ING_BANK = 'INGBCZPP';

	public const KOMERCNI_BANKA = 'KOMBCZPP';

	public const MBANK = 'BREXCZPP';

	public const MONETA_MONEY_BANK = 'AGBACZPP';

	public const OBER_BANK = 'OBKLCZ2X';

	public const RAIFFEISENBANK = 'RZBCCZPP';

	public const SBER_BANK = 'VBOECZ2X';

	public const UNICREDIT_BANK_CZ = 'BACXCZPP';

	// sk
	public const BKS_BANK = 'BFKKSKBB';

	public const CITI_BANK_SK = 'CITISKBA';

	public const CSOB_SK = 'CEKOSKBX';

	public const FIO_BANKA_SK = 'FIOZSKBA';

	public const ING_BANK_SK = 'INGBSKBX';

	public const JT_BANKA_SK = 'JTBPSKBA';

	public const MBANK_SK = 'BREXSKBX';

	public const OBER_BANK_SK = 'OBKLSKBA';

	public const OTP_BANKA = 'OTPVSKBX';

	public const POSTOVA_BANKA = 'POBNSKBA';

	public const PRIMA_BANKA = 'KOMASK2X';

	public const PRIVAT_BANKA = 'BSLOSK22';

	public const SLOVENSKA_SPORITELNA = 'GIBASKBX';

	public const TATRA_BANKA = 'TATRSKBX';

	public const UNICREDIT_BANK_SK = 'UNCRSKBX';

	public const VUB_BANK = 'SUBASKBX';


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
			self::AIR_BANK,
			self::CESKA_SPORITELNA,
			self::CSOB,
			self::EQUA_BANK,
			self::ERA,
			self::EXPO_BANK,
			self::FIO_BANKA,
			self::HELLO_BANK,
			self::ING_BANK,
			self::KOMERCNI_BANKA,
			self::MBANK,
			self::MONETA_MONEY_BANK,
			self::OBER_BANK,
			self::RAIFFEISENBANK,
			self::SBER_BANK,
			self::UNICREDIT_BANK_CZ,
		];
	}


	/**
	 * @return string[]
	 */
	public static function sk(): array
	{
		return [
			self::BKS_BANK,
			self::CITI_BANK_SK,
			self::CSOB_SK,
			self::FIO_BANKA_SK,
			self::ING_BANK_SK,
			self::JT_BANKA_SK,
			self::MBANK_SK,
			self::OBER_BANK_SK,
			self::OTP_BANKA,
			self::POSTOVA_BANKA,
			self::PRIMA_BANKA,
			self::PRIVAT_BANKA,
			self::SLOVENSKA_SPORITELNA,
			self::TATRA_BANKA,
			self::UNICREDIT_BANK_SK,
			self::VUB_BANK,
		];
	}
}
