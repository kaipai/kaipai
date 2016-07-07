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
                'crontab-interest-product-start' => array(
                    'options' => array(
                        'route' => 'crontab-interest-product-start',
                        'defaults' => array(
                            '__NAMESPACE__' => 'Front\Controller',
                            'controller' => 'Crontab',
                            'action' => 'interest-product-start'
                        )
                    )
                ),
                'crontab-interest-product-end' => array(
                    'options' => array(
                        'route' => 'crontab-interest-product-end',
                        'defaults' => array(
                            '__NAMESPACE__' => 'Front\Controller',
                            'controller' => 'Crontab',
                            'action' => 'interest-product-end'
                        )
                    )
                ),
                'crontab-del-unsold-product' => array(
                    'options' => array(
                        'route' => 'crontab-del-unsold-product',
                        'defaults' => array(
                            '__NAMESPACE__' => 'Front\Controller',
                            'controller' => 'Crontab',
                            'action' => 'del-unsold-product'
                        )
                    )
                ),
                'crontab-del-over-time-special' => array(
                    'options' => array(
                        'route' => 'crontab-del-over-time-special',
                        'defaults' => array(
                            '__NAMESPACE__' => 'Front\Controller',
                            'controller' => 'Crontab',
                            'action' => 'del-over-time-special'
                        )
                    )
                ),
                'crontab-proxy-price-invalid' => array(
                    'options' => array(
                        'route' => 'crontab-proxy-price-invalid',
                        'defaults' => array(
                            '__NAMESPACE__' => 'Front\Controller',
                            'controller' => 'Crontab',
                            'action' => 'proxy-price-invalid'
                        )
                    )
                ),
                'crontab-cancel-order' => array(
                    'options' => array(
                        'route' => 'crontab-cancel-order',
                        'defaults' => array(
                            '__NAMESPACE__' => 'Front\Controller',
                            'controller' => 'Crontab',
                            'action' => 'cancel-order'
                        )
                    )
                ),
                'crontab-confirm-delivery-done' => array(
                    'options' => array(
                        'route' => 'crontab-confirm-delivery-done',
                        'defaults' => array(
                            '__NAMESPACE__' => 'Front\Controller',
                            'controller' => 'Crontab',
                            'action' => 'confirm-delivery-done'
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
