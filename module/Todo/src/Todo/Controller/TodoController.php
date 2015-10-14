<?php
namespace Todo\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Todo\Model\Todo;
use Todo\Form\TodoForm;
use Todo\Form\TodoFilterForm;

class TodoController extends AbstractActionController
{
	protected $todoTable;

	public function indexAction()
	{
		$form = $this->getTodoForm();

		$request = $this->getRequest();
		if ($request->isPost()) {
			if(isset($_POST['action'])){
				$todo = new Todo();
				switch($_POST['action']){
					case "add_todo" :
						$form->setInputFilter($todo->getInputFilter());
						$form->setData($request->getPost());
						if ($form->isValid()) {
							$data = $form->getData();
							$todo->exchangeArray($data);
							if($this->getTodoTable()->saveTodo($todo)){
							}
						}
						break;
					case "update_todo" :
						$form->setInputFilter($todo->getInputFilter());
						$form->setData($request->getPost());
						if ($form->isValid()) {
							$todo->exchangeArray($form->getData());
							if($this->getTodoTable()->saveTodo($todo)){
							}
						}
						break;
				}
			}

			return $this->redirect()->toUrl($_SERVER['REQUEST_URI']);
		}

		$todoFilterForm = $this->getTodoFilterForm();
		$filter = array();
		if(isset($_GET['filter'])) {
			$todoFilterForm->setData($_GET['filter']);
			if($todoFilterForm->isValid()) {
				$filter = $todoFilterForm->getData();
			}
		}

		return new ViewModel(array(
			'todos' => $this->getTodoTable()->fetchAll($filter),
			'form' => $form,
			'todoFilterForm' => $todoFilterForm,
		));
	}

//	public function addAction()
//	{
//		$form = $this->getTodoForm();
//		$form->get('submit')->setValue('Add');
//
//		$request = $this->getRequest();
//		if ($request->isPost()) {
//			$todo = new Todo();
//			$form->setInputFilter($todo->getInputFilter());
//			$form->setData($request->getPost());
//
//			if ($form->isValid()) {
//				$todo->exchangeArray($form->getData());
//				$this->getTodoTable()->saveTodo($todo);
//
//				// Redirect to list of todos
//				return $this->redirect()->toRoute('todo');
//			}
//		}
//		return array('form' => $form);
//	}
//
//	public function editAction()
//	{
//		$id = (int) $this->params()->fromRoute('id', 0);
//		if (!$id) {
//			return $this->redirect()->toRoute('todo', array(
//				'action' => 'add'
//			));
//		}
//
//		try {
//			$todo = $this->getTodoTable()->getTodo($id);
//		}
//		catch (\Exception $e) {
//			return $this->redirect()->toRoute('todo', array(
//				'action' => 'index'
//			));
//		}
//
//		$form = $this->getTodoForm();
//		$form->bind($todo);
//		$form->get('submit')->setAttribute('value', 'Edit');
//
//		$request = $this->getRequest();
//		if ($request->isPost()) {
//			$form->setInputFilter($todo->getInputFilter());
//			$form->setData($request->getPost());
//
//			if ($form->isValid()) {
//				$this->getTodoTable()->saveTodo($todo);
//
//				return $this->redirect()->toRoute('todo');
//			}
//		}
//
//		return array(
//			'todo_sid' => $id,
//			'form' => $form,
//		);
//	}

	public function deleteAction()
	{
		$id = (int) $this->params()->fromRoute('id', 0);
		if (!$id) {
			return $this->redirect()->toRoute('todo');
		}

		$request = $this->getRequest();
		if ($request->isPost()) {
			$del = $request->getPost('del', 'No');

			if ($del == 'Yes') {
				$id = (int) $request->getPost('id');
				$this->getTodoTable()->deleteTodo($id);
			}

			return $this->redirect()->toRoute('todo');
		}

		return array(
			'id'    => $id,
			'todo' => $this->getTodoTable()->getTodo($id)
		);
	}

	public function getTodoTable()
	{
		if (!$this->todoTable) {
			$sm = $this->getServiceLocator();
			$this->todoTable = $sm->get('Todo\Model\TodoTable');
		}
		return $this->todoTable;
	}

	public function getTodoForm()
	{
		return  new TodoForm($this->getServiceLocator()->get('Todo\Model\ProjectTable'),
				$this->getServiceLocator()->get('Todo\Model\TodoStatusTable')
				);
	}

	public function getTodoFilterForm()
	{
		return new TodoFilterForm($this->getServiceLocator()->get('Todo\Model\ProjectTable'),
				$this->getServiceLocator()->get('Todo\Model\TodoStatusTable')
				);
	}
}