<?php

return array(
    'controller_plugins' => array(
        'invokables' => array(
        )
    ),
    'router' => array(
        'routes' => array(
            'api' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/api',
                ),
                'child_routes' => array(
                    'v1' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/[:version][/:controller][/:action]',
                            'constraints' => array(
                                'version' => 'v1',
                                'controller' => '[a-zA-Z0-9_-]+',
                                'action' => '[a-zA-Z0-9_-]+',
                            ),
                            'defaults' => array(
                                '__NAMESPACE__' => 'Api\Controller\v1',
                                'controller' => 'Api\Controller\v1\Index',
                                'action' => 'index'
                            )
                        )
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'doctype' => 'HTML5',
        'template_map' => array(
        ),
        'template_path_stack' => array(
        ),
    ),
);
