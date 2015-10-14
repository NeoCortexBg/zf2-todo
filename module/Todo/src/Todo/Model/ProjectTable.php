<?php
namespace Todo\Model;

use Zend\Db\TableGateway\TableGateway;

class ProjectTable
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

	public function getProject($project_sid)
	{
		$project_sid  = (int) $project_sid;
		$rowset = $this->tableGateway->select(array('project_sid' => $project_sid));
		$row = $rowset->current();
		if (!$row) {
			throw new \Exception("Could not find row $project_sid");
		}
		return $row;
	}

	public function saveProject(Project $project)
	{
		$data = array(
			'name'  => $project->name,
		);

		$project_sid = (int) $project->project_sid;
		if ($project_sid == 0) {
			$this->tableGateway->insert($data);
		} else {
			if ($this->getProject($project_sid)) {
				$this->tableGateway->update($data, array('project_sid' => $project_sid));
			} else {
				throw new \Exception('Project id does not exist');
			}
		}
	}

	public function deleteProject($project_sid)
	{
		return $this->tableGateway->delete(array('project_sid' => (int) $project_sid));
	}
}
