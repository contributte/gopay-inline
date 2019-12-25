<?php

namespace Contributte\GopayInline\Utils;

final class Validator
{

	/**
	 * @param mixed[] $array
	 * @param string[] $keys
	 * @return TRUE|string[]
	 */
	public static function validateRequired(array $array, array $keys)
	{
		$diff = array_diff_key(array_flip($keys), $array);

		return count($diff) > 0 ? array_keys($diff) : TRUE;
	}

	/**
	 * @param mixed[] $array
	 * @param string[] $keys
	 * @return TRUE|string[]
	 */
	public static function validateOptional(array $array, array $keys)
	{
		$diff = array_diff_key($array, array_flip($keys));

		return count($diff) > 0 ? array_keys($diff) : TRUE;
	}

}
