<?php


namespace COM\Service;

include 'phpqrcode/phpqrcode.php';

class QrcodeService{

    public function png($url){
        $fileName = time() . '.png';
        \QRcode::png($url, BaseRootPath . '/qrcode/' . $fileName, 0, 6);

        return $fileName;
    }
}