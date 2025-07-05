<?php

require_once('adminClass.php');
require_once('dbClass.php');

class actionplanClass extends adminClass
{
	function __construct()
	{
		parent::__construct();
	}
	
	/*** Action Plan Module ***/
	
	function getActionPlanModules($category_id, $year = 0) {
		$moduleTable = new action_plan_module(array('db'=>'db2'));
		$select = $moduleTable->select()->where("category_id= ?", $category_id);
		$select->where('site_id = ?', $this->site_id);
		if($year > 0) $select->where('show_year = ?', $year);
		$select->order('show_year asc');
		$select->order('sort_order asc');
		$modules = $moduleTable->getAdapter()->fetchAll($select);
		return $modules;
	}

	function saveActionPlanModule($params)
	{		
		$moduleTable = new action_plan_module(array('db'=>'db2'));
		
		$data = array(
			'site_id'				=> $this->site_id,
			'module_name'			=> $params['module_name'],
			'sort_order'			=> $params['sort_order'],
			'category_id'			=> $params['category_id'],
			'show_year'				=> $params['show_year'],
			'total_bobot'			=> $params['total_bobot']
		);
			
		if(empty($params['action_plan_module_id']))
		{
			$moduleTable->insert($data);
		}
		else
		{
			$where = $moduleTable->getAdapter()->quoteInto('action_plan_module_id = ?', $params['action_plan_module_id']);
			$moduleTable->update($data, $where);
		}
	}
	
	function getActionPlanModuleById($module_id)
	{
		$moduleTable = new action_plan_module(array('db'=>'db2'));
		$select = $moduleTable->select()->where('action_plan_module_id = ?', $module_id);
		$target = $moduleTable->getAdapter()->fetchRow($select);
		return $target;
	}	
	
	function deleteActionPlanModuleById($id)
	{
		$moduleTable = new action_plan_module(array('db'=>'db2'));
		
		if ( is_numeric($id) && $id > 0 )
		{		
			$where = array();
			$where[] = $moduleTable->getAdapter()->quoteInto('action_plan_module_id = ?', $id);
			$moduleTable->delete($where);
		}
	}

	function copyActionPlanModuleToOtherSite($params)
	{		
		$moduleTable = new action_plan_module(array('db'=>'db2'));
		
		$data = array(
			'site_id'				=> $params['site_id'],
			'module_name'			=> $params['module_name'],
			'sort_order'			=> $params['sort_order'],
			'category_id'			=> $params['category_id'],
			'show_year'				=> $params['show_year'],
			'total_bobot'			=> $params['total_bobot']
		);
			
		$moduleTable->insert($data);
	}

	function getActionPlanModuleByModuleName($module_name, $category_id, $year, $site_id)
	{
		$moduleTable = new action_plan_module(array('db'=>'db2'));
		$select = $moduleTable->select()->where('module_name = ?', $module_name)->where('category_id = ?', $category_id)->where('show_year = ?', $year)->where('site_id = ?', $site_id);
		$module = $moduleTable->getAdapter()->fetchRow($select);
		return $module;
	}	

	/*** Action Plan Target ***/

	function getActionPlanTarget($category_id, $year) {
		$targetTable = new action_plan_target(array('db'=>'db2'));
		$select = $targetTable->getAdapter()->select();
		$select->from(array("t"=>"action_plan_target"), array("t.*"));
		$select->joinLeft(array("m"=>"action_plan_module"), "m.action_plan_module_id = t.action_plan_module_id", array("m.show_year", "m.module_name"));
		$select->where("t.category_id= ?", $category_id);
		$select->where("t.site_id= ?", $this->site_id);
		$select->where("m.show_year= ?", $year);
		$select->order('m.sort_order asc');
		$select->order('t.sort_order asc');
		$select->order('t.action_plan_module_id asc');
		$target = $targetTable->getAdapter()->fetchAll($select);
		return $target;
	}
	
