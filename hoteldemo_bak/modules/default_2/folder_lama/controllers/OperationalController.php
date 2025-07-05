<?php
require_once('actionControllerBase.php');

class OperationalController extends actionControllerBase
{
	public function dashboardAction() {
		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "View Site Manager Dashboard";
		$logData['data'] = "View Site Manager Dashboard";
		$logsTable->insertLogs($logData);	

		$this->renderTemplate('site_manager_dashboard.tpl'); 
	}

	public function addAction() {
		$this->view->ident = $this->ident;
		$this->view->title = "Add Operational Mall Report";
		
		$now = date("Y-m-d");
		
		Zend_Loader::LoadClass('issueClass', $this->modelDir);
		$issueClass = new issueClass();
		//$this->view->utilitySpecificReport = $issueClass->getIssueByCategoryDateAndCatId($now, '4');
		$this->view->utilitySpecificReport = $issueClass->getUnsolvedIssueByCategoryId(0, '10');
		
		Zend_Loader::LoadClass('securityClass', $this->modelDir);
		$securityClass = new securityClass();
		/*$securityReport = $securityClass->getChiefSecurityReportByDate($now);
		if(!empty($securityReport)) $this->view->securitySpecificReport = $securityClass->getSpecificReportByChiefSecurityReport($securityReport['chief_security_report_id']);*/
		//$this->view->securitySpecificReport = $securityClass->getUnsolvedIssuesByChiefSecurityReport('0', $now);
		$securitySpecificReport = $securityClass->getOMUnsolvedSpecificReports('0', $now);
		$security_issues = $issueClass->getOMOpenedIssues($now, 1);
		$this->view->securitySpecificReport =  array_merge($securitySpecificReport, $security_issues);

		Zend_Loader::LoadClass('safetyClass', $this->modelDir);
		$safetyClass = new safetyClass();
		/*$safetyReport = $safetyClass->getSafetyReportByDate($now);
		if(!empty($safetyReport)) $this->view->safetySpecificReport = $safetyClass->getSpecificReportById($safetyReport['report_id']);*/
		//$this->view->safetySpecificReport = $safetyClass->getUnsolvedIssues('0', $now);
		$safetySpecificReport = $safetyClass->getOMUnsolvedSpecificReports('0', $now);
		$safety_issues = $issueClass->getOMOpenedIssues($now, 3);
		$this->view->safetySpecificReport =  array_merge($safetySpecificReport, $safety_issues);

		/*Zend_Loader::LoadClass('housekeepingClass', $this->modelDir);
		$housekeepingClass = new housekeepingClass();		
		$housekeepingReport = $housekeepingClass->getReportByDate($now);
		if(!empty($housekeepingReport))
		{
			$this->view->hk_progress_report_shift12 = $progressreportTable->getHousekeepingProgressReport($housekeepingReport['housekeeping_report_id'], '12');
			$this->view->hk_progress_report_shift3 = $progressreportTable->getHousekeepingProgressReport($housekeepingReport['housekeeping_report_id'], '3');
			$this->view->hk_other_info = $progressreportTable->getHousekeepingOtherInfo($housekeepingReport['housekeeping_report_id']);
		}*/
		$progressreportTable = $this->loadModel('progressreport');
		$this->view->hk_progress_report_shift = $progressreportTable->getUnsolvedHousekeepingProgressReport('0');
		$this->view->hk_other_info = $progressreportTable->getUnsolvedHousekeepingOtherInfo('0');
		$this->view->hk_issues = $issueClass->getOMOpenedIssues($now, 2);
		
		Zend_Loader::LoadClass('parkingClass', $this->modelDir);
		$parkingClass = new parkingClass();
		/*$parkingReport = $parkingClass->getReportByDate($now);
		if(!empty($parkingReport)) $this->view->parkingSpecificReport = $parkingClass->getSpecificReportById($parkingReport['parking_report_id']);*/
		//$this->view->parkingSpecificReport = $parkingClass->getUnsolvedIssues('0', $now);		
		$parkingSpecificReport = $parkingClass->getOMUnsolvedSpecificReports('0', $now);
		$parking_issues = $issueClass->getOMOpenedIssues($now, 5);
		$this->view->parkingSpecificReport =  array_merge($parkingSpecificReport, $parking_issues);

		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "Add OM Daily Report";
		$logData['data'] = "Opening the form page";
		$logsTable->insertLogs($logData);	

		$this->renderTemplate('form_daily_operational_mall.tpl'); 
	}
	
