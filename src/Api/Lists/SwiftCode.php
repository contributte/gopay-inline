<?php

namespace Markette\GopayInline\Api\Lists;

class SwiftCode
{

	const CESKA_SPORITELNA = 'GIBACZPX';
	const KOMERCNI_BANKA = 'KOMBCZPP';
	const RAIFFEISENBANK = 'RZBCCZPP';
	const MBANK = 'BREXCZPP';
	const FIO_BANKA = 'FIOBCZPP';
	const CSOB = 'CEKOCZPP';
	const UNICREDIT_BANK_CZ = 'BACXCZPP';
	const ERA = 'CEKOCZPP-ERA';
	const VSEOBECNA_VEROVA_BANKA_BANKA = 'SUBASKBX';
	const TATRA_BANKA = 'TATRSKBX';
	const UNICREDIT_BANK_SK = 'UNCRSKBX';
	const SLOVENSKA_SPORITELNA = 'GIBASKBX';
	const OTP_BANKA = 'OTPVSKBX';
	const POSTOVA_BANKA = 'POBNSKBA';
	const CSOB_SK = 'CEKOSKBX';
	const SBERBANK_SLOVENSKO = 'LUBASKBX';

	/**
	 * @return array
	 */
	public static function all()
	{
		return array_merge(self::cz(), self::sk());
	}

	/**
	 * @return array
	 */
	public static function cz()
	{
		return [
			self::CESKA_SPORITELNA,
			self::KOMERCNI_BANKA,
			self::RAIFFEISENBANK,
			self::MBANK,
			self::FIO_BANKA,
			self::CSOB,
			self::ERA,
			self::UNICREDIT_BANK_CZ,
		];
	}

	/**
	 * @return array
	 */
	public static function sk()
	{
		return [
			self::VSEOBECNA_VEROVA_BANKA_BANKA,
			self::TATRA_BANKA,
			self::UNICREDIT_BANK_SK,
			self::SLOVENSKA_SPORITELNA,
			self::OTP_BANKA,
			self::POSTOVA_BANKA,
			self::CSOB_SK,
			self::SBERBANK_SLOVENSKO,
		];
	}

}
