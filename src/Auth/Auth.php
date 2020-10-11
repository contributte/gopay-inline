<?php

namespace Contributte\GopayInline\Auth;


use Contributte\GopayInline\Http\Response;

interface Auth
{

	/**
	 * @param array $credentials
	 * @return Response
	 */
	public function authenticate(array $credentials);

}