	public function saveissuesAction() {
		set_time_limit(7200);
		$params = $this->_getAllParams();
		
		Zend_Loader::LoadClass('operationalClass', $this->modelDir);
		$operationalClass = new operationalClass();
		
		$report = $operationalClass->getReportByDate(date("Y-m-d"));
		if(empty($params['operation_mall_report_id']) && !empty($report))
		{
			$this->view->title = "Add Operational Mall Report";
			$this->view->message="Report is already exist";
			$this->view->operational = $params; 
			
			$this->renderTemplate('form_daily_operational_mall.tpl'); 
			exit();
		}
		
		
		if(empty($params['operation_mall_report_id'])) 
		{
			$params['user_id'] = $this->ident['user_id'];
			$operational['operation_mall_report_id'] = $params['operation_mall_report_id'] = $operationalClass->saveReport($params);
		}
		else {
			$operational = $operationalClass->getReportById($params['operation_mall_report_id']);
			$datetime = explode(" ",$operational['created_date']);

			$date = explode("-",$datetime[0]);
			$r_date = mktime(0, 0, 0, intval($date[1]), intval($date[2]), intval($date[0]));
			$operational['report_date'] = date("l, j F Y", $r_date);	
		}
		$this->view->operational =  $operational;
		
		if(!empty($params['utility_specific_report_id']))
		{
			$u=0;
			Zend_Loader::LoadClass('issueClass', $this->modelDir);
			$issueClass = new issueClass();
			foreach($params['utility_specific_report_id'] as $utility_specific_report_id) {
				if(!empty($params['utility_specific_report_completion_date'][$u]) && $params['utility_specific_report_completion_date'][$u] != "0000-00-00 00:00:00")
					$issueClass->updateSolvedDate($utility_specific_report_id, $params['utility_specific_report_completion_date'][$u], $params['operation_mall_report_id'], "om_report_id");
				elseif(empty($params['utility_specific_report_completion_date'][$u]) || $params['utility_specific_report_completion_date'][$u] == "0000-00-00 00:00:00")
					$issueClass->updateSolvedDate($utility_specific_report_id, "0000-00-00", "0", "om_report_id");
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
					$safetyClass->updateSpecificReportCompletionDate($safety_specific_report_id, $params['safety_specific_report_completion_date'][$sa], $params['operation_mall_report_id'], "om_report_id");
				elseif(empty($params['safety_specific_report_completion_date'][$sa]) || $params['safety_specific_report_completion_date'][$sa] == "0000-00-00 00:00:00")
					$safetyClass->updateSpecificReportCompletionDate($safety_specific_report_id, "0000-00-00", "0", "om_report_id");
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
					$securityClass->updateSpecificReportCompletionDate($security_specific_report_id, $params['security_specific_report_completion_date'][$se], $params['operation_mall_report_id'], "om_report_id");
				elseif(empty($params['security_specific_report_completion_date'][$se]) || $params['security_specific_report_completion_date'][$se] == "0000-00-00 00:00:00")
					$securityClass->updateSpecificReportCompletionDate($security_specific_report_id, "0000-00-00", "0", "om_report_id");
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
					$housekeepingClass->updateProgressReportCompletionDate($hk_progress_report_shift_id, $params['hk_progress_report_shift_completion_date'][$hk12], $params['operation_mall_report_id'], "om_report_id");
				elseif(empty($params['hk_progress_report_shift_completion_date'][$hk12]) || $params['hk_progress_report_shift_completion_date'][$hk12] == "0000-00-00 00:00:00")
					$housekeepingClass->updateProgressReportCompletionDate($hk_progress_report_shift_id, "0000-00-00", "0", "om_report_id");
				$hk12++;
			}
		}
		
		if(!empty($params['hk_other_info_id']))
		{
			$hoi=0;
			foreach($params['hk_other_info_id'] as $hk_other_info_id) {
				if(!empty($params['hk_other_info_completion_date'][$hoi]) && $params['hk_other_info_completion_date'][$hoi] != "0000-00-00 00:00:00")
					$housekeepingClass->updateOtherInfoCompletionDate($hk_other_info_id, $params['hk_other_info_completion_date'][$hoi], $params['operation_mall_report_id'], "om_report_id");
				elseif(empty($params['hk_other_info_completion_date'][$hoi]) || $params['hk_other_info_completion_date'][$hoi] == "0000-00-00 00:00:00")
					$housekeepingClass->updateOtherInfoCompletionDate($hk_other_info_id, "0000-00-00", "0", "om_report_id");
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
					$parkingClass->updateSpecificReportCompletionDate($parking_specific_report_id, $params['parking_specific_report_completion_date'][$p], $params['operation_mall_report_id'], "om_report_id");
				elseif(empty($params['parking_specific_report_completion_date'][$p]) || $params['parking_specific_report_completion_date'][$p] == "0000-00-00 00:00:00")
					$parkingClass->updateSpecificReportCompletionDate($parking_specific_report_id, "0000-00-00", "0", "om_report_id");
				$p++;
			}
		}
		
		//$this->exporttopdf($operational['operation_mall_report_id']);

		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "Save OM Daily Report - Page 1";
		$logData['data'] = json_encode($params);
		$logsTable->insertLogs($logData);	
		
		$this->_response->setRedirect($this->baseUrl.'/default/operational/page2/id/'.$operational['operation_mall_report_id']);
		$this->_response->sendResponse();
		exit();
		
	}
	
	public function page2Action() {
		$params = $this->_getAllParams();
		
		Zend_Loader::LoadClass('operationalClass', $this->modelDir);
		$operationalClass = new operationalClass();
		
		$operational = $operationalClass->getReportById($params['id']);
		$datetime = explode(" ",$operational['created_date']);

		$date = explode("-",$datetime[0]);
		$r_date = mktime(0, 0, 0, intval($date[1]), intval($date[2]), intval($date[0]));
		$operational['report_date'] = date("l, j F Y", $r_date);	

		Zend_Loader::LoadClass('parkingClass', $this->modelDir);
		$parkingClass = new parkingClass();
		$parkingReport = $parkingClass->getReportByDate($datetime[0]);
		$operational['car_parking'] = $parkingReport['inhouse_carcount_mobil'];
		$operational['car_drop_off'] = $parkingReport['inhouse_carcount_drop_off'];
		$operational['valet_parking'] = $parkingReport['inhouse_carcount_valet_reg'];
		$operational['box_vehicle'] = $parkingReport['inhouse_carcount_box'];
		$operational['taxi_bluebird'] = $parkingReport['inhouse_carcount_taxi'];
		$operational['motorbike'] = $parkingReport['inhouse_carcount_motor'];
		
		$this->view->operational = $operational;
		
		$this->view->marketing_promotion = $operationalClass->getEvents($params['id']);
		
		$this->view->attachment = $operationalClass->getAttachments($params['id']);

		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "Open OM Daily Report - Page 2";
		$logData['data'] = json_encode($params);
		$logsTable->insertLogs($logData);	

		$this->renderTemplate('form_daily_operational_mall2.tpl'); 
	}
	
