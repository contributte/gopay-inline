<?php

namespace Contributte\GopayInline\Api\Lists;

class SwiftCode
{
	const OTHERS = 'OTHERS';

	// cz
	const AIR_BANK = 'AIRACZPP';
	const CESKA_SPORITELNA = 'GIBACZPX';
	const CSOB = 'CEKOCZPP';
	const EQUA_BANK = 'EQBKCZPP';
	const ERA = 'CEKOCZPP-ERA';
	const EXPO_BANK = 'EXPNCZPP';
	const FIO_BANKA = 'FIOBCZPP';
	const HELLO_BANK = 'BPPFCZP1';
	const ING_BANK = 'INGBCZPP';
	const KOMERCNI_BANKA = 'KOMBCZPP';
	const MBANK = 'BREXCZPP';
	const MONETA_MONEY_BANK = 'AGBACZPP';
	const OBER_BANK = 'OBKLCZ2X';
	const RAIFFEISENBANK = 'RZBCCZPP';
	const SBER_BANK = 'VBOECZ2X';
	const UNICREDIT_BANK_CZ = 'BACXCZPP';

	// sk
	const BKS_BANK = 'BFKKSKBB';
	const CITI_BANK_SK = 'CITISKBA';
	const CSOB_SK = 'CEKOSKBX';
	const FIO_BANKA_SK = 'FIOZSKBA';
	const ING_BANK_SK = 'INGBSKBX';
	const JT_BANKA_SK = 'JTBPSKBA';
	const MBANK_SK = 'BREXSKBX';
	const OBER_BANK_SK = 'OBKLSKBA';
	const OTP_BANKA = 'OTPVSKBX';
	const POSTOVA_BANKA = 'POBNSKBA';
	const PRIMA_BANKA = 'KOMASK2X';
	const PRIVAT_BANKA = 'BSLOSK22';
	const SLOVENSKA_SPORITELNA = 'GIBASKBX';
	const TATRA_BANKA = 'TATRSKBX';
	const UNICREDIT_BANK_SK = 'UNCRSKBX';
	const VUB_BANK = 'SUBACZPP';

	/**
	 * @return array
	 */
	public static function all()
	{
		return array_merge([self::OTHERS], self::cz(), self::sk());
	}

	/**
	 * @return array
	 */
	public static function cz()
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
	 * @return array
	 */
	public static function sk()
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
