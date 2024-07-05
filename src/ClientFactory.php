<?php


namespace Cxb\GuzzleHttp;

/**
 *
 * Class ClientFactory
 * @package Cxb\HyperfDingTalk\Http
 */
final class ClientFactory
{
    /**
     * get请求
     * @param string $url
     * @param array $headers
     */
    public static function get(string $url,array $headers=[]){
        $request=new RequestClient('get',$url,null,$headers);
        return self::send($request);
    }
    /**
     * @param $url
     * @param null $body
     * @param array $headers
     */
    public static function post($url,$body=null,array $headers=[]){
        $request=new RequestClient('post',$url,$body,$headers);
        return self::send($request);
    }
    /**
     *
     * @param RequestClient $request
     * @return ResponseClient
     */
    public static function send(RequestClient $request):ResponseClient {
        $t1 = microtime(true);//执行开始时间
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT,10);
        curl_setopt($ch, CURLOPT_TIMEOUT,$request->getTimeOut());
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
        curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
        curl_setopt($ch, CURLOPT_URL, $request->url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $request->method);
        curl_setopt($ch, CURLOPT_POST, $request->method == 'POST' ? 1 : 0);
        if (!empty($request->headers)) {
            $headers = [];
            foreach ($request->headers as $key => $val) {
                array_push($headers, "$key: $val");
            }
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }
        if (!empty($request->body)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $request->body);

        }
        $result = curl_exec($ch);
        $duration = round(microtime(true) - $t1, 3);
        $ret = curl_errno($ch);
        if ($ret !== 0) {
            $r = new ResponseClient(-1, $duration, array(), null, 'curl 请求'.curl_error($ch),$request);
        }else{
            $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $r = new ResponseClient($code, $duration, $request->headers, $result, null,$request);
        }
        curl_close($ch);
        return $r;
    }
}