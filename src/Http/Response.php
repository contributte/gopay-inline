<?php

namespace Markette\GopayInline\Http;

use stdClass;

class Response
{

    /** @var bool|NULL|stdClass */
    protected $data;

    /** @var stdClass */
    protected $headers;

    /** @var int */
    protected $code;

    /** @var string */
    protected $error;

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed $data
     */
    public function setData($data)
    {
        if (!is_bool($data) && $data !== NULL) {
            $data = (object)$data;
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
     */
    public function setHeaders($headers)
    {
        $this->headers = (object)$headers;
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
     */
    public function setError($error)
    {
        $this->error = $error;
    }

}
