<?php

namespace Markette\GopayInline\Exception;

use stdClass;

class HttpException extends GopayException
{

	/**
	 * @param stdClass $error
	 * @return self
	 */
	public static function format(stdClass $error)
	{
		$field = isset($error->field) ? '[' . $error->field . ']' : NULL;
		$message = isset($error->message) ? $error->message : (isset($error->description) ? $error->description : NULL);
		$scope = isset($error->scope) ? '(' . $error->scope . ')' : NULL;
		$code = isset($error->error_code) ? '#' . $error->error_code : NULL;

		$parts = array_filter([$code, $scope, $field, $message], function ($item) {
			return $item !== NULL;
		});

		return implode(' ', $parts);
	}

}
