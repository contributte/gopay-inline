<?php

declare(strict_types=1);

namespace Contributte\GopayInline\Http;


final class Curl implements Io
{
	public function call(Request $request): ?Response
	{
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $request->getUrl());
		$headers = $request->getHeaders();
		array_walk($headers, function (&$item, $key) {
			$item = sprintf('%s:%s', $key, $item);
		});
		curl_setopt($ch, CURLOPT_HTTPHEADER, array_values($headers));
		curl_setopt_array($ch, $request->getOpts());
		$result = curl_exec($ch);

		$response = new Response;
		if ($result === false) {
			$response->setError(curl_strerror(curl_errno($ch)));
			$response->setData(null);
			$response->setCode(curl_errno($ch));
			$response->setHeaders(curl_getinfo($ch));
		} else {
			$info = curl_getinfo($ch);
			$response->setCode(curl_getinfo($ch, CURLINFO_HTTP_CODE));
			$response->setHeaders($info);
			$response->setData(json_decode($result, true));
			// Note: Sometimes cURL can return $info['content_type'] === 'application/octet-stream'
		}

		curl_close($ch);

		return $response;
	}
}
