<?php declare(strict_types = 1);

namespace Contributte\GopayInline\Http;

interface Io
{

	public function call(Request $request): Response;

}
