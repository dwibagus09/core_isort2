<?php
require_once('actionControllerBase.php');

class BmController extends actionControllerBase
{
	public function addAction() {
		$this->view->ident = $this->ident;
		$this->view->title = "Add Building Manager Report";
		
		$now = date("Y-m-d");
		
		Zend_Loader::LoadClass('bmClass', $this->modelDir);
		$bmClass = new bmClass();
		$reportBM = $bmClass->getReportByDate($now);
		$this->view->building = $reportBM[0]['building'];
		
		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "Add BM Report";
		$logData['data'] = "Opening the form page";
		$logsTable->insertLogs($logData);	

		$this->renderTemplate('form_daily_bm.tpl'); 
	}
	
	public function savereportAction() {
		set_time_limit(7200);
		$params = $this->_getAllParams();
		
		$params['user_id'] = $this->ident['user_id'];
		Zend_Loader::LoadClass('bmClass', $this->modelDir);
		$bmClass = new bmClass();
		
		$report = $bmClass->getReportByDate(date("Y-m-d"));
		if(empty($params['report_id']) && count($report) > 1)
		{
			$this->view->title = "Add Building Manager Daily Report";
			$this->view->message="Report is already exist";
			$this->view->bm = $params; 
			
			$this->renderTemplate('form_daily_bm.tpl'); 
			exit();
		}
		
		$params['report_id'] = $bmClass->saveReport($params);
		
		/*** UTILITY ***
		if(!empty($params['utility_location']))
		{		
			$m = 0;
			$dt3=array();
			foreach($params['utility_location'] as $location)
			{
				if($params['utility_issue_id'][$m] > 0)
				{
					$dt3[$m] = $bmClass->getUtilityIssueById($params['utility_issue_id'][$m]);
					if(!empty($location)) $dt3[$m]['location'] = $location;
					if(!empty($params['utility_description'][$m])) $dt3[$m]['description'] = $params['utility_description'][$m];
					if(!empty($params['utility_status'][$m])) $dt3[$m]['status'] = $params['utility_status'][$m];
					if(!empty($params['utility_completion_date'][$m])) $dt3[$m]['completion_date'] = $params['utility_completion_date'][$m];
				}
				else
				{
					$dt3[$m]['bm_report_id'] = $params['report_id'];
					$dt3[$m]['location'] = $location;
					$dt3[$m]['description'] = $params['utility_description'][$m];
					$dt3[$m]['status'] = $params['utility_status'][$m];
					$dt3[$m]['completion_date'] = $params['utility_completion_date'][$m];
				}	
				
				$dt3[$m]['site_id'] = $this->site_id;
				if(!empty($_FILES["utility_img"]['name'][$m]))
				{
					$dt3[$m]['pic'] = $_FILES["utility_img"];
				}
				$m++;
			}			
			$bmClass->deleteUtilityByReportId($params['report_id']);
			$m=0;
			foreach($dt3 as $dt)
			{
				$utility_img = $dt['pic'];
				unset($dt['pic']);
				$issue_id = $bmClass->addUtilityIssue($dt);
				if($issue_id > 0)
				{
					if(!empty($utility_img['name'][$m]))
					{
						$ext = explode(".",$utility_img['name'][$m]);
						$filename = $issue_id."_utility.".$ext[count($ext)-1];
						if(move_uploaded_file($utility_img["tmp_name"][$m], $this->config->paths->html."/images/bm/".$filename))
							$bmClass->updateUtilityFileName($issue_id,'picture', $filename);
					}
				}
				$m++;
			}			
		}
		
		/*** SAFETY ***
		if(!empty($params['safety_location']))
		{		
			$m = 0;
			$dt3=array();
			foreach($params['safety_location'] as $location)
			{
				if($params['safety_issue_id'][$m] > 0)
				{
					$dt3[$m] = $bmClass->getSafetyIssueById($params['safety_issue_id'][$m]);
					if(!empty($location)) $dt3[$m]['location'] = $location;
					if(!empty($params['safety_description'][$m])) $dt3[$m]['description'] = $params['safety_description'][$m];
					if(!empty($params['safety_status'][$m])) $dt3[$m]['status'] = $params['safety_status'][$m];
					if(!empty($params['safety_completion_date'][$m])) $dt3[$m]['completion_date'] = $params['safety_completion_date'][$m];
				}
				else
				{
					$dt3[$m]['bm_report_id'] = $params['report_id'];
					$dt3[$m]['location'] = $location;
					$dt3[$m]['description'] = $params['safety_description'][$m];
					$dt3[$m]['status'] = $params['safety_status'][$m];
					$dt3[$m]['completion_date'] = $params['safety_completion_date'][$m];
				}	
				
				$dt3[$m]['site_id'] = $this->site_id;
				if(!empty($_FILES["safety_img"]['name'][$m]))
				{
					$dt3[$m]['pic'] = $_FILES["safety_img"];
				}
				$m++;
			}			
			$bmClass->deleteSafetyByReportId($params['report_id']);
			$m=0;
			foreach($dt3 as $dt)
			{
				$safety_img = $dt['pic'];
				unset($dt['pic']);
				$issue_id = $bmClass->addSafetyIssue($dt);
				if($issue_id > 0)
				{
					if(!empty($safety_img['name'][$m]))
					{
						$ext = explode(".",$safety_img['name'][$m]);
						$filename = $issue_id."_safety.".$ext[count($ext)-1];
						if(move_uploaded_file($safety_img["tmp_name"][$m], $this->config->paths->html."/images/bm/".$filename))
							$bmClass->updateSafetyFileName($issue_id,'picture', $filename);
					}
				}
				$m++;
			}			
		}
		
		/*** SECURITY ***
		if(!empty($params['security_location']))
		{		
			$m = 0;
			$dt3=array();
			foreach($params['security_location'] as $location)
			{
				if($params['security_issue_id'][$m] > 0)
				{
					$dt3[$m] = $bmClass->getSecurityIssueById($params['security_issue_id'][$m]);
					if(!empty($location)) $dt3[$m]['location'] = $location;
					if(!empty($params['security_description'][$m])) $dt3[$m]['description'] = $params['security_description'][$m];
					if(!empty($params['security_status'][$m])) $dt3[$m]['status'] = $params['security_status'][$m];
					if(!empty($params['security_completion_date'][$m])) $dt3[$m]['completion_date'] = $params['security_completion_date'][$m];
				}
				else
				{
					$dt3[$m]['bm_report_id'] = $params['report_id'];
					$dt3[$m]['location'] = $location;
					$dt3[$m]['description'] = $params['security_description'][$m];
					$dt3[$m]['status'] = $params['security_status'][$m];
					$dt3[$m]['completion_date'] = $params['security_completion_date'][$m];
				}	
				
				$dt3[$m]['site_id'] = $this->site_id;
				if(!empty($_FILES["security_img"]['name'][$m]))
				{
					$dt3[$m]['pic'] = $_FILES["security_img"];
				}
				$m++;
			}			
			$bmClass->deleteSecurityByReportId($params['report_id']);
			$m=0;
			foreach($dt3 as $dt)
			{
				$security_img = $dt['pic'];
				unset($dt['pic']);
				$issue_id = $bmClass->addSecurityIssue($dt);
				if($issue_id > 0)
				{
					if(!empty($security_img['name'][$m]))
					{
						$ext = explode(".",$security_img['name'][$m]);
						$filename = $issue_id."_security.".$ext[count($ext)-1];
						if(move_uploaded_file($security_img["tmp_name"][$m], $this->config->paths->html."/images/bm/".$filename))
							$bmClass->updateSecurityFileName($issue_id,'picture', $filename);
					}
				}
				$m++;
			}			
		}
		
		/*** HOUSEKEEPING ***
		if(!empty($params['housekeeping_location']))
		{		
			$m = 0;
			$dt3=array();
			foreach($params['housekeeping_location'] as $location)
			{
				if($params['housekeeping_issue_id'][$m] > 0)
				{
					$dt3[$m] = $bmClass->getHousekeepingIssueById($params['housekeeping_issue_id'][$m]);
					if(!empty($location)) $dt3[$m]['location'] = $location;
					if(!empty($params['housekeeping_description'][$m])) $dt3[$m]['description'] = $params['housekeeping_description'][$m];
					if(!empty($params['housekeeping_status'][$m])) $dt3[$m]['status'] = $params['housekeeping_status'][$m];
					if(!empty($params['housekeeping_completion_date'][$m])) $dt3[$m]['completion_date'] = $params['housekeeping_completion_date'][$m];
				}
				else
				{
					$dt3[$m]['bm_report_id'] = $params['report_id'];
					$dt3[$m]['location'] = $location;
					$dt3[$m]['description'] = $params['housekeeping_description'][$m];
					$dt3[$m]['status'] = $params['housekeeping_status'][$m];
					$dt3[$m]['completion_date'] = $params['housekeeping_completion_date'][$m];
				}	
				
				$dt3[$m]['site_id'] = $this->site_id;
				if(!empty($_FILES["housekeeping_img"]['name'][$m]))
				{
					$dt3[$m]['pic'] = $_FILES["housekeeping_img"];
				}
				$m++;
			}			
			$bmClass->deleteHousekeepingByReportId($params['report_id']);
			$m=0;
			foreach($dt3 as $dt)
			{
				$housekeeping_img = $dt['pic'];
				unset($dt['pic']);
				$issue_id = $bmClass->addHousekeepingIssue($dt);
				if($issue_id > 0)
				{
					if(!empty($housekeeping_img['name'][$m]))
					{
						$ext = explode(".",$housekeeping_img['name'][$m]);
						$filename = $issue_id."_housekeeping.".$ext[count($ext)-1];
						if(move_uploaded_file($housekeeping_img["tmp_name"][$m], $this->config->paths->html."/images/bm/".$filename))
							$bmClass->updateHousekeepingFileName($issue_id,'picture', $filename);
					}
				}
				$m++;
			}			
		}
		
		/*** PARKING & TRAFFIC ***
		if(!empty($params['parking_location']))
		{		
			$m = 0;
			$dt3=array();
			foreach($params['parking_location'] as $location)
			{
				if($params['parking_issue_id'][$m] > 0)
				{
					$dt3[$m] = $bmClass->getParkingIssueById($params['parking_issue_id'][$m]);
					if(!empty($location)) $dt3[$m]['location'] = $location;
					if(!empty($params['parking_description'][$m])) $dt3[$m]['description'] = $params['parking_description'][$m];
					if(!empty($params['parking_status'][$m])) $dt3[$m]['status'] = $params['parking_status'][$m];
					if(!empty($params['parking_completion_date'][$m])) $dt3[$m]['completion_date'] = $params['parking_completion_date'][$m];
				}
				else
				{
					$dt3[$m]['bm_report_id'] = $params['report_id'];
					$dt3[$m]['location'] = $location;
					$dt3[$m]['description'] = $params['parking_description'][$m];
					$dt3[$m]['status'] = $params['parking_status'][$m];
					$dt3[$m]['completion_date'] = $params['parking_completion_date'][$m];
				}	
				
				$dt3[$m]['site_id'] = $this->site_id;
				if(!empty($_FILES["parking_img"]['name'][$m]))
				{
					$dt3[$m]['pic'] = $_FILES["parking_img"];
				}
				$m++;
			}			
			$bmClass->deleteParkingByReportId($params['report_id']);
			$m=0;
			foreach($dt3 as $dt)
			{
				$parking_img = $dt['pic'];
				unset($dt['pic']);
				$issue_id = $bmClass->addParkingIssue($dt);
				if($issue_id > 0)
				{
					if(!empty($parking_img['name'][$m]))
					{
						$ext = explode(".",$parking_img['name'][$m]);
						$filename = $issue_id."_parking.".$ext[count($ext)-1];
						if(move_uploaded_file($parking_img["tmp_name"][$m], $this->config->paths->html."/images/bm/".$filename))
							$bmClass->updateParkingFileName($issue_id,'picture', $filename);
					}
				}
				$m++;
			}			
		}
		
		/*** RESIDENT RELATIONS ***
		if(!empty($params['resident_location']))
		{		
			$m = 0;
			$dt3=array();
			foreach($params['resident_location'] as $location)
			{
				if($params['resident_issue_id'][$m] > 0)
				{
					$dt3[$m] = $bmClass->getResidentIssueById($params['resident_issue_id'][$m]);
					if(!empty($location)) $dt3[$m]['location'] = $location;
					if(!empty($params['resident_description'][$m])) $dt3[$m]['description'] = $params['resident_description'][$m];
					if(!empty($params['resident_status'][$m])) $dt3[$m]['status'] = $params['resident_status'][$m];
					if(!empty($params['resident_completion_date'][$m])) $dt3[$m]['completion_date'] = $params['resident_completion_date'][$m];
				}
				else
				{
					$dt3[$m]['bm_report_id'] = $params['report_id'];
					$dt3[$m]['location'] = $location;
					$dt3[$m]['description'] = $params['resident_description'][$m];
					$dt3[$m]['status'] = $params['resident_status'][$m];
					$dt3[$m]['completion_date'] = $params['resident_completion_date'][$m];
				}	
				
				$dt3[$m]['site_id'] = $this->site_id;
				if(!empty($_FILES["resident_img"]['name'][$m]))
				{
					$dt3[$m]['pic'] = $_FILES["resident_img"];
				}
				$m++;
			}			
			$bmClass->deleteResidentByReportId($params['report_id']);
			$m=0;
			foreach($dt3 as $dt)
			{
				$resident_img = $dt['pic'];
				unset($dt['pic']);
				$issue_id = $bmClass->addResidentIssue($dt);
				if($issue_id > 0)
				{
					if(!empty($resident_img['name'][$m]))
					{
						$ext = explode(".",$resident_img['name'][$m]);
						$filename = $issue_id."_resident.".$ext[count($ext)-1];
						if(move_uploaded_file($resident_img["tmp_name"][$m], $this->config->paths->html."/images/bm/".$filename))
							$bmClass->updateResidentFileName($issue_id,'picture', $filename);
					}
				}
				$m++;
			}			
		}
		
		/*** BUILDING SERVICE ***
		if(!empty($params['building_service_location']))
		{		
			$m = 0;
			$dt3=array();
			foreach($params['building_service_location'] as $location)
			{
				if($params['building_service_issue_id'][$m] > 0)
				{
					$dt3[$m] = $bmClass->getBuildingServiceIssueById($params['building_service_issue_id'][$m]);
					if(!empty($location)) $dt3[$m]['location'] = $location;
					if(!empty($params['building_service_description'][$m])) $dt3[$m]['description'] = $params['building_service_description'][$m];
					if(!empty($params['building_service_status'][$m])) $dt3[$m]['status'] = $params['building_service_status'][$m];
					if(!empty($params['building_service_completion_date'][$m])) $dt3[$m]['completion_date'] = $params['building_service_completion_date'][$m];
				}
				else
				{
					$dt3[$m]['bm_report_id'] = $params['report_id'];
					$dt3[$m]['location'] = $location;
					$dt3[$m]['description'] = $params['building_service_description'][$m];
					$dt3[$m]['status'] = $params['building_service_status'][$m];
					$dt3[$m]['completion_date'] = $params['building_service_completion_date'][$m];
				}	
				
				$dt3[$m]['site_id'] = $this->site_id;
				if(!empty($_FILES["building_service_img"]['name'][$m]))
				{
					$dt3[$m]['pic'] = $_FILES["building_service_img"];
				}
				$m++;
			}			
			$bmClass->deleteBuildingServiceByReportId($params['report_id']);
			$m=0;
			foreach($dt3 as $dt)
			{
				$building_service_img = $dt['pic'];
				unset($dt['pic']);
				$issue_id = $bmClass->addBuildingServiceIssue($dt);
				if($issue_id > 0)
				{
					if(!empty($building_service_img['name'][$m]))
					{
						$ext = explode(".",$building_service_img['name'][$m]);
						$filename = $issue_id."_building_service.".$ext[count($ext)-1];
						if(move_uploaded_file($building_service_img["tmp_name"][$m], $this->config->paths->html."/images/bm/".$filename))
							$bmClass->updateBuildingServiceFileName($issue_id,'picture', $filename);
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
					$dt3[$m] = $bmClass->getAttachmentById($params['attachment_id'][$m]);
					if(!empty($description)) $dt3[$m]['description'] = $description;
				}
				else
				{
					$dt3[$m]['site_id'] = $this->site_id;
					$dt3[$m]['report_id'] = $params['report_id'];
					$dt3[$m]['description'] = $description;
					
				}	
				if(!empty($_FILES["attachment_file"]['name'][$m]))
				{	
					$dt3[$m]['attachment'] = $_FILES["attachment_file"];
				}
				$m++;
			}			
			$bmClass->deleteAttachmentByReportId($params['report_id']);
			$m=0;
			foreach($dt3 as $dt)
			{
				$attachment = $dt['attachment'];
				unset($dt['attachment']);
				$attachment_id = $bmClass->addAttachment($dt);
				if($attachment_id > 0)
				{
					if(!empty($attachment['name'][$m]))
					{
						$ext = explode(".",$attachment['name'][$m]);
						$filename = $attachment_id.".".$ext[count($ext)-1];
						if(move_uploaded_file($attachment["tmp_name"][$m], $this->config->paths->html."/attachment/bm/".$filename))
							$bmClass->updateAttachment($attachment_id,'filename', $filename);
					}
				}
				$m++;
			}			
		}*/
		
		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "Save BM Daily Report - Page 1";
		$logData['data'] = json_encode($params);
		$logsTable->insertLogs($logData);

		$this->_response->setRedirect($this->baseUrl.'/default/bm/page2/id/'.$params['report_id']);
		$this->_response->sendResponse();
		exit();
	}
	
