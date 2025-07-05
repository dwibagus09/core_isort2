<?php

require_once('actionControllerBase.php');
class Admin_ActionplanController extends actionControllerBase
{	
	/*** ACTION PLAN MODULE ***/
	
	public function viewmoduleAction() {
		$params = $this->_getAllParams();
		
		$params['user_id'] = $this->ident['user_id'];
		Zend_Loader::LoadClass('actionplanClass', $this->modelDir);
		$actionplanClass = new actionplanClass();

		if(!empty($params['y'])) $this->view->selectedYear = $year = $params['y'];
		else $this->view->selectedYear = $year = date("Y");
		
		$this->view->modules = $actionplanClass->getActionPlanModules($params['c'], $year);
		$this->view->category_id = $params['c'];

		Zend_Loader::LoadClass('siteClass', $this->modelDir);
		$siteClass = new siteClass();
		$this->view->sites = $siteClass->getSites();		

		if($params['c'] == 6) $this->view->title = "Preventive Maintenance Module";
		else $this->view->title = "Action Plan Module";
		
		if($params['c'] == 1) $this->view->category = "security";
		elseif($params['c'] == 2) $this->view->category = "housekeeping";
		elseif($params['c'] == 3) $this->view->category = "safety";
		elseif($params['c'] == 5) $this->view->category = "parking";
		elseif($params['c'] == 6) $this->view->category = "engineering";

		echo $this->view->render('header.php');
        echo $this->view->render('view_action_plan_module.php');
        echo $this->view->render('footer.php');
	}
	
	public function savemoduleAction() {
		$params = $this->_getAllParams();
	
		Zend_Loader::LoadClass('actionplanClass', $this->modelDir);
		$actionplanClass = new actionplanClass();
	
		$this->view->module = $actionplanClass->saveActionPlanModule($params);

		$this->cache->remove("ap_modules_".$this->site_id."_".$params['category_id']."_".$params['show_year']);
		$this->cache->remove("ap_schedule_".$this->site_id."_".$params['category_id']."_".$params['show_year']);
		$this->cache->remove("ap_week_total_".$this->site_id."_".$params['category_id']."_".$params['show_year']);
		$this->cache->remove("ap_calendar_".$this->site_id."_".$params['category_id']."_".$params['show_year']);
	}
	
	public function getmodulebyidAction() {
		$params = $this->_getAllParams();
		Zend_Loader::LoadClass('actionplanClass', $this->modelDir);
		$actionplanClass = new actionplanClass();
		
		if(!empty($params['id'])) 
		{
			echo json_encode($actionplanClass->getActionPlanModuleById($params['id']));
		}
	}
	
	public function deletemodulebyidAction() {
		$params = $this->_getAllParams();
		Zend_Loader::LoadClass('actionplanClass', $this->modelDir);
		$actionplanClass = new actionplanClass();
		$deletedModule = $actionplanClass->getActionPlanModuleById($params['id']);
		$actionplanClass->deleteActionPlanModuleById($params['id']);

		$this->cache->remove("ap_modules_".$this->site_id."_".$deletedModule['category_id']."_".$deletedModule['show_year']);
		$this->cache->remove("ap_schedule_".$this->site_id."_".$deletedModule['category_id']."_".$deletedModule['show_year']);
		$this->cache->remove("ap_week_total_".$this->site_id."_".$deletedModule['category_id']."_".$deletedModule['show_year']);
		$this->cache->remove("ap_calendar_".$this->site_id."_".$deletedModule['category_id']."_".$deletedModule['show_year']);
	}
	
