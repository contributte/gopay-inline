<?php declare(strict_types = 1);

namespace Contributte\GopayInline\Http;

interface Http
{

	// Http methods
	public const METHOD_GET = 'GET';
	public const METHOD_POST = 'POST';

	// Content types
	public const CONTENT_JSON = 'application/json';
	public const CONTENT_FORM = 'application/x-www-form-urlencoded';

	public function doRequest(Request $request): Response;

}
