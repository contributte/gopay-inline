<?php

namespace Markette\GopayInline\Api\Lists;

class PaymentInstrument
{

	// Payment cards
	const PAYMENT_CARD = 'PAYMENT_CARD';

	// Bank transfer
	const BANK_ACCOUNT = 'BANK_ACCOUNT';

	// Premium SMS
	const PRSMS = 'PRSMS';

	// mPayment
	const MPAYMENT = 'MPAYMENT';

	// Paysafecard
	const PAYSAFECARD = 'PAYSAFECARD';

	// superCASH
	const SUPERCASH = 'SUPERCASH';

	// GoPay account
	const GOPAY = 'GOPAY';

	// PayPal account
	const PAYPAL = 'PAYPAL';

	// BITCOIN account
	const BITCOIN = 'BITCOIN';

	/**
	 * @return array
	 */
	public static function all()
	{
		return [
			self::PAYMENT_CARD,
			self::BANK_ACCOUNT,
			self::PRSMS,
			self::MPAYMENT,
			self::PAYSAFECARD,
			self::SUPERCASH,
			self::GOPAY,
			self::PAYPAL,
			self::BITCOIN,
		];
	}

}