	public function copymoduleAction() {
		$params = $this->_getAllParams();

		Zend_Loader::LoadClass('actionplanClass', $this->modelDir);
		$actionplanClass = new actionplanClass();

		$module = $actionplanClass->getActionPlanModuleById($params['action_plan_module_id']);
		
		Zend_Loader::LoadClass('siteClass', $this->modelDir);
		$siteClass = new siteClass();
		
		$module['category_id'] = $params['category_id'];
		$msg = "";
		foreach($params['site_id'] as $site_id) {
			$existingModule = $actionplanClass->getActionPlanModuleByModuleName($module['module_name'], $params['category_id'], $params['year'], $site_id);
			$site = $siteClass->getSite($site_id);
			if(empty($existingModule))
			{
			$module['site_id'] = $site_id;
			$actionplanClass->copyActionPlanModuleToOtherSite($module);
			$this->cache->remove("ap_modules_".$site_id."_".$params['category_id']."_".date("Y"));
			$this->cache->remove("ap_schedule_".$site_id."_".$params['category_id']."_".date("Y"));
			$this->cache->remove("ap_week_total_".$site_id."_".$params['category_id']."_".date("Y"));
			$this->cache->remove("ap_calendar_".$site_id."_".$params['category_id']."_".date("Y"));
				$msg = $msg . "File has been copied to ".$site['initial']."\n";
			}
			else
			{
				$msg = $msg . "File cannot be copied to ".$site['initial']." because it's already exist\n";
			}
		}
		echo $msg; 
	}
	
	
	/*** ACTION PLAN TARGET ***/
	
	public function viewtargetAction() {
		$params = $this->_getAllParams();
		
		$params['user_id'] = $this->ident['user_id'];
		Zend_Loader::LoadClass('actionplanClass', $this->modelDir);
		$actionplanClass = new actionplanClass();

		if(!empty($params['y'])) $this->view->selectedYear = $year = $params['y'];
		else $this->view->selectedYear = $year = date("Y");
		
		$this->view->target = $actionplanClass->getActionPlanTarget($params['c'], $year);
		
		$this->view->modules = $actionplanClass->getActionPlanModules($params['c']);
		$this->view->category_id = $params['c'];
		
		$this->view->id = $params['id'];

		Zend_Loader::LoadClass('siteClass', $this->modelDir);
		$siteClass = new siteClass();
		$this->view->sites = $siteClass->getSites();
		
		if($params['c'] == 6) $this->view->title = "Preventive Maintenance Target";
		else $this->view->title = "Action Plan Target";
		
		if($params['c'] == 1) $this->view->category = "security";
		elseif($params['c'] == 2) $this->view->category = "housekeeping";
		elseif($params['c'] == 3) $this->view->category = "safety";
		elseif($params['c'] == 5) $this->view->category = "parking";
		elseif($params['c'] == 6) $this->view->category = "engineering";

		echo $this->view->render('header.php');
        echo $this->view->render('view_action_plan_target.php');
        echo $this->view->render('footer.php');
	}
	
	public function savetargetAction() {
		$params = $this->_getAllParams();
	
		Zend_Loader::LoadClass('actionplanClass', $this->modelDir);
		$actionplanClass = new actionplanClass();
	
		$this->view->id = $actionplanClass->saveActionPlanTarget($params);
			

		$this->cache->remove("ap_modules_".$this->site_id."_".$params['category_id']."_".date("Y"));
		$this->cache->remove("ap_schedule_".$this->site_id."_".$params['category_id']."_".date("Y"));
		$this->cache->remove("ap_week_total_".$this->site_id."_".$params['category_id']."_".date("Y"));
		$this->cache->remove("ap_calendar_".$this->site_id."_".$params['category_id']."_".date("Y"));
	}
	
	public function gettargetbyidAction() {
		$params = $this->_getAllParams();
		Zend_Loader::LoadClass('actionplanClass', $this->modelDir);
		$actionplanClass = new actionplanClass();
		
		if(!empty($params['id'])) 
		{
			echo json_encode($actionplanClass->getActionPlanTargetById($params['id']));
		}
	}
	
