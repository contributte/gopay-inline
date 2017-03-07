<?php

namespace Markette\GopayInline\Http;

class Curl implements Io
{

	/**
	 * @param Request $request
	 * @return Response
	 */
	public function call(Request $request)
	{
		// Create cURL
		$ch = curl_init();

		// Set-up URL
		curl_setopt($ch, CURLOPT_URL, $request->getUrl());

		// Set-up headers
		$headers = $request->getHeaders();
		array_walk($headers, function (&$item, $key) {
			$item = sprintf('%s:%s', $key, $item);
		});
		curl_setopt($ch, CURLOPT_HTTPHEADER, array_values($headers));

		// Set-up others
		curl_setopt_array($ch, $request->getOpts());

		// Receive result
		$result = curl_exec($ch);

		// Parse response
		$response = new Response();
		if ($result === FALSE) {
			$response->setError(curl_strerror(curl_errno($ch)));
			$response->setData(FALSE);
			$response->setCode(curl_errno($ch));
			$response->setHeaders(curl_getinfo($ch));
		} else {
			$info = curl_getinfo($ch);
			$response->setCode(curl_getinfo($ch, CURLINFO_HTTP_CODE));
			$response->setHeaders($info);

			if ($info['content_type'] == 'application/octet-stream') {
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
