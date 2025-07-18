<?php
require_once('actionControllerBase.php');

class ActionplanController extends actionControllerBase
{
	public function view2Action() {
		$params = $this->_getAllParams();
		
		Zend_Loader::LoadClass('actionplanClass', $this->modelDir);
		$actionplanClass = new actionplanClass();

		if(empty($params['y'])) $year = date("Y");
		else $year = $params['y'];

		$this->view->selectedYear = $year;

		$this->view->modules = $actionplanClass->getActionPlanModules($params['c'], $year);

		//$moduleTargetActivity = $this->cache->load("ap_module_target_activity_".$this->site_id."_".$params['c']."_".$year);	
		if(empty($moduleTargetActivity))
		{
			$moduleTargetActivity = $actionplanClass->getActionPlanActivityTargetModule($params['c'], $year);
			//$this->cache->save($moduleTargetActivity, "ap_module_target_activity_".$this->site_id."_".$params['c']."_".$year, array("ap_module_target_activity_".$this->site_id."_".$params['c']."_".$year), 0);
		}

		//$activitySchedule = $this->cache->load("ap_schedule_".$this->site_id."_".$params['c']."_".$year);	
		if(empty($activitySchedule))
		{
			$activitySchedule = array();
			$schedule_dates = $actionplanClass->getScheduleDatesByCategoryId($this->site_id, $params['c'], $year);
			if(!empty($schedule_dates))
			{
				foreach($schedule_dates as $sdate)
				{
					$activitySchedule[$sdate['action_plan_activity_id']][$sdate['month']][$sdate['week']] = $sdate;
				}
			}
			//$this->cache->save($activitySchedule, "ap_schedule_".$this->site_id."_".$params['c']."_".$year, array("ap_schedule_".$this->site_id."_".$params['c']."_".$year), 0);
		}
		
		
		if(!empty($moduleTargetActivity))
		{
			$totalActivityPerTarget = array();
			foreach($moduleTargetActivity as &$activity) {
				$totalActivityPerTarget[$activity['action_plan_target_id']] = $totalActivityPerTarget[$activity['action_plan_target_id']]+1;
				$activity['totalDone'] = $totalDone1 = $actionplanClass->getTotalDoneSchedule($activity['action_plan_activity_id'], $this->site_id, $year);
				$activity['total'] = $total1 = intval($activity['total_schedule']);
				if($total1>0) $activity['percentage1'] = ($totalDone1/$total1)*100;
				else $activity['percentage1'] = 0;
				$weekTotal = 0;					
				for($m=0; $m<12; $m++) {
					$month[$m]['month_name'] = date("F", mktime(0, 0, 0, $m+1, 1, $year));
					$month[$m]['no_of_weeks'] = $this->numberOfWeeks($m+1,$year);
					for($w=1; $w <= $month[$m]['no_of_weeks']; $w++) {	
						$site1data = $activitySchedule[$activity['action_plan_activity_id']][$m+1][$w];
						$site1_date = $site1data['schedule_date'];
						$site1_date1 = explode(" ", $site1_date);
						$site1_date2 = explode("-", $site1_date1[0]);
						$activity['month'][$m][$w]['site1'] = $site1_date2[2];
						$activity['month'][$m][$w]['site1_schedule_id'] = $site1data['schedule_id'];
						$activity['month'][$m][$w]['site1_site_id'] = $site1data['site_id'];
						$activity['month'][$m][$w]['status'] = $site1data['status'];
					}
					$weekTotal = $weekTotal + $month[$m]['no_of_weeks'];
				}
				$a++;
			}
		}

		$this->view->schedule = $moduleTargetActivity;
		
		$this->view->weekTotal = $weekTotal;
		
		$this->view->calendar = $month;

		$this->view->totalActivityPerTarget = $totalActivityPerTarget;
		
		$this->view->category_id = $params['c'];

		Zend_Loader::LoadClass('categoryClass', $this->modelDir);
		$categoryClass = new categoryClass();
	
		$this->view->category = $category = $categoryClass->getCategoryById($params['c']);

		$this->view->done = $actionplanClass->getTotalDone($this->site_id, $params['c'], $year);
		$this->view->outstanding = $actionplanClass->getTotalOutstanding($this->site_id, $params['c'], $year);
		$this->view->reschedule = $actionplanClass->getTotalReschedule($this->site_id, $params['c'], $year);
		$this->view->upcoming = $actionplanClass->getTotalUpcoming($this->site_id, $params['c'], $year);

		$this->view->hideScrollbar = 1;

		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "View ".$this->ident['initial']." ".$category['category_name']." Action Plan ".$year;
		$logData['data'] = json_encode($params);
		$logsTable->insertLogs($logData);	
		
		$this->renderTemplate('action_plan_view.tpl'); 
	}
	
	function numberOfWeeks($month, $year){
		$firstday = date("w", mktime(0, 0, 0, $month, 1, $year)); 
		$lastday = date("t", mktime(0, 0, 0, $month, 1, $year));
		$count_weeks = 1 + ceil(($lastday-8+$firstday)/7);
		return $count_weeks;
	} 
	
	public function viewAction() {
		$params = $this->_getAllParams();
		
		Zend_Loader::LoadClass('actionplanClass', $this->modelDir);
		$actionplanClass = new actionplanClass();

		if(empty($params['y'])) $year = date("Y");
		else $year = $params['y'];

		$this->view->selectedYear = $year;
		
		$this->view->modules = $actionplanClass->getActionPlanModules($params['c'], $year);

		if(empty($params['m'])) $month = date("n");
		else $month = $params['m'];
		
		$schedules = $actionplanClass->getScheduleByMonthYear($params['c'], $month, $year);
		foreach($schedules as &$schedule)
		{
			$scheduledate= explode(" ",$schedule['schedule_date']);
			$schedule['date'] = $scheduledate[0];
			$schedule['totalDone'] = $totalDone = $actionplanClass->getTotalDoneSchedule($schedule['action_plan_activity_id'], $this->site_id, $year);
			$schedule['total'] = $total = intval($schedule['total_schedule']);
			if($total>0) $schedule['percentage'] = ($totalDone/$total)*100;
			else $schedule['percentage'] = 0;
		}
		$this->view->schedules = $schedules;
		//print_r($schedules); exit();
		//$this->view->totalActivityPerTarget = $totalActivityPerTarget;
		
		$this->view->category_id = $params['c'];

		Zend_Loader::LoadClass('categoryClass', $this->modelDir);
		$categoryClass = new categoryClass();
	
		$this->view->category = $category = $categoryClass->getCategoryById($params['c']);

		$this->view->done = $actionplanClass->getTotalDone($this->site_id, $params['c'], $year);
		$this->view->outstanding = $actionplanClass->getTotalOutstanding($this->site_id, $params['c'], $year);
		$this->view->reschedule = $actionplanClass->getTotalReschedule($this->site_id, $params['c'], $year);
		$this->view->upcoming = $actionplanClass->getTotalUpcoming($this->site_id, $params['c'], $year);

		$this->view->hideScrollbar = 1;

		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "View ".$this->ident['initial']." ".$category['category_name']." Action Plan ".$year;
		$logData['data'] = json_encode($params);
		$logsTable->insertLogs($logData);	
		
		$this->renderTemplate('action_plan_view2.tpl'); 
	}
	
	public function savescheduleAction() {
		$params = $this->_getAllParams();
		$params['user_id'] = $this->ident['user_id'];
		
		Zend_Loader::LoadClass('actionplanClass', $this->modelDir);
		$actionplanClass = new actionplanClass();
		$msg = "";
		$activity = $actionplanClass->getActionPlanActivityById($params['action_plan_activity_id']);
		if(!empty($params['schedule_date'])) {
			foreach($params['schedule_date'] as $schedule_date)
			{
				if(!empty($schedule_date) && strlen($schedule_date) == 10)
				{
					$params['schedule'] = $schedule_date;
					$params['week'] = $this->weekOfMonth($schedule_date);
					$dt = explode("-", $schedule_date);
					$params['month'] = $dt[1];
					if(empty($year)) $year = $dt[0];
					$s_date = mktime(0, 0, 0, intval($dt[1]), intval($dt[2]), intval($dt[0]));
					$schedule = date("l, j F Y", $s_date);
					// check total of existing schedule date
					$totalExisting = $actionplanClass->totalSchedulesByActivity($params['action_plan_activity_id']);
					$totalSchedule = $actionplanClass->getActivityTotalSchedule($params['action_plan_activity_id']);
					if($totalExisting < $totalSchedule)
					{
						// check if schedule date is already exist
						$existing = $actionplanClass->checkifscheduledateexist($params['action_plan_activity_id'],$params['schedule'], $params['week'], $params['month'], $year);
						if(empty($existing))
						{
							$actionplanClass->saveActionPlanSchedule($params);
							$msg .= "Action plan for ".$activity['activity_name']." on ".$schedule." has been successfully added.<br/>";
							//$this->cache->remove("ap_schedule_".$this->site_id."_".$params['category_id']."_".$year);
						}
						else
						{
							$msg .= '<font color="red">Action plan for '.$activity['activity_name']." on ".$schedule." cannot be added because there is already a schedule for that week.</font><br/>";
						}
					}
					else
					{
						$msg .= '<font color="red">Action plan for '.$activity['activity_name']." on ".$schedule." cannot be added because it has reached the maximum number of total schedule.</font><br/>";
					}
				}
			}			
		}

		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "Save Action Plan Schedule";
		$logData['data'] = json_encode($params);
		$logsTable->insertLogs($logData);	

		echo $msg;
	}
	
	function weekOfMonth($date) {		
		$currentWeek = ceil((date("d",strtotime($date)) - date("w",strtotime($date)) - 1) / 7) + 1;
		return $currentWeek;
	}
	