	public function deletetargetbyidAction() {
		$params = $this->_getAllParams();
		Zend_Loader::LoadClass('actionplanClass', $this->modelDir);
		$actionplanClass = new actionplanClass();
		$actionplanClass->deleteActionPlanTargetById($params['id']);

		$this->cache->remove("ap_modules_".$this->site_id."_".$params['c']."_".date("Y"));
		$this->cache->remove("ap_schedule_".$this->site_id."_".$params['c']."_".date("Y"));
		$this->cache->remove("ap_week_total_".$this->site_id."_".$params['c']."_".date("Y"));
		$this->cache->remove("ap_calendar_".$this->site_id."_".$params['c']."_".date("Y"));
		
		$this->_response->setRedirect($this->baseUrl.'/admin/actionplan/target/c/'.$params['c']);
		$this->_response->sendResponse();
		exit();
	}
	
	public function gettargetbymoduleidAction() {
		$params = $this->_getAllParams();
		Zend_Loader::LoadClass('actionplanClass', $this->modelDir);
		$actionplanClass = new actionplanClass();
		
		if(!empty($params['mid'])) 
		{
			echo json_encode($actionplanClass->getActionPlanTargetByModuleId($params['mid']));
		}
	}

	public function copytargetAction() {
		$params = $this->_getAllParams();
	
		Zend_Loader::LoadClass('actionplanClass', $this->modelDir);
		$actionplanClass = new actionplanClass();

		$target = $actionplanClass->getActionPlanTargetById($params['action_plan_target_id']);

		$module = $actionplanClass->getActionPlanModuleById($target['action_plan_module_id']);
		
		Zend_Loader::LoadClass('siteClass', $this->modelDir);
		$siteClass = new siteClass();
		
		$target['category_id'] = $params['category_id'];
		$msg = "";

		foreach($params['site_id'] as $site_id) {
			$newModule = $actionplanClass->getActionPlanModuleByModuleName($module['module_name'], $params['category_id'], $params['year'], $site_id);
			$target['new_action_plan_module_id'] = $newModule['action_plan_module_id'];			
			$site = $siteClass->getSite($site_id);
			
			$checkExistingTarget = $actionplanClass->getActionPlanTargetByTargetAndModule($newModule['action_plan_module_id'], $target['target_name'], $params['category_id'], $params['year'], $site_id);
			if(empty($checkExistingTarget))
			{
			$target['site_id'] = $site_id;
			$actionplanClass->copyActionPlanTargetToOtherSite($target);
			$this->cache->remove("ap_modules_".$site_id."_".$params['category_id']."_".$params['year']);
			$this->cache->remove("ap_schedule_".$site_id."_".$params['category_id']."_".$params['year']);
			$this->cache->remove("ap_week_total_".$site_id."_".$params['category_id']."_".$params['year']);
			$this->cache->remove("ap_calendar_".$site_id."_".$params['category_id']."_".$params['year']);
				$msg = $msg . "Target has been copied to ".$site['initial']."\n";		
			}
			else
			{
				$msg = $msg . "Target cannot be copied to ".$site['initial']." because it's already exist\n";
			}
		}
		echo $msg; 
	}
	
	/*** ACTIVITY ***/
	
	public function viewactivityAction() {
		$params = $this->_getAllParams();
		
		$params['user_id'] = $this->ident['user_id'];
		Zend_Loader::LoadClass('actionplanClass', $this->modelDir);
		$actionplanClass = new actionplanClass();

		if(!empty($params['y'])) $this->view->selectedYear = $year = $params['y'];
		else $this->view->selectedYear = $year = date("Y");
		
		$activity = $actionplanClass->getActionPlanActivity($params['c'], $year);		
		
		foreach($activity as &$a) {
			$delegated_to = "";
			if(!empty($a['chief'])) $delegated_to .= "Chief, ";
			if(!empty($a['spv'])) $delegated_to .= "Spv, ";
			if(!empty($a['staff'])) $delegated_to .= "Staff, ";
			if(!empty($a['admin'])) $delegated_to .= "Admin, ";
			$a['delegated_to'] = substr($delegated_to, 0,-2);
		}
		$this->view->activity = $activity;
		
		$this->view->target = $actionplanClass->getActionPlanTarget($params['c'], $year);
		$this->view->category_id = $params['c'];

		Zend_Loader::LoadClass('siteClass', $this->modelDir);
		$siteClass = new siteClass();
		$this->view->sites = $siteClass->getSites();
		
		if($params['c'] == 6) $this->view->title = "Preventive Maintenance Activity";
		else $this->view->title = "Action Plan Activity";
		
		if($params['c'] == 1) $this->view->category = "security";
		elseif($params['c'] == 2) $this->view->category = "housekeeping";
		elseif($params['c'] == 3) $this->view->category = "safety";
		elseif($params['c'] == 5) $this->view->category = "parking";
		elseif($params['c'] == 6) $this->view->category = "engineering";

		echo $this->view->render('header.php');
        echo $this->view->render('view_action_plan_activity.php');
        echo $this->view->render('footer.php');
	}
	
