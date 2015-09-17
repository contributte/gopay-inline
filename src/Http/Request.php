<?php

namespace Markette\GopayInline\Http;

class Request
{

    /** @var string */
    protected $url;

    /** @var array */
    protected $headers = [];

    /** @var array */
    protected $opts = [];

    /** @var array */
    protected $data = [];

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @param array $headers
     */
    public function setHeaders(array $headers)
    {
        $this->headers = $headers;
    }

    /**
     * @param string $name
     * @param string $value
     */
    public function addHeader($name, $value)
    {
        $this->headers[$name] = $value;
    }

    /**
     * @param array $headers
     */
    public function appendHeaders(array $headers)
    {
        $this->headers += $headers;
    }

    /**
     * @return array
     */
    public function getOpts()
    {
        return $this->opts;
    }

    /**
     * @param array $opts
     */
    public function setOpts(array $opts)
    {
        $this->opts = $opts;
    }

    /**
     * @param string $name
     * @param string $value
     */
    public function addOpt($name, $value)
    {
        $this->opts[$name] = $value;
    }

    /**
     * @param array $opts
     */
    public function appendOpts(array $opts)
    {
        $this->opts += $opts;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param array $data
     */
    public function setData(array $data)
    {
        $this->data = $data;
    }

}
