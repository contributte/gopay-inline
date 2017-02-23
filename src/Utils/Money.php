<?php

namespace Markette\GopayInline\Utils;

final class Money
{

	/**
	 * @param float $amout
	 * @return float
	 */
	public static function toCents($amout)
	{
		return round($amout * 100);
	}

}