	public function saveactivityAction() {
		$params = $this->_getAllParams();
	
		Zend_Loader::LoadClass('actionplanClass', $this->modelDir);
		$actionplanClass = new actionplanClass();
	
		$params['chief'] = "1";
		$params['spv'] = "1";
		$params['staff'] = "1";
		$params['admin'] = "1";	
		$this->view->id = $actionplanClass->saveActionPlanActivity($params);//js

		$this->cache->remove("ap_modules_".$this->site_id."_".$params['category_id']."_".$params['year']);
		$this->cache->remove("ap_schedule_".$this->site_id."_".$params['category_id']."_".$params['year']);
		$this->cache->remove("ap_week_total_".$this->site_id."_".$params['category_id']."_".$params['year']);
		$this->cache->remove("ap_calendar_".$this->site_id."_".$params['category_id']."_".$params['year']);
	}
	
	public function getactivitybyidAction() {
		$params = $this->_getAllParams();
		Zend_Loader::LoadClass('actionplanClass', $this->modelDir);
		$actionplanClass = new actionplanClass();
		
		if(!empty($params['id'])) 
		{
			echo json_encode($actionplanClass->getActionPlanActivityById($params['id']));
		}
	}
	
	public function deleteactivitybyidAction() {
		$params = $this->_getAllParams();
		Zend_Loader::LoadClass('actionplanClass', $this->modelDir);
		$actionplanClass = new actionplanClass();
		
		$actionplanClass->deleteActionPlanActivityById($params['id']);

		$this->cache->remove("ap_modules_".$this->site_id."_".$params['c']."_".$params['y']);
		$this->cache->remove("ap_schedule_".$this->site_id."_".$params['c']."_".$params['y']);
		$this->cache->remove("ap_week_total_".$this->site_id."_".$params['c']."_".$params['y']);
		$this->cache->remove("ap_calendar_".$this->site_id."_".$params['c']."_".$params['y']);
		
		$this->_response->setRedirect($this->baseUrl.'/default/actionplan/activity/c/'.$params['c']);
		$this->_response->sendResponse();
		exit();
	}
	
	public function getactivitybytargetidAction() {
		$params = $this->_getAllParams();
		Zend_Loader::LoadClass('actionplanClass', $this->modelDir);
		$actionplanClass = new actionplanClass();
		
		if(!empty($params['tid'])) 
		{
			echo json_encode($actionplanClass->getActionPlanActivityByTargetId($params['tid']));
		}
	}

