<?php
namespace Base\Delegator;


class Logger {

    function log($info){
        $logger = new \Zend\Log\Logger;
        $writer = new \Zend\Log\Writer\Stream(ROOT.'/module/Base/Logs/error_' . date('Y-m-d') . '.log');
        $logger->addWriter($writer);
        $logger->crit($info);
        return $logger;
    }
}