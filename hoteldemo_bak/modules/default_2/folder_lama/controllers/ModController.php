<?php
require_once('actionControllerBase.php');

class ModController extends actionControllerBase
{
	private function checkAllowedMOD() {
		Zend_Loader::LoadClass('modscheduleClass', $this->modelDir);
		$modscheduleClass = new modscheduleClass();
		$allowedMODUsers = $modscheduleClass->getMODScheduleByDate(date("Y-m-d"));
		$allowedUsers = array();
		$j=0;
		if(!empty($allowedMODUsers))
		{
			foreach($allowedMODUsers as $au)
			{
				$allowedUsers[$j] = $au['mod_user_id'];
				$j++;
			}
			if(in_array($this->ident['user_id'], $allowedUsers)) $allowEditMod = 1;
			else  $allowEditMod = 0;
		}
		else{
			$allowEditMod = 1;
		}
		return $allowEditMod;
	}

	public function addAction() {
		if($this->showAddMod == 1 && $this->checkAllowedMOD() == 1) {
			$this->view->ident = $this->ident;
			$this->view->title = "Add Manager On Duty Report";
			
			$now = date("Y-m-d");
			
			Zend_Loader::LoadClass('issueClass', $this->modelDir);
			$issueClass = new issueClass();
			$this->view->utilitySpecificReport = $issueClass->getUnsolvedIssueByCategoryId2($params['id'], '10'); //$issueClass->getIssueByCategoryDateAndCatId($now, '4');
			
			Zend_Loader::LoadClass('securityClass', $this->modelDir);
			$securityClass = new securityClass();
			$securityReport = $securityClass->getChiefSecurityReportByDate($now);
			$securitySpecificReport = $security_issues = array();
			if(!empty($securityReport)) $securitySpecificReport = $securityClass->getUnsolvedIssuesByChiefSecurityReport2($securityReport['chief_security_report_id'], '0', $now); //$securityClass->getSpecificReportByChiefSecurityReport($securityReport['chief_security_report_id']);
			$security_issues = $issueClass->getMODOpenedIssues($now, 1);
			$this->view->securitySpecificReport =  array_merge($securitySpecificReport, $security_issues);

			Zend_Loader::LoadClass('safetyClass', $this->modelDir);
			$safetyClass = new safetyClass();
			$safetyReport = $safetyClass->getSafetyReportByDate($now);
			$safetySpecificReport = $safety_issues = array();
			if(!empty($safetyReport)) $safetySpecificReport = $safetyClass->getUnsolvedIssues2($safetyReport['report_id'], '0', $now); //$safetyClass->getSpecificReportById($safetyReport['report_id']);
			$safety_issues = $issueClass->getMODOpenedIssues($now, 3);
			$this->view->safetySpecificReport =  array_merge($safetySpecificReport, $safety_issues);

			Zend_Loader::LoadClass('housekeepingClass', $this->modelDir);
			$housekeepingClass = new housekeepingClass();		
			$housekeepingReport = $housekeepingClass->getReportByDate($now);
			if(!empty($housekeepingReport))
			{
				Zend_Loader::LoadClass('progressreportClass', $this->modelDir);
				$progressreportTable = new progressreportClass();	
				/*$hk_progress_report_shift12 = $progressreportTable->getHousekeepingProgressReport($housekeepingReport['housekeeping_report_id'], '12');
				$hk_progress_report_shift3 = $progressreportTable->getHousekeepingProgressReport($housekeepingReport['housekeeping_report_id'], '3');
				$this->view->hk_progress_report_shift = array_merge($hk_progress_report_shift12, $hk_progress_report_shift3);
				$this->view->hk_other_info = $progressreportTable->getHousekeepingOtherInfo($housekeepingReport['housekeeping_report_id']);*/
				$this->view->hk_progress_report_shift = $progressreportTable->getUnsolvedHousekeepingProgressReport2('0');
				$this->view->hk_other_info = $progressreportTable->getUnsolvedHousekeepingOtherInfo2('0');
				$this->view->hk_issues = $issueClass->getMODOpenedIssues($now, 2);
			}
			
			Zend_Loader::LoadClass('parkingClass', $this->modelDir);
			$parkingClass = new parkingClass();
			$parkingReport = $parkingClass->getReportByDate($now);
			$parkingSpecificReport = $parking_issues = array();
			if(!empty($parkingReport)) $parkingSpecificReport = $parkingClass->getUnsolvedIssues2($parkingReport['parking_report_id'], '0', $now); //$parkingClass->getSpecificReportById($parkingReport['parking_report_id']);
			$parking_issues = $issueClass->getMODOpenedIssues($now, 5);
			$this->view->parkingSpecificReport =  array_merge($parkingSpecificReport, $parking_issues);
			
			Zend_Loader::LoadClass('modClass', $this->modelDir);
			$mod = new modClass();
			$this->view->staff_condition_operasional = $mod->getStaffCondition($this->site_id, '0', '0');
			$this->view->staff_condition_non_operasional = $mod->getStaffCondition($this->site_id, '1', '0');

			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "Add MOD Report";
			$logData['data'] = "Opening the form page";
			$logsTable->insertLogs($logData);	
			
			$this->renderTemplate('form_mod.tpl'); 
		}
		else {
			$this->_response->setRedirect($this->baseUrl.'/default/mod/viewreport');
			$this->_response->sendResponse();
			exit();
		}
	}
	
	public function savereportAction() {
		set_time_limit(7200);
		
		$params = $this->_getAllParams();
		$params['user_id'] = $this->ident['user_id'];
		Zend_Loader::LoadClass('modClass', $this->modelDir);
		$modClass = new modClass();
		
		$report = $modClass->getReportByDate(date("Y-m-d"));
		if(empty($params['mod_report_id']) && !empty($report))
		{
			$this->view->title = "Add MOD Report";
			$this->view->message="Report is already exist";
			$this->view->mod = $params; 
			
			$this->view->staff_condition_operasional = $mod->getStaffCondition($this->site_id, '0');
			$this->view->staff_condition_non_operasional = $mod->getStaffCondition($this->site_id, '1');
			
			$this->renderTemplate('form_mod.tpl'); 
			exit();
		}
		
		$params['mod_report_id'] = $modClass->saveReport($params);

		$modClass->logMODReport($params);
		
		if(!empty($params['utility_specific_report_id']))
		{
			$u=0;
			Zend_Loader::LoadClass('issueClass', $this->modelDir);
			$issueClass = new issueClass();
			foreach($params['utility_specific_report_id'] as $utility_specific_report_id) {
				if(!empty($params['utility_specific_report_completion_date'][$u]) && $params['utility_specific_report_completion_date'][$u] != "0000-00-00 00:00:00")
					$issueClass->updateSolvedDate($utility_specific_report_id, $params['utility_specific_report_completion_date'][$u], $params['mod_report_id'],"mod_report_id");
				$u++;
			}
		}
		
		if(!empty($params['safety_specific_report_id']))
		{
			$sa=0;
			Zend_Loader::LoadClass('safetyClass', $this->modelDir);
			$safetyClass = new safetyClass();
			foreach($params['safety_specific_report_id'] as $safety_specific_report_id) {
				if(!empty($params['safety_specific_report_completion_date'][$sa]) && $params['safety_specific_report_completion_date'][$sa] != "0000-00-00 00:00:00")
					$safetyClass->updateSpecificReportCompletionDate($safety_specific_report_id, $params['safety_specific_report_completion_date'][$sa], $params['mod_report_id'],"mod_report_id");
				$sa++;
			}
		}
		
		if(!empty($params['security_specific_report_id']))
		{
			$se=0;
			Zend_Loader::LoadClass('securityClass', $this->modelDir);
			$securityClass = new securityClass();
			foreach($params['security_specific_report_id'] as $security_specific_report_id) {
				if(!empty($params['security_specific_report_completion_date'][$se]) && $params['security_specific_report_completion_date'][$se] != "0000-00-00 00:00:00")
					$securityClass->updateSpecificReportCompletionDate($security_specific_report_id, $params['security_specific_report_completion_date'][$se], $params['mod_report_id'],"mod_report_id");
				$se++;
			}
		}
		
		Zend_Loader::LoadClass('housekeepingClass', $this->modelDir);
		$housekeepingClass = new housekeepingClass();
		if(!empty($params['hk_progress_report_shift_id']))
		{
			$hk12=0;
			foreach($params['hk_progress_report_shift_id'] as $hk_progress_report_shift_id) {
				if(!empty($params['hk_progress_report_shift_completion_date'][$hk12]) && $params['hk_progress_report_shift_completion_date'][$hk12] != "0000-00-00 00:00:00")
					$housekeepingClass->updateProgressReportCompletionDate($hk_progress_report_shift_id, $params['hk_progress_report_shift_completion_date'][$hk12], $params['mod_report_id'],"mod_report_id");
				$hk12++;
			}
		}
		
		if(!empty($params['hk_other_info_id']))
		{
			$hoi=0;
			foreach($params['hk_other_info_id'] as $hk_other_info_id) {
				if(!empty($params['hk_other_info_completion_date'][$hoi]) && $params['hk_other_info_completion_date'][$hoi] != "0000-00-00 00:00:00")
					$housekeepingClass->updateOtherInfoCompletionDate($hk_other_info_id, $params['hk_other_info_completion_date'][$hoi], $params['mod_report_id'],"mod_report_id");
				$hoi++;
			}
		}
		
		if(!empty($params['parking_specific_report_id']))
		{
			$p=0;
			Zend_Loader::LoadClass('parkingClass', $this->modelDir);
			$parkingClass = new parkingClass();
			foreach($params['parking_specific_report_id'] as $parking_specific_report_id) {
				if(!empty($params['parking_specific_report_completion_date'][$p]) && $params['parking_specific_report_completion_date'][$p] != "0000-00-00 00:00:00")
					$parkingClass->updateSpecificReportCompletionDate($parking_specific_report_id, $params['parking_specific_report_completion_date'][$p], $params['mod_report_id'],"mod_report_id");
				$p++;
			}
		}
		
		$modClass->deleteStaffConditionByModReportId($params['mod_report_id']);
		if(!empty($params['operasional_staff_condition_id']))
		{
			$i = 0;
			foreach($params['operasional_staff_condition_id'] as $operasional_staff_condition_id)
			{
				if(!empty($params['inhouse_operational_staff_condition'][$i]) || !empty($params['outsource_operational_staff_condition'][$i])) {
					$dt['mod_report_id'] = $params['mod_report_id'];
					$dt['staff_condition_id'] = $operasional_staff_condition_id;
					$dt['inhouse'] = $params['inhouse_operational_staff_condition'][$i];
					$dt['outsource'] = $params['outsource_operational_staff_condition'][$i];
					$modClass->addStaffCondition($dt);
				}
				$i++;
			}
		}
		
		if(!empty($params['non_operasional_staff_condition_id']))
		{
			$j = 0;
			foreach($params['non_operasional_staff_condition_id'] as $non_operasional_staff_condition_id)
			{
				if(!empty($params['inhouse_non_operational_staff_condition'][$j]) || !empty($params['outsource_non_operational_staff_condition'][$j])) {
					$dt2['mod_report_id'] = $params['mod_report_id'];
					$dt2['staff_condition_id'] = $non_operasional_staff_condition_id;
					$dt2['inhouse'] = $params['inhouse_non_operational_staff_condition'][$j];
					$dt2['outsource'] = $params['outsource_non_operational_staff_condition'][$j];
					$modClass->addStaffCondition($dt2);
				}
				$j++;
			}
		}
		
		/*** EVENT ***/
		/*if(!empty($params['event_name']))
		{		
			$m = 0;
			$dt3=array();
			foreach($params['event_name'] as $event_name)
			{
				if($params['event_id'][$m] > 0)
				{
					$existingEvent = array();
					$dt3[$m] = $modClass->getEventById($params['event_id'][$m]);
					if(!empty($event_name)) $dt3[$m]['event_name'] = $event_name;
					if(!empty($params['event_location'][$m])) $dt3[$m]['event_location'] = $params['event_location'][$m];
					if(!empty($params['event_status'][$m])) $dt3[$m]['event_status'] = $params['event_status'][$m];
				}
				else
				{
					$dt3[$m]['mod_report_id'] = $params['mod_report_id'];
					$dt3[$m]['event_id'] = $params['event_id'];
					$dt3[$m]['event_name'] = $event_name;
					$dt3[$m]['event_location'] = $params['event_location'][$m];
					$dt3[$m]['event_status'] = $params['event_status'][$m];
					
				}	
				if(!empty($_FILES["event_image"]['name'][$m]))
				{
					$dt3[$m]['event_image'] = $_FILES["event_image"];
				}
				$m++;
			}			
			$modClass->deleteEventsByReportId($params['mod_report_id']);
			$m=0;
			foreach($dt3 as $dt)
			{
				$event_img = $dt['event_image'];
				unset($dt['event_image']);
				$event_id = $modClass->addEvent($dt);
				if($event_id > 0)
				{
					if(!empty($event_img['name'][$m]))
					{
						$ext = explode(".",$event_img['name'][$m]);
						$filename = $event_id."_mod.".$ext[count($ext)-1];
						if(move_uploaded_file($event_img["tmp_name"][$m], $this->config->paths->html."/images/event/".$filename))
							$modClass->updateEventFileName($event_id,'event_img', $filename);
					}
				}
				$m++;
			}			
		}*/
		
		/*** MALL CONDITION ***/
		
		/*if(!empty($params['area_mall_condition']))
		{		
			$n = 0;
			$dt4=array();
			foreach($params['area_mall_condition'] as $area_mall_condition)
			{
				if($params['mall_condition_id'][$n] > 0)
				{
					$existingEvent = array();
					$dt4[$n] = $modClass->getMallConditionById($params['mall_condition_id'][$n]);
					if(!empty($area_mall_condition)) $dt4[$n]['area'] = $area_mall_condition;
					if(!empty($params['condition_floor'][$n])) $dt4[$n]['condition_floor'] = $params['mall_condition_floor'][$n];
					if(!empty($params['status'][$n])) $dt4[$n]['status'] = $params['status_mall_condition'][$n];
				}
				else
				{
					$dt4[$n]['mod_report_id'] = $params['mod_report_id'];
					$dt4[$n]['mall_condition_id'] = $params['mall_condition_id'];
					$dt4[$n]['area'] = $area_mall_condition;
					$dt4[$n]['condition_floor'] = $params['mall_condition_floor'][$n];
					$dt4[$n]['status'] = $params['status_mall_condition'][$n];
					
				}	
				if(!empty($_FILES["condition_image"]['name'][$n]))
				{
					$dt4[$n]['condition_image'] = $_FILES["condition_image"];
				}
				$n++;
			}			
			$modClass->deleteMallConditionByReportId($params['mod_report_id']);
			$n=0;
			foreach($dt4 as $dt)
			{
				$condition_img = $dt['condition_image'];
				unset($dt['condition_image']);
				$mall_condition_id = $modClass->addMallCondition($dt);
				if($mall_condition_id > 0)
				{
					if(!empty($condition_img['name'][$n]))
					{
						$ext = explode(".",$condition_img['name'][$n]);
						$filename = $mall_condition_id."_mod.".$ext[count($ext)-1];
						if(move_uploaded_file($condition_img["tmp_name"][$n], $this->config->paths->html."/images/mall_condition/".$filename))
							$modClass->updateMallConditionFileName($mall_condition_id,'condition_img', $filename);
					}
				}
				$n++;
			}			
		}*/
		
		/*if(!empty($params['attachment-description']))
		{		
			$m = 0;
			$dt3=array();
			foreach($params['attachment-description'] as $description)
			{
				if($params['attachment_id'][$m] > 0)
				{
					$existingAttachment = array();
					$dt3[$m] = $modClass->getAttachmentById($params['attachment_id'][$m]);
					if(!empty($description)) $dt3[$m]['description'] = $description;
				}
				else
				{
					$dt3[$m]['site_id'] = $this->site_id;
					$dt3[$m]['report_id'] = $params['mod_report_id'];
					$dt3[$m]['description'] = $description;
					
				}	
				if(!empty($_FILES["attachment_file"]['name'][$m]))
				{	
					$dt3[$m]['attachment'] = $_FILES["attachment_file"];
				}
				$m++;
			}			
			$modClass->deleteAttachmentByReportId($params['mod_report_id']);
			$m=0;
			foreach($dt3 as $dt)
			{
				$attachment = $dt['attachment'];
				unset($dt['attachment']);
				$attachment_id = $modClass->addAttachment($dt);
				if($attachment_id > 0)
				{
					if(!empty($attachment['name'][$m]))
					{
						$ext = explode(".",$attachment['name'][$m]);
						$filename = $attachment_id.".".$ext[count($ext)-1];
						if(move_uploaded_file($attachment["tmp_name"][$m], $this->config->paths->html."/attachment/mod/".$filename))
							$modClass->updateAttachment($attachment_id,'filename', $filename);
					}
				}
				$m++;
			}			
		}*/

		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "Save MOD Report - page 1";
		$logData['data'] = json_encode($params);
		$logsTable->insertLogs($logData);	
		
		$this->_response->setRedirect($this->baseUrl.'/default/mod/page2/id/'.$params['mod_report_id']);
		$this->_response->sendResponse();
		exit();
	}
	
