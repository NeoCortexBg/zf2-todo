<?php
namespace Todo\Model;

use Zend\Db\TableGateway\TableGateway;

class TodoTable
{
	protected $tableGateway;

	public function __construct(TableGateway $tableGateway)
	{
		$this->tableGateway = $tableGateway;
	}

	public function fetchAll(array $filter = array())
	{
		$sqlSelect = $this->tableGateway->getSql()->select();
		$sqlSelect->join('todo_status', 'todo_status.todo_status_sid = todo.todo_status_sid', array('status' => 'name'));

		if(!empty($filter)){
//			ppv($filter);
			if(!empty($filter['order_by'])){
				$sqlSelect->order($filter['order_by'] . ' ' . ((!empty($filter['order_dir']) && $filter['order_dir'] === 'asc') ? $filter['order_dir'] : 'desc'));
			}
			if(!empty($filter['project_sid'])){
				$sqlSelect->where(array('todo.project_sid' => $filter['project_sid']));
			}
			if(!empty($filter['todo_status_sid'])){
				$sqlSelect->where(array('todo.todo_status_sid' => $filter['todo_status_sid']));
			}
//			die('asas');
		}

//		ppv($sqlSelect);

		return $this->tableGateway->selectWith($sqlSelect);
//		return  $this->tableGateway->select();
	}

	public function getTodo($todo_sid)
	{
		$todo_sid  = (int) $todo_sid;
		$rowset = $this->tableGateway->select(array('todo_sid' => $todo_sid));
		$row = $rowset->current();
		if (!$row) {
			throw new \Exception("Could not find row $todo_sid");
		}
		return $row;
	}

	public function saveTodo(Todo $todo)
	{
		$data = array(
			'project_sid' => $todo->project_sid,
			'priority'  => $todo->priority,
			'todo_status_sid'  => $todo->todo_status_sid,
			'text'  => $todo->text,
		);

		$todo_sid = (int) $todo->todo_sid;
		if ($todo_sid == 0) {
			return $this->tableGateway->insert($data);
		} else {
			if ($this->getTodo($todo_sid)) {
				return $this->tableGateway->update($data, array('todo_sid' => $todo_sid));
			} else {
				throw new \Exception('Todo id does not exist');
			}
		}
	}

	public function deleteTodo($todo_sid)
	{
		return $this->tableGateway->delete(array('todo_sid' => (int) $todo_sid));
	}
}
