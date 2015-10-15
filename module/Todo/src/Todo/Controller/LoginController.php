<?php
namespace Todo\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Todo\Form\LoginForm;
use Todo\Model\User;
use Zend\Authentication\Adapter\DbTable as AuthAdapter;

class LoginController extends AbstractActionController
{
	protected $auth;

	public function loginAction()
	{
		$auth = $this->getAuth();
		if($auth->hasIdentity()) {
			return $this->redirect()->toRoute('todo');
		}

		$form = new LoginForm();
		$form->get('submit')->setValue('Add');

		$request = $this->getRequest();
		if ($request->isPost()) {
			$user = new User();
			$form->setInputFilter($user->getInputFilter());
			$form->setData($request->getPost());

			if ($form->isValid()) {
				$data = $form->getData();

				$authAdapter = new AuthAdapter($this->getServiceLocator()->get('Zend\Db\Adapter\Adapter'),
												'user',
												'email',
												'password',
												'MD5(?)'
											   );

				$authAdapter
					->setIdentity($data['email'])
					->setCredential($data['password']);

				$auth->authenticate($authAdapter);

				return $this->redirect()->toRoute('todo');
			}
		}
		return array('form' => $form);
	}

	public function logoutAction()
	{
		$auth = $this->getAuth();
		$auth->clearIdentity();
		return $this->redirect()->toRoute('login');
	}

	private function getAuth()
	{
		if(!$this->auth) {
			$this->auth = $this->getServiceLocator()->get('AuthenticationService');
		}

		return $this->auth;
	}

}