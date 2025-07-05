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
		$this->view->start_year = $this->config->general->start_year;
		
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
		$module = $actionplanClass->getActionPlanModuleById($params['action_plan_module_id']);
		if($params['scheduledate'] == "daily") 
		{
			$firstDate = $module['show_year']."-01-01";
			$lastDate = $module['show_year']."-12-31";
			$k=0;
			while( $firstDate <= $lastDate ) {
				$params['schedule_date'][$k] = $firstDate;
				$curDate = explode("-", $firstDate);
				$firstDate  = date("Y-m-d", mktime(0, 0, 0, $curDate[1] , $curDate[2]+1, $curDate[0]));
				$k++;
			}
		}
		else if($params['scheduledate'] == "weekly") {
			$dayNo = date("N", strtotime($module['show_year']."-01-01"));
			$curDate = explode("-", $module['show_year']."-01-01");
			$lastDate = $module['show_year']."-12-31";
			if($dayNo > $params['day'])
			{
				$diffDay = $dayNo - $params['day'];
				$firstDate  = date("Y-m-d", mktime(0, 0, 0, $curDate[1] , $curDate[2]-$diffDay+7, $curDate[0]));
			}
			else {
				$diffDay = $params['day'] - $dayNo;
				$firstDate  = date("Y-m-d", mktime(0, 0, 0, $curDate[1] , $curDate[2]+$diffDay, $curDate[0]));
			}
			$k = 0;
			while( $firstDate <= $lastDate ) {
				$params['schedule_date'][$k] = $firstDate;
				$curDate = explode("-", $firstDate);
				$firstDate  = date("Y-m-d", mktime(0, 0, 0, $curDate[1] , $curDate[2]+7, $curDate[0]));
				$k++;
			}
		}
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
						$actionplanClass->saveActionPlanSchedule($params);
						$msg .= "Action plan for ".$activity['activity_name']." on ".$schedule." has been successfully added.<br/>";
						//$this->cache->remove("ap_schedule_".$this->site_id."_".$params['category_id']."_".$year);
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
			if(!empty($_FILES["attachment"]['name']))
			{
				foreach($_FILES["attachment"]['name'] as $attachment)
				{
					if(!empty($attachment)) {
						$attachment_id = $actionplanClass->addScheduleAttachment($params);
						if($attachment)
						{
							$ext = explode(".",$attachment);
							$filename = $attachment_id.".".$ext[count($ext)-1];
							$datafolder = $this->config->paths->html."actionplan/".strtolower(str_replace(" & ", "", $category['category_name']))."/".date("Y")."/";
							if(!is_dir($datafolder)) mkdir($datafolder, 0777, true);
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
				$datafolder = $this->config->paths->html."actionplan/cqc/".strtolower(str_replace(" & ", "", $category['category_name']))."/".date("Y")."/";
				if(!is_dir($datafolder)) mkdir($datafolder, 0777, true);
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
		if($total>0) $schedule['percentage'] = round((($totalDone/$total)*100),2);
		else $schedule['percentage'] = 0;
		
		if(!empty($params['id'])) 
		{
			$schedule = array_map('utf8_encode', $schedule);
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
		
		/*$modules = $actionplanClass->getActionPlanModulesByYear($params['c'], $year);
		$mod = array();
		if(!empty($modules))
		{
			foreach($modules as $m)
			{
				$mod[$m['action_plan_module_id']] = $m['module_name']; 
			}
		}
		$target = $actionplanClass->getActionPlanTargetByCatYear($params['c'], $year);
		$targt = array();
		if(!empty($target))
		{
			foreach($target as $t)
			{
				$targt[$t['action_plan_target_id']] = $t['target_name']; 
			}
		}*/
		
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
			$data[$i]['title'] = trim($schedule['activity_name'])." (".round($schedule['percentage'],2)."%) (".$schedule['totalDone']."/".$schedule['total'].")";
			$data[$i]['start'] = $schedule['date'];
			$data[$i]['id'] = $schedule['schedule_id'];
			if($schedule['status'] == 1) $data[$i]['color'] = '#9e824b'; 
			else if($schedule['status'] == 2) $data[$i]['color'] = 'darkorange';
			else if(($schedule['date'] < date("Y-m-d")) && $schedule['status'] < 1) $data[$i]['color'] = 'red';
			/*$schedule['module_name'] = $mod[$schedule['action_plan_module_id']];
			$schedule['target_name'] = $targt[$schedule['action_plan_target_id']];*/
			$data[$i] = array_map('utf8_encode', $data[$i]);
			$i++;
		}
		/*echo "<pre>"; print_r($data); exit();*/
		echo json_encode($data);
	}
	
	public function getallactionplanAction() {
		$params = $this->_getAllParams();
		
		Zend_Loader::LoadClass('actionplanClass', $this->modelDir);
		$actionplanClass = new actionplanClass();

		if(empty($params['y'])) $year = date("Y");
		else $year = $params['y'];

		$this->view->selectedYear = $year;

		$actionPlans = $actionplanClass->getAllSchedule($params['c'], $year);
		
		if(!empty($actionPlans))
		{
			$i = 0;
			$totalRow = array();
			foreach($actionPlans as &$ap)
			{
				$schedule = explode(" ",$ap['schedule_date']);
				$ap['schedule_date_formatted'] = date("j M Y",strtotime($schedule[0]));
				$totalRow['module'][$ap['action_plan_module_id']] = $totalRow['module'][$ap['action_plan_module_id']] + 1;
				$totalRow['target'][$ap['action_plan_target_id']] = $totalRow['target'][$ap['action_plan_target_id']] + 1;
				$totalRow['activity'][$ap['action_plan_activity_id']] = $totalRow['activity'][$ap['action_plan_activity_id']] + 1;
			}
		}
		$this->view->actionPlans = $actionPlans;	
		$this->view->totalRow = $totalRow;	

		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "View ".$this->ident['initial']." ".$category['category_name']." All Action Plan List ".$year;
		$logData['data'] = json_encode($params);
		$logsTable->insertLogs($logData);	
		
		echo $this->view->render('action_plan_all_schedules.tpl'); 
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
	
	public function getmodulebyyearAction() {
		$params = $this->_getAllParams();
		Zend_Loader::LoadClass('actionplanClass', $this->modelDir);
		$actionplanClass = new actionplanClass();
		
		if(!empty($params['y'])) 
		{
			echo json_encode($actionplanClass->getActionPlanModulesByYear($params['c'], $params['y']));
		}
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
	
	/*** ACTION PLAN RESCHEDULE ***/
	
	public function reschedulelistAction() {
		$params = $this->_getAllParams();
		
		Zend_Loader::LoadClass('actionplanClass', $this->modelDir);
		$actionplanClass = new actionplanClass();
		
		$this->view->rescheduleList = $actionplanClass->getActionPlanRescheduleList($params['cat']);
		
		Zend_Loader::LoadClass('categoryClass', $this->modelDir);
		$categoryClass = new categoryClass();	
		$category = $categoryClass->getCategoryById($params['cat']);
		$this->view->category_id = $params['cat'];
		$this->view->category_name = $category['category_name'];

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

		$this->_response->setRedirect($this->baseUrl.'/default/actionplan/reschedulelist/cat/'.$schedule['category_id']);
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

		$this->_response->setRedirect($this->baseUrl.'/default/actionplan/reschedulelist/cat/'.$schedule['category_id']);
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
						if(substr($attachment['uploaded_date'],0,10) < "2023-02-23") $url =  str_replace("ts.","tstest.",$this->baseUrl);
						else $url = $this->baseUrl;
					
						$html.='<li><i class="fa fa-paperclip"></i> <a href="'.$url.'/actionplan/'.strtolower(str_replace(" & ", "", $category['category_name'])).'/'.substr($attachment['uploaded_date'],0,4)."/".$attachment['filename'].'" target="_blank">';
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
		
		$datafolder = $this->config->paths->html."actionplan/".strtolower(str_replace(" & ", "", $category['category_name']))."/".substr($curAttachment['uploaded_date'],0,4)."/";		
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
										$html .= '<li><a href="'.$this->baseUrl.'/actionplan/'.strtolower(str_replace(" & ","",$category['category_name'])).'/'.substr($document['uploaded_date'],0,4)."/".$document['filename'].'" target="_blank">'.$document['filename'].'</a></li>';
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

	
	
	
	
	/*** SEND DAILY AND WEEKLY REMINDER  ***/
	
	public function sendreminderAction() {
		$params = $this->_getAllParams();
		
		Zend_Loader::LoadClass('actionplanClass', $this->modelDir);
		$actionplanClass = new actionplanClass();
		
		$schedule = $actionplanClass->getTomorrowSchedule($params['category_id'], $params['site_id']);
		
		if(!empty($schedule))
		{
			$categoryClass = $this->loadModel('category');
			$category = $categoryClass->getCategoryById($params['category_id']);	
						
			$botToken = '716005387:AAFhUdDGlXjK5YFMYqwx_lCpqpp8760S_TE';
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
			
			if($params['category_id'] == 6) $title = "Preventive Maintenance";
			else $title = "Action Plan";
			
			$txt = '['.strtoupper($title).' DAILY REMINDER]

';
			foreach($schedule as $sch)
			{
				$date = explode(" ",$sch['schedule_date']);
				$schedule_date = date("j F Y", strtotime($date[0]));
				$html.='<tr>
						<td style="border:1px solid #bbb; padding:5px;">'.$i.'</td>
						<td style="border:1px solid #bbb; padding:5px;">'.$sch['module_name'].'</td>
						<td style="border:1px solid #bbb; padding:5px;">'.trim($sch['target_name']).'</td>
						<td style="border:1px solid #bbb; padding:5px;">'.trim($sch['activity_name']).'</td>
						<td style="border:1px solid #bbb; padding:5px;">'.$schedule_date.'</td>
						<td style="border:1px solid #bbb; padding:5px;">'.$sch['document_as_approves'].'</td>
						<td style="border:1px solid #bbb; padding:5px;">'.$sch['remark'].'</td>
						</tr>';
						
				$txt .= trim($sch['target_name']).' - '.trim($sch['activity_name']).' : '.$schedule_date.'
				
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
			//echo "<pre>"; print_r($data);
			
			require_once 'Zend/Mail.php';
			$mail = new Zend_Mail();
			$mail->setBodyHtml($html);
			$mail->setFrom("noreply@isort.id");
			
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
			
				$mail->setSubject($site['site_name'] . ' - '.$category['category_name'].' - '.$title.' Reminder for Upcoming Schedule');
				
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
				  <th style="background-color:#9e824b; color: #fff; border:1px solid #fff; padding:5px;">No</th>
				  <th style="background-color:#9e824b; color: #fff; border:1px solid #fff; padding:5px;">Date</th>
				  <th style="background-color:#9e824b; color: #fff; border:1px solid #fff; padding:5px;">Action Plan '.date("Y", strtotime("+1 week")).'</th>	
				  <th style="background-color:#9e824b; color: #fff; border:1px solid #fff; padding:5px;">Document as approves</th>
				  <th style="background-color:#9e824b; color: #fff; border:1px solid #fff; padding:5px;">Remark</th>
				</tr>
			  </thead>
			  <tbody>';
			$i=0;
			foreach($schedule as &$sch)
			{
				$date = explode(" ", $sch['schedule_date']);
				$formatted_schedule_date = date("j F Y", strtotime($date[0]));
				
				$html .= '<tr>
				  <td style="background-color:#e5e5e5; color: #000; border:1px solid #fff; padding:5px;">'.($i+1).'</th>
				  <td style="background-color:#e5e5e5; color: #000; border:1px solid #fff; padding:5px;">'.$formatted_schedule_date.'</td>
				  <td style="background-color:#e5e5e5; color: #000; border:1px solid #fff; padding:5px;">'.$sch['activity_name'].'</td>
				  <td style="background-color:#e5e5e5; color: #000; border:1px solid #fff; padding:5px;">'.$sch['document_as_approves'].'</td>
				  <td style="background-color:#e5e5e5; color: #000; border:1px solid #fff; padding:5px;">'.$sch['remark'].'</td>
				</tr>';
				$i++;
			}
			$html .= '</tbody></table>';
			
			require_once 'Zend/Mail.php';
			$mail = new Zend_Mail();
			$mail->setBodyHtml($html);
			$mail->setFrom("noreply@isort.id");
			
			$emails = $actionplanClass->getActionPlanEmail($params['c'], $this->site_id);
			print_r($emails);
			
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
				if(in_array($category['category_id'], array(1,2,3,5,6)))
				{
					if($category['category_id'] == 6) $title = "Preventive Maintenance";
					else $title = "Action Plan";
				
					$schedule = $actionplanClass->getActionPlanUpcomingSchedule2($site['site_id'], $category['category_id'], $startdate, $enddate);
					
					$botToken = '716005387:AAFhUdDGlXjK5YFMYqwx_lCpqpp8760S_TE';
					$website="https://api.telegram.org/bot".$botToken;
					$chatId=$category['site_'.$site['site_id']];  //Receiver Chat Id 

					if(!empty($schedule))
					{
						$html = 'Dear All,<br/><br/> 
			Please find the table below for your kindly reminder;<br/>
			<h2>Reminder for your '.$category['category_name'].' '.$title.' '.date("Y", strtotime("+1 week")).'</h2>

						<table>
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
							<th style="background-color:#9e824b; color: #fff; border:1px solid #fff; padding:5px;">No</th>
							<th style="background-color:#9e824b; color: #fff; border:1px solid #fff; padding:5px;">Date</th>
							<th style="background-color:#9e824b; color: #fff; border:1px solid #fff; padding:5px;">Target</th>
							<th style="background-color:#9e824b; color: #fff; border:1px solid #fff; padding:5px;">Action Plan '.date("Y", strtotime("+1 week")).'</th>	
							</tr>
						</thead>
						<tbody>';
						$i=0;
						$txt = '['.strtoupper($title).' WEEKLY REMINDER]
						
';
						foreach($schedule as &$sch)
						{
							$date = explode(" ", $sch['schedule_date']);
							$formatted_schedule_date = date("j F Y", strtotime($date[0]));
							
							$html .= '<tr>
							<td style="background-color:#e5e5e5; color: #000; border:1px solid #fff; padding:5px;">'.($i+1).'</th>
							<td style="background-color:#e5e5e5; color: #000; border:1px solid #fff; padding:5px;">'.$formatted_schedule_date.'</td>
							<td style="background-color:#e5e5e5; color: #000; border:1px solid #fff; padding:5px;">'.$sch['target_name'].'</td>
							<td style="background-color:#e5e5e5; color: #000; border:1px solid #fff; padding:5px;">'.$sch['activity_name'].'</td>
							</tr>';

							$txt .= $sch['target_name'].' - '.$sch['activity_name'].' : '.$formatted_schedule_date.'

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
						$mail->setFrom("noreply@isort.id");
						
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
					<th style="background-color:#9e824b; color: #fff; border:1px solid #fff; padding:5px;">No</th>
					<th style="background-color:#9e824b; color: #fff; border:1px solid #fff; padding:5px;">Date</th>
					<th style="background-color:#9e824b; color: #fff; border:1px solid #fff; padding:5px;">Activity</th>	
					<th style="background-color:#9e824b; color: #fff; border:1px solid #fff; padding:5px;">Document as approves</th>
					<th style="background-color:#9e824b; color: #fff; border:1px solid #fff; padding:5px;">Status</th>
				</tr>
				</thead>
				<tbody>';
			$i=0;
			foreach($curYearReviewSchedule as &$sch)
			{
				$date = explode(" ", $sch['schedule_date']);
				$formatted_schedule_date = date("j F Y", strtotime($date[0]));
				
				$html .= '<tr>
					<td style="background-color:#e5e5e5; color: #000; border:1px solid #fff; padding:5px;">'.($i+1).'</th>
					<td style="background-color:#e5e5e5; color: #000; border:1px solid #fff; padding:5px;">'.$formatted_schedule_date.'</td>
					<td style="background-color:#e5e5e5; color: #000; border:1px solid #fff; padding:5px;">'.$sch['activity_name'].'</td>
					<td style="background-color:#e5e5e5; color: #000; border:1px solid #fff; padding:5px;">'.$sch['document_as_approves'].'</td>
					<td style="background-color:#e5e5e5; color: #000; border:1px solid #fff; padding:5px;">'.$sch['remark'].'</td>
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
					<th rowspan="2" style="background-color:#9e824b; color: #fff; border:1px solid #fff; padding:5px;">No</th>
					<th rowspan="2" style="background-color:#9e824b; color: #fff; border:1px solid #fff; padding:5px;">Date</th>
					<th rowspan="2" style="background-color:#9e824b; color: #fff; border:1px solid #fff; padding:5px;">Activity</th>	
					<th rowspan="2" style="background-color:#9e824b; color: #fff; border:1px solid #fff; padding:5px;">Document as approves</th>
					<th colspan="2" style="background-color:#9e824b; color: #fff; border:1px solid #fff; padding:5px;">Status</th>
					<th rowspan="2" style="background-color:#9e824b; color: #fff; border:1px solid #fff; padding:5px;">Remark</th>
				</tr>
				<tr>
					<th style="background-color:#9e824b; color: #fff; border:1px solid #fff; padding:5px;">Achieved</th>
					<th style="background-color:#9e824b; color: #fff; border:1px solid #fff; padding:5px;">Miss</th>
				</tr>
				</thead>
				<tbody>';
			$j=0;
			foreach($lastWeekReviewSchedule as &$sch2)
			{
				$date = explode(" ", $sch2['schedule_date']);
				$formatted_schedule_date = date("j F Y", strtotime($date[0]));
				
				$html .= '<tr>
				<td style="background-color:#e5e5e5; color: #000; border:1px solid #fff; padding:5px;">'.($j+1).'</th>
				<td style="background-color:#e5e5e5; color: #000; border:1px solid #fff; padding:5px;">'.$formatted_schedule_date.'</td>
				<td style="background-color:#e5e5e5; color: #000; border:1px solid #fff; padding:5px;">'.$sch2['activity_name'].'</td>
				<td style="background-color:#e5e5e5; color: #000; border:1px solid #fff; padding:5px;">';
				if($sch2['status'] == 1) { 
					if(!empty($sch2['documents']))
					{
						$html .= '<ul style="padding-left: 15px;">';
						foreach($sch2['documents'] as $document) {
							$html .= '<li><a href="'.$this->baseUrl.'/actionplan/'.strtolower($category['category_name']).'/'.substr($document['uploaded_date'], 0, 4)."/".$document['filename'].'" target="_blank">'.$document['filename'].'</a></li>';
						}
						$html .= "</ul>";
					}
				} else {
					$html .= str_replace("<br>","&#13;",$sch2['document_as_approves']);
				}
				$html .= '</td>
				<td style="background-color:#e5e5e5; color: #000; border:1px solid #fff; padding:5px;" align="center">';
				if($sch2['status'] == 1) $html .= '&#10004;';			
				$html .= '</td><td style="background-color:#e5e5e5; color: #000; border:1px solid #fff; padding:5px;" align="center">';
				if($sch2['status'] != 1) $html .= '&#10004;';
				$html .= '</td><td style="background-color:#e5e5e5; color: #000; border:1px solid #fff; padding:5px;">'.$sch2['remark'].'</td>
				</tr>';
				$i++;
			}
			$html .= '</tbody></table>';
		}
		
		require_once 'Zend/Mail.php';
		$mail = new Zend_Mail();
		$mail->setBodyHtml($html);
		$mail->setFrom("noreply@isort.id");
		
		$emails = $actionplanClass->getActionPlanEmail($params['c'], $this->site_id);
		print_r($emails);
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
								<th style="background-color:#9e824b; color: #fff; border:1px solid #fff; padding:5px;">No</th>
								<th style="background-color:#9e824b; color: #fff; border:1px solid #fff; padding:5px;">Date</th>
								<th style="background-color:#9e824b; color: #fff; border:1px solid #fff; padding:5px;">Target</th>	
								<th style="background-color:#9e824b; color: #fff; border:1px solid #fff; padding:5px;">Activity</th>	
								<th style="background-color:#9e824b; color: #fff; border:1px solid #fff; padding:5px;">Document as approves</th>
								<th style="background-color:#9e824b; color: #fff; border:1px solid #fff; padding:5px;">Status</th>
							</tr>
							</thead>
							<tbody>';
						$i=0;
						foreach($curYearReviewSchedule as &$sch)
						{
							$date = explode(" ", $sch['schedule_date']);
							$formatted_schedule_date = date("j F Y", strtotime($date[0]));
							
							$html .= '<tr>
								<td style="background-color:#e5e5e5; color: #000; border:1px solid #fff; padding:5px;">'.($i+1).'</th>
								<td style="background-color:#e5e5e5; color: #000; border:1px solid #fff; padding:5px;">'.$formatted_schedule_date.'</td>
								<td style="background-color:#e5e5e5; color: #000; border:1px solid #fff; padding:5px;">'.$sch['target_name'].'</td>
								<td style="background-color:#e5e5e5; color: #000; border:1px solid #fff; padding:5px;">'.$sch['activity_name'].'</td>
								<td style="background-color:#e5e5e5; color: #000; border:1px solid #fff; padding:5px;">'.$sch['doc_act'].'</td>
								<td style="background-color:#e5e5e5; color: #000; border:1px solid #fff; padding:5px;">'.$sch['remarks_act'].'</td>
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
								<th rowspan="2" style="background-color:#9e824b; color: #fff; border:1px solid #fff; padding:5px;">No</th>
								<th rowspan="2" style="background-color:#9e824b; color: #fff; border:1px solid #fff; padding:5px;">Date</th>
								<th rowspan="2" style="background-color:#9e824b; color: #fff; border:1px solid #fff; padding:5px;">Target</th>
								<th rowspan="2" style="background-color:#9e824b; color: #fff; border:1px solid #fff; padding:5px;">Activity</th>	
								<th rowspan="2" style="background-color:#9e824b; color: #fff; border:1px solid #fff; padding:5px;">Document as approves</th>
								<th colspan="2" style="background-color:#9e824b; color: #fff; border:1px solid #fff; padding:5px;">Status</th>
							</tr>
							<tr>
								<th style="background-color:#9e824b; color: #fff; border:1px solid #fff; padding:5px;">Achieved</th>
								<th style="background-color:#9e824b; color: #fff; border:1px solid #fff; padding:5px;">Miss</th>
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
								<td style="background-color:#e5e5e5; color: #000; border:1px solid #fff; padding:5px;">'.($j+1).'</th>
								<td style="background-color:#e5e5e5; color: #000; border:1px solid #fff; padding:5px;">'.$formatted_schedule_date.'</td>
								<td style="background-color:#e5e5e5; color: #000; border:1px solid #fff; padding:5px;">'.$sch2['target_name'].'</td>
								<td style="background-color:#e5e5e5; color: #000; border:1px solid #fff; padding:5px;">'.$sch2['activity_name'].'</td>
								<td style="background-color:#e5e5e5; color: #000; border:1px solid #fff; padding:5px;">';
								if($sch2['status'] == 1) { 
									if(!empty($sch2['documents']))
									{
										$html .= '<ul style="padding-left: 15px;">';
										foreach($sch2['documents'] as $document) {
											$html .= '<li><a href="'.$this->baseUrl.'/actionplan/'.strtolower(str_replace(" & ","",$category['category_name'])).'/'.substr($document['uploaded_date'],0,4)."/".$document['filename'].'" target="_blank">'.$document['filename'].'</a></li>';
										}
										$html .= "</ul>";
									}
								} else {
									$html .= str_replace("<br>","&#13;",$sch2['doc_act']);
								}
								$html .= '</td>
								<td style="background-color:#e5e5e5; color: #000; border:1px solid #fff; padding:5px;" align="center">';
								if($sch2['status'] == 1) $html .= '&#10004;';			
								$html .= '</td><td style="background-color:#e5e5e5; color: #000; border:1px solid #fff; padding:5px;" align="center">';
								if($sch2['status'] != 1) $html .= '&#10004;';
								$html .= '</td></tr>';
								$j++;
							}
						}
						$html .= '</tbody></table>';
					}
					
					require_once 'Zend/Mail.php';
					$mail = new Zend_Mail();
					$mail->setBodyHtml($html);
					$mail->setFrom("noreply@isort.id");
					
					$emails = $actionplanClass->getActionPlanReviewEmail($category['category_id'], $site['site_id']);
					Zend_Loader::LoadClass('siteClass', $this->modelDir);
					$siteClass = new siteClass();
					

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

			if(empty($params['y']) && date("n") > 2) $year = date("Y");
			elseif(empty($params['y']) && date("n") < 3) $year = date("Y")-1;
			else $year = $params['y'];

			$this->view->selectedYear = $year;

			Zend_Loader::LoadClass('categoryClass', $this->modelDir);
			$categoryClass = new categoryClass();	
			$this->view->category = $categoryClass->getCategoryById($params['c']);

			if($this->allowApproveCQC == 0) $showDisapprove = 1;
			else $showDisapprove = 0;

			if(!empty($params['period'])) $period = $params['period'];
			else{
				if(date("n") > 2 && date("n") < 9) $period = 1;
				else $period = 2;
			}
			$this->view->period = $period;
			$ap = $actionplanClass->getActionPlanForCQC($params['c'], $year, $showDisapprove, $period);
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

	public function showcurrentcqclistAction() {
		if($this->showCQC == 1)
		{
			$params = $this->_getAllParams();
			
			Zend_Loader::LoadClass('actionplanClass', $this->modelDir);
			$actionplanClass = new actionplanClass();

			if(empty($params['y'])) {
				if(date("n") < 3) $year = date("Y") - 1;
				else $year = date("Y");
			}
			else $year = $params['y'];

			$this->view->selectedYear = $year;

			Zend_Loader::LoadClass('categoryClass', $this->modelDir);
			$categoryClass = new categoryClass();	
			$this->view->category = $categoryClass->getCategoryById($params['c']);

			if($this->allowApproveCQC == 0) $showDisapprove = 1;
			else $showDisapprove = 0;

			if(!empty($params['period'])) $period = $params['period'];
			else{
				if(date("n") > 2 && date("n") < 9) $period = 1;
				else $period = 2;
				
			}

			$ap = $actionplanClass->getActionPlanForCQC($params['c'], $year, $showDisapprove, $period);
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
			
			echo $this->view->render('cqc_table.tpl');
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
				$datafolder = $this->config->paths->html."actionplan/cqc/".strtolower(str_replace(" & ", "", $category['category_name']))."/";
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
						$remark .= '<br/><i class="fa fa-paperclip"></i> <a href="'.$this->baseUrl.'/actionplan/cqc/'.strtolower(str_replace(" & ", "", $category['category_name'])).'/'.substr($c['uploaded_date'],0,4)."/".$c['attachment'].'" target="_blank">'.$c['attachment'].'</a>';
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
				$datafolder = $this->config->paths->html."actionplan/".strtolower(str_replace(" & ", "", $category['category_name']))."/".date("Y")."/";
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
	
	public function cqcemailconfirmationAction() {
		if($this->showCQCEmail == 1)
		{
			$params = $this->_getAllParams();
			
			Zend_Loader::LoadClass('actionplanClass', $this->modelDir);
			$actionplanClass = new actionplanClass();

			$period = 1;
			if(empty($params['y']) && date("n") > 3) $year = date("Y");
			else if(date("n") < 3) {
				$year = date("Y")-1;
				$period = 2;
			}
			else $year = $params['y'];

			$this->view->selectedYear = $year;

			Zend_Loader::LoadClass('categoryClass', $this->modelDir);
			$categoryClass = new categoryClass();	
			$this->view->category = $categoryClass->getCategoryById($params['c']);

			$showDisapprove = 1;

			$ap = $actionplanClass->getActionPlanForCQC($params['c'], $year, $showDisapprove, $period);
			foreach($ap as &$a)
			{	
				$date = explode(" ", $a['schedule_date']);
				$a['date'] = date("j F Y", strtotime($date[0]));
				$a['documents'] = $actionplanClass->getActionPlanAttachmentListByScheduleId($a['schedule_id']);
				$a['cqc'] = $actionplanClass->getCQCByScheduleId($a['schedule_id']);
			}
			$this->view->ap = $ap;
			
			$to = $actionplanClass->getCqcEmail($params['c'], "");
			if(!empty($to))
			{
				$toEmail = "";
				foreach($to as $t)
				{
					$toEmail .= $t['email'].", ";
				}
			}
			$this->view->to = substr($toEmail, 0, -2);

			$cc = $actionplanClass->getCqcEmail($params['c'], "1");
			if(!empty($cc))
			{
				$ccEmail = "";
				foreach($cc as $c)
				{
					$ccEmail .= $c['email'].", ";
				}
			}
			$this->view->cc = substr($ccEmail, 0, -2);

			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "CQC Email Confirmation";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);	
			
			$this->renderTemplate('cqc_email_confirmation.tpl'); 
		}
		else {
			$this->_response->setRedirect($this->baseUrl.'/default');
			$this->_response->sendResponse();
			exit();
		}
	}

	public function sendcqcemailAction() {
		if($this->showCQCEmail == 1)
		{
			$params = $this->_getAllParams();

			Zend_Loader::LoadClass('categoryClass', $this->modelDir);
			$categoryClass = new categoryClass();	
			$category = $categoryClass->getCategoryById($params['c']);
			
			Zend_Loader::LoadClass('actionplanClass', $this->modelDir);
			$actionplanClass = new actionplanClass();

			$period = 1;
			if(empty($params['y']) && date("n") > 3) $year = date("Y");
			else if(date("n") < 3) {
				$year = date("Y")-1;
				$period = 2;
			}
			else $year = $params['y'];

			
			$html = 'Dear all,<br/>
			Dengan ini, Technical Service sampaikan hasil CQC dari tanggal 1 Juli - 31 Desember 2020.<br/>
			Secara keseluruhan, pemenuhan pelaksanaan action plan sudah terlaksana dengan baik, namun masih terdapat beberapa temuan yang harus <strong>diperbaiki dan diunggah kembali</strong> sesuai dengan catatan di bawah ini.<br/>
			<span style="color:red">Note: Batas waktu mengunggah dokumen pada tanggal '.date("j F Y",mktime(0, 0, 0, date("m")  , date("d")+14, date("Y"))).'</span><br/>';

			

			$showDisapprove = 1;

			$ap = $actionplanClass->getActionPlanForCQC($params['c'], $year, $showDisapprove, $period);

			if(!empty($ap))
			{
				$html .= '<table>
				<thead>
					<tr>
					<th style="background-color:#1f497d; color: #fff; border:1px solid #fff; padding:5px;">Module</th>
					<th style="background-color:#1f497d; color: #fff; border:1px solid #fff; padding:5px;">Target</th>
					<th style="background-color:#1f497d; color: #fff; border:1px solid #fff; padding:5px;">Activity</th>	
					<th style="background-color:#1f497d; color: #fff; border:1px solid #fff; padding:5px;">Date</th>
					<th style="background-color:#1f497d; color: #fff; border:1px solid #fff; padding:5px;">Remarks</th>
					</tr>
				</thead>
				<tbody>';
				$i=0;
				foreach($ap as &$a)
				{
					$date = explode(" ", $a['schedule_date']);
					$a['date'] = date("j F Y", strtotime($date[0]));
					$a['documents'] = $actionplanClass->getActionPlanAttachmentListByScheduleId($a['schedule_id']);
					$a['cqc'] = $actionplanClass->getCQCByScheduleId($a['schedule_id']);

					$remarks = "";
					if(!empty($a['cqc'])) {
						foreach($a['cqc'] as $cqc) {
							$remarks .= $cqc['remarks'];
							if(!empty($cqc['attachment'])) {
								$remarks .= '<br/><i class="fa fa-paperclip"></i> <a href="'.$this->baseUrl.'/storage/actionplan/cqc/'.strtolower(str_replace(" & ", "", $this->category['category_name'])).'/'.$cqc['attachment'].'" target="_blank">'.$cqc['attachment'].'</a>';
						
							}
						}
					}
					
					$html .= '<tr>
					<td style="background-color:#dbe5f1; color: #000; border:1px solid #fff; padding:5px;">'.$a['module_name'].'</th>
					<td style="background-color:#dbe5f1; color: #000; border:1px solid #fff; padding:5px;">'.$a['target_name'].'</td>
					<td style="background-color:#dbe5f1; color: #000; border:1px solid #fff; padding:5px;">'.$a['activity_name'].'</td>
					<td style="background-color:#dbe5f1; color: #000; border:1px solid #fff; padding:5px;">'.$a['date'].'</td>
					<td style="background-color:#dbe5f1; color: #000; border:1px solid #fff; padding:5px;">'.$remarks.'</td>
					</tr>';
					$i++;
				}
				$html .= '</tbody></table>';
			}
			
			require_once 'Zend/Mail.php';
			$mail = new Zend_Mail();
			$mail->setBodyHtml($html);
			$mail->setFrom("noreply@isort.id");

			$to = $actionplanClass->getCqcEmail($params['c'], "");
			if(!empty($to))
			{
				foreach($to as $t)
				{
					$mail->addTo($t['email']);
				}
			}

			$cc = $actionplanClass->getCqcEmail($params['c'], "1");
			if(!empty($cc))
			{
				foreach($cc as $c)
				{
					$mail->addCC($c['email']);
				}
			}
			
			$emails = $actionplanClass->getActionPlanEmail($params['c'], $this->site_id);
			Zend_Loader::LoadClass('siteClass', $this->modelDir);
			$siteClass = new siteClass();
			
			$site = $siteClass->getSiteById($this->site_id);
			$success = 0;
	
			
			$mail->setSubject($this->ident['initial']." - ".$category['category_name'].' CQC Periode: 1 Juli - 31 Desember '.$year);
			
			try {
				$mail->send();
				//echo "success";
				$success = 1;
			}
			catch (Exception  $ex) {
				//echo "failed=".$ex;
			}
			unset($mail);
			
			if($success)
			{
				echo "Email successfully sent";
			}
			else{
				echo "Sending email failed, please try again.";
			}
		}
		else {
			$this->_response->setRedirect($this->baseUrl.'/default');
			$this->_response->sendResponse();
			exit();
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
	
	public function importAction() {
		include "SimpleXLSX.php";
		$params = $this->_getAllParams();
		Zend_Loader::LoadClass('actionplanClass', $this->modelDir);
		$actionplanClass = new actionplanClass();
		
		$xlsx = SimpleXLSX::parse($this->config->paths->html.'Action_plan_template.xlsx');
	
		/* SECURITY */
		$i = 0;
		$mCtr = 1;
		$tCtr = 1;
		$aCtr = 1;
		$cur_module_id = 0;
		foreach($xlsx->rows(0) as $row )
		{
			if($i > 0)
			{
				/* add module */
				if(!empty($row[0]))
				{
					$data['action_plan_module_id'] = "";
					$data['module_name'] = $row[0];
					$data['sort_order'] = $mCtr;
					$data['category_id'] = 1;
					$data['show_year'] = date("Y");
					$cur_module_id = $actionplanClass->saveActionPlanModule($data);
					$mCtr++;
				}
				
				/* add target */
				if(!empty($row[1]))
				{
					$data['action_plan_target_id'] = "";
					$data['action_plan_module_id'] = $cur_module_id;
					$data['target_name'] = $row[1];
					$data['sort_order'] = $tCtr;
					$data['category_id'] = 1;
					
					$cur_target_id = $actionplanClass->saveActionPlanTarget($data);
					$tCtr++;					
				}
				
				/* add activity */
				if(!empty($row[2]))
				{
					$data['action_plan_target_id'] = $cur_target_id;
					$data['activity_name'] = $row[2];
					$data['sort_order'] = $aCtr;
					$data['category_id'] = 1;
					$data['total_schedule'] = $row[3];
					$data['document_as_approve'] = "";
					$data['remarks'] = "";
					
					$cur_activity_id = $actionplanClass->saveActionPlanActivity($data);
					$aCtr++;					
				}
				
				print_r($data); echo "<br/>";		
			}
			$i++;
		}
		echo "<br/><br/><br/>";
		
		/* SAFETY */
		$i = 0;
		$mCtr = 1;
		$tCtr = 1;
		$aCtr = 1;
		$cur_module_id = 0;
		$data = array();
		foreach($xlsx->rows(1) as $row )
		{
			if($i > 0)
			{
				/* add module */
				if(!empty($row[0]))
				{
					$data['action_plan_module_id'] = "";
					$data['module_name'] = $row[0];
					$data['sort_order'] = $mCtr;
					$data['category_id'] = 3;
					$data['show_year'] = date("Y");
					$cur_module_id = $actionplanClass->saveActionPlanModule($data);
					$mCtr++;
				}
				
				/* add target */
				if(!empty($row[1]))
				{
					$data['action_plan_target_id'] = "";
					$data['action_plan_module_id'] = $cur_module_id;
					$data['target_name'] = $row[1];
					$data['sort_order'] = $tCtr;
					$data['category_id'] = 3;
					
					$cur_target_id = $actionplanClass->saveActionPlanTarget($data);
					$tCtr++;					
				}
				
				/* add activity */
				if(!empty($row[2]))
				{
					$data['action_plan_target_id'] = $cur_target_id;
					$data['activity_name'] = $row[2];
					$data['sort_order'] = $aCtr;
					$data['category_id'] = 3;
					$data['total_schedule'] = $row[3];
					$data['document_as_approve'] = "";
					$data['remarks'] = "";
					
					$cur_activity_id = $actionplanClass->saveActionPlanActivity($data);
					$aCtr++;					
				}
				
				print_r($data); echo "<br/>";		
			}
			$i++;
		}
		echo "<br/><br/><br/>";
		
		/* PARKING */
		$i = 0;
		$mCtr = 1;
		$tCtr = 1;
		$aCtr = 1;
		$cur_module_id = 0;
		$data = array();
		foreach($xlsx->rows(2) as $row )
		{
			if($i > 0)
			{
				/* add module */
				if(!empty($row[0]))
				{
					$data['action_plan_module_id'] = "";
					$data['module_name'] = $row[0];
					$data['sort_order'] = $mCtr;
					$data['category_id'] = 5;
					$data['show_year'] = date("Y");
					$cur_module_id = $actionplanClass->saveActionPlanModule($data);
					$mCtr++;
				}
				
				/* add target */
				if(!empty($row[1]))
				{
					$data['action_plan_target_id'] = "";
					$data['action_plan_module_id'] = $cur_module_id;
					$data['target_name'] = $row[1];
					$data['sort_order'] = $tCtr;
					$data['category_id'] = 5;
					
					$cur_target_id = $actionplanClass->saveActionPlanTarget($data);
					$tCtr++;					
				}
				
				/* add activity */
				if(!empty($row[2]))
				{
					$data['action_plan_target_id'] = $cur_target_id;
					$data['activity_name'] = $row[2];
					$data['sort_order'] = $aCtr;
					$data['category_id'] = 5;
					$data['total_schedule'] = $row[3];
					$data['document_as_approve'] = "";
					$data['remarks'] = "";
					
					$cur_activity_id = $actionplanClass->saveActionPlanActivity($data);
					$aCtr++;					
				}
				
				print_r($data); echo "<br/>";		
			}
			$i++;
		}
		echo "<br/><br/><br/>";
		
		/* HOUSEKEEPING */
		$i = 0;
		$mCtr = 1;
		$tCtr = 1;
		$aCtr = 1;
		$cur_module_id = 0;
		$data = array();
		foreach($xlsx->rows(3) as $row )
		{
			if($i > 0)
			{
				/* add module */
				if(!empty($row[0]))
				{
					$data['action_plan_module_id'] = "";
					$data['module_name'] = $row[0];
					$data['sort_order'] = $mCtr;
					$data['category_id'] = 2;
					$data['show_year'] = date("Y");
					$cur_module_id = $actionplanClass->saveActionPlanModule($data);
					$mCtr++;
				}
				
				/* add target */
				if(!empty($row[1]))
				{
					$data['action_plan_target_id'] = "";
					$data['action_plan_module_id'] = $cur_module_id;
					$data['target_name'] = $row[1];
					$data['sort_order'] = $tCtr;
					$data['category_id'] = 2;
					
					$cur_target_id = $actionplanClass->saveActionPlanTarget($data);
					$tCtr++;					
				}
				
				/* add activity */
				if(!empty($row[2]))
				{
					$data['action_plan_target_id'] = $cur_target_id;
					$data['activity_name'] = $row[2];
					$data['sort_order'] = $aCtr;
					$data['category_id'] = 2;
					$data['total_schedule'] = $row[3];
					$data['document_as_approve'] = "";
					$data['remarks'] = "";
					
					$cur_activity_id = $actionplanClass->saveActionPlanActivity($data);
					$aCtr++;					
				}
				
				print_r($data); echo "<br/>";		
			}
			$i++;
		}
		echo "<br/><br/><br/>";
		
		/* ENGINEERING */
		$i = 0;
		$mCtr = 1;
		$tCtr = 1;
		$aCtr = 1;
		$cur_module_id = 0;
		$data = array();
		foreach($xlsx->rows(4) as $row )
		{
			if($i > 0)
			{
				/* add module */
				if(!empty($row[0]))
				{
					$data['action_plan_module_id'] = "";
					$data['module_name'] = $row[0];
					$data['sort_order'] = $mCtr;
					$data['category_id'] = 6;
					$data['show_year'] = date("Y");
					$cur_module_id = $actionplanClass->saveActionPlanModule($data);
					$mCtr++;
				}
				
				/* add target */
				if(!empty($row[1]))
				{
					$data['action_plan_target_id'] = "";
					$data['action_plan_module_id'] = $cur_module_id;
					$data['target_name'] = $row[1];
					$data['sort_order'] = $tCtr;
					$data['category_id'] = 6;
					
					$cur_target_id = $actionplanClass->saveActionPlanTarget($data);
					$tCtr++;					
				}
				
				/* add activity */
				if(!empty($row[2]))
				{
					$data['action_plan_target_id'] = $cur_target_id;
					$data['activity_name'] = $row[2];
					$data['sort_order'] = $aCtr;
					$data['category_id'] = 6;
					$data['total_schedule'] = $row[3];
					$data['document_as_approve'] = "";
					$data['remarks'] = "";
					
					$cur_activity_id = $actionplanClass->saveActionPlanActivity($data);
					$aCtr++;					
				}
				
				print_r($data); echo "<br/>";		
			}
			$i++;
		}
		echo "<br/><br/><br/>";
	}
	
	function exportaplisttopdfAction() {
		$params = $this->_getAllParams();
		
		Zend_Loader::LoadClass('actionplanClass', $this->modelDir);
		$actionplanClass = new actionplanClass();
		
		$year = $params['y'];

		$actionPlans = $actionplanClass->getAllSchedule($params['c'], $year);
		
		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "Export Action Plan List ". $year." to PDF";
		$logData['data'] = json_encode($params);
		$logsTable->insertLogs($logData);

		if($params['c'] == 6)	$title = "Preventive Maintenance";
		else $title = "Action Plan";
		
		require_once('fpdf/mc_table.php');
		$pdf=new PDF_MC_Table();
		$pdf->AddPage();
		$pdf->SetFont('Arial','B',14);
		$pdf->Cell(80);
		$pdf->Cell(20,10,$title." Schedule List ".$year,0,0,'C');
		$pdf->Ln(20);

		$pdf->SetFillColor(158,130,75);
		$pdf->SetTextColor(255);
		$pdf->SetDrawColor(153);
		$pdf->SetLineWidth(.3);
		$pdf->SetFont('','B', 9);		
		// Header
		$pdf->Cell(55,6,'Module',1,0,'C',true);
		$pdf->Cell(55,6,'Target',1,0,'C',true);
		$pdf->Cell(55,6,'Activity',1,0,'C',true);
		$pdf->Cell(25,6,'Date',1,0,'C',true);
		$pdf->Ln();
		
		// Color and font restoration
		$pdf->SetFillColor(238,238,238);
		$pdf->SetTextColor(0);
		$pdf->SetFont('');
		
		/*$pdf->Cell(40,18,'Words Here', 1,0, 'C');
		$x = $pdf->GetX();
		$pdf->Cell(40,6,'Words Here', 1,0);
		$pdf->Cell(40,6,'Words Here', 1,1);
		$pdf->SetX($x);
		$pdf->Cell(40,6,'[x] abc', 1,0);
		$pdf->Cell(40,6,'[x] Checkbox 1', 1,1);
		$pdf->SetX($x);
		$pdf->Cell(40,6,'[x] def', 1,0);
		$pdf->Cell(40,6,'[x] Checkbox 1', 1,1);*/

		// Data
		$fill = false;
		if(!empty($actionPlans))
		{
			$i = 1;
			$module = "";
			$curModule = "";
			$target = "";
			$curTarget = "";
			$activity = "";
			$pdf->SetWidths(array(55, 55, 55, 25));	
			foreach($actionPlans as $ap)
			{				
				if($activity != $ap['activity_name'])
				{
					if(!empty($activity))
					{ 
						$pdf->Row(array($curModule,$curTarget,$activity,$schedule_dates));
						$curModule = "";
						$curTarget = "";
					}
					$activity = $ap['activity_name'];					
					$schedule_dates = "";
				}
				if($module != $ap['module_name'])
					$module = $curModule = $ap['module_name'];
				if($target != $ap['target_name'])
					$target = $curTarget = $ap['target_name'];
				
				$schedule = explode(" ",$ap['schedule_date']);
				$ap['schedule_date_formatted'] = date("j M Y",strtotime($schedule[0]));
				$schedule_dates .= $ap['schedule_date_formatted']."\n";
				
				$i++;
			}
			$pdf->Row(array($curModule,$curTarget,$activity,$schedule_dates));
		}	
	
		$pdf->Output();
	}
	
	
	function migrateapactivityAction() {
		Zend_Loader::LoadClass('actionplanClass', $this->modelDir);
		$actionplanClass = new actionplanClass();
		
		$activity = $actionplanClass->getActionPlanActivities();
		echo "INSERT INTO `action_plan_activity` (site_id, action_plan_target_id, activity_name, sort_order, category_id, total_schedule, document_as_approve, remarks, chief, spv, staff, admin, chief_bobot, spv_bobot, staff_bobot, admin_bobot, kpi_only) VALUES<br/>";
		if(!empty($activity))
		{
			foreach($activity as $a)
			{
				echo "(".$a['site_id'].",".(intval($a['action_plan_target_id'])+11).",'".trim($a['activity_name'])."',".$a['sort_order'].",".$a['category_id'].",".$a['total_schedule'].",NULL,NULL,'1','1','1','1',NULL,NULL,NULL,NULL,'0'),<br/>";
			}
		}
	}
	
	function migrateapscheduleAction() {
		Zend_Loader::LoadClass('actionplanClass', $this->modelDir);
		$actionplanClass = new actionplanClass();
		
		$schedule = $actionplanClass->getActionPlanSchedule();
		echo "INSERT INTO `action_plan_schedule` (site_id, user_id, action_plan_module_id, action_plan_target_id, action_plan_activity_id, schedule_date, created_date, week, month, status, document_as_approves, remark, status_achieved, allow_additional_upload, original_schedule_date, rating, cqc_approved, cqc_approved_by_user, cqc_approved_date) VALUES <br/>";
		if(!empty($schedule))
		{
			foreach($schedule as $s)
			{
				if($s['action_plan_module_id'] > 0)
				{
					//echo "<pre>"; print_r($s);
					if($s['action_plan_target_id'] == 79) $s['target_name'] = "Lantai Conblok bwh Caf";
					if($s['action_plan_target_id'] == 169) $s['target_name'] = "Kaca luar tenant caf";
					if($s['action_plan_target_id'] == 143) $s['target_name'] = "Logo caf";
					$target = $actionplanClass->getActionPlanTargetLive($s['target_name'], $s['action_plan_module_id']);
					//echo $target['action_plan_target_id'];
					
					//if(empty($target)) { echo "<pre>"; print_r($s); exit(); }
					
					if($s['action_plan_activity_id'] == 421) $s['activity_name'] = "Shisa Caf% AC NO.1";
					if($s['action_plan_activity_id'] == 422) $s['activity_name'] = "Shisa Caf% AC NO.2";
					$activity = $actionplanClass->getActionPlanActivityLive($s['activity_name'], $target['action_plan_target_id']);
					//print_r($activity);
					echo "(2,".$s['user_id'].",".$s['action_plan_module_id'].",".$target['action_plan_target_id'].",".$activity['action_plan_activity_id'].",'".$s['schedule_date']."','".$s['created_date']."',".$s['week'].",".$s['month'].",".$s['status'].",NULL,NULL,NULL,NULL,'".$s['original_schedule_date']."',".$s['rating'].",'".$s['cqc_approved']."',NULL,NULL),<br/>";
				}
			}
		}
	}
	
	function migratenullscheduleAction() {
		Zend_Loader::LoadClass('actionplanClass', $this->modelDir);
		$actionplanClass = new actionplanClass();
		
		$schedule = $actionplanClass->getNullActionPlanActivityLive();
		echo "<pre>"; //print_r($schedule);
		if(!empty($schedule))
		{
			$delete = "";
			foreach($schedule as $s)
			{
				$schedule2 = $actionplanClass->getActionPlanScheduleByDate($s['created_date'], $s['original_schedule_date']);
				if(!empty($schedule2))
				{
					$schedule2 = $schedule2[0];
					if($schedule2['action_plan_target_id'] == 213) $target['action_plan_target_id'] = 224;
					if($schedule2['action_plan_target_id'] == 176) $target['action_plan_target_id'] = 187;
					else $target = $actionplanClass->getActionPlanTargetLive($schedule2['target_name'], $schedule2['action_plan_module_id']);
					
					if($schedule2['action_plan_activity_id'] == 421) $schedule2['activity_name'] = "Shisa Caf% AC NO.1";
					if($schedule2['action_plan_activity_id'] == 422) $schedule2['activity_name'] = "Shisa Caf% AC NO.2";
					if($schedule2['action_plan_activity_id'] == 798) $schedule2['activity_name'] = "RISER & MAINLINE SHISA CAF";
					if($target['action_plan_target_id'] == 72) $target['action_plan_target_id'] = 73;
					if($target['action_plan_target_id'] == 65) $target['action_plan_target_id'] = 224;
					if($target['action_plan_target_id'] == 66) $target['action_plan_target_id'] = 70;
					if($target['action_plan_target_id'] == 124) $target['action_plan_target_id'] = 125;
					if($target['action_plan_target_id'] == 131) $target['action_plan_target_id'] = 134;
					if($target['action_plan_target_id'] == 196) $target['action_plan_target_id'] = 203;
					if($target['action_plan_target_id'] == 102) $target['action_plan_target_id'] = 149;
					if($target['action_plan_target_id'] == 94) $target['action_plan_target_id'] = 97;
					$activity = $actionplanClass->getActionPlanActivityLive($schedule2['activity_name'], $target['action_plan_target_id']);
					if(empty($activity)) { print_r($schedule2); print_r($target); }
					
					echo "(2,".$s['user_id'].",".$schedule2['action_plan_module_id'].",".$target['action_plan_target_id'].",".$activity['action_plan_activity_id'].",'".$s['schedule_date']."','".$s['created_date']."',".$s['week'].",".$s['month'].",".$s['status'].",NULL,NULL,NULL,NULL,'".$s['original_schedule_date']."',".$s['rating'].",'".$s['cqc_approved']."',NULL,NULL),<br/>";
					$delete .= "delete from action_plan_schedule where schedule_id=".$s['schedule_id'].";<br/>";
				}
			}
			echo "<br/><br/>".$delete;
		}
	}
	
	function migrateattachmentAction() {
		Zend_Loader::LoadClass('actionplanClass', $this->modelDir);
		$actionplanClass = new actionplanClass();
		
		$attachments = $actionplanClass->getActionPlanScheduleAttachments();
		echo "<pre>"; //print_r($schedule);
		if(!empty($attachments))
		{
			echo "INSERT INTO `action_plan_schedule_attachment` (site_id, user_id, action_plan_schedule_id, filename, uploaded_date, description, cqc) VALUES <br/>";
			foreach($attachments as $a)
			{
				$schedule = $actionplanClass->getActionPlanScheduleLiveByDate($a['schedule_date'], $a['created_date'], $a['original_schedule_date'], 14);
				if(!empty($schedule))
				{
					$schedule_id = $schedule[0]['schedule_id'];
					
					echo "(2,".$a['user_id'].",".$schedule_id.",'".$a['filename']."','".$a['uploaded_date']."','".trim($a['description'])."','".$a['cqc']."'),<br/>";
				}
			}
		}
	}
}

?>