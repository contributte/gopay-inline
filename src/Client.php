<?php

namespace Contributte\GopayInline;


use Contributte\GopayInline\Api\Token;
use Contributte\GopayInline\Auth\Auth;
use Contributte\GopayInline\Auth\Oauth2Client;
use Contributte\GopayInline\Exception\GopayException;
use Contributte\GopayInline\Http\Http;
use Contributte\GopayInline\Http\HttpClient;
use Contributte\GopayInline\Http\Request;
use Contributte\GopayInline\Http\Response;
use Contributte\GopayInline\Service\AccountsService;
use Contributte\GopayInline\Service\AuthenticationService;
use Contributte\GopayInline\Service\PaymentsService;

/**
 * @property-read PaymentsService $payments
 * @property-read AccountsService $accounts
 */
class Client
{

	/** @var array */
	private static $services = [
		'authentication' => null,
		'accounts' => null,
		'payments' => null,
	];

	/** @var Config */
	private $config;

	/** @var Auth */
	private $auth;

	/** @var Http */
	private $http;

	/** @var Token|null */
	private $token;


	/**
	 * @param Config $config
	 */
	public function __construct($config)
	{
		$this->config = $config;
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
	 * @return bool
	 */
	public function hasToken()
	{
		return $this->token !== null;
	}


	/**
	 * @param array $credentials
	 * @return string
	 */
	public function authenticate(array $credentials)
	{
		if ($this->token === null) {
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
		if ($this->token === null) {
			throw new GopayException('Invalid token. Please do authorization.');
		}

		return $this->getHttp()->doRequest($request);
	}


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
	 * API *********************************************************************
	 */


	/**
	 * @return AuthenticationService
	 */
	public function createAuthenticationService()
	{
		return new AuthenticationService($this);
	}


	/**
	 * @param string $name
	 * @return mixed
	 */
	public function __get($name)
	{
		if (array_key_exists($name, self::$services)) {
			if (self::$services[$name] === null) {
				self::$services[$name] = call_user_func_array([$this, 'create' . ucfirst($name) . 'Service'], [$this]);
			}

			return self::$services[$name];
		}

		return null;
	}

	/**
	 * SERVICES ****************************************************************
	 */


	/**
	 * @return Auth
	 */
	protected function getAuth()
	{
		if ($this->auth === null) {
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
		if ($this->http === null) {
			$this->http = new HttpClient();
		}

		return $this->http;
	}

	/**
	 * MAGIC *******************************************************************
	 */


	/**
	 * @param Http $http
	 * @return void
	 */
	public function setHttp(Http $http)
	{
		$this->http = $http;
	}

}
