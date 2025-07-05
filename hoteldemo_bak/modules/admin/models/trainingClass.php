<?php

require_once('adminClass.php');
require_once('dbClass.php');

class trainingClass extends adminClass
{
	function __construct()
	{
		parent::__construct();
	}
	

	function getSecurityTrainingActivity()
	{
		$taTable = new security_training_activity(array('db' => 'db')); //use db object from registry

		$select = $taTable->select()->where('site_id = ?', $this->site_id);
		
		return $this->db->fetchAll($select);
	}
	
	
	function addSecurityTrainingActivity($params)
	{		
		$taTable = new security_training_activity(array('db' => 'db')); //use db object from registry
		
		$data = array(
			'activity'	=> $params['activity'],
			'site_id'	=> $this->site_id
		);	
		if(empty($params['training_activity_id']))
		{
			$taTable->insert($data);
		}
		else
		{
			$where = $taTable->getAdapter()->quoteInto('training_activity_id = ?', $params['training_activity_id']);
			$taTable->update($data, $where);
		}
		
	}
	
	function getSecurityTrainingActivityById($id)
	{		
		$taTable = new security_training_activity(array('db' => 'db')); //use db object from registry

		$select = $taTable->select()
			->where('training_activity_id = ?', $id);
			
		$rs = $this->db->fetchRow($select);
		return $rs;	
	}
	
	function deleteSecurityTrainingActivity($id)
	{
		$taTable = new security_training_activity(array('db' => 'db')); //use db object from registry
		
		if ( is_numeric($id) && $id > 0 )
		{		
			$where = $taTable->getAdapter()->quoteInto('training_activity_id = ?', $id);
			$taTable->delete($where);
		}
	}
	
	/*** SAFETY TRAINING ***/
	
	function getSafetyTrainingActivity()
	{
		$stTable = new safety_training_activity(array('db' => 'db')); //use db object from registry

		$select = $stTable->select();
		
		return $this->db->fetchAll($select);
	}
	
	
	function addSafetyTrainingActivity($params)
	{		
		$stTable = new safety_training_activity(array('db' => 'db')); //use db object from registry
		
		$data = array(
			'activity'	=> $params['activity']
		);	
		if(empty($params['training_activity_id']))
		{
			$stTable->insert($data);
		}
		else
		{
			$where = $stTable->getAdapter()->quoteInto('training_activity_id = ?', $params['training_activity_id']);
			$stTable->update($data, $where);
		}
		
	}
	
	function getSafetyTrainingActivityById($id)
	{		
		$stTable = new safety_training_activity(array('db' => 'db')); //use db object from registry

		$select = $stTable->select()
			->where('training_activity_id = ?', $id);
			
		$rs = $this->db->fetchRow($select);
		return $rs;	
	}
	
	function deleteSafetyTrainingActivity($id)
	{
		$stTable = new safety_training_activity(array('db' => 'db')); //use db object from registry
		
		if ( is_numeric($id) && $id > 0 )
		{		
			$where = $stTable->getAdapter()->quoteInto('training_activity_id = ?', $id);
			$stTable->delete($where);
		}
	}
	
	/*** PARKING TRAINING ***/
	
	function getParkingTrainingActivity()
	{
		$ptTable = new parking_training_activity(array('db' => 'db')); //use db object from registry

		$select = $ptTable->select()->where('site_id = ?', $this->site_id);
		
		return $this->db->fetchAll($select);
	}
	
	
	function addParkingTrainingActivity($params)
	{		
		$ptTable = new parking_training_activity(array('db' => 'db')); //use db object from registry
		
		$data = array(
			'activity'	=> $params['activity'],
			'site_id'	=> $this->site_id
		);	
		if(empty($params['training_activity_id']))
		{
			$ptTable->insert($data);
		}
		else
		{
			$where = $ptTable->getAdapter()->quoteInto('training_activity_id = ?', $params['training_activity_id']);
			$ptTable->update($data, $where);
		}
		
	}
	
	function getParkingTrainingActivityById($id)
	{		
		$ptTable = new parking_training_activity(array('db' => 'db')); //use db object from registry

		$select = $ptTable->select()
			->where('training_activity_id = ?', $id);
			
		$rs = $this->db->fetchRow($select);
		return $rs;	
	}
	
	function deleteParkingTrainingActivity($id)
	{
		$ptTable = new parking_training_activity(array('db' => 'db')); //use db object from registry
		
		if ( is_numeric($id) && $id > 0 )
		{		
			$where = $ptTable->getAdapter()->quoteInto('training_activity_id = ?', $id);
			$ptTable->delete($where);
		}
	}
	
}
?>