	function saveActionPlanTarget($params)
	{		
		$targetTable = new action_plan_target(array('db'=>'db2'));
		
		$data = array(
			'site_id'				=> $this->site_id,
			'action_plan_module_id'	=> $params['action_plan_module_id'],
			'target_name'			=> $params['target_name'],
			'sort_order'			=> $params['sort_order'],
			'category_id'			=> $params['category_id'],
			'chief_bobot'			=> $params['chief_bobot'],
			'spv_bobot'				=> $params['spv_bobot'],
			'staff_bobot'			=> $params['staff_bobot'],
			'admin_bobot'			=> $params['admin_bobot'],
			'use_activity_bobot'	=> $params['use_activity_bobot'],
			'kpi_only'				=> $params['kpi_only']
		);
			
		if(empty($params['action_plan_target_id']))
		{
			$targetTable->insert($data);
			$id = $targetTable->getAdapter()->lastInsertId();//ubh
		}
		else
		{
			$where = $targetTable->getAdapter()->quoteInto('action_plan_target_id = ?', $params['action_plan_target_id']);
			$targetTable->update($data, $where);
			$id = $params['action_plan_target_id'];//ubh
		}
		echo $id;//copy
	}
	
	function getActionPlanTargetById($target_id)
	{
		$targetTable = new action_plan_target(array('db'=>'db2'));
		$select = $targetTable->select()->where('action_plan_target_id = ?', $target_id);
		$target = $targetTable->getAdapter()->fetchRow($select);
		return $target;
	}	
	
	function deleteActionPlanTargetById($id)
	{
		$targetTable = new action_plan_target(array('db'=>'db2'));
		
		if ( is_numeric($id) && $id > 0 )
		{		
			$where = array();
			$where[] = $targetTable->getAdapter()->quoteInto('action_plan_target_id = ?', $id);
			$targetTable->delete($where);
		}
	}
	
	
	function getActionPlanTargetByModuleId($module_id)
	{
		$targetTable = new action_plan_target(array('db'=>'db2'));
		$select = $targetTable->select()->where('action_plan_module_id = ?', $module_id)->where('site_id = ?', $this->site_id)->order('sort_order asc');
		$target = $targetTable->getAdapter()->fetchAll($select);
		return $target;
	}	

	function copyActionPlanTargetToOtherSite($params)
	{		
		$targetTable = new action_plan_target(array('db'=>'db2'));
		
		$data = array(
			'site_id'				=> $params['site_id'],
			'action_plan_module_id'	=> $params['new_action_plan_module_id'],
			'target_name'			=> $params['target_name'],
			'sort_order'			=> $params['sort_order'],
			'category_id'			=> $params['category_id'],
			'chief_bobot'			=> $params['chief_bobot'],
			'spv_bobot'				=> $params['spv_bobot'],
			'staff_bobot'			=> $params['staff_bobot'],
			'admin_bobot'			=> $params['admin_bobot'],
			'use_activity_bobot'	=> $params['use_activity_bobot'],
			'kpi_only'				=> $params['kpi_only']
		);
			
		$targetTable->insert($data);
	}

	function getActionPlanTargetByTargetName($target_name, $category_id, $site_id, $year)
	{
		$targetTable = new action_plan_target(array('db'=>'db2'));
		$select = $targetTable->getAdapter()->select();
		$select->from(array("t"=>"action_plan_target"), array("t.*"));
		$select->joinLeft(array("m"=>"action_plan_module"), "m.action_plan_module_id = t.action_plan_module_id", array("m.show_year"));
		$select->where("t.target_name= ?", $target_name);
		$select->where("t.category_id= ?", $category_id);
		$select->where("t.site_id= ?", $site_id);
		$select->where("m.show_year= ?", $year);
		$target = $targetTable->getAdapter()->fetchRow($select);
		return $target;
	}
	
	function getActionPlanTargetByTargetAndModule($module_id, $target_name, $category_id, $year, $site_id)
	{
		$targetTable = new action_plan_target(array('db'=>'db2'));
		$select = $targetTable->getAdapter()->select();
		$select->from(array("t"=>"action_plan_target"), array("t.*"));
		$select->joinLeft(array("m"=>"action_plan_module"), "m.action_plan_module_id = t.action_plan_module_id", array("m.show_year"));
		$select->where("t.target_name= ?", $target_name);
		$select->where("t.category_id= ?", $category_id);
		$select->where("t.site_id= ?", $site_id);
		$select->where("m.show_year= ?", $year);
		$select->where("t.action_plan_module_id= ?", $module_id);
		$target = $targetTable->getAdapter()->fetchRow($select);
		return $target;
	}
	
	/*** Action Plan Activity ***/
	
	function getActionPlanActivity($category_id, $year) {
		$activityTable = new action_plan_activity(array('db'=>'db2'));
		$select = $activityTable->getAdapter()->select();
		$select->from(array("a"=>"action_plan_activity"), array("a.*"));
		$select->joinLeft(array("t"=>"action_plan_target"), "t.action_plan_target_id = a.action_plan_target_id", array("t.target_name"));
		$select->joinLeft(array("m"=>"action_plan_module"), "m.action_plan_module_id = t.action_plan_module_id", array("m.show_year", "m.module_name"));
		$select->where("a.category_id= ?", $category_id);
		$select->where("a.site_id= ?", $this->site_id);
		$select->where("m.show_year= ?", $year);
		$select->order('a.action_plan_target_id asc');
		$select->order('a.sort_order asc');
		$target = $activityTable->getAdapter()->fetchAll($select);
		return $target;
	}
	
	function saveActionPlanActivity($params)
	{		
		$activityTable = new action_plan_activity(array('db'=>'db2'));
		
		$data = array(
			'site_id'				=> $this->site_id,
			'action_plan_target_id'	=> $params['action_plan_target_id'],
			'activity_name'			=> $params['activity_name'],
			'sort_order'			=> $params['sort_order'],
			'category_id'			=> $params['category_id'],
			'total_schedule'		=> $params['total_schedule'],
			'document_as_approve'	=> $params['document_as_approve'],
			'remarks'				=> $params['remarks'],
			'chief'					=> $params['chief'],
			'spv'					=> $params['spv'],
			'staff'					=> $params['staff'],
			'admin'					=> $params['admin'],
			'chief_bobot'			=> $params['chief_bobot'],
			'spv_bobot'				=> $params['spv_bobot'],
			'staff_bobot'			=> $params['staff_bobot'],
			'admin_bobot'			=> $params['admin_bobot'],
		);
			
		if(empty($params['action_plan_activity_id']))
		{
			$activityTable->insert($data);
			$id = $activityTable->getAdapter()->lastInsertId();//ubh
		}
		else
		{
			$where = $activityTable->getAdapter()->quoteInto('action_plan_activity_id = ?', $params['action_plan_activity_id']);
			$activityTable->update($data, $where);
			$id = $params['action_plan_activity_id'];//ubh
		}
		echo $id;//copy
	}
	
	function getActionPlanActivityById($activity_id)
	{
		$activityTable = new action_plan_activity(array('db'=>'db2'));
		$select = $activityTable->select()->where('action_plan_activity_id = ?', $activity_id);
		$target = $activityTable->getAdapter()->fetchRow($select);
		return $target;
	}	
	
	function deleteActionPlanActivityById($id)
	{
		$activityTable = new action_plan_activity(array('db'=>'db2'));
		
		if ( is_numeric($id) && $id > 0 )
		{		
			$where = array();
			$where[] = $activityTable->getAdapter()->quoteInto('action_plan_activity_id = ?', $id);
			$activityTable->delete($where);
		}
	}
	
	function getActionPlanActivityByTargetId($action_plan_target_id)
	{
		$activityTable = new action_plan_activity(array('db'=>'db2'));
		$select = $activityTable->select()->where('action_plan_target_id = ?', $action_plan_target_id)->where('site_id = ?', $this->site_id)->order('sort_order asc');
		$activity = $activityTable->getAdapter()->fetchAll($select);
		return $activity;
	}	

