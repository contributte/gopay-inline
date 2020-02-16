<?php declare(strict_types = 1);

namespace Contributte\GopayInline\Http;

class Curl implements Io
{

	public function call(Request $request): Response
	{
		// Create cURL
		$ch = curl_init();

		// Set-up URL
		curl_setopt($ch, CURLOPT_URL, $request->getUrl());

		// Set-up headers
		$headers = $request->getHeaders();
		array_walk($headers, function (&$item, $key): void {
			$item = sprintf('%s:%s', $key, $item);
		});
		curl_setopt($ch, CURLOPT_HTTPHEADER, array_values($headers));

		// Set-up others
		curl_setopt_array($ch, $request->getOpts());

		// Receive result
		$result = curl_exec($ch);

		// Parse response
		$response = new Response();
		if ($result === false) {
			$response->setError(curl_strerror(curl_errno($ch)));
			$response->setData(false);
			$response->setCode(curl_errno($ch));
			$response->setHeaders(curl_getinfo($ch));
		} else {
			$info = curl_getinfo($ch);
			$response->setCode(curl_getinfo($ch, CURLINFO_HTTP_CODE));
			$response->setHeaders($info);

			if ($info['content_type'] === 'application/octet-stream') {
				$response->setData($result);
			} else {
				$response->setData(json_decode($result));
			}
		}

		// Close cURL
		curl_close($ch);

		return $response;
	}

}
