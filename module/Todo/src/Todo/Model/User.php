<?php
namespace Todo\Model;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class User
	implements InputFilterAwareInterface
{
	public $user_sid;
	public $email;
	public $password;

	protected $inputFilter;

	public function exchangeArray($data)
	{
		$exchangeFields = array(
			'user_sid',
			'email',
			'password',
		);
		foreach($exchangeFields as $v) {
			$this->$v     = (!empty($data[$v])) ? $data[$v] : null;
		}
	}

	public function getArrayCopy()
	{
		return get_object_vars($this);
	}

	public function setInputFilter(InputFilterInterface $inputFilter)
	{
		throw new \Exception("Not used");
	}

	public function getInputFilter()
	{
		if (!$this->inputFilter) {
			$inputFilter = new InputFilter();

			$inputFilter->add(array(
				'name'     => 'user_sid',
				'required' => true,
				'filters'  => array(
					array('name' => 'Int'),
				),
			));

			$inputFilter->add(array(
				'name'     => 'email',
				'required' => true,
				'filters'  => array(
					array('name' => 'StripTags'),
					array('name' => 'StringTrim'),
				),
				'validators' => array(
					array('name'    => 'EmailAddress'),
				),
			));

			$inputFilter->add(array(
				'name'     => 'password',
				'required' => true,
				'validators' => array(
					array(
						'name'    => 'StringLength',
						'options' => array(
							'encoding' => 'UTF-8',
							'min'      => 6,
							'max'      => 50,
						),
					),
				),
			));

			$this->inputFilter = $inputFilter;
		}

		return $this->inputFilter;
	}
}
