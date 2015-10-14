<?php
namespace Todo\Form;

use Todo\Form\TodoForm;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class TodoFilterForm extends TodoForm
{
	protected $inputFilter;

	protected function setElements()
	{
		$this->setName('filter');
		$this->setAttribute('method', 'get');
		$this->setWrapElements(true);

		$this->add(array(
			'name' => 'order_by',
			'type' => 'Select',
			'options' => array(
				'label' => 'Order by',
				'value_options' => array(
					'date_created' => 'Date Created',
					'priority' => 'Priority',
					'status_sid' => 'Status',
					'project_sid' => 'Project',
				)
			),
		));
		$this->add(array(
			'name' => 'order_dir',
			'type' => 'Select',
			'options' => array(
				'label' => 'Order Dir',
				'value_options' => array(
					'desc' => 'Desc',
					'asc' => 'Asc',
				)
			),
		));
		$this->add(array(
			'name' => 'project_sid',
			'type' => 'Select',
			'options' => array(
				'label' => 'Project',
				'empty_option' => '--- Project ---',
				'value_options' => $this->getProjectSidOptions()
			),
		));
		$this->add(array(
			'name' => 'todo_status_sid',
			'type' => 'Select',
			'options' => array(
				'label' => 'Status',
				'empty_option' => '--- Status ---',
				'value_options' => $this->getTodoStatusSidOptions()
			),
		));
		$this->add(array(
			'name' => 'submit',
			'type' => 'Submit',
			'attributes' => array(
				'value' => 'Filter',
			),
		));

		$this->setInputFilter($this->getInputFilter());
	}

	public function getInputFilter()
	{
		if (!$this->inputFilter) {
			$inputFilter = new InputFilter();

			$inputFilter->add(array(
				'name'     => 'order_by',
				'required' => false,
			));
			$inputFilter->add(array(
				'name'     => 'order_dir',
				'required' => false,
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
				'name'     => 'todo_status_sid',
				'required' => false,
				'filters'  => array(
					array('name' => 'Int'),
					array('name' => 'Null'),
				),
			));
			$inputFilter->add(array(
				'name'     => 'submit',
				'required' => false,
			));

			$this->inputFilter = $inputFilter;
		}

		return $this->inputFilter;
	}
}
