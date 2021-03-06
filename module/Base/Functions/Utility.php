<?php
namespace Base\Functions;


use Base\ConstDir\Regexp;
use Zend\Debug\Debug;
use Zend\Validator\Regex;

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

    public static function curl($uri, $data = array(), $method = 'post')
    {



        if(empty($uri)) return false;

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
        return mb_substr(strip_tags($str), 0, $length, "UTF-8");
    }

    public static function saveBaseCodePic($pic){
        $picPath = '';
        if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $pic, $result)) {
            $type = $result[2];
            $picPath = "/uploads/store-verify/". uniqid() . '.' . $type;
            $path = BaseRootPath . $picPath;
            file_put_contents($path, base64_decode(str_replace($result[1], '', $pic)));
        }else{
            $picPath = $pic;
        }

        return $picPath;
    }

    public static function getLeftTime($startTime, $endTime){
        if(strlen($startTime) != 10){
            $startTime = strtotime($startTime);
            $endTime = strtotime($endTime);
        }
        $strtime = '';
        $time = $endTime - $startTime;

        if($time >= 86400){
            $strtime .= intval($time / 86400).'天';
            $time = $time % 86400;
        }else{
            $strtime .= '';
        }

        if($time >= $strtime){
            $strtime .= intval($time / 3600).'小时';
            $time = $time % 3600;
        }else{
            $strtime .= '';
        }
        if($time >= 60){
            $strtime .= intval($time / 60).'分钟';
            $time = $time % 60;
        }else{
            $strtime .= '';
        }
        if($time > 0){
            $strtime .= intval($time).'秒';
        }


        return $strtime;
    }

    public static function getBodyText($data){

        $data = htmlspecialchars_decode($data);
        $data = str_replace(array('img{width:100%!important}', ' '), '', $data);
        //preg_match_all(Regexp::BODY_CONTENT, $data, $tmp);
        //$data = $tmp[0][0];
        return strip_tags($data);


    }

    public static function getMemberArticleText($data){
        preg_match_all(Regexp::MEMBER_ARTICLE_CONTENT, $data, $tmp);
        $tmp = strip_tags($tmp[0][0]);
        $tmp = str_replace(' ', '', $tmp);
        return $tmp;
    }

    public static function getPrivateNickName($nickName){
        $count = mb_strlen($nickName, 'UTF-8') - 2;
        $nickName = mb_substr($nickName, 0, 1, 'UTF-8');
        $last = '';
        if($count >= 1){
            $last = mb_substr($nickName, -1, 1, 'UTF-8');
        }


        for($i = 0; $i <= $count; $i++){
            $nickName .= '*';
        }
        $nickName .= $last;


        return $nickName;
    }

    public static function toXml($data)
    {

        $xml = "<xml>";
        foreach ($data as $key=>$val)
        {
            if (is_numeric($val)){
                $xml.="<".$key.">".$val."</".$key.">";
            }else{
                $xml.="<".$key."><![CDATA[".$val."]]></".$key.">";
            }
        }
        $xml.="</xml>";
        return $xml;
    }

    public static function decodeXml($xml){

        libxml_disable_entity_loader(true);
        $data = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);

        return $data;
    }

    public static function toUrlParams($data){
        $buff = "";
        foreach ($data as $k => $v)
        {
            if($k != "sign" && $v != "" && !is_array($v)){
                $buff .= $k . "=" . $v . "&";
            }
        }

        $buff = trim($buff, "&");
        return $buff;
    }

    public static function returnXml($data){
        header("Content-Type:application/xml;Charset=utf-8");
        $data = Utility::toXml($data);
        die(urldecode($data));
    }

    public static function getImgs($content){
        preg_match_all('/<img\ssrc="([^"]*)"/', $content, $matches);

        return $matches[1];
    }

    public static function getThumb($path, $pixel){
        if(empty($path)) return false;
        $fileName = strstr($path, '.', true);
        $ext = strstr($path, '.');
        if(file_exists(BaseRootPath . $fileName . $pixel . $ext)){
            return $fileName . $pixel . $ext;
        }else{
            return $path;
        }
    }
}
