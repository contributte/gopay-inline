<?php

namespace Markette\GopayInline\Utils;

final class Validator
{

	/**
	 * @param array $array
	 * @param array $keys
	 * @return TRUE|array
	 */
	public static function validateRequired(array $array, array $keys)
	{
		$diff = array_diff_key(array_flip($keys), $array);

		return $diff ? array_keys($diff) : TRUE;
	}

	/**
	 * @param array $array
	 * @param array $keys
	 * @return TRUE|array
	 */
	public static function validateOptional(array $array, array $keys)
	{
		$diff = array_diff_key($array, array_flip($keys));

		return $diff ? array_keys($diff) : TRUE;
	}

}