	public function page2Action() {
		$params = $this->_getAllParams();
		
		$params = $this->_getAllParams();
		Zend_Loader::LoadClass('bmClass', $this->modelDir);
		$bmClass = new bmClass();
		$bm = $bmClass->getReportById($params['id']);
			
		$datetime = explode(" ",$bm['created_date']);

		$date = explode("-",$datetime[0]);
		$r_date = mktime(0, 0, 0, intval($date[1]), intval($date[2]), intval($date[0]));
		$bm['report_date'] = date("l, j F Y", $r_date);	
		$this->view->bm = $bm;
		
		$this->view->utility = $bmClass->getUtilityIssues($params['id']);
		$this->view->safety = $bmClass->getSafetyIssues($params['id']);
		$this->view->security = $bmClass->getSecurityIssues($params['id']);
		$this->view->housekeeping = $bmClass->getHousekeepingIssues($params['id']);
		$this->view->parking = $bmClass->getParkingIssues($params['id']);
		$this->view->resident = $bmClass->getResidentIssues($params['id']);
		$this->view->building_service = $bmClass->getBuildingServiceIssues($params['id']);
		
		$this->view->attachment = $bmClass->getAttachments($params['id']);

		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "Open BM Daily Report - Page 2";
		$logData['data'] = json_encode($params);
		$logsTable->insertLogs($logData);
		
		$this->renderTemplate('form_daily_bm2.tpl'); 
	}
	
