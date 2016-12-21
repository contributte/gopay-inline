<?php

namespace Markette\GopayInline\Http;

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

	/** @var string */
	protected $error;

	/**
	 * @return array
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
		if (!is_bool($data) && $data) {
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
	 * @param int $code
	 * @return void
	 */
	public function setCode($code)
	{
		$this->code = intval($code);
	}

	/**
	 * @return string
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
		return $this->error == FALSE;
	}

	/**
	 * ARRAY ACCESS ************************************************************
	 */

	/**
	 * @param string $offset
	 * @return bool
	 */
	public function offsetExists($offset)
	{
		if ($this->data) {
			return isset($this->data[$offset]);
		}

		return FALSE;
	}

	/**
	 * @param string $offset
	 * @return mixed
	 */
	public function offsetGet($offset)
	{
		if (!$this->data) return NULL;

		return $this->data[$offset];
	}

	/**
	 * @param string $offset
	 * @param mixed $value
	 * @return void
	 */
	public function offsetSet($offset, $value)
	{
		if ($this->data) {
			$this->data[$offset] = $value;
		}
	}

	/**
	 * @param string $offset
	 * @return void
	 */
	public function offsetUnset($offset)
	{
		if ($this->data) {
			unset($this->data[$offset]);
		}
	}

	/**
	 * @return int
	 */
	public function count()
	{
		return count($this->data);
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
		} else {
			return $this->offsetGet($name);
		}
	}

}
