<?php

declare(strict_types=1);

namespace Contributte\GopayInline\Exception;


class HttpException extends GopayException
{

	/**
	 * @param mixed[] $error
	 * @return string
	 */
	public static function format(array $error): string
	{
		$field = isset($error['field']) ? '[' . $error['field'] . ']' : null;
		$description = $error['description'] ?? null;
		$message = isset($error['message']) ? rtrim($error['message'], '.') . ($description !== null ? ':' : '') : null;
		$scope = isset($error['scope']) ? '(' . $error['scope'] . ')' : null;
		$code = isset($error['error_code']) ? '#' . $error['error_code'] : null;

		$parts = array_filter([$code, $scope, $field, $message, $description], static function (?string $item): bool {
			return $item !== null;
		});

		return implode(' ', $parts);
	}
}
