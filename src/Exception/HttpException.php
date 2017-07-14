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
		$description = isset($error->description) ? $error->description : NULL;
		$message = isset($error->message) ? rtrim($error->message, '.') . ($description !== NULL ? ':' : '') : NULL;
		$scope = isset($error->scope) ? '(' . $error->scope . ')' : NULL;
		$code = isset($error->error_code) ? '#' . $error->error_code : NULL;

		$parts = array_filter([$code, $scope, $field, $message, $description], function ($item) {
			return $item !== NULL;
		});

		return implode(' ', $parts);
	}

}
