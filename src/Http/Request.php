<?php declare(strict_types = 1);

namespace Contributte\GopayInline\Http;

class Request
{

	/** @var string|null */
	protected $url;

	/** @var mixed[] */
	protected $headers = [];

	/** @var mixed[] */
	protected $opts = [];

	/** @var mixed[] */
	protected $data = [];

	public function getUrl(): ?string
	{
		return $this->url;
	}

	public function setUrl(string $url): void
	{
		$this->url = $url;
	}

	/**
	 * @return mixed[]
	 */
	public function getHeaders(): array
	{
		return $this->headers;
	}

	/**
	 * @param mixed[] $headers
	 */
	public function setHeaders(array $headers): void
	{
		$this->headers = $headers;
	}

	public function addHeader(string $name, string $value): void
	{
		$this->headers[$name] = $value;
	}

	/**
	 * @param mixed[] $headers
	 */
	public function appendHeaders(array $headers): void
	{
		$this->headers += $headers;
	}

	/**
	 * @return mixed[]
	 */
	public function getOpts(): array
	{
		return $this->opts;
	}

	/**
	 * @param mixed[] $opts
	 */
	public function setOpts(array $opts): void
	{
		$this->opts = $opts;
	}

	public function addOpt(string $name, string $value): void
	{
		$this->opts[$name] = $value;
	}

	/**
	 * @param mixed[] $opts
	 */
	public function appendOpts(array $opts): void
	{
		$this->opts += $opts;
	}

	/**
	 * @return mixed[]
	 */
	public function getData(): array
	{
		return $this->data;
	}

	/**
	 * @param mixed[] $data
	 */
	public function setData(array $data): void
	{
		$this->data = $data;
	}

}
