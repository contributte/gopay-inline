<?php

namespace Contributte\GopayInline\Http;

interface Io
{

	/**
	 * @param Request $request
	 * @return Response
	 */
	public function call(Request $request);

}
