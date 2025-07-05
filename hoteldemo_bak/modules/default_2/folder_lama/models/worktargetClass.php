<?php

require_once('defaultClass.php');
require_once('dbClass.php');

class worktargetClass extends defaultClass
{	
	function getHousekeepingWorkTarget($housekeeping_report_id) {
		$workTargetTable = new housekeeping_work_target(array('db'=>'db'));
		$select = $workTargetTable->select();
		$select->where('housekeeping_report_id = ?', $housekeeping_report_id);
		$activity = $workTargetTable->getAdapter()->fetchAll($select);
		return $activity;
	}
	
	function deleteHousekeepingWorkTargetByReportId($housekeeping_report_id)
	{
		$workTargetTable = new housekeeping_work_target(array('db'=>'db'));
		
		if ( is_numeric($housekeeping_report_id) && $housekeeping_report_id > 0 )
		{		
			$where = $workTargetTable->getAdapter()->quoteInto('housekeeping_report_id = ?', $housekeeping_report_id);
			$workTargetTable->delete($where);
		}
	}
	
	function addHousekeepingWorkTarget($params) {
		$workTargetTable = new housekeeping_work_target(array('db'=>'db'));
	
		$workTargetTable->insert($params);
		return $this->db->lastInsertId();
	}
}
?>