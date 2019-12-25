<?php

namespace Contributte\GopayInline\Http;

interface Io
{

	/**
	 * @param Request $request
	 * @return Response|FALSE
	 */
	public function call(Request $request);

}
