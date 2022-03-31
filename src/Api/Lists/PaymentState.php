<?php declare(strict_types = 1);

namespace Contributte\GopayInline\Api\Lists;

class PaymentState
{

	// Payment created
	public const CREATED = 'CREATED';

	// Payment method chosen
	public const PAYMENT_METHOD_CHOSEN = 'PAYMENT_METHOD_CHOSEN';

	// Payment paid
	public const PAID = 'PAID';

	// Payment pre-authorized
	public const AUTHORIZED = 'AUTHORIZED';

	// Payment canceled
	public const CANCELED = 'CANCELED';

	// Payment timeouted
	public const TIMEOUTED = 'TIMEOUTED';

	// Payment refunded
	public const REFUNDED = 'REFUNDED';

	// Payment partially refunded
	public const PARTIALLY_REFUNDED = 'PARTIALLY_REFUNDED';

}