	public function updatestatusscheduleAction()
	{
		$params = $this->_getAllParams();
		$params['user_id'] = $this->ident['user_id'];
		
		Zend_Loader::LoadClass('actionplanClass', $this->modelDir);
		$actionplanClass = new actionplanClass();

		Zend_Loader::LoadClass('categoryClass', $this->modelDir);
		$categoryClass = new categoryClass();		
		$category = $categoryClass->getCategoryById($params['category_id']);

		$sch = $actionplanClass->getActionPlanScheduleById($params['action_plan_schedule_id'], $this->site_id);
		$year = date("Y", strtotime($sch['schedule_date']));

		if(($params['update_status_schedule'] == "done" || $params['additional_attachment'] == 1 ) && $params['notapprove'] != "1")
		{
			$i=0;
			if(!empty($_FILES["attachment"]))
			{
				foreach($_FILES["attachment"]['name'] as $attachment)
				{
					if(!empty($attachment)) {
						$attachment_id = $actionplanClass->addScheduleAttachment($params);
						if($attachment)
						{
							$ext = explode(".",$attachment);
							$filename = $attachment_id.".".$ext[count($ext)-1];
							$datafolder = $this->config->paths->html."/actionplan/".strtolower(str_replace(" & ", "", $category['category_name']))."/";
							if(move_uploaded_file($_FILES["attachment"]["tmp_name"][$i], $datafolder.$filename))
							{
								$actionplanClass->updateScheduleAttachment($attachment_id,'filename', $filename);
								$actionplanClass->updateScheduleAttachment($attachment_id,'description', $params['description'][$i]);
								
								if(in_array(strtolower($ext[count($ext)-1]), array("jpg", "jpeg", "png","bmp")))
								{
									$magickPath = "/usr/bin/convert";
									/*** resize image if size greater than 500 Kb ***/
									if(filesize($datafolder.$filename) > 500000) exec($magickPath . ' ' . $datafolder.$filename . ' -resize 800x800\> ' . $datafolder.$filename);
								}								
								$actionplanClass->updateschedulestatus($params['action_plan_schedule_id'], '1');
							}						
							$i++;
						}
					}
				}
			}
			if($this->showActionPlanSetting == 1) $actionplanClass->updateAllowAdditionalUpload($params['action_plan_schedule_id'], $params['allow_upload']);
			//$this->cache->remove("ap_schedule_".$this->site_id."_".$params['category_id']."_".$year);

			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "Add Action Plan Schedule Attachment";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);	
		}
		elseif($params['update_status_schedule'] == "reschedule")
		{
			$actionplanClass->reschedule($params);
			$actionplanClass->updateschedulestatus($params['action_plan_schedule_id'], '2');
			//$this->cache->remove("ap_schedule_".$this->site_id."_".$params['category_id']."_".$year);

			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "Reschedule Action Plan Schedule";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);	
		}
		elseif($params['notapprove'] == "1")
		{
			$id = $actionplanClass->saveCQC($params);
			
			if(!empty($_FILES["cqc_attachment"]))
			{
				$ext = explode(".",$_FILES["cqc_attachment"]['name']);
				$filename = $id.".".$ext[count($ext)-1];
				$datafolder = $this->config->paths->html."/actionplan/cqc/".strtolower(str_replace(" & ", "", $category['category_name']))."/";
				if(move_uploaded_file($_FILES["cqc_attachment"]["tmp_name"], $datafolder.$filename))
				{								
					if(in_array(strtolower($ext[count($ext)-1]), array("jpg", "jpeg", "png","bmp")))
					{
						$magickPath = "/usr/bin/convert";
						/*** resize image if size greater than 500 Kb ***/
						if(filesize($datafolder.$filename) > 500000) exec($magickPath . ' ' . $datafolder.$filename . ' -resize 800x800\> ' . $datafolder.$filename);
					}								
					$actionplanClass->updateCQC($id, $filename);
				}	
			}
			$totalCQC = $actionplanClass->getTotalCQCByScheduleId($params['action_plan_schedule_id']);
			if($totalCQC > 1)
			{
				if($totalCQC == 2) $rating = 2;
				elseif($totalCQC == 3) $rating = 1;
				elseif($totalCQC > 3) $rating = 0;
				$actionplanClass->updateScheduleRating($params['action_plan_schedule_id'], $rating);
			}
		}
		
		$this->_response->setRedirect($this->baseUrl.'/default/actionplan/view/c/'.$params['category_id']);
		$this->_response->sendResponse();
		exit();
	}
	
	public function getschedulebyidAction() {
		$params = $this->_getAllParams();
		
		if(empty($params['y'])) $year = date("Y");
		else $year = $params['y'];
		
		Zend_Loader::LoadClass('actionplanClass', $this->modelDir);
		$actionplanClass = new actionplanClass();
		
		$schedule = $actionplanClass->getActionPlanScheduleById($params['id'], $params['site_id']);
		$date = explode(" ",$schedule['schedule_date']);
		$schedule['date']=date("j M Y", strtotime($date[0]));
		
		$date2 = explode(" ",$schedule['reschedule_date']);
		$schedule['reschedule']=date("j M Y", strtotime($date2[0]));
		
		$schedule['totalDone'] = $totalDone = $actionplanClass->getTotalDoneSchedule($schedule['action_plan_activity_id'], $this->site_id, $year);
		$schedule['total'] = $total = intval($schedule['total_schedule']);
		if($total>0) $schedule['percentage'] = ($totalDone/$total)*100;
		else $schedule['percentage'] = 0;
		
		if(!empty($params['id'])) 
		{
			echo json_encode($schedule);
		}
	}
	
	public function getschedulebymonthyearAction() {
		$params = $this->_getAllParams();
		
		Zend_Loader::LoadClass('actionplanClass', $this->modelDir);
		$actionplanClass = new actionplanClass();

		if(empty($params['y'])) $year = date("Y");
		else $year = $params['y'];

		if(empty($params['m'])) $month = date("n");
		else $month = $params['m'];
		
		$schedules = $actionplanClass->getScheduleByMonthYear($params['c'], $month, $year);
		$data= array();
		$i = 0;
		foreach($schedules as &$schedule)
		{
			$scheduledate= explode(" ",$schedule['schedule_date']);
			$schedule['date'] = $scheduledate[0];
			$schedule['totalDone'] = $totalDone = $actionplanClass->getTotalDoneSchedule($schedule['action_plan_activity_id'], $this->site_id, $year);
			$schedule['total'] = $total = intval($schedule['total_schedule']);
			if($total>0) $schedule['percentage'] = ($totalDone/$total)*100;
			else $schedule['percentage'] = 0;
			$data[$i]['title'] = $schedule['activity_name']." (".$schedule['percentage']."%) (".$schedule['totalDone']."/".$schedule['total'].")";
			$data[$i]['start'] = $schedule['date'];
			$data[$i]['id'] = $schedule['schedule_id'];
			if($schedule['status'] == 1) $data[$i]['color'] = '#a1a2a6'; else if($schedule['status'] == 2) $data[$i]['color'] = 'darkorange';
			$i++;
		}
		
		echo json_encode($data);
	}

	/*** ACTION PLAN MODULE ***/
	
	public function moduleAction() {
		$params = $this->_getAllParams();
		
		$params['user_id'] = $this->ident['user_id'];
		Zend_Loader::LoadClass('actionplanClass', $this->modelDir);
		$actionplanClass = new actionplanClass();
		
		$this->view->modules = $actionplanClass->getActionPlanModules($params['c']);
		$this->view->category_id = $params['c'];

		$siteClass = $this->loadModel('site');
		$this->view->sites = $siteClass->getSites();

		$this->renderTemplate('action_plan_module.tpl');
	}
	
	public function savemoduleAction() {
		$params = $this->_getAllParams();
	
		Zend_Loader::LoadClass('actionplanClass', $this->modelDir);
		$actionplanClass = new actionplanClass();
	
		$this->view->module = $actionplanClass->saveActionPlanModule($params);

		//$this->cache->remove("ap_modules_".$this->site_id."_".$params['category_id']."_".$params['show_year']);
		//$this->cache->remove("ap_schedule_".$this->site_id."_".$params['category_id']."_".$params['show_year']);
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

		//$this->cache->remove("ap_modules_".$this->site_id."_".$params['c']."_".$deletedModule['show_year']);
		//$this->cache->remove("ap_schedule_".$this->site_id."_".$params['c']."_".$deletedModule['show_year']);
		
		$this->_response->setRedirect($this->baseUrl.'/default/actionplan/module/c/'.$params['c']);
		$this->_response->sendResponse();
		exit();
	}
	
	public function copymoduleAction() {
		$params = $this->_getAllParams();
	
		Zend_Loader::LoadClass('actionplanClass', $this->modelDir);
		$actionplanClass = new actionplanClass();

		$module = $actionplanClass->getActionPlanModuleById($params['action_plan_module_id']);
		$module['site_id'] = $params['site_id'];
	
		$actionplanClass->copyActionPlanModuleToOtherSite($module);

		//$this->cache->remove("ap_modules_".$params['site_id']."_".$params['category_id']."_".date("Y"));
		//$this->cache->remove("ap_schedule_".$params['site_id']."_".$params['category_id']."_".date("Y"));
	}
	
	
	/*** ACTION PLAN TARGET ***/
	
	public function targetAction() {
		$params = $this->_getAllParams();
		
		$params['user_id'] = $this->ident['user_id'];
		Zend_Loader::LoadClass('actionplanClass', $this->modelDir);
		$actionplanClass = new actionplanClass();
		
		$this->view->target = $actionplanClass->getActionPlanTarget($params['c']);
		
		$this->view->modules = $actionplanClass->getActionPlanModules($params['c']);
		$this->view->category_id = $params['c'];

		$siteClass = $this->loadModel('site');
		$this->view->sites = $siteClass->getSites();

		$this->renderTemplate('action_plan_target.tpl');
	}
	
	public function savetargetAction() {
		$params = $this->_getAllParams();
	
		Zend_Loader::LoadClass('actionplanClass', $this->modelDir);
		$actionplanClass = new actionplanClass();
	
		$this->view->target = $actionplanClass->saveActionPlanTarget($params);

		//$this->cache->remove("ap_modules_".$this->site_id."_".$params['category_id']."_".date("Y"));
		//$this->cache->remove("ap_schedule_".$this->site_id."_".$params['category_id']."_".date("Y"));
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

		/*$this->cache->remove("ap_modules_".$this->site_id."_".$params['c']."_".date("Y"));
		$this->cache->remove("ap_schedule_".$this->site_id."_".$params['c']."_".date("Y"));
		$this->cache->remove("ap_week_total_".$this->site_id."_".$params['c']."_".date("Y"));
		$this->cache->remove("ap_calendar_".$this->site_id."_".$params['c']."_".date("Y"));*/
		
		$this->_response->setRedirect($this->baseUrl.'/default/actionplan/target/c/'.$params['c']);
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
		$target['site_id'] = $params['site_id'];

		$module = $actionplanClass->getActionPlanModuleById($target['action_plan_module_id']);
		
		$newModule = $actionplanClass->getActionPlanModuleByModuleName($module['module_name'], $params['site_id']);
	
		$target['new_action_plan_module_id'] = $newModule['action_plan_module_id'];

		$actionplanClass->copyActionPlanTargetToOtherSite($target);

		/*$this->cache->remove("ap_modules_".$params['site_id']."_".$params['category_id']."_".date("Y"));
		$this->cache->remove("ap_schedule_".$params['site_id']."_".$params['category_id']."_".date("Y"));*/
	}
	
	/*** ACTIVITY ***/
	
	public function activityAction() {
		$params = $this->_getAllParams();
		
		$params['user_id'] = $this->ident['user_id'];
		Zend_Loader::LoadClass('actionplanClass', $this->modelDir);
		$actionplanClass = new actionplanClass();
		
		$this->view->activity = $actionplanClass->getActionPlanActivity($params['c']);
		
		$this->view->target = $actionplanClass->getActionPlanTarget($params['c']);
		$this->view->category_id = $params['c'];

		$siteClass = $this->loadModel('site');
		$this->view->sites = $siteClass->getSites();

		$this->renderTemplate('action_plan_activity.tpl');
	}
	
	public function saveactivityAction() {
		$params = $this->_getAllParams();
	
		Zend_Loader::LoadClass('actionplanClass', $this->modelDir);
		$actionplanClass = new actionplanClass();
	
		$this->view->target = $actionplanClass->saveActionPlanActivity($params);

		/*$this->cache->remove("ap_modules_".$this->site_id."_".$params['category_id']."_".date("Y"));
		$this->cache->remove("ap_schedule_".$this->site_id."_".$params['category_id']."_".date("Y"));*/
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

		/*$this->cache->remove("ap_modules_".$this->site_id."_".$params['c']."_".date("Y"));
		$this->cache->remove("ap_schedule_".$this->site_id."_".$params['c']."_".date("Y"));*/
		
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

		foreach($params['site_id'] as $site_id) {
			$activity = $actionplanClass->getActionPlanActivityById($params['action_plan_activity_id']);
			$activity['site_id'] = $site_id;

			$target = $actionplanClass->getActionPlanTargetById($activity['action_plan_target_id']);
			
			$newTarget = $actionplanClass->getActionPlanTargetByTargetName($target['target_name'], $site_id);
			
			$activity['new_action_plan_target_id'] = $newTarget['action_plan_target_id'];
			
			$actionplanClass->copyActionPlanActivityToOtherSite($activity);

			/*$this->cache->remove("ap_modules_".$site_id."_".$params['category_id']."_".date("Y"));
			$this->cache->remove("ap_schedule_".$site_id."_".$params['category_id']."_".date("Y"));*/
		}
		
	}
	
	/*** REMINDER ***/
	
	public function reminderAction() {
		$params = $this->_getAllParams();
		
		$params['user_id'] = $this->ident['user_id'];
		Zend_Loader::LoadClass('actionplanClass', $this->modelDir);
		$actionplanClass = new actionplanClass();
		
		$this->view->email = $actionplanClass->getActionPlanEmail($params['c'], $this->site_id);
		
		$this->view->category_id = $params['c'];

		$this->renderTemplate('action_plan_reminder.tpl');
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
	

	/*** SEND DAILY REMINDER  ***/
	public function sendreminderAction() {
		$params = $this->_getAllParams();
		
		Zend_Loader::LoadClass('actionplanClass', $this->modelDir);
		$actionplanClass = new actionplanClass();
		
		$schedule = $actionplanClass->getTomorrowSchedule($params['category_id'], $params['site_id']);
		
		if(!empty($schedule))
		{
			$categoryClass = $this->loadModel('category');
			$category = $categoryClass->getCategoryById($params['category_id']);	
						
			$botToken = '476346623:AAFlB9X5-bShAkdTK9ziNDrfZ0TCePXl2cY';
			$website="https://api.telegram.org/bot".$botToken;
			$chatId=$category['site_'.$params['site_id']];  //Receiver Chat Id 
		
			$html = '<table cellpadding="0" cellspacing="0">
						<tr>
							<th style="background-color:#555; color: #fff; border:1px solid #fff; padding:5px;">No.</th>
							<th style="background-color:#555; color: #fff; border:1px solid #fff; padding:5px;">Module</th>
							<th style="background-color:#555; color: #fff; border:1px solid #fff; padding:5px;">Target</th>
							<th style="background-color:#555; color: #fff; border:1px solid #fff; padding:5px;">Activity</th>
							<th style="background-color:#555; color: #fff; border:1px solid #fff; padding:5px;">Date</th>
							<th style="background-color:#555; color: #fff; border:1px solid #fff; padding:5px;">Document as approves</th>
							<th style="background-color:#555; color: #fff; border:1px solid #fff; padding:5px;">Remark</th>
						</tr>';
			$i=1;
				$txt = '[ACTION PLAN DAILY REMINDER]

';
				foreach($schedule as $sch)
				{
					$date = explode(" ",$sch['schedule_date']);
					$schedule_date = date("j F Y", strtotime($date[0]));
					$html.='<tr>
							<td style="border:1px solid #bbb; padding:5px;">'.$i.'</td>
							<td style="border:1px solid #bbb; padding:5px;">'.$sch['module_name'].'</td>
							<td style="border:1px solid #bbb; padding:5px;">'.$sch['target_name'].'</td>
							<td style="border:1px solid #bbb; padding:5px;">'.$sch['activity_name'].'</td>
							<td style="border:1px solid #bbb; padding:5px;">'.$schedule_date.'</td>
							<td style="border:1px solid #bbb; padding:5px;">'.$sch['document_as_approves'].'</td>
							<td style="border:1px solid #bbb; padding:5px;">'.$sch['remark'].'</td>
							</tr>';
							
					$txt .= $sch['activity_name'].' - '.$schedule_date.'
					
';
	
					$i++;
				}
			$html .= "</table>";

			$data=array(
				'chat_id'=>$chatId,
				'text'=>$txt
			);
			$ch = curl_init($website . '/sendMessage');
			curl_setopt($ch, CURLOPT_HEADER, false);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, ($data));
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			$result = curl_exec($ch);
			curl_close($ch);

			require_once 'Zend/Mail.php';
			$mail = new Zend_Mail();
			$mail->setBodyHtml($html);
			$mail->setFrom("srt@pakuwon.com");
			
			$emails = $actionplanClass->getActionPlanEmail($params['category_id'], $params['site_id']);
			Zend_Loader::LoadClass('siteClass', $this->modelDir);
			$siteClass = new siteClass();
			$site = $siteClass->getSiteById($params['site_id']);
			
			if(!empty($emails))		
			{
				foreach($emails as $email)
				{
					$mail->addTo($email['email']);
				}
			
				$mail->setSubject($site['site_name'] . ' - '.$category['category_name'].' - Action Plan Reminder for Upcoming Schedule');
				
				try {
					//$mail->send();
					echo "success";
				}
				catch (Exception  $ex) {
					echo "failed=".$ex;
				}
				unset($mail);
					
				echo $html;
			}
		}
	}
	
	/*** ACTION PLAN RESCHEDULE ***/
	
	public function reschedulelistAction() {
		$params = $this->_getAllParams();
		
		Zend_Loader::LoadClass('actionplanClass', $this->modelDir);
		$actionplanClass = new actionplanClass();
		
		$this->view->rescheduleList = $actionplanClass->getActionPlanRescheduleList();

		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "View Action Plan Reschedule List";
		$logData['data'] = json_encode($params);
		$logsTable->insertLogs($logData);	
		
		$this->renderTemplate('action_plan_reschedule_list.tpl');
	}
	
	public function approverescheduleAction() {
		$params = $this->_getAllParams();
		
		Zend_Loader::LoadClass('actionplanClass', $this->modelDir);
		$actionplanClass = new actionplanClass();
		
		$actionplanClass->approveReschedule($params['id'], $this->ident['user_id']);
		
		$reschedule = $actionplanClass->getRescheduleById($params['id']);
		$week = $this->weekOfMonth($reschedule['reschedule_date']);
		$month = date("m", strtotime($reschedule['reschedule_date']));
		$actionplanClass->updateScheduleDate($reschedule['action_plan_schedule_id'], $reschedule['reschedule_date'], $week, $month);

		$schedule = $actionplanClass->getActionPlanScheduleById($reschedule['action_plan_schedule_id'], $this->site_id);
		$year = date("Y", strtotime($reschedule['reschedule_date']));
		//$this->cache->remove("ap_schedule_".$this->site_id."_".$schedule['category_id']."_".$year);

		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "Approve Action Plan Reschedule";
		$logData['data'] = json_encode($params);
		$logsTable->insertLogs($logData);	

		$this->_response->setRedirect($this->baseUrl.'/default/actionplan/reschedulelist');
		$this->_response->sendResponse();
		exit();
	}

	public function rejectrescheduleAction() {
		$params = $this->_getAllParams();
		
		Zend_Loader::LoadClass('actionplanClass', $this->modelDir);
		$actionplanClass = new actionplanClass();
		
		$reschedule = $actionplanClass->getRescheduleById($params['id']);
		
		$actionplanClass->updateschedulestatus($reschedule['action_plan_schedule_id'], "0");

		$actionplanClass->deleteReschedule($params['id']);

		$schedule = $actionplanClass->getActionPlanScheduleById($reschedule['action_plan_schedule_id'], $this->site_id);
		$year = date("Y", strtotime($reschedule['reschedule_date']));
		//$this->cache->remove("ap_schedule_".$this->site_id."_".$schedule['category_id']."_".$year);

		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "Reject Action Plan Reschedule";
		$logData['data'] = json_encode($params);
		$logsTable->insertLogs($logData);	

		$this->_response->setRedirect($this->baseUrl.'/default/actionplan/reschedulelist');
		$this->_response->sendResponse();
		exit();
	}


	/*** ACTION PLAN RESCHEDULE STATISTIC */

	public function reschedulestatisticAction() {
		$params = $this->_getAllParams();
		
		Zend_Loader::LoadClass('actionplanClass', $this->modelDir);
		$actionplanClass = new actionplanClass();

		if(empty($params['year'])) $params['year'] = date("Y");
		if(empty($params['filter'])) $params['filter'] ="ts";
		
		$rescheduleStatistic = $actionplanClass->getRescheduleStatistic($params);

		foreach($rescheduleStatistic as &$rs)
		{
			if($rs['total_reschedule'] > 1)
			{
				if($params['filter'] == "u") {
					$rs_detail = $actionplanClass->getReschedulesByUserId($rs['user_id'], $params['c']);
					$rs['activity_list'] = "<ul>";
					foreach($rs_detail as &$d)
					{
						$ori_date = explode(" ", $d['original_date']);
						$rs_date = explode(" ", $d['reschedule_date']);
						$rs['activity_list'] = $rs['activity_list']. "<li>".$d['target_name']." - ".$d['activity_name']." - ".date("j M Y", strtotime($ori_date[0])) ." to ".date("j M Y", strtotime($rs_date[0]))."</li>";
					}
					$rs['activity_list'] = $rs['activity_list']. "</ul>";
				}
				else
				{
					$rs_detail = $actionplanClass->getReschedulesByScheduleId($rs['action_plan_schedule_id']);
					$rs['reschedule_dates'] = "";
					foreach($rs_detail as $d)
					{
						$ori_date = explode(" ", $d['original_date']);
						$rs_date = explode(" ", $d['reschedule_date']);
						$rs['reschedule_dates'] = $rs['reschedule_dates']. date("j M Y", strtotime($ori_date[0])) ." to ".date("j M Y", strtotime($rs_date[0]))."<br/>";
					}
				}
			}
			else
			{
				$ori_date = explode(" ", $rs['original_date']);
				$rs_date = explode(" ", $rs['reschedule_date']);
				if($params['filter'] == "u") $rs['activity_list'] = "<ul><li>".$rs['target_name']." - ".$rs['activity_name']." - ".date("j M Y", strtotime($ori_date[0])) ." to ".date("j M Y", strtotime($rs_date[0]))."</li></ul>";
				else $rs['reschedule_dates'] = date("j M Y", strtotime($ori_date[0])) ." to ".date("j M Y", strtotime($rs_date[0]));
			}
		}
		$this->view->rescheduleStatistic = $rescheduleStatistic;
		
		$this->view->category_id = $params['c'];

		$this->view->filter = $params['filter'];		

		$this->view->selectedYear = $params['year'];

		$this->renderTemplate('action_plan_reschedule_statistic.tpl');
	}
	
	/*** ACTION PLAN ATTACHMENT ***/
	
	public function getattachmentbyscheduleidAction() {
		$params = $this->_getAllParams();
		Zend_Loader::LoadClass('actionplanClass', $this->modelDir);
		$actionplanClass = new actionplanClass();

		Zend_Loader::LoadClass('categoryClass', $this->modelDir);
		$categoryClass = new categoryClass();	
		$category = $categoryClass->getCategoryById($params['category_id']);
		
		if(!empty($params['id'])) 
		{
			//$cqc = $actionplanClass->getCQCByScheduleId($params['id']);

			/*if(!empty($cqc))
			{
				$k = 0;
				$html="";
				foreach($cqc as $c)
				{
					if($k == 0) $startdate = 0;
					else $startdate = $cqc[$k-1]['submit_date'];
					$attachments = $actionplanClass->getAttachmentByDateCQC($params['id'], $startdate, $c['submit_date']);
					if(!empty($attachments))
					{
						$html.='<ul style="padding-left: 0px; list-style:none;">';
						
						foreach($attachments as $attachment)
						{
							$html.='<li><i class="fa fa-paperclip"></i> <a href="'.$this->baseUrl.'/actionplan/'.strtolower(str_replace(" & ", "", $category['category_name'])).'/'.$attachment['filename'].'" target="_blank">';
							if(!empty($attachment['description'])) $html.= $attachment['description'];
							else $html.=$attachment['filename'];
							$html.= '</a>';
							if($this->showReminderReview == 1)	$html.= ' <a class="action-btn delete-ap-att" data-id="'.$attachment['attachment_id'].'" data-filename="'.$attachment['filename'].'"><i class="fa fa-trash" ></i></a>';
							$html.= '</li>';
						}
						$html.='</ul>
						<div style="font-weight:bold; border: 2px solid red; border-radius:5px; padding:5px 10px; margin-bottom:10px;">'.$c['remarks'].'<br/><i class="fa fa-paperclip"></i> <a href="'.$this->baseUrl.'/actionplan/cqc/'.strtolower(str_replace(" & ", "", $category['category_name'])).'/'.$c['attachment'].'" target="_blank">'.$c['attachment'].'</a></div>';
					}
					$k++;
				}
				
				$attachmentsAfterCQC = $actionplanClass->getAttachmentByDateCQC($params['id'], $cqc[$k-1]['submit_date'], 0);
				if(!empty($attachmentsAfterCQC))
				{
					$html.='<ul style="padding-left: 0px; list-style:none;">';
					
					foreach($attachmentsAfterCQC as $attachment)
					{
						$html.='<li><i class="fa fa-paperclip"></i> <a href="'.$this->baseUrl.'/actionplan/'.strtolower(str_replace(" & ", "", $category['category_name'])).'/'.$attachment['filename'].'" target="_blank">';
						if(!empty($attachment['description'])) $html.= $attachment['description'];
						else $html.=$attachment['filename'];
						$html.= '</a>';
						if($this->showReminderReview == 1)	$html.= ' <a class="action-btn delete-ap-att" data-id="'.$attachment['attachment_id'].'" data-filename="'.$attachment['filename'].'"><i class="fa fa-trash" ></i></a>';
						$html.= '</li>';
					}
					$html.='</ul>';
				}
			}
			else
			{*/
				$attachments = $actionplanClass->getActionPlanAttachmentByScheduleId($params['id']);
				
				if(!empty($attachments))
				{
					$html='<ul style="padding-left: 0px; list-style:none;">';
					
					foreach($attachments as $attachment)
					{
						$html.='<li><i class="fa fa-paperclip"></i> <a href="'.$this->baseUrl.'/actionplan/'.strtolower(str_replace(" & ", "", $category['category_name'])).'/'.$attachment['filename'].'" target="_blank">';
						if(!empty($attachment['description'])) $html.= $attachment['description'];
						else $html.=$attachment['filename'];
						$html.= '</a>';
						if($this->showReminderReview == 1)	$html.= ' <a class="action-btn delete-ap-att" data-id="'.$attachment['attachment_id'].'" data-filename="'.$attachment['filename'].'"><i class="fa fa-trash" ></i></a>';
						$html.= '</li>';
					}
					$html.="</ul>";
				}
			//}
			echo $html;
		}
	}

	public function deleteattachmentbyidAction() {
		$params = $this->_getAllParams();
		Zend_Loader::LoadClass('actionplanClass', $this->modelDir);
		$actionplanClass = new actionplanClass();

		$curAttachment = $actionplanClass->getActionPlanAttachmentById($params['id']);
		
		$actionplanClass->deleteAttachmentById($params['id']);

		Zend_Loader::LoadClass('categoryClass', $this->modelDir);
		$categoryClass = new categoryClass();
	
		$category = $categoryClass->getCategoryById($params['category_id']);
		
		$baseDir = dirname(dirname(dirname(dirname(__FILE__))));
		$baseDir = str_replace("\\", "/", $baseDir);
		$baseDir = rtrim($baseDir, "/");
		$baseDir = $baseDir."/sites/default/html/actionplan/";
		$datafolder = $baseDir.strtolower(str_replace(" & ", "", $category['category_name']));
		unlink($datafolder."/".$params['filename']);

		$listAttachments = $actionplanClass->getActionPlanAttachmentByScheduleId($curAttachment['action_plan_schedule_id']);
		if(count($listAttachments) == 0)  
		{
			$actionplanClass->updateschedulestatus($curAttachment['action_plan_schedule_id'], 0);
			$sch = $actionplanClass->getActionPlanScheduleById($curAttachment['action_plan_schedule_id'], $this->site_id);
			$year = date("Y", strtotime($sch['schedule_date']));

			//$this->cache->remove("ap_schedule_".$this->site_id."_".$params['category_id']."_".$year);
		}

		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "Delete Action Plan Schedule Attachment";
		$logData['data'] = json_encode($params);
		$logsTable->insertLogs($logData);	
	}
	
	
	/*** UPCOMING SCHEDULE WEEKLY ***/
	
	function getWeekDates($year, $week, $start=true)
	{
		if($week < 10) $week = "0".$week;
		$from = date("Y-m-d", strtotime("{$year}-W{$week}-1")); //Returns the date of monday in week
		$to = date("Y-m-d", strtotime("{$year}-W{$week}-7"));   //Returns the date of sunday in week
	 
		if($start) {
			return $from;
		} else {
			return $to;
		}
		//return "Week {$week} in {$year} is from {$from} to {$to}.";
	}
	
	public function upcomingAction() {
		$params = $this->_getAllParams();
		
		$params['user_id'] = $this->ident['user_id'];
		Zend_Loader::LoadClass('actionplanClass', $this->modelDir);
		$actionplanClass = new actionplanClass();
		
		/*$currentWeek = date('W', strtotime(date("Y-m-d")));
		if($currentWeek == "01" && date("m-d") == "12-31") {
			$nextWeek =  1;
			$y = date("Y",mktime(0, 0, 0, date("m"),   date("d"),   date("Y")+1));
		}
		else 
		{
			$nextWeek = $currentWeek+1;
			$startdate = $this->getWeekDates(date("Y"), $nextWeek, true);
			$enddate = $this->getWeekDates(date("Y"), $nextWeek, false);
		}*/
		

		$currentWeek = date('W', strtotime(date("Y-m-d")));
		$startdate = $this->getWeekDates(date("Y"), ($currentWeek+1), true);
		$enddate = $this->getWeekDates(date("Y"), ($currentWeek+1), false);
		$this->view->startdate = date("j F Y", strtotime($startdate));
		$this->view->enddate = date("j F Y", strtotime($enddate));
		$this->view->month = date("F", strtotime($startdate));
		$this->view->week = $this->weekOfMonth($startdate);
		
		$this->view->sitename = $this->ident['site_fullname'];
		
		$schedule = $actionplanClass->getActionPlanUpcomingSchedule($params['c'], $startdate, $enddate);
		
		if(!empty($schedule))
		{
			foreach($schedule as &$sch)
			{
				$date = explode(" ", $sch['schedule_date']);
				$sch['formatted_schedule_date'] = date("j F Y", strtotime($date[0]));
			}
		}
		$this->view->schedule = $schedule;
		
		$this->view->category_id = $params['c'];

		$this->renderTemplate('action_plan_upcoming.tpl');
	}
	
	public function updateupcomingAction() {
		$params = $this->_getAllParams();
		
		Zend_Loader::LoadClass('actionplanClass', $this->modelDir);
		$actionplanClass = new actionplanClass();
	
		if(!empty($params['scheduleid'])) {
			$i=0;
			foreach($params['scheduleid'] as $schedule_id)
			{
				$actionplanClass->updateDocumentRemark($schedule_id,$params['document'][$i], $params['remark'][$i]);
				$i++;
			}			
		}
		
		$this->_response->setRedirect($this->baseUrl.'/default/actionplan/upcoming/c/'.$params['category_id']);
		$this->_response->sendResponse();
		exit();
	}
	
	public function sendweeklyreminderAction() {
		$params = $this->_getAllParams();
		
		Zend_Loader::LoadClass('actionplanClass', $this->modelDir);
		$actionplanClass = new actionplanClass();
		
		$currentWeek = date('W', strtotime(date("Y-m-d")));
		
		$startdate = $this->getWeekDates(date("Y"), ($currentWeek+1), true);
		$enddate = $this->getWeekDates(date("Y"), ($currentWeek+1), false);
		$start_date = date("j F Y", strtotime($startdate));
		$end_date = date("j F Y", strtotime($enddate));
		$month = date("F", strtotime($startdate));
		$week = $this->weekOfMonth($startdate);
		
		$sitename = $this->ident['site_fullname'];
		
		$schedule = $actionplanClass->getActionPlanUpcomingSchedule($params['c'], $startdate, $enddate);
		
		$categoryClass = $this->loadModel('category');
		$category = $categoryClass->getCategoryById($params['c']);	
		
		if(!empty($schedule))
		{
			$html = 'Dear All,<br/><br/> 
Please find the table below for your kindly reminder;<br/>
<h2>Reminder for your '.$category['category_name'].' Action Plan '.date("Y", strtotime("+1 week")).'</h2>

			<table>
			<tr>
				<td>Month</td>
				<td width="20"></td>
				<td>'.$month.'</td>
			</tr>
			<tr>
				<td>Week (Date)</td>
				<td></td>
				<td>'.$week.' ('.$start_date.' - '.$end_date.')</td>
			</tr>
			<tr>
				<td>Unit</td>
				<td></td>
				<td>'.$sitename.'</td>
			</tr>
			</table>
			
			<table class="table table-striped">
			  <thead>
				<tr>
				  <th style="background-color:#1f497d; color: #fff; border:1px solid #fff; padding:5px;">No</th>
				  <th style="background-color:#1f497d; color: #fff; border:1px solid #fff; padding:5px;">Date</th>
				  <th style="background-color:#1f497d; color: #fff; border:1px solid #fff; padding:5px;">Action Plan '.date("Y", strtotime("+1 week")).'</th>	
				  <th style="background-color:#1f497d; color: #fff; border:1px solid #fff; padding:5px;">Document as approves</th>
				  <th style="background-color:#1f497d; color: #fff; border:1px solid #fff; padding:5px;">Remark</th>
				</tr>
			  </thead>
			  <tbody>';
			$i=0;
			foreach($schedule as &$sch)
			{
				$date = explode(" ", $sch['schedule_date']);
				$formatted_schedule_date = date("j F Y", strtotime($date[0]));
				
				$html .= '<tr>
				  <td style="background-color:#dbe5f1; color: #000; border:1px solid #fff; padding:5px;">'.($i+1).'</th>
				  <td style="background-color:#dbe5f1; color: #000; border:1px solid #fff; padding:5px;">'.$formatted_schedule_date.'</td>
				  <td style="background-color:#dbe5f1; color: #000; border:1px solid #fff; padding:5px;">'.$sch['activity_name'].'</td>
				  <td style="background-color:#dbe5f1; color: #000; border:1px solid #fff; padding:5px;">'.$sch['document_as_approves'].'</td>
				  <td style="background-color:#dbe5f1; color: #000; border:1px solid #fff; padding:5px;">'.$sch['remark'].'</td>
				</tr>';
				$i++;
			}
			$html .= '</tbody></table>';
			
			require_once 'Zend/Mail.php';
			$mail = new Zend_Mail();
			$mail->setBodyHtml($html);
			$mail->setFrom("srt@pakuwon.com");
			
			$emails = $actionplanClass->getActionPlanEmail($params['c'], $this->site_id);
			Zend_Loader::LoadClass('siteClass', $this->modelDir);
			$siteClass = new siteClass();
			
			$site = $siteClass->getSiteById($this->site_id);
			$success = 0;
			if(!empty($emails))		
			{
				foreach($emails as $email)
				{
					$mail->addTo($email['email']);
				}

				//$mail->addTo("just4u209@gmail.com");
			
				$mail->setSubject('Reminder of '.$category['category_name'].' Planning Action '.$site['initial'].': '.$start_date.' - '.$end_date);
				
				try {
					$mail->send();
					echo "success";
					$success = 1;
				}
				catch (Exception  $ex) {
					echo "failed=".$ex;
				}
				unset($mail);
					
				echo $html;
			}
		}
		
		if($success)
		{
			$this->_response->setRedirect($this->baseUrl.'/default/actionplan/upcoming/c/'.$params['c']);
			$this->_response->sendResponse();
			exit();
		}
		else{
			echo "failed";
			exit();
		}
	}

	public function sendweeklyreminderautoAction() {
		Zend_Loader::LoadClass('siteClass', $this->modelDir);
		$siteClass = new siteClass();

		Zend_Loader::LoadClass('actionplanClass', $this->modelDir);
		$actionplanClass = new actionplanClass();
		
		$currentWeek = date('W', strtotime(date("Y-m-d")));
		
		$startdate = $this->getWeekDates(date("Y"), ($currentWeek+1), true);
		$enddate = $this->getWeekDates(date("Y"), ($currentWeek+1), false);
		$start_date = date("j F Y", strtotime($startdate));
		$end_date = date("j F Y", strtotime($enddate));
		$month = date("F", strtotime($startdate));
		$week = $this->weekOfMonth($startdate);
		
		$sites = $siteClass->getSites();

		foreach($sites as $site)
		{
			$categoryClass = $this->loadModel('category');
			$categories = $categoryClass->getCategories();
			foreach($categories as $category)
			{
				if(in_array($category['category_id'], array(1,2,3,5)))
				{
					$schedule = $actionplanClass->getActionPlanUpcomingSchedule2($site['site_id'], $category['category_id'], $startdate, $enddate);
					
					$botToken = '476346623:AAFlB9X5-bShAkdTK9ziNDrfZ0TCePXl2cY';
					$website="https://api.telegram.org/bot".$botToken;
					$chatId=$category['site_'.$site['site_id']];  //Receiver Chat Id 

					if(!empty($schedule))
					{
						$html = 'Dear All,<br/><br/> 
			Please find the table below for your kindly reminder;<br/>
			<h2>Reminder for your '.$category['category_name'].' Action Plan '.date("Y", strtotime("+1 week")).'</h2>

						<table>
						<tr>
							<td>Month</td>
							<td width="20"></td>
							<td>'.$month.'</td>
						</tr>
						<tr>
							<td>Week (Date)</td>
							<td></td>
							<td>'.$week.' ('.$start_date.' - '.$end_date.')</td>
						</tr>
						<tr>
							<td>Unit</td>
							<td></td>
							<td>'.$site['site_fullname'].'</td>
						</tr>
						</table>
						
						<table class="table table-striped">
						<thead>
							<tr>
							<th style="background-color:#1f497d; color: #fff; border:1px solid #fff; padding:5px;">No</th>
							<th style="background-color:#1f497d; color: #fff; border:1px solid #fff; padding:5px;">Date</th>
							<th style="background-color:#1f497d; color: #fff; border:1px solid #fff; padding:5px;">Action Plan '.date("Y", strtotime("+1 week")).'</th>	
							<th style="background-color:#1f497d; color: #fff; border:1px solid #fff; padding:5px;">Document as approves</th>
							<th style="background-color:#1f497d; color: #fff; border:1px solid #fff; padding:5px;">Remark</th>
							</tr>
						</thead>
						<tbody>';
						$i=0;
						$txt = '[ACTION PLAN WEEKLY REMINDER]
						
';
						foreach($schedule as &$sch)
						{
							$date = explode(" ", $sch['schedule_date']);
							$formatted_schedule_date = date("j F Y", strtotime($date[0]));
							
							$html .= '<tr>
							<td style="background-color:#dbe5f1; color: #000; border:1px solid #fff; padding:5px;">'.($i+1).'</th>
							<td style="background-color:#dbe5f1; color: #000; border:1px solid #fff; padding:5px;">'.$formatted_schedule_date.'</td>
							<td style="background-color:#dbe5f1; color: #000; border:1px solid #fff; padding:5px;">'.$sch['activity_name'].'</td>
							<td style="background-color:#dbe5f1; color: #000; border:1px solid #fff; padding:5px;">'.$sch['doc_act'].'</td>
							<td style="background-color:#dbe5f1; color: #000; border:1px solid #fff; padding:5px;">'.$sch['remarks_act'].'</td>
							</tr>';

							$txt .= $sch['activity_name'].' - '.$formatted_schedule_date.'

';
							$i++;
						}
						$html .= '</tbody></table>';

						$data=array(
							'chat_id'=>$chatId,
							'text'=>$txt
						);
						$ch = curl_init($website . '/sendMessage');
						curl_setopt($ch, CURLOPT_HEADER, false);
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
						curl_setopt($ch, CURLOPT_POST, 1);
						curl_setopt($ch, CURLOPT_POSTFIELDS, ($data));
						curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
						$result = curl_exec($ch);
						curl_close($ch);
						echo $txt."<br/><br/>";
						
						require_once 'Zend/Mail.php';
						$mail = new Zend_Mail();
						$mail->setBodyHtml($html);
						$mail->setFrom("srt@pakuwon.com");
						
						$emails = $actionplanClass->getActionPlanReminderEmail($category['category_id'], $site['site_id']);
						$success = 0;
						if(!empty($emails))		
						{
							foreach($emails as $email)
							{
								if($email['cc'] == '1')	$mail->addCC($email['email']);
								else $mail->addTo($email['email']);
							}
						
							$mail->setSubject('Reminder of '.$category['category_name'].' Planning Action '.$site['initial'].': '.$start_date.' - '.$end_date);
							
							try {
								$mail->send();
								echo "success";
								$success = 1;
								print_r($emails); 
								echo $html;
							}
							catch (Exception  $ex) {
								echo "failed=".$ex;
							}
							unset($mail);								
						}
					}
				}
			}
		}
		
		
		/*
		
		$sitename = $this->ident['site_fullname'];
	
		$categoryClass = $this->loadModel('category');
		$category = $categoryClass->getCategoryById($params['c']);	
		
		if($success)
		{
			$this->_response->setRedirect($this->baseUrl.'/default/actionplan/upcoming/c/'.$params['c']);
			$this->_response->sendResponse();
			exit();
		}
		else{
			echo "failed";
			exit();
		}*/
	}
	
	/*** REVIEW ***/
	
	public function reviewAction() {
		$params = $this->_getAllParams();

		Zend_Loader::LoadClass('siteClass', $this->modelDir);
		$siteClass = new siteClass();

		Zend_Loader::LoadClass('actionplanClass', $this->modelDir);
		$actionplanClass = new actionplanClass();
		
		$currentWeek = date('W', strtotime(date("Y-m-d")));

		if($currentWeek == '01') {
			$lastWeek =  date('W', strtotime(date("Y-m-d", mktime(0, 0, 0, date("m")  , date("d")-7, date("Y")))));
			$y = date("Y") - 1;
		}
		else {
			$lastWeek = $currentWeek-1;
			$y = date("Y");
		}
		$startdate = $this->getWeekDates($y, $lastWeek, true);
		$enddate = $this->getWeekDates($y, $lastWeek, false);
		$start_date = date("j F Y", strtotime($startdate));
		$end_date = date("j F Y", strtotime($enddate));
		$month = date("F", strtotime($startdate));
		$week = $this->weekOfMonth($startdate);
		
		$sitename = $this->ident['site_fullname'];

		$categoryClass = $this->loadModel('category');
		$categories = $categoryClass->getCategories();
		foreach($categories as $category)
		{
			if(in_array($category['category_id'], array(1,2,3,5)))
			{
				$curYearReviewSchedule = $actionplanClass->getActionPlanCurrentYearReviewSchedule2($this->site_id, $params['c'], $startdate);
			
				if(!empty($curYearReviewSchedule))
				{
					foreach($curYearReviewSchedule as &$cyrs)
					{
						$date = explode(" ", $cyrs['schedule_date']);
						$cyrs['formatted_schedule_date'] = date("j F Y", strtotime($date[0]));
						if($cyrs['status'] == 2)
						{
							$reschedule = $actionplanClass->getRescheduleByScheduleId($cyrs['schedule_id']);
							if(!empty($reschedule) && empty($cyrs['remark']))
							{
								$reschedule_date = explode(" ", $reschedule['reschedule_date']);
								$cyrs['remark'] = "reschedule to ".date("j M", strtotime($reschedule_date[0]));
							}
						}
					}
				}
				
				$lastWeekReviewSchedule = $actionplanClass->getActionPlanLastWeekReviewSchedule2($this->site_id, $params['c'], $startdate, $enddate);

				Zend_Loader::LoadClass('categoryClass', $this->modelDir);
				$categoryClass = new categoryClass();

				$this->view->category = $categoryClass->getCategoryById($category['category_id']);					
				if(!empty($lastWeekReviewSchedule))
				{
					foreach($lastWeekReviewSchedule as &$lwrs)
					{
						$date = explode(" ", $lwrs['schedule_date']);
						$lwrs['formatted_schedule_date'] = date("j F Y", strtotime($date[0]));
						if($lwrs['status'] == 1)
						{
							$lwrs['documents'] = $actionplanClass->getActionPlanAttachmentByScheduleId($lwrs['schedule_id']);
						}
						
						if(!empty($lwrs['reschedule_date']))
						{
							if($date[0] > $enddate)
							{
								$lwrs['remarks_act'] = "reschedule to ".$lwrs['formatted_schedule_date'];
							}
						}
					}
				}
				
				$categoryClass = $this->loadModel('category');
				$category = $categoryClass->getCategoryById($params['c']);	
				
				
				$html = 'Dear All,<br/><br/> 
				Please kindly find enclosed below for your review;<br/>
		<h2>Review '.$category['category_name'].' Action Plan Activity '.date("Y", strtotime("-1 week")).'</h2>

				<table>
				<tr>
					<td>Month</td>
					<td width="20"></td>
					<td>'.$month.'</td>
				</tr>
				<tr>
					<td>Week (Date)</td>
					<td></td>
					<td>'.$week.' ('.$start_date.' - '.$end_date.')</td>
				</tr>
				<tr>
					<td>Unit</td>
					<td></td>
					<td>'.$sitename.'</td>
				</tr>
				</table>
				<br/>';
				if(!empty($curYearReviewSchedule))
				{
					$html .= '<h3>Outstanding Action Plan '.date("Y").' Cumulated</h3>
					<table class="table table-striped">
						<thead>
						<tr>
							<th style="background-color:#1f497d; color: #fff; border:1px solid #fff; padding:5px;">No</th>
							<th style="background-color:#1f497d; color: #fff; border:1px solid #fff; padding:5px;">Date</th>
							<th style="background-color:#1f497d; color: #fff; border:1px solid #fff; padding:5px;">Planning Action '.date("Y", strtotime("-1 week")).'</th>	
							<th style="background-color:#1f497d; color: #fff; border:1px solid #fff; padding:5px;">Document as approves</th>
							<th style="background-color:#1f497d; color: #fff; border:1px solid #fff; padding:5px;">Status</th>
						</tr>
						</thead>
						<tbody>';
					$i=0;
					foreach($curYearReviewSchedule as &$sch)
					{
						$date = explode(" ", $sch['schedule_date']);
						$formatted_schedule_date = date("j F Y", strtotime($date[0]));
						
						$html .= '<tr>
							<td style="background-color:#dbe5f1; color: #000; border:1px solid #fff; padding:5px;">'.($i+1).'</th>
							<td style="background-color:#dbe5f1; color: #000; border:1px solid #fff; padding:5px;">'.$formatted_schedule_date.'</td>
							<td style="background-color:#dbe5f1; color: #000; border:1px solid #fff; padding:5px;">'.$sch['activity_name'].'</td>
							<td style="background-color:#dbe5f1; color: #000; border:1px solid #fff; padding:5px;">'.$sch['doc_act'].'</td>
							<td style="background-color:#dbe5f1; color: #000; border:1px solid #fff; padding:5px;">'.$sch['remarks_act'].'</td>
						</tr>';
						$i++;
					}
					$html .= '</tbody></table><br/><br/>';
				}

				if(!empty($lastWeekReviewSchedule))
				{
					$html .= '<h3>Review Previous Week</h3>
					<table class="table table-striped">
						<thead>
						<tr>
							<th rowspan="2" style="background-color:#1f497d; color: #fff; border:1px solid #fff; padding:5px;">No</th>
							<th rowspan="2" style="background-color:#1f497d; color: #fff; border:1px solid #fff; padding:5px;">Date</th>
							<th rowspan="2" style="background-color:#1f497d; color: #fff; border:1px solid #fff; padding:5px;">Planning Action '.date("Y", strtotime("-1 week")).'</th>	
							<th rowspan="2" style="background-color:#1f497d; color: #fff; border:1px solid #fff; padding:5px;">Document as approves</th>
							<th colspan="2" style="background-color:#1f497d; color: #fff; border:1px solid #fff; padding:5px;">Status</th>
							<th rowspan="2" style="background-color:#1f497d; color: #fff; border:1px solid #fff; padding:5px;">Remark</th>
						</tr>
						<tr>
							<th style="background-color:#1f497d; color: #fff; border:1px solid #fff; padding:5px;">Achieved</th>
							<th style="background-color:#1f497d; color: #fff; border:1px solid #fff; padding:5px;">Miss</th>
						</tr>
						</thead>
						<tbody>';
					$j=0;
					foreach($lastWeekReviewSchedule as &$sch2)
					{
						if($prevActivityId != $sch2['action_plan_activity_id'])
						{
							$date_sch = explode(" ", $sch2['schedule_date']);
							$formatted_schedule_date = date("j F Y", strtotime($date_sch[0]));
							if($date_sch[0] > $enddate)
							{
								$date_ori = explode(" ", $sch2['original_date']);
								$formatted_schedule_date = date("j F Y", strtotime($date_ori[0]));
							}

							$prevActivityId = $sch2['action_plan_activity_id'];
							
							$html .= '<tr>
							<td style="background-color:#dbe5f1; color: #000; border:1px solid #fff; padding:5px;">'.($j+1).'</th>
							<td style="background-color:#dbe5f1; color: #000; border:1px solid #fff; padding:5px;">'.$formatted_schedule_date.'</td>
							<td style="background-color:#dbe5f1; color: #000; border:1px solid #fff; padding:5px;">'.$sch2['activity_name'].'</td>
							<td style="background-color:#dbe5f1; color: #000; border:1px solid #fff; padding:5px;">';
							if($sch2['status'] == 1) { 
								if(!empty($sch2['documents']))
								{
									$html .= '<ul style="padding-left: 15px;">';
									foreach($sch2['documents'] as $document) {
										$html .= '<li><a href="'.$this->baseUrl.'/actionplan/'.strtolower(str_replace(" & ","",$category['category_name'])).'/'.$document['filename'].'" target="_blank">'.$document['filename'].'</a></li>';
									}
									$html .= "</ul>";
								}
							} else {
								$html .= str_replace("<br>","&#13;",$sch2['doc_act']);
							}
							$html .= '</td>
							<td style="background-color:#dbe5f1; color: #000; border:1px solid #fff; padding:5px;" align="center">';
							if($sch2['status'] == 1) $html .= '&#10004;';			
							$html .= '</td><td style="background-color:#dbe5f1; color: #000; border:1px solid #fff; padding:5px;" align="center">';
							if($sch2['status'] != 1) $html .= '&#10004;';
							$html .= '</td><td style="background-color:#dbe5f1; color: #000; border:1px solid #fff; padding:5px;">'.$sch2['remarks_act'].'</td>
							</tr>';
							$j++;
						}
					}
					$html .= '</tbody></table>';
				}
			}
		}

		$this->view->html = $html;

		$this->renderTemplate('action_plan_review.tpl');
	}

	public function updatereviewAction() {
		$params = $this->_getAllParams();
		
		Zend_Loader::LoadClass('actionplanClass', $this->modelDir);
		$actionplanClass = new actionplanClass();
	
		if(!empty($params['scheduleid'])) {
			$i=0;
			foreach($params['scheduleid'] as $schedule_id)
			{
				$actionplanClass->updateDocumentRemark($schedule_id,$params['document'][$i], $params['remark'][$i]);
				$i++;
			}			
		}

		if(!empty($params['scheduleid2'])) {
			$j=0;
			foreach($params['scheduleid2'] as $schedule_id2)
			{
				$actionplanClass->updateDocumentRemark($schedule_id2,$params['document2'][$j], $params['remark2'][$j], 1, $params['status_achieved'.($j+1)]);
				$j++;
			}			
		}
		
		$this->_response->setRedirect($this->baseUrl.'/default/actionplan/review/c/'.$params['category_id']);
		$this->_response->sendResponse();
		exit();
	}
	
	public function sendweeklyreviewAction() {
		$params = $this->_getAllParams();
		
		Zend_Loader::LoadClass('actionplanClass', $this->modelDir);
		$actionplanClass = new actionplanClass();
		
		$currentWeek = date('W', strtotime(date("Y-m-d")));

		if($currentWeek == '01') {
			$lastWeek =  date('W', strtotime(date("Y-m-d", mktime(0, 0, 0, date("m")  , date("d")-7, date("Y")))));
			$y = date("Y") - 1;
		}
		else {
			$lastWeek = $currentWeek-1;
			$y = date("Y");
		}
		$startdate = $this->getWeekDates($y, $lastWeek, true);
		$enddate = $this->getWeekDates($y, $lastWeek, false);
		$start_date = date("j F Y", strtotime($startdate));
		$end_date = date("j F Y", strtotime($enddate));
		$month = date("F", strtotime($startdate));
		$week = $this->weekOfMonth($startdate);
		
		$sitename = $this->ident['site_fullname'];


		$curYearReviewSchedule = $actionplanClass->getActionPlanCurrentYearReviewSchedule($params['c'], $startdate);
		
		if(!empty($curYearReviewSchedule))
		{
			foreach($curYearReviewSchedule as &$cyrs)
			{
				$date = explode(" ", $cyrs['schedule_date']);
				$cyrs['formatted_schedule_date'] = date("j F Y", strtotime($date[0]));
				if($cyrs['status'] == 2)
				{
					$reschedule = $actionplanClass->getRescheduleByScheduleId($cyrs['schedule_id']);
					if(!empty($reschedule) && empty($cyrs['remark']))
					{
						$reschedule_date = explode(" ", $reschedule['reschedule_date']);
						$cyrs['remark'] = "reschedule to ".date("j M", strtotime($reschedule_date[0]));
					}
				}
			}
		}
		
		$lastWeekReviewSchedule = $actionplanClass->getActionPlanLastWeekReviewSchedule($params['c'], $startdate, $enddate);

		Zend_Loader::LoadClass('categoryClass', $this->modelDir);
		$categoryClass = new categoryClass();

		$this->view->category = $categoryClass->getCategoryById($params['c']);
		
		if(!empty($lastWeekReviewSchedule))
		{
			foreach($lastWeekReviewSchedule as &$lwrs)
			{
				$date = explode(" ", $lwrs['schedule_date']);
				$lwrs['formatted_schedule_date'] = date("j F Y", strtotime($date[0]));
				if($lwrs['status'] == 1)
				{
					$lwrs['documents'] = $actionplanClass->getActionPlanAttachmentByScheduleId($lwrs['schedule_id']);
				}
			}
		}
		
		$categoryClass = $this->loadModel('category');
		$category = $categoryClass->getCategoryById($params['c']);	
		
		
		$html = 'Dear All,<br/><br/> 
		Please kindly find enclosed below for your review;<br/>
<h2>Review '.$category['category_name'].' Action Plan Activity '.date("Y", strtotime("-1 week")).'</h2>

		<table>
		<tr>
			<td>Month</td>
			<td width="20"></td>
			<td>'.$month.'</td>
		</tr>
		<tr>
			<td>Week (Date)</td>
			<td></td>
			<td>'.$week.' ('.$start_date.' - '.$end_date.')</td>
		</tr>
		<tr>
			<td>Unit</td>
			<td></td>
			<td>'.$sitename.'</td>
		</tr>
		</table>
		<br/>';
		if(!empty($curYearReviewSchedule))
		{
			$html .= '<h3>Outstanding Action Plan '.date("Y").' Cumulated</h3>
			<table class="table table-striped">
				<thead>
				<tr>
					<th style="background-color:#1f497d; color: #fff; border:1px solid #fff; padding:5px;">No</th>
					<th style="background-color:#1f497d; color: #fff; border:1px solid #fff; padding:5px;">Date</th>
					<th style="background-color:#1f497d; color: #fff; border:1px solid #fff; padding:5px;">Planning Action '.date("Y", strtotime("-1 week")).'</th>	
					<th style="background-color:#1f497d; color: #fff; border:1px solid #fff; padding:5px;">Document as approves</th>
					<th style="background-color:#1f497d; color: #fff; border:1px solid #fff; padding:5px;">Status</th>
				</tr>
				</thead>
				<tbody>';
			$i=0;
			foreach($curYearReviewSchedule as &$sch)
			{
				$date = explode(" ", $sch['schedule_date']);
				$formatted_schedule_date = date("j F Y", strtotime($date[0]));
				
				$html .= '<tr>
					<td style="background-color:#dbe5f1; color: #000; border:1px solid #fff; padding:5px;">'.($i+1).'</th>
					<td style="background-color:#dbe5f1; color: #000; border:1px solid #fff; padding:5px;">'.$formatted_schedule_date.'</td>
					<td style="background-color:#dbe5f1; color: #000; border:1px solid #fff; padding:5px;">'.$sch['activity_name'].'</td>
					<td style="background-color:#dbe5f1; color: #000; border:1px solid #fff; padding:5px;">'.$sch['document_as_approves'].'</td>
					<td style="background-color:#dbe5f1; color: #000; border:1px solid #fff; padding:5px;">'.$sch['remark'].'</td>
				</tr>';
				$i++;
			}
			$html .= '</tbody></table><br/><br/>';
		}

		if(!empty($lastWeekReviewSchedule))
		{
			$html .= '<h3>Review Previous Week</h3>
			<table class="table table-striped">
				<thead>
				<tr>
					<th rowspan="2" style="background-color:#1f497d; color: #fff; border:1px solid #fff; padding:5px;">No</th>
					<th rowspan="2" style="background-color:#1f497d; color: #fff; border:1px solid #fff; padding:5px;">Date</th>
					<th rowspan="2" style="background-color:#1f497d; color: #fff; border:1px solid #fff; padding:5px;">Planning Action '.date("Y", strtotime("-1 week")).'</th>	
					<th rowspan="2" style="background-color:#1f497d; color: #fff; border:1px solid #fff; padding:5px;">Document as approves</th>
					<th colspan="2" style="background-color:#1f497d; color: #fff; border:1px solid #fff; padding:5px;">Status</th>
					<th rowspan="2" style="background-color:#1f497d; color: #fff; border:1px solid #fff; padding:5px;">Remark</th>
				</tr>
				<tr>
					<th style="background-color:#1f497d; color: #fff; border:1px solid #fff; padding:5px;">Achieved</th>
					<th style="background-color:#1f497d; color: #fff; border:1px solid #fff; padding:5px;">Miss</th>
				</tr>
				</thead>
				<tbody>';
			$j=0;
			foreach($lastWeekReviewSchedule as &$sch2)
			{
				$date = explode(" ", $sch2['schedule_date']);
				$formatted_schedule_date = date("j F Y", strtotime($date[0]));
				
				$html .= '<tr>
				<td style="background-color:#dbe5f1; color: #000; border:1px solid #fff; padding:5px;">'.($j+1).'</th>
				<td style="background-color:#dbe5f1; color: #000; border:1px solid #fff; padding:5px;">'.$formatted_schedule_date.'</td>
				<td style="background-color:#dbe5f1; color: #000; border:1px solid #fff; padding:5px;">'.$sch2['activity_name'].'</td>
				<td style="background-color:#dbe5f1; color: #000; border:1px solid #fff; padding:5px;">';
				if($sch2['status'] == 1) { 
					if(!empty($sch2['documents']))
					{
						$html .= '<ul style="padding-left: 15px;">';
						foreach($sch2['documents'] as $document) {
							$html .= '<li><a href="'.$this->baseUrl.'/actionplan/'.strtolower($category['category_name']).'/'.$document['filename'].'" target="_blank">'.$document['filename'].'</a></li>';
						}
						$html .= "</ul>";
					}
				} else {
					$html .= str_replace("<br>","&#13;",$sch2['document_as_approves']);
				}
				$html .= '</td>
				<td style="background-color:#dbe5f1; color: #000; border:1px solid #fff; padding:5px;" align="center">';
				if($sch2['status'] == 1) $html .= '&#10004;';			
				$html .= '</td><td style="background-color:#dbe5f1; color: #000; border:1px solid #fff; padding:5px;" align="center">';
				if($sch2['status'] != 1) $html .= '&#10004;';
				$html .= '</td><td style="background-color:#dbe5f1; color: #000; border:1px solid #fff; padding:5px;">'.$sch2['remark'].'</td>
				</tr>';
				$i++;
			}
			$html .= '</tbody></table>';
		}
		
		require_once 'Zend/Mail.php';
		$mail = new Zend_Mail();
		$mail->setBodyHtml($html);
		$mail->setFrom("srt@pakuwon.com");
		
		$emails = $actionplanClass->getActionPlanEmail($params['c'], $this->site_id);
		Zend_Loader::LoadClass('siteClass', $this->modelDir);
		$siteClass = new siteClass();
		
		$site = $siteClass->getSiteById($this->site_id);
		
		if(!empty($emails))		
		{
			foreach($emails as $email)
			{
				$mail->addTo($email['email']);
			}

			//$mail->addTo("just4u209@gmail.com");
		
			$mail->setSubject('Review of '.$category['category_name'].' Planning Action '.$site['initial'].': '.$start_date.' - '.$end_date);
			
			try {
				$mail->send();
				echo "success";
			}
			catch (Exception  $ex) {
				echo "failed=".$ex;
			}
			unset($mail);
				
			echo $html;
		}
		
		$this->_response->setRedirect($this->baseUrl.'/default/actionplan/review/c/'.$params['c']);
		$this->_response->sendResponse();
		exit();
	}

	public function deletestatusscheduleAction() {
		$params = $this->_getAllParams();
		Zend_Loader::LoadClass('actionplanClass', $this->modelDir);
		$actionplanClass = new actionplanClass();
		$schedule = $actionplanClass->getActionPlanScheduleById($params['action_plan_schedule_id'], $this->site_id);
		$actionplanClass->deleteScheduleById($params['action_plan_schedule_id']);

		$year = date("Y", strtotime($schedule['schedule_date']));
		//$this->cache->remove("ap_schedule_".$this->site_id."_".$params['category_id']."_".$year);

		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "Delete Action Plan Schedule";
		$logData['data'] = json_encode($params);
		$logsTable->insertLogs($logData);	
	}

	public function sendweeklyreviewautoAction() {		
		$params = $this->_getAllParams();

		Zend_Loader::LoadClass('siteClass', $this->modelDir);
		$siteClass = new siteClass();

		Zend_Loader::LoadClass('actionplanClass', $this->modelDir);
		$actionplanClass = new actionplanClass();
		
		$currentWeek = date('W', strtotime(date("Y-m-d")));

		if($currentWeek == '01') {
			$lastWeek =  date('W', strtotime(date("Y-m-d", mktime(0, 0, 0, date("m")  , date("d")-7, date("Y")))));
			$y = date("Y") - 1;
		}
		else {
			$lastWeek = $currentWeek-1;
			$y = date("Y");
		}
		$startdate = $this->getWeekDates($y, $lastWeek, true);
		$enddate = $this->getWeekDates($y, $lastWeek, false);
		$start_date = date("j F Y", strtotime($startdate));
		$end_date = date("j F Y", strtotime($enddate));
		$month = date("F", strtotime($startdate));
		$week = $this->weekOfMonth($startdate);
		
		$sites = $siteClass->getSites();

		foreach($sites as $site)
		{
			$categoryClass = $this->loadModel('category');
			$categories = $categoryClass->getCategories();
			foreach($categories as $category)
			{
				if(in_array($category['category_id'], array(1,2,3,5))/* && in_array($site['site_id'], array(4,5,6,7,8))*/)
				{
					$curYearReviewSchedule = $actionplanClass->getActionPlanCurrentYearReviewSchedule2($site['site_id'], $category['category_id'], $startdate);
				
					if(!empty($curYearReviewSchedule))
					{
						foreach($curYearReviewSchedule as &$cyrs)
						{
							$date = explode(" ", $cyrs['schedule_date']);
							$cyrs['formatted_schedule_date'] = date("j F Y", strtotime($date[0]));
							if($cyrs['status'] == 2)
							{
								$reschedule = $actionplanClass->getRescheduleByScheduleId($cyrs['schedule_id']);
								if(!empty($reschedule) && empty($cyrs['remark']))
								{
									$reschedule_date = explode(" ", $reschedule['reschedule_date']);
									$cyrs['remark'] = "reschedule to ".date("j M", strtotime($reschedule_date[0]));
								}
							}
						}
					}
					
					$lastWeekReviewSchedule = $actionplanClass->getActionPlanLastWeekReviewSchedule2($site['site_id'], $category['category_id'], $startdate, $enddate);

					Zend_Loader::LoadClass('categoryClass', $this->modelDir);
					$categoryClass = new categoryClass();

					$this->view->category = $categoryClass->getCategoryById($category['category_id']);					
					if(!empty($lastWeekReviewSchedule))
					{
						foreach($lastWeekReviewSchedule as &$lwrs)
						{
							$date = explode(" ", $lwrs['schedule_date']);
							$lwrs['formatted_schedule_date'] = date("j F Y", strtotime($date[0]));
							if($lwrs['status'] == 1)
							{
								$lwrs['documents'] = $actionplanClass->getActionPlanAttachmentByScheduleId($lwrs['schedule_id']);
							}
							
							if(!empty($lwrs['reschedule_date']))
							{
								if($date[0] > $enddate)
								{
									$lwrs['remarks_act'] = "reschedule to ".$lwrs['formatted_schedule_date'];
								}
							}
						}
					}
					
					$categoryClass = $this->loadModel('category');
					$category = $categoryClass->getCategoryById($category['category_id']);	
					
					
					$html = 'Dear All,<br/><br/> 
					Please kindly find enclosed below for your review;<br/>
			<h2>Review '.$category['category_name'].' Action Plan Activity '.date("Y", strtotime("-1 week")).'</h2>

					<table>
					<tr>
						<td>Month</td>
						<td width="20"></td>
						<td>'.$month.'</td>
					</tr>
					<tr>
						<td>Week (Date)</td>
						<td></td>
						<td>'.$week.' ('.$start_date.' - '.$end_date.')</td>
					</tr>
					<tr>
						<td>Unit</td>
						<td></td>
						<td>'.$site['site_fullname'].'</td>
					</tr>
					</table>
					<br/>';
					if(!empty($curYearReviewSchedule))
					{
						$html .= '<h3>Outstanding Action Plan '.date("Y").' Cumulated</h3>
						<table class="table table-striped">
							<thead>
							<tr>
								<th style="background-color:#1f497d; color: #fff; border:1px solid #fff; padding:5px;">No</th>
								<th style="background-color:#1f497d; color: #fff; border:1px solid #fff; padding:5px;">Date</th>
								<th style="background-color:#1f497d; color: #fff; border:1px solid #fff; padding:5px;">Planning Action '.date("Y", strtotime("-1 week")).'</th>	
								<th style="background-color:#1f497d; color: #fff; border:1px solid #fff; padding:5px;">Document as approves</th>
								<th style="background-color:#1f497d; color: #fff; border:1px solid #fff; padding:5px;">Status</th>
							</tr>
							</thead>
							<tbody>';
						$i=0;
						foreach($curYearReviewSchedule as &$sch)
						{
							$date = explode(" ", $sch['schedule_date']);
							$formatted_schedule_date = date("j F Y", strtotime($date[0]));
							
							$html .= '<tr>
								<td style="background-color:#dbe5f1; color: #000; border:1px solid #fff; padding:5px;">'.($i+1).'</th>
								<td style="background-color:#dbe5f1; color: #000; border:1px solid #fff; padding:5px;">'.$formatted_schedule_date.'</td>
								<td style="background-color:#dbe5f1; color: #000; border:1px solid #fff; padding:5px;">'.$sch['activity_name'].'</td>
								<td style="background-color:#dbe5f1; color: #000; border:1px solid #fff; padding:5px;">'.$sch['doc_act'].'</td>
								<td style="background-color:#dbe5f1; color: #000; border:1px solid #fff; padding:5px;">'.$sch['remarks_act'].'</td>
							</tr>';
							$i++;
						}
						$html .= '</tbody></table><br/><br/>';
					}

					if(!empty($lastWeekReviewSchedule))
					{
						$html .= '<h3>Review Previous Week</h3>
						<table class="table table-striped">
							<thead>
							<tr>
								<th rowspan="2" style="background-color:#1f497d; color: #fff; border:1px solid #fff; padding:5px;">No</th>
								<th rowspan="2" style="background-color:#1f497d; color: #fff; border:1px solid #fff; padding:5px;">Date</th>
								<th rowspan="2" style="background-color:#1f497d; color: #fff; border:1px solid #fff; padding:5px;">Planning Action '.date("Y", strtotime("-1 week")).'</th>	
								<th rowspan="2" style="background-color:#1f497d; color: #fff; border:1px solid #fff; padding:5px;">Document as approves</th>
								<th colspan="2" style="background-color:#1f497d; color: #fff; border:1px solid #fff; padding:5px;">Status</th>
								<th rowspan="2" style="background-color:#1f497d; color: #fff; border:1px solid #fff; padding:5px;">Remark</th>
							</tr>
							<tr>
								<th style="background-color:#1f497d; color: #fff; border:1px solid #fff; padding:5px;">Achieved</th>
								<th style="background-color:#1f497d; color: #fff; border:1px solid #fff; padding:5px;">Miss</th>
							</tr>
							</thead>
							<tbody>';
						$j=0;
						foreach($lastWeekReviewSchedule as &$sch2)
						{
							if($prevActivityId != $sch2['action_plan_activity_id'])
							{
								$date_sch = explode(" ", $sch2['schedule_date']);
								$formatted_schedule_date = date("j F Y", strtotime($date_sch[0]));
								if($date_sch[0] > $enddate)
								{
									$date_ori = explode(" ", $sch2['original_date']);
									$formatted_schedule_date = date("j F Y", strtotime($date_ori[0]));
								}

								$prevActivityId = $sch2['action_plan_activity_id'];
								
								$html .= '<tr>
								<td style="background-color:#dbe5f1; color: #000; border:1px solid #fff; padding:5px;">'.($j+1).'</th>
								<td style="background-color:#dbe5f1; color: #000; border:1px solid #fff; padding:5px;">'.$formatted_schedule_date.'</td>
								<td style="background-color:#dbe5f1; color: #000; border:1px solid #fff; padding:5px;">'.$sch2['activity_name'].'</td>
								<td style="background-color:#dbe5f1; color: #000; border:1px solid #fff; padding:5px;">';
								if($sch2['status'] == 1) { 
									if(!empty($sch2['documents']))
									{
										$html .= '<ul style="padding-left: 15px;">';
										foreach($sch2['documents'] as $document) {
											$html .= '<li><a href="'.$this->baseUrl.'/actionplan/'.strtolower(str_replace(" & ","",$category['category_name'])).'/'.$document['filename'].'" target="_blank">'.$document['filename'].'</a></li>';
										}
										$html .= "</ul>";
									}
								} else {
									$html .= str_replace("<br>","&#13;",$sch2['doc_act']);
								}
								$html .= '</td>
								<td style="background-color:#dbe5f1; color: #000; border:1px solid #fff; padding:5px;" align="center">';
								if($sch2['status'] == 1) $html .= '&#10004;';			
								$html .= '</td><td style="background-color:#dbe5f1; color: #000; border:1px solid #fff; padding:5px;" align="center">';
								if($sch2['status'] != 1) $html .= '&#10004;';
								$html .= '</td><td style="background-color:#dbe5f1; color: #000; border:1px solid #fff; padding:5px;">'.$sch2['remarks_act'].'</td>
								</tr>';
								$j++;
							}
						}
						$html .= '</tbody></table>';
					}
					
					require_once 'Zend/Mail.php';
					$mail = new Zend_Mail();
					$mail->setBodyHtml($html);
					$mail->setFrom("srt@pakuwon.com");
					
					$emails = $actionplanClass->getActionPlanReviewEmail($category['category_id'], $site['site_id']);
					Zend_Loader::LoadClass('siteClass', $this->modelDir);
					$siteClass = new siteClass();
					

					print_r($emails);
					if(!empty($emails))		
					{
						foreach($emails as $email)
						{
							if($email['cc'] == '1')	$mail->addCC($email['email']);
							else $mail->addTo($email['email']);
						}
					
						$mail->setSubject('Review of '.$category['category_name'].' Planning Action '.$site['initial'].': '.$start_date.' - '.$end_date);
						
						try {
							$res = $mail->send();
							echo "success ";
						}
						catch (Exception  $ex) {
							echo "failed=".$ex;
						}
						unset($mail);
							
						echo $html;
					}

				}
			}
		}
	}

	public function viewcqcAction() {
		if($this->showCQC == 1)
		{
			$params = $this->_getAllParams();
			
			Zend_Loader::LoadClass('actionplanClass', $this->modelDir);
			$actionplanClass = new actionplanClass();

			if(empty($params['y'])) $year = date("Y");
			else $year = $params['y'];

			$this->view->selectedYear = $year;

			Zend_Loader::LoadClass('categoryClass', $this->modelDir);
			$categoryClass = new categoryClass();	
			$this->view->category = $categoryClass->getCategoryById($params['c']);

			if($this->allowApproveCQC == 0) $showDisapprove = 1;
			else $showDisapprove = 0;

			$ap = $actionplanClass->getActionPlanForCQC($params['c'], $year, $showDisapprove);
			foreach($ap as &$a)
			{	
				$date = explode(" ", $a['schedule_date']);
				$a['date'] = date("j F Y", strtotime($date[0]));
				$a['documents'] = $actionplanClass->getActionPlanAttachmentListByScheduleId($a['schedule_id']);
				$a['cqc'] = $actionplanClass->getCQCByScheduleId($a['schedule_id']);
			}
			$this->view->ap = $ap;
			//$this->view->hideScrollbar = 1;

			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "View CQC";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);	
			
			$this->renderTemplate('cqc_view.tpl'); 
		}
		else {
			$this->_response->setRedirect($this->baseUrl.'/default');
			$this->_response->sendResponse();
			exit();
		}
	}

	public function addcqcAction() {
		if($this->showCQC == 1)
		{
			$params = $this->_getAllParams();
			$params['user_id'] = $this->ident['user_id'];

			Zend_Loader::LoadClass('categoryClass', $this->modelDir);
			$categoryClass = new categoryClass();

			$category = $categoryClass->getCategoryById($params['category_id']);

			Zend_Loader::LoadClass('actionplanClass', $this->modelDir);
			$actionplanClass = new actionplanClass();
			$id = $actionplanClass->saveCQC($params);
				
			if(!empty($_FILES["cqc_attachment"]))
			{
				$ext = explode(".",$_FILES["cqc_attachment"]['name']);
				$filename = $id.".".$ext[count($ext)-1];
				$datafolder = $this->config->paths->html."/actionplan/cqc/".strtolower(str_replace(" & ", "", $category['category_name']))."/";
				if(move_uploaded_file($_FILES["cqc_attachment"]["tmp_name"], $datafolder.$filename))
				{								
					if(in_array(strtolower($ext[count($ext)-1]), array("jpg", "jpeg", "png","bmp")))
					{
						$magickPath = "/usr/bin/convert";
						/*** resize image if size greater than 500 Kb ***/
						if(filesize($datafolder.$filename) > 500000) exec($magickPath . ' ' . $datafolder.$filename . ' -resize 800x800\> ' . $datafolder.$filename);
					}								
					$actionplanClass->updateCQC($id, $filename);
				}	
			}
			$totalCQC = $actionplanClass->getTotalCQCByScheduleId($params['action_plan_schedule_id']);
			if($totalCQC == 1) $rating = 2;
			elseif($totalCQC == 2) $rating = 1;
			elseif($totalCQC > 2) $rating = 0;
			$actionplanClass->updateScheduleRating($params['action_plan_schedule_id'], $rating);

			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "Add CQC Attachment";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);	
			
			$cqc = $actionplanClass->getCQCByScheduleId($params['action_plan_schedule_id']);

			if(!empty($cqc))
			{
				$remark = "";
				foreach($cqc as $c) {
					$remark .= '<div class="cqc">'.$c['remarks'];
					if(!empty($c['attachment'])) {
						$remark .= '<br/><i class="fa fa-paperclip"></i> <a href="'.$this->baseUrl.'/actionplan/cqc/'.strtolower(str_replace(" & ", "", $category['category_name'])).'/'.$c['attachment'].'" target="_blank">'.$c['attachment'].'</a>';
					}
					$remark .= '</div>';						  
				}
			}
			
			$response['action_plan_schedule_id'] = $params['action_plan_schedule_id'];
			$response['remark'] = $remark;
			echo json_encode($response);

		}
		else {
			$this->_response->setRedirect($this->baseUrl.'/default');
			$this->_response->sendResponse();
			exit();
		}
	}

	public function approvecqcAction() {
		if($this->showCQC == 1)
		{
			$params = $this->_getAllParams();
			$params['user_id'] = $this->ident['user_id'];
			Zend_Loader::LoadClass('actionplanClass', $this->modelDir);
			$actionplanClass = new actionplanClass();
			$id = $actionplanClass->approveCQC($params);

			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "Approve CQC";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);	

			echo $params['id'];
		}
		else {
			$this->_response->setRedirect($this->baseUrl.'/default');
			$this->_response->sendResponse();
			exit();
		}
	}

	public function uploadattachmentaftercqcAction() {
		if($this->showCQC == 1)
		{
			$params = $this->_getAllParams();

			Zend_Loader::LoadClass('categoryClass', $this->modelDir);
			$categoryClass = new categoryClass();

			$category = $categoryClass->getCategoryById($params['category_id']);

			Zend_Loader::LoadClass('actionplanClass', $this->modelDir);
			$actionplanClass = new actionplanClass();
			if(!empty($_FILES["filename"]['name'])) {
				$params['user_id'] = $this->ident['user_id'];
				// CQC parameter ini utk menandakan kalau filenya di upload setelah cqc yg ke brp
				$params['cqc'] = $actionplanClass->getTotalCQCByScheduleId($params['action_plan_schedule_id']);
				$attachment_id = $actionplanClass->addScheduleAttachment($params);
				$ext = explode(".",$_FILES["filename"]['name']);
				$filename = $attachment_id.".".$ext[count($ext)-1];
				$datafolder = $this->config->paths->html."/actionplan/".strtolower(str_replace(" & ", "", $category['category_name']))."/";
				if(move_uploaded_file($_FILES["filename"]["tmp_name"], $datafolder.$filename))
				{
					$actionplanClass->updateScheduleAttachment($attachment_id,'filename', $filename);
					$actionplanClass->updateScheduleAttachment($attachment_id,'description', $params['description']);
					
					if(in_array(strtolower($ext[count($ext)-1]), array("jpg", "jpeg", "png","bmp")))
					{
						$magickPath = "/usr/bin/convert";
						/*** resize image if size greater than 500 Kb ***/
						if(filesize($datafolder.$filename) > 500000) exec($magickPath . ' ' . $datafolder.$filename . ' -resize 800x800\> ' . $datafolder.$filename);
					}								
				}	
			}
			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "Upload Action Plan Schedule Document after CQC";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);	
		}
	}

	public function getscheduleforthisactivityAction() {
		$params = $this->_getAllParams();
		Zend_Loader::LoadClass('actionplanClass', $this->modelDir);
		$actionplanClass = new actionplanClass();
		
		$activity = $actionplanClass->getActionPlanActivityById($params['activity_id']);

		$schedule = $actionplanClass->getActionPlanScheduleByActivityId($params['activity_id']);
		$i = 0;
		$scheduleList = array();
		foreach($schedule as $s)
		{
			$date = explode(" ",$s['schedule_date']);
			$scheduledate=date("j M Y", strtotime($date[0]));
			$scheduleList[$i] = $scheduledate;
			$i++;
		}
		$data['addtldate'] = $activity['total_schedule'] - count($schedule);
		$data['scheduleList'] = $scheduleList;
		echo json_encode($data);
	}
	
	public function getemptyactivityAction() {
		$params = $this->_getAllParams();
		Zend_Loader::LoadClass('actionplanClass', $this->modelDir);
		$actionplanClass = new actionplanClass();
		
		$schedule = $actionplanClass->getschedulelistofemptyactivity();
		
		foreach($schedule as $s)
		{
		    $actionplanClass->deleteScheduleById($s['schedule_id']);
		}
	}
	
	public function getemptymoduleAction() {
		$params = $this->_getAllParams();
		Zend_Loader::LoadClass('actionplanClass', $this->modelDir);
		$actionplanClass = new actionplanClass();
		
		$target = $actionplanClass->gettargetlistofemptymodule();
		
		foreach($target as $t)
		{
		    print_r($t);
		    $actionplanClass->deleteTargetById($t['action_plan_target_id']);
		}
	}
	
	public function getemptytargetAction() {
		$params = $this->_getAllParams();
		Zend_Loader::LoadClass('actionplanClass', $this->modelDir);
		$actionplanClass = new actionplanClass();
		
		$activity = $actionplanClass->getactivitylistofemptytarget();
		
		foreach($activity as $a)
		{
		    print_r($a);
		    $actionplanClass->deleteActivityById($a['action_plan_activity_id']);
		}
	}
	
	public function copyactionplantocuryearAction() {
	    Zend_Loader::LoadClass('actionplanClass', $this->modelDir);
		$actionplanClass = new actionplanClass();
	
		$target = $actionplanClass->getActionPlanTargetByYear(2020);
		foreach($target as $t) {
			$module = $actionplanClass->getActionPlanModuleByNameCatSiteYear($t['module_name'], $t['category_id'], $t['site_id'], 2021);
			$data['site_id'] = $t['site_id'];
			$data['action_plan_module_id'] = $module['action_plan_module_id'];
			$data['target_name'] = $t['target_name'];
			$data['sort_order']	= $t['sort_order'];
			$data['category_id'] = $t['category_id'];
			echo "<pre>";
			print_r($data);
			$actionplanClass->saveActionPlanTarget($data);
		}
	}
	
	public function copyactionplanactivitytocuryearAction() {
	    Zend_Loader::LoadClass('actionplanClass', $this->modelDir);
		$actionplanClass = new actionplanClass();
	
		$activity = $actionplanClass->getActionPlanActivityByYear(2020);
		foreach($activity as $a) {
			$target = $actionplanClass->getActionPlanTargetByNameCatSiteYear($a['target_name'], $a['category_id'], $a['site_id'], 2021);
			$data['site_id'] = $a['site_id'];
			$data['action_plan_target_id'] = $target['action_plan_target_id'];
			$data['activity_name'] = $a['activity_name'];
			$data['sort_order']	= $a['sort_order'];
			$data['category_id'] = $a['category_id'];
			$data['total_schedule'] = $a['total_schedule'];
			$data['document_as_approve'] = $a['document_as_approve'];
			$data['remarks'] = $a['remarks'];
			echo "<pre>";
			print_r($data);
			$actionplanClass->saveActionPlanActivity($data);
		}
	}
}

?>