<?php
namespace Todo\Model;

use Zend\Db\TableGateway\TableGateway;

class UserTable
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

	public function getUser($user_sid)
	{
		$user_sid  = (int) $user_sid;
		$rowset = $this->tableGateway->select(array('user_sid' => $user_sid));
		$row = $rowset->current();
		if (!$row) {
			throw new \Exception("Could not find row $user_sid");
		}
		return $row;
	}

	public function saveUser(User $user)
	{
		$data = array(
			'email'  => $user->email,
			'password'  => $user->password,
		);

		$user_sid = (int) $user->user_sid;
		if ($user_sid == 0) {
			$this->tableGateway->insert($data);
		} else {
			if ($this->getUser($user_sid)) {
				$this->tableGateway->update($data, array('user_sid' => $user_sid));
			} else {
				throw new \Exception('User id does not exist');
			}
		}
	}

	public function deleteUser($user_sid)
	{
		return $this->tableGateway->delete(array('user_sid' => (int) $user_sid));
	}
}
