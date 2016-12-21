<?php

namespace Markette\GopayInline\Http;

interface Http
{

	// Http methods
	const METHOD_GET = 'GET';
	const METHOD_POST = 'POST';

	// Content types
	const CONTENT_JSON = 'application/json';
	const CONTENT_FORM = 'application/x-www-form-urlencoded';

	/**
	 * @param Request $request
	 * @return Response
	 */
	public function doRequest(Request $request);

}
