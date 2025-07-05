<?php

require_once('adminClass.php');
require_once('dbClass.php');

class staffconditionClass extends adminClass
{
	function __construct()
	{
		parent::__construct();
	}
	

	function getStaffConditions()
	{
		$staffConditionTable = new mod_staff_condition(array('db' => 'db')); //use db object from registry

		$select = $staffConditionTable->select()->order("department");
		
		return $this->db->fetchAll($select);
	}
	
	
	function addStaffCondition($params)
	{		
		$staffConditionTable = new mod_staff_condition(array('db' => 'db')); //use db object from registry
		
		$data = array(
			'site_id'		=> $params['site_id'],
			'department'	=> $params['department'],
			'type'			=> $params['type']
		);
			
		if(empty($params['staff_condition_id']))
		{
			$staffConditionTable->insert($data);
		}
		else
		{
			$where = $staffConditionTable->getAdapter()->quoteInto('staff_condition_id = ?', $params['staff_condition_id']);
			$staffConditionTable->update($data, $where);
		}
		
	}
	
	function getStaffConditionById($id)
	{		
		$staffConditionTable = new mod_staff_condition(array('db' => 'db')); //use db object from registry

		$select = $staffConditionTable->select()
			->where('staff_condition_id = ?', $id);
			
		$rs = $this->db->fetchRow($select);
		return $rs;	
	}
	
	function deleteStaffCondition($id)
	{
		$staffConditionTable = new mod_staff_condition(array('db' => 'db')); //use db object from registry
		
		if ( is_numeric($id) && $id > 0 )
		{		
			$where = $staffConditionTable->getAdapter()->quoteInto('staff_condition_id = ?', $id);
			$staffConditionTable->delete($where);
		}
	}
	
	
}
?>