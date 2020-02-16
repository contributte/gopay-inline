<?php declare(strict_types = 1);

namespace Contributte\GopayInline\Http;

use ArrayAccess;
use Countable;
use IteratorAggregate;
use RecursiveArrayIterator;

/**
 * @property-read array $data
 * @property-read array $headers
 * @property-read int|null $code
 * @property-read string|null $error
 */
class Response implements ArrayAccess, Countable, IteratorAggregate
{

	/** @var mixed[] */
	protected $data = [];

	/** @var array<string, string> */
	protected $headers = [];

	/** @var int|null */
	protected $code;

	/** @var string|null */
	protected $error;

	/**
	 * @return mixed[]
	 */
	public function getData(): array
	{
		return $this->data;
	}

	/**
	 * @param mixed $data
	 */
	public function setData($data): void
	{
		if (!is_bool($data) && ($data === null || $data)) {
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

	public function setError(string $error): void
	{
		$this->error = $error;
	}

	public function isSuccess(): bool
	{
		return $this->error === null;
	}

	// phpcs:disable
	public function offsetExists($offset): bool
	{
		if ($this->data) {
			return isset($this->data[$offset]);
		}

		return false;
	}

	/**
	 * @return mixed
	 */
	public function offsetGet($offset)
	{
		if (!$this->data) {
			return null;
		}

		return $this->data[$offset];
	}

	/**
	 * @param mixed[] $value
	 */
	public function offsetSet($offset, $value): void
	{
		if ($this->data) {
			$this->data[$offset] = $value;
		}
	}

	public function offsetUnset($offset): void
	{
		if ($this->data) {
			unset($this->data[$offset]);
		}
	}
	// phpcs:enable

	public function count(): int
	{
		return $this->data === null ? 0 : count($this->data);
	}

	public function getIterator(): RecursiveArrayIterator
	{
		return new RecursiveArrayIterator($this->data);
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
