<?php declare(strict_types = 1);

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

	/** @var Config */
	private $config;

	/** @var Auth */
	private $auth;

	/** @var Http */
	private $http;

	/** @var Token|null */
	private $token;

	/** @var array<string, object> */
	private static $services = [
		'authentication' => null,
		'accounts' => null,
		'payments' => null,
	];

	public function __construct(Config $config)
	{
		$this->config = $config;
	}

	protected function getAuth(): Auth
	{
		if ($this->auth === null) {
			$this->auth = new Oauth2Client($this, $this->getHttp());
		}

		return $this->auth;
	}

	public function setAuth(Auth $auth): void
	{
		$this->auth = $auth;
	}

	protected function getHttp(): Http
	{
		if ($this->http === null) {
			$this->http = new HttpClient();
		}

		return $this->http;
	}

	public function setHttp(Http $http): void
	{
		$this->http = $http;
	}

	public function getGoId(): string
	{
		return $this->config->getGoId();
	}

	public function getClientId(): string
	{
		return $this->config->getClientId();
	}

	public function getClientSecret(): string
	{
		return $this->config->getClientSecret();
	}

	public function getToken(): ?Token
	{
		return $this->token;
	}

	public function hasToken(): bool
	{
		return $this->token !== null;
	}

	/**
	 * @param string|Token $token
	 */
	public function setToken($token): void
	{
		if (is_string($token)) {
			$this->token = new Token();
			$this->token->accessToken = $token;
		} else {
			$this->token = $token;
		}
	}

	/**
	 * API *********************************************************************
	 */

	/**
	 * @param mixed[] $credentials
	 */
	public function authenticate(array $credentials): string
	{
		if ($this->token === null) {
			$response = $this->getAuth()->authenticate($credentials);
			$this->token = Token::create($response->getData());
		}

		return $this->token->accessToken;
	}

	public function call(Request $request): Response
	{
		if ($this->token === null) {
			throw new GopayException('Invalid token. Please do authorization.');
		}

		return $this->getHttp()->doRequest($request);
	}

	public function createPaymentsService(): PaymentsService
	{
		return new PaymentsService($this);
	}

	public function createAccountsService(): AccountsService
	{
		return new AccountsService($this);
	}

	public function createAuthenticationService(): AuthenticationService
	{
		return new AuthenticationService($this);
	}

	/**
	 * MAGIC *******************************************************************
	 */

	/**
	 * @return mixed
	 */
	public function __get(string $name)
	{
		if (array_key_exists($name, self::$services)) {
			if (self::$services[$name] === null) {
				self::$services[$name] = call_user_func_array([$this, 'create' . ucfirst($name) . 'Service'], [$this]);
			}

			return self::$services[$name];
		}

		return null;
	}

}
