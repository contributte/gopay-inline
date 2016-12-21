<?php

namespace Markette\GopayInline\Api\Lists;

class PaymentState
{

	// Payment created
	const CREATED = 'CREATED';

	// Payment method chosen
	const PAYMENT_METHOD_CHOSEN = 'PAYMENT_METHOD_CHOSEN';

	// Payment paid
	const PAID = 'PAID';

	// Payment pre-authorized
	const AUTHORIZED = 'AUTHORIZED';

	// Payment canceled
	const CANCELED = 'CANCELED';

	// Payment timeouted
	const TIMEOUTED = 'TIMEOUTED';

	// Payment refunded
	const REFUNDED = 'REFUNDED';

	// Payment partially refunded
	const PARTIALLY_REFUNDED = 'PARTIALLY_REFUNDED';

}