	public function viewreportAction() {
		$params = $this->_getAllParams();
		Zend_Loader::LoadClass('bmClass', $this->modelDir);
		$bmClass = new bmClass();
		Zend_Loader::LoadClass('bmcommentsClass', $this->modelDir);
		$bmcommentsClass = new bmcommentsClass();
		if(empty($params['start'])) $params['start'] = '0';
		$params['pagesize'] = 10;
		$this->view->start = $params['start'];
		$bm = $bmClass->getReports($params);
		foreach($bm as &$b)
		{
			$date = explode(" ", $b['created_date']);
			$b['allowEditDate'] = $date[0];
			$arr_date = explode("-",$date[0]);
			$b['day_date'] = date("l, j F Y", mktime(0, 0, 0, $arr_date[1], $arr_date[2], $arr_date[0]));
			$b['comments'] = $bmcommentsClass->getCommentsByReportId($b['report_id'], '3');
		}
		$this->view->bm = $bm;
		
		
		$totalReport = $bmClass->getTotalReport();
		if($totalReport['total'] > 10)
		{
			if($params['start'] >= 10)
			{
				$this->view->firstPageUrl = "/default/bm/viewreport";
				$this->view->prevUrl = "/default/bm/viewreport/view/start/".($params['start']-$params['pagesize']);
			}
			if($params['start'] < (floor(($totalReport['total']-1)/10)*10))
			{
				$this->view->nextUrl = "/default/bm/viewreport/start/".($params['start']+$params['pagesize']);
				$this->view->lastPageUrl = "/default/bm/viewreport/start/".(floor(($totalReport['total']-1)/10)*10);
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
		$logData['action'] = "View BM Daily Report List";
		$logData['data'] = json_encode($params);
		$logsTable->insertLogs($logData);
		
    	$this->renderTemplate('view_daily_bm.tpl');  
	}
	
	public function editAction() {
		$params = $this->_getAllParams();
		Zend_Loader::LoadClass('bmClass', $this->modelDir);
		$bmClass = new bmClass();
		
		if(!empty($params['id'])) 
		{
			$bm = $bmClass->getReportById($params['id']);
			
			$datetime = explode(" ",$bm['created_date']);
			/*if($datetime[0] != date("Y-m-d")) 
			{
				$this->_response->setRedirect($this->baseUrl.'/default/bm/viewreport');
				$this->_response->sendResponse();
				exit();
			}*/
			$date = explode("-",$datetime[0]);
			$r_date = mktime(0, 0, 0, intval($date[1]), intval($date[2]), intval($date[0]));
			$bm['report_date'] = date("l, j F Y", $r_date);	
			$this->view->bm = $bm;
			
			$report_date = $datetime[0];
			
			$this->view->utility = $bmClass->getUtilityIssues($params['id']);
			$this->view->safety = $bmClass->getSafetyIssues($params['id']);
			$this->view->security = $bmClass->getSecurityIssues($params['id']);
			$this->view->housekeeping = $bmClass->getHousekeepingIssues($params['id']);
			$this->view->parking = $bmClass->getParkingIssues($params['id']);
			$this->view->resident = $bmClass->getResidentIssues($params['id']);
			$this->view->building_service = $bmClass->getBuildingServiceIssues($params['id']);
			
			$this->view->attachment = $bmClass->getAttachments($params['id']);
		}

		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "Edit BM Daily Report";
		$logData['data'] = json_encode($params);
		$logsTable->insertLogs($logData);
		
		$this->view->title = "Edit Daily Building Manager Report";
		$this->view->editMode = 1;
		$this->renderTemplate('form_daily_bm.tpl');  
	}
	
	public function exporttopdfAction() {
		$params = $this->_getAllParams();
		Zend_Loader::LoadClass('bmClass', $this->modelDir);
		$bmClass = new bmClass();
		
		if(!empty($params['id'])) 
		{
			$bm = $bmClass->getReportById($params['id']);

			$params['user_id'] = $this->ident['user_id'];
			$bmClass->addReadBMReportLog($params);
			
			$datetime = explode(" ",$bm['created_date']);
			$date = explode("-",$datetime[0]);
			$r_date = mktime(0, 0, 0, intval($date[1]), intval($date[2]), intval($date[0]));
			$bm['report_date'] = date("l, j F Y", $r_date);	
			
			$report_date = $datetime[0];
			
			$utility = $bmClass->getUtilityIssues($params['id']);
			$safety = $bmClass->getSafetyIssues($params['id']);
			$security = $bmClass->getSecurityIssues($params['id']);
			$housekeeping = $bmClass->getHousekeepingIssues($params['id']);
			$parking = $bmClass->getParkingIssues($params['id']);
			$resident = $bmClass->getResidentIssues($params['id']);
			$building_service = $bmClass->getBuildingServiceIssues($params['id']);
			
			$attachment = $bmClass->getAttachments($params['id']);
			
			if($bm['building'] == '1') $building = 'Office Tower';
			elseif($bm['building'] == '2') $building = 'Kondominium';

			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "Export BM Daily Report to PDF";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);
			
			require('PHPpdf/html2fpdf.php');

			$html= '<html>
			<head>
			<title>Daily Building Manager Report</title>
			 
			</head>
			<body>
			<h2>Daily Building Manager Report</h2>
			'.$bm['site_fullname'].'
			
			<h3>DATE / BUILDING</h3>
			<table width="100%" cellspacing="6" cellpadding="5" align="center" border="1" valign="top">
				<tr><td><strong>Day / Date</strong></td><td colspan="3">'.$bm['report_date'].'</td></tr>
				<tr><td><strong>Building</strong></td><td colspan="3">'.$building.'</td></tr>
			</table>
			
			
			<h3>ISSUES</h3>
			<h4>Utility</h4>';
			if(!empty($utility)) { 
				$html .= '<table width="100%" cellspacing="6" cellpadding="5" align="center" border="1" valign="top">
				<tr bgcolor="#afd9af">
				  <th width="150"><strong>Foto</strong></th>
				  <th width="100"><strong>Lokasi</strong></th>
				  <th><strong>Deskripsi</strong></th>
				  <th width="100"><strong>Status</strong></th>
				  <th width="100"><strong>Completion Date</strong></th>
				</tr>';
				foreach($utility as $u) { 
					$html .= '<tr>
						<td>';
					if(!empty($u['picture']) && @getimagesize($this->config->paths->html.'/images/bm/'.$u['picture'])) {
						$html .= '<img src="'.$this->config->general->url.'images/bm/'.str_replace("Utility.","Utility_thumb.",$u['picture']).'" height="50px" />';
					}
					$html .= '</td><td>'.$u['location'].'</td>
						<td>'.$u['description'].'</td>
						<td>'.$u['status'].'</td>
						<td>'.$u['completion_date'].'</td>
					</tr>';
				}
				$html .= '</table>';
			}
			else
			{
				$html .= 'No Utility Issue<br>&nbsp;<br>';
			}
			$html .= '<h4>Safety</h4>';
			if(!empty($safety)) { 
				$html .= '<table width="100%" cellspacing="6" cellpadding="5" align="center" border="1" valign="top">
				<tr bgcolor="#afd9af">
				  <th width="150"><strong>Foto</strong></th>
				  <th width="100"><strong>Lokasi</strong></th>
				  <th><strong>Deskripsi</strong></th>
				  <th width="100"><strong>Status</strong></th>
				  <th width="100"><strong>Completion Date</strong></th>
				</tr>';
				foreach($safety as $sa) { 
					$html .= '<tr>
						<td>';
					if(!empty($sa['picture']) && @getimagesize($this->config->paths->html.'/images/bm/'.$sa['picture'])) {
						$html .= '<img src="'.$this->config->general->url.'images/bm/'.str_replace("Safety.","Safety_thumb.",$sa['picture']).'" height="50px" />';
					}
					$html .= '</td><td>'.$sa['location'].'</td>
						<td>'.$sa['description'].'</td>
						<td>'.$sa['status'].'</td>
						<td>'.$sa['completion_date'].'</td>
					</tr>';
				}
				$html .= '</table>';
			}
			else
			{
				$html .= 'No Safety Issue<br>&nbsp;<br>';
			}			
			$html .= '<h4>Security</h4>';
			if(!empty($security)) { 
				$html .= '<table width="100%" cellspacing="6" cellpadding="5" align="center" border="1" valign="top">
				<tr bgcolor="#afd9af">
				  <th width="150"><strong>Foto</strong></th>
				  <th width="100"><strong>Lokasi</strong></th>
				  <th><strong>Deskripsi</strong></th>
				  <th width="100"><strong>Status</strong></th>
				  <th width="100"><strong>Completion Date</strong></th>
				</tr>';
				foreach($security as $se) { 
					$html .= '<tr>
						<td>';
					if(!empty($se['picture']) && @getimagesize($this->config->paths->html.'/images/bm/'.$se['picture'])) {
						$html .= '<img src="'.$this->config->general->url.'images/bm/'.str_replace("Security.","Security_thumb.",$se['picture']).'" height="50px" />';
					}
					$html .= '</td><td>'.$se['location'].'</td>
						<td>'.$se['description'].'</td>
						<td>'.$se['status'].'</td>
						<td>'.$se['completion_date'].'</td>
					</tr>';
				}
				$html .= '</table>';
			}
			else
			{
				$html .= 'No Security Issue<br>&nbsp;<br>';
			}	
			$html .= '<h4>Housekeeping</h4>';
			if(!empty($housekeeping)) { 
				$html .= '<table width="100%" cellspacing="6" cellpadding="5" align="center" border="1" valign="top">
				<tr bgcolor="#afd9af">
				  <th width="150"><strong>Foto</strong></th>
				  <th width="100"><strong>Lokasi</strong></th>
				  <th><strong>Deskripsi</strong></th>
				  <th width="100"><strong>Status</strong></th>
				  <th width="100"><strong>Completion Date</strong></th>
				</tr>';
				foreach($housekeeping as $h) { 
					$html .= '<tr>
						<td>';
					if(!empty($h['picture']) && @getimagesize($this->config->paths->html.'/images/bm/'.$h['picture'])) {
						$html .= '<img src="'.$this->config->general->url.'images/bm/'.str_replace("Housekeeping.","Housekeeping_thumb.",$h['picture']).'" height="50px" />';
					}
					$html .= '</td><td>'.$h['location'].'</td>
						<td>'.$h['description'].'</td>
						<td>'.$h['status'].'</td>
						<td>'.$h['completion_date'].'</td>
					</tr>';
				}
				$html .= '</table>';
			}	
			else
			{
				$html .= 'No Housekeeping Issue<br>&nbsp;<br>';
			}	
			$html .= '<h4>Parking &amp; Traffic</h4>';
			if(!empty($parking)) { 
				$html .= '<table width="100%" cellspacing="6" cellpadding="5" align="center" border="1" valign="top">
				<tr bgcolor="#afd9af">
				  <th width="150"><strong>Foto</strong></th>
				  <th width="100"><strong>Lokasi</strong></th>
				  <th><strong>Deskripsi</strong></th>
				  <th width="100"><strong>Status</strong></th>
				  <th width="100"><strong>Completion Date</strong></th>
				</tr>';
				foreach($parking as $p) { 
					$html .= '<tr>
						<td>';
					if(!empty($p['picture']) && @getimagesize($this->config->paths->html.'/images/bm/'.$p['picture'])) {
						$html .= '<img src="'.$this->config->general->url.'images/bm/'.str_replace("Parking_Traffic.","Parking_Traffic_thumb.",$p['picture']).'" height="50px" />';
					}
					$html .= '</td><td>'.$p['location'].'</td>
						<td>'.$p['description'].'</td>
						<td>'.$p['status'].'</td>
						<td>'.$p['completion_date'].'</td>
					</tr>';
				}
				$html .= '</table>';
			}	
			else
			{
				$html .= 'No Parking & Traffic Issue<br>&nbsp;<br>';
			}	
			$html .= '<h4>Resident Relations</h4>';
			if(!empty($resident)) { 
				$html .= '<table width="100%" cellspacing="6" cellpadding="5" align="center" border="1" valign="top">
				<tr bgcolor="#afd9af">
				  <th width="150"><strong>Foto</strong></th>
				  <th width="100"><strong>Lokasi</strong></th>
				  <th><strong>Deskripsi</strong></th>
				  <th width="100"><strong>Status</strong></th>
				  <th width="100"><strong>Completion Date</strong></th>
				</tr>';
				foreach($resident as $r) { 
					$html .= '<tr>
						<td>';
					if(!empty($r['picture']) && @getimagesize($this->config->paths->html.'/images/bm/'.$r['picture'])) {
						$html .= '<img src="'.$this->config->general->url.'images/bm/'.str_replace("Resident_Relations.","Resident_Relations_thumb.",$r['picture']).'" height="50px" />';
					}
					$html .= '</td><td>'.$r['location'].'</td>
						<td>'.$r['description'].'</td>
						<td>'.$r['status'].'</td>
						<td>'.$r['completion_date'].'</td>
					</tr>';
				}
				$html .= '</table>';
			}	
			else
			{
				$html .= 'No Resident Relations Issue<br>&nbsp;<br>';
			}	
			$html .= '<h4>Building Service</h4>';
			if(!empty($building_service)) { 
				$html .= '<table width="100%" cellspacing="6" cellpadding="5" align="center" border="1" valign="top">
				<tr bgcolor="#afd9af">
				  <th width="150"><strong>Foto</strong></th>
				  <th width="100"><strong>Lokasi</strong></th>
				  <th><strong>Deskripsi</strong></th>
				  <th width="100"><strong>Status</strong></th>
				  <th width="100"><strong>Completion Date</strong></th>
				</tr>';
				foreach($building_service as $bs) { 
					$html .= '<tr>
						<td>';
					if(!empty($bs['picture']) && @getimagesize($this->config->paths->html.'/images/bm/'.$bs['picture'])) {
						$html .= '<img src="'.$this->config->general->url.'images/bm/'.str_replace("Building_Service.","Building_Service_thumb.",$bs['picture']).'" height="50px" />';
					}
					$html .= '</td><td>'.$bs['location'].'</td>
						<td>'.$bs['description'].'</td>
						<td>'.$bs['status'].'</td>
						<td>'.$bs['completion_date'].'</td>
					</tr>';
				}
				$html .= '</table>';
			}
			else
			{
				$html .= 'No Building Service Issue<br>&nbsp;<br>';
			}	
			$html .= '<h3>SUMMARY WO/WR TENANT &amp; INTERNAL</h3>
		<h4>WO Internal</h4>
		<table width="100%" cellspacing="6" cellpadding="5" align="center" border="1" valign="top">
			<tr bgcolor="#afd9af">
			  <th rowspan="2"><strong>Department</strong></th>
			  <th rowspan="2"><strong>No. of Req. WO per Today</strong></th>
			  <th rowspan="2"><strong>Completed WO per Today</strong></th>
			  <th rowspan="2"><strong>No. of Outstanding WO per Today</strong></th>
			  <th colspan="2"><strong>Accumulate</strong></th>
			</tr>
			<tr bgcolor="#afd9af">
			  <th><strong>Previous Outstanding</strong></th>
			  <th><strong>Total Outstanding</strong></th>
			</tr>
			<tr>
				<td>Engineering</td>
				<td>'.$bm['internal_engineering_req_wo'].'</td>
				<td>'.$bm['internal_engineering_completed_wo'].'</td>
				<td>'.$bm['internal_engineering_outstanding_wo'].'</td>
				<td>'.$bm['internal_engineering_prev_outstanding'].'</td>
				<td>'.$bm['internal_engineering_total_outstanding'].'</td>
			</tr>
			<tr>
				<td>BS / Civil</td>
				<td>'.$bm['internal_bs_civil_req_wo'].'</td>
				<td>'.$bm['internal_bs_civil_completed_wo'].'</td>
				<td>'.$bm['internal_bs_civil_outstanding_wo'].'</td>
				<td>'.$bm['internal_bs_civil_prev_outstanding'].'</td>
				<td>'.$bm['internal_bs_civil_total_outstanding'].'</td>
			</tr>
			<tr>
				<td>Housekeeping</td>
				<td>'.$bm['internal_housekeeping_req_wo'].'</td>
				<td>'.$bm['internal_housekeeping_completed_wo'].'</td>
				<td>'.$bm['internal_housekeeping_outstanding_wo'].'</td>
				<td>'.$bm['internal_housekeeping_prev_outstanding'].'</td>
				<td>'.$bm['internal_housekeeping_total_outstanding'].'</td>
			</tr>
			<tr>
				<td>Parking</td>
				<td>'.$bm['internal_parking_req_wo'].'</td>
				<td>'.$bm['internal_parking_completed_wo'].'</td>
				<td>'.$bm['internal_parking_outstanding_wo'].'</td>
				<td>'.$bm['internal_parking_prev_outstanding'].'</td>
				<td>'.$bm['internal_parking_total_outstanding'].'</td>
			</tr>
			<tr>
				<td>Other</td>
				<td>'.$bm['internal_other_req_wo'].'</td>
				<td>'.$bm['internal_other_completed_wo'].'</td>
				<td>'.$bm['internal_other_outstanding_wo'].'</td>
				<td>'.$bm['internal_other_prev_outstanding'].'</td>
				<td>'.$bm['internal_other_total_outstanding'].'</td>
			</tr>
		</table>
			
		<h4>WO / WR Tenants</h4>
		<table width="100%" cellspacing="6" cellpadding="5" align="center" border="1" valign="top">
			<tr bgcolor="#afd9af">
			  <th rowspan="2"><strong>Department</strong></th>
			  <th rowspan="2"><strong>No. of Req. WO per Today</strong></th>
			  <th rowspan="2"><strong>Completed WO per Today</strong></th>
			  <th rowspan="2"><strong>No. of Outstanding WO per Today</strong></th>
			  <th colspan="2"><strong>Accumulate</strong></th>
			</tr>
			<tr bgcolor="#afd9af">
			  <th><strong>Previous Outstanding</strong></th>
			  <th><strong>Total Outstanding</strong></th>
			</tr>
			<tr>
				<td>Engineering</td>
				<td>'.$bm['tenant_engineering_req_wo'].'</td>
				<td>'.$bm['tenant_engineering_completed_wo'].'</td>
				<td>'.$bm['tenant_engineering_outstanding_wo'].'</td>
				<td>'.$bm['tenant_engineering_prev_outstanding'].'</td>
				<td>'.$bm['tenant_engineering_total_outstanding'].'</td>
			</tr>
			<tr>
				<td>BS / Civil</td>
				<td>'.$bm['tenant_bs_civil_req_wo'].'</td>
				<td>'.$bm['tenant_bs_civil_completed_wo'].'</td>
				<td>'.$bm['tenant_bs_civil_outstanding_wo'].'</td>
				<td>'.$bm['tenant_bs_civil_prev_outstanding'].'</td>
				<td>'.$bm['tenant_bs_civil_total_outstanding'].'</td>
			</tr>
			<tr>
				<td>Housekeeping</td>
				<td>'.$bm['tenant_housekeeping_req_wo'].'</td>
				<td>'.$bm['tenant_housekeeping_completed_wo'].'</td>
				<td>'.$bm['tenant_housekeeping_outstanding_wo'].'</td>
				<td>'.$bm['tenant_housekeeping_prev_outstanding'].'</td>
				<td>'.$bm['tenant_housekeeping_total_outstanding'].'</td>
			</tr>
			<tr>
				<td>Parking</td>
				<td>'.$bm['tenant_parking_req_wo'].'</td>
				<td>'.$bm['tenant_parking_completed_wo'].'</td>
				<td>'.$bm['tenant_parking_outstanding_wo'].'</td>
				<td>'.$bm['tenant_parking_prev_outstanding'].'</td>
				<td>'.$bm['tenant_parking_total_outstanding'].'</td>
			</tr>
			<tr>
				<td>Other</td>
				<td>'.$bm['tenant_other_req_wo'].'</td>
				<td>'.$bm['tenant_other_completed_wo'].'</td>
				<td>'.$bm['tenant_other_outstanding_wo'].'</td>
				<td>'.$bm['tenant_other_prev_outstanding'].'</td>
				<td>'.$bm['tenant_other_total_outstanding'].'</td>
			</tr>
		</table>
		
		<h3>JUMLAH PETUGAS</h3>
		<h4>A. Inhouse</h4>
		<table width="100%" cellspacing="6" cellpadding="5" align="center" border="1" valign="top">
			<tr bgcolor="#afd9af">
			  <th width="120" rowspan="2"><strong>Divisi</strong></th>
			  <th colspan="6"><strong>Jumlah</strong></th>
			  <th rowspan="2"><strong>Keterangan</strong></th>
			</tr>
			<tr bgcolor="#afd9af">
				<th width="65"><strong>Shift 1</strong></th>
				<th width="65"><strong>Middle</strong></th>
				<th width="65"><strong>Shift 2</strong></th>
				<th width="65"><strong>Shift 3</strong></th>
				<th width="65"><strong>Off</strong></th>
				<th width="65"><strong>Absent</strong></th>
			</tr>
			<tr>
				<td>Engineering</td>
				<td>'.$bm['inhouse_engineering_shift1'].'</td>
				<td>'.$bm['inhouse_engineering_middle'].'</td>
				<td>'.$bm['inhouse_engineering_shift2'].'</td>
				<td>'.$bm['inhouse_engineering_shift3'].'</td>
				<td>'.$bm['inhouse_engineering_off'].'</td>
				<td>'.$bm['inhouse_engineering_absent'].'</td>
				<td>'.$bm['inhouse_engineering_keterangan'].'</textarea></td>
			</tr>
			<tr>
				<td>BS / Civil</td>
				<td>'.$bm['inhouse_bs_civil_shift1'].'</td>
				<td>'.$bm['inhouse_bs_civil_middle'].'</td>
				<td>'.$bm['inhouse_bs_civil_shift2'].'</td>
				<td>'.$bm['inhouse_bs_civil_shift3'].'</td>
				<td>'.$bm['inhouse_bs_civil_off'].'</td>
				<td>'.$bm['inhouse_bs_civil_absent'].'</td>
				<td>'.$bm['inhouse_bs_civil_keterangan'].'</textarea></td>
			</tr>
			<tr>
				<td>Housekeeping</td>
				<td>'.$bm['inhouse_housekeeping_shift1'].'</td>
				<td>'.$bm['inhouse_housekeeping_middle'].'</td>
				<td>'.$bm['inhouse_housekeeping_shift2'].'</td>
				<td>'.$bm['inhouse_housekeeping_shift3'].'</td>
				<td>'.$bm['inhouse_housekeeping_off'].'</td>
				<td>'.$bm['inhouse_housekeeping_absent'].'</td>
				<td>'.$bm['inhouse_housekeeping_keterangan'].'</textarea></td>
			</tr>
			<tr>
				<td>Parking</td>
				<td>'.$bm['inhouse_parking_shift1'].'</td>
				<td>'.$bm['inhouse_parking_middle'].'</td>
				<td>'.$bm['inhouse_parking_shift2'].'</td>
				<td>'.$bm['inhouse_parking_shift3'].'</td>
				<td>'.$bm['inhouse_parking_off'].'</td>
				<td>'.$bm['inhouse_parking_absent'].'</td>
				<td>'.$bm['inhouse_parking_keterangan'].'</textarea></td>
			</tr>
			<tr>
				<td>Other</td>
				<td>'.$bm['inhouse_other_shift1'].'</td>
				<td>'.$bm['inhouse_other_middle'].'</td>
				<td>'.$bm['inhouse_other_shift2'].'</td>
				<td>'.$bm['inhouse_other_shift3'].'</td>
				<td>'.$bm['inhouse_other_off'].'</td>
				<td>'.$bm['inhouse_other_absent'].'</td>
				<td>'.$bm['inhouse_other_keterangan'].'</textarea></td>
			</tr>
		</table>
		
		<h4>B. Outsource</h4>
		<table width="100%" cellspacing="6" cellpadding="5" align="center" border="1" valign="top">
			<tr bgcolor="#afd9af">
			  <th width="120" rowspan="2"><strong>Divisi</strong></th>
			  <th colspan="6"><strong>Jumlah</strong></th>
			  <th rowspan="2"><strong>Keterangan</strong></th>
			</tr>
			<tr bgcolor="#afd9af">
				<th width="65"><strong>Shift 1</strong></th>
				<th width="65"><strong>Middle</strong></th>
				<th width="65"><strong>Shift 2</strong></th>
				<th width="65"><strong>Shift 3</strong></th>
				<th width="65"><strong>Off</strong></th>
				<th width="65"><strong>Absent</strong></th>
			</tr>
			<tr>
				<td>Engineering</td>
				<td>'.$bm['outsource_engineering_shift1'].'</td>
				<td>'.$bm['outsource_engineering_middle'].'</td>
				<td>'.$bm['outsource_engineering_shift2'].'</td>
				<td>'.$bm['outsource_engineering_shift3'].'</td>
				<td>'.$bm['outsource_engineering_off'].'</td>
				<td>'.$bm['outsource_engineering_absent'].'</td>
				<td>'.$bm['outsource_engineering_keterangan'].'</textarea></td>
			</tr>
			<tr>
				<td>BS / Civil</td>
				<td>'.$bm['outsource_bs_civil_shift1'].'</td>
				<td>'.$bm['outsource_bs_civil_middle'].'</td>
				<td>'.$bm['outsource_bs_civil_shift2'].'</td>
				<td>'.$bm['outsource_bs_civil_shift3'].'</td>
				<td>'.$bm['outsource_bs_civil_off'].'</td>
				<td>'.$bm['outsource_bs_civil_absent'].'</td>
				<td>'.$bm['outsource_bs_civil_keterangan'].'</textarea></td>
			</tr>
			<tr>
				<td>Housekeeping</td>
				<td>'.$bm['outsource_housekeeping_shift1'].'</td>
				<td>'.$bm['outsource_housekeeping_middle'].'</td>
				<td>'.$bm['outsource_housekeeping_shift2'].'</td>
				<td>'.$bm['outsource_housekeeping_shift3'].'</td>
				<td>'.$bm['outsource_housekeeping_off'].'</td>
				<td>'.$bm['outsource_housekeeping_absent'].'</td>
				<td>'.$bm['outsource_housekeeping_keterangan'].'</textarea></td>
			</tr>
			<tr>
				<td>Parking</td>
				<td>'.$bm['outsource_parking_shift1'].'</td>
				<td>'.$bm['outsource_parking_middle'].'</td>
				<td>'.$bm['outsource_parking_shift2'].'</td>
				<td>'.$bm['outsource_parking_shift3'].'</td>
				<td>'.$bm['outsource_parking_off'].'</td>
				<td>'.$bm['outsource_parking_absent'].'</td>
				<td>'.$bm['outsource_parking_keterangan'].'</textarea></td>
			</tr>
			<tr>
				<td>Other</td>
				<td>'.$bm['outsource_other_shift1'].'</td>
				<td>'.$bm['outsource_other_middle'].'</td>
				<td>'.$bm['outsource_other_shift2'].'</td>
				<td>'.$bm['outsource_other_shift3'].'</td>
				<td>'.$bm['outsource_other_off'].'</td>
				<td>'.$bm['outsource_other_absent'].'</td>
				<td>'.$bm['outsource_other_keterangan'].'</textarea></td>
			</tr>
		</table>
		
		<table width="100%" cellspacing="6" cellpadding="5" align="center" border="1" valign="top">
			<tr bgcolor="#afd9af">
				<th width="120"><strong>TOTAL</strong></th>
				<th width="65"><strong>'.$bm['total_shift1'].'</strong></th>
				<th width="65"><strong>'.$bm['total_middle'].'</strong></th>
				<th width="65"><strong>'.$bm['total_shift2'].'</strong></th>
				<th width="65"><strong>'.$bm['total_shift3'].'</strong></th>
				<th width="65"><strong>'.$bm['total_off'].'</strong></th>
				<th width="65"><strong>'.$bm['total_absent'].'</strong></th>
				<th><strong>'.$bm['total_keterangan'].'</strong></th>
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
						<td><a href="'.$this->baseUrl.'/default/attachment/openattachment/c/9/f/'.$att['filename'].'">'.$att['description'].'</a></td>
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
	
	
	function getcommentsbyreportidAction()
	{
		$params = $this->_getAllParams();
		$this->view->ident = $this->ident;
		$category = $this->loadModel('category');
		$bmCommentsTable = $this->loadModel('bmcomments');
		$comments = $bmCommentsTable->getCommentsByReportId($params['id']);
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
		$bmCommentsTable = $this->loadModel('bmcomments');
		$params['user_id'] = $this->ident['user_id'];
		$params['site_id'] = $this->site_id;

		if($_FILES["attachment"]["size"] > 0)
		{
			$ext = explode(".",$_FILES["attachment"]['name']);
			$filename = "bm_".date("YmdHis").".".$ext[count($ext)-1];
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
				$bmCommentsTable->addComment($params);
			}		
		}
		else{
			$bmCommentsTable->addComment($params);
		}

		$allParams = $params;

		Zend_Loader::LoadClass('bmClass', $this->modelDir);
		$bmClass = new bmClass();
		$bm = $bmClass->getReportById($params['report_id']);
		
		$datetime = explode(" ",$bm['created_date']);
		$date = explode("-",$datetime[0]);
		$r_date = mktime(0, 0, 0, intval($date[1]), intval($date[2]), intval($date[0]));
		$report_date = date("l, j F Y", $r_date);
		
		$categoryClass = $this->loadModel('category');
		$category = $categoryClass->getCategoryById('9');	

		
		$botToken = '476346623:AAFlB9X5-bShAkdTK9ziNDrfZ0TCePXl2cY';
		$website="https://api.telegram.org/bot".$botToken;
		//$chatId=$category['telegram_channel_id'];  //Receiver Chat Id 
		$chatId=$category['site_'.$this->site_id];  //Receiver Chat Id 

		if(!empty($params['filename'])) $attachmenttext = '
attachment : '.$this->config->general->url."comments/".$params['filename'];

		$txt = '[NEW COMMENT] 
'.$this->ident['name']." : ".$params['comment'].$attachmenttext.'

[BUILDING MANAGER REPORT]
Report Date : '.$report_date;
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
		$logData['action'] = "Add Comment to BM Daily Report";
		$logData['data'] = json_encode($allParams);
		$logsTable->insertLogs($logData);

		echo $params['report_id'];
	}	
	
	function getupdatedcommentsAction()
	{
		$params = $this->_getAllParams();
		$params['pagesize'] = 10;
	
		Zend_Loader::LoadClass('bmClass', $this->modelDir);
		$bmClass = new bmClass();
		
		$data= array();
		$bmReports = $bmClass->getReports($params);	
		$commentsTable = $this->loadModel('bmcomments');
		$i=0;
		foreach($bmReports as $s) {
			$data[$i]['report_id'] = $s['report_id'];
			$comments = $commentsTable->getCommentsByReportId($s['report_id'], '3');
			
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
	
	function addissueAction() {
		$params = $this->_getAllParams();
		
		Zend_Loader::LoadClass('bmClass', $this->modelDir);
		$bmClass = new bmClass();
		
		if(strtolower($params['issue_type']) == "utility") $issue_id = $bmClass->addUtilityIssue($params);
		else if(strtolower($params['issue_type']) == "safety") $issue_id = $bmClass->addSafetyIssue($params);
		else if(strtolower($params['issue_type']) == "security") $issue_id = $bmClass->addSecurityIssue($params);
		else if(strtolower($params['issue_type']) == "housekeeping") $issue_id = $bmClass->addHousekeepingIssue($params);
		else if(strtolower($params['issue_type']) == "parking_traffic") $issue_id = $bmClass->addParkingIssue($params);
		else if(strtolower($params['issue_type']) == "resident_relations") $issue_id = $bmClass->addResidentIssue($params);
		else if(strtolower($params['issue_type']) == "building_service") $issue_id = $bmClass->addBuildingServiceIssue($params);
		
		if(!empty($_FILES["pic"]))
		{
			$ext = explode(".",$_FILES["pic"]['name']);
			$filename = $issue_id."_".$params['issue_type'].".".$ext[count($ext)-1];
			$datafolder = $this->config->paths->html."/images/bm/";
			if(move_uploaded_file($_FILES["pic"]["tmp_name"], $datafolder.$filename))
			{
				if(strtolower($params['issue_type']) == "utility") $bmClass->updateUtilityFileName($issue_id,'picture', $filename);
				else if(strtolower($params['issue_type']) == "safety") $bmClass->updateSafetyFileName($issue_id,'picture', $filename);
				else if(strtolower($params['issue_type']) == "security") $bmClass->updateSecurityFileName($issue_id,'picture', $filename);
				else if(strtolower($params['issue_type']) == "housekeeping") $bmClass->updateHousekeepingFileName($issue_id,'picture', $filename);
				else if(strtolower($params['issue_type']) == "parking_traffic") $bmClass->updateParkingFileName($issue_id,'picture', $filename);
				else if(strtolower($params['issue_type']) == "resident_relations") $bmClass->updateResidentFileName($issue_id,'picture', $filename);
				else if(strtolower($params['issue_type']) == "building_service") $bmClass->updateBuildingServiceFileName($issue_id,'picture', $filename);
				
				$magickPath = "/usr/bin/convert";
				$new_file_thumb = $issue_id."_".$params['issue_type']."_thumb.".$ext[count($ext)-1];
				/*** create thumbnail image ***/
				exec($magickPath . ' ' . $datafolder.$filename . ' -resize 128x128 ' . $datafolder.$new_file_thumb);
				/*** resize image if size greater than 500 Kb ***/
				if(filesize($datafolder.$filename) > 500000) exec($magickPath . ' ' . $datafolder.$filename . ' -resize 800x800\> ' . $datafolder.$filename);
			}
		}

		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "Add Issue to BM Daily Report";
		$logData['data'] = json_encode($params);
		$logsTable->insertLogs($logData);
		
		$this->_response->setRedirect($this->baseUrl.'/default/bm/page2/id/'.$params['report_id']);
		$this->_response->sendResponse();
		exit();
	}
	
	public function getissuebyidAction() {
		$params = $this->_getAllParams();
		Zend_Loader::LoadClass('bmClass', $this->modelDir);
		$bmClass = new bmClass();
		
		if(!empty($params['id'])) 
		{
			if(strtolower($params['it']) == "utility")  echo json_encode($bmClass->getUtilityIssueById($params['id']));
			else if(strtolower($params['it']) == "safety")  echo json_encode($bmClass->getSafetyIssueById($params['id']));
			else if(strtolower($params['it']) == "security") echo json_encode($bmClass->getSecurityIssueById($params['id']));
			else if(strtolower($params['it']) == "housekeeping") echo json_encode($bmClass->getHousekeepingIssueById($params['id']));
			else if(strtolower($params['it']) == "parking_traffic") echo json_encode($bmClass->getParkingIssueById($params['id']));
			else if(strtolower($params['it']) == "resident_relations") echo json_encode($bmClass->getResidentIssueById($params['id']));
			else if(strtolower($params['it']) == "building_service") echo json_encode($bmClass->getBuildingServiceIssueById($params['id']));
		}
	}
	
	public function deleteissuebyidAction() {
		$params = $this->_getAllParams();
		Zend_Loader::LoadClass('bmClass', $this->modelDir);
		$bmClass = new bmClass();
		
		$bmClass->deleteIssueById($params['id'], $params['it']);

		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "Delete Issue from BM Daily Report";
		$logData['data'] = json_encode($params);
		$logsTable->insertLogs($logData);
		
		$this->_response->setRedirect($this->baseUrl.'/default/bm/page2/id/'.$params['bm_report_id']);
		$this->_response->sendResponse();
		exit();
	}
	
	function addattachmentAction()
	{
		$params = $this->_getAllParams();
		
		Zend_Loader::LoadClass('bmClass', $this->modelDir);
		$bmClass = new bmClass();
		
		$attachment_id = $bmClass->addAttachment($params);
		
		if(!empty($_FILES["attachment_file"]))
		{
			$ext = explode(".",$_FILES["attachment_file"]['name']);
			$filename = $attachment_id.".".$ext[count($ext)-1];
			$datafolder = $this->config->paths->html."/attachment/bm/";
			if(move_uploaded_file($_FILES["attachment_file"]["tmp_name"], $datafolder.$filename))
			{
				$bmClass->updateAttachment($attachment_id,'filename', $filename);
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
		$logData['action'] = "Add Attachment to BM Daily Report";
		$logData['data'] = json_encode($params);
		$logsTable->insertLogs($logData);

		$this->_response->setRedirect($this->baseUrl.'/default/bm/page2/id/'.$params['report_id']);
		$this->_response->sendResponse();
		exit();
	
	}	
	
	public function getattachmentbyidAction() {
		$params = $this->_getAllParams();
		Zend_Loader::LoadClass('bmClass', $this->modelDir);
		$bmClass = new bmClass();
		
		if(!empty($params['id'])) 
		{
			echo json_encode($bmClass->getAttachmentById($params['id']));
		}
	}
	
	public function deleteattachmentbyidAction() {
		$params = $this->_getAllParams();
		Zend_Loader::LoadClass('bmClass', $this->modelDir);
		$bmClass = new bmClass();
		
		$bmClass->deleteAttachmentById($params['id']);

		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "Delete Attachment from BM Daily Report";
		$logData['data'] = json_encode($params);
		$logsTable->insertLogs($logData);
		
		$this->_response->setRedirect($this->baseUrl.'/default/bm/page2/id/'.$params['bm_report_id']);
		$this->_response->sendResponse();
		exit();
	}
}

?>