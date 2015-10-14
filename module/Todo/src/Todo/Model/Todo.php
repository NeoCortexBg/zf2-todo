<?php
namespace Todo\Model;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Todo
	implements InputFilterAwareInterface
{
	public $todo_sid;
	public $project_sid;
	public $priority;
	public $todo_status_sid;
	public $text;
	public $date_created;
	public $status;

	protected $inputFilter;

	public function exchangeArray($data)
	{
		$exchangeFields = array(
			'todo_sid',
			'project_sid',
			'priority',
			'todo_status_sid',
			'text',
			'date_created',

			'status',
		);
		foreach($exchangeFields as $v) {
			$this->$v = (array_key_exists($v, $data)) ? $data[$v] : null;
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
				'name'     => 'todo_sid',
				'required' => true,
				'filters'  => array(
					array('name' => 'Int'),
				),
			));

			$inputFilter->add(array(
				'name'     => 'project_sid',
				'required' => false,
				'filters'  => array(
					array('name' => 'Int'),
					array('name' => 'Null'),
				),
			));

			$inputFilter->add(array(
				'name'     => 'priority',
				'required' => true,
				'filters'  => array(
					array('name' => 'Int'),
				),
			));

			$inputFilter->add(array(
				'name'     => 'todo_status_sid',
				'required' => true,
				'filters'  => array(
					array('name' => 'Int'),
				),
			));

			$inputFilter->add(array(
				'name'     => 'text',
				'required' => true,
				'filters'  => array(
					array('name' => 'StripTags'),
					array('name' => 'StringTrim'),
				),
				'validators' => array(
					array(
						'name'    => 'StringLength',
						'options' => array(
							'encoding' => 'UTF-8',
							'min'      => 1,
							'max'      => 1000,
						),
					),
				),
			));

			$this->inputFilter = $inputFilter;
		}

		return $this->inputFilter;
	}
}
