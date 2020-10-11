<?php

declare(strict_types=1);

namespace Contributte\GopayInline\Api\Lists;


final class PaymentInstrument
{

	// Payment cards
	public const PAYMENT_CARD = 'PAYMENT_CARD';

	// Bank transfer
	public const BANK_ACCOUNT = 'BANK_ACCOUNT';

	// Premium SMS
	public const PRSMS = 'PRSMS';

	// mPayment
	public const MPAYMENT = 'MPAYMENT';

	// Paysafecard
	public const PAYSAFECARD = 'PAYSAFECARD';

	// superCASH
	public const SUPERCASH = 'SUPERCASH';

	// GoPay account
	public const GOPAY = 'GOPAY';

	// PayPal account
	public const PAYPAL = 'PAYPAL';

	// BITCOIN account
	public const BITCOIN = 'BITCOIN';

	// Google Pay
	public const GPAY = 'GPAY';


	/**
	 * @return mixed[]
	 */
	public static function all(): array
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
			self::GPAY,
		];
	}
}
