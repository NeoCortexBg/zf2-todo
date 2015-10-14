<?php
namespace Todo\Form;

use Zend\Form\Form;

class LoginForm extends Form
{
	public function __construct()
	{
		parent::__construct('login');

		$this->add(array(
			'name' => 'user_sid',
			'type' => 'Hidden',
		));
		$this->add(array(
			'name' => 'email',
			'type' => 'Text',
			'options' => array(
				'label' => 'Email',
			),
		));
		$this->add(array(
			'name' => 'password',
			'type' => 'Password',
			'options' => array(
				'label' => 'Password',
			),
		));
		$this->add(array(
			'name' => 'submit',
			'type' => 'Submit',
			'attributes' => array(
				'value' => 'login',
				'id' => 'submitbutton',
			),
		));
	}
}
