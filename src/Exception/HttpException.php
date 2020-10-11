<?php

namespace Contributte\GopayInline\Exception;


use stdClass;

class HttpException extends GopayException
{

	/**
	 * @param stdClass $error
	 * @return string
	 */
	public static function format(stdClass $error)
	{
		$field = isset($error->field) ? '[' . $error->field . ']' : null;
		$description = isset($error->description) ? $error->description : null;
		$message = isset($error->message) ? rtrim($error->message, '.') . ($description !== null ? ':' : '') : null;
		$scope = isset($error->scope) ? '(' . $error->scope . ')' : null;
		$code = isset($error->error_code) ? '#' . $error->error_code : null;

		$parts = array_filter([$code, $scope, $field, $message, $description], function ($item) {
			return $item !== null;
		});

		return implode(' ', $parts);
	}

}
