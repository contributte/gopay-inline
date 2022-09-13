<?php declare(strict_types = 1);

namespace Contributte\GopayInline\Http;

use ArrayAccess;
use Countable;
use IteratorAggregate;
use RecursiveArrayIterator;
use ReturnTypeWillChange;

/**
 * @property-read array $data
 * @property-read array $headers
 * @property-read int|null $code
 * @property-read string|null $error
 * @implements ArrayAccess<string, mixed>
 * @implements IteratorAggregate<string, mixed>
 */
class Response implements ArrayAccess, Countable, IteratorAggregate
{

	/** @var mixed[]|null */
	protected $data;

	/** @var array<string, string> */
	protected $headers = [];

	/** @var int|null */
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
	 * @param mixed $data
	 */
	public function setData($data): void
	{
		if ($data !== null) {
			$data = (array) $data;
		}

		$this->data = $data;
	}

	/**
	 * @return array<string, string>
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

	public function getCode(): ?int
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

	public function setError(?string $error): void
	{
		$this->error = $error;
	}

	public function isSuccess(): bool
	{
		return $this->error === null;
	}

	/**
	 * @param mixed $offset
	 */
	public function offsetExists($offset): bool
	{
		return isset($this->data[$offset]);
	}

	/**
	 * @param mixed $offset
	 * @return mixed
	 */
	#[ReturnTypeWillChange]
	public function offsetGet($offset)
	{
		if ($this->data === null) {
			return null;
		}

		return $this->data[$offset];
	}

	/**
	 * @param mixed $offset
	 * @param mixed $value
	 */
	public function offsetSet($offset, $value): void
	{
		$this->data[$offset] = $value;
	}

	/**
	 * @param mixed $offset
	 */
	public function offsetUnset($offset): void
	{
		unset($this->data[$offset]);
	}

	public function count(): int
	{
		return $this->data === null ? 0 : count($this->data);
	}

	public function getIterator(): RecursiveArrayIterator
	{
		return new RecursiveArrayIterator($this->data ?? []);
	}

	/**
	 * MAGIC *******************************************************************
	 **/

	/**
	 * @return mixed
	 */
	public function __get(string $name)
	{
		if (isset($this->$name)) {
			return $this->$name;
		}

		return $this->offsetGet($name);
	}

}
