<?php

namespace Cxb\GuzzleHttp;

/**
 *
 * Class Request
 * @package App\Component\Http
 */
 class RequestHttp
{
    public $url;
    public $headers;
    public $body;
    public $method;

    /**
     *
     * Request constructor.
     * @param $method
     * @param $url
     * @param null $body
     * @param array $headers
     */
    public function __construct($method, $url, $body = null,array $headers = array()) {
        $this->method=strtoupper($method);
        $this->url=$url;
        $this->body=$body;
        $this->headers=$headers;
    }

    /**
     *
     * @return array|false|int|string|null
     */
    public function getParseUrl(){
        return parse_url($this->url);
    }

    /**
     * @return mixed|string|null
     */
    public function getQuery(){
        return $this->getParseUrl()['query']??null;
    }

    /**
     * @return mixed|string|null
     */
    public function getPath(){
        return $this->getParseUrl()['path']??null;
    }

    /**
     * @return float|int
     */
    public function getTimeOut(){
        return 6*60;
    }

    /**
     * @return mixed|string|null
     */
    public function getHost(){
        return $this->getParseUrl()['host']??null;
    }
    /**
     * 获取端口号
     */
    public function getPort(){
        $data=$this->getParseUrl();
        return isset($data['port'])&&!empty($data['port'])?$data['port']:(($data['scheme']??'http')=='https'?443:80);
    }
}