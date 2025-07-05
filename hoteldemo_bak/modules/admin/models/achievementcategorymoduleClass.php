<?php

require_once('adminClass.php');
require_once('dbClass.php');

class achievementcategorymoduleClass extends adminClass
{
	function __construct()
	{
		parent::__construct();
	}

	function getAchievementModuleCategory($category_id)
	{
		$categoryTable = new achievement_category_module(array('db' => 'db')); //use db object from registry

		$select = $categoryTable->select()->where('category_id = ?', $category_id)->where('site_id = ?', $this->site_id);
		
		return $this->db->fetchAll($select);
	}
	
	
	function addAchievementModuleCategory($params)
	{		
		$categoryTable = new achievement_category_module(array('db' => 'db')); //use db object from registry

		$data = array(
			'site_id'			=> $this->site_id,
			'category_id'		=> $params['category_id'],
			'module_id'			=> $params['action_plan_module_id'],
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
	
	function getAchievementCategoryModuleById($id)
	{		
		$categoryTable = new achievement_category_module(array('db' => 'db')); //use db object from registry

		$select = $categoryTable->select()
			->where('id = ?', $id);
			
		$rs = $categoryTable->getAdapter()->fetchRow($select);
		return $rs;	
	}
	
	function deleteAchievementCategoryModule($id)
	{
		$categoryTable = new achievement_category_module(array('db' => 'db')); //use db object from registry
		
		if ( is_numeric($id) && $id > 0 )
		{		
			$where = $categoryTable->getAdapter()->quoteInto('id = ?', $id);
			$categoryTable->delete($where);
		}
	}

	function copyAchievementCategoryModuleToOtherSite($params)
	{		
		$categoryTable = new achievement_category_module(array('db' => 'db'));
		
		$data = array(
			'site_id'			=> $params['site_id'],
			'category_id'		=> $params['category_id'],
			'module_id'			=> $params['module_id'],
			'start_range'		=> $params['start_range'],
			'end_range'			=> $params['end_range'],
			'description'		=> $params['description']
		);
		
		$categoryTable->insert($data);
	}

}
?>