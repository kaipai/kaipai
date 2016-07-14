<?php
return array(
    'controller_plugins' => array(
        'invokables' => array(
        )
    ),
    'service_manager' => array(
    ),
    'view_manager' => array(
    ),
    'console' => array(
        'router' => array(
            'routes' => array(
                'test-index' => array(
                    'options' => array(
                        'route' => 'test-index',
                        'defaults' => array(
                            '__NAMESPACE__' => 'Front\Controller',
                            'controller' => 'Test',
                            'action' => 'index'
                        )
                    )
                ),
                'test-cache' => array(
                    'options' => array(
                        'route' => 'test-cache',
                        'defaults' => array(
                            '__NAMESPACE__' => 'Front\Controller',
                            'controller' => 'Test',
                            'action' => 'cache'
                        )
                    )
                ),
            )
        )
    ),

);
