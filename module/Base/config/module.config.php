<?php
return array(
    'controller_plugins' => array(
        'invokables' => array(
        )
    ),
    'service_manager' => array(
        'invokables' => array(
            'logger-factory' => 'Base\Delegator\LoggerFactory',
            'logger' => 'Base\Delegator\Logger',

        ),
        'delegators' => array(
            'logger' => array(
                'logger-factory'
            ),
        ),
        'initializers' => array(
            'Base\Services\Initializers\Db'
        ),
        'factories' => array(
            'Zend\Db\Adapter\Adapter' => 'Zend\Db\Adapter\AdapterServiceFactory',
            'Base\Cache\Redis' => 'Base\Cache\RedisCacheFactory',
            'COM\Service\BaiduPushService'=>function($sm){
                $bdService  = new COM\Service\BaiduPushService();
                $bdService->setConfig($sm);
                return $bdService;
            },
             'Base\Cache\File' => function($sm){
                 $fileCacheService = new Base\Cache\FileCache($sm);
                 return $fileCacheService;
            },
        )
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions' => true,
        'doctype' => 'HTML5',
        'not_found_template' => 'error/404',
        'exception_template' => 'error/index',
        'template_path_stack' => array(
            'base' => __DIR__ . '/../view',
        ),
    ),

);
