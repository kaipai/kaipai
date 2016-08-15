<?php
namespace Base\Cache;

class Redis extends \Zend\Cache\Storage\Adapter\Redis{

	public function __construct($options)
    {
        parent::__construct($options);
    }

    /**
     * 获取redis资源对象
     *
     */
    public function getRedisObj()
    {
        /** @var \Redis $redis */
        $redis = $this->getRedisResource();
        return $redis;
    }
}