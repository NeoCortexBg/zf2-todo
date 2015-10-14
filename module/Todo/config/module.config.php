<?php

return array(
	'controllers' => array(
		'invokables' => array(
			'Todo\Controller\Todo' => 'Todo\Controller\TodoController',
			'Todo\Controller\Project' => 'Todo\Controller\ProjectController',
			'Todo\Controller\Login' => 'Todo\Controller\LoginController',
		),
	),
	'router' => array(
		'routes' => array(
			'todo' => array(
				'type'    => 'segment',
				'options' => array(
					'route'    => '/[todo[/:action][/:id]]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id'     => '[0-9]+',
					),
					'defaults' => array(
						'controller' => 'Todo\Controller\Todo',
						'action'     => 'index',
					),
				),
			),
			'project' => array(
				'type'    => 'segment',
				'options' => array(
					'route'    => '/project[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id'     => '[0-9]+',
					),
					'defaults' => array(
						'controller' => 'Todo\Controller\Project',
						'action'     => 'index',
					),
				),
			),
			'login' => array(
				'type'    => 'literal',
				'options' => array(
					'route'    => '/login',
					'defaults' => array(
						'controller' => 'Todo\Controller\Login',
						'action'     => 'login',
					),
				),
			),
			'logout' => array(
				'type'    => 'literal',
				'options' => array(
					'route'    => '/logout',
					'defaults' => array(
						'controller' => 'Todo\Controller\Login',
						'action'     => 'logout',
					),
				),
			),
		),
	),
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        ),
        'factories' => array(
            'translator' => 'Zend\Mvc\Service\TranslatorServiceFactory',
        ),
    ),
    'translator' => array(
        'locale' => 'en_US',
        'translation_file_patterns' => array(
            array(
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
            ),
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
//            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
            'todo/form'           => __DIR__ . '/../view/partial/todo_form.phtml',
            'todo/form_filter'           => __DIR__ . '/../view/partial/todo_filter_form.phtml',
        ),
        'template_path_stack' => array(
			'todo' => __DIR__ . '/../view',
        ),
    ),
);
