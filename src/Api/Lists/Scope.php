<?php

namespace Markette\GopayInline\Api\Lists;

class Scope
{

	// Allows only the establishment of payments
	const PAYMENT_CREATE = 'payment-create';

	// Allows all operations above payments
	const PAYMENT_ALL = 'payment-all';

}