	function copyActionPlanActivityToOtherSite($params)
	{		
		$activityTable = new action_plan_activity(array('db'=>'db2'));
		
		$data = array(
			'site_id'				=> $params['site_id'],
			'action_plan_target_id'	=> $params['new_action_plan_target_id'],
			'activity_name'			=> $params['activity_name'],
			'sort_order'			=> $params['sort_order'],
			'category_id'			=> $params['category_id'],
			'total_schedule'		=> $params['total_schedule'],
			'document_as_approve'	=> $params['document_as_approve'],
			'remarks'				=> $params['remarks'],
			'chief'					=> $params['chief'],
			'spv'					=> $params['spv'],
			'staff'					=> $params['staff'],
			'admin'					=> $params['admin'],
			'chief_bobot'			=> $params['chief_bobot'],
			'spv_bobot'				=> $params['spv_bobot'],
			'staff_bobot'			=> $params['staff_bobot'],
			'admin_bobot'			=> $params['admin_bobot'],
		);
			
		$activityTable->insert($data);
	}
	
	function getActionPlanActivityByActivityAndTarget($target_id, $activity_name, $category_id, $year, $site_id)
	{
		$activityTable = new action_plan_activity(array('db'=>'db2'));
		$select = $activityTable->getAdapter()->select();
		$select->from(array("a"=>"action_plan_activity"), array("a.*"));
		$select->joinLeft(array("t"=>"action_plan_target"), "t.action_plan_target_id = a.action_plan_target_id", array());
		$select->joinLeft(array("m"=>"action_plan_module"), "m.action_plan_module_id = t.action_plan_module_id", array("m.show_year"));
		$select->where("a.activity_name= ?", $activity_name);
		$select->where("a.category_id= ?", $category_id);
		$select->where("a.site_id= ?", $site_id);
		$select->where("m.show_year= ?", $year);
		$select->where("a.action_plan_target_id= ?", $target_id);
		$activity = $activityTable->getAdapter()->fetchRow($select);
		return $activity;
	}
	
	/*** Action Plan Reminder Email ***/
	
	function getActionPlanEmail($category_id, $site_id) {
		$emailTable = new action_plan_reminder_email(array('db'=>'db2'));
		$select = $emailTable->select()->where("category_id= ?", $category_id)->where("site_id= ?", $site_id)->order('email asc');
		$email = $emailTable->getAdapter()->fetchAll($select);
		return $email;
	}

	function getActionPlanReminderEmail($category_id, $site_id) {
		$emailTable = new action_plan_reminder_email(array('db'=>'db2'));
		$select = $emailTable->select()->where("category_id= ?", $category_id)->where("site_id= ?", $site_id)->where("reminder= '1'")->order('email asc');
		$email = $emailTable->getAdapter()->fetchAll($select);
		return $email;
	}

	function getActionPlanReviewEmail($category_id, $site_id) {
		$emailTable = new action_plan_reminder_email(array('db'=>'db2'));
		$select = $emailTable->select()->where("category_id= ?", $category_id)->where("site_id= ?", $site_id)->where("review= '1'")->order('email asc');
		$email = $emailTable->getAdapter()->fetchAll($select);
		return $email;
	}
	
	function saveActionPlanEmail($params)
	{		
		$emailTable = new action_plan_reminder_email(array('db'=>'db2'));
		
		$data = array(
			'site_id'				=> $this->site_id,
			'email'					=> $params['email'],
			'category_id'			=> $params['category_id'],
			'cc'					=> $params['cc'],
			'reminder'				=> $params['reminder'],
			'review'				=> $params['review']
		);
			
		if(empty($params['email_id']))
		{
			$emailTable->insert($data);
		}
		else
		{
			$where = $emailTable->getAdapter()->quoteInto('email_id = ?', $params['email_id']);
			$emailTable->update($data, $where);
		}
	}
	
	function getActionPlanEmailById($email_id)
	{
		$emailTable = new action_plan_reminder_email(array('db'=>'db2'));
		$select = $emailTable->select()->where('email_id = ?', $email_id);
		$target = $emailTable->getAdapter()->fetchRow($select);
		return $target;
	}	
	
	function deleteActionPlanEmailById($id)
	{
		$emailTable = new action_plan_reminder_email(array('db'=>'db2'));
		
		if ( is_numeric($id) && $id > 0 )
		{		
			$where = array();
			$where[] = $emailTable->getAdapter()->quoteInto('email_id = ?', $id);
			$emailTable->delete($where);
		}
	}
	
}
?>