<?php

require_once('adminClass.php');
require_once('dbClass.php');

class achievementcategoryClass extends adminClass
{
	function __construct()
	{
		parent::__construct();
	}

	function getAchievementCategory()
	{
		$categoryTable = new achievement_category(array('db' => 'db')); //use db object from registry

		$select = $categoryTable->select();
		
		return $this->db->fetchAll($select);
	}
	
	
	function addAchievementCategory($params)
	{		
		$categoryTable = new achievement_category(array('db' => 'db')); //use db object from registry

		$data = array(
			'start_range'		=> $params['start_range'],
			'end_range'			=> $params['end_range'],
			'description'		=> $params['description']
		);	

		if(empty($params['id']))
		{
			$categoryTable->insert($data);
		}
		else
		{
			$where = $categoryTable->getAdapter()->quoteInto('id = ?', $params['id']);
			$categoryTable->update($data, $where);
		}
		
	}
	
	function getAchievementCategoryById($id)
	{		
		$categoryTable = new achievement_category(array('db' => 'db')); //use db object from registry

		$select = $categoryTable->select()
			->where('id = ?', $id);
			
		$rs = $categoryTable->getAdapter()->fetchRow($select);
		return $rs;	
	}
	
	function deleteAchievementCategory($id)
	{
		$categoryTable = new achievement_category(array('db' => 'db')); //use db object from registry
		
		if ( is_numeric($id) && $id > 0 )
		{		
			$where = $categoryTable->getAdapter()->quoteInto('id = ?', $id);
			$categoryTable->delete($where);
		}
	}

}
?>