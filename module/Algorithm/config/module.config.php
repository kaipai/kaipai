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
    'router' => array(
        'routes' => array(
            'algorithm' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/algorithm',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Algorithm\Controller',
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
                                '__NAMESPACE__' => 'Algorithm\Controller',
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
                'sort-index-name' => array(
                    'options' => array(
                        'route' => 'sort-index',
                        'defaults' => array(
                            '__NAMESPACE__' => 'Algorithm\Controller',
                            'controller' => 'Sort',
                            'action' => 'index'
                        )
                    )
                ),
                'basis-index-name' => array(
                    'options' => array(
                        'route' => 'basis-index',
                        'defaults' => array(
                            '__NAMESPACE__' => 'Algorithm\Controller',
                            'controller' => 'Basis',
                            'action' => 'index'
                        )
                    )
                ),
            )
        )
    ),
);
