<?php

declare(strict_types=1);

namespace Contributte\GopayInline\Utils;


final class Money
{
	public static function toCents(float $amout): float
	{
		return round($amout * 100);
	}
}
