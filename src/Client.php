<?php

namespace Markette\GopayInline;

use Markette\GopayInline\Api\Token;
use Markette\GopayInline\Auth\Auth;
use Markette\GopayInline\Auth\Oauth2Client;
use Markette\GopayInline\Exception\GopayException;
use Markette\GopayInline\Http\Http;
use Markette\GopayInline\Http\HttpClient;
use Markette\GopayInline\Http\Request;
use Markette\GopayInline\Http\Response;
use Markette\GopayInline\Service\AccountsService;
use Markette\GopayInline\Service\AuthenticationService;
use Markette\GopayInline\Service\PaymentsService;

/**
 * @property-read PaymentsService $payments
 * @property-read AccountsService $accounts
 */
class Client
{

	/** @var Config */
	private $config;

	/** @var Auth */
	private $auth;

	/** @var Http */
	private $http;

	/** @var Token */
	private $token;

	/** @var array */
	private static $services = [
		'authentication' => NULL,
		'accounts' => NULL,
		'payments' => NULL,
	];

	/**
	 * @param Config $config
	 */
	public function __construct($config)
	{
		$this->config = $config;
	}

	/**
	 * @return Auth
	 */
	protected function getAuth()
	{
		if (!$this->auth) {
			$this->auth = new Oauth2Client($this, $this->getHttp());
		}

		return $this->auth;
	}

	/**
	 * @param Auth $auth
	 * @return void
	 */
	public function setAuth(Auth $auth)
	{
		$this->auth = $auth;
	}

	/**
	 * @return Http
	 */
	protected function getHttp()
	{
		if (!$this->http) {
			$this->http = new HttpClient();
		}

		return $this->http;
	}

	/**
	 * @param Http $http
	 * @return void
	 */
	public function setHttp(Http $http)
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
	 * @return bool
	 */
	public function hasToken()
	{
		return $this->token !== NULL;
	}

	/**
	 * @param mixed $token
	 * @return void
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
	 * @param array $credentials
	 * @return string
	 */
	public function authenticate(array $credentials)
	{
		if (!$this->token) {
			$response = $this->getAuth()->authenticate($credentials);
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
	 * @return AccountsService
	 */
	public function createAccountsService()
	{
		return new AccountsService($this);
	}

	/**
	 * @return AuthenticationService
	 */
	public function createAuthenticationService()
	{
		return new AuthenticationService($this);
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
		if (array_key_exists($name, self::$services)) {
			if (self::$services[$name] === NULL) {
				self::$services[$name] = call_user_func_array([$this, 'create' . ucfirst($name) . 'Service'], [$this]);
			}

			return self::$services[$name];
		}

		return NULL;
	}

}
