<?php

namespace Cxb\GuzzleHttp;

/**
 *
 * Class Request
 * @package App\Component\Http
 */
final class RequestClient
{
    public $url;
    public $headers;
    public $body;
    public $method;
    public $request_id;
    /**
     *
     * Request constructor.
     * @param $method
     * @param $url
     * @param null $body
     * @param array $headers
     */
    public function __construct($method, $url, $body = null, array $headers = array())
    {
        $this->method = strtoupper($method);
        $this->url = $url;
        $this->body = $body;
        $this->headers = $headers;
        $this->request_id=$this->hadoopPrimaryKey('R');
    }

    /**
     *
     * @param string $prefix
     */
    public function hadoopPrimaryKey($prefix='')
    {
        $epoch=1479533469598;
        $max12bit = 4095;
        $max41bit = 1099511627775;
        $machineId=mt_rand(1,999);//获取当前进程ID
        $time = floor(microtime(true) * 1000);
        $time-=$epoch;
        $base = decbin($max41bit+ $time);
        $machineid = str_pad(decbin($machineId), 10, "0", STR_PAD_LEFT);
        $random = str_pad(decbin(mt_rand(0, $max12bit)), 12, "0", STR_PAD_LEFT);
        $base = $base.$machineid.$random;
        return $prefix.((string)bindec($base));
    }

    /**
     *
     * @return array|false|int|string|null
     */
    public function getParseUrl()
    {
        return parse_url($this->url);
    }

    /**
     * @return mixed|string|null
     */
    public function getQuery()
    {
        return $this->getParseUrl()['query'] ?? null;
    }

    /**
     * @return mixed|string|null
     */
    public function getPath()
    {
        return $this->getParseUrl()['path'] ?? null;
    }

    /**
     * 请求超时时间
     * @return float|int
     */
    public function getTimeOut()
    {
        return 6 * 60;
    }

    /**
     * @return mixed|string|null
     */
    public function getHost()
    {
        return $this->getParseUrl()['host'] ?? null;
    }

    /**
     * 获取端口号
     */
    public function getPort()
    {
        $data = $this->getParseUrl();
        return isset($data['port']) && !empty($data['port']) ? $data['port'] : (($data['scheme'] ?? 'http') == 'https' ? 443 : 80);
    }
}