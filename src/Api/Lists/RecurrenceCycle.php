<?php declare(strict_types = 1);

namespace Contributte\GopayInline\Api\Lists;

class RecurrenceCycle
{

	// Daily recurring
	public const DAY = 'DAY';

	// Weekly recurring
	public const WEEK = 'WEEK';

	// Monthly recurring
	public const MONTH = 'MONTH';

	// Set only at manual recurring payments
	public const ON_DEMAND = 'ON_DEMAND';

}