	public function page2Action() {
		$params = $this->_getAllParams();
		
		Zend_Loader::LoadClass('modClass', $this->modelDir);
		$modClass = new modClass();
		
		$mod = $modClass->getReportById($params['id']);
		$datetime = explode(" ",$mod['created_date']);

		$date = explode("-",$datetime[0]);
		$r_date = mktime(0, 0, 0, intval($date[1]), intval($date[2]), intval($date[0]));
		$mod['report_date'] = date("l, j F Y", $r_date);	

		Zend_Loader::LoadClass('parkingClass', $this->modelDir);
		$parkingClass = new parkingClass();
		$parkingReport = $parkingClass->getReportByDate($datetime[0]);
		$mod['car_parking'] = $parkingReport['inhouse_carcount_mobil'];
		$mod['car_drop_off'] = $parkingReport['inhouse_carcount_drop_off'];
		$mod['valet_parking'] = $parkingReport['inhouse_carcount_valet_reg'];
		$mod['box_vehicle'] = $parkingReport['inhouse_carcount_box'];
		$mod['taxi_bluebird'] = $parkingReport['inhouse_carcount_taxi'];
		$mod['motorbike'] = $parkingReport['inhouse_carcount_motor'];
		
		$this->view->mod = $mod;
			
		$this->view->staff_condition_operasional = $modClass->getStaffCondition($this->site_id, '0', $params['id']);
		$this->view->staff_condition_non_operasional = $modClass->getStaffCondition($this->site_id, '1', $params['id']);
		
		//$this->view->events = $mod->getEvents($params['id']);
		$this->view->mall_condition = $modClass->getMallConditions($params['id']);
		
		$equipmentTable = $this->loadModel('equipment');
		$this->view->equipments = $equipmentTable->getModEquipments($params['id']);
		
		$commentsTable = $this->loadModel('comments');
		$issueTable = $this->loadModel('issue');
		$incident = $issueTable->getIssueByDateAndType($datetime[0], 1);
		foreach($incident as &$ir)
		{
			if($ir['solved'] == 1)
			{
				$c = $commentsTable->getCommentByCommentDate($ir['solved_date']);
				$ir['comment'] = $c['comment'];
			}
		}
		$this->view->incident = $incident;
		$lostFound = $issueTable->getIssueByDateAndType($datetime[0], 3);
		foreach($lostFound as &$lf)
		{
			if($lf['solved'] == 1)
			{
				$c = $commentsTable->getCommentByCommentDate($lf['solved_date']);
				$lf['comment'] = $c['comment'];
			}
		}
		$this->view->lostFound = $lostFound;
		$glitch = $issueTable->getIssueByDateAndType($datetime[0], 2);
		foreach($glitch as &$gl)
		{
			if($gl['solved'] == 1)
			{
				$c = $commentsTable->getCommentByCommentDate($gl['solved_date']);
				$gl['comment'] = $c['comment'];
			}
		}
		$this->view->glitch = $glitch;

		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "Open MOD Report - page 2";
		$logData['data'] = json_encode($params);
		$logsTable->insertLogs($logData);	

		$this->renderTemplate('form_mod2.tpl'); 
	}
	
	public function savereport2Action() {
		set_time_limit(7200);
		
		$params = $this->_getAllParams();
		$params['user_id'] = $this->ident['user_id'];
		Zend_Loader::LoadClass('modClass', $this->modelDir);
		$modClass = new modClass();
		
		$report = $modClass->getReportByDate(date("Y-m-d"));
		if(empty($params['mod_report_id']) && !empty($report))
		{
			$this->view->title = "Add MOD Report";
			$this->view->message="Report is already exist";
			$this->view->mod = $params; 
			
			$this->view->staff_condition_operasional = $mod->getStaffCondition($this->site_id, '0');
			$this->view->staff_condition_non_operasional = $mod->getStaffCondition($this->site_id, '1');
			
			$this->renderTemplate('form_mod.tpl'); 
			exit();
		}
		
		$params['mod_report_id'] = $modClass->updateReportPage2($params);
		
		if(!empty($_FILES["way_system_img"]['name']) && !empty($params['mod_report_id']))
		{
			$way_system_image = $_FILES["way_system_img"];
			$ext = explode(".",$way_system_image['name']);
			$filename = $params['mod_report_id']."_mod.".$ext[count($ext)-1];
			if(move_uploaded_file($way_system_image["tmp_name"], $this->config->paths->html."/images/way_system/".$filename))
				$modClass->updateWaySystemFileName($params['mod_report_id'],'way_system_img', $filename);
		}
		
		$modClass->deleteStaffConditionByModReportId($params['mod_report_id']);
		if(!empty($params['operasional_staff_condition_id']))
		{
			$i = 0;
			foreach($params['operasional_staff_condition_id'] as $operasional_staff_condition_id)
			{
				if(!empty($params['inhouse_operational_staff_condition'][$i]) || !empty($params['outsource_operational_staff_condition'][$i])) {
					$dt['mod_report_id'] = $params['mod_report_id'];
					$dt['staff_condition_id'] = $operasional_staff_condition_id;
					$dt['inhouse'] = $params['inhouse_operational_staff_condition'][$i];
					$dt['outsource'] = $params['outsource_operational_staff_condition'][$i];
					$modClass->addStaffCondition($dt);
				}
				$i++;
			}
		}
		
		if(!empty($params['non_operasional_staff_condition_id']))
		{
			$j = 0;
			foreach($params['non_operasional_staff_condition_id'] as $non_operasional_staff_condition_id)
			{
				if(!empty($params['inhouse_non_operational_staff_condition'][$j]) || !empty($params['outsource_non_operational_staff_condition'][$j])) {
					$dt2['mod_report_id'] = $params['mod_report_id'];
					$dt2['staff_condition_id'] = $non_operasional_staff_condition_id;
					$dt2['inhouse'] = $params['inhouse_non_operational_staff_condition'][$j];
					$dt2['outsource'] = $params['outsource_non_operational_staff_condition'][$j];
					$modClass->addStaffCondition($dt2);
				}
				$j++;
			}
		}
		
		
		/*** EQUIPMENT ***/
		$equipmentTable = $this->loadModel('equipment');
		if(!empty($params['equipment_list_id']))
		{
			$i = 0;
			foreach($params['equipment_list_id'] as $equipment_list_id)
			{
				if(!empty($params['equipment_area'][$i]) || !empty($params['equipment_keterangan'][$i])) {
					$dt['mod_report_id'] = $params['mod_report_id'];
					$dt['site_id'] = $this->site_id;
					$dt['equipment_id'] = $params['equipment_id'][$i];
					$dt['mod_equipment_list_id'] = $equipment_list_id;
					$dt['area'] = $params['equipment_area'][$i];
					$dt['keterangan'] = $params['equipment_keterangan'][$i];
					$equipment_id = $equipmentTable->saveModEquipment($dt);
					if(!empty($_FILES["equipment_img"]['name'][$i]))
					{
						$equip_image = $_FILES["equipment_img"];
						$ext = explode(".",$equip_image['name'][$i]);
						$filename = $equipment_id."_mod.".$ext[count($ext)-1];
						if(move_uploaded_file($equip_image["tmp_name"][$i], $this->config->paths->html."/images/equipment/".$filename))
						{
							$equipmentTable->updateModEquipmentFileName($equipment_id,'image', $filename);
							$magickPath = "/usr/bin/convert";
							/*** resize image if size greater than 500 Kb ***/
							if(filesize($this->config->paths->html."/images/equipment/".$filename) > 500000) exec($magickPath . ' ' . $this->config->paths->html."/images/equipment/".$filename . ' -resize 800x800\> ' . $this->config->paths->html."/images/equipment/".$filename);
							
						}
					}
				}
				$i++;
			}
		}
		
		$issueTable = $this->loadModel('issue');
		
		/*** INCIDENT ***/
		
		if(!empty($params['issue_id_incident']))
		{
			$data = array();
			$l = 0;
			foreach($params['issue_id_incident'] as $issue_id)
			{
					$data['status'] = $params['status_incident'][$l];
					$data['keterangan'] = $params['keterangan_incident'][$l];
					$issueTable->updateIssue($issue_id, $data);
					$l++;
			}
		}
				
		/*** LOST AND FOUND ***/
		if(!empty($params['issue_id_lost_found']))
		{
			$data = array();
			$l = 0;
			foreach($params['issue_id_lost_found'] as $issue_id)
			{
					$data['status'] = $params['status_lost_found'][$l];
					$data['keterangan'] = $params['keterangan_lost_found'][$l];
					$issueTable->updateIssue($issue_id, $data);
					$l++;
			}
		}
		
		/*** GLITCH ***/
		if(!empty($params['issue_id_glitch']))
		{
			$data = array();
			$l = 0;
			foreach($params['issue_id_glitch'] as $issue_id)
			{
					$data['status'] = $params['status_glitch'][$l];
					$data['keterangan'] = $params['keterangan_glitch'][$l];
					$issueTable->updateIssue($issue_id, $data);
					$l++;
			}
		}

		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "Save MOD Report - page 2";
		$logData['data'] = json_encode($params);
		$logsTable->insertLogs($logData);	
		
		$this->_response->setRedirect($this->baseUrl.'/default/mod/page3/id/'.$params['mod_report_id']);
		$this->_response->sendResponse();
		exit();
	}
	