	public function savereportAction() {
		set_time_limit(7200);
		$params = $this->_getAllParams();
		
		$params['user_id'] = $this->ident['user_id'];
		Zend_Loader::LoadClass('operationalClass', $this->modelDir);
		$operationalClass = new operationalClass();
		
		/*$report = $operationalClass->getReportByDate(date("Y-m-d"));
		if(empty($params['operation_mall_report_id']) && !empty($report))
		{
			$this->view->title = "Add Operational Mall Report";
			$this->view->message="Report is already exist";
			$this->view->operational = $params; 
			
			$this->renderTemplate('form_daily_operational_mall.tpl'); 
			exit();
		}*/
		
		$params['operation_mall_report_id'] = $operationalClass->saveReport($params);
		
		/*if(!empty($params['event_name']))
		{		
			$m = 0;
			$dt5=array();
			foreach($params['event_name'] as $event_name)
			{
				if($params['event_id'][$m] > 0)
				{
					$existingProgressReport = array();
					$dt5[$m] = $operationalClass->getEventById($params['event_id'][$m]);
					if(!empty($event_name)) $dt5[$m]['event_name'] = $event_name;
					if(!empty($params['event_location'][$m])) $dt5[$m]['event_location'] = $params['event_location'][$m];
					if(!empty($params['event_condition'][$m])) $dt5[$m]['event_condition'] = $params['event_condition'][$m];
					if(!empty($params['event_period'][$m])) $dt5[$m]['event_period'] = $params['event_period'][$m];
				}
				else
				{
					$dt5[$m]['operation_mall_report_id'] = $params['operation_mall_report_id'];
					$dt5[$m]['event_name'] = $event_name;
					$dt5[$m]['event_location'] = $params['event_location'][$m];
					$dt5[$m]['event_condition'] = $params['event_condition'][$m];
					$dt5[$m]['event_period'] = $params['event_period'][$m];
					
				}	
				if(!empty($_FILES["event_img"]['name'][$m]))
				{
					$dt5[$m]['event_img_upload'] = $_FILES["event_img"];
				}
				$m++;
			}			
			$operationalClass->deleteEventsByReportId($params['operation_mall_report_id']);
			$m=0;
			foreach($dt5 as $dt)
			{
				$event_img = $dt['event_img_upload'];
				unset($dt['event_img_upload']);
				$event_id = $operationalClass->addEvent($dt);
				if($event_id > 0)
				{
					if(!empty($event_img['name'][$m]))
					{
						$ext = explode(".",$event_img['name'][$m]);
						$filename = $event_id."_om.".$ext[count($ext)-1];
						if(move_uploaded_file($event_img["tmp_name"][$m], $this->config->paths->html."/images/event/".$filename))
							$operationalClass->updateEventFileName($event_id,'event_img', $filename);
					}
				}
				$m++;
			}			
		}
		
		if(!empty($params['attachment-description']))
		{		
			$m = 0;
			$dt3=array();
			foreach($params['attachment-description'] as $description)
			{
				if($params['attachment_id'][$m] > 0)
				{
					$existingAttachment = array();
					$dt3[$m] = $operationalClass->getAttachmentById($params['attachment_id'][$m]);
					if(!empty($description)) $dt3[$m]['description'] = $description;
				}
				else
				{
					$dt3[$m]['site_id'] = $this->site_id;
					$dt3[$m]['report_id'] = $params['operation_mall_report_id'];
					$dt3[$m]['description'] = $description;
					
				}	
				if(!empty($_FILES["attachment_file"]['name'][$m]))
				{	
					$dt3[$m]['attachment'] = $_FILES["attachment_file"];
				}
				$m++;
			}			
			$operationalClass->deleteAttachmentByReportId($params['operation_mall_report_id']);
			$m=0;
			foreach($dt3 as $dt)
			{
				$attachment = $dt['attachment'];
				unset($dt['attachment']);
				$attachment_id = $operationalClass->addAttachment($dt);
				if($attachment_id > 0)
				{
					if(!empty($attachment['name'][$m]))
					{
						$ext = explode(".",$attachment['name'][$m]);
						$filename = $attachment_id.".".$ext[count($ext)-1];
						if(move_uploaded_file($attachment["tmp_name"][$m], $this->config->paths->html."/attachment/operational/".$filename))
							$operationalClass->updateAttachment($attachment_id,'filename', $filename);
					}
				}
				$m++;
			}			
		}*/

		//$this->exporttopdf($params['operation_mall_report_id']);

		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "Save OM Daily Report - Page 2";
		$logData['data'] = json_encode($params);
		$logsTable->insertLogs($logData);	
		
		$this->_response->setRedirect($this->baseUrl.'/default/operational/viewreport');
		$this->_response->sendResponse();
		exit();
	}
	
	public function viewreportAction() {
		$params = $this->_getAllParams();
		Zend_Loader::LoadClass('operationalClass', $this->modelDir);
		$operationalClass = new operationalClass();
		Zend_Loader::LoadClass('operationalcommentsClass', $this->modelDir);
		$operationalcommentsClass = new operationalcommentsClass();
		if(empty($params['start'])) $params['start'] = '0';
		$params['pagesize'] = 10;
		$this->view->start = $params['start'];
		$operational = $operationalClass->getReports($params);
		foreach($operational as &$op)
		{
			$date = explode(" ", $op['created_date']);
			if($op['created_date'] >= date("Y-m-d", mktime(0, 0, 0, date("m"), date("d")-1,   date("Y")))) $op['allowEdit'] = 1;
			else $op['allowEdit'] = 0;
			$arr_date = explode("-",$date[0]);
			$op['day_date'] = date("l, j F Y", mktime(0, 0, 0, $arr_date[1], $arr_date[2], $arr_date[0]));
			$op['comments'] = $operationalcommentsClass->getCommentsByOperationalMallReportId($op['operation_mall_report_id'], '3');
		}
		$this->view->operational = $operational;
		
		
		$totalReport = $operationalClass->getTotalReport();
		if($totalReport['total'] > 10)
		{
			if($params['start'] >= 10)
			{
				$this->view->firstPageUrl = "/default/operational/viewreport";
				$this->view->prevUrl = "/default/operational/viewreport/start/".($params['start']-$params['pagesize']);
			}
			if($params['start'] < (floor(($totalReport['total']-1)/10)*10))
			{
				$this->view->nextUrl = "/default/operational/viewreport/start/".($params['start']+$params['pagesize']);
				$this->view->lastPageUrl = "/default/operational/viewreport/start/".(floor(($totalReport['total']-1)/10)*10);
			}
		}
		$this->view->curPage = ($params['start']/$params['pagesize'])+1;
		$this->view->totalPage = ceil($totalReport['total']/$params['pagesize']);
		$this->view->startRec = $params['start'] + 1;
		$endRec = $params['start'] + $params['pagesize'];
		if($totalReport['total'] >=  $endRec) $this->view->endRec =  $endRec;
		else $this->view->endRec =  $totalReport['total'];		
		$this->view->totalRec = $totalReport['total'];

		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "View OM Daily Report List";
		$logData['data'] = json_encode($params);
		$logsTable->insertLogs($logData);	
		
    	$this->renderTemplate('view_daily_operational.tpl');  
	}
	
