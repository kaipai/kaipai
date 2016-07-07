<?php
namespace COM\Service;

use Base\Functions\Utility;
use Zend\ServiceManager\ServiceManager;
use Zend\ServiceManager\ServiceManagerAwareInterface;

class SmsService implements ServiceManagerAwareInterface
{
    protected $sm;

    public function sendSMS($mobile, $content)
    {
        $this->config = $this->sm->get('Config');
        if ($this->config['sms']['type'] == 1) {
            $res = $this->luosimao($mobile, $content);
            $apiResult = "[" . $this->config['sms']['luosimao']['name'] . "]" . implode(',', $res);
            $status = $res['error'] === 0 ? 1 : 0;
        } elseif ($this->config['sms']['type'] == 2) {
            $mobile = is_array($mobile) ? implode(",", $mobile) : $mobile;
            $content = str_replace(array('【', '】'), '', $content);
            $content = urlencode($content);
            $query = "apikey=" . $this->config['sms']['yunpian']['apikey'] . "&mobile={$mobile}&text={$content}";
            $res = $this->yunpian($mobile, $query);
            $apiResult = "[" . $this->config['sms']['yunpian']['name'] . "]" . $res['code'] . "," . $res['msg'];
            if ($res['code'] == 0) $apiResult .= "," . $res['result']['sid'];
            $content = urldecode($content);
            $status = $res['code'] === 0 ? 1 : 0;
        } else {
            return 'select send type';
        }
        $logData = array(
            'mobile' => $mobile,
            'content' => $content,
            'status' => $status,
            'apiResult' => $apiResult,
        );
        $this->sm->get('Base\Model\SmsLog')->insert($logData);
        return $res;
    }

    /**
     * Set service manager
     *
     * @param ServiceManager $serviceManager
     */
    public function setServiceManager(ServiceManager $serviceManager)
    {
        // TODO: Implement setServiceManager() method.
        $this->sm = $serviceManager;
    }

    /**
     * @description 螺丝帽发送sms
     */
    public function luosimao($mobile, $content)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://sms-api.luosimao.com/v1/send.json");

        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);

        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, 'api:key-');

        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, array('mobile' => $mobile, 'message' => $content . "【蚂蚁车管家】"));

        $res = curl_exec($ch);
        curl_close($ch);
        $res = json_decode($res, true);
        return $res;
    }

    /**
     * @descrption 云片发送sms
     */
    public function yunpian($mobile, $query)
    {
        $data = "";
        $url = $this->config['sms']['yunpian']['url'];
        $info = parse_url($url);
        $fp = fsockopen($info["host"], 80, $errno, $errstr, 30);
        if (!$fp) {
            return $data;
        }
        $head = "POST " . $info['path'] . " HTTP/1.0\r\n";
        $head .= "Host: " . $info['host'] . "\r\n";
        $head .= "Referer: http://" . $info['host'] . $info['path'] . "\r\n";
        $head .= "Content-type: application/x-www-form-urlencoded\r\n";
        $head .= "Content-Length: " . strlen(trim($query)) . "\r\n";
        $head .= "\r\n";
        $head .= trim($query);
        $write = fputs($fp, $head);
        $header = "";
        while ($str = trim(fgets($fp, 4096))) {
            $header .= $str;
        }
        while (!feof($fp)) {
            $data .= fgets($fp, 4096);
        }
        return json_decode($data, true);
    }
}
