<?php
namespace Base\Functions;


use Zend\Debug\Debug;

class Utility
{
    public static function recreateIndex($source, $fieldName, $multi = false)
    {
        $data = array();
        foreach ($source as $k => $item) {
            if (isset($item[$fieldName])) {
                ($multi == false) ? $data[$item[$fieldName]] = $source[$k] : $data[$item[$fieldName]][] = $source[$k];
            }

        }
        return $data;
    }

    public static function dump($var, $willDie = false)
    {
        Debug::dump($var, null, true);
        if ($willDie)
            die;
    }

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

    public static function getRandCode($length)
    {
        $chars = 'abcdefghijklmnopqrstuvwxyz0123456789';
        $code = '';
        for ($i = 0; $i < $length; $i++) {
            $code .= $chars[mt_rand(0, strlen($chars) - 1)];
        }
        return $code;
    }

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

    public static function genSignature($data){
        $signature = 'sign:';
        if(!empty($data)){
            foreach($data as $k => $v){
                $signature .= $k . $v;
            }
        }
        return md5($signature);
    }

    public static function keyFilter($source, $keys){
        $result = array();
        if(empty($source)){
            return $result;
        }
        if(!empty($keys)){
            foreach($source as $k => $v){
                if(is_array($v) && is_numeric($k)){
                    foreach($v as $sk => $sv){
                        if(in_array($sk, $keys)){
                            $tmp[$sk] = $sv;
                        }
                    }
                    if(!empty($tmp)){
                        $result[] = $tmp;
                    }
                }else{
                    if(in_array($k, $keys)) {
                        $tmp2[$k] = $v;
                    }
                }
            }
            if(!empty($tmp2)){
                $result[] = $tmp2;
            }
        }else{
            return $source;
        }

        return $result;
    }

    public static function mbCutStr($str, $length = 1){
        return mb_substr($str, 0, $length, "UTF-8");
    }

    public static function saveBaseCodePic($pic){
        $picPath = '';
        if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $pic, $result)) {
            $type = $result[2];
            $picPath = "/uploads/store-verify/". uniqid() . '.' . $type;
            $path = BaseRootPath . $picPath;
            file_put_contents($path, base64_decode(str_replace($result[1], '', $pic)));
        }

        return $picPath;
    }
}