	public function editAction() {
		$params = $this->_getAllParams();
		Zend_Loader::LoadClass('operationalClass', $this->modelDir);
		$operationalClass = new operationalClass();
		
		if(!empty($params['id'])) 
		{
			$operational = $operationalClass->getReportById($params['id']);
			
			$datetime = explode(" ",$operational['created_date']);
			/*if($datetime[0] != date("Y-m-d")) 
			{
				$this->_response->setRedirect($this->baseUrl.'/default/operational/viewreport');
				$this->_response->sendResponse();
				exit();
			}*/
			$date = explode("-",$datetime[0]);
			$r_date = mktime(0, 0, 0, intval($date[1]), intval($date[2]), intval($date[0]));
			$operational['report_date'] = date("l, j F Y", $r_date);	
			$this->view->operational = $operational;
			
			$report_date = $datetime[0];
		
			Zend_Loader::LoadClass('issueClass', $this->modelDir);
			$issueClass = new issueClass();
			$this->view->utilitySpecificReport = $issueClass->getUnsolvedIssueByCategoryId($params['id'],'10');
			
			Zend_Loader::LoadClass('securityClass', $this->modelDir);
			$securityClass = new securityClass();
			/*$securityReport = $securityClass->getChiefSecurityReportByDate($report_date);
			if(!empty($securityReport)) $this->view->securitySpecificReport = $securityClass->getSpecificReportByChiefSecurityReport($securityReport['chief_security_report_id']);*/
			//$this->view->securitySpecificReport = $securityClass->getUnsolvedIssuesByChiefSecurityReport($params['id'], $datetime[0]);
			$securitySpecificReport = $securityClass->getOMUnsolvedSpecificReports($params['id'], $datetime[0]);
			$security_issues = $issueClass->getOMOpenedIssues($datetime[0], 1);
			$this->view->securitySpecificReport =  array_merge($securitySpecificReport, $security_issues);
			
			Zend_Loader::LoadClass('safetyClass', $this->modelDir);
			$safetyClass = new safetyClass();
			/*$safetyReport = $safetyClass->getSafetyReportByDate($report_date);
			if(!empty($safetyReport)) $this->view->safetySpecificReport = $safetyClass->getSpecificReportById($safetyReport['report_id']);*/
			//$this->view->safetySpecificReport = $safetyClass->getUnsolvedIssues($params['id'], $datetime[0]);
			$safetySpecificReport = $safetyClass->getOMUnsolvedSpecificReports($params['id'], $datetime[0]);
			$safety_issues = $issueClass->getOMOpenedIssues($datetime[0], 3);
			$this->view->safetySpecificReport =  array_merge($safetySpecificReport, $safety_issues);
			
			/*Zend_Loader::LoadClass('housekeepingClass', $this->modelDir);
			$housekeepingClass = new housekeepingClass();		
			$housekeepingReport = $housekeepingClass->getReportByDate($report_date);
			if(!empty($housekeepingReport))
			{
				$progressreportTable = $this->loadModel('progressreport');
				$this->view->hk_progress_report_shift12 = $progressreportTable->getHousekeepingProgressReport($housekeepingReport['housekeeping_report_id'], '12');
				$this->view->hk_progress_report_shift3 = $progressreportTable->getHousekeepingProgressReport($housekeepingReport['housekeeping_report_id'], '3');
				$this->view->hk_other_info = $progressreportTable->getHousekeepingOtherInfo($housekeepingReport['housekeeping_report_id']);
			}*/
			
			$progressreportTable = $this->loadModel('progressreport');
			$this->view->hk_progress_report_shift = $progressreportTable->getUnsolvedHousekeepingProgressReport($params['id']);
			$this->view->hk_other_info = $progressreportTable->getUnsolvedHousekeepingOtherInfo($params['id']);
			$this->view->hk_issues = $issueClass->getOMOpenedIssues($datetime[0], 2);

			Zend_Loader::LoadClass('parkingClass', $this->modelDir);
			$parkingClass = new parkingClass();
			/*$parkingReport = $parkingClass->getReportByDate($report_date);
			if(!empty($parkingReport)) $this->view->parkingSpecificReport = $parkingClass->getSpecificReportById($parkingReport['parking_report_id']);*/
			//$this->view->parkingSpecificReport = $parkingClass->getUnsolvedIssues($params['id'], $datetime[0]);
			$parkingSpecificReport = $parkingClass->getOMUnsolvedSpecificReports($params['id'], $datetime[0]);
			$parking_issues = $issueClass->getOMOpenedIssues($datetime[0], 5);
			$this->view->parkingSpecificReport =  array_merge($parkingSpecificReport, $parking_issues);
		}
		
		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "Edit OM Daily Report";
		$logData['data'] = json_encode($params);
		$logsTable->insertLogs($logData);	

		$this->view->title = "Edit Operational Mall Report";
		$this->view->editMode = 1;
		$this->renderTemplate('form_daily_operational_mall.tpl');  
	}

