<?php

declare(strict_types=1);

namespace Contributte\GopayInline\Api\Lists;


final class Scope
{

	// Allows only the establishment of payments
	public const PAYMENT_CREATE = 'payment-create';

	// Allows all operations above payments
	public const PAYMENT_ALL = 'payment-all';
}
