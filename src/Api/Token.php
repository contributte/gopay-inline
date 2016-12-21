<?php

namespace Markette\GopayInline\Api;

class Token
{

	/** @var string */
	public $type;

	/** @var string */
	public $accessToken;

	/** @var string */
	public $refreshToken;

	/** @var int */
	public $expireIn;

	/**
	 * @param array $data
	 * @return Token
	 */
	public static function create(array $data)
	{
		$token = new Token;
		if (isset($data['token_type'])) {
			$token->type = $data['token_type'];
		}
		if (isset($data['access_token'])) {
			$token->accessToken = $data['access_token'];
		}
		if (isset($data['refresh_token'])) {
			$token->refreshToken = $data['refresh_token'];
		}
		if (isset($data['expires_in'])) {
			$token->expireIn = $data['expires_in'];
		}

		return $token;
	}

}