	public function page3Action() {
		$params = $this->_getAllParams();
		
		Zend_Loader::LoadClass('modClass', $this->modelDir);
		$modClass = new modClass();
		
		$mod = $modClass->getReportById($params['id']);
		$datetime = explode(" ",$mod['created_date']);

		$date = explode("-",$datetime[0]);
		$r_date = mktime(0, 0, 0, intval($date[1]), intval($date[2]), intval($date[0]));
		$mod['report_date'] = date("l, j F Y", $r_date);	
		
		$this->view->mod = $mod;
		
		$this->view->events = $modClass->getEvents($params['id']);

		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "Open MOD Report - page 3";
		$logData['data'] = json_encode($params);
		$logsTable->insertLogs($logData);	
		
		$this->view->attachment = $modClass->getAttachments($params['id']);
		$this->renderTemplate('form_mod3.tpl'); 
	}
	
	public function viewreportAction() {
		$params = $this->_getAllParams();
		Zend_Loader::LoadClass('modClass', $this->modelDir);
		$modClass = new modClass();
		Zend_Loader::LoadClass('modcommentsClass', $this->modelDir);
		$modcommentsClass = new modcommentsClass();
		Zend_Loader::LoadClass('modscheduleClass', $this->modelDir);
		$modscheduleClass = new modscheduleClass();

		if(empty($params['start'])) $params['start'] = '0';
		$params['pagesize'] = 10;
		$this->view->start = $params['start'];
		$mod = $modClass->getReports($params);
		foreach($mod as &$m)
		{
			$date = explode(" ", $m['created_date']);
			if($date[0] == date("Y-m-d")) $m['allowEdit'] = 1;
			else $m['allowEdit'] = 0;
			$arr_date = explode("-",$date[0]);
			$m['day_date'] = date("l, j F Y", mktime(0, 0, 0, $arr_date[1], $arr_date[2], $arr_date[0]));
			$m['comments'] = $modcommentsClass->getCommentsByModReportId($m['mod_report_id'], '3');
			$users = $modClass->getUsersByReport($m['mod_report_id']);
			if(!empty($users))
			{
				if($date[0] < "2019-06-11")	$m['name'] = $m['name'].", ";
				else $m['name'] = "";
				foreach($users as $u)
				{
					$m['name'] .= $u['name'].", ";
				}
				$m['name'] = substr($m['name'],0,-2);
			}

		}
		$this->view->mod = $mod;
		
		
		$totalReport = $modClass->getTotalReport();
		if($totalReport['total'] > 10)
		{
			if($params['start'] >= 10)
			{
				$this->view->firstPageUrl = "/default/mod/viewreport";
				$this->view->prevUrl = "/default/mod/viewreport/start/".($params['start']-$params['pagesize']);
			}
			if($params['start'] < (floor(($totalReport['total']-1)/10)*10))
			{
				$this->view->nextUrl = "/default/mod/viewreport/start/".($params['start']+$params['pagesize']);
				$this->view->lastPageUrl = "/default/mod/viewreport/start/".(floor(($totalReport['total']-1)/10)*10);
			}
		}
		$this->view->curPage = ($params['start']/$params['pagesize'])+1;
		$this->view->totalPage = ceil($totalReport['total']/$params['pagesize']);
		$this->view->startRec = $params['start'] + 1;
		$endRec = $params['start'] + $params['pagesize'];
		if($totalReport['total'] >=  $endRec) $this->view->endRec =  $endRec;
		else $this->view->endRec =  $totalReport['total'];		
		$this->view->totalRec = $totalReport['total'];

		$allowedMODUsers = $modscheduleClass->getMODScheduleByDate(date("Y-m-d"));
		$allowedUsers = array();
		$j=0;
		if(!empty($allowedMODUsers))
		{
			foreach($allowedMODUsers as $au)
			{
				$allowedUsers[$j] = $au['mod_user_id'];
				$j++;
			}
			if(in_array($this->ident['user_id'], $allowedUsers)) $allowEditMod = 1;
			else  $allowEditMod = 0;
		}
		else{
			$allowEditMod = 1;
		}
		$this->view->allowEditMod = $allowEditMod;
		$this->view->site_id = $this->site_id;

		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "View MOD Report List";
		$logData['data'] = json_encode($params);
		$logsTable->insertLogs($logData);	

    	$this->renderTemplate('view_mod.tpl');  
	}
	
	function getcommentsbyreportidAction()
	{
		$params = $this->_getAllParams();
		$this->view->ident = $this->ident;
		$category = $this->loadModel('category');
		$modCommentsTable = $this->loadModel('modcomments');
		$comments = $modCommentsTable->getCommentsByModReportId($params['id']);
		$commentText = "";
		if(!empty($comments)) {
			foreach($comments as $comment)
			{
				$commentText .= '<div style="border-bottom:1px solid #ccc; text-align:left; clear:both; margin-bottom:5px; padding-bottom:5px;"><strong>'.$comment['name'].' : </strong>'.$comment['comment'].'<br/>';
				if(!empty($comment['filename'])) $commentText .= '<a href="'.$this->baseUrl."/comments/".$comment['filename'].'" target="_blank"><i class="fa fa-paperclip"></i> '.$comment['filename'].'</a>';
				$commentText .= '<div style="font-size:10px; color:#aaa; text-align:right;">'.$comment['comment_date']."</div></div>";
			}
		}
		
		echo $commentText;	
	}
	
	function addcommentAction() {
		$params = $this->_getAllParams();
		$commentsTable = $this->loadModel('modcomments');
		$params['user_id'] = $this->ident['user_id'];
		$params['site_id'] = $this->site_id;

		if($_FILES["attachment"]["size"] > 0)
		{
			$ext = explode(".",$_FILES["attachment"]['name']);
			$filename = "mod_".date("YmdHis").".".$ext[count($ext)-1];
			$datafolder = $this->config->paths->html."/comments/";
			if(move_uploaded_file($_FILES["attachment"]["tmp_name"], $datafolder.$filename))
			{
				
				if(in_array(strtolower($ext[count($ext)-1]), array("jpg", "jpeg", "png","bmp")))
				{
					$magickPath = "/usr/bin/convert";
					/*** resize image if size greater than 500 Kb ***/
					if(filesize($datafolder.$filename) > 500000) exec($magickPath . ' ' . $datafolder.$filename . ' -resize 800x800\> ' . $datafolder.$filename);
				}					
				$params['filename'] = $filename;	
				$commentsTable->addComment($params);
			}		
		}
		else{
			$commentsTable->addComment($params);
		}
		$allParams = $params;
		
		Zend_Loader::LoadClass('modClass', $this->modelDir);
		$modClass = new modClass();
		$mod = $modClass->getReportById($params['report_id']);
		
		$datetime = explode(" ",$mod['created_date']);
		$date = explode("-",$datetime[0]);
		$r_date = mktime(0, 0, 0, intval($date[1]), intval($date[2]), intval($date[0]));
		$report_date = date("l, j F Y", $r_date);
		
		$categoryClass = $this->loadModel('category');
		$category = $categoryClass->getCategoryById('8');	

		$botToken = '716005387:AAFhUdDGlXjK5YFMYqwx_lCpqpp8760S_TE';
		$website="https://api.telegram.org/bot".$botToken;
		//$chatId=$category['telegram_channel_id'];  //Receiver Chat Id 
		$chatId=$category['site_'.$this->site_id];  //Receiver Chat Id 

		if(!empty($params['filename'])) $attachmenttext = '
attachment : '.$this->config->general->url."comments/".$params['filename'];

		$txt = '[NEW COMMENT] 
'.$this->ident['name']." : ".$params['comment'].$attachmenttext.'

[MOD REPORT]
Report Date : '.$report_date.'
Report Link : '.$this->config->general->url."default/mod/viewdetailreport/s/".$this->site_id."/id/".$params['report_id'];
		$params=array(
			'chat_id'=>$chatId,
			'text'=>$txt
		);
		$ch = curl_init($website . '/sendMessage');
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, ($params));
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$result = curl_exec($ch);
		curl_close($ch); 

		$allParams['telegram'] = $params;
		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "Add Comment to MOD Report";
		$logData['data'] = json_encode($allParams);
		$logsTable->insertLogs($logData);	

