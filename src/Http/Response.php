<?php

declare(strict_types=1);

namespace Contributte\GopayInline\Http;


use RecursiveArrayIterator;

final class Response implements \ArrayAccess, \Countable, \IteratorAggregate
{

	/** @var mixed[]|null */
	protected $data;

	/** @var mixed[] */
	protected $headers;

	/** @var int */
	protected $code;

	/** @var string|null */
	protected $error;


	/**
	 * @return mixed[]|null
	 */
	public function getData(): ?array
	{
		return $this->data;
	}


	/**
	 * @param mixed[] $data
	 */
	public function setData(?array $data): void
	{
		$this->data = $data;
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


	public function getCode(): int
	{
		return $this->code;
	}


	public function setCode(int $code): void
	{
		$this->code = $code;
	}


	public function getError(): ?string
	{
		return $this->error;
	}


	public function setError(string $error): void
	{
		$this->error = $error;
	}


	public function isSuccess(): bool
	{
		return $this->error === null;
	}


	/**
	 * @param mixed $offset
	 * @return bool
	 */
	public function offsetExists($offset): bool
	{
		return isset($this->data[$offset]);
	}


	/**
	 * @param mixed $offset
	 * @return mixed|null
	 */
	public function offsetGet($offset)
	{
		return $this->data[$offset] ?? null;
	}


	/**
	 * @param mixed $offset
	 * @param mixed $value
	 */
	public function offsetSet($offset, $value): void
	{
		if (is_array($this->data)) {
			$this->data[$offset] = $value;
		}
	}


	/**
	 * @param mixed $offset
	 */
	public function offsetUnset($offset): void
	{
		if (is_array($this->data)) {
			unset($this->data[$offset]);
		}
	}


	public function count(): int
	{
		return $this->data === null ? 0 : \count($this->data);
	}


	public function getIterator(): \RecursiveArrayIterator
	{
		return new RecursiveArrayIterator($this->data);
	}


	/**
	 * @return mixed
	 */
	public function __get(string $name)
	{
		return $this->$name ?? $this->offsetGet($name);
	}
}
