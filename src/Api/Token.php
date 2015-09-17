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
     * @param mixed $data
     * @return Token
     */
    public static function create($data)
    {
        $token = new Token;
        $token->type = $data->token_type;
        $token->accessToken = $data->access_token;
        $token->refreshToken = $data->refresh_token;
        $token->expireIn = intval($data->expires_in);
        return $token;
    }

}
