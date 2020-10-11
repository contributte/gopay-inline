<?php

declare(strict_types=1);

namespace Contributte\GopayInline\Http;


use RecursiveArrayIterator;

/**
 * @property-read mixed $data
 * @property-read mixed $headers
 * @property-read int $code
 * @property-read string $error
 */
class Response implements \ArrayAccess, \Countable, \IteratorAggregate
{

	/** @var mixed */
	protected $data;

	/** @var mixed */
	protected $headers;

	/** @var int */
	protected $code;

	/** @var string|null */
	protected $error;


	/**
	 * @return array|FALSE
	 */
	public function getData()
	{
		return $this->data;
	}


	/**
	 * @param mixed $data
	 * @return void
	 */
	public function setData($data)
	{
		if (!is_bool($data) && $data !== null) {
			$data = (array) $data;
		}
		$this->data = $data;
	}


	/**
	 * @return array
	 */
	public function getHeaders()
	{
		return $this->headers;
	}


	/**
	 * @param mixed $headers
	 * @return void
	 */
	public function setHeaders($headers)
	{
		$this->headers = $headers;
	}


	/**
	 * @return int
	 */
	public function getCode()
	{
		return $this->code;
	}


	/**
	 * @param mixed $code
	 * @return void
	 */
	public function setCode($code)
	{
		$this->code = (int) $code;
	}


	/**
	 * @return string|null
	 */
	public function getError()
	{
		return $this->error;
	}


	/**
	 * @param string $error
	 * @return void
	 */
	public function setError($error)
	{
		$this->error = $error;
	}


	/**
	 * @return bool
	 */
	public function isSuccess()
	{
		return $this->error == false;
	}

	/**
	 * ARRAY ACCESS ************************************************************
	 */

	/**
	 * @param mixed $offset
	 * @return bool
	 */
	public function offsetExists($offset)
	{
		if (is_array($this->data)) {
			return isset($this->data[$offset]);
		}

		return false;
	}


	/**
	 * @param mixed $offset
	 * @return mixed
	 */
	public function offsetGet($offset)
	{
		if (!is_array($this->data))
			return null;

		return $this->data[$offset];
	}


	/**
	 * @param mixed $offset
	 * @param mixed $value
	 * @return void
	 */
	public function offsetSet($offset, $value)
	{
		if (is_array($this->data)) {
			$this->data[$offset] = $value;
		}
	}


	/**
	 * @param mixed $offset
	 * @return void
	 */
	public function offsetUnset($offset)
	{
		if (is_array($this->data)) {
			unset($this->data[$offset]);
		}
	}


	/**
	 * @return int
	 */
	public function count()
	{
		return $this->data === null ? 0 : count($this->data);
	}

	/**
	 * ITERATOR AGGREGATE ******************************************************
	 */

	/**
	 * @return RecursiveArrayIterator
	 */
	public function getIterator()
	{
		return new RecursiveArrayIterator($this->data);
	}

	/**
	 * MAGIC *******************************************************************
	 **/

	/**
	 * @param string $name
	 * @return mixed
	 */
	public function __get($name)
	{
		if (isset($this->$name)) {
			return $this->$name;
		}

		return $this->offsetGet($name);
	}

}