	public function viewdetailreportAction() {
		if($this->showOM == 1)
		{
			$params = $this->_getAllParams();

			Zend_Loader::LoadClass('operationalClass', $this->modelDir);
			$operationalClass = new operationalClass();

			if(!empty($params['id'])) 
			{
				if($this->showSiteSelection == 1 && !empty($params['s']))
				{
					$siteTable = $this->loadModel('site');
					if($params['s'] != $this->ident['site_id'])
					{
						$siteTable->setSite($params['s']);
						$this->ident['site_id'] = $params['s'];
						$this->_response->setRedirect($this->baseUrl."/default/operational/viewdetailreport/id/".$params['id']);
						$this->_response->sendResponse();
						exit();
					}
				}

				$params['user_id'] = $this->ident['user_id'];
				$operationalClass->addReadOMReportLog($params);
				
				$operational = $operationalClass->getReportById($params['id']);
				
				$datetime = explode(" ",$operational['created_date']);

				$date = explode("-",$datetime[0]);
				$r_date = mktime(0, 0, 0, intval($date[1]), intval($date[2]), intval($date[0]));
				$operational['report_date'] = date("l, j F Y", $r_date);	
				
				$report_date = $datetime[0];
				
				Zend_Loader::LoadClass('parkingClass', $this->modelDir);
				$parkingClass = new parkingClass();
				$parkingReport = $parkingClass->getReportByDate($datetime[0]);
				$operational['car_parking'] = $parkingReport['inhouse_carcount_mobil'];
				$operational['car_drop_off'] = $parkingReport['inhouse_carcount_drop_off'];
				$operational['valet_parking'] = $parkingReport['inhouse_carcount_valet_reg'];
				$operational['box_vehicle'] = $parkingReport['inhouse_carcount_box'];
				$operational['taxi_bluebird'] = $parkingReport['inhouse_carcount_taxi'];
				$operational['motorbike'] = $parkingReport['inhouse_carcount_motor'];

				$this->view->operational = $operational;

				Zend_Loader::LoadClass('issueClass', $this->modelDir);
				$issueClass = new issueClass();
				$this->view->utilitySpecificReport = $issueClass->getIssueByReportAndCatId('om_report_id', $params['id'], '10', $report_date);
				
				Zend_Loader::LoadClass('securityClass', $this->modelDir);
				$securityClass = new securityClass();
				//$securitySpecificReport = $securityClass->getSpecificReportByReport('om_report_id', $params['id'], $report_date);
				$security_solved_specific_report = $securityClass->getOMSolvedSpecificReports($params['id'], $report_date);
				$security_issues = $issueClass->getOMClosedIssues($report_date, 1);
				$this->view->securitySpecificReport = array_merge($security_solved_specific_report, $security_issues);
				
				Zend_Loader::LoadClass('safetyClass', $this->modelDir);
				$safetyClass = new safetyClass();
				//$safetySpecificReport = $safetyClass->getSpecificReportByReport('om_report_id', $params['id'], $report_date);
				$safety_solved_specific_report = $safetyClass->getOMSolvedSpecificReports($params['id'], $report_date);
				$safety_issues = $issueClass->getOMClosedIssues($report_date, 3);
				$this->view->safetySpecificReport = array_merge($safety_solved_specific_report, $safety_issues);
				
				Zend_Loader::LoadClass('housekeepingClass', $this->modelDir);
				$housekeepingClass = new housekeepingClass();		
				$progressreportTable = $this->loadModel('progressreport');
				$this->view->hk_progress_report_shift = $progressreportTable->getHousekeepingProgressReportByReport('om_report_id', $params['id']);
				$this->view->hk_other_info = $progressreportTable->getHousekeepingOtherInfoByReport('om_report_id', $params['id']);
				$this->view->hk_issues = $issueClass->getOMClosedIssues($report_date, 2);
				
				Zend_Loader::LoadClass('parkingClass', $this->modelDir);
				$parkingClass = new parkingClass();
				//$parkingSpecificReport = $parkingClass->getSpecificReportByReport('om_report_id', $params['id']);
				$parking_solved_specific_report = $parkingClass->getOMSolvedSpecificReports($params['id'], $report_date);
				$parking_issues = $issueClass->getOMClosedIssues($report_date, 5);
				$this->view->parkingSpecificReport = array_merge($parking_solved_specific_report, $parking_issues);

				$this->view->marketing_promotion = $operationalClass->getEvents($params['id']);
				
				$this->view->attachment = $operationalClass->getAttachments($params['id']);

				$this->view->ident = $this->ident;

				$operationalcommentsClass = $this->loadModel('operationalcomments');
				$this->view->comments = $operationalcommentsClass->getCommentsByOperationalMallReportId($params['id'], 0, 'asc');

				$logsTable = $this->loadModel('logs');
				$logData['user_id'] = intval($this->ident['user_id']);
				$logData['action'] = "View Detail OM Daily Report";
				$logData['data'] = json_encode($params);
				$logsTable->insertLogs($logData);	
			
				$this->renderTemplate('view_om_detail_report.tpl');  
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
		Zend_Loader::LoadClass('operationalClass', $this->modelDir);
		$operationalClass = new operationalClass();
		
		if(!empty($params['id'])) 
		{
			$operational = $operationalClass->getReportById($params['id']);
			
			$datetime = explode(" ",$operational['created_date']);
			$date = explode("-",$datetime[0]);
			$r_date = mktime(0, 0, 0, intval($date[1]), intval($date[2]), intval($date[0]));
			$operational['report_date'] = date("l, j F Y", $r_date);	
			
			$report_date = $datetime[0];
			
			Zend_Loader::LoadClass('parkingClass', $this->modelDir);
			$parkingClass = new parkingClass();
			$parkingReport = $parkingClass->getReportByDate($datetime[0]);
			$operational['car_parking'] = $parkingReport['inhouse_carcount_mobil'];
			$operational['car_drop_off'] = $parkingReport['inhouse_carcount_drop_off'];
			$operational['valet_parking'] = $parkingReport['inhouse_carcount_valet_reg'];
			$operational['box_vehicle'] = $parkingReport['inhouse_carcount_box'];
			$operational['taxi_bluebird'] = $parkingReport['inhouse_carcount_taxi'];
			$operational['motorbike'] = $parkingReport['inhouse_carcount_motor'];
		
			Zend_Loader::LoadClass('issueClass', $this->modelDir);
			$issueClass = new issueClass();
			$utilitySpecificReport = $issueClass->getIssueByReportAndCatId('om_report_id', $params['id'], '10', $report_date);
			
			Zend_Loader::LoadClass('securityClass', $this->modelDir);
			$securityClass = new securityClass();
			//$securitySpecificReport = $securityClass->getSpecificReportByReport('om_report_id', $params['id'], $report_date);
			$security_solved_specific_report = $securityClass->getOMSolvedSpecificReports($params['id'], $report_date);
			$security_issues = $issueClass->getOMClosedIssues($report_date, 1);
			$securitySpecificReport = array_merge($security_solved_specific_report, $security_issues);
			
			Zend_Loader::LoadClass('safetyClass', $this->modelDir);
			$safetyClass = new safetyClass();
			//$safetySpecificReport = $safetyClass->getSpecificReportByReport('om_report_id', $params['id'], $report_date);
			$safety_solved_specific_report = $safetyClass->getOMSolvedSpecificReports($params['id'], $report_date);
			$safety_issues = $issueClass->getOMClosedIssues($report_date, 3);
			$safetySpecificReport = array_merge($safety_solved_specific_report, $safety_issues);
			
			Zend_Loader::LoadClass('housekeepingClass', $this->modelDir);
			$housekeepingClass = new housekeepingClass();		
			$progressreportTable = $this->loadModel('progressreport');
			$hk_progress_report_shift = $progressreportTable->getHousekeepingProgressReportByReport('om_report_id', $params['id']);
			$hk_other_info = $progressreportTable->getHousekeepingOtherInfoByReport('om_report_id', $params['id']);
			$hk_issues = $issueClass->getOMClosedIssues($report_date, 2);
			
			Zend_Loader::LoadClass('parkingClass', $this->modelDir);
			$parkingClass = new parkingClass();
			//$parkingSpecificReport = $parkingClass->getSpecificReportByReport('om_report_id', $params['id']);
			$parking_solved_specific_report = $parkingClass->getOMSolvedSpecificReports($params['id'], $report_date);
			$parking_issues = $issueClass->getOMClosedIssues($report_date, 5);
			$parkingSpecificReport = array_merge($parking_solved_specific_report, $parking_issues);

			$marketing_promotion = $operationalClass->getEvents($params['id']);
			
			$attachment = $operationalClass->getAttachments($params['id']);
			
			require('PHPpdf/html2fpdf.php');


			$html= '<html>
			<head>
			<title>Daily Operational Mall Report</title>
			 
			</head>
			<body>
			<h2>Daily Operational Mall Report</h2>
			'.$operational['site_fullname'].'
			
			<h3>DAY / DATE</h3>
			<table width="100%" cellspacing="6" cellpadding="5" align="center" border="1" valign="top">
				<tr><td><strong>Day / Date</strong></td><td colspan="3">'.$operational['report_date'].'</td></tr>
			</table>
			
			<h3>ISSUES</h3>
			<h4>A. BUILDING SERVICE</h4>';
		
			
		if(!empty($utilitySpecificReport)) { 	
			$html .= '<table width="100%" cellspacing="6" cellpadding="5" align="center" border="1" valign="top">
			<tr bgcolor="#afd9af">
				<th width="120"><strong>Foto</strong></th>
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
				if(!empty($sasr['completion_date']) && $sasr['completion_date']!="0000-00-00 00:00:00") $safety_comp_date = $sasr['completion_date'];
				else $safety_comp_date = $sasr['solved_date'];
				$safety_completion_date = explode(" ", $safety_comp_date);
				if(!empty($sasr['issue_id'])) $sasr['detail'] = $sasr['description'];
				$html .= '<tr>
					<td>';
				if(!empty($sasr['picture'])) $html .= '<img src="'.$this->config->general->url.'images/issues/'.str_replace(".","_thumb.",$sasr['picture']).'" height="50" style="margin-right:5px;" /> ';
				if(!empty($sasr['solved_picture'])) $html .= '<img src="'.$this->config->general->url.'images/issues/'.str_replace(".","_thumb.",$sasr['solved_picture']).'" height="50" />';
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
				if(!empty($sesr['completion_date']) && $sesr['completion_date']!="0000-00-00 00:00:00") $security_comp_date = $sesr['completion_date'];
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
						if(!empty($hk_other_info['img_progress'])) 
						{
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
			foreach($hk_issues as $hk_issue) {
				$security_comp_date = $hk_issue['solved_date'];
				$hk_completion_date = explode(" ", $security_comp_date);
				$html .= '<tr>
					<td>';
				if(!empty($hk_issue['picture'])) $html .= '<img src="'.$this->config->general->url.'images/issues/'.str_replace(".","_thumb.",$hk_issue['picture']).'" width="40" style="margin-right:5px;" /> ';
				if(!empty($hk_issue['solved_picture'])) $html .= '<img src="'.$this->config->general->url.'images/issues/'.str_replace(".","_thumb.",$hk_issue['solved_picture']).'" width="40" />';
				$html .= '</td>
					<td>'.$hk_issue['location'].'</td>
					<td>'.$hk_issue['description'].'</td>
					<td>'.$hk_completion_date[0].'</td>
				</tr>';
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
		$html .= '<h3>MARKETING &amp; PROMOTION</h4>			
			<table width="100%" cellspacing="6" cellpadding="5" align="center" border="1" valign="top">
			<tr bgcolor="#afd9af">
				<th><strong>Nama Event</strong></th>
				<th width="100"><strong>Foto-foto</strong></th>
				<th><strong>Lokasi</strong></th>
				<th><strong>Kondisi Event</strong></th>
				<th><strong>Periode Event</strong></th>
			</tr>';
			if(!empty($marketing_promotion)) {
				foreach($marketing_promotion as $mp) {
					$html .= '<tr>
						<td>'.$mp['event_name'].'</td>
						<td>';
					if(!empty($mp['event_img'])) {
						$html .= '<img src="'.$this->config->general->url.'images/event/'.$mp['event_img'].'" height="50px" />';
					}
					
					$html .= '</td>
						<td>'.$mp['event_location'].'</td>
						<td>'.$mp['event_condition'].'</td>
						<td>'.$mp['event_period'].'</td>
					</tr>';
				}
			}
			$html .= '</table>
		
		<h3>SUMMARY WO/WR TENANT &amp; INTERNAL</h3>
		<table width="100%" cellspacing="6" cellpadding="5" align="center" border="1" valign="top">
			<tr bgcolor="#afd9af">
			  <th rowspan="2"><strong>Department</strong></th>
			  <th rowspan="2"><strong>No. of Req. WO per today</strong></th>
			  <th rowspan="2"><strong>Completed WO per today</strong></th>
			  <th rowspan="2"><strong>No. of Outstanding WO per today</strong></th>
			  <th colspan="2"><strong>Accumulate</strong></th>
			</tr>
			<tr bgcolor="#afd9af">
			  <th><strong>Previous Outstanding</strong></th>
			  <th><strong>Total Outstanding</strong></th>
			</tr>
			<tr>
				<td>Engineering</td>
				<td>'.$operational['engineering_no_of_req_wo'].'</td>
				<td>'.$operational['engineering_completed_wo'].'</td>
				<td>'.$operational['engineering_no_of_outstanding_wo'].'</td>
				<td>'.$operational['engineering_previous_outstanding'].'</td>
				<td>'.$operational['engineering_next_outstanding'].'</td>
			</tr>
			<tr>
				<td>BS/Civil</td>
				<td>'.$operational['bs_no_of_req_wo'].'</td>
				<td>'.$operational['bs_completed_wo'].'</td>
				<td>'.$operational['bs_no_of_outstanding_wo'].'</td>
				<td>'.$operational['bs_previous_outstanding'].'</td>
				<td>'.$operational['bs_next_outstanding'].'</td>
			</tr>
			<tr>
				<td>Housekeeping</td>
				<td>'.$operational['housekeeping_no_of_req_wo'].'</td>
				<td>'.$operational['housekeeping_completed_wo'].'</td>
				<td>'.$operational['housekeeping_no_of_outstanding_wo'].'</td>
				<td>'.$operational['housekeeping_previous_outstanding'].'</td>
				<td>'.$operational['housekeeping_next_outstanding'].'</td>
			</tr>
			<tr>
				<td>Parking</td>
				<td>'.$operational['parking_no_of_req_wo'].'</td>
				<td>'.$operational['parking_completed_wo'].'</td>
				<td>'.$operational['parking_no_of_outstanding_wo'].'</td>
				<td>'.$operational['parking_previous_outstanding'].'</td>
				<td>'.$operational['parking_next_outstanding'].'</td>
			</tr>
			<tr>
				<td>Others</td>
				<td>'.$operational['other_no_of_req_wo'].'</td>
				<td>'.$operational['other_completed_wo'].'</td>
				<td>'.$operational['other_no_of_outstanding_wo'].'</td>
				<td>'.$operational['other_previous_outstanding'].'</td>
				<td>'.$operational['other_next_outstanding'].'</td>
			</tr>';
		$html .= '</table>
			
			<h3>PERHITUNGAN HEAD COUNT &amp; CAR COUNT</h3>
		<table width="100%" cellspacing="6" cellpadding="5" align="center" border="0" valign="top">
			<tr>
				<td width="150">A. Head Count</td>
				<td></td>
				<td>'.$operational['head_count'].'</td>
			</tr>
			<tr>
				<td>B. Total Car Count</td>
				<td></td>
				<td>'.$operational['total_car_count'].'</td>
			</tr>
			<tr>
				<td></td>
				<td>1. Car Parking</td>
				<td>'.$operational['car_parking'].'</td>
			</tr>
			<tr>
				<td></td>
				<td>2. Car Drop Off</td>
				<td>'.$operational['car_drop_off'].'</td>
			</tr>
			<tr>
				<td></td>
				<td>3. Valet Parking</td>
				<td>'.$operational['valet_parking'].'</td>
			</tr>
			<tr>
				<td></td>
				<td>4. Box Vehicle</td>
				<td>'.$operational['box_vehicle'].'</td>
			</tr>
			<tr>
				<td></td>
				<td>5. Taxi</td>
				<td>'.$operational['taxi_bluebird'].'</td>
			</tr>
			<tr>
				<td>C. Motorbike</td>
				<td></td>
				<td>'.$operational['motorbike'].'</td>
			</tr>
			<tr>
				<td>D. Bus</td>
				<td></td>
				<td>'.$operational['bus'].'</td>
			</tr>
		</table>';
		
		$html .= '<h4>Attachment</h4>			
			<table width="100%" cellspacing="6" cellpadding="5" align="center" border="1" valign="top">
			<tr bgcolor="#afd9af">
				<th><strong>Description</strong></th>
			</tr>';
			if(!empty($attachment)) {
				foreach($attachment as $att) {
					$html .= '<tr>
						<td><a target="_blank" href="'.$this->baseUrl.'/default/attachment/openattachment/c/7/f/'.$att['filename'].'">'.$att['description'].'</a></td>
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
			Zend_Loader::LoadClass('operationalClass', $this->modelDir);
			$operationalClass = new operationalClass();
			//$operational = $operationalClass->getReportById($params['id']);

			$params['user_id'] = $this->ident['user_id'];
			$operationalClass->addReadOMReportLog($params);

			$filename = $this->config->paths->html.'/pdf_report/om/' . $this->site_id."_om_".$params['id'].".pdf";
			if (!file_exists($filename)/* || date("Y-m-d", mktime(0, 0, 0, date("m"), date("d")-1, date("Y"))) < $operational['created_date']*/) {		
				$this->exportomtopdf($params['id']);
			}
			// Header content type 
			header('Content-type: application/pdf'); 				
			header('Content-Disposition: inline; filename="' . $filename . '"'); 				
			// Read the file 
			readfile($filename); 
			exit();
		}
	}

	public function downloadomreportAction() {		
		$params = $this->_getAllParams();
		if(!empty($params['id']))
		{			
			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "Export OM Daily Report to PDF";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);	
			
			$this->exportomtopdf($params['id'], "", 1);
		}		
	}

	public function savepdfAction() {		
		$params = $this->_getAllParams();
		if(!empty($params['id']))
		{
			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "Export OM Daily Report to PDF";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);	
			$this->exportomtopdf($params['id']);
		}		
	}
	
	
	function getcommentsbyreportidAction()
	{
		$params = $this->_getAllParams();
		$this->view->ident = $this->ident;
		$category = $this->loadModel('category');
		$operationalCommentsTable = $this->loadModel('operationalcomments');
		$comments = $operationalCommentsTable->getCommentsByOperationalMallReportId($params['id']);
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
		$operationalCommentsTable = $this->loadModel('operationalcomments');
		$params['user_id'] = $this->ident['user_id'];
		$params['site_id'] = $this->site_id;

		if($_FILES["attachment"]["size"] > 0)
		{
			$ext = explode(".",$_FILES["attachment"]['name']);
			$filename = "om_".date("YmdHis").".".$ext[count($ext)-1];
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
				$operationalCommentsTable->addComment($params);
			}		
		}
		else{
			$operationalCommentsTable->addComment($params);
		}	
		
		$allParams = $params;
		
		Zend_Loader::LoadClass('operationalClass', $this->modelDir);
		$operationalClass = new operationalClass();
		$operational = $operationalClass->getReportById($params['report_id']);
		
		$datetime = explode(" ",$operational['created_date']);
		$date = explode("-",$datetime[0]);
		$r_date = mktime(0, 0, 0, intval($date[1]), intval($date[2]), intval($date[0]));
		$report_date = date("l, j F Y", $r_date);
		
		$categoryClass = $this->loadModel('category');
		$category = $categoryClass->getCategoryById('7');	

		
		$botToken = '716005387:AAFhUdDGlXjK5YFMYqwx_lCpqpp8760S_TE';
		$website="https://api.telegram.org/bot".$botToken;
		//$chatId=$category['telegram_channel_id'];  //Receiver Chat Id 
		$chatId=$category['site_'.$this->site_id];  //Receiver Chat Id 

		if(!empty($params['filename'])) $attachmenttext = '
attachment : '.$this->config->general->url."comments/".$params['filename'];

		$txt = '[NEW COMMENT] 
'.$this->ident['name']." : ".$params['comment'].$attachmenttext.'

[OPERATIONAL MALL REPORT]
Report Date : '.$report_date.'
Report Link : '.$this->config->general->url."default/operational/viewdetailreport/s/".$this->site_id."/id/".$params['report_id'];
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
		$logData['action'] = "Add Comment to Housekeeping Daily Report";
		$logData['data'] = json_encode($allParams);
		$logsTable->insertLogs($logData);	

		echo $allParams['filename'];
	}	
	
	function getupdatedcommentsAction()
	{
		$params = $this->_getAllParams();
		$params['pagesize'] = 10;
	
		Zend_Loader::LoadClass('operationalClass', $this->modelDir);
		$operationalClass = new operationalClass();
		
		$data= array();
		$operationalReports = $operationalClass->getReports($params);	
		$commentsTable = $this->loadModel('operationalcomments');
		$i=0;
		foreach($operationalReports as $s) {
			$data[$i]['operational_report_id'] = $s['operation_mall_report_id'];
			$comments = $commentsTable->getCommentsByOperationalMallReportId($s['operation_mall_report_id'], '3');
			
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
		
		Zend_Loader::LoadClass('operationalClass', $this->modelDir);
		$operationalClass = new operationalClass();
		
		$data= array();

		$commentCacheName = "om_comments_".$this->site_id."_".$params["start"];
		$data = $this->cache->load($commentCacheName);
		$i=0;
		if(empty($data))
		{
			$operationalReports = $operationalClass->getReports($params);	
			$commentsTable = $this->loadModel('operationalcomments');
			foreach($operationalReports as $s) {
				$data[$i]['operational_report_id'] = $s['operation_mall_report_id'];
				$comments = $commentsTable->getCommentsByOperationalMallReportId($s['operation_mall_report_id'], '3');
				
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
	
	function addattachmentAction()
	{
		$params = $this->_getAllParams();
		
		Zend_Loader::LoadClass('operationalClass', $this->modelDir);
		$operationalClass = new operationalClass();
		
		$attachment_id = $operationalClass->addAttachment($params);
		
		if(!empty($_FILES["attachment_file"]))
		{
			$ext = explode(".",$_FILES["attachment_file"]['name']);
			$filename = $attachment_id.".".$ext[count($ext)-1];
			$datafolder = $this->config->paths->html."/attachment/operational/";
			if(move_uploaded_file($_FILES["attachment_file"]["tmp_name"], $datafolder.$filename))
			{
				$operationalClass->updateAttachment($attachment_id,'filename', $filename);
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
		$logData['action'] = "Add Attachment to OM Daily Report";
		$logData['data'] = json_encode($params);
		$logsTable->insertLogs($logData);	
		
		
		$this->_response->setRedirect($this->baseUrl.'/default/operational/page2/id/'.$params['report_id']);
		$this->_response->sendResponse();
		exit();
	
	}
	
	public function getattachmentbyidAction() {
		$params = $this->_getAllParams();
		Zend_Loader::LoadClass('operationalClass', $this->modelDir);
		$operationalClass = new operationalClass();
		
		if(!empty($params['id'])) 
		{
			echo json_encode($operationalClass->getAttachmentById($params['id']));
		}
	}
	
	public function deleteattachmentbyidAction() {
		$params = $this->_getAllParams();
		Zend_Loader::LoadClass('operationalClass', $this->modelDir);
		$operationalClass = new operationalClass();
		
		$operationalClass->deleteAttachmentById($params['id']);

		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "Delete OM Daily Report Attachment";
		$logData['data'] = json_encode($params);
		$logsTable->insertLogs($logData);	
		
		$this->_response->setRedirect($this->baseUrl.'/default/operational/page2/id/'.$params['om_report_id']);
		$this->_response->sendResponse();
		exit();
	}
	
	function addeventAction()
	{
		$params = $this->_getAllParams();
		
		Zend_Loader::LoadClass('operationalClass', $this->modelDir);
		$operationalClass = new operationalClass();
		
		$params['operation_mall_report_id'] = $params['report_id'];
		$event_id = $operationalClass->addEvent($params);
		
		if(!empty($_FILES["event_img"]))
		{
			$ext = explode(".",$_FILES["event_img"]['name']);
			$filename = $event_id."_om.".$ext[count($ext)-1];
			$datafolder = $this->config->paths->html."/images/event/";
			if(move_uploaded_file($_FILES["event_img"]["tmp_name"], $datafolder.$filename))
			{
				$operationalClass->updateEventFileName($event_id,'event_img', $filename);
				$magickPath = "/usr/bin/convert";
				$new_file_thumb = $event_id."_om_thumb.".$ext[count($ext)-1];
				/*** create thumbnail image ***/
				exec($magickPath . ' ' . $datafolder.$filename . ' -resize 128x128 ' . $datafolder.$new_file_thumb);
				/*** resize image if size greater than 500 Kb ***/
				if(filesize($datafolder.$filename) > 500000) exec($magickPath . ' ' . $datafolder.$filename . ' -resize 800x800\> ' . $datafolder.$filename);
			}
		}

		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "Add Event to OM Daily Report";
		$logData['data'] = json_encode($params);
		$logsTable->insertLogs($logData);	
		
		$this->_response->setRedirect($this->baseUrl.'/default/operational/page2/id/'.$params['report_id']);
		$this->_response->sendResponse();
		exit();
	
	}
	
	public function geteventbyidAction() {
		$params = $this->_getAllParams();
		Zend_Loader::LoadClass('operationalClass', $this->modelDir);
		$operationalClass = new operationalClass();
		
		if(!empty($params['id'])) 
		{
			echo json_encode($operationalClass->getEventById($params['id']));
		}
	}
	
	public function deleteeventbyidAction() {
		$params = $this->_getAllParams();
		Zend_Loader::LoadClass('operationalClass', $this->modelDir);
		$operationalClass = new operationalClass();
		
		$operationalClass->deleteEventById($params['id']);

		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "Delete Event to OM Daily Report";
		$logData['data'] = json_encode($params);
		$logsTable->insertLogs($logData);	
		
		$this->_response->setRedirect($this->baseUrl.'/default/operational/page2/id/'.$params['om_report_id']);
		$this->_response->sendResponse();
		exit();
	}
}

?>