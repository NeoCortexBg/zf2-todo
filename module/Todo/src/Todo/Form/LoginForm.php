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
			'attributes' => array(
				'class' => 'form-control',
				'placeholder' => 'Email address',
			),
		));
		$this->add(array(
			'name' => 'password',
			'type' => 'Password',
			'options' => array(
				'label' => 'Password',
			),
			'attributes' => array(
				'class' => 'form-control',
				'placeholder' => 'Password',
			),
		));
		$this->add(array(
			'name' => 'submit',
			'type' => 'Submit',
			'attributes' => array(
				'class' => 'btn btn-lg btn-primary btn-block',
			),
		));
	}
}
