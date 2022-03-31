<?php declare(strict_types = 1);

namespace Contributte\GopayInline\Auth;

use Contributte\GopayInline\Http\Response;

interface Auth
{

	/**
	 * @param mixed[] $credentials
	 */
	public function authenticate(array $credentials): Response;

}
