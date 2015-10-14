<?php
namespace Todo\Form;

use Zend\Form\Form;
use Todo\Model\ProjectTable;
use Todo\Model\TodoStatusTable;

class TodoForm extends Form
{
	protected $projectTable;
	protected $projectSidOptions;
	protected $todoStatusSidOptions;
	protected $todoStatusTable;

	public function __construct(ProjectTable $projectTable, TodoStatusTable $todoStatusTable)
	{
		$this->projectTable = $projectTable;
		$this->todoStatusTable = $todoStatusTable;

		parent::__construct();

		$this->setElements();
	}

	protected function setElements()
	{
		$this->add(array(
			'name' => 'todo_sid',
			'type' => 'Hidden',
		));
		$this->add(array(
			'name' => 'priority',
			'type' => 'Text',
			'options' => array(
				'label' => 'Priority',
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
				'value_options' => $this->getTodoStatusSidOptions()
			),
		));
		$this->add(array(
			'name' => 'text',
			'type' => 'TextArea',
			'options' => array(
				'label' => 'Text',
			),
		));
		$this->add(array(
			'name' => 'submit',
			'type' => 'Submit',
			'attributes' => array(
				'value' => 'Go',
			),
		));
	}

	protected function getProjectTable()
	{
		if(!$this->projectTable) {
			throw new \Exception("Project table not set yet");
		}
		return $this->projectTable;
	}

	protected function getTodoStatusTable()
	{
		if(!$this->todoStatusTable) {
			throw new \Exception("Todo Status table not set yet");
		}
		return $this->todoStatusTable;
	}

	protected function getProjectSidOptions()
	{
		if(!$this->projectSidOptions) {
			$projects = $this->getProjectTable()->fetchAll();
			$options = array();
			if($projects) {
				foreach($projects as $p) {
					$options[$p->project_sid] = $p->name;
				}
			}
			$this->projectSidOptions = $options;
		}

		return $this->projectSidOptions;
	}

	protected function getTodoStatusSidOptions()
	{
		if(!$this->todoStatusSidOptions) {
			$todoStatuses = $this->getTodoStatusTable()->fetchAll();
			$options = array();
			if($todoStatuses) {
				foreach($todoStatuses as $ts) {
					$options[$ts->todo_status_sid] = $ts->name;
				}
			}
			$this->todoStatusSidOptions = $options;
		}

		return $this->todoStatusSidOptions;
	}
}