		echo $allParams['filename'];
	}	
	
	function getupdatedcommentsAction()
	{
		$params = $this->_getAllParams();
		$params['pagesize'] = 10;
		
		Zend_Loader::LoadClass('modClass', $this->modelDir);
		$modClass = new modClass();
		
		$data= array();
		$modReports = $modClass->getReports($params);	
		$commentsTable = $this->loadModel('modcomments');
		$i=0;
		foreach($modReports as $m) {
			$data[$i]['mod_report_id'] = $m['mod_report_id'];
			$comments = $commentsTable->getCommentsByModReportId($m['mod_report_id'], '3');
			if(!empty($comments)) { 
				$comment_content = "";
				foreach($comments as $comment)
				{
					$comment_content .= '<div style="border-bottom:1px solid #ccc; text-align:left; clear:both; margin-bottom:5px; padding-bottom:5px;"><strong>'.$comment['name'].' : </strong>'.$comment['comment'].'<br/>';
					if(!empty($comment['filename'])) $comment_content .= '<a href="'.$this->baseUrl."/comments/".$comment['filename'].'" target="_blank"><i class="fa fa-paperclip"></i> '.$comment['filename'].'</a>';
					$comment_content .= '<div style="font-size:10px; color:#aaa; text-align:right;">'.$comment['comment_date']."</div></div>";
				}
				$data[$i]['comment'] = $comment_content;
			}
			$i++;
		}
				
		echo json_encode($data);
	}

	function updatecommentsAction()
	{
		$params = $this->_getAllParams();
		$params['pagesize'] = 10;
		
		Zend_Loader::LoadClass('modClass', $this->modelDir);
		$modClass = new modClass();
		
		$data= array();

		$commentCacheName = "mod_comments_".$this->site_id."_".$params["start"];
		$data = $this->cache->load($commentCacheName);
		$i=0;
		if(empty($data))
		{
			$modReports = $modClass->getReports($params);	
			$commentsTable = $this->loadModel('modcomments');
			foreach($modReports as $m) {
				$data[$i]['mod_report_id'] = $m['mod_report_id'];
				$comments = $commentsTable->getCommentsByModReportId($m['mod_report_id'], '3');
				if(!empty($comments)) { 
					$comment_content = "";
					foreach($comments as $comment)
					{
						$comment_content .= '<div style="border-bottom:1px solid #ccc; text-align:left; clear:both; margin-bottom:5px; padding-bottom:5px;"><strong>'.$comment['name'].' : </strong>'.$comment['comment'].'<br/>';
						if(!empty($comment['filename'])) $comment_content .= '<a href="'.$this->baseUrl."/comments/".$comment['filename'].'" target="_blank"><i class="fa fa-paperclip"></i> '.$comment['filename'].'</a>';
						$comment_content .= '<div style="font-size:10px; color:#aaa; text-align:right;">'.$comment['comment_date']."</div></div>";
					}
					$data[$i]['comment'] = $comment_content;
				}
				$i++;
			}
			$this->cache->save($data, $commentCacheName, array($commentCacheName), 60);
		}		
		echo json_encode($data);
	}
	
	public function editAction() {
		$params = $this->_getAllParams();
		Zend_Loader::LoadClass('modClass', $this->modelDir);
		$modClass = new modClass();
		
		if(!empty($params['id'])) 
		{
			$mod = $modClass->getReportById($params['id']);
			$datetime = explode(" ",$mod['created_date']);
			/*if($datetime[0] != date("Y-m-d")) 
			{
				$this->_response->setRedirect($this->baseUrl.'/default/mod/viewreport');
				$this->_response->sendResponse();
				exit();
			}*/
			$date = explode("-",$datetime[0]);
			
			Zend_Loader::LoadClass('issueClass', $this->modelDir);
			$issueClass = new issueClass();
			$this->view->utilitySpecificReport = $issueClass->getMODOpenedIssues($datetime[0], '10'); //$issueClass->getIssueByCategoryDateAndCatId($datetime[0], '4');
			
			Zend_Loader::LoadClass('securityClass', $this->modelDir);
			$securityClass = new securityClass();
			$securityReport = $securityClass->getChiefSecurityReportByDate($datetime[0]);
			$securitySpecificReport = $security_issues = array();
			if(!empty($securityReport)) $securitySpecificReport = $securityClass->getUnsolvedIssuesByChiefSecurityReport2($securityReport['chief_security_report_id'],$params['id'], $datetime[0]); //$securityClass->getSpecificReportByChiefSecurityReport($securityReport['chief_security_report_id']);
			$security_issues = $issueClass->getMODOpenedIssues($datetime[0], 1);
			$this->view->securitySpecificReport =  array_merge($securitySpecificReport, $security_issues);

			Zend_Loader::LoadClass('safetyClass', $this->modelDir);
			$safetyClass = new safetyClass();
			$safetyReport = $safetyClass->getSafetyReportByDate($datetime[0]);
			$safetySpecificReport = $safety_issues = array();
			if(!empty($safetyReport)) $safetySpecificReport = $safetyClass->getUnsolvedIssues2($safetyReport['report_id'], $params['id'], $datetime[0]); //$safetyClass->getSpecificReportById($safetyReport['report_id']);
			$safety_issues = $issueClass->getMODOpenedIssues($datetime[0], 3);
			$this->view->safetySpecificReport =  array_merge($safetySpecificReport, $safety_issues);

			Zend_Loader::LoadClass('housekeepingClass', $this->modelDir);
			$housekeepingClass = new housekeepingClass();		
			$housekeepingReport = $housekeepingClass->getReportByDate($datetime[0]);
			if(!empty($housekeepingReport))
			{
				Zend_Loader::LoadClass('progressreportClass', $this->modelDir);
				$progressreportTable = new progressreportClass();	
				/*$hk_progress_report_shift12 = $progressreportTable->getHousekeepingProgressReport($housekeepingReport['housekeeping_report_id'], '12');
				$hk_progress_report_shift3 = $progressreportTable->getHousekeepingProgressReport($housekeepingReport['housekeeping_report_id'], '3');
				$this->view->hk_progress_report_shift = array_merge($hk_progress_report_shift12, $hk_progress_report_shift3);
				$this->view->hk_other_info = $progressreportTable->getHousekeepingOtherInfo($housekeepingReport['housekeeping_report_id']); */
				$this->view->hk_progress_report_shift = $progressreportTable->getUnsolvedHousekeepingProgressReport2($params['id']);
				$this->view->hk_other_info = $progressreportTable->getUnsolvedHousekeepingOtherInfo2($params['id']);
				$this->view->hk_issues = $issueClass->getMODOpenedIssues($datetime[0], 2);
			}
			
			Zend_Loader::LoadClass('parkingClass', $this->modelDir);
			$parkingClass = new parkingClass();
			$parkingReport = $parkingClass->getReportByDate($datetime[0]);
			$parkingSpecificReport = $parking_issues = array();
			if(!empty($parkingReport)) $parkingSpecificReport = $parkingClass->getUnsolvedIssues2($parkingReport['parking_report_id'], $params['id'], $datetime[0]); //$parkingClass->getSpecificReportById($parkingReport['parking_report_id']);
			$parking_issues = $issueClass->getMODOpenedIssues($datetime[0], 5);
			$this->view->parkingSpecificReport =  array_merge($parkingSpecificReport, $parking_issues);

			$r_date = mktime(0, 0, 0, intval($date[1]), intval($date[2]), intval($date[0]));
			$mod['report_date'] = date("l, j F Y", $r_date);	
			$this->view->mod = $mod;
		
			Zend_Loader::LoadClass('modClass', $this->modelDir);
			$mod = new modClass();			
			$this->view->staff_condition_operasional = $mod->getStaffCondition($this->site_id, '0', $params['id']);
			$this->view->staff_condition_non_operasional = $mod->getStaffCondition($this->site_id, '1', $params['id']);
			
			//$this->view->events = $mod->getEvents($params['id']);
			$this->view->mall_condition = $mod->getMallConditions($params['id']);
			
			$equipmentTable = $this->loadModel('equipment');
			$this->view->equipments = $equipmentTable->getModEquipments($params['id']);
			
			$issueTable = $this->loadModel('issue');
			$this->view->incident = $issueTable->getIssueByDateAndType($datetime[0], 1);
			$this->view->lostFound = $issueTable->getIssueByDateAndType($datetime[0], 3);
			$this->view->glitch = $issueTable->getIssueByDateAndType($datetime[0], 2);
		
			//$this->view->attachment = $modClass->getAttachments($params['id']);
		}	
		
		$this->view->title = "Edit Mod Report";
		$this->view->editMode = 1;

		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "Edit MOD Report";
		$logData['data'] = json_encode($params);
		$logsTable->insertLogs($logData);	

		$this->renderTemplate('form_mod.tpl');  
	}

	public function viewdetailreportAction() {
		if($this->showMod == 1)
		{
			$params = $this->_getAllParams();

			Zend_Loader::LoadClass('modClass', $this->modelDir);
			$modClass = new modClass();
			
			if(!empty($params['id'])) 
			{
				if($this->showSiteSelection == 1 && !empty($params['s']))
				{
					$siteTable = $this->loadModel('site');
					if($params['s'] != $this->ident['site_id'])
					{
						$siteTable->setSite($params['s']);
						$this->ident['site_id'] = $params['s'];
						$this->_response->setRedirect($this->baseUrl."/default/mod/viewdetailreport/id/".$params['id']);
						$this->_response->sendResponse();
						exit();
					}
				}

				$params['user_id'] = $this->ident['user_id'];
				$modClass->addReadMODReportLog($params);
				
				$mod = $modClass->getReportById($params['id']);
				$datetime = explode(" ",$mod['created_date']);
				
				$filename = $this->config->paths->html.'/pdf_report/mod/' . $this->site_id."_mod_".$params['id'].".pdf";			
				$date = explode("-",$datetime[0]);

				$r_date = mktime(0, 0, 0, intval($date[1]), intval($date[2]), intval($date[0]));
				$mod['report_date'] = date("l, j F Y", $r_date);	

				Zend_Loader::LoadClass('parkingClass', $this->modelDir);
				$parkingClass = new parkingClass();
				$parkingReport = $parkingClass->getReportByDate($datetime[0]);
				$mod['car_parking'] = $parkingReport['inhouse_carcount_mobil'];
				$mod['car_drop_off'] = $parkingReport['inhouse_carcount_drop_off'];
				$mod['valet_parking'] = $parkingReport['inhouse_carcount_valet_reg'];
				$mod['box_vehicle'] = $parkingReport['inhouse_carcount_box'];
				$mod['taxi_bluebird'] = $parkingReport['inhouse_carcount_taxi'];
				$mod['motorbike'] = $parkingReport['inhouse_carcount_motor'];
				
				$this->view->mod = $mod;
				Zend_Loader::LoadClass('issueClass', $this->modelDir);
				$issueClass = new issueClass();
				$this->view->utilitySpecificReport = $issueClass->getIssueByReportAndCatId('mod_report_id', $params['id'], '10', $datetime[0]);
				
				Zend_Loader::LoadClass('securityClass', $this->modelDir);
				$securityClass = new securityClass();
				//$securitySpecificReport = $securityClass->getSpecificReportByReport('mod_report_id', $params['id'], $datetime[0]);
				$security_solved_specific_report = $securityClass->getMODSolvedSpecificReports($params['id'], $datetime[0]);
				$security_issues = $issueClass->getIssueByReportAndCatId('mod_report_id', $params['id'], '1', $datetime[0]);
				$this->view->securitySpecificReport = array_merge($security_solved_specific_report, $security_issues);

				Zend_Loader::LoadClass('safetyClass', $this->modelDir);
				$safetyClass = new safetyClass();
				//$safetySpecificReport = $safetyClass->getSpecificReportByReport('mod_report_id', $params['id'], $datetime[0]);
				$safety_solved_specific_report = $safetyClass->getMODSolvedSpecificReports($params['id'], $datetime[0]);
				$safety_issues = $issueClass->getIssueByReportAndCatId('mod_report_id', $params['id'], '3', $datetime[0]);
				$this->view->safetySpecificReport = array_merge($safety_solved_specific_report, $safety_issues);
				
				Zend_Loader::LoadClass('housekeepingClass', $this->modelDir);
				$housekeepingClass = new housekeepingClass();		
				$progressreportTable = $this->loadModel('progressreport');
				$this->view->hk_progress_report_shift = $progressreportTable->getHousekeepingProgressReportByReport('mod_report_id', $params['id']);
				$this->view->hk_other_info = $progressreportTable->getHousekeepingOtherInfoByReport('mod_report_id', $params['id']);
				$this->view->hk_issues = $issueClass->getIssueByReportAndCatId('mod_report_id', $params['id'], '2', $datetime[0]);
				
				Zend_Loader::LoadClass('parkingClass', $this->modelDir);
				$parkingClass = new parkingClass();
				//$parkingSpecificReport = $parkingClass->getSpecificReportByReport('mod_report_id', $params['id']);
				$parking_solved_specific_report = $parkingClass->getMODSolvedSpecificReports($params['id'], $datetime[0]);
				$parking_issues = $issueClass->getIssueByReportAndCatId('mod_report_id', $params['id'], '5', $datetime[0]);
				$this->view->parkingSpecificReport = array_merge($parking_solved_specific_report, $parking_issues);

				$this->view->staff_condition_operasional = $modClass->getStaffCondition($this->site_id, '0', $params['id']);
				$this->view->staff_condition_non_operasional = $modClass->getStaffCondition($this->site_id, '1', $params['id']);
				
				$this->view->events = $modClass->getEvents($params['id']);
				$this->view->mall_condition = $modClass->getMallConditions($params['id']);
				
				$equipmentTable = $this->loadModel('equipment');
				$this->view->equipments = $equipmentTable->getModEquipments($params['id']);
				
				$issueTable = $this->loadModel('issue');
				$incident = $issueTable->getIssueByDateAndType($datetime[0], 1);
				$commentsTable = $this->loadModel('comments');
				foreach($incident as &$ir)
				{
					if($ir['solved'] == 1)
					{
						$c = $commentsTable->getCommentByCommentDate($ir['solved_date']);
						$ir['comment'] = $c['comment'];
					}
				}
				$this->view->incident = $incident;
				$lostFound = $issueTable->getIssueByDateAndType($datetime[0], 3);
				foreach($lostFound as &$lf)
				{
					if($lf['solved'] == 1)
					{
						$c = $commentsTable->getCommentByCommentDate($lf['solved_date']);
						$lf['comment'] = $c['comment'];
					}
				}
				$this->view->lostFound = $lostFound;
				$glitch = $issueTable->getIssueByDateAndType($datetime[0], 2);
				foreach($glitch as &$gl)
				{
					if($gl['solved'] == 1)
					{
						$c = $commentsTable->getCommentByCommentDate($gl['solved_date']);
						$gl['comment'] = $c['comment'];
					}
				}
				$this->view->glitch = $glitch;
				$this->view->attachment = $modClass->getAttachments($params['id']);

				$users = $modClass->getUsersByReport($params['id']);
				if(!empty($users))
				{
					if($datetime[0] < "2019-06-11")	$name = $name.", ";
					else $name = "";
					foreach($users as $u)
					{
						$name .= $u['name'].", ";
					}
					$name = substr($name,0,-2);
				}
				else $name = $mod['name'];	

				$this->view->name = $name;

				$this->view->ident = $this->ident;

				$modCommentsTable = $this->loadModel('modcomments');
				$this->view->comments = $modCommentsTable->getCommentsByModReportId($params['id'], 0, 'asc');

				$logsTable = $this->loadModel('logs');
				$logData['user_id'] = intval($this->ident['user_id']);
				$logData['action'] = "View Detail MOD Report";
				$logData['data'] = json_encode($params);
				$logsTable->insertLogs($logData);	
				
				$this->renderTemplate('view_mod_detail_report.tpl');  
			}
		}
		else {
			$this->_response->setRedirect($this->baseUrl);
			$this->_response->sendResponse();
			exit();
		}
	}
	
	public function exporttopdf2Action() {
		$params = $this->_getAllParams();
		Zend_Loader::LoadClass('modClass', $this->modelDir);
		$modClass = new modClass();
		
		if(!empty($params['id'])) 
		{
			$mod = $modClass->getReportById($params['id']);
			$datetime = explode(" ",$mod['created_date']);
		
			$date = explode("-",$datetime[0]);

			$r_date = mktime(0, 0, 0, intval($date[1]), intval($date[2]), intval($date[0]));
			$mod['report_date'] = date("l, j F Y", $r_date);	

			Zend_Loader::LoadClass('parkingClass', $this->modelDir);
			$parkingClass = new parkingClass();
			$parkingReport = $parkingClass->getReportByDate($datetime[0]);
			$mod['car_parking'] = $parkingReport['inhouse_carcount_mobil'];
			$mod['car_drop_off'] = $parkingReport['inhouse_carcount_drop_off'];
			$mod['valet_parking'] = $parkingReport['inhouse_carcount_valet_reg'];
			$mod['box_vehicle'] = $parkingReport['inhouse_carcount_box'];
			$mod['taxi_bluebird'] = $parkingReport['inhouse_carcount_taxi'];
			$mod['motorbike'] = $parkingReport['inhouse_carcount_motor'];
			
			Zend_Loader::LoadClass('issueClass', $this->modelDir);
			$issueClass = new issueClass();
			$utilitySpecificReport = $issueClass->getIssueByReportAndCatId('mod_report_id', $params['id'], '10', $datetime[0]);
			
			Zend_Loader::LoadClass('securityClass', $this->modelDir);
			$securityClass = new securityClass();
			//$securitySpecificReport = $securityClass->getSpecificReportByReport('mod_report_id', $params['id'], $datetime[0]);
			$security_solved_specific_report = $securityClass->getMODSolvedSpecificReports($params['id'], $datetime[0]);
			$security_issues = $issueClass->getIssueByReportAndCatId('mod_report_id', $params['id'], '1', $datetime[0]);
			$securitySpecificReport = array_merge($security_solved_specific_report, $security_issues);

			Zend_Loader::LoadClass('safetyClass', $this->modelDir);
			$safetyClass = new safetyClass();
			//$safetySpecificReport = $safetyClass->getSpecificReportByReport('mod_report_id', $params['id'], $datetime[0]);
			$safety_solved_specific_report = $safetyClass->getMODSolvedSpecificReports($params['id'], $datetime[0]);
			$safety_issues = $issueClass->getIssueByReportAndCatId('mod_report_id', $params['id'], '3', $datetime[0]);
			$safetySpecificReport = array_merge($safety_solved_specific_report, $safety_issues);
			
			Zend_Loader::LoadClass('housekeepingClass', $this->modelDir);
			$housekeepingClass = new housekeepingClass();		
			$progressreportTable = $this->loadModel('progressreport');
			$hk_progress_report_shift = $progressreportTable->getHousekeepingProgressReportByReport('mod_report_id', $params['id']);
			$hk_other_info = $progressreportTable->getHousekeepingOtherInfoByReport('mod_report_id', $params['id']);
			$hk_issues = $issueClass->getIssueByReportAndCatId('mod_report_id', $params['id'], '2', $datetime[0]);
			
			Zend_Loader::LoadClass('parkingClass', $this->modelDir);
			$parkingClass = new parkingClass();
			//$parkingSpecificReport = $parkingClass->getSpecificReportByReport('mod_report_id', $params['id']);
			$parking_solved_specific_report = $parkingClass->getMODSolvedSpecificReports($params['id'], $datetime[0]);
			$parking_issues = $issueClass->getIssueByReportAndCatId('mod_report_id', $params['id'], '5', $datetime[0]);
			$parkingSpecificReport = array_merge($parking_solved_specific_report, $parking_issues);

			$staff_condition_operasional = $modClass->getStaffCondition($this->site_id, '0', $params['id']);
			$staff_condition_non_operasional = $modClass->getStaffCondition($this->site_id, '1', $params['id']);
			
			$events = $modClass->getEvents($params['id']);
			$mall_condition = $modClass->getMallConditions($params['id']);
			
			$equipmentTable = $this->loadModel('equipment');
			$equipments = $equipmentTable->getModEquipments($params['id']);
			
			$issueTable = $this->loadModel('issue');
			$incident = $issueTable->getIssueByDateAndType($datetime[0], 1);
			$commentsTable = $this->loadModel('comments');
			foreach($incident as &$ir)
			{
				if($ir['solved'] == 1)
				{
					$c = $commentsTable->getCommentByCommentDate($ir['solved_date']);
					$ir['comment'] = $c['comment'];
				}
			}
			$lostFound = $issueTable->getIssueByDateAndType($datetime[0], 3);
			foreach($lostFound as &$lf)
			{
				if($lf['solved'] == 1)
				{
					$c = $commentsTable->getCommentByCommentDate($lf['solved_date']);
					$lf['comment'] = $c['comment'];
				}
			}
				
			$glitch = $issueTable->getIssueByDateAndType($datetime[0], 2);
			foreach($glitch as &$gl)
			{
				if($gl['solved'] == 1)
				{
					$c = $commentsTable->getCommentByCommentDate($gl['solved_date']);
					$gl['comment'] = $c['comment'];
				}
			}
			$attachment = $modClass->getAttachments($params['id']);

			$users = $modClass->getUsersByReport($params['id']);
			if(!empty($users))
			{
				if($datetime[0] < "2019-06-11")	$name = $name.", ";
				else $name = "";
				foreach($users as $u)
				{
					$name .= $u['name'].", ";
				}
				$name = substr($name,0,-2);
			}
			else $name = $mod['name'];

			
			
			/*** END OF SPECIFIC REPORT ***/
			
			require('PHPpdf/html2fpdf.php');


			$html= '<html>
			<head>
			<title>Manager On Duty Report</title>
			 
			</head>
			<body>
			<h2>Manager On Duty Report</h2>
			'.$name.'<br><br>
			'.$mod['site_fullname'].'
			
			<h3>DAY / DATE</h3>
			<table width="100%" cellspacing="6" cellpadding="5" align="center" border="1" valign="top">
				<tr><td><strong>Day / Date</strong></td><td colspan="3">'.$mod['report_date'].'</td></tr>
			</table>
			
			<h3>ISSUES</h3>
			<h4>A. Building Service</h4>';
		
			
		if(!empty($utilitySpecificReport)) { 	
			$html .= '<table width="100%" cellspacing="6" cellpadding="5" align="center" border="1" valign="top">
			<tr bgcolor="#afd9af">
				<th width="110"><strong>Foto</strong></th>
				<th width="100"><strong>Lokasi</strong></th>
				<th><strong>Deskripsi</strong></th>
				<th width="100"><strong>Completion Date</strong></th>
			</tr>';
			foreach($utilitySpecificReport as $usr) {
				$completion_date = explode(" ", $usr['solved_date']);
				$html .= '<tr>
					<td>';
				if(!empty($usr['picture'])) $html .= '<img src="'.$this->config->general->url.'images/issues/'.str_replace(".","_thumb.",$usr['picture']).'" width="40" style="margin-right:5px; margin-bottom:5px;" /> ';
				if(!empty($usr['solved_picture'])) $html .= '<img src="'.$this->config->general->url.'images/issues/'.str_replace(".","_thumb.",$usr['solved_picture']).'" width="40" style="margin-right:5px; margin-bottom:5px;" />';
				$html .= '</td>
					<td>'.$usr['location'].'</td>
					<td>'.$usr['description'].'</td>
					<td>'.$completion_date[0].'</td>
				</tr>';
			}
			$html .= '</table>';
		}
		else
		{
			$html .= 'No Building Service Issue<br>&nbsp;<br>';
		}
		
		$html .= '<h4>B. Safety</h4>';
		
		if(!empty($safetySpecificReport)) { 
			$html .= '<table width="100%" cellspacing="6" cellpadding="5" align="center" border="1" valign="top">
			<tr bgcolor="#afd9af">
				<th width="110"><strong>Foto</strong></th>
				<th width="100"><strong>Lokasi</strong></th>
				<th><strong>Deskripsi</strong></th>
				<th width="100"><strong>Completion Date</strong></th>
			</tr>';
			foreach($safetySpecificReport as $sasr) {
				if(!empty($sasr['completion_date'])) $safety_comp_date = $sasr['completion_date'];
				else $safety_comp_date = $sasr['solved_date'];
				$safety_completion_date = explode(" ", $safety_comp_date);
				if(!empty($sasr['issue_id'])) $sasr['detail'] = $sasr['description'];
				$html .= '<tr>
					<td>';
				if(!empty($sasr['picture'])) $html .= '<img src="'.$this->config->general->url.'images/issues/'.str_replace(".","_thumb.",$sasr['picture']).'" width="40" style="margin-right:5px;" /> ';
				if(!empty($sasr['solved_picture'])) $html .= '<img src="'.$this->config->general->url.'images/issues/'.str_replace(".","_thumb.",$sasr['solved_picture']).'" width="40" />';
				$html .= '</td>
					<td>'.$sasr['location'].'</td>
					<td>'.$sasr['detail'].'</td>
					<td>'.$safety_completion_date[0].'</td>
				</tr>';
			} 
			$html .= '</table>';
		}
		else
		{
			$html .= 'No Safety Issue<br>&nbsp;<br>';
		}		
		
		$html .= '<h4>C. Security</h4>';
		if(!empty($securitySpecificReport)) {
			$html .= '<table width="100%" cellspacing="6" cellpadding="5" align="center" border="1" valign="top">
			<tr bgcolor="#afd9af">
				<th width="110"><strong>Foto</strong></th>
				<th width="100"><strong>Lokasi</strong></th>
				<th><strong>Deskripsi</strong></th>
				<th width="100"><strong>Completion Date</strong></th>
			</tr>';
			foreach($securitySpecificReport as $sesr) {
				if(!empty($sesr['completion_date'])) $security_comp_date = $sesr['completion_date'];
				else $security_comp_date = $sesr['solved_date'];
				$security_completion_date = explode(" ", $security_comp_date);
				if(!empty($sesr['issue_id'])) $sesr['detail'] = $sesr['description'];
				$html .= '<tr>
					<td>';
				if(!empty($sesr['picture'])) $html .= '<img src="'.$this->config->general->url.'images/issues/'.str_replace(".","_thumb.",$sesr['picture']).'" width="40" style="margin-right:5px;" /> ';
				if(!empty($sesr['solved_picture'])) $html .= '<img src="'.$this->config->general->url.'images/issues/'.str_replace(".","_thumb.",$sesr['solved_picture']).'" width="40" />';
				$html .= '</td>
					<td>'.$sesr['location'].'</td>
					<td>'.$sesr['detail'].'</td>
					<td>'.$security_completion_date[0].'</td>
				</tr>';
			}
			$html .= '</table>';
		}
		else
		{
			$html .= 'No Security Issue<br>&nbsp;<br>';
		}	
		$html .= '<h4>D. Housekeeping</h4>';
		if(!empty($hk_progress_report_shift) || !empty($hk_other_info) || !empty($hk_issues))
		{
			$html .= '<table width="100%" cellspacing="6" cellpadding="5" align="center" border="1" valign="top">
			<tr bgcolor="#afd9af">
				<th width="160"><strong>Foto</strong></th>
				<th><strong>Lokasi</strong></th>
				<th width="120"><strong>Status</strong></th>
				<th width="100"><strong>Completion Date</strong></th>
			</tr>';

			if(!empty($hk_progress_report_shift)) { 
				
				foreach($hk_progress_report_shift as $hk_progress_report_shift) { 
					$hk_progress_report_shift_completion_date = explode(" ", $hk_progress_report_shift['completion_date']);
					$html .= '<tr>
						<td>';

					if(!empty($hk_progress_report_shift['img_before'])) 
					{
						$ext = explode(".",$hk_progress_report_shift['img_before']);
						if(in_array(strtolower($ext[count($ext)-1]), array("jpg", "jpeg")))	$html .= '<img src="'.$this->config->general->url.'images/progress_report/'.$hk_progress_report_shift['img_before'].'" width="40" style="margin-right:5px; margin-bottom:5px;"  /> '; 
						else $html .= '<a href="'.$this->config->general->url.'images/progress_report/'.$hk_progress_report_shift['img_before'].'">'.$hk_progress_report_shift['img_before']."</a> ";
					}
					if(!empty($hk_progress_report_shift['img_progress'])) 
					{
						$ext = explode(".",$hk_progress_report_shift['img_progress']);
						if(in_array(strtolower($ext[count($ext)-1]), array("jpg", "jpeg")))	$html .= '<img src="'.$this->config->general->url.'images/progress_report/'.$hk_progress_report_shift['img_progress'].'" width="40" style="margin-right:5px; margin-bottom:5px;" /> '; 
						else $html .= '<a href="'.$this->config->general->url.'images/progress_report/'.$hk_progress_report_shift['img_progress'].'">'.$hk_progress_report_shift['img_progress']."</a> ";
					}
					if(!empty($hk_progress_report_shift['img_after'])) 
					{
						$ext = explode(".",$hk_progress_report_shift['img_after']);
						if(in_array(strtolower($ext[count($ext)-1]), array("jpg", "jpeg")))	$html .= '<img src="'.$this->config->general->url.'/images/progress_report/'.$hk_progress_report_shift['img_after'].'" width="40" style="margin-right:5px; margin-bottom:5px;" /> '; 
						else $html .= '<a href="'.$this->config->general->url.'images/progress_report/'.$hk_progress_report_shift['img_after'].'">'.$hk_progress_report_shift['img_after']."</a> ";
					}

					$html .= '</td>
					<td>'.$hk_progress_report_shift['area'].'</td>
					<td>'.$hk_progress_report_shift['status'].'</td>
					<td>'.$hk_progress_report_shift_completion_date[0].'</td>
				</tr>';
				}
			}
			
			if(!empty($hk_other_info)) { 
				foreach($hk_other_info as $hk_other_info) { 
					$hk_other_info_completion_date = explode(" ", $hk_other_info['completion_date']);
					
					$html .= '<tr>
					<td>';
						if(!empty($hk_other_info['img_progress'])) {
							$ext = explode(".",$hk_other_info['img_progress']);
							if(in_array(strtolower($ext[count($ext)-1]), array("jpg", "jpeg")))	$html .= '<img src="'.$this->config->general->url.'images/progress_report/'.$hk_other_info['img_progress'].'" width="40" style="margin-right:5px;" /> ';
							else $html .= '<a href="'.$this->config->general->url.'images/progress_report/'.$hk_other_info['img_progress'].'">'.$hk_other_info['img_progress']."</a> ";
							
						}
					$html .= '</td>
						<td>'.$hk_other_info['area'].'</td>
						<td>'.$hk_other_info['status'].'</td>
						<td>'.$hk_other_info_completion_date[0].'</td>
					</tr>';
				}
			}

			if(!empty($hk_issues)) { 
				foreach($hk_issues as $hk_issue) { 
					$hk_issue_solved_date = explode(" ", $hk_issue['solved_date']);
					
					$html .= '<tr>
					<td>';
					if(!empty($hk_issue['picture'])) $html .= '<img src="'.$this->config->general->url.'images/issues/'.str_replace(".","_thumb.",$hk_issue['picture']).'" width="40" style="margin-right:5px;" /> ';
					if(!empty($hk_issue['solved_picture'])) $html .= '<img src="'.$this->config->general->url.'images/issues/'.str_replace(".","_thumb.",$hk_issue['solved_picture']).'" width="40" />';
					$html .= '</td>
						<td>'.$hk_issue['location'].'</td>
						<td>'.$hk_issue['description'].'</td>
						<td>'.$hk_issue_solved_date[0].'</td>
					</tr>';
				}
			}
			$html .= '</table>';
		}
		else
		{
			$html .= 'No Housekeeping Issue<br>&nbsp;<br>';
		}
		$html .= '<h4>E. Parking &amp; Traffic</h4>';
		
		if(!empty($parkingSpecificReport)) { 
			$html .= '<table width="100%" cellspacing="6" cellpadding="5" align="center" border="1" valign="top">
			<tr bgcolor="#afd9af">
				<th width="110"><strong>Foto</strong></th>
				<th width="80"><strong>Time</strong></th>
				<th width="80"><strong>Lokasi</strong></th>
				<th><strong>Deskripsi</strong></th>
				<th width="100"><strong>Completion Date</strong></th>
			</tr>';
			foreach($parkingSpecificReport as $psr) {
				if(!empty($psr['completion_date'])) $parking_comp_date = $psr['completion_date'];
				else $parking_comp_date = $psr['solved_date'];
				$parking_completion_date = explode(" ", $parking_comp_date);
				if(!empty($psr['issue_id'])) $psr['detail'] = $psr['description'];
				$html .= '<tr>
					<td>';
				if(!empty($psr['picture'])) $html .= '<img src="'.$this->config->general->url.'images/issues/'.str_replace(".","_thumb.",$psr['picture']).'" width="40" style="margin-right:5px;" /> ';
				if(!empty($psr['solved_picture'])) $html .= '<img src="'.$this->config->general->url.'images/issues/'.str_replace(".","_thumb.",$psr['solved_picture']).'" width="40" />';
				$html .= '</td>
					<td>';
				if($psr['issue_type_id'] != 4) $html .= $psr['time'];
				$html .= '</td>
					<td>';
				if($psr['issue_type_id'] != 6) { 
					if($psr['issue_type_id'] < 4) $html .= $psr['location']; 
					else $html .= $psr['area'];
				}
				$html .= '</td>
					<td>'.$psr['detail'].'</td>
					<td>'.$parking_completion_date[0].'</td>
				</tr>';
			}
			$html .= '</table>';
		}
		else
		{
			$html .= 'No Parking & Traffic Issue<br>&nbsp;<br>';
		}	
			
		$html .= '<h3>JUMLAH PETUGAS</h3>
		<h4>A. Inhouse</h4>
		<table width="100%" cellspacing="6" cellpadding="5" align="center" border="1" valign="top">
			<tr bgcolor="#afd9af">
			  <th width="120" rowspan="2"><strong>Divisi</strong></th>
			  <th colspan="5"><strong>Jumlah</strong></th>
			  <th rowspan="2"><strong>Keterangan</strong></th>
			</tr>
			<tr bgcolor="#afd9af">
				<th width="65"><strong>Shift 1</strong></th>
				<th width="65"><strong>Middle</strong></th>
				<th width="65"><strong>Shift 2</strong></th>
				<th width="65"><strong>Shift 3</strong></th>
				<th width="65"><strong>Absent</strong></th>
			</tr>
			<tr>
				<td>Engineering</td>
				<td>'.$mod['inhouse_engineering_shift1'].'</td>
				<td>'.$mod['inhouse_engineering_middle'].'</td>
				<td>'.$mod['inhouse_engineering_shift2'].'</td>
				<td>'.$mod['inhouse_engineering_shift3'].'</td>
				<td>'.$mod['inhouse_engineering_absent'].'</td>
				<td>'.$mod['inhouse_engineering_keterangan'].'</textarea></td>
			</tr>
			<tr>
				<td>BS</td>
				<td>'.$mod['inhouse_bs_shift1'].'</td>
				<td>'.$mod['inhouse_bs_middle'].'</td>
				<td>'.$mod['inhouse_bs_shift2'].'</td>
				<td>'.$mod['inhouse_bs_shift3'].'</td>
				<td>'.$mod['inhouse_bs_absent'].'</td>
				<td>'.$mod['inhouse_bs_keterangan'].'</textarea></td>
			</tr>
			<tr>
				<td>Tenant Relation</td>
				<td>'.$mod['inhouse_tr_shift1'].'</td>
				<td>'.$mod['inhouse_tr_middle'].'</td>
				<td>'.$mod['inhouse_tr_shift2'].'</td>
				<td>'.$mod['inhouse_tr_shift3'].'</td>
				<td>'.$mod['inhouse_tr_absent'].'</td>
				<td>'.$mod['inhouse_tr_keterangan'].'</textarea></td>
			</tr>
			<tr>
				<td>Security</td>
				<td>'.$mod['inhouse_security_shift1'].'</td>
				<td>'.$mod['inhouse_security_middle'].'</td>
				<td>'.$mod['inhouse_security_shift2'].'</td>
				<td>'.$mod['inhouse_security_shift3'].'</td>
				<td>'.$mod['inhouse_security_absent'].'</td>
				<td>'.$mod['inhouse_security_keterangan'].'</textarea></td>
			</tr>
			<tr>
				<td>Safety</td>
				<td>'.$mod['inhouse_safety_shift1'].'</td>
				<td>'.$mod['inhouse_safety_middle'].'</td>
				<td>'.$mod['inhouse_safety_shift2'].'</td>
				<td>'.$mod['inhouse_safety_shift3'].'</td>
				<td>'.$mod['inhouse_safety_absent'].'</td>
				<td>'.$mod['inhouse_safety_keterangan'].'</textarea></td>
			</tr>
			<tr>
				<td>Parking</td>
				<td>'.$mod['inhouse_parking_shift1'].'</td>
				<td>'.$mod['inhouse_parking_middle'].'</td>
				<td>'.$mod['inhouse_parking_shift2'].'</td>
				<td>'.$mod['inhouse_parking_shift3'].'</td>
				<td>'.$mod['inhouse_parking_absent'].'</td>
				<td>'.$mod['inhouse_parking_keterangan'].'</textarea></td>
			</tr>
			<tr>
				<td>Housekeeping</td>
				<td>'.$mod['inhouse_housekeeping_shift1'].'</td>
				<td>'.$mod['inhouse_housekeeping_middle'].'</td>
				<td>'.$mod['inhouse_housekeeping_shift2'].'</td>
				<td>'.$mod['inhouse_housekeeping_shift3'].'</td>
				<td>'.$mod['inhouse_housekeeping_absent'].'</td>
				<td>'.$mod['inhouse_housekeeping_keterangan'].'</textarea></td>
			</tr>
			<tr>
				<td>Customer Service</td>
				<td>'.$mod['inhouse_reception_shift1'].'</td>
				<td>'.$mod['inhouse_reception_middle'].'</td>
				<td>'.$mod['inhouse_reception_shift2'].'</td>
				<td>'.$mod['inhouse_reception_shift3'].'</td>
				<td>'.$mod['inhouse_reception_absent'].'</td>
				<td>'.$mod['inhouse_reception_keterangan'].'</textarea></td>
			</tr>
		</table>
		
		<h4>B. Outsource</h4>
		<table width="100%" cellspacing="6" cellpadding="5" align="center" border="1" valign="top">
			<tr bgcolor="#afd9af">
			  <th width="120" rowspan="2"><strong>Divisi</strong></th>
			  <th colspan="5"><strong>Jumlah</strong></th>
			  <th rowspan="2"><strong>Keterangan</strong></th>
			</tr>
			<tr bgcolor="#afd9af">
				<th width="65"><strong>Shift 1</strong></th>
				<th width="65"><strong>Middle</strong></th>
				<th width="65"><strong>Shift 2</strong></th>
				<th width="65"><strong>Shift 3</strong></th>
				<th width="65"><strong>Absent</strong></th>
			</tr>
			<tr>
				<td>Security</td>
				<td>'.$mod['outsource_security_safety_shift1'].'</td>
				<td>'.$mod['outsource_security_safety_middle'].'</td>
				<td>'.$mod['outsource_security_safety_shift2'].'</td>
				<td>'.$mod['outsource_security_safety_shift3'].'</td>
				<td>'.$mod['outsource_security_safety_absent'].'</td>
				<td>'.$mod['outsource_security_safety_keterangan'].'</textarea></td>
			</tr>
			<tr>
				<td>Safety</td>
				<td>'.$mod['outsource_safety_shift1'].'</td>
				<td>'.$mod['outsource_safety_middle'].'</td>
				<td>'.$mod['outsource_safety_shift2'].'</td>
				<td>'.$mod['outsource_safety_shift3'].'</td>
				<td>'.$mod['outsource_safety_absent'].'</td>
				<td>'.$mod['outsource_safety_keterangan'].'</textarea></td>
			</tr>
			<tr>
				<td>Parking</td>
				<td>'.$mod['outsource_parking_shift1'].'</td>
				<td>'.$mod['outsource_parking_middle'].'</td>
				<td>'.$mod['outsource_parking_shift2'].'</td>
				<td>'.$mod['outsource_parking_shift3'].'</td>
				<td>'.$mod['outsource_parking_absent'].'</td>
				<td>'.$mod['outsource_parking_keterangan'].'</textarea></td>
			</tr>
			<tr>
				<td>Valet</td>
				<td>'.$mod['outsource_valet_shift1'].'</td>
				<td>'.$mod['outsource_valet_middle'].'</td>
				<td>'.$mod['outsource_valet_shift2'].'</td>
				<td>'.$mod['outsource_valet_shift3'].'</td>
				<td>'.$mod['outsource_valet_absent'].'</td>
				<td>'.$mod['outsource_valet_keterangan'].'</textarea></td>
			</tr>
			<tr>
				<td>Housekeeping</td>
				<td>'.$mod['outsource_housekeeping_shift1'].'</td>
				<td>'.$mod['outsource_housekeeping_middle'].'</td>
				<td>'.$mod['outsource_housekeeping_shift2'].'</td>
				<td>'.$mod['outsource_housekeeping_shift3'].'</td>
				<td>'.$mod['outsource_housekeeping_absent'].'</td>
				<td>'.$mod['outsource_housekeeping_keterangan'].'</textarea></td>
			</tr>
			<tr>
				<td>Pest Control</td>
				<td>'.$mod['outsource_pest_control_shift1'].'</td>
				<td>'.$mod['outsource_pest_control_middle'].'</td>
				<td>'.$mod['outsource_pest_control_shift2'].'</td>
				<td>'.$mod['outsource_pest_control_shift3'].'</td>
				<td>'.$mod['outsource_pest_control_absent'].'</td>
				<td>'.$mod['outsource_pest_control_keterangan'].'</textarea></td>
			</tr>
		</table>
		
		<table width="100%" cellspacing="6" cellpadding="5" align="center" border="1" valign="top">
			<tr bgcolor="#afd9af">
				<th width="120"><strong>TOTAL</strong></th>
				<th width="65"><strong>'.$mod['total_shift1'].'</strong></th>
				<th width="65"><strong>'.$mod['total_middle'].'</strong></th>
				<th width="65"><strong>'.$mod['total_shift2'].'</strong></th>
				<th width="65"><strong>'.$mod['total_shift3'].'</strong></th>
				<th width="65"><strong>'.$mod['total_absent'].'</strong></th>
				<th><strong>'.$mod['total_keterangan'].'</strong></th>
			</tr>
		</table>';
			
		$html.= '<h3>JUMLAH KENDARAAN MASUK</h3>
			<table width="100%" cellspacing="6" cellpadding="5" align="center" border="1" valign="top">
				<tr bgcolor="#afd9af">
					<th width="50%">Jenis Kendaraan</th>
					<th width="50%">Jumlah</th>
				</tr>
				<tr>
					<td>Car Count Parking</td>
					<td>'.$mod['car_parking'].'</td>
				</tr>
				<tr>
					<td>Car Count Drop Off</td>
					<td>'.$mod['car_drop_off'].'</td>
				</tr>
				<tr>
					<td>Box Vehicle</td>
					<td>'.$mod['box_vehicle'].'</td>
				</tr>
				<tr>
					<td>Motorbike</td>
					<td>'.$mod['motorbike'].'</td>
				</tr>
				<tr>
					<td>Bus</td>
					<td>'.$mod['bus'].'</td>
				</tr>
				<tr>
					<td>Valet Service</td>
					<td>'.$mod['valet_parking'].'</td>
				</tr>
				
				<tr>
					<td>Taxi Bluebird</td>
					<td>'.$mod['taxi_bluebird'].'</td>
				</tr>
				<tr>
					<td>Taxi Non Blue bird</td>
					<td>'.$mod['taxi_non_bluebird'].'</td>
				</tr>					
			 </table>			

			<h3>FASILITAS/PERALATAN</h3>
			<table width="100%" cellspacing="6" cellpadding="5" align="center" border="1" valign="top">
				<tr bgcolor="#afd9af">
				  <th>Nama Fasilitas/Peralatan</th>
				  <th>Kondisi (Foto bila ada)</th>
				  <th>Lantai/Area</th>
				  <th>Keterangan</th>
				</tr>';
				  
				if(!empty($equipments)) {
					$i = 0;
					foreach($equipments as $equipment) {
						$html .= '<tr>
						<td>'.$equipment['equipment_name'].'</td>
						<td>';
					if(!empty($equipment['image']) && @getimagesize($this->config->paths->html.'/images/equipment/'.$equipment['image'])) {
						$html .= '<img src="'.$this->config->general->url.'images/equipment/'.$equipment['image'].'" height="50px" />';
					}
					$html .= '</td>
						<td>'.htmlentities(stripslashes($equipment['area'])).'</td>
						<td>'.$equipment['keterangan'].'</td>
					</tr>'; 
					$i++; } 
					}
				  $html .= '</table>	
				  
			<h3>INCIDENT</h3>';
				  
			if(!empty($incident)) {
				$html .= '<table width="100%" cellspacing="6" cellpadding="5" align="center" border="1" valign="top">
				<tr bgcolor="#afd9af">
				  <th>Nama Insiden</th>
				  <th width="150">Kondisi (Foto)</th>
				  <th width="100">Lantai/Area</th>
				  <th width="100">Status</th>
				  <th width="100">Keterangan</th>
				</tr>';
				$i = 0;
				foreach($incident as $inc) {
					if(empty($inc['status'])) $inc['status'] = $inc['comment'];
					$html .= '<tr>
					<td>'.$inc['description'].'</td>
					<td>';
				if(!empty($inc['picture']) && @getimagesize($this->config->paths->html.'/images/issues/'.str_replace(".","_thumb.",$inc['picture']))) {
					$html .= '<img src="'.$this->config->general->url.'images/issues/'.str_replace(".","_thumb.",$inc['picture']).'" height="50px" />';
				}
				if(!empty($inc['solved_picture']) && @getimagesize($this->config->paths->html.'/images/issues/'.str_replace(".","_thumb.",$inc['solved_picture']))) {
					$html .= '<img src="'.$this->config->general->url.'images/issues/'.str_replace(".","_thumb.",$inc['solved_picture']).'" height="50px" />';
				}
				$html .= '</td>
					<td>'.$inc['location'].'</td>
					<td>'.$inc['status'].'</td>
					<td>'.$inc['keterangan'].'</td>
				</tr>';
				$i++; }
				$html .= '</table>';
			}
			else
			{
				$html .= 'No Incident Report Issue<br>&nbsp;<br>';
			}
			$html .= '<h3>LOST &amp; FOUND</h3>';
				  
			if(!empty($lostFound)) {
				$html .= '<table width="100%" cellspacing="6" cellpadding="5" align="center" border="1" valign="top">
					<tr bgcolor="#afd9af">
					  <th>Kejadian</th>
					  <th width="150">Informasi Pelapor (Foto)</th>
					  <th>Lantai/Area</th>
					  <th>Status</th>
					  <th>Keterangan</th>
					</tr>';
				$i = 0;
				foreach($lostFound as $lf) {
					if(empty($lf['status'])) $lf['status'] = $lf['comment'];
					$html .= '<tr>
					<td>'.$lf['description'].'</td>
					<td>';
				if(!empty($lf['picture']) && @getimagesize($this->config->paths->html.'/images/issues/'.str_replace(".","_thumb.",$lf['picture']))) {
					$html .= '<img src="'.$this->config->general->url.'images/issues/'.str_replace(".","_thumb.",$lf['picture']).'" height="50px" />';
				}
				if(!empty($lf['solved_picture']) && @getimagesize($this->config->paths->html.'/images/issues/'.str_replace(".","_thumb.",$lf['solved_picture']))) {
					$html .= '<img src="'.$this->config->general->url.'images/issues/'.str_replace(".","_thumb.",$lf['solved_picture']).'" height="50px" />';
				}
				$html .= '</td>
					<td>'.$lf['location'].'</td>
					<td>'.$lf['status'].'</td>
					<td>'.$lf['keterangan'].'</td>
				</tr>';
				$i++; 
				} 
				$html .= '</table>';
			}
			else
			{
				$html .= 'No Lost & Found Issue<br>&nbsp;<br>';
			}
			
			$html .= '<h3>GLITCH</h3>';
				  
			if(!empty($glitch)) {
				$html .= '<table width="100%" cellspacing="6" cellpadding="5" align="center" border="1" valign="top">
				<tr bgcolor="#afd9af">
				  <th>Pelanggaran</th>
				  <th width="150">Foto Pelanggaran</th>
				  <th>Lantai/Area</th>
				  <th>Tindakan Perbaikan</th>
				  <th>Keterangan</th>
				</tr>';
				$i = 0;
				foreach($glitch as $g) {
					if(empty($g['status'])) $g['status'] = $g['comment'];
					$html .= '<tr>
					<td>'.$g['description'].'</td>
					<td>';
				if(!empty($g['picture']) && @getimagesize($this->config->paths->html.'/images/issues/'.str_replace(".","_thumb.",$g['picture']))) {
					$html .= '<img src="'.$this->config->general->url.'images/issues/'.str_replace(".","_thumb.",$g['picture']).'" height="50px" />';
				}
				if(!empty($g['solved_picture']) && @getimagesize($this->config->paths->html.'/images/issues/'.str_replace(".","_thumb.",$g['solved_picture']))) {
					$html .= '<img src="'.$this->config->general->url.'images/issues/'.str_replace(".","_thumb.",$g['solved_picture']).'" height="50px" />';
				}
				$html .= '</td>
					<td>'.$g['location'].'</td>
					<td>'.$g['status'].'</td>
					<td>'.$g['keterangan'].'</td>
				</tr>';
				$i++; 
				} 
				$html .= '</table>';
			}
			else
			{
				$html .= 'No Glitch Issue<br>&nbsp;<br>';
			}
			  $html .= '<h3>WAY SYSTEM</h3>';
			if(!empty($mod['way_system_img']) && @getimagesize($this->config->paths->html.'/images/way_system/'.$mod['way_system_img'])) {
				$html .= '<img src="'.$this->config->general->url.'images/way_system/'.$mod['way_system_img'].'" height="50px" />';
			}
			$html .= '<h4>SG</h4>
			<table width="100%" cellspacing="6" cellpadding="5" align="center" border="1" valign="top">
				<tr bgcolor="#afd9af">
				  <th>Info</th>
				  <th>Jumlah</th>
				</tr>
				<tr>
				  <td>Absent</td>
				  <td>'.$mod['sg_absent'].'</td>
				</tr>
				<tr>
				  <td>Subtitute</td>
				  <td>'.$mod['sg_subtitute'].'</td>
				</tr>
				<tr>
				  <td>Subtitute (No Beacon)</td>
				  <td>'.$mod['sg_subtitute_no_beacon'].'</td>
				</tr>
				<tr>
				  <td>Negligence</td>
				  <td>'.$mod['sg_negligence'].'</td>
				</tr>
			</table>
			<h4>HK</h4>
			<table width="100%" cellspacing="6" cellpadding="5" align="center" border="1" valign="top">
				<tr bgcolor="#afd9af">
				  <th>Info</th>
				  <th>Jumlah</th>
				</tr>
				<tr>
				  <td>Absent</td>
				  <td>'.$mod['hk_absent'].'</td>
				</tr>
				<tr>
				  <td>Subtitute</td>
				  <td>'.$mod['hk_subtitute'].'</td>
				</tr>
				<tr>
				  <td>Subtitute (No Beacon)</td>
				  <td>'.$mod['hk_subtitute_no_beacon'].'</td>
				</tr>
				<tr>
				  <td>Negligence</td>
				  <td>'.$mod['hk_negligence'].'</td>
				</tr>
			</table>';

			$html .='<h3>EVENT</h3>
			<table width="100%" cellspacing="6" cellpadding="5" align="center" border="1" valign="top">
				<tr bgcolor="#afd9af">
				  <th>Nama Event</th>
				  <th width="100">Kondisi Event (Foto)</th>
				  <th>Lantai</th>
				  <th>Status Event</th>
				</tr>';
				  
				if(!empty($events)) {
					$i = 0;
					foreach($events as $event) {
						$html .= '<tr>
						<td>'.$event['event_name'].'</td>
						<td>';
					if(!empty($event['event_img']) && @getimagesize($this->config->paths->html.'/images/event/'.$event['event_img'])) {
						$html .= '<img src="'.$this->config->general->url.'images/event/'.$event['event_img'].'" height="50px" />';
					}
					$html .= '</td>
						<td>'.$event['event_location'].'</td>
						<td>'.$event['event_status'].'</td>
					</tr>';
					$i++; } 
					}
				  $html .= '</table>';
			
			$html .= '<h4>Attachment</h4>			
			<table width="100%" cellspacing="6" cellpadding="5" align="center" border="1" valign="top">
			<tr bgcolor="#afd9af">
				<th><strong>Description</strong></th>
			</tr>';
			if(!empty($attachment)) {
				foreach($attachment as $att) {
					$html .= '<tr>
						<td><a href="'.$this->baseUrl.'/default/attachment/openattachment/c/8/f/'.$att['filename'].'">'.$att['description'].'</a></td>
					</tr>';
				}
			}
			$html .= '</table>
				  </body>
			</html>';
		
			
			$pdf=new HTML2FPDF();
			$pdf->AddPage();
			$pdf->WriteHTML($html);
			if (preg_match("/MSIE/i", $_SERVER["HTTP_USER_AGENT"])){
				header("Content-type: application/PDF");
			} else {
				header("Content-type: application/PDF");
				header("Content-Type: application/pdf");
			}
			$pdf->Output("sample2.pdf","I");
		}
	}

	public function exporttopdfAction() {
		$params = $this->_getAllParams();
		if(!empty($params['id']))
		{
			Zend_Loader::LoadClass('modClass', $this->modelDir);
			$modClass = new modClass();
			//$mod = $modClass->getReportById($params['id']);

			$params['user_id'] = $this->ident['user_id'];
			$modClass->addReadMODReportLog($params);

			$filename = $this->config->paths->html.'/pdf_report/mod/' . $this->site_id."_mod_".$params['id'].".pdf";
			if (!file_exists($filename)/* || date("Y-m-d", mktime(0, 0, 0, date("m"), date("d")-1, date("Y"))) < $mod['created_date']*/) {		
				$this->exportmodtopdf($params['id']);
			}
			// Header content type 
			header('Content-type: application/pdf'); 				
			header('Content-Disposition: inline; filename="' . $filename . '"'); 				
			// Read the file 
			readfile($filename); 
			exit();
		}
	}

	public function downloadmodreportAction() {		
		$params = $this->_getAllParams();
		if(!empty($params['id']))
		{			
			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "Export MOD Report to PDF";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);	
			
			$this->exportmodtopdf($params['id'], "", 1);
		}		
	}

	public function savepdfAction() {		
		$params = $this->_getAllParams();
		if(!empty($params['id']))
		{
			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "Export MOD Report to PDF";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);	

			$this->exportmodtopdf($params['id']);
		}		
	}
	
	function addattachmentAction()
	{
		$params = $this->_getAllParams();
		
		Zend_Loader::LoadClass('modClass', $this->modelDir);
		$modClass = new modClass();
		
		$attachment_id = $modClass->addAttachment($params);
		
		if(!empty($_FILES["attachment_file"]))
		{
			$ext = explode(".",$_FILES["attachment_file"]['name']);
			$filename = $attachment_id.".".$ext[count($ext)-1];
			$datafolder = $this->config->paths->html."/attachment/mod/";
			if(move_uploaded_file($_FILES["attachment_file"]["tmp_name"], $datafolder.$filename))
			{
				$modClass->updateAttachment($attachment_id,'filename', $filename);
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
		$logData['action'] = "Add Attachment to MOD Report";
		$logData['data'] = json_encode($params);
		$logsTable->insertLogs($logData);	
		
		$this->_response->setRedirect($this->baseUrl.'/default/mod/page3/id/'.$params['report_id']);
		$this->_response->sendResponse();
		exit();
	
	}
	
	public function getattachmentbyidAction() {
		$params = $this->_getAllParams();
		Zend_Loader::LoadClass('modClass', $this->modelDir);
		$modClass = new modClass();
		
		if(!empty($params['id'])) 
		{
			echo json_encode($modClass->getAttachmentById($params['id']));
		}
	}
	
	public function deleteattachmentbyidAction() {
		$params = $this->_getAllParams();
		Zend_Loader::LoadClass('modClass', $this->modelDir);
		$modClass = new modClass();
		
		$modClass->deleteAttachmentById($params['id']);

		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "Delete MOD Report Attachment";
		$logData['data'] = json_encode($params);
		$logsTable->insertLogs($logData);	
		
		$this->_response->setRedirect($this->baseUrl.'/default/mod/page3/id/'.$params['om_report_id']);
		$this->_response->sendResponse();
		exit();
	}
	
	function addeventAction()
	{
		$params = $this->_getAllParams();
		
		Zend_Loader::LoadClass('modClass', $this->modelDir);
		$modClass = new modClass();
		
		$params['mod_report_id'] = $params['report_id'];
		$event_id = $modClass->addEvent($params);
		
		if(!empty($_FILES["event_image"]))
		{
			$ext = explode(".",$_FILES["event_image"]['name']);
			$filename = $event_id."_mod.".$ext[count($ext)-1];
			$datafolder = $this->config->paths->html."/images/event/";
			if(move_uploaded_file($_FILES["event_image"]["tmp_name"], $datafolder.$filename))
			{
				$modClass->updateEventFileName($event_id,'event_img', $filename);
				$magickPath = "/usr/bin/convert";
				$new_file_thumb = $event_id."_mod_thumb.".$ext[count($ext)-1];
				/*** create thumbnail image ***/
				exec($magickPath . ' ' . $datafolder.$filename . ' -resize 128x128 ' . $datafolder.$new_file_thumb);
				/*** resize image if size greater than 500 Kb ***/
				if(filesize($datafolder.$filename) > 500000) exec($magickPath . ' ' . $datafolder.$filename . ' -resize 800x800\> ' . $datafolder.$filename);
			}
		}

		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "Add Event to MOD Report";
		$logData['data'] = json_encode($params);
		$logsTable->insertLogs($logData);	
		
		$this->_response->setRedirect($this->baseUrl.'/default/mod/page3/id/'.$params['report_id']);
		$this->_response->sendResponse();
		exit();
	
	}
	
	public function geteventbyidAction() {
		$params = $this->_getAllParams();
		Zend_Loader::LoadClass('modClass', $this->modelDir);
		$modClass = new modClass();
		
		if(!empty($params['id'])) 
		{
			echo json_encode($modClass->getEventById($params['id']));
		}
	}
	
	public function deleteeventbyidAction() {
		$params = $this->_getAllParams();
		Zend_Loader::LoadClass('modClass', $this->modelDir);
		$modClass = new modClass();
		
		$modClass->deleteEventById($params['id']);

		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "Delete Event from MOD Report";
		$logData['data'] = json_encode($params);
		$logsTable->insertLogs($logData);	
		
		$this->_response->setRedirect($this->baseUrl.'/default/mod/page3/id/'.$params['mod_report_id']);
		$this->_response->sendResponse();
		exit();
	}

	public function scheduleAction() {
		Zend_Loader::LoadClass('userClass', $this->modelDir);
		$userClass = new userClass();

		$this->view->modUsers = $userClass->getUsersByRole(10);

		Zend_Loader::LoadClass('modscheduleClass', $this->modelDir);
		$modscheduleClass = new modscheduleClass();

		$schedules = $modscheduleClass->getSchedules();
		foreach($schedules as &$schedule)
		{
			$schedule['date'] = date("j M Y", strtotime($schedule['schedule_date']));
		}
		$this->view->schedules = $schedules;

		$this->renderTemplate('mod_schedule.tpl'); 
	}

	public function savescheduleAction() {
		$params = $this->_getAllParams();
		Zend_Loader::LoadClass('modscheduleClass', $this->modelDir);
		$modscheduleClass = new modscheduleClass();

		$params['added_by'] = $this->ident['user_id'];
		if(!empty($params['schedule_id']))  $modscheduleClass->updateSchedule($params);
		else $modscheduleClass->addSchedule($params);
		
		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "Add User to MOD Schedule";
		$logData['data'] = json_encode($params);
		$logsTable->insertLogs($logData);

	}

	public function getschedulebyidAction() {
		$params = $this->_getAllParams();
		Zend_Loader::LoadClass('modscheduleClass', $this->modelDir);
		$modscheduleClass = new modscheduleClass();
		
		if(!empty($params['id'])) 
		{
			echo json_encode($modscheduleClass->getScheduleById($params['id']));
		}
	}

	public function deleteschedulebyidAction() {
		$params = $this->_getAllParams();
		Zend_Loader::LoadClass('modscheduleClass', $this->modelDir);
		$modscheduleClass = new modscheduleClass();
		
		$modscheduleClass->deleteScheduleById($params['id']);

		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "Delete User from MOD Schedule";
		$logData['data'] = json_encode($params);
		$logsTable->insertLogs($logData);	
		
		$this->_response->setRedirect($this->baseUrl.'/default/mod/schedule');
		$this->_response->sendResponse();
		exit();
	}
	
	public function schedulereportAction() {
		$params = $this->_getAllParams();

		if(!empty($params['month'])) $selectedMonth = $params['month'];
		else $selectedMonth = date("m");
		if(!empty($params['year'])) $selectedYear = $params['year'];
		else $selectedYear = date("Y");

		Zend_Loader::LoadClass('userClass', $this->modelDir);
		$userClass = new userClass();

		$modUsers = $userClass->getUsersByRole(10);

		
		Zend_Loader::LoadClass('modscheduleClass', $this->modelDir);
		$modscheduleClass = new modscheduleClass();

		if(!empty($modUsers))
		{
			foreach($modUsers as &$user) {
				$userSchedule = $modscheduleClass->getScheduleByUser($selectedMonth, $selectedYear, $user['user_id']);
				if(!empty($userSchedule))
				{
					foreach($userSchedule as $us) {
						$usd = explode("-",$us['schedule_date']);
						$user[ $usd[2]] = "v";
					}
				}
				
			}
		}
		$this->view->modUsers = $modUsers;

		$schedulesDate = $modscheduleClass->getScheduleByMonth($selectedMonth, $selectedYear);
		$i=0;
		if(!empty($schedulesDate))
		{
			foreach($schedulesDate as $date) {
				$dt = explode("-",$date['schedule_date']);
				$dateList[$i] = $dt[2];
				$i++;
			}
		}

		$this->view->dateList = $dateList;

		$this->view->selectedMonth = $selectedMonth;
		$this->view->selectedYear =$selectedYear;

		$this->view->selectedMonthYear = date("F Y", mktime(0, 0, 0, $selectedMonth, 1, $selectedYear));
		
		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "Open MOD Schedule Report";
		$logData['data'] = json_encode($params);
		$logsTable->insertLogs($logData);

		$this->renderTemplate('mod_schedule_report.tpl'); 
	}

	function exportscheduletopdfAction() {
		$params = $this->_getAllParams();

		$month = $params['m'];
		$year = $params['y'];

		$selectedMonthYear = date("F Y", mktime(0, 0, 0, $month, 1, $year));

		Zend_Loader::LoadClass('userClass', $this->modelDir);
		$userClass = new userClass();

		$modUsers = $userClass->getUsersByRole(10);
		
		Zend_Loader::LoadClass('modscheduleClass', $this->modelDir);
		$modscheduleClass = new modscheduleClass();

		if(!empty($modUsers))
		{
			foreach($modUsers as &$user) {
				$userSchedule = $modscheduleClass->getScheduleByUser($month, $year, $user['user_id']);
				if(!empty($userSchedule))
				{
					foreach($userSchedule as $us) {
						$usd = explode("-",$us['schedule_date']);
						$user[$usd[2]] = "v";
					}
				}			
			}
		}

		$schedulesDate = $modscheduleClass->getScheduleByMonth($month, $year);
		$i=0;
		if(!empty($schedulesDate))
		{
			foreach($schedulesDate as $date) {
				$dt = explode("-",$date['schedule_date']);
				$dateList[$i] = $dt[2];
				$i++;
			}
		}


		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "Export MOD Schedule to PDF";
		$logData['data'] = json_encode($params);
		$logsTable->insertLogs($logData);	

		require('fpdf/fpdf.php');

		$pdf = new FPDF();
		$pdf->AddPage('L');
		$pdf->SetFont('Arial','B',15);
		$pdf->Cell(275,10,$this->ident['initial'].' - MOD Schedule Report - '.$selectedMonthYear,0,0,'C');
		$pdf->Ln(14);
		
		// Color and font restoration
		$pdf->SetFillColor(9,41,102);
		$pdf->SetTextColor(255);
		$pdf->SetFont('Arial','B',10);
		// Header
		$pdf->Cell((275-(count($dateList)*10)),10,'Name',1,0,'C',true);
		if(!empty($dateList))
		{
			foreach($dateList as $dl) {
				$pdf->Cell(10,10,$dl,1,0,'C',true);
			}
		}
		$pdf->Ln();
		if(!empty($modUsers))
		{
			foreach($modUsers as $u) { 				
				$pdf->SetTextColor(0);
				$pdf->Cell((275-(count($dateList)*10)),10,$u['name'],1,0,'L',false);
				$pdf->SetTextColor(255,0,0);
				if(!empty($dateList))
				{
					foreach($dateList as $dl) {
						$pdf->Cell(10,10,$u[$dl],1,0,'C',false);
					}
				}
				$pdf->Ln();	
			}
		}
	
		$pdf->Output();
	}
}

?>