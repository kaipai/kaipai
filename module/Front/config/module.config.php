<?php

return array(
    'controller_plugins' => array(
        'invokables' => array(
        )
    ),
    'router' => array(
        'routes' => array(
            'front' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/front',
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
                            'route' => '/[:controller][/:action]',
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
    'view_manager' => array(
        'doctype' => 'HTML5',
        'display_not_found_reason' => true,
        'display_exceptions' => true,
        'not_found_template' => 'error/404',
        'exception_template' => 'error/index',
        'template_map' => array(
            'frontLayout' => __DIR__ . '/../view/layout/front.phtml'
        ),
        'template_path_stack' => array(
            'front' => __DIR__ . '/../view'
        ),
    ),
);
