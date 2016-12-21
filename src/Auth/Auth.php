<?php

namespace Markette\GopayInline\Auth;

use Markette\GopayInline\Http\Response;

interface Auth
{

	/**
	 * @param array $credentials
	 * @return Response
	 */
	public function authenticate(array $credentials);

}
