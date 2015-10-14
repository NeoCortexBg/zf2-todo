<?php
namespace Todo\Model;

use Zend\Db\TableGateway\TableGateway;

class TodoStatusTable
{
	protected $tableGateway;

	public function __construct(TableGateway $tableGateway)
	{
		$this->tableGateway = $tableGateway;
	}

	public function fetchAll()
	{
		return  $this->tableGateway->select();
	}

	public function getTodoStatus($todo_status_sid)
	{
		$todo_status_sid  = (int) $todo_status_sid;
		$rowset = $this->tableGateway->select(array('todo_status_sid' => $todo_status_sid));
		$row = $rowset->current();
		if (!$row) {
			throw new \Exception("Could not find row $todo_status_sid");
		}
		return $row;
	}

	public function saveTodoStatus(TodoStatus $todoStatus)
	{
		$data = array(
			'name'  => $todoStatus->name,
		);

		$todo_status_sid = (int) $todoStatus->todo_status_sid;
		if ($todo_status_sid == 0) {
			$this->tableGateway->insert($data);
		} else {
			if ($this->getTodoStatus($todo_status_sid)) {
				$this->tableGateway->update($data, array('todo_status_sid' => $todo_status_sid));
			} else {
				throw new \Exception('TodoStatus id does not exist');
			}
		}
	}

	public function deleteTodoStatus($todo_status_sid)
	{
		return $this->tableGateway->delete(array('todo_status_sid' => (int) $todo_status_sid));
	}
}