	public function copyactivityAction() {
		$params = $this->_getAllParams();
		Zend_Loader::LoadClass('actionplanClass', $this->modelDir);
		$actionplanClass = new actionplanClass();

		$activity = $actionplanClass->getActionPlanActivityById($params['action_plan_activity_id']);
		$target = $actionplanClass->getActionPlanTargetById($activity['action_plan_target_id']);
		
		Zend_Loader::LoadClass('siteClass', $this->modelDir);
		$siteClass = new siteClass();
		
		$activity['category_id'] = $params['category_id'];
		$msg = "";

		foreach($params['site_id'] as $site_id) {
			$newTarget = $actionplanClass->getActionPlanTargetByTargetName($target['target_name'], $params['category_id'], $site_id, $params['year']);
			$activity['new_action_plan_target_id'] = $newTarget['action_plan_target_id'];	
			$site = $siteClass->getSite($site_id);
			
			$checkExistingActivity = $actionplanClass->getActionPlanActivityByActivityAndTarget($newTarget['action_plan_target_id'], $activity['activity_name'], $params['category_id'], $params['year'], $site_id);
			
			if(empty($checkExistingActivity))
			{
			$activity['site_id'] = $site_id;
			$actionplanClass->copyActionPlanActivityToOtherSite($activity);
			$this->cache->remove("ap_modules_".$site_id."_".$params['category_id']."_".$params['year']);
			$this->cache->remove("ap_schedule_".$site_id."_".$params['category_id']."_".$params['year']);
			$this->cache->remove("ap_week_total_".site_id."_".$params['category_id']."_".$params['year']);
			$this->cache->remove("ap_calendar_".$site_id."_".$params['category_id']."_".$params['year']);
		$msg = $msg . "Activity has been copied to ".$site['initial']."\n";
			}
			
			else
			{
				$msg = $msg . "Activity cannot be copied to ".$site['initial']." because it's already exist\n";
			}
		}
		echo $msg; 
	}
	
	/*** REMINDER ***/
	
	public function reminderAction() {
		$params = $this->_getAllParams();
		
		$params['user_id'] = $this->ident['user_id'];
		Zend_Loader::LoadClass('actionplanClass', $this->modelDir);
		$actionplanClass = new actionplanClass();
		
		$this->view->email = $actionplanClass->getActionPlanEmail($params['c'], $this->site_id);
		
		$this->view->category_id = $params['c'];
		
		if($params['c'] == 6) $this->view->title = "Preventive Maintenance Reminder Email";
		else $this->view->title = "Action Plan Reminder Email";
		
		if($params['c'] == 1) $this->view->category = "security";
		elseif($params['c'] == 2) $this->view->category = "housekeeping";
		elseif($params['c'] == 3) $this->view->category = "safety";
		elseif($params['c'] == 5) $this->view->category = "parking";
		elseif($params['c'] == 6) $this->view->category = "engineering";

		echo $this->view->render('header.php');
        echo $this->view->render('view_action_plan_reminder.php');
        echo $this->view->render('footer.php');
	}
	
	public function saveemailAction() {
		$params = $this->_getAllParams();
	
		Zend_Loader::LoadClass('actionplanClass', $this->modelDir);
		$actionplanClass = new actionplanClass();
	
		$this->view->email = $actionplanClass->saveActionPlanEmail($params);
	}
	
	public function getemailbyidAction() {
		$params = $this->_getAllParams();
		Zend_Loader::LoadClass('actionplanClass', $this->modelDir);
		$actionplanClass = new actionplanClass();
		
		if(!empty($params['id'])) 
		{
			echo json_encode($actionplanClass->getActionPlanEmailById($params['id']));
		}
	}
	
	public function deleteemailbyidAction() {
		$params = $this->_getAllParams();
		Zend_Loader::LoadClass('actionplanClass', $this->modelDir);
		$actionplanClass = new actionplanClass();
		$actionplanClass->deleteActionPlanEmailById($params['id']);
		
		$this->_response->setRedirect($this->baseUrl.'/default/actionplan/reminder/c/'.$params['c']);
		$this->_response->sendResponse();
		exit();
	}

	/*** 
	 *  https://srtadmin.pakuwon.com/admin/removecache/catid/1/y/2020 
	 *  https://srtadmin.pakuwon.com/admin/removecache/catid/3/y/2020 
	 *  https://srtadmin.pakuwon.com/admin/removecache/catid/5/y/2020 
	 * ***/
	function removecacheAction() {
		$params = $this->_getAllParams();

		$this->cache->remove("ap_schedule_".$this->site_id."_".$params['catid']."_".$params['y']);
		$this->cache->remove("ap_week_total_".$this->site_id."_".$params['catid']."_".$params['y']);
		$this->cache->remove("ap_calendar_".$this->site_id."_".$params['catid']."_".$params['y']);
	}
	
}
?>
