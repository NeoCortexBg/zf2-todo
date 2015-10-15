<?php
namespace Todo;

require_once __DIR__ . '/debug.php';

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Todo\Model\Todo;
use Todo\Model\TodoTable;
use Todo\Model\TodoStatus;
use Todo\Model\TodoStatusTable;
use Todo\Model\Project;
use Todo\Model\ProjectTable;
use Todo\Model\User;
use Todo\Model\UserTable;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\ModuleRouteListener;

class Module implements AutoloaderProviderInterface, ConfigProviderInterface
{
	public function getAutoloaderConfig()
	{
		return array(
			'Zend\Loader\ClassMapAutoloader' => array(
				__DIR__ . '/autoload_classmap.php',
			),
			'Zend\Loader\StandardAutoloader' => array(
				'namespaces' => array(
					__NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
				),
			),
		);
	}

	public function getConfig()
	{
		return include __DIR__ . '/config/module.config.php';
	}

	public function getServiceConfig()
	{
		return array(
			'factories' => array(
				'Todo\Model\TodoTable' =>  function($sm) {
					$tableGateway = $sm->get('TodoTableGateway');
					$table = new TodoTable($tableGateway);
					return $table;
				},
				'TodoTableGateway' => function ($sm) {
					$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
					$resultSetPrototype = new ResultSet();
					$resultSetPrototype->setArrayObjectPrototype(new Todo());
					return new TableGateway('todo', $dbAdapter, null, $resultSetPrototype);
				},
				'Todo\Model\TodoStatusTable' =>  function($sm) {
					$tableGateway = $sm->get('TodoStatusTableGateway');
					$table = new TodoStatusTable($tableGateway);
					return $table;
				},
				'TodoStatusTableGateway' => function ($sm) {
					$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
					$resultSetPrototype = new ResultSet();
					$resultSetPrototype->setArrayObjectPrototype(new TodoStatus());
					return new TableGateway('todo_status', $dbAdapter, null, $resultSetPrototype);
				},
				'Todo\Model\ProjectTable' =>  function($sm) {
					$tableGateway = $sm->get('ProjectTableGateway');
					$table = new ProjectTable($tableGateway);
					return $table;
				},
				'ProjectTableGateway' => function ($sm) {
					$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
					$resultSetPrototype = new ResultSet();
					$resultSetPrototype->setArrayObjectPrototype(new Project());
					return new TableGateway('project', $dbAdapter, null, $resultSetPrototype);
				},
				'Todo\Model\UserTable' =>  function($sm) {
					$tableGateway = $sm->get('UserTableGateway');
					$table = new UserTable($tableGateway);
					return $table;
				},
				'UserTableGateway' => function ($sm) {
					$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
					$resultSetPrototype = new ResultSet();
					$resultSetPrototype->setArrayObjectPrototype(new User());
					return new TableGateway('user', $dbAdapter, null, $resultSetPrototype);
				},
				'AuthenticationService' => function ($sm) {
					$auth = new AuthenticationService();
					$auth->setStorage(new SessionStorage('loggedInUser'));
					return $auth;
				},
			),
		);
	}

	public function onBootstrap(MvcEvent $e)
	{
		$e->getApplication()->getEventManager()->attach('dispatch', array($this, 'authorize'), 100);
	}

	public function authorize(MvcEvent $e)
	{
		if(!in_array($e->getRouteMatch()->getMatchedRouteName(), array('login', 'logout'))
			&& !$e->getApplication()->getServiceManager()->get("AuthenticationService")->hasIdentity()) {
			$response = $e->getResponse();
			$response->getHeaders()->addHeaderLine('Location', '/login');
			$response->setStatusCode(302);

			return $response;
		}
	}
}
