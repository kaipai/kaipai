<?php

return array(
    'router' => array(
        'routes' => array(
            'base' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Front\Controller',
                        'controller' => 'Index',
                        'action' => 'index',
                    ),
                ),
                'child_routes' => array(
                    'default' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '[:controller][/:action]',
                            'constraints' => array(
                                '__NAMESPACE__' => 'Front\Controller',
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'console' => array(
        'router' => array(
            'routes' => array(
                'crontab-auction' => array(
                    'options' => array(
                        'route' => 'crontab-auction',
                        'defaults' => array(
                            '__NAMESPACE__' => 'Front\Controller',
                            'controller' => 'Crontab',
                            'action' => 'auction'
                        )
                    )
                ),
                'crontab-products-on' => array(
                    'options' => array(
                        'route' => 'crontab-products-on',
                        'defaults' => array(
                            '__NAMESPACE__' => 'Front\Controller',
                            'controller' => 'Crontab',
                            'action' => 'products-on'
                        )
                    )
                ),
                'crontab-specials-on' => array(
                    'options' => array(
                        'route' => 'crontab-specials-on',
                        'defaults' => array(
                            '__NAMESPACE__' => 'Front\Controller',
                            'controller' => 'Crontab',
                            'action' => 'specials-on'
                        )
                    )
                ),
                'crontab-specials-off' => array(
                    'options' => array(
                        'route' => 'crontab-specials-off',
                        'defaults' => array(
                            '__NAMESPACE__' => 'Front\Controller',
                            'controller' => 'Crontab',
                            'action' => 'specials-off'
                        )
                    )
                ),
            )
        )
    ),
    'view_manager' => array(
        'doctype' => 'HTML5',
        'display_not_found_reason' => true,
        'display_exceptions' => true,
        'not_found_template' => 'error/404',
        'exception_template' => 'error/index',
        'template_map' => array(
            'frontLayout' => __DIR__ . '/../view/layout/front.phtml',
            'frontMemberLayout' => __DIR__ . '/../view/layout/member.phtml'
        ),
        'template_path_stack' => array(
            'front' => __DIR__ . '/../view'
        ),
    ),
);
