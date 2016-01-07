<?php
/**
 * Created by PhpStorm.
 * User: william
 * Date: 15/5/11
 * Time: 11:33
 */

namespace Base\Functions;


use Zend\Debug\Debug;

class Utility
{
    public static function dump($var, $willDie = false)
    {
        Debug::dump($var, null, true);
        if ($willDie)
            die;
    }

    /**
     * 取客户端ip
     * @return string
     */
    public static function getIp()
    {
        static $ip = null;
        if (!$ip) {
            if (isset ($_SERVER ['HTTP_X_FORWARDED_FOR']) && $_SERVER ['HTTP_X_FORWARDED_FOR'] && $_SERVER ['REMOTE_ADDR']) {
                if (strstr($_SERVER ['HTTP_X_FORWARDED_FOR'], ',')) {
                    $x = explode(',', $_SERVER ['HTTP_X_FORWARDED_FOR']);
                    $_SERVER ['HTTP_X_FORWARDED_FOR'] = trim(end($x));
                }
                if (preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $_SERVER ['HTTP_X_FORWARDED_FOR'])) {
                    $ip = $_SERVER ['HTTP_X_FORWARDED_FOR'];
                }
            } elseif (isset ($_SERVER ['HTTP_CLIENT_IP']) && $_SERVER ['HTTP_CLIENT_IP'] && preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $_SERVER ['HTTP_CLIENT_IP'])) {
                $ip = $_SERVER ['HTTP_CLIENT_IP'];
            }
            if (!$ip && preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $_SERVER ['REMOTE_ADDR'])) {
                $ip = $_SERVER ['REMOTE_ADDR'];
            }
            !$ip && $ip = 'Unknown';
        }
        return $ip;
    }

    /**
     * 根据ip获取所在城市
     */
    public static function getRegionFromIp($ip = null)
    {
        if (!empty($ip)) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'http://ip.taobao.com/service/getIpInfo.php?ip=' . $ip);
            curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // 内容不会直接输出, 以文件流的方式返回, 存储在$response变量中
            $response = curl_exec($ch);
            curl_close($ch);
            $response = json_decode($response, true);
            if ($response['code'] === 0) {
                return $response['data'];
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * 获取随机码
     * @param $length
     */
    public static function getRandCode($length)
    {
        $chars = 'abcdefghijklmnopqrstuvwxyz0123456789';
        $code = '';
        for ($i = 0; $i < $length; $i++) {
            $code .= $chars[mt_rand(0, strlen($chars) - 1)];
        }
        return $code;
    }

    /**
     * 异或运算
     * @param string $data
     * @param string $key
     */
    public static function simpleXor($data, $key)
    {
        $result = '';
        $i = 0;
        $data = self::mbStringToArray($data);
        foreach ($data as $v) {
            $result .= chr(ord($v) ^ ord($key[$i]));
            $i++;
            $i = $i % strlen($key);
        }

        return $result;
    }

    /**
     * 字符串转为数组
     * @param $string
     * @return array
     */
    public static function mbStringToArray($string)
    {
        $strlen = mb_strlen($string);
        while ($strlen) {
            $array[] = mb_substr($string, 0, 1, "UTF-8");

            $string = mb_substr($string, 1, $strlen, "UTF-8");

            $strlen = mb_strlen($string);
        }
        return $array;
    }

    /**
     * 发起curl请求
     * @param $uri
     * @param $data
     * @return bool|mixed
     */
    public static function curl($uri, $data, $method = 'post')
    {



        if(empty($uri) || empty($data)) return false;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $uri);
        if($method == 'post'){
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // 内容不会直接输出, 以文件流的方式返回, 存储在$response变量中
        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }

    /**
     * 根据参数生成签名
     */
    public static function genSignature($data){
        $signature = 'sign:';
        if(!empty($data)){
            foreach($data as $k => $v){
                $signature .= $k . $v;
            }
        }
        return md5($signature);
    }
}
