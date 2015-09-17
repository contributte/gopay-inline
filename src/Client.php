<?php

namespace Markette\GopayInline;

use Markette\GopayInline\Api\Token;
use Markette\GopayInline\Auth\Oauth2Client;
use Markette\GopayInline\Exception\GopayException;
use Markette\GopayInline\Http\HttpClient;
use Markette\GopayInline\Http\Request;
use Markette\GopayInline\Http\Response;
use Markette\GopayInline\Service\PaymentsService;

/**
 * @property-read PaymentsService $payments
 */
class Client
{

    /** @var Config */
    private $config;

    /** @var Oauth2Client */
    private $auth;

    /** @var HttpClient */
    private $http;

    /** @var Token */
    private $token;

    /** @var array */
    private static $services = [
        'payments'
    ];

    /**
     * @param Config $config
     */
    function __construct($config)
    {
        $this->config = $config;
    }

    /**
     * @return Oauth2Client
     */
    protected function getAuth()
    {
        if (!$this->auth) {
            $this->auth = new Oauth2Client($this);
        }
        return $this->auth;
    }

    /**
     * @param Oauth2Client $auth
     */
    public function setAuth($auth)
    {
        $this->auth = $auth;
    }

    /**
     * @return HttpClient
     */
    public function getHttp()
    {
        if (!$this->http) {
            $this->http = new HttpClient($this);
        }

        return $this->http;
    }

    /**
     * @param HttpClient $http
     */
    public function setHttp($http)
    {
        $this->http = $http;
    }

    /**
     * @return float
     */
    public function getGoId()
    {
        return $this->config->getGoId();
    }

    /**
     * @return string
     */
    public function getClientId()
    {
        return $this->config->getClientId();
    }

    /**
     * @return string
     */
    public function getClientSecret()
    {
        return $this->config->getClientSecret();
    }

    /**
     * @return Token
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param mixed $token
     */
    public function setToken($token)
    {
        if (is_string($token)) {
            $this->token = new Token;
            $this->token->accessToken = $token;
        } else {
            $this->token = $token;
        }
    }

    /**
     * API *********************************************************************
     */

    /**
     * @param string $scope
     * @return string
     */
    public function authenticate($scope)
    {
        if (!$this->token) {
            $response = $this->getAuth()->authenticate($scope);
            $this->token = Token::create($response->getData());
        }

        return $this->token->accessToken;
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function call(Request $request)
    {
        if (!$this->token) {
            throw new GopayException('Invalid token. Please do authorization.');
        }

        return $this->getHttp()->doRequest($request);
    }

    /**
     * SERVICES ****************************************************************
     */

    /**
     * @return PaymentsService
     */
    public function createPaymentsService()
    {
        return new PaymentsService($this);
    }

    /**
     * MAGIC *******************************************************************
     */

    /**
     * @param string $name
     * @return mixed
     */
    public function __get($name)
    {
        if (in_array($name, self::$services)) {
            return call_user_func_array([$this, 'create' . ucfirst($name) . 'Service'], [$this]);
        }

        return NULL;
    }

}
