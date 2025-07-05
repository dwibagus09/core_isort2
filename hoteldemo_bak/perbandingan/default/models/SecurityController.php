<?php
require_once('actionControllerBase.php');

class SecurityController extends actionControllerBase
{
    public function dashboardAction() {
		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "View Security Dashboard";
		$logData['data'] = "View Security Dashboard";
		$logsTable->insertLogs($logData);	

		$this->renderTemplate('security_dashboard.tpl'); 
	}
	
	public function masterplandashboardAction() {
		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "View Security Master Plan Dashboard";
		$logData['data'] = "View Security Master Plan Dashboard";
		$logsTable->insertLogs($logData);	

		$this->renderTemplate('security_masterplan_dashboard.tpl'); 
	}
	
	public function addAction() {
		$this->view->ident = $this->ident;
		if($this->teacher) $this->view->title = "Add SPV Daily Report";
		else $this->view->title = "Add SPV Daily Report";
		
		/*Zend_Loader::LoadClass('equipmentClass', $this->modelDir);
		$equipmentClass = new equipmentClass();
		$vendors = $equipmentClass->getVendorSecurityEquipments();
		
		if(!empty($vendors))
		{
			foreach($vendors as $v)
			{
				if(strtolower($v['vendor']) == 'spd') $vendor[0] = $v['vendor'];
				else $vendor[1] = $v['vendor'];
			}
		}*/
		Zend_Loader::LoadClass('vendorClass', $this->modelDir);
		$vendorClass = new vendorClass();
		$this->view->vendor = $vendorClass->getVendor($this->site_id);
		Zend_Loader::LoadClass('shiftClass', $this->modelDir);
		$shiftClass = new shiftClass();
		$this->view->shift = $shiftClass->getShift();

		if(date("G") >= 7 && date("G") < 15) $security['shift'] = 1; 
		elseif(date("G") >= 15 && date("G") < 23) $security['shift'] = 2; 
		else $security['shift'] = 3; 

		$this->view->security = $security;
		
		if($security['shift'] == 1)
		{
		    $startdate = date("Y-m-d")." 07:00:00";
		    $enddate = date("Y-m-d")." 15:00:00";
		}
		if($security['shift'] == 2)
		{
		    $startdate = date("Y-m-d")." 15:00:00";
		    $enddate = date("Y-m-d")." 23:00:00";
		}
		if($security['shift'] == 3)
		{
		    $startdate =  date("Y-m-d",mktime(0, 0, 0, date("m"), date("d")-1, date("Y")))." 23:00:00";
		    $enddate = date("Y-m-d")." 07:00:00";
		}
		
		Zend_Loader::LoadClass('issueClass', $this->modelDir);
		$issueClass = new issueClass();
		$issues = $issueClass->getIssuesByShift(1, $this->site_id, $startdate, $enddate);
		
		if(!empty($issues))
		{
		    $issues_by_type = array();
		    foreach($issues as $is)
		    {
		        $issuedatetime = explode(" ", $is['issue_date']);
				$is['date_time'] = date("j M Y", strtotime($issuedatetime[0]))." ".$issuedatetime[1];
				
			    if($is['issue_date'] > "2019-10-23 14:30:00")
    			{
    				$issuedate = explode("-",$issuedatetime[0]);
    				$imageURL = "/images/issues/".$issuedate[0]."/";
    			}
    			else
    				$imageURL = "/images/issues/";
    			
    			$pic = explode(".", $is['picture']);
				$is['large_pic'] = $this->baseUrl.$imageURL.$pic[0]."_large.".$pic[1];
				$is['thumb_pic'] = $this->baseUrl.$imageURL.$pic[0]."_thumb.".$pic[1];
				
		        $issues_by_type[$is['issue_type_id']][] = $is;
		    }
		}
		$this->view->issuesbytype = $issues_by_type;
		
		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "Add Spv Security Daily Report";
		$logData['data'] = "Opening the form page";
		$logsTable->insertLogs($logData);	

		$this->renderTemplate('form_daily_security.tpl'); 
	}
	
	public function insertAction() {
		$params = $this->_getAllParams();
		$params['user_id'] = $this->ident['user_id'];
		Zend_Loader::LoadClass('securityClass', $this->modelDir);
		$securityClass = new securityClass();
		$report = $securityClass->getSecurityReportByShift(date("Y-m-d"), $params['shift'], $this->site_id);
		if(empty($params['security_id']) && !empty($report))
		{
			$this->view->title = "Add Security Report";
			$this->view->message="Report is already exist for this shift";
			$this->view->security = $params; 
			Zend_Loader::LoadClass('shiftClass', $this->modelDir);
			$shiftClass = new shiftClass();
			$this->view->shift = $shiftClass->getShift();

			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "Add Spv Security Daily Report - Report is already exist";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);	

			$this->renderTemplate('form_daily_security.tpl'); 
			exit();
		}
		else
		{
			$params['role_id'] = $this->ident['role_id'];
			$params['created_by'] = $report['user_id'];
			if(!empty($params['security_id']))	$security_id = $securityClass->updateSecurity($params);
			else $security_id = $securityClass->addSecurity($params);
			$i = 0;
			
			$dt['chief_security_report_id'] = $params['chief_security_report_id'];
			$dt['issue_type'] = $issue_type;
			$dt['time'] = $params['time-sr'][$j];
			$dt['detail'] = $params['description-sr'][$j];
			$dt['status'] = $params['status-sr'][$j];
			
			//$securityClass->deleteSpecificReportBySecurityId($security_id);
			
			//$securityClass->deleteDefectListBySecurityId($security_id);
			if(!empty($params['id-issue-defect-list']))
			{
				foreach($params['id-issue-defect-list'] as $issue_id_defect_list)
				{
					$dt['security_id'] = $security_id;
					$dt['sdl_id'] = $params['id-defect-list'][$i];
					$dt['area'] = $params['area-defect-list'][$i];
					$dt['detail'] = $params['detil-defect-list'][$i];
					$dt['issue_id'] = $issue_id_defect_list;
					$dt['status'] = $dt['follow_up'] = $params['followup-defect-list'][$i];
					//$dt['issue_type'] = '4';
					if(!empty($dt['issue_id'])) {
						$securityClass->addDefectList($dt);
						//$securityClass->addSpecificReport($dt);
					}
					$i++;
				}
			}
			
			$j = 0;
			//$securityClass->deleteIncidentBySecurityId($security_id);
			if(!empty($params['id-issue-incident']))
			{
				foreach($params['id-issue-incident'] as $issue_id_incident)
				{
					$dt2['security_id'] = $security_id;
					$dt2['incident_id'] = $params['id-incident'][$j];
					$dt2['issue_id'] = $issue_id_incident;
					$dt2['status'] = $params['status-incident'][$j];
					$dt2['issue_type'] = '1';
					if(!empty($dt2['issue_id'] ))
					{
						$securityClass->addIncident($dt2);					
						//$securityClass->addSpecificReport($dt2);
					}
					$j++;
				}
			}
			
			$k = 0;
			//$securityClass->deleteGlitchBySecurityId($security_id);
			if(!empty($params['id-issue-glitch']))
			{
				foreach($params['id-issue-glitch'] as $issue_id_glitch)
				{
					$dt3['security_id'] = $security_id;
					$dt3['glitch_id'] = $params['id-glitch'][$k];
					$dt3['issue_id'] = $issue_id_glitch;
					$dt3['status'] = $params['status-glitch'][$k];
					//$dt3['issue_type'] = '2';
					if(!empty($dt3['issue_id']))
					{
						$securityClass->addGlitch($dt3);					
						//$securityClass->addSpecificReport($dt3);
					}
					$k++;
				}
			}
			
			$l = 0;
			//$securityClass->deleteLostFoundBySecurityId($security_id);
			if(!empty($params['id-issue-lost-found']))
			{
				foreach($params['id-issue-lost-found'] as $issue_id_lost_found)
				{
					$dt4['security_id'] = $security_id;
					$dt4['lost_found_id'] = $params['id-lost-found'][$l];
					$dt4['issue_id'] = $issue_id_lost_found;
					$dt4['status'] = $params['status-lost-found'][$l];
					$dt4['issue_type'] = '3';
					if(!empty($dt4['issue_id']))
					{
						$securityClass->addLostFound($dt4);
						//$securityClass->addSpecificReport($dt4);
					}
					$l++;
				}
			}

			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "Add Spv Daily Report is successful";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);	
			
			$this->_response->setRedirect($this->baseUrl.'/default/security/page2/id/'.$security_id);
			$this->_response->sendResponse();
			exit();
		}
	}
	
	public function page2Action() {
		$params = $this->_getAllParams();
		
		Zend_Loader::LoadClass('securityClass', $this->modelDir);
		$securityClass = new securityClass();
		
		$security = $securityClass->getSecurityReportById($params['id']);
		$datetime = explode(" ",$security['created_date']);
		$date = explode("-",$datetime[0]);
		$r_date = mktime(0, 0, 0, intval($date[1]), intval($date[2]), intval($date[0]));
		$security['report_date'] = date("l, j F Y", $r_date);
		$this->view->security = $security;
		
		Zend_Loader::LoadClass('shiftClass', $this->modelDir);
		$shiftClass = new shiftClass();
		$this->view->shift = $shiftClass->getShift();
		
		$this->view->attachment = $securityClass->getSpvAttachments($params['id']);

		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "Add Spv Security Daily Report";
		$logData['data'] = "Opening page 2";
		$logsTable->insertLogs($logData);	
		
		$this->renderTemplate('form_daily_security2.tpl'); 
	}
	
	public function viewAction() {
		$params = $this->_getAllParams();
		Zend_Loader::LoadClass('securityClass', $this->modelDir);
		$securityClass = new securityClass();
		Zend_Loader::LoadClass('securitycommentsClass', $this->modelDir);
		$securitycommentsClass = new securitycommentsClass();
		if(empty($params['start'])) $params['start'] = '0';
		$params['pagesize'] = 10;
		$this->view->start = $params['start'];
		$params['site_id'] = $this->site_id;
		$security = $securityClass->getSecurityReports($params);
		foreach($security as &$s)
		{
			$report_date = explode(" ",$s['created_date']);
			$arr_date = explode("-",$report_date[0]);
			if($s['created_date'] >= date("Y-m-d", mktime(0, 0, 0, date("m"), date("d")-1,   date("Y")))) $s['allowEdit'] = 1;
			else $s['allowEdit'] = 0;
			$s['day_date'] = date("l, j F Y", mktime(0, 0, 0, $arr_date[1], $arr_date[2], $arr_date[0]));
			$s['report_date'] = $report_date[0];
			$s['comments'] = $securitycommentsClass->getCommentsByReportDate($report_date[0], '3', $this->site_id);
		}
		$this->view->security = $security;
		
		$totalSpvReport = $securityClass->getTotalSpvReport($this->site_id);
		
		if($totalSpvReport['total'] > 10)
		{
			if($params['start'] >= 10)
			{
				$this->view->firstPageUrl = "/default/security/view";
				$this->view->prevUrl = "/default/security/view/start/".($params['start']-$params['pagesize']);
			}
			if($params['start'] < (floor(($totalSpvReport['total']-1)/10)*10))
			{
				$this->view->nextUrl = "/default/security/view/start/".($params['start']+$params['pagesize']);
				$this->view->lastPageUrl = "/default/security/view/start/".(floor(($totalSpvReport['total']-1)/10)*10);
			}
		}

		$this->view->curPage = ($params['start']/$params['pagesize'])+1;
		$this->view->totalPage = ceil($totalSpvReport['total']/$params['pagesize']);
		$this->view->startRec = $params['start'] + 1;
		$endRec = $params['start'] + $params['pagesize'];
		if($totalSpvReport['total'] >=  $endRec) $this->view->endRec =  $endRec;
		else $this->view->endRec =  $totalSpvReport['total'];		
		$this->view->totalRec = $totalSpvReport['total'];

		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "View Spv Security Daily Report";
		$logData['data'] = json_encode($params);
		$logsTable->insertLogs($logData);	
		
		
    	$this->renderTemplate('view_daily_security.tpl');  
	}
	
	public function editAction() {
		$this->view->ident = $this->ident;
		$params = $this->_getAllParams();
		Zend_Loader::LoadClass('securityClass', $this->modelDir);
		$securityClass = new securityClass();
		$security = $securityClass->getSecurityReportById($params['id']);
		
		$datetime = explode(" ",$security['created_date']);
		$date = explode("-",$datetime[0]);
		$r_date = mktime(0, 0, 0, intval($date[1]), intval($date[2]), intval($date[0]));
		$security['report_date'] = date("l, j F Y", $r_date);
		$this->view->security = $security;
		
		Zend_Loader::LoadClass('shiftClass', $this->modelDir);
		$shiftClass = new shiftClass();
		$this->view->shift = $shiftClass->getShift();
		
		/*$security['defect_list'] = $securityClass->getSpecificReportBySecurityIdIssueType($params['id'], '4');
		$security['incident'] = $securityClass->getSpecificReportBySecurityIdIssueType($params['id'], '1');
		$security['glitch'] = $securityClass->getSpecificReportBySecurityIdIssueType($params['id'], '2');
		$security['lost_found'] = $securityClass->getSpecificReportBySecurityIdIssueType($params['id'], '3');*/
		
		if($security['shift'] == 1)
		{
		    $startdate = $datetime[0]." 07:00:00";
		    $enddate = $datetime[0]." 15:00:00";
		}
		if($security['shift'] == 2)
		{
		    $startdate = $datetime[0]." 15:00:00";
		    $enddate = $datetime[0]." 23:00:00";
		}
		if($security['shift'] == 3)
		{
		    $startdate =  date("Y-m-d",mktime(0, 0, 0, $date[1], $date[2]-1, $date[0]))." 23:00:00";
		    $enddate = $datetime[0]." 07:00:00";
		}
		
	    Zend_Loader::LoadClass('issueClass', $this->modelDir);
		$issueClass = new issueClass();
		
		$defect_list = $issueClass->getIssuesByTypeShift(4, 1, $this->site_id, $startdate, $enddate);
		
		$issues_by_type = array();
		if(!empty($defect_list))
		{
		    foreach($defect_list as $dl)
		    {
		        $issuedatetime = explode(" ", $dl['issue_date']);
				$dl['date_time'] = date("j M Y", strtotime($issuedatetime[0]))." ".$issuedatetime[1];
				
			    if($dl['issue_date'] > "2019-10-23 14:30:00")
    			{
    				$issuedate = explode("-",$issuedatetime[0]);
    				$imageURL = "/images/issues/".$issuedate[0]."/";
    			}
    			else
    				$imageURL = "/images/issues/";
    			
    			$pic = explode(".", $dl['picture']);
				$dl['large_pic'] = $this->baseUrl.$imageURL.$pic[0]."_large.".$pic[1];
				$dl['thumb_pic'] = $this->baseUrl.$imageURL.$pic[0]."_thumb.".$pic[1];
				
				$dl['status'] = $dl['follow_up'];
		        $issues_by_type[$dl['issue_type_id']][] = $dl;
		    }
		}
		
		$incident = $issueClass->getIssuesByTypeShift(1, 1, $this->site_id, $startdate, $enddate);
		if(!empty($incident))
		{
		    foreach($incident as $inc)
		    {
		        $issuedatetime = explode(" ", $inc['issue_date']);
				$inc['date_time'] = date("j M Y", strtotime($issuedatetime[0]))." ".$issuedatetime[1];
				
			    if($inc['issue_date'] > "2019-10-23 14:30:00")
    			{
    				$issuedate = explode("-",$issuedatetime[0]);
    				$imageURL = "/images/issues/".$issuedate[0]."/";
    			}
    			else
    				$imageURL = "/images/issues/";
    			
    			$pic = explode(".", $inc['picture']);
				$inc['large_pic'] = $this->baseUrl.$imageURL.$pic[0]."_large.".$pic[1];
				$inc['thumb_pic'] = $this->baseUrl.$imageURL.$pic[0]."_thumb.".$pic[1];
				
		        $issues_by_type[$inc['issue_type_id']][] = $inc;
		    }
		}
		
		$glitch = $issueClass->getIssuesByTypeShift(2, 1, $this->site_id, $startdate, $enddate);
		
		if(!empty($glitch))
		{
		    foreach($glitch as $gl)
		    {
		        $issuedatetime = explode(" ", $gl['issue_date']);
				$gl['date_time'] = date("j M Y", strtotime($issuedatetime[0]))." ".$issuedatetime[1];
				
			    if($gl['issue_date'] > "2019-10-23 14:30:00")
    			{
    				$issuedate = explode("-",$issuedatetime[0]);
    				$imageURL = "/images/issues/".$issuedate[0]."/";
    			}
    			else
    				$imageURL = "/images/issues/";
    			
    			$pic = explode(".", $gl['picture']);
				$gl['large_pic'] = $this->baseUrl.$imageURL.$pic[0]."_large.".$pic[1];
				$gl['thumb_pic'] = $this->baseUrl.$imageURL.$pic[0]."_thumb.".$pic[1];
				
		        $issues_by_type[$gl['issue_type_id']][] = $gl;
		    }
		}
		
		$lost_found = $issueClass->getIssuesByTypeShift(3, 1, $this->site_id, $startdate, $enddate);
		if(!empty($lost_found))
		{
		    foreach($lost_found as $lf)
		    {
		        $issuedatetime = explode(" ", $lf['issue_date']);
				$lf['date_time'] = date("j M Y", strtotime($issuedatetime[0]))." ".$issuedatetime[1];
				
			    if($lf['issue_date'] > "2019-10-23 14:30:00")
    			{
    				$issuedate = explode("-",$issuedatetime[0]);
    				$imageURL = "/images/issues/".$issuedate[0]."/";
    			}
    			else
    				$imageURL = "/images/issues/";
    			
    			$pic = explode(".", $lf['picture']);
				$lf['large_pic'] = $this->baseUrl.$imageURL.$pic[0]."_large.".$pic[1];
				$lf['thumb_pic'] = $this->baseUrl.$imageURL.$pic[0]."_thumb.".$pic[1];
				
		        $issues_by_type[$lf['issue_type_id']][] = $lf;
		    }
		}
		
	    $this->view->issuesbytype = $issues_by_type;
		
		//$this->view->attachment = $securityClass->getSpvAttachments($params['id']);
		
		/*Zend_Loader::LoadClass('equipmentClass', $this->modelDir);
		$equipmentClass = new equipmentClass();
		$vendors = $equipmentClass->getVendorSecurityEquipments();
		
		if(!empty($vendors))
		{
			foreach($vendors as $v)
			{
				if(strtolower($v['vendor']) == 'spd') $vendor[0] = $v['vendor'];
				else $vendor[1] = $v['vendor'];
			}
		}
		$this->view->vendor = $vendor;*/

		Zend_Loader::LoadClass('vendorClass', $this->modelDir);
		$vendorClass = new vendorClass();
		$this->view->vendor = $vendorClass->getVendor($this->site_id);

		$this->view->editMode = 1;
		
		if($this->teacher) $this->view->title = "Edit SPV Daily Report";
		else $this->view->title = "Edit SPV Daily Report";
 
		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "Edit Spv Security Daily Report";
		$logData['data'] = json_encode($params);
		$logsTable->insertLogs($logData);

		$this->renderTemplate('form_daily_security.tpl');  
	}
	
	public function deleteAction() {
		$params = $this->_getAllParams();
		Zend_Loader::LoadClass('securityClass', $this->modelDir);
		$securityClass = new securityClass();
		
		$securityClass->deleteSecurityById($params['id']);
		$securityClass->deleteDefectListBySecurityId($params['id']);
		$securityClass->deleteIncidentBySecurityId($params['id']);
		$securityClass->deleteGlitchBySecurityId($params['id']);
		$securityClass->deleteLostFoundBySecurityId($params['id']);
		Zend_Loader::LoadClass('securitycommentsClass', $this->modelDir);
		$securitycommentsClass = new securitycommentsClass();
		$securitycommentsClass->deleteCommentsBySecurityId($params['id']);

		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "Delete Spv Security Daily Report";
		$logData['data'] = json_encode($params);
		$logsTable->insertLogs($logData);
		
		self::viewAction();
	}

	public function viewspvdetailreportAction() {
		$params = $this->_getAllParams();

		if(!empty($params['id']))
		{
			Zend_Loader::LoadClass('securityClass', $this->modelDir);
			$securityClass = new securityClass();

			$security = $securityClass->getSecurityReportById($params['id']);

			$datetime = explode(" ",$security['created_date']);
			$date = explode("-",$datetime[0]);
			$r_date = mktime(0, 0, 0, intval($date[1]), intval($date[2]), intval($date[0]));
			$report_date = date("l, j F Y", $r_date);
			$security['date'] = $datetime[0];

			if($security['shift'] == 1) $security['shift_name'] = "Pagi, 07:00 - 15:00 WIB";
			else if($security['shift'] == 2) $security['shift_name'] = "Siang, 15:00 - 23:00 WIB";
			else if($security['shift'] == 3) $security['shift_name'] = "Malam, 23:00 - 07:00 WIB";
			
			/*$security['defect_list'] = $securityClass->getSpecificReportBySecurityIdIssueType($params['id'], '4');
			$security['incident'] = $securityClass->getSpecificReportBySecurityIdIssueType($params['id'], '1');
			$security['glitch'] = $securityClass->getSpecificReportBySecurityIdIssueType($params['id'], '2');
			$security['lost_found'] = $securityClass->getSpecificReportBySecurityIdIssueType($params['id'], '3');*/
			
			if($security['shift'] == 1)
    		{
    		    $startdate = $datetime[0]." 07:00:00";
    		    $enddate = $datetime[0]." 15:00:00";
    		}
    		if($security['shift'] == 2)
    		{
    		    $startdate = $datetime[0]." 15:00:00";
    		    $enddate = $datetime[0]." 23:00:00";
    		}
    		if($security['shift'] == 3)
    		{
    		    $startdate =  date("Y-m-d",mktime(0, 0, 0, $date[1], $date[2]-1, $date[0]))." 23:00:00";
    		    $enddate = $datetime[0]." 07:00:00";
    		}
			
			Zend_Loader::LoadClass('issueClass', $this->modelDir);
	    	$issueClass = new issueClass();
			
			$defect_list = $issueClass->getIssuesByTypeShift(4, 1, $this->site_id, $startdate, $enddate);
		    
    		if(!empty($defect_list))
    		{
    		    foreach($defect_list as &$dl)
    		    {
    		        $issuedatetime = explode(" ", $dl['issue_date']);
    				$dl['date_time'] = date("j M Y", strtotime($issuedatetime[0]))." ".$issuedatetime[1];
    				
    			    if($dl['issue_date'] > "2019-10-23 14:30:00")
        			{
        				$issuedate = explode("-",$issuedatetime[0]);
        				$imageURL = "/images/issues/".$issuedate[0]."/";
        			}
        			else
        				$imageURL = "/images/issues/";
        			
        			$pic = explode(".", $dl['picture']);
    				$dl['large_pic'] = $this->baseUrl.$imageURL.$pic[0]."_large.".$pic[1];
    				$dl['thumb_pic'] = $this->baseUrl.$imageURL.$pic[0]."_thumb.".$pic[1];
    				
    				$dl['status'] = $dl['follow_up'];
    		    }
    		}
    		
    		$security['defect_list'] = $defect_list;
    		
    		$incident = $issueClass->getIssuesByTypeShift(1, 1, $this->site_id, $startdate, $enddate);
    		if(!empty($incident))
    		{
    		    foreach($incident as &$inc)
    		    {
    		        $issuedatetime = explode(" ", $inc['issue_date']);
    				$inc['date_time'] = date("j M Y", strtotime($issuedatetime[0]))." ".$issuedatetime[1];
    				
    			    if($inc['issue_date'] > "2019-10-23 14:30:00")
        			{
        				$issuedate = explode("-",$issuedatetime[0]);
        				$imageURL = "/images/issues/".$issuedate[0]."/";
        			}
        			else
        				$imageURL = "/images/issues/";
        			
        			$pic = explode(".", $inc['picture']);
    				$inc['large_pic'] = $this->baseUrl.$imageURL.$pic[0]."_large.".$pic[1];
    				$inc['thumb_pic'] = $this->baseUrl.$imageURL.$pic[0]."_thumb.".$pic[1];
    				
    		    }
    		}
    		
    		$security['incident'] = $incident;
    		
    		$glitch = $issueClass->getIssuesByTypeShift(2, 1, $this->site_id, $startdate, $enddate);
    		
    		if(!empty($glitch))
    		{
    		    foreach($glitch as &$gl)
    		    {
    		        $issuedatetime = explode(" ", $gl['issue_date']);
    				$gl['date_time'] = date("j M Y", strtotime($issuedatetime[0]))." ".$issuedatetime[1];
    				
    			    if($gl['issue_date'] > "2019-10-23 14:30:00")
        			{
        				$issuedate = explode("-",$issuedatetime[0]);
        				$imageURL = "/images/issues/".$issuedate[0]."/";
        			}
        			else
        				$imageURL = "/images/issues/";
        			
        			$pic = explode(".", $gl['picture']);
    				$gl['large_pic'] = $this->baseUrl.$imageURL.$pic[0]."_large.".$pic[1];
    				$gl['thumb_pic'] = $this->baseUrl.$imageURL.$pic[0]."_thumb.".$pic[1];
    				
    		    }
    		}
    		$security['glitch'] = $glitch;
    		
    		$lost_found = $issueClass->getIssuesByTypeShift(3, 1, $this->site_id, $startdate, $enddate);
    		if(!empty($lost_found))
    		{
    		    foreach($lost_found as &$lf)
    		    {
    		        $issuedatetime = explode(" ", $lf['issue_date']);
    				$lf['date_time'] = date("j M Y", strtotime($issuedatetime[0]))." ".$issuedatetime[1];
    				
    			    if($lf['issue_date'] > "2019-10-23 14:30:00")
        			{
        				$issuedate = explode("-",$issuedatetime[0]);
        				$imageURL = "/images/issues/".$issuedate[0]."/";
        			}
        			else
        				$imageURL = "/images/issues/";
        			
        			$pic = explode(".", $lf['picture']);
    				$lf['large_pic'] = $this->baseUrl.$imageURL.$pic[0]."_large.".$pic[1];
    				$lf['thumb_pic'] = $this->baseUrl.$imageURL.$pic[0]."_thumb.".$pic[1];
    				
    		    }
    		}
    		$security['lost_found'] = $lost_found;
			
			$this->view->security = $security;

			$this->view->attachment = $securityClass->getSpvAttachments($params['id']);

			Zend_Loader::LoadClass('vendorClass', $this->modelDir);
			$vendorClass = new vendorClass();
			$this->view->vendor = $vendorClass->getVendor($this->site_id);
		
			$this->view->ident = $this->ident;

			Zend_Loader::LoadClass('securitycommentsClass', $this->modelDir);
			$securitycommentsClass = new securitycommentsClass();
			$this->view->comments = $securitycommentsClass->getCommentsByReportDate($datetime[0], 0, $this->site_id, 'asc');

			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "View Spv Security Daily Report Detail";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);

			$this->renderTemplate('view_spv_security_detail_report.tpl');   
		}
	}

	public function downloadspvreportAction() {		
		$params = $this->_getAllParams();
		if(!empty($params['id']))
		{			
			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "Export Spv Security Daily Report to PDF";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);
			$this->exportspvsecuritytopdf($params['id'], "", 1);
		}		
	}
	
	public function exporttopdf2Action() {
		$params = $this->_getAllParams();
		Zend_Loader::LoadClass('securityClass', $this->modelDir);
		$securityClass = new securityClass();
		$security = $securityClass->getSecurityReportById($params['id']);
		$datetime = explode(" ",$security['created_date']);
		$date = explode("-",$datetime[0]);
		$r_date = mktime(0, 0, 0, intval($date[1]), intval($date[2]), intval($date[0]));
		$report_date = date("l, j F Y", $r_date);
		
		
		/*$defect_list = $securityClass->getDefectListBySecurityId($params['id']);
		$incident = $securityClass->getIncidentBySecurityId($params['id']);
		$glitch = $securityClass->getGlitchBySecurityId($params['id']);
		$lost_found = $securityClass->getLostFoundBySecurityId($params['id']);*/
		$defect_list = $securityClass->getSpecificReportBySecurityIdIssueType($params['id'], '4');
		$incident = $securityClass->getSpecificReportBySecurityIdIssueType($params['id'], '1');
		$glitch = $securityClass->getSpecificReportBySecurityIdIssueType($params['id'], '2');
		$lost_found = $securityClass->getSpecificReportBySecurityIdIssueType($params['id'], '3');
		
		$attachment = $securityClass->getSpvAttachments($params['id']);
		
		/*Zend_Loader::LoadClass('equipmentClass', $this->modelDir);
		$equipmentClass = new equipmentClass();
		$vendors = $equipmentClass->getVendorSecurityEquipments();
		
		if(!empty($vendors))
		{
			foreach($vendors as $v)
			{
				if(strtolower($v['vendor']) == 'spd') $vendor[0] = $v['vendor'];
				else $vendor[1] = $v['vendor'];
			}
		}*/

		Zend_Loader::LoadClass('vendorClass', $this->modelDir);
		$vendorClass = new vendorClass();
		$vendor = $vendorClass->getVendor($this->site_id);

		require('PHPpdf/html2fpdf.php');


		$html= '<html>
		<head>
		<title>Security Supervisor Daily Report</title>
		 
		</head>
		<body>
		<h1>Daily Report</h1>
		<h3>Security</h3>
		'.$security['site_fullname'].'
		
		<h3>1. Date / Shift</h3>
		<table width="100%" cellspacing="6" cellpadding="5" align="center" border="1" valign="top">
			<tr><td><strong>Day / Date</strong></td><td>'.$report_date.'</td></tr>
			<tr><td><strong>Shift</strong></td><td>'.$security['shift'].'</td></tr>
			<tr><td></td><td>'.$security['name'].'</td></tr>
		</table>
		
		<h3>2. Man Power</h3>
		<table width="100%" cellspacing="6" cellpadding="5" align="center" border="1" valign="top">
			<tr bgcolor="#afd9af"><th><strong>IN HOUSE</strong></th><th><strong>'.strtoupper($vendor[0]['vendor_name']).'</strong></th><th><strong>'.strtoupper($vendor[1]['vendor_name']).'</strong></th></tr>
			<tr>
				<td><strong>Spv</strong> : '.$security['supervisor'].'<br><strong>Posko</strong> : '.$security['staff_posko'].'<br><strong>CCTV</strong> : '.$security['staff_cctv'].'<br><strong>Safety</strong> : '.$security['safety'].'</td>
				<td><strong>Waka</strong> : '.$security['chief_spd'].'<br><strong>Panwas</strong> : '.$security['panwas_spd'].'<br><strong>Danton / Danru</strong> : '.$security['danton_spd'].'<br><strong>Jumlah</strong> : '.$security['jumlah_spd'].'</td><td><strong>Chief</strong> : '.$security['chief_army'].'<br><strong>Panwas</strong> : '.$security['panwas_army'].'<br><strong>Danton / Danru</strong> : '.$security['danton_army'].'<br><strong>Jumlah</strong> : '.$security['jumlah_army'].'</td>
			</tr>
		</table>
		
		<h3>3. Briefing</h3>
		<div>'.str_replace("<br />", "<br>",nl2br($security['briefing'])).'</div>
		 
		<h3>4. Defect List</h3>';
		
		if(!empty($defect_list))
		{
			$html .= '<table width="100%" cellspacing="6" cellpadding="5" align="center" border="1" valign="top">
			<tr bgcolor="#afd9af"><th>Area</th><th>Details</th><th>Follow up</th></tr>';
			foreach($defect_list as $dl)
			{
				$html .= '<tr><td>'.$dl['area'].'</td><td>'.$dl['detail'].'</td><td>'.$dl['status'].'</td></tr>';
			}
			$html .= '</table>';
		}
		else
		{
			$html .= 'No Defect List<br>&nbsp;<br>';
		}
		$html .= '<h3>5. Incident Report</h3>';
		
		if(!empty($incident))
		{
			$html .= '<table width="100%" cellspacing="6" cellpadding="5" align="center" border="1" valign="top">
			<tr bgcolor="#afd9af"><th><strong>Date &amp; Time</strong></th><th><strong>Description</strong></th><th><strong>Status</strong></th></tr>';
			foreach($incident as $i)
			{
				$issue_date_time = explode(" ",$i['issue_date']);
				$issue_datetime = date("j M Y", strtotime($issue_date_time[0]))." ".$issue_date_time[1];
				$html .= '<tr><td>'.$issue_datetime.'</td><td>'.$i['description'].'</td><td>'.$i['status'].'</td></tr>';
			}
			$html .= '</table>';
		}
		else
		{
			$html .= 'No Incident Report<br>&nbsp;<br>';
		}
		
		$html .= '<h3>6. Glitch</h3>';
		
		if(!empty($glitch))
		{
			$html .= '<table width="100%" cellspacing="6" cellpadding="5" align="center" border="1" valign="top">
			<tr bgcolor="#afd9af"><th><strong>Date &amp; Time</strong></th><th><strong>Description</strong></th><th><strong>Status</strong></th></tr>';
			foreach($glitch as $g)
			{
				$issue_date_time = explode(" ",$g['issue_date']);
				$issue_datetime = date("j M Y", strtotime($issue_date_time[0]))." ".$issue_date_time[1];
				$html .= '<tr><td>'.$issue_datetime.'</td><td>'.$g['description'].'</td><td>'.$g['status'].'</td></tr>';
			}
			$html .= '</table>';
		}
		else
		{
			$html .= 'No Glitch Report<br>&nbsp;<br>';
		}
		
		$html .= '<h3>7. Lost & Found</h3>';
		
		if(!empty($lost_found))
		{
			$html .= '<table width="100%" cellspacing="6" cellpadding="5" align="center" border="1" valign="top">
			<tr bgcolor="#afd9af"><th><strong>Date &amp; Time</strong></th><th><strong>Description</strong></th><th><strong>Status</strong></th></tr>';
			foreach($lost_found as $lf)
			{
				$issue_date_time = explode(" ",$lf['issue_date']);
				$issue_datetime = date("j M Y", strtotime($issue_date_time[0]))." ".$issue_date_time[1];
				$html .= '<tr><td>'.$issue_datetime.'</td><td>'.$lf['description'].'</td><td>'.$lf['status'].'</td></tr>';
			}
			$html .= '</table>';
		}
		else
		{
			$html .= 'No Lost & Found Report<br>&nbsp;<br>';
		}
		
		$html .= '<h4>Attachment</h4>			
			<table width="100%" cellspacing="6" cellpadding="5" align="center" border="1" valign="top">
			<tr bgcolor="#afd9af">
				<th width="100"><strong>Filename</strong></th>
				<th><strong>Description</strong></th>
			</tr>';
			if(!empty($attachment)) {
				foreach($attachment as $att) {
					$html .= '<tr>
						<td><a href="'.$this->baseUrl.'/default/attachment/openattachment/c/1/f/'.$att['filename'].'">'.$att['filename'].'</a></td>
						<td>'.$att['description'].'</td>
					</tr>';
				}
			}
			$html .= '</table>';
		
		$html .= '</body>
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
		$pdf->Output("security.pdf","I");

	}
	
	function addattachmentAction()
	{
		$params = $this->_getAllParams();
		
		Zend_Loader::LoadClass('securityClass', $this->modelDir);
		$securityClass = new securityClass();
		
		$attachment_id = $securityClass->addSpvAttachment($params);
		
		if(!empty($_FILES["attachment_file"]))
		{
			$ext = explode(".",$_FILES["attachment_file"]['name']);
			$filename = $attachment_id."_spv.".$ext[count($ext)-1];
			$datafolder = $this->config->paths->html."/attachment/security/";
			if(move_uploaded_file($_FILES["attachment_file"]["tmp_name"], $datafolder.$filename))
			{
				$securityClass->updateSpvAttachment($attachment_id,'filename', $filename);
				
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
		$logData['action'] = "Add Spv Security Daily Report Attachment";
		$logData['data'] = json_encode($params);
		$logsTable->insertLogs($logData);	
		
		$this->_response->setRedirect($this->baseUrl.'/default/security/page2/id/'.$params['report_id']);
		$this->_response->sendResponse();
		exit();
	
	}
	
	public function getattachmentbyidAction() {
		$params = $this->_getAllParams();
		Zend_Loader::LoadClass('securityClass', $this->modelDir);
		$securityClass = new securityClass();
		
		if(!empty($params['id'])) 
		{
			echo json_encode($securityClass->getSpvAttachmentById($params['id']));
		}
	}
	
	public function deleteattachmentbyidAction() {
		$params = $this->_getAllParams();
		Zend_Loader::LoadClass('securityClass', $this->modelDir);
		$securityClass = new securityClass();
		
		$securityClass->deleteSpvAttachmentById($params['id']);

		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "Delete Spv Security Daily Report";
		$logData['data'] = json_encode($params);
		$logsTable->insertLogs($logData);
		
		$this->_response->setRedirect($this->baseUrl.'/default/security/page2/id/'.$params['report_id']);
		$this->_response->sendResponse();
		exit();
	}
	
	public function exporttopdfAction() {
		$params = $this->_getAllParams();
		if(!empty($params['id']))
		{
			Zend_Loader::LoadClass('securityClass', $this->modelDir);
			$securityClass = new securityClass();
			$security = $securityClass->getSecurityReportById($params['id']);

			$params['user_id'] = $this->ident['user_id'];
			$securityClass->addReadSpvReportLog($params);

			$filename = $this->config->paths->html.'/pdf_report/security/' . $this->site_id."_spv_".$params['id'].".pdf";
			if (!file_exists($filename) || date("Y-m-d", mktime(0, 0, 0, date("m"), date("d")-1, date("Y"))) < $security['created_date']) {		
				$this->exportspvsecuritytopdf($params['id']);
			}

			// Header content type 
			header('Content-type: application/pdf'); 				
			header('Content-Disposition: inline; filename="' . $filename . '"'); 				
			// Read the file 
			readfile($filename); 
			exit();
		}
	}
	
	function getcommentsbysecurityidAction()
	{
		$params = $this->_getAllParams();
		$this->view->ident = $this->ident;
		$category = $this->loadModel('category');
		$securityCommentsTable = $this->loadModel('securitycomments');
		$comments = $securityCommentsTable->getCommentsBySecurityId($params['id']);
		$commentText = "";
		if(!empty($comments)) {
			foreach($comments as $comment)
			{
				$commentText .= '<div style="border-bottom:1px solid #ccc; text-align:left; clear:both; margin-bottom:5px; padding-bottom:5px;">'.$comment['comment'].'<br/>';
				if(!empty($comment['filename'])) $commentText .= '<a href="'.$this->baseUrl."/comments/".$comment['filename'].'" target="_blank"><i class="fa fa-paperclip"></i> '.$comment['filename'].'</a>';
				$commentText .= '<div style="font-size:10px; color:#aaa; text-align:right;">'.$comment['comment_date']."</div></div>";
			}
		}
		
		echo $commentText;	
	}
	
	function addcommentAction() {
		$params = $this->_getAllParams();
		
		$commentsTable = $this->loadModel('securitycomments');
		$params['user_id'] = $this->ident['user_id'];
		$params['site_id'] = $this->site_id;

		if($_FILES["attachment"]["size"] > 0)
		{
			$ext = explode(".",$_FILES["attachment"]['name']);
			$filename = "sec_".date("YmdHis").".".$ext[count($ext)-1];
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
		
		/*Zend_Loader::LoadClass('securityClass', $this->modelDir);
		$securityClass = new securityClass();
		$security = $securityClass->getSecurityReportById($params['security_id']);*/
		
		$date = explode("-",$params['report_date']);
		$r_date = mktime(0, 0, 0, intval($date[1]), intval($date[2]), intval($date[0]));
		$report_date = date("l, j F Y", $r_date);
		
		$categoryClass = $this->loadModel('category');
		$category = $categoryClass->getCategoryById('1');	
			
		/*if($this->site_id < 4)	$botToken = '476346623:AAFlB9X5-bShAkdTK9ziNDrfZ0TCePXl2cY';
		else $botToken = '716005387:AAFhUdDGlXjK5YFMYqwx_lCpqpp8760S_TE';*/
		$botToken = '476346623:AAFlB9X5-bShAkdTK9ziNDrfZ0TCePXl2cY';
		$website="https://api.telegram.org/bot".$botToken;
		//$chatId=$category['telegram_channel_id'];  //Receiver Chat Id 
		$chatId=$category['site_'.$this->site_id];  //Receiver Chat Id 

		if(!empty($params['filename'])) $attachmenttext = '
attachment : '.$this->config->general->url."comments/".$params['filename'];

		$txt = '[NEW COMMENT] 
'.$this->ident['name']." : ".$params['comment'].$attachmenttext.'

[SECURITY REPORT]
Report Date : '.$report_date.'
Report Link : '.$this->config->general->url."default/security/viewchiefdetailreport/s/".$params['site_id']."/dt/".$params["report_date"];
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
		//echo json_encode($params);

		$allParams['telegram'] = $params;
		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "Add Comment to daily Spv Security Report";
		$logData['data'] = json_encode($allParams);
		$logsTable->insertLogs($logData);

		echo $allParams['filename'];
	}	
	
	function getupdatedcommentsAction()
	{
		$params = $this->_getAllParams();
		$params['pagesize'] = 10;
	
		Zend_Loader::LoadClass('securityClass', $this->modelDir);
		$securityClass = new securityClass();
		
		$data= array();
		$security = $securityClass->getSecurityReports($params);	
		$commentsTable = $this->loadModel('securitycomments');
		$i=0;
		foreach($security as $s) {
			$created_date = explode(" ",$s['created_date']);
			$data[$i]['report_date'] = $created_date[0];
			$data[$i]['security_id'] = $s['security_id'];
			//$comments = $commentsTable->getCommentsByChiefSecurityId( $s['chief_security_report_id'], '3');
			$comments = $commentsTable->getCommentsByReportDate($created_date[0], '3', $this->site_id);
			if(!empty($comments)) { 
				$comment_content = "";
				foreach($comments as $comment)
				{
					$comment_content .= '<div style="border-bottom:1px solid #ccc; text-align:left; clear:both; margin-bottom:5px; padding-bottom:5px;"><strong>'.$comment['name'].' :</strong> '.$comment['comment'].'<br/>';
					if(!empty($comment['filename'])) $comment_content .= '<a href="'.$this->baseUrl."/comments/".$comment['filename'].'" target="_blank"><i class="fa fa-paperclip"></i> '.$comment['filename'].'</a>';
					$comment_content .= '<div style="font-size:10px; color:#aaa; text-align:right;">'.$comment['comment_date']."</div></div>";
				}
				$data[$i]['comment'] = $comment_content;
			}
			$i++;
		}
				
		echo json_encode($data);
	}
	
	public function addchiefreportAction() {		
		$trainingActivityTable = $this->loadModel('trainingactivity');
		$this->view->training_activity = $trainingActivityTable->getTrainingActivity();
		
		$issueTypeTable = $this->loadModel('issuetype');
		//$this->view->issue_type = $issueTypeTable->getIssueType('1');
		$this->view->issue_type = $issueTypeTable->getIssueTypeByIds('1, 2, 3, 4');

		$settingTable = $this->loadModel('setting');
		$this->view->setting = $settingTable->getOtherSetting();
		
		$equipmentTable = $this->loadModel('equipment');
		$this->view->equipments = $equipmentTable->getSecurityEquipments();
		
		Zend_Loader::LoadClass('vendorClass', $this->modelDir);
		$vendorClass = new vendorClass();
		$this->view->vendor = $vendorClass->getVendor($this->site_id);

		$this->view->title = "Add Chief Security Daily Report";

		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "Add Chief Security Daily Report";
		$logData['data'] = "Opening the form page";
		$logsTable->insertLogs($logData);	

		$this->renderTemplate('form_chief_security.tpl'); 
	}
	
	public function savechiefreportAction() {
		$params = $this->_getAllParams();
		$params['user_id'] = $this->ident['user_id'];
		Zend_Loader::LoadClass('securityClass', $this->modelDir);
		$securityClass = new securityClass();

		if(!empty($params['report_date']) && $params['report_date'] > "0000-00-00")
		{		
			$params['chief_security_report_id'] = $securityClass->saveChiefReport($params);
			
			/*** Save Morning Security Report Id ***/
			$paramsMorning["shift"] = '1';
			$paramsMorning["supervisor-inhouse"] = $params['supervisor_inhouse_pagi'];
			$paramsMorning["staff-posko-inhouse"] = $params['staff_posko_inhouse_pagi'];
			$paramsMorning["staff-cctv-inhouse"] = $params['staff_cctv_inhouse_pagi'];
			$paramsMorning["safety-inhouse"] = $params['safety_inhouse_pagi'];
			$paramsMorning["briefing"] = $params['morning_briefing1'];
			$paramsMorning["briefing2"] = $params['morning_briefing2'];
			$paramsMorning["briefing3"] = $params['morning_briefing3'];
			$paramsMorning["user_id"] = $params['user_id'];
			$paramsMorning["security_id"] = $params['morning_security_report_id'];
			$paramsMorning["chief_security_report_id"] = $params['chief_security_report_id'];
			$paramsMorning["report_date"] = $params['report_date'];
			if(empty($params['morning_security_report_id'])) $securityClass->addSecurity($paramsMorning, '1');
			elseif(!empty($params['morning_security_report_id'])) $securityClass->updateSecurity($paramsMorning, '1');
			
			/*** Save Afternoon Security Report Id ***/
			$paramsAfternoon["shift"] = '2';
			$paramsAfternoon["supervisor-inhouse"] = $params['supervisor_inhouse_siang'];
			$paramsAfternoon["staff-posko-inhouse"] = $params['staff_posko_inhouse_siang'];
			$paramsAfternoon["staff-cctv-inhouse"] = $params['staff_cctv_inhouse_siang'];
			$paramsAfternoon["safety-inhouse"] = $params['safety_inhouse_siang'];
			$paramsAfternoon["briefing"] = $params['afternoon_briefing1'];
			$paramsAfternoon["briefing2"] = $params['afternoon_briefing2'];
			$paramsAfternoon["briefing3"] = $params['afternoon_briefing3'];
			$paramsAfternoon["user_id"] = $params['user_id'];
			$paramsAfternoon["security_id"] = $params['afternoon_security_report_id'];
			$paramsAfternoon["chief_security_report_id"] = $params['chief_security_report_id'];
			$paramsAfternoon["report_date"] = $params['report_date'];
			if(empty($params['afternoon_security_report_id'])) $securityClass->addSecurity($paramsAfternoon, '1');
			elseif(!empty($params['afternoon_security_report_id'])) $securityClass->updateSecurity($paramsAfternoon, '1');
			
			/*** Save Night Security Report Id ***/
			$paramsNight["shift"] = '3';
			$paramsNight["supervisor-inhouse"] = $params['supervisor_inhouse_malam'];
			$paramsNight["staff-posko-inhouse"] = $params['staff_posko_inhouse_malam'];
			$paramsNight["staff-cctv-inhouse"] = $params['staff_cctv_inhouse_malam'];
			$paramsNight["safety-inhouse"] = $params['safety_inhouse_malam'];
			$paramsNight["briefing"] = $params['night_briefing1'];
			$paramsNight["briefing2"] = $params['night_briefing2'];
			$paramsNight["briefing3"] = $params['night_briefing3'];
			$paramsNight["user_id"] = $params['user_id'];
			$paramsNight["security_id"] = $params['night_security_report_id'];
			$paramsNight["chief_security_report_id"] = $params['chief_security_report_id'];
			$paramsNight["report_date"] = $params['report_date'];
			if(empty($params['night_security_report_id'])) $securityClass->addSecurity($paramsNight, '1');
			elseif(!empty($params['night_security_report_id'])) $securityClass->updateSecurity($paramsNight, '1');
			
			$equipmentTable = $this->loadModel('equipment');
			$equipmentTable->deletePerlengkapanByChiefSecurityId($params['chief_security_report_id']);
			if(!empty($params['id_equipment_list']))
			{
				$i = 0;
				foreach($params['id_equipment_list'] as $id_equipment_list)
				{
					if(!empty($params['total_equipment'][$i]) || !empty($params['ok_condition'][$i]) || !empty($params['bad_condition'][$i]) || !empty($params['description'][$i])) {
						$dt['chief_security_report_id'] = $params['chief_security_report_id'];
						$dt['security_equipment_list_id'] = $id_equipment_list;
						$dt['vendor'] = $params['vendor_equipment'][$i];
						$dt['total_equipment'] = $params['total_equipment'][$i];
						$dt['ok_condition'] = $params['ok_condition'][$i];
						$dt['bad_condition'] = $params['bad_condition'][$i];
						$dt['description'] = $params['description'][$i];
						$equipmentTable->addPerlengkapan($dt);
					}
					$i++;
				}
			}
		}

		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "Save Chief Security Daily Report - page 1";
		$logData['data'] = json_encode($params);
		$logsTable->insertLogs($logData);	

		$this->_response->setRedirect($this->baseUrl.'/default/security/chiefpage2/id/'.$params['chief_security_report_id']);
		$this->_response->sendResponse();
		exit();
	}

	public function chiefpage2Action() {
		$params = $this->_getAllParams();
		
		Zend_Loader::LoadClass('securityClass', $this->modelDir);
		$securityClass = new securityClass();
		
		$spvSecurity = $securityClass->getSecurityReportByChiefId($params['id']);
		$datetime = explode(" ",$spvSecurity[0]['created_date']);
		$date = explode("-",$datetime[0]);
		$r_date = mktime(0, 0, 0, intval($date[1]), intval($date[2]), intval($date[0]));
		$security = $securityClass->getChiefSecurityReportById($params['id']);
		$security['created_date'] = date("l, j F Y", $r_date);
		$security['report_date'] = $datetime[0];
		$sec['morning'] = $securityClass->getSecurityReportByShift($security['report_date'], '1', $this->site_id);
		$sec['afternoon'] = $securityClass->getSecurityReportByShift($security['report_date'], '2', $this->site_id);
		$sec['night'] = $securityClass->getSecurityReportByShift($security['report_date'], '3', $this->site_id);
		if($sec['morning']['chief_security_report_id'] > 0) $sec['chief_security_report_id'] = $sec['morning']['chief_security_report_id'];
		elseif($sec['afternoon']['chief_security_report_id'] > 0) $sec['chief_security_report_id'] = $sec['afternoon']['chief_security_report_id'];
		elseif($sec['night']['chief_security_report_id'] > 0) $sec['chief_security_report_id'] = $sec['night']['chief_security_report_id'];
		$security['morning'] = $sec['morning'];
		$security['afternoon'] = $sec['afternoon'];
		$security['night'] = $sec['night'];		
		$this->view->security = $security;
		
		$settingTable = $this->loadModel('setting');
		$this->view->setting = $settingTable->getOtherSetting();

		
		$trainingTable = $this->loadModel('training');
		$this->view->training_activity = $trainingTable->getTrainingActivity();
		
		if(!empty($security['chief_security_report_id']))
		{
			$this->view->outdoorTraining = $trainingTable->getSecurityTrainingByType($security['chief_security_report_id'],'1');
			$this->view->inHouseTraining = $trainingTable->getSecurityTrainingByType($security['chief_security_report_id'],'2');
		}
		
		$issueTypeTable = $this->loadModel('issuetype');
		//$this->view->issue_type = $issueTypeTable->getIssueType('1');
		$this->view->issue_type = $issueTypeTable->getIssueTypeByIds('1, 2, 3, 4');
		
		$security_ids = "";
		if(!empty($sec['morning']['security_id'])) $security_ids .= $sec['morning']['security_id'].",";
		if(!empty($sec['afternoon']['security_id'])) $security_ids .= $sec['afternoon']['security_id'].",";
		if(!empty($sec['night']['security_id'])) $security_ids .= $sec['night']['security_id'].",";
		$security_ids = substr($security_ids,0,-1);
		$issueTable = $this->loadModel('issue');
		
		/*$specific_report = $securityClass->getSpecificReportByIds($security_ids, $tempId);
		
		foreach($specific_report as &$sr)
		{
			if(!empty($sr['issue_id']))
			{
				$sr['detail'] = $sr['description'];
				$datetime = explode(" ",$sr['issue_date']);
				$sr['time'] = $datetime[1];
			}
		}*/
		
		$startdate =  date("Y-m-d",mktime(0, 0, 0, intval($date[1]), intval($date[2])-1, intval($date[0])))." 23:00:00";
		$enddate = $datetime[0]." 23:00:00";
		
		Zend_Loader::LoadClass('issueClass', $this->modelDir);
		$issueClass = new issueClass();
		$defect_list = $issueClass->getIssuesByTypeShift(4, 1, $this->site_id, $startdate, $enddate);
		$glitch = $issueClass->getIssuesByTypeShift(2, 1, $this->site_id, $startdate, $enddate);
		$lost_found = $issueClass->getIssuesByTypeShift(3, 1, $this->site_id, $startdate, $enddate);
		$incident = $issueClass->getIssuesByTypeShift(1, 1, $this->site_id, $startdate, $enddate);
		$issues = array_merge($defect_list, $glitch, $lost_found, $incident);
		
		if(!empty($issues))
		{
		    foreach($issues as &$is)
		    {
		        $issuedatetime = explode(" ", $is['issue_date']);
				$is['date_time'] = date("j M Y", strtotime($issuedatetime[0]))." ".$issuedatetime[1];
				
			    if($is['issue_date'] > "2019-10-23 14:30:00")
    			{
    				$issuedate = explode("-",$issuedatetime[0]);
    				$imageURL = "/images/issues/".$issuedate[0]."/";
    			}
    			else
    				$imageURL = "/images/issues/";
    			
    			$pic = explode(".", $is['picture']);
				$is['large_pic'] = $this->baseUrl.$imageURL.$pic[0]."_large.".$pic[1];
				$is['thumb_pic'] = $this->baseUrl.$imageURL.$pic[0]."_thumb.".$pic[1];
				if($is['issue_type_id'] == 4) 
				{
				    $is['status'] = $is['follow_up'];
				    $is['security_issue_id'] = $is['sdl_id'];
				}
				if($is['issue_type_id'] == 3) 
				{
				    $is['security_issue_id'] = $is['lost_found_id'];
				}
				if($is['issue_type_id'] == 2) 
				{
				    $is['security_issue_id'] = $is['glitch_id'];
				}
				if($is['issue_type_id'] == 1) 
				{
				    $is['security_issue_id'] = $is['incident_id'];
				}
		    }
		}
		
		$this->view->specific_report = $issues;

		Zend_Loader::LoadClass('shiftClass', $this->modelDir);
		$shiftClass = new shiftClass();
		$this->view->shift = $shiftClass->getShift();

		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "Display Chief Security Daily Report - page 2";
		$logData['data'] = json_encode($params);
		$logsTable->insertLogs($logData);	
				
		$this->renderTemplate('form_chief_security2.tpl'); 
	}

	public function savechiefreport2Action() {
		$params = $this->_getAllParams();
		$params['user_id'] = $this->ident['user_id'];
		Zend_Loader::LoadClass('securityClass', $this->modelDir);
		$securityClass = new securityClass();
		$params['chief_security_report_id'] = $securityClass->saveChiefReport2($params);
		
		$trainingTable = $this->loadModel('training');
		$trainingTable->deleteTrainingByChiefSecurityId($params['chief_security_report_id']);
		if(!empty($params['training_type']))
		{		
			$k = 0;
			foreach($params['training_type'] as $training_type)
			{
				$dt=array();
					$dt2['chief_security_report_id'] = $params['chief_security_report_id'];
					$dt2['training_type'] = $training_type;
					$dt2['training_activity'] = $params['training_activity'][$k];
					$dt2['description_training'] = $params['description_training'][$k];
					$trainingTable->addTraining($dt2);
				$k++;
			}			
		}

		/*$securityClass->deleteSpecificReportByChiefSecurityId($params['chief_security_report_id']);
		$securityClass->deleteSpecificReportBySecurityId($params['morning_security_report_id']);
		$securityClass->deleteSpecificReportBySecurityId($params['afternoon_security_report_id']);
		$securityClass->deleteSpecificReportBySecurityId($params['night_security_report_id']);
		if(!empty($params['issue_type']))
		{		
			$j = 0;
			foreach($params['issue_type'] as $issue_type)
			{
				$dt=array();
				$dt['chief_security_report_id'] = $params['chief_security_report_id'];
				$dt['issue_type'] = $issue_type;
				$dt['time'] = $params['time-sr'][$j];
				$dt['detail'] = $params['description-sr'][$j];
				$dt['status'] = $params['status-sr'][$j];
				$dt['area'] = $params['time-sr'][$j];
				$dt['security_id'] = $params['security-id-sr'][$j];
				$dt['issue_id'] = $params['id-issue-sr'][$j];
				$securityClass->addSpecificReport($dt);
				$j++;
			}			
		}*/
		
		if(!empty($params['id-issue']))
		{
		    $is=0;
			foreach($params['id-issue'] as $issue_id)
			{
				$dt['issue_id'] = $issue_id;
				$dt['status'] = $dt['follow_up'] = $params['status-issue'][$is];
				$dt['issue_type_id'] = $params['issue-type-id'][$is];
				$dt['security_issue_id'] = $params['id-security-issue'][$is];
				$securityClass->updateSecurityIssue($dt);
				$is++;
			}
		}

		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "Save Chief Security Daily Report - page 2";
		$logData['data'] = json_encode($params);
		$logsTable->insertLogs($logData);	
		
		$this->_response->setRedirect($this->baseUrl.'/default/security/chiefpage3/id/'.$params['chief_security_report_id']);
		$this->_response->sendResponse();
		exit();
	}
	
	public function chiefpage3Action() {
		$params = $this->_getAllParams();
		
		Zend_Loader::LoadClass('securityClass', $this->modelDir);
		$securityClass = new securityClass();
		
		$spvSecurity = $securityClass->getSecurityReportByChiefId($params['id']);		
		$datetime = explode(" ",$spvSecurity[0]['created_date']);
		$date = explode("-",$datetime[0]);
		$r_date = mktime(0, 0, 0, intval($date[1]), intval($date[2]), intval($date[0]));
		$security = $securityClass->getChiefSecurityReportById($params['id']);	
		$security['created_date'] = date("l, j F Y", $r_date);
		$security['report_date'] = $datetime[0];
		$this->view->security = $security;
		
		$settingTable = $this->loadModel('setting');
		$this->view->setting = $settingTable->getOtherSetting();
		
		if(!empty($params['id']))
			$this->view->attachment = $securityClass->getChiefAttachments($params['id']);
		
		$attachmentMorning = $attachmentAfternoon = $attachmentNight = array();
		if(!empty($spvSecurity))
		{
			$i = 0;
			foreach($spvSecurity as $sec)
			{
				$attachmentSpv[$i] =  $securityClass->getSpvAttachments($sec['security_id']);
				$i++;
			}			
			$this->view->attachmentSpv = array_merge($attachmentSpv[0], $attachmentSpv[1], $attachmentSpv[2]);
		}

		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "Display Chief Security Daily Report - page 3";
		$logData['data'] = json_encode($params);
		$logsTable->insertLogs($logData);	
		
		$this->renderTemplate('form_chief_security3.tpl'); 
	}
	
	public function viewchiefreportAction() {
		$params = $this->_getAllParams();
		Zend_Loader::LoadClass('securityClass', $this->modelDir);
		$securityClass = new securityClass();
		Zend_Loader::LoadClass('securitycommentsClass', $this->modelDir);
		$securitycommentsClass = new securitycommentsClass();
		if(empty($params['start'])) $params['start'] = '0';
		$params['pagesize'] = 10;
		$this->view->start = $params['start'];
		$security = $securityClass->getChiefSecurityReports($params);
		foreach($security as &$s)
		{
			$date = explode(" ", $s['created_date']);
			$arr_date = explode("-",$date[0]);
			if($s['created_date'] >= date("Y-m-d", mktime(0, 0, 0, date("m"), date("d")-1,   date("Y")))) $s['allowEdit'] = 1;
			else $s['allowEdit'] = 0;
			$s['day_date'] = date("l, j F Y", mktime(0, 0, 0, $arr_date[1], $arr_date[2], $arr_date[0]));
			$s['report_date'] = $date[0];
			$s['comments'] = $securitycommentsClass->getCommentsByReportDate($s['report_date'], '3', $this->site_id);
			//$s['comments'] = $securitycommentsClass->getCommentsByChiefSecurityId($s['chief_security_report_id'], '3');
		}
		$this->view->security = $security;
		
		//$specific_report['glitch'] = $securityClass->getGlitchByDate(date("Y-m-d"));
		
		$totalChiefReport = $securityClass->getTotalChiefReport();
		if($totalChiefReport['total'] > 10)
		{
			if($params['start'] >= 10)
			{
				$this->view->firstPageUrl = "/default/security/viewchiefreport";
				$this->view->prevUrl = "/default/security/viewchiefreport/start/".($params['start']-$params['pagesize']);
			}
			if($params['start'] < (floor(($totalChiefReport['total']-1)/10)*10))
			{
				$this->view->nextUrl = "/default/security/viewchiefreport/start/".($params['start']+$params['pagesize']);
				$this->view->lastPageUrl = "/default/security/viewchiefreport/start/".(floor(($totalChiefReport['total']-1)/10)*10);
			}
		}

		$this->view->curPage = ($params['start']/$params['pagesize'])+1;
		$this->view->totalPage = ceil($totalChiefReport['total']/$params['pagesize']);
		$this->view->startRec = $params['start'] + 1;
		$endRec = $params['start'] + $params['pagesize'];
		if($totalChiefReport['total'] >=  $endRec) $this->view->endRec =  $endRec;
		else $this->view->endRec =  $totalChiefReport['total'];		
		$this->view->totalRec = $totalChiefReport['total'];

		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "View Chief Security Daily Report List";
		$logData['data'] = json_encode($params);
		$logsTable->insertLogs($logData);	
		
    	$this->renderTemplate('view_chief_security.tpl');  
	}
	
	public function editchiefreportAction() {
		$params = $this->_getAllParams();
		Zend_Loader::LoadClass('securityClass', $this->modelDir);
		$securityClass = new securityClass();
		
		$sec['morning'] = $securityClass->getSecurityReportByShift($params['dt'], '1', $this->site_id);
		$sec['afternoon'] = $securityClass->getSecurityReportByShift($params['dt'], '2', $this->site_id);
		$sec['night'] = $securityClass->getSecurityReportByShift($params['dt'], '3', $this->site_id);
		if($sec['morning']['chief_security_report_id'] > 0) $sec['chief_security_report_id'] = $sec['morning']['chief_security_report_id'];
		elseif($sec['afternoon']['chief_security_report_id'] > 0) $sec['chief_security_report_id'] = $sec['afternoon']['chief_security_report_id'];
		elseif($sec['night']['chief_security_report_id'] > 0) $sec['chief_security_report_id'] = $sec['night']['chief_security_report_id'];
		if(!empty($sec['chief_security_report_id'])) $security = $securityClass->getChiefSecurityReportById($sec['chief_security_report_id']);
		$security['morning'] = $sec['morning'];
		$security['afternoon'] = $sec['afternoon'];
		$security['night'] = $sec['night'];
		
		$date = explode("-",$params['dt']);
		$r_date = mktime(0, 0, 0, intval($date[1]), intval($date[2]), intval($date[0]));
		$security['created_date'] = date("l, j F Y", $r_date);
		$security['report_date'] = $params['dt'];
		/*$security['chief_spd'] = $security['morning']['chief_spd'] + $security['afternoon']['chief_spd'] + $security['night']['chief_spd'];
		$security['chief_army'] = $security['morning']['chief_army'] + $security['afternoon']['chief_army'] + $security['night']['chief_army'];
		$security['panwas_spd'] = $security['morning']['panwas_spd'] + $security['afternoon']['panwas_spd'] + $security['night']['panwas_spd'];
		$security['panwas_army'] = $security['morning']['panwas_army'] + $security['afternoon']['panwas_army'] + $security['night']['panwas_army'];
		$security['danton_pagi_spd'] = $security['morning']['danton_spd'] + $security['afternoon']['danton_spd'] + $security['night']['danton_spd'];
		$security['danton_pagi_army'] = $security['morning']['danton_army'] + $security['afternoon']['danton_army'] + $security['night']['danton_army'];*/
		
		$trainingTable = $this->loadModel('training');
		$this->view->training_activity = $trainingTable->getTrainingActivity();
		
		if(!empty($sec['chief_security_report_id']))
		{
			$this->view->outdoorTraining = $trainingTable->getSecurityTrainingByType($sec['chief_security_report_id'],'1');
			$this->view->inHouseTraining = $trainingTable->getSecurityTrainingByType($sec['chief_security_report_id'],'2');
		}
		
		$issueTypeTable = $this->loadModel('issuetype');
		//$this->view->issue_type = $issueTypeTable->getIssueType('1');
		$this->view->issue_type = $issueTypeTable->getIssueTypeByIds('1, 2, 3, 4');
		
		$settingTable = $this->loadModel('setting');
		$this->view->setting = $settingTable->getOtherSetting();
		
		if(empty($sec['chief_security_report_id'])) $tempId = '0';
		else $tempId = $sec['chief_security_report_id'];
		$equipmentTable = $this->loadModel('equipment');
		$this->view->equipments = $equipmentTable->getPerlengkapanByChiefSecurityReport($tempId);
		
		$security_ids = "";
		if(!empty($security['morning']['security_id'])) $security_ids .= $security['morning']['security_id'].",";
		if(!empty($security['afternoon']['security_id'])) $security_ids .= $security['afternoon']['security_id'].",";
		if(!empty($security['night']['security_id'])) $security_ids .= $security['night']['security_id'].",";
		$security_ids = substr($security_ids,0,-1);
		$issueTable = $this->loadModel('issue');
		/*if(!empty($security_ids))
		{
			$incident_report = $issueTable->getIssuesByIds('security_incident',$security_ids);
			$lost_found_report = $issueTable->getIssuesByIds('security_lost_found',$security_ids);
			$glitch = $issueTable->getIssuesByIds('security_glitch',$security_ids);			
			$defect_list = $securityClass->getDefectListByIds($security_ids);
		}
		
		if(!empty($tempId)) $spec_report = $securityClass->getSpecificReportByChiefSecurityReport($tempId);
		$specific_report = array_merge($incident_report,$lost_found_report, $glitch, $defect_list, $spec_report);*/
		
		$specific_report = $securityClass->getSpecificReportByIds($security_ids, $tempId);
		
		foreach($specific_report as &$sr)
		{
			if(!empty($sr['issue_id']))
			{
				$sr['detail'] = $sr['description'];
				$datetime = explode(" ",$sr['issue_date']);
				$sr['time'] = $datetime[1];
			}
		}
		$this->view->specific_report = $specific_report;
		
		Zend_Loader::LoadClass('shiftClass', $this->modelDir);
		$shiftClass = new shiftClass();
		$this->view->shift = $shiftClass->getShift();
		
		/*if(!empty($sec['chief_security_report_id']))
			$this->view->attachment = $securityClass->getChiefAttachments($sec['chief_security_report_id']);
		
		$attachmentMorning = $attachmentAfternoon = $attachmentNight = array();
		if(!empty($sec['morning']['security_id'])) $attachmentMorning = $securityClass->getSpvAttachments($sec['morning']['security_id']);
		if(!empty($sec['afternoon']['security_id'])) $attachmentAfternoon = $securityClass->getSpvAttachments($sec['afternoon']['security_id']);
		if(!empty($sec['night']['security_id'])) $attachmentNight = $securityClass->getSpvAttachments($sec['night']['security_id']);
		$this->view->attachmentSpv = array_merge($attachmentMorning, $attachmentAfternoon, $attachmentNight);*/
		
		/*Zend_Loader::LoadClass('equipmentClass', $this->modelDir);
		$equipmentClass = new equipmentClass();
		$vendors = $equipmentClass->getVendorSecurityEquipments();
		
		if(!empty($vendors))
		{
			foreach($vendors as $v)
			{
				if(strtolower($v['vendor']) == 'spd') $vendor[0] = $v['vendor'];
				else $vendor[1] = $v['vendor'];
			}
		}
		$this->view->vendor = $vendor; */
		Zend_Loader::LoadClass('vendorClass', $this->modelDir);
		$vendorClass = new vendorClass();
		$this->view->vendor = $vendorClass->getVendor($this->site_id);
		
		$this->view->security = $security;
		$this->view->title = "Edit Chief Security Daily Report";

		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "Edit Chief Security Daily Report - page 1";
		$logData['data'] = "Display form - page 1";
		$logsTable->insertLogs($logData);	

		$this->renderTemplate('form_chief_security.tpl');  
	}
	
	public function deletechiefreportAction() {
		$params = $this->_getAllParams();
		Zend_Loader::LoadClass('securityClass', $this->modelDir);
		$securityClass = new securityClass();
		
		$securityClass->deleteChiefSecurityReportById($params['id']);
		$securityClass->deleteSpecificReportByChiefSecurityId($params['id']);
		
		Zend_Loader::LoadClass('equipmentClass', $this->modelDir);
		$equipmentClass = new equipmentClass();
		$equipmentClass->deletePerlengkapanByChiefSecurityId($params['id']);

		Zend_Loader::LoadClass('securitycommentsClass', $this->modelDir);
		$securitycommentsClass = new securitycommentsClass();
		$securitycommentsClass->deleteChiefCommentsById($params['id']);

		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "Delete Chief Security Daily Report";
		$logData['data'] = json_encode($params);
		$logsTable->insertLogs($logData);	
		
		self::viewchiefreportAction();
	}
	
	function getsecuritycommentsbyreportdateAction()
	{
		$params = $this->_getAllParams();
		$this->view->ident = $this->ident;
		$category = $this->loadModel('category');
		$securityCommentsTable = $this->loadModel('securitycomments');
		//$comments = $securityCommentsTable->getCommentsByChiefSecurityId($params['id']);
		$comments = $securityCommentsTable->getCommentsByReportDate($params['report_date'], 0, $this->site_id);
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
	
	function addchiefcommentAction() {
		$allParams = $params = $this->_getAllParams();
		$commentsTable = $this->loadModel('securitycomments');
		$params['user_id'] = $this->ident['user_id'];
		$commentsTable->addChiefComment($params);
		
		Zend_Loader::LoadClass('securityClass', $this->modelDir);
		$securityClass = new securityClass();
		$security = $securityClass->getChiefSecurityReportById($params['chief_security_report_id']);
		
		$datetime = explode(" ",$security['created_date']);
		$date = explode("-",$datetime[0]);
		$r_date = mktime(0, 0, 0, intval($date[1]), intval($date[2]), intval($date[0]));
		$report_date = date("l, j F Y", $r_date);
		
		$categoryClass = $this->loadModel('category');
		$category = $categoryClass->getCategoryById('1');	
					
		/*if($this->site_id < 4)	$botToken = '476346623:AAFlB9X5-bShAkdTK9ziNDrfZ0TCePXl2cY';
		else $botToken = '716005387:AAFhUdDGlXjK5YFMYqwx_lCpqpp8760S_TE';*/
		$botToken = '476346623:AAFlB9X5-bShAkdTK9ziNDrfZ0TCePXl2cY';
		$website="https://api.telegram.org/bot".$botToken;
		//$chatId=$category['telegram_channel_id'];  //Receiver Chat Id 
		$chatId=$category['site_'.$this->site_id];  //Receiver Chat Id 
		$txt = '[NEW COMMENT] 
'.$params['comment'].'

[CHIEF SECURITY REPORT]
Submitted by : '.$security['name'].' 
Date : '.$report_date.'
Report Link : '.$this->config->general->url."default/security/viewchiefdetailreport/s/".$this->site_id."/dt/".$datetime[0];
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
		echo $params['chief_security_report_id'];

		$allParams['telegram'] = $params;
		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "Add comment to Chief Security Daily Report";
		$logData['data'] = json_encode($allParams);
		$logsTable->insertLogs($logData);	
	}	
	
	function getupdatedchiefcommentsAction()
	{
		$params = $this->_getAllParams();
		$params['pagesize'] = 10;
		
		Zend_Loader::LoadClass('securityClass', $this->modelDir);
		$securityClass = new securityClass();
		
		$data= array();
		$security = $securityClass->getChiefSecurityReports($params);	
		$commentsTable = $this->loadModel('securitycomments');
		$i=0;
		foreach($security as $s) {
			$created_date = explode(" ",$s['created_date']);
			$data[$i]['report_date'] = $created_date[0];
			//$comments = $commentsTable->getCommentsByChiefSecurityId( $s['chief_security_report_id'], '3');
			$comments = $commentsTable->getCommentsByReportDate($created_date[0], '3', $this->site_id);
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

	public function viewchiefdetailreportAction() {
		if($this->showChiefSecurity == 1)
		{
			$params = $this->_getAllParams();

			if(!empty($params['dt']))
			{
				Zend_Loader::LoadClass('securityClass', $this->modelDir);
				$securityClass = new securityClass();

				$sec['morning'] = $securityClass->getSecurityReportByShift($params['dt'], '1', $this->site_id);
				$sec['afternoon'] = $securityClass->getSecurityReportByShift($params['dt'], '2', $this->site_id);
				$sec['night'] = $securityClass->getSecurityReportByShift($params['dt'], '3', $this->site_id);

				if($this->showSiteSelection == 1 && !empty($params['s']))
				{
					$siteTable = $this->loadModel('site');
					if($params['s'] != $this->ident['site_id'])
					{
						$siteTable->setSite($params['s']);
						$this->ident['site_id'] = $params['s'];
						$this->_response->setRedirect($this->baseUrl."/default/security/viewchiefdetailreport/dt/".$params['dt']);
						$this->_response->sendResponse();
						exit();
					}
				}

				if(empty($sec['morning']) && empty($sec['afternoon']) && empty($sec['night']))
				{
					$this->_response->setRedirect($this->baseUrl.'/default/security/viewchiefreport');
					$this->_response->sendResponse();
					exit();
				}
				else
				{			
					if($sec['morning']['chief_security_report_id'] > 0) $sec['chief_security_report_id'] = $sec['morning']['chief_security_report_id'];
					elseif($sec['afternoon']['chief_security_report_id'] > 0) $sec['chief_security_report_id'] = $sec['afternoon']['chief_security_report_id'];
					elseif($sec['night']['chief_security_report_id'] > 0) $sec['chief_security_report_id'] = $sec['night']['chief_security_report_id'];
					if(!empty($sec['chief_security_report_id'])) 
					{
						$security = $securityClass->getChiefSecurityReportById($sec['chief_security_report_id']);
						
						$params['user_id'] = $this->ident['user_id'];
						$params['id'] = $sec['chief_security_report_id'];
						$securityClass->addReadChiefReportLog($params);
					}

					$security['morning'] = $sec['morning'];
					$security['afternoon'] = $sec['afternoon'];
					$security['night'] = $sec['night'];
					
					$date = explode("-",$params['dt']);
					$r_date = mktime(0, 0, 0, intval($date[1]), intval($date[2]), intval($date[0]));
					$security['created_date'] = date("l, j F Y", $r_date);
					$security['report_date'] = $params['dt'];
					$this->view->security = $security;

					if(empty($sec['chief_security_report_id'])) $tempId = '0';
					else $tempId = $sec['chief_security_report_id'];
					$equipmentTable = $this->loadModel('equipment');
					$this->view->equipments = $equipmentTable->getPerlengkapanByChiefSecurityReport($tempId);
					
					$trainingTable = $this->loadModel('training');
					
					if(!empty($sec['chief_security_report_id']))
					{
						$this->view->outsourceTraining = $trainingTable->getSecurityTrainingByType($sec['chief_security_report_id'],'1');
						$this->view->inHouseTraining = $trainingTable->getSecurityTrainingByType($sec['chief_security_report_id'],'2');
					}
					
					$settingTable = $this->loadModel('setting');
					$this->view->setting = $settingTable->getOtherSetting();
					
					/*** SPECIFIC REPORT ***/
					
					/*$security_ids = "";
					if(!empty($security['morning']['security_id'])) $security_ids .= $security['morning']['security_id'].",";
					if(!empty($security['afternoon']['security_id'])) $security_ids .= $security['afternoon']['security_id'].",";
					if(!empty($security['night']['security_id'])) $security_ids .= $security['night']['security_id'].",";
					$security_ids = substr($security_ids,0,-1);
					$issueTable = $this->loadModel('issue');
					
					$specific_reports = $securityClass->getSpecificReportByIds($security_ids, $tempId);
					foreach($specific_reports as &$sr)
					{
						if(!empty($sr['issue_id']))
						{
							$sr['detail'] = $sr['description'];
							$datetime = explode(" ",$sr['issue_date']);
							$sr['time'] = $datetime[1];
						}
					}
					$this->view->specific_reports = $specific_reports;*/
					
					$startdate =  date("Y-m-d",mktime(0, 0, 0, intval($date[1]), intval($date[2])-1, intval($date[0])))." 23:00:00";
            		$enddate = $params['dt']." 23:00:00";
            		
            		Zend_Loader::LoadClass('issueClass', $this->modelDir);
            		$issueClass = new issueClass();
            		$defect_list = $issueClass->getIssuesByTypeShift(4, 1, $this->site_id, $startdate, $enddate);
            		$glitch = $issueClass->getIssuesByTypeShift(2, 1, $this->site_id, $startdate, $enddate);
            		$lost_found = $issueClass->getIssuesByTypeShift(3, 1, $this->site_id, $startdate, $enddate);
            		$incident = $issueClass->getIssuesByTypeShift(1, 1, $this->site_id, $startdate, $enddate);
            		$issues = array_merge($defect_list, $glitch, $lost_found, $incident);
            		
            		if(!empty($issues))
            		{
            		    foreach($issues as &$is)
            		    {
            		        $issuedatetime = explode(" ", $is['issue_date']);
            				$is['date_time'] = date("j M Y", strtotime($issuedatetime[0]))." ".$issuedatetime[1];
            				
            			    if($is['issue_date'] > "2019-10-23 14:30:00")
                			{
                				$issuedate = explode("-",$issuedatetime[0]);
                				$imageURL = "/images/issues/".$issuedate[0]."/";
                			}
                			else
                				$imageURL = "/images/issues/";
                			
                			$pic = explode(".", $is['picture']);
            				$is['large_pic'] = $this->baseUrl.$imageURL.$pic[0]."_large.".$pic[1];
            				$is['thumb_pic'] = $this->baseUrl.$imageURL.$pic[0]."_thumb.".$pic[1];
            				if($is['issue_type_id'] == 4) 
            				{
            				    $is['status'] = $is['follow_up'];
            				}
            		    }
            		}
            		$this->view->specific_reports = $issues;

					/*** END OF SPECIFIC REPORT ***/
					$attachment = $attachmentMorning = $attachmentAfternoon = $attachmentNight = array();
					if(!empty($sec['chief_security_report_id'])) $attachment = $securityClass->getChiefAttachments($sec['chief_security_report_id']);
							
					if(!empty($security['morning']['security_id'])) $attachmentMorning = $securityClass->getSpvAttachments($security['morning']['security_id']);
					if(!empty($security['afternoon']['security_id'])) $attachmentAfternoon = $securityClass->getSpvAttachments($security['afternoon']['security_id']);
					if(!empty($security['night']['security_id'])) $attachmentNight = $securityClass->getSpvAttachments($security['night']['security_id']);
					$this->view->attachment = array_merge($attachmentNight, $attachmentMorning, $attachmentAfternoon, $attachment);
					
					Zend_Loader::LoadClass('vendorClass', $this->modelDir);
					$vendorClass = new vendorClass();
					$this->view->vendor = $vendorClass->getVendor($this->site_id);
				
					$this->view->ident = $this->ident;

					Zend_Loader::LoadClass('securitycommentsClass', $this->modelDir);
					$securitycommentsClass = new securitycommentsClass();
					$this->view->comments = $securitycommentsClass->getCommentsByReportDate($params['dt'], 0, $this->site_id, 'asc');

					$logsTable = $this->loadModel('logs');
					$logData['user_id'] = intval($this->ident['user_id']);
					$logData['action'] = "View Chief Security Daily Report Detail";
					$logData['data'] = json_encode($params);
					$logsTable->insertLogs($logData);	

					$this->renderTemplate('view_chief_security_detail_report.tpl');   
				}
			}
		}
		else {
			$this->_response->setRedirect($this->baseUrl);
			$this->_response->sendResponse();
			exit();
		}
	}

	public function downloadchiefreportAction() {		
		$params = $this->_getAllParams();
		if(!empty($params['dt']))
		{			
			require('fpdf/mc_table.php');
			$params = $this->_getAllParams();
			Zend_Loader::LoadClass('securityClass', $this->modelDir);
			$securityClass = new securityClass();

			$sec['morning'] = $securityClass->getSecurityReportByShift($params['dt'], '1', $this->site_id);
			$sec['afternoon'] = $securityClass->getSecurityReportByShift($params['dt'], '2', $this->site_id);
			$sec['night'] = $securityClass->getSecurityReportByShift($params['dt'], '3', $this->site_id);
			
			if($sec['morning']['chief_security_report_id'] > 0) $sec['chief_security_report_id'] = $sec['morning']['chief_security_report_id'];
			elseif($sec['afternoon']['chief_security_report_id'] > 0) $sec['chief_security_report_id'] = $sec['afternoon']['chief_security_report_id'];
			elseif($sec['night']['chief_security_report_id'] > 0) $sec['chief_security_report_id'] = $sec['night']['chief_security_report_id'];

			$filename = $this->site_id."_chief_".str_replace("-","",$params['dt']).".pdf";
	
			if(!empty($sec['chief_security_report_id'])) 
			{
				$security = $securityClass->getChiefSecurityReportById($sec['chief_security_report_id']);
				
				$params['user_id'] = $this->ident['user_id'];
				$params['id'] = $tempId = $sec['chief_security_report_id'];
				$securityClass->addReadChiefReportLog($params);
			}
			else {
				$tempId = '0';
			}
			

			$security['morning'] = $sec['morning'];
			$security['afternoon'] = $sec['afternoon'];
			$security['night'] = $sec['night'];
			
			$date = explode("-",$params['dt']);
			$r_date = mktime(0, 0, 0, intval($date[1]), intval($date[2]), intval($date[0]));
			$security['created_date'] = date("l, j F Y", $r_date);
			$security['report_date'] = $params['dt'];
			
			$equipmentTable = $this->loadModel('equipment');
			$equipments = $equipmentTable->getPerlengkapanByChiefSecurityReport($tempId);
			
			$trainingTable = $this->loadModel('training');
			
			if(!empty($sec['chief_security_report_id']))
			{
				$outsourceTraining = $trainingTable->getSecurityTrainingByType($sec['chief_security_report_id'],'1');
				$inHouseTraining = $trainingTable->getSecurityTrainingByType($sec['chief_security_report_id'],'2');
				if(count($outsourceTraining) > count($inHouseTraining)) $totalTraining = count($outsourceTraining);
				else $totalTraining = count($inHouseTraining);
			}
			
			$settingTable = $this->loadModel('setting');
			$setting = $settingTable->getOtherSetting();
			
			/*** SPECIFIC REPORT ***/
			
			/*$security_ids = "";
			if(!empty($security['morning']['security_id'])) $security_ids .= $security['morning']['security_id'].",";
			if(!empty($security['afternoon']['security_id'])) $security_ids .= $security['afternoon']['security_id'].",";
			if(!empty($security['night']['security_id'])) $security_ids .= $security['night']['security_id'].",";
			$security_ids = substr($security_ids,0,-1);
			$issueTable = $this->loadModel('issue');
			
			$specific_reports = $securityClass->getSpecificReportByIds($security_ids, $tempId);
			foreach($specific_reports as &$sr)
			{
				if(!empty($sr['issue_id']))
				{
					$sr['detail'] = $sr['description'];
					$datetime = explode(" ",$sr['issue_date']);
					$sr['time'] = $datetime[1];
				}
			}*/
			
			$startdate =  date("Y-m-d",mktime(0, 0, 0, intval($date[1]), intval($date[2])-1, intval($date[0])))." 23:00:00";
    		$enddate = $params['dt']." 23:00:00";
    		
    		Zend_Loader::LoadClass('issueClass', $this->modelDir);
    		$issueClass = new issueClass();
    		$defect_list = $issueClass->getIssuesByTypeShift(4, 1, $this->site_id, $startdate, $enddate);
    		$glitch = $issueClass->getIssuesByTypeShift(2, 1, $this->site_id, $startdate, $enddate);
    		$lost_found = $issueClass->getIssuesByTypeShift(3, 1, $this->site_id, $startdate, $enddate);
    		$incident = $issueClass->getIssuesByTypeShift(1, 1, $this->site_id, $startdate, $enddate);
    		$issues = array_merge($defect_list, $glitch, $lost_found, $incident);
    		
    		if(!empty($issues))
    		{
    		    foreach($issues as &$is)
    		    {
    		        $issuedatetime = explode(" ", $is['issue_date']);
    				$is['date_time'] = date("j M Y", strtotime($issuedatetime[0]))." ".$issuedatetime[1];
    				
    			    if($is['issue_date'] > "2019-10-23 14:30:00")
        			{
        				$issuedate = explode("-",$issuedatetime[0]);
        				$imageURL = "/images/issues/".$issuedate[0]."/";
        			}
        			else
        				$imageURL = "/images/issues/";
        			
        			$pic = explode(".", $is['picture']);
    				$is['large_pic'] = $this->baseUrl.$imageURL.$pic[0]."_large.".$pic[1];
    				$is['thumb_pic'] = $this->baseUrl.$imageURL.$pic[0]."_thumb.".$pic[1];
    				if($is['issue_type_id'] == 4) 
    				{
    				    $is['status'] = $is['follow_up'];
    				}
    		    }
    		}
    		$specific_reports = $issues;
			
			/*** END OF SPECIFIC REPORT ***/
			$attachment = $attachmentMorning = $attachmentAfternoon = $attachmentNight = array();
			if(!empty($sec['chief_security_report_id'])) $attachment = $securityClass->getChiefAttachments($sec['chief_security_report_id']);
					
			if(!empty($security['morning']['security_id'])) $attachmentMorning = $securityClass->getSpvAttachments($security['morning']['security_id']);
			if(!empty($security['afternoon']['security_id'])) $attachmentAfternoon = $securityClass->getSpvAttachments($security['afternoon']['security_id']);
			if(!empty($security['night']['security_id'])) $attachmentNight = $securityClass->getSpvAttachments($security['night']['security_id']);
			$attachment = array_merge($attachmentNight, $attachmentMorning, $attachmentAfternoon, $attachment);
			
			Zend_Loader::LoadClass('vendorClass', $this->modelDir);
			$vendorClass = new vendorClass();
			$vendor = $vendorClass->getVendor($this->site_id);

			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "Export Chief Security Daily Report to PDF";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);	

			$pdf=new PDF_MC_Table();
			$pdf->AddPage();
			$pdf->SetTitle($this->ident['initial']." - Chief Security Daily Report");
			$pdf->SetFont('Arial','B',15);
			$pdf->Write(10,'Chief Security Report');
			$pdf->ln();
			$pdf->SetFont('Arial','B',10);
			$pdf->Write(5,$this->ident['site_fullname']);
			$pdf->ln();

			$pdf->SetFont('Arial','B',11);
			$pdf->Write(10,'DAY / DATE');
			$pdf->Ln();
			$pdf->SetFont('Arial','',10);
			$pdf->Cell(40,7,'Day / Date',1,0,'L');
			$pdf->Cell(50,7,$security['created_date'],1,0,'L');
			$pdf->Ln();
			$pdf->Cell(40,7,'Reporting Time',1,0,'L');
			$pdf->Cell(50,7,$setting['chief_security_reporting_time'],1,0,'L');
			$pdf->Ln();
			$pdf->Ln();

			$pdf->SetFont('Arial','B',11);
			$pdf->Write(10,'MAN POWER');
			$pdf->Ln();
			$pdf->SetFont('Arial','',10);
			$pdf->SetFillColor(158,130,75);
			$pdf->SetTextColor(255,255,255);
			$pdf->SetFont('','B');
			$pdf->Cell(189,7,'IN HOUSE',1,0,'C',true);
			$pdf->Ln();
			$pdf->Cell(45,7,'',1,0,'L',true);
			$pdf->Cell(48,7,'Malam',1,0,'C',true);
			$pdf->Cell(48,7,'Pagi',1,0,'C',true);
			$pdf->Cell(48,7,'Siang',1,0,'C',true);
			$pdf->Ln();
			$pdf->SetFont('');
			$pdf->SetTextColor(0,0,0);
			$pdf->SetWidths(array(45, 48, 48, 48));			
			$pdf->Row(array("SUPERVISOR",$security['night']['supervisor'],$security['morning']['supervisor'],$security['afternoon']['supervisor']));
			$pdf->Row(array("STAFF POSKO",$security['night']['staff_posko'],$security['morning']['staff_posko'],$security['afternoon']['staff_posko']));
			$pdf->Row(array("STAFF CCTV",$security['night']['staff_cctv'],$security['morning']['staff_cctv'],$security['afternoon']['staff_cctv']));
			$pdf->Row(array("SAFETY",$security['night']['safety'],$security['morning']['safety'],$security['afternoon']['safety']));
			$pdf->Ln();
			
			$pdf->SetFont('Arial','',10);
			$pdf->SetFillColor(158,130,75);
			$pdf->SetTextColor(255,255,255);
			$pdf->SetFont('','B');
			$pdf->Cell(189,7,'VENDOR',1,0,'C',true);
			$pdf->Ln();
			$pdf->Cell(49,7,'',1,0,'L',true);
			$pdf->Cell(70,7,$vendor[0]['vendor_name'],1,0,'C',true);
			$pdf->Cell(70,7,$vendor[1]['vendor_name'],1,0,'C',true);
			$pdf->Ln();
			$pdf->SetFont('');
			$pdf->SetTextColor(0,0,0);
			$pdf->SetWidths(array(49, 70, 70));			
			$pdf->Row(array("CHIEF / WAKA",$security['chief_spd'],$security['chief_army']));
			$pdf->Row(array("PANWAS",$security['panwas_spd'],$security['panwas_army']));
			$pdf->Row(array("DANTON / DANRU PAGI",$security['danton_pagi_spd'],$security['danton_pagi_army']));
			$pdf->Row(array("KEKUATAN",$security['kekuatan_spd'],$security['kekuatan_army']));
			$pdf->Ln();

			
			if(!empty($equipments)) {
				$pdf->SetFont('Arial','B',11);
				$pdf->Write(10,'PERLENGKAPAN');
				$pdf->Ln();
				$pdf->SetFont('Arial','',10);
				$pdf->SetFillColor(158,130,75);
				$pdf->SetTextColor(255,255,255);
				$pdf->SetFont('','B');
				$pdf->Cell(35,7,'Nama','LTR',0,'C',true);
				$pdf->Cell(35,7,'Vendor','LTR',0,'C',true);
				$pdf->Cell(30,7,'Jumlah','LTR',0,'C',true);
				$pdf->Cell(50,7,'Kondisi',1,0,'C',true);
				$pdf->Cell(39,7,'Keterangan','LTR',0,'C',true);
				$pdf->Ln();
				$pdf->Cell(35,7,'Perlengkapan','LRB',0,'C',true);
				$pdf->Cell(35,7,'','LRB',0,'C',true);
				$pdf->Cell(30,7,'','LRB',0,'C',true);
				$pdf->Cell(25,7,'Ok',1,0,'C',true);
				$pdf->Cell(25,7,'Tidak Ok',1,0,'C',true);
				$pdf->Cell(39,7,'','LRB',0,'C',true);
				$pdf->Ln();
				$pdf->SetFont('');
				$pdf->SetTextColor(0,0,0);
				$pdf->SetWidths(array(35, 35, 30, 25, 25, 39));	
				$i = 0;
				foreach($equipments as $equipment) {
					$pdf->Row(array($equipment['equipment_name'],$equipment['vendor_name'],$equipment['total_equipment'], str_replace("√",'v',$equipment['ok_condition']), $equipment['bad_condition'], $equipment['description']));
					$i++; 
				} 
				$pdf->Ln();
			}
			


			$pdf->SetFont('Arial','B',11);
			$pdf->Write(10,'BRIEFING');
			$pdf->ln();
			$pdf->SetFont('Arial','B',10);
			$pdf->Write(10,'Morning Briefing');
			$pdf->ln();
			$pdf->SetFont('Arial','',10);
			if(!empty($security['morning']['briefing']))
			{
				$morning_briefing = explode("<br>", $security['morning']['briefing']);
				$i = 0;
				foreach($morning_briefing as $b)
				{
					if(trim($morning_briefing[$i-1]) == "" && trim($b) == "") ;
					else
					{
						$pdf->Write(5,$b);
						$pdf->Ln();
					}
					$i++;
				}
				$pdf->Ln();
			}

			if(!empty($security['morning']['briefing2']))
			{
				$morning_briefing2 = explode("<br>", $security['morning']['briefing2']);
				$i = 0;
				foreach($morning_briefing2 as $b)
				{
					if(trim($morning_briefing2[$i-1]) == "" && trim($b) == "") ;
					else
					{
						$pdf->Write(5,$b);
						$pdf->Ln();
					}
					$i++;
				}
				$pdf->Ln();
			}

			if(!empty($security['morning']['briefing3']))
			{
				$morning_briefing3 = explode("<br>", $security['morning']['briefing3']);
				$i = 0;
				foreach($morning_briefing3 as $b)
				{
					if(trim($morning_briefing3[$i-1]) == "" && trim($b) == "") ;
					else
					{
						$pdf->Write(5,$b);
						$pdf->Ln();
					}
					$i++;
				}
				$pdf->Ln();
			}

			$pdf->Line($pdf->getX(),$pdf->getY(), 189, $pdf->getY());

			$pdf->SetFont('Arial','B',10);
			$pdf->Write(10,'Afternoon Briefing');
			$pdf->ln();
			$pdf->SetFont('Arial','',10);
			if(!empty($security['afternoon']['briefing']))
			{
				$afternoon_briefing = explode("<br>", $security['afternoon']['briefing']);
				$i = 0;
				foreach($afternoon_briefing as $b)
				{
					if(trim($afternoon_briefing[$i-1]) == "" && trim($b) == "") ;
					else
					{
						$pdf->Write(5,$b);
						$pdf->Ln();
					}
					$i++;
				}
				$pdf->Ln();
			}

			if(!empty($security['afternoon']['briefing2']))
			{
				$afternoon_briefing2 = explode("<br>", $security['afternoon']['briefing2']);
				$i = 0;
				foreach($afternoon_briefing2 as $b)
				{
					if(trim($afternoon_briefing2[$i-1]) == "" && trim($b) == "") ;
					else
					{
						$pdf->Write(5,$b);
						$pdf->Ln();
					}
					$i++;
				}
				$pdf->Ln();
			}

			if(!empty($security['afternoon']['briefing3']))
			{
				$afternoon_briefing3 = explode("<br>", $security['afternoon']['briefing3']);
				$i = 0;
				foreach($afternoon_briefing3 as $b)
				{
					if(trim($afternoon_briefing3[$i-1]) == "" && trim($b) == "") ;
					else
					{
						$pdf->Write(5,$b);
						$pdf->Ln();
					}
					$i++;
				}
				$pdf->Ln();
			}

			$pdf->Line($pdf->getX(),$pdf->getY(), 189, $pdf->getY());

			$pdf->SetFont('Arial','B',10);
			$pdf->Write(10,'Night Briefing');
			$pdf->ln();
			$pdf->SetFont('Arial','',10);
			if(!empty($security['night']['briefing']))
			{
				$night_briefing = explode("<br>", $security['night']['briefing']);
				$i = 0;
				foreach($night_briefing as $b)
				{
					if(trim($night_briefing[$i-1]) == "" && trim($b) == "") ;
					else
					{
						$pdf->Write(5,$b);
						$pdf->Ln();
					}
					$i++;
				}
				$pdf->Ln();
			}

			if(!empty($security['night']['briefing2']))
			{
				$night_briefing2 = explode("<br>", $security['night']['briefing2']);
				$i = 0;
				foreach($night_briefing2 as $b)
				{
					if(trim($night_briefing2[$i-1]) == "" && trim($b) == "") ;
					else
					{
						$pdf->Write(5,$b);
						$pdf->Ln();
					}
					$i++;
				}
				$pdf->Ln();
			}

			if(!empty($security['night']['briefing3']))
			{
				$night_briefing3 = explode("<br>", $security['night']['briefing3']);
				$i = 0;
				foreach($night_briefing3 as $b)
				{
					if(trim($night_briefing3[$i-1]) == "" && trim($b) == "") ;
					else
					{
						$pdf->Write(5,$b);
						$pdf->Ln();
					}
					$i++;
				}
				$pdf->Ln();
			}

			$pdf->Line($pdf->getX(),$pdf->getY(), 189, $pdf->getY());
		
			if(!empty($outsourceTraining) || !empty($inHouseTraining))
			{
				$pdf->SetFont('Arial','B',11);
				$pdf->Write(10,'TRAINING');
				$pdf->ln();

				if(!empty($outsourceTraining))
				{				
					$pdf->SetFont('Arial','',10);
					$pdf->SetFillColor(158,130,75);
					$pdf->SetTextColor(255,255,255);
					$pdf->SetFont('','B');
					$pdf->Cell(189,7,'OUTSOURCE',1,0,'C',true);
					$pdf->Ln();
					$pdf->Cell(60,7,'Activity',1,0,'C',true);
					$pdf->Cell(129,7,'Description',1,0,'C',true);
					$pdf->Ln();
					$pdf->SetFont('');
					$pdf->SetTextColor(0,0,0);
					$pdf->SetWidths(array(60,129));		
					foreach($outsourceTraining as $outsourceTrain) {	
						$pdf->Row(array($outsourceTrain['activity'],$outsourceTrain['description']));
					}
					$pdf->Ln();
				}

				if(!empty($inHouseTraining))
				{				
					$pdf->SetFont('Arial','',10);
					$pdf->SetFillColor(158,130,75);
					$pdf->SetTextColor(255,255,255);
					$pdf->SetFont('','B');
					$pdf->Cell(189,7,'IN HOUSE',1,0,'C',true);
					$pdf->Ln();
					$pdf->Cell(60,7,'Activity',1,0,'C',true);
					$pdf->Cell(129,7,'Description',1,0,'C',true);
					$pdf->Ln();
					$pdf->SetFont('');
					$pdf->SetTextColor(0,0,0);
					$pdf->SetWidths(array(60,129));		
					foreach($inHouseTraining as $inHouseTrain) {	
						$pdf->Row(array($inHouseTrain['activity'],$inHouseTrain['description']));
					}
					$pdf->Ln();
				}
			}

			$pdf->SetFont('Arial','B',11);
			$pdf->Write(10,'SOSIALISASI SOP');
			$pdf->ln();
			$pdf->SetFont('Arial','',10);
			$pdf->Write(10,$security['sosialisasi_sop_a']);
			$pdf->Ln();
			$pdf->Write(10,$security['sosialisasi_sop_b']);
			$pdf->Ln();
			$pdf->Write(10,$security['sosialisasi_sop_c']);
			$pdf->Ln();

			if(!empty($specific_reports))
			{
				$pdf->SetFont('Arial','B',11);
				$pdf->Write(10,'SPECIFIC REPORT');
				$pdf->Ln();
				$pdf->SetFont('Arial','',10);
				$pdf->SetFillColor(158,130,75);
				$pdf->SetTextColor(255,255,255);
				$pdf->SetFont('','B');
				$pdf->Cell(20,7,'Image',1,0,'C',true);
				$pdf->Cell(30,7,'Date & Time',1,0,'C',true);
				$pdf->Cell(30,7,'Location',1,0,'C',true);
				$pdf->Cell(55,7,'Description',1,0,'C',true);
				$pdf->Cell(55,7,'Status',1,0,'C',true);
				$pdf->Ln();
				$pdf->SetFont('Arial','',10);
				$pdf->SetTextColor(0,0,0);	
				$pdf->SetWidths(array(20,30,30,55,55));
				$pdf->SetAligns(array('C','C','C','C','C'));
				foreach($specific_reports as $specific_report)
				{
					$y = $pdf->GetY() + 1;
					$pdf->Row(array("\n\n\n\n",$specific_report['date_time'],$specific_report['location'],$specific_report['description'],$specific_report['status']));
					
					if (file_exists(str_replace($this->baseUrl."/", $this->config->paths->html, $specific_report['thumb_pic']))) {
    					list($width, $height) = getimagesize(str_replace($this->baseUrl."/", $this->config->paths->html, $specific_report['thumb_pic']));
    					if($width > $height)
    					{
    						$w = 18;
    						$h = 0;
    					}
    					else {
    						$w = 0;
    						$h = 18;
    					}
    					
    					$pdf->Image($specific_report['thumb_pic'],11,$y, $w,$h);
				    }
				}
				$pdf->Ln();
			}
			

			if(!empty($attachment))
			{
				$pdf->SetFont('Arial','B',11);
				$pdf->Write(10,'ATTACHMENTS');
				$pdf->Ln();
				$pdf->SetFont('Arial','',10);
				$pdf->SetTextColor(0,0,0);	
				foreach($attachment as $att)
				{
					$pdf->Cell(100,7,"- ".$att['description'],0,1, 'L', false, $this->baseUrl.'/default/attachment/openattachment/c/1/f/'.$att['filename']);
				}
			}
			$pdf->Ln();

			$pdf->Output('D', $filename, false);
		}		
	}
	
	public function exportchiefreporttopdf2Action() {
		$params = $this->_getAllParams();
		Zend_Loader::LoadClass('securityClass', $this->modelDir);
		$securityClass = new securityClass();
		
		$sec['morning'] = $securityClass->getSecurityReportByShift($params['dt'], '1', $this->site_id);
		$sec['afternoon'] = $securityClass->getSecurityReportByShift($params['dt'], '2', $this->site_id);
		$sec['night'] = $securityClass->getSecurityReportByShift($params['dt'], '3', $this->site_id);
		
		if($sec['morning']['chief_security_report_id'] > 0) $sec['chief_security_report_id'] = $sec['morning']['chief_security_report_id'];
		elseif($sec['afternoon']['chief_security_report_id'] > 0) $sec['chief_security_report_id'] = $sec['afternoon']['chief_security_report_id'];
		elseif($sec['night']['chief_security_report_id'] > 0) $sec['chief_security_report_id'] = $sec['night']['chief_security_report_id'];
		if(!empty($sec['chief_security_report_id'])) $security = $securityClass->getChiefSecurityReportById($sec['chief_security_report_id']);
		$security['morning'] = $sec['morning'];
		$security['afternoon'] = $sec['afternoon'];
		$security['night'] = $sec['night'];
		
		$date = explode("-",$params['dt']);
		$r_date = mktime(0, 0, 0, intval($date[1]), intval($date[2]), intval($date[0]));
		$security['created_date'] = date("l, j F Y", $r_date);
		$security['report_date'] = $params['dt'];
		
		if(empty($sec['chief_security_report_id'])) $tempId = '0';
		else $tempId = $sec['chief_security_report_id'];
		$equipmentTable = $this->loadModel('equipment');
		$equipments = $equipmentTable->getPerlengkapanByChiefSecurityReport($tempId);
		
		$trainingTable = $this->loadModel('training');
		
		if(!empty($sec['chief_security_report_id']))
		{
			$outdoorTraining = $trainingTable->getSecurityTrainingByType($sec['chief_security_report_id'],'1');
			$inHouseTraining = $trainingTable->getSecurityTrainingByType($sec['chief_security_report_id'],'2');
			if(count($outdoorTraining) > count($inHouseTraining)) $totalTraining = count($outdoorTraining);
			else $totalTraining = count($inHouseTraining);
		}
		
		$settingTable = $this->loadModel('setting');
		$setting = $settingTable->getOtherSetting();
		
		/*** SPECIFIC REPORT ***/
		
		$security_ids = "";
		if(!empty($security['morning']['security_id'])) $security_ids .= $security['morning']['security_id'].",";
		if(!empty($security['afternoon']['security_id'])) $security_ids .= $security['afternoon']['security_id'].",";
		if(!empty($security['night']['security_id'])) $security_ids .= $security['night']['security_id'].",";
		$security_ids = substr($security_ids,0,-1);
		$issueTable = $this->loadModel('issue');
		
		/*if(!empty($security_ids))
		{
			$incident_report = $issueTable->getIssuesByIds('security_incident',$security_ids);
			$lost_found_report = $issueTable->getIssuesByIds('security_lost_found',$security_ids);
			$glitch = $issueTable->getIssuesByIds('security_glitch',$security_ids);			
			$defect_list = $securityClass->getDefectListByIds($security_ids);
		}
		
		Zend_Loader::LoadClass('shiftClass', $this->modelDir);
		$shiftClass = new shiftClass();
		$shift = $shiftClass->getShift();
		
		if(!empty($tempId)) $spec_report = $securityClass->getSpecificReportByChiefSecurityReport($tempId);
		$specific_reports = array_merge($incident_report,$lost_found_report, $glitch, $defect_list, $spec_report);*/
		$specific_reports = $securityClass->getSpecificReportByIds($security_ids, $tempId);
		foreach($specific_reports as &$sr)
		{
			if(!empty($sr['issue_id']))
			{
				$sr['detail'] = $sr['description'];
				$datetime = explode(" ",$sr['issue_date']);
				$sr['time'] = $datetime[1];
			}
		}
		
		/*** END OF SPECIFIC REPORT ***/
		
		if(!empty($sec['chief_security_report_id'])) $attachment = $securityClass->getChiefAttachments($sec['chief_security_report_id']);
		
		$attachmentMorning = $attachmentAfternoon = $attachmentNight = array();
		if(!empty($security['morning']['security_id'])) $attachmentMorning = $securityClass->getSpvAttachments($security['morning']['security_id']);
		if(!empty($security['afternoon']['security_id'])) $attachmentAfternoon = $securityClass->getSpvAttachments($security['afternoon']['security_id']);
		if(!empty($security['night']['security_id'])) $attachmentNight = $securityClass->getSpvAttachments($security['night']['security_id']);
		$attachment = array_merge($attachmentNight, $attachmentMorning, $attachmentAfternoon, $attachment);
		
		/*Zend_Loader::LoadClass('equipmentClass', $this->modelDir);
		$equipmentClass = new equipmentClass();
		$vendors = $equipmentClass->getVendorSecurityEquipments();
		
		if(!empty($vendors))
		{
			foreach($vendors as $v)
			{
				if(strtolower($v['vendor']) == 'spd') $vendor[0] = $v['vendor'];
				else $vendor[1] = $v['vendor'];
			}
		}*/
		Zend_Loader::LoadClass('vendorClass', $this->modelDir);
		$vendorClass = new vendorClass();
		$vendor = $vendorClass->getVendor($this->site_id);
		
		require('PHPpdf/html2fpdf.php');


		$html= '<html>
		<head>
		<title>Chief Security Daily Report</title>
		 
		</head>
		<body>
		<h2>Chief Security Report</h2>
		<h3>Security</h3>
		'.$this->ident['site_fullname'].'
		
		<h3>DAY / DATE</h3>
		<table width="100%" cellspacing="6" cellpadding="5" align="center" border="1" valign="top">
			<tr><td><strong>Day / Date</strong></td><td>'.$security['created_date'].'</td></tr>
			<tr><td><strong>Reporting Time</strong></td><td>'.$setting['chief_security_reporting_time'].'</td></tr>
		</table>
		
		<h3>MAN POWER</h3>
		<table width="100%" cellspacing="6" cellpadding="5" align="center" border="1" valign="top">
			<tr bgcolor="#afd9af"><th><strong>IN HOUSE</strong></th><th><strong>Vendor</strong></th></tr>
			<tr>
				<td>SUPERVISOR<br>
					Malam : '.$security['night']['supervisor'].'<br>
					Pagi : '.$security['morning']['supervisor'].'<br>
					Siang : '.$security['afternoon']['supervisor'].'<br>&nbsp;<br>
					STAFF POSKO<br>
					Malam : '.$security['night']['staff_posko'].'<br>
					Pagi : '.$security['morning']['staff_posko'].'<br>
					Siang : '.$security['afternoon']['staff_posko'].'<br>&nbsp;<br>
					STAFF CCTV<br>
					Malam : '.$security['night']['staff_cctv'].'<br>
					Pagi : '.$security['morning']['staff_cctv'].'<br>
					Siang : '.$security['afternoon']['staff_cctv'].'<br>&nbsp;<br>
					SAFETY<br>
					Malam : '.$security['night']['safety'].'<br>
					Pagi : '.$security['morning']['safety'].'<br>
					Siang : '.$security['afternoon']['safety'].'
				</td>
				<td>CHIEF / WAKA<br>
					'.$vendor[0]['vendor_name'].' : '.$security['chief_spd'].'<br>
					'.$vendor[1]['vendor_name'].' : '.$security['chief_army'].'<br>&nbsp;<br>&nbsp;<br>
					PANWAS<br>
					'.$vendor[0]['vendor_name'].' : '.$security['panwas_spd'].'<br>
					'.$vendor[1]['vendor_name'].' : '.$security['panwas_army'].'<br>&nbsp;<br>&nbsp;<br>
					DANTON / DANRU PAGI<br>
					'.$vendor[0]['vendor_name'].' : '.$security['danton_pagi_spd'].'<br>
					'.$vendor[1]['vendor_name'].' : '.$security['danton_pagi_army'].'<br>&nbsp;<br>&nbsp;<br>
					KEKUATAN<br>
					'.$vendor[0]['vendor_name'].' : '.$security['kekuatan_spd'].'<br>
					'.$vendor[1]['vendor_name'].' : '.$security['kekuatan_army'].'
				</td>
			</tr>
		</table>
		
		<h3>PERLENGKAPAN</h3>
		<table width="100%" cellspacing="6" cellpadding="5" align="center" border="1" valign="top">
			<tr bgcolor="#afd9af">
			  <th rowspan="2">Nama Perlengkapan</th>
			  <th rowspan="2">Vendor</th>
			  <th rowspan="2">Jumlah</th>
			  <th width="150" colspan="2">Kondisi</th>
			  <th rowspan="2">Keterangan</th>
			</tr>
			<tr bgcolor="#afd9af">
			  <th width="75">Ok</th>
			  <th width="75">Tidak Ok</th>
			</tr>';
			  
			if(!empty($equipments)) {
				$i = 0;
				foreach($equipments as $equipment) {
					$html .= '<tr>
					<td>'.$equipment['equipment_name'].'</td>
					<td>'.$equipment['vendor_name'].'</td>
					<td>'.$equipment['total_equipment'].'</td>
					<td>'.str_replace("√",'<img src="'.$this->config->general->url.'images/checkmark.jpg" height="16px" />',$equipment['ok_condition']).'</td>
					<td>'.$equipment['bad_condition'].'</td>
					<td>'.$equipment['description'].'</td>
				</tr>';
				$i++; } 
				}
			  $html .= '</table>	

		<h3>BRIEFING</h3>
		<h4>Morning Briefing</h4>';
		if(!empty($security['morning']['briefing']))
			$html .= '<div>'.str_replace("<br />", "<br>",nl2br($security['morning']['briefing'])).'</div><br>
		<hr>';
		if(!empty($security['morning']['briefing2']))
			$html .= '<div>'.str_replace("<br />", "<br>",nl2br($security['morning']['briefing2'])).'</div><br>
		<hr>';
		if(!empty($security['morning']['briefing3']))
			$html .= '<div>'.str_replace("<br />", "<br>",nl2br($security['morning']['briefing3'])).'</div><br>
		<hr>';
		$html .= '<h4>Afternoon Briefing</h4>';
		if(!empty($security['afternoon']['briefing']))
			$html .= '<div>'.str_replace("<br />", "<br>",nl2br($security['afternoon']['briefing'])).'</div><br>
		<hr>';
		if(!empty($security['afternoon']['briefing2']))
			$html .= '<div>'.str_replace("<br />", "<br>",nl2br($security['afternoon']['briefing2'])).'</div><br>
		<hr>';
		if(!empty($security['afternoon']['briefing3']))
			$html .= '<div>'.str_replace("<br />", "<br>",nl2br($security['afternoon']['briefing3'])).'</div><br>
		<hr>';
		$html .= '<h4>Night Briefing</h4>';
		if(!empty($security['night']['briefing']))
			$html .= '<div>'.str_replace("<br />", "<br>",nl2br($security['night']['briefing'])).'</div><br>
		<hr>';
		if(!empty($security['night']['briefing2']))
			$html .= '<div>'.str_replace("<br />", "<br>",nl2br($security['night']['briefing2'])).'</div><br>
		<hr>';
		if(!empty($security['night']['briefing3']))
			$html .= '<div>'.str_replace("<br />", "<br>",nl2br($security['night']['briefing3'])).'</div><br>
		<hr>';
		
		$html .= '<h3>TRAINING</h3>
		<table width="100%" cellspacing="6" cellpadding="5" align="center" border="1" valign="top">
			<tr>
				<th><strong>Outsource</strong></th>
				<th><strong>In House</strong></th>
			</tr>';
		for($j=0; $j<$totalTraining; $j++)
		{
			$html .= '<tr>
				<td>'.strtoupper($outdoorTraining[$j]['activity']).'<br>'.$outdoorTraining[$j]['description'].'</td>
				<td>'.strtoupper($inHouseTraining[$j]['activity']).'<br>'.$inHouseTraining[$j]['description'].'</td>
			</tr>';
		} 
	  $html .= '
		</table>
		
		<h3>SOSIALISASI SOP</h3>
'.$security['sosialisasi_sop_a'].'<br>
'.$security['sosialisasi_sop_b'].'<br>
'.$security['sosialisasi_sop_c'].'<br>';
			
		if(!empty($specific_reports)) { 
		$html .= '<h3>SPECIFIC REPORT</h3>
		<table width="100%" cellspacing="6" cellpadding="5" align="center" border="1" valign="top">';
			foreach($specific_reports as $specific_report) {
				$timeField = "Time";
				if($specific_report['issue_type_id'] < 4)
				{
					$specific_report['detail'] = $specific_report['description'];
				}
				if($specific_report['issue_type_id'] == 4)
				{
					$specific_report['time'] =  $specific_report['area'];
					//$specific_report['status'] = $specific_report['follow_up'];
					$specific_report['issue_type_name'] = "Defect List";
					$timeField = "Area";
				}
				//$issueDate = explode(" ",$specific_report['issue_date']);
				//$specific_report['time'] = $issueDate[1];
				$html .= '<tr>
					<td>'.strtoupper($specific_report['issue_type_name']).'<br>'.$timeField.' : '.$specific_report['time'].'<br>Detail : '.$specific_report['detail'].'</td>
					<td><br>Status :<br>'.$specific_report['status'].'</td>
				</tr>';
			 }
		$html .= '</table>';
		}
		
		$html .= '<h4>Attachment</h4>			
			<table width="100%" cellspacing="6" cellpadding="5" align="center" border="1" valign="top">
			<tr bgcolor="#afd9af">
				<th width="120"><strong>Filename</strong></th>
				<th><strong>Description</strong></th>
			</tr>';
			if(!empty($attachment)) {
				foreach($attachment as $att) {
					$html .= '<tr>
						<td><a href="'.$this->baseUrl.'/default/attachment/openattachment/c/1/f/'.$att['filename'].'">'.$att['filename'].'</a></td>
						<td>'.$att['description'].'</td>
					</tr>';
				}
			}
			$html .= '</table>';
		
		$html .= '</body>
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

	public function exportchiefreporttopdfAction() {		
		require('fpdf/mc_table.php');
		$params = $this->_getAllParams();
		Zend_Loader::LoadClass('securityClass', $this->modelDir);
		$securityClass = new securityClass();

		$sec['morning'] = $securityClass->getSecurityReportByShift($params['dt'], '1', $this->site_id);
		$sec['afternoon'] = $securityClass->getSecurityReportByShift($params['dt'], '2', $this->site_id);
		$sec['night'] = $securityClass->getSecurityReportByShift($params['dt'], '3', $this->site_id);
		
		if($sec['morning']['chief_security_report_id'] > 0) $sec['chief_security_report_id'] = $sec['morning']['chief_security_report_id'];
		elseif($sec['afternoon']['chief_security_report_id'] > 0) $sec['chief_security_report_id'] = $sec['afternoon']['chief_security_report_id'];
		elseif($sec['night']['chief_security_report_id'] > 0) $sec['chief_security_report_id'] = $sec['night']['chief_security_report_id'];
		if(!empty($sec['chief_security_report_id'])) 
		{
			$security = $securityClass->getChiefSecurityReportById($sec['chief_security_report_id']);
			
			$params['user_id'] = $this->ident['user_id'];
			$params['id'] = $sec['chief_security_report_id'];
			$securityClass->addReadChiefReportLog($params);
		}

		$filename = $this->config->paths->html.'/pdf_report/security/' . $this->site_id."_chief_".$sec['chief_security_report_id'].".pdf";
		if (file_exists($filename) && date("Y-m-d", mktime(0, 0, 0, date("m"), date("d")-1, date("Y"))) > $params['dt']) {	
			// Header content type 
			header('Content-type: application/pdf'); 				
			header('Content-Disposition: inline; filename="' . $filename . '"'); 				
			// Read the file 
			readfile($filename); 
			exit();
		} else {		
			$security['morning'] = $sec['morning'];
			$security['afternoon'] = $sec['afternoon'];
			$security['night'] = $sec['night'];
			
			$date = explode("-",$params['dt']);
			$r_date = mktime(0, 0, 0, intval($date[1]), intval($date[2]), intval($date[0]));
			$security['created_date'] = date("l, j F Y", $r_date);
			$security['report_date'] = $params['dt'];
			
			if(empty($sec['chief_security_report_id'])) $tempId = '0';
			else $tempId = $sec['chief_security_report_id'];
			$equipmentTable = $this->loadModel('equipment');
			$equipments = $equipmentTable->getPerlengkapanByChiefSecurityReport($tempId);
			
			$trainingTable = $this->loadModel('training');
			
			if(!empty($sec['chief_security_report_id']))
			{
				$outsourceTraining = $trainingTable->getSecurityTrainingByType($sec['chief_security_report_id'],'1');
				$inHouseTraining = $trainingTable->getSecurityTrainingByType($sec['chief_security_report_id'],'2');
				if(count($outsourceTraining) > count($inHouseTraining)) $totalTraining = count($outsourceTraining);
				else $totalTraining = count($inHouseTraining);
			}
			
			$settingTable = $this->loadModel('setting');
			$setting = $settingTable->getOtherSetting();
			
			/*** SPECIFIC REPORT ***/
			
			$security_ids = "";
			if(!empty($security['morning']['security_id'])) $security_ids .= $security['morning']['security_id'].",";
			if(!empty($security['afternoon']['security_id'])) $security_ids .= $security['afternoon']['security_id'].",";
			if(!empty($security['night']['security_id'])) $security_ids .= $security['night']['security_id'].",";
			$security_ids = substr($security_ids,0,-1);
			$issueTable = $this->loadModel('issue');
			
			$specific_reports = $securityClass->getSpecificReportByIds($security_ids, $tempId);
			foreach($specific_reports as &$sr)
			{
				if(!empty($sr['issue_id']))
				{
					$sr['detail'] = $sr['description'];
					$datetime = explode(" ",$sr['issue_date']);
					$sr['time'] = $datetime[1];
				}
			}
			
			/*** END OF SPECIFIC REPORT ***/
			$attachment = $attachmentMorning = $attachmentAfternoon = $attachmentNight = array();
			if(!empty($sec['chief_security_report_id'])) $attachment = $securityClass->getChiefAttachments($sec['chief_security_report_id']);
					
			if(!empty($security['morning']['security_id'])) $attachmentMorning = $securityClass->getSpvAttachments($security['morning']['security_id']);
			if(!empty($security['afternoon']['security_id'])) $attachmentAfternoon = $securityClass->getSpvAttachments($security['afternoon']['security_id']);
			if(!empty($security['night']['security_id'])) $attachmentNight = $securityClass->getSpvAttachments($security['night']['security_id']);
			$attachment = array_merge($attachmentNight, $attachmentMorning, $attachmentAfternoon, $attachment);
			
			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "Export Chief Security Daily Report to PDF";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);	

			Zend_Loader::LoadClass('vendorClass', $this->modelDir);
			$vendorClass = new vendorClass();
			$vendor = $vendorClass->getVendor($this->site_id);

			$pdf=new PDF_MC_Table();
			$pdf->AddPage();
			$pdf->SetTitle($this->ident['initial']." - Chief Security Daily Report");
			$pdf->SetFont('Arial','B',15);
			$pdf->Write(10,'Chief Security Report');
			$pdf->ln();
			$pdf->SetFont('Arial','B',10);
			$pdf->Write(5,$this->ident['site_fullname']);
			$pdf->ln();

			$pdf->SetFont('Arial','B',11);
			$pdf->Write(10,'DAY / DATE');
			$pdf->Ln();
			$pdf->SetFont('Arial','',10);
			$pdf->Cell(40,7,'Day / Date',1,0,'L');
			$pdf->Cell(50,7,$security['created_date'],1,0,'L');
			$pdf->Ln();
			$pdf->Cell(40,7,'Reporting Time',1,0,'L');
			$pdf->Cell(50,7,$setting['chief_security_reporting_time'],1,0,'L');
			$pdf->Ln();
			$pdf->Ln();

			$pdf->SetFont('Arial','B',11);
			$pdf->Write(10,'MAN POWER');
			$pdf->Ln();
			$pdf->SetFont('Arial','',10);
			$pdf->SetFillColor(158,130,75);
			$pdf->SetTextColor(255,255,255);
			$pdf->SetFont('','B');
			$pdf->Cell(189,7,'IN HOUSE',1,0,'C',true);
			$pdf->Ln();
			$pdf->Cell(45,7,'',1,0,'L',true);
			$pdf->Cell(48,7,'Malam',1,0,'C',true);
			$pdf->Cell(48,7,'Pagi',1,0,'C',true);
			$pdf->Cell(48,7,'Siang',1,0,'C',true);
			$pdf->Ln();
			$pdf->SetFont('');
			$pdf->SetTextColor(0,0,0);
			$pdf->SetWidths(array(45, 48, 48, 48));			
			$pdf->Row(array("SUPERVISOR",$security['night']['supervisor'],$security['morning']['supervisor'],$security['afternoon']['supervisor']));
			$pdf->Row(array("STAFF POSKO",$security['night']['staff_posko'],$security['morning']['staff_posko'],$security['afternoon']['staff_posko']));
			$pdf->Row(array("STAFF CCTV",$security['night']['staff_cctv'],$security['morning']['staff_cctv'],$security['afternoon']['staff_cctv']));
			$pdf->Row(array("SAFETY",$security['night']['safety'],$security['morning']['safety'],$security['afternoon']['safety']));
			$pdf->Ln();

			$pdf->SetFont('Arial','',10);
			$pdf->SetFillColor(158,130,75);
			$pdf->SetTextColor(255,255,255);
			$pdf->SetFont('','B');
			$pdf->Cell(189,7,'VENDOR',1,0,'C',true);
			$pdf->Ln();
			$pdf->Cell(49,7,'',1,0,'L',true);
			$pdf->Cell(70,7,$vendor[0]['vendor_name'],1,0,'C',true);
			$pdf->Cell(70,7,$vendor[1]['vendor_name'],1,0,'C',true);
			$pdf->Ln();
			$pdf->SetFont('');
			$pdf->SetTextColor(0,0,0);
			$pdf->SetWidths(array(49, 70, 70));			
			$pdf->Row(array("CHIEF / WAKA",$security['chief_spd'],$security['chief_army']));
			$pdf->Row(array("PANWAS",$security['panwas_spd'],$security['panwas_army']));
			$pdf->Row(array("DANTON / DANRU PAGI",$security['danton_pagi_spd'],$security['danton_pagi_army']));
			$pdf->Row(array("KEKUATAN",$security['kekuatan_spd'],$security['kekuatan_army']));
			$pdf->Ln();

			
			if(!empty($equipments)) {
				$pdf->SetFont('Arial','B',11);
				$pdf->Write(10,'PERLENGKAPAN');
				$pdf->Ln();
				$pdf->SetFont('Arial','',10);
				$pdf->SetFillColor(158,130,75);
				$pdf->SetTextColor(255,255,255);
				$pdf->SetFont('','B');
				$pdf->Cell(35,7,'Nama','LTR',0,'C',true);
				$pdf->Cell(35,7,'Vendor','LTR',0,'C',true);
				$pdf->Cell(30,7,'Jumlah','LTR',0,'C',true);
				$pdf->Cell(50,7,'Kondisi',1,0,'C',true);
				$pdf->Cell(39,7,'Keterangan','LTR',0,'C',true);
				$pdf->Ln();
				$pdf->Cell(35,7,'Perlengkapan','LRB',0,'C',true);
				$pdf->Cell(35,7,'','LRB',0,'C',true);
				$pdf->Cell(30,7,'','LRB',0,'C',true);
				$pdf->Cell(25,7,'Ok',1,0,'C',true);
				$pdf->Cell(25,7,'Tidak Ok',1,0,'C',true);
				$pdf->Cell(39,7,'','LRB',0,'C',true);
				$pdf->Ln();
				$pdf->SetFont('');
				$pdf->SetTextColor(0,0,0);
				$pdf->SetWidths(array(35, 35, 30, 25, 25, 39));	
				$i = 0;
				foreach($equipments as $equipment) {
					$pdf->Row(array($equipment['equipment_name'],$equipment['vendor_name'],$equipment['total_equipment'], str_replace("√",'v',$equipment['ok_condition']), $equipment['bad_condition'], $equipment['description']));
					$i++; 
				} 
				$pdf->Ln();
			}
			


			$pdf->SetFont('Arial','B',11);
			$pdf->Write(10,'BRIEFING');
			$pdf->ln();
			$pdf->SetFont('Arial','B',10);
			$pdf->Write(10,'Morning Briefing');
			$pdf->ln();
			$pdf->SetFont('Arial','',10);
			if(!empty($security['morning']['briefing']))
			{
				$morning_briefing = explode("<br>", $security['morning']['briefing']);
				$i = 0;
				foreach($morning_briefing as $b)
				{
					if(trim($morning_briefing[$i-1]) == "" && trim($b) == "") ;
					else
					{
						$pdf->Write(5,$b);
						$pdf->Ln();
					}
					$i++;
				}
				$pdf->Ln();
			}

			if(!empty($security['morning']['briefing2']))
			{
				$morning_briefing2 = explode("<br>", $security['morning']['briefing2']);
				$i = 0;
				foreach($morning_briefing2 as $b)
				{
					if(trim($morning_briefing2[$i-1]) == "" && trim($b) == "") ;
					else
					{
						$pdf->Write(5,$b);
						$pdf->Ln();
					}
					$i++;
				}
				$pdf->Ln();
			}

			if(!empty($security['morning']['briefing3']))
			{
				$morning_briefing3 = explode("<br>", $security['morning']['briefing3']);
				$i = 0;
				foreach($morning_briefing3 as $b)
				{
					if(trim($morning_briefing3[$i-1]) == "" && trim($b) == "") ;
					else
					{
						$pdf->Write(5,$b);
						$pdf->Ln();
					}
					$i++;
				}
				$pdf->Ln();
			}

			$pdf->Line($pdf->getX(),$pdf->getY(), 189, $pdf->getY());

			$pdf->SetFont('Arial','B',10);
			$pdf->Write(10,'Afternoon Briefing');
			$pdf->ln();
			$pdf->SetFont('Arial','',10);
			if(!empty($security['afternoon']['briefing']))
			{
				$afternoon_briefing = explode("<br>", $security['afternoon']['briefing']);
				$i = 0;
				foreach($afternoon_briefing as $b)
				{
					if(trim($afternoon_briefing[$i-1]) == "" && trim($b) == "") ;
					else
					{
						$pdf->Write(5,$b);
						$pdf->Ln();
					}
					$i++;
				}
				$pdf->Ln();
			}

			if(!empty($security['afternoon']['briefing2']))
			{
				$afternoon_briefing2 = explode("<br>", $security['afternoon']['briefing2']);
				$i = 0;
				foreach($afternoon_briefing2 as $b)
				{
					if(trim($afternoon_briefing2[$i-1]) == "" && trim($b) == "") ;
					else
					{
						$pdf->Write(5,$b);
						$pdf->Ln();
					}
					$i++;
				}
				$pdf->Ln();
			}

			if(!empty($security['afternoon']['briefing3']))
			{
				$afternoon_briefing3 = explode("<br>", $security['afternoon']['briefing3']);
				$i = 0;
				foreach($afternoon_briefing3 as $b)
				{
					if(trim($afternoon_briefing3[$i-1]) == "" && trim($b) == "") ;
					else
					{
						$pdf->Write(5,$b);
						$pdf->Ln();
					}
					$i++;
				}
				$pdf->Ln();
			}

			$pdf->Line($pdf->getX(),$pdf->getY(), 189, $pdf->getY());

			$pdf->SetFont('Arial','B',10);
			$pdf->Write(10,'Night Briefing');
			$pdf->ln();
			$pdf->SetFont('Arial','',10);
			if(!empty($security['night']['briefing']))
			{
				$night_briefing = explode("<br>", $security['night']['briefing']);
				$i = 0;
				foreach($night_briefing as $b)
				{
					if(trim($night_briefing[$i-1]) == "" && trim($b) == "") ;
					else
					{
						$pdf->Write(5,$b);
						$pdf->Ln();
					}
					$i++;
				}
				$pdf->Ln();
			}

			if(!empty($security['night']['briefing2']))
			{
				$night_briefing2 = explode("<br>", $security['night']['briefing2']);
				$i = 0;
				foreach($night_briefing2 as $b)
				{
					if(trim($night_briefing2[$i-1]) == "" && trim($b) == "") ;
					else
					{
						$pdf->Write(5,$b);
						$pdf->Ln();
					}
					$i++;
				}
				$pdf->Ln();
			}

			if(!empty($security['night']['briefing3']))
			{
				$night_briefing3 = explode("<br>", $security['night']['briefing3']);
				$i = 0;
				foreach($night_briefing3 as $b)
				{
					if(trim($night_briefing3[$i-1]) == "" && trim($b) == "") ;
					else
					{
						$pdf->Write(5,$b);
						$pdf->Ln();
					}
					$i++;
				}
				$pdf->Ln();
			}

			$pdf->Line($pdf->getX(),$pdf->getY(), 189, $pdf->getY());
		
			if(!empty($outsourceTraining) || !empty($inHouseTraining))
			{
				$pdf->SetFont('Arial','B',11);
				$pdf->Write(10,'TRAINING');
				$pdf->ln();

				if(!empty($outsourceTraining))
				{				
					$pdf->SetFont('Arial','',10);
					$pdf->SetFillColor(158,130,75);
					$pdf->SetTextColor(255,255,255);
					$pdf->SetFont('','B');
					$pdf->Cell(189,7,'OUTSOURCE',1,0,'C',true);
					$pdf->Ln();
					$pdf->Cell(60,7,'Activity',1,0,'C',true);
					$pdf->Cell(129,7,'Description',1,0,'C',true);
					$pdf->Ln();
					$pdf->SetFont('');
					$pdf->SetTextColor(0,0,0);
					$pdf->SetWidths(array(60,129));		
					foreach($outsourceTraining as $outsourceTrain) {	
						$pdf->Row(array($outsourceTrain['activity'],$outsourceTrain['description']));
					}
					$pdf->Ln();
				}

				if(!empty($inHouseTraining))
				{				
					$pdf->SetFont('Arial','',10);
					$pdf->SetFillColor(158,130,75);
					$pdf->SetTextColor(255,255,255);
					$pdf->SetFont('','B');
					$pdf->Cell(189,7,'IN HOUSE',1,0,'C',true);
					$pdf->Ln();
					$pdf->Cell(60,7,'Activity',1,0,'C',true);
					$pdf->Cell(129,7,'Description',1,0,'C',true);
					$pdf->Ln();
					$pdf->SetFont('');
					$pdf->SetTextColor(0,0,0);
					$pdf->SetWidths(array(60,129));		
					foreach($inHouseTraining as $inHouseTrain) {	
						$pdf->Row(array($inHouseTrain['activity'],$inHouseTrain['description']));
					}
					$pdf->Ln();
				}
			}

			$pdf->SetFont('Arial','B',11);
			$pdf->Write(10,'SOSIALISASI SOP');
			$pdf->ln();
			$pdf->SetFont('Arial','',10);
			$pdf->Write(10,$security['sosialisasi_sop_a']);
			$pdf->Ln();
			$pdf->Write(10,$security['sosialisasi_sop_b']);
			$pdf->Ln();
			$pdf->Write(10,$security['sosialisasi_sop_c']);
			$pdf->Ln();

			if(!empty($specific_reports))
			{
				$pdf->SetFont('Arial','B',11);
				$pdf->Write(10,'SPECIFIC REPORT');
				$pdf->Ln();
				$pdf->SetFont('Arial','',10);
				$pdf->SetTextColor(0,0,0);	
				$pdf->SetWidths(array(94,95));
				foreach($specific_reports as $specific_report)
				{
					$timeField = "Time";
					if($specific_report['issue_type_id'] < 4)
					{
						$specific_report['detail'] = $specific_report['description'];
					}
					if($specific_report['issue_type_id'] == 4)
					{
						$specific_report['time'] =  $specific_report['area'];
						$specific_report['issue_type_name'] = "Defect List";
						$timeField = "Area";
					}
					$issue = $specific_report['issue_type_name']."\n".$timeField.' : '.$specific_report['time']."\nDetail : ".$specific_report['detail'];
					$pdf->Row(array($issue,"Status :\n".$specific_report['status']));
				}
				$pdf->Ln();
			}
			

			if(!empty($attachment))
			{
				$pdf->SetFont('Arial','B',11);
				$pdf->Write(10,'ATTACHMENTS');
				$pdf->Ln();
				$pdf->SetFont('Arial','',10);
				$pdf->SetTextColor(0,0,0);	
				foreach($attachment as $att)
				{
					$pdf->Cell(100,7,"- ".$att['description'],0,1, 'L', false, $this->baseUrl.'/default/attachment/openattachment/c/1/f/'.$att['filename']);
				}
			}
			$pdf->Ln();

			$pdf->Output('F', $filename, false);
			$pdf->Output('I', $filename, false);
		}
	}
	
	function addchiefattachmentAction()
	{
		$params = $this->_getAllParams();
		
		Zend_Loader::LoadClass('securityClass', $this->modelDir);
		$securityClass = new securityClass();
		
		$attachment_id = $securityClass->addChiefAttachment($params);
		
		if(!empty($_FILES["attachment_file"]))
		{
			$ext = explode(".",$_FILES["attachment_file"]['name']);
			$filename = $attachment_id."_chief.".$ext[count($ext)-1];
			$datafolder = $this->config->paths->html."/attachment/security/";
			if(move_uploaded_file($_FILES["attachment_file"]["tmp_name"], $datafolder.$filename))
			{
				$securityClass->updateChiefAttachment($attachment_id,'filename', $filename);
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
		$logData['action'] = "Add Chief Security Daily Report Attachment";
		$logData['data'] = json_encode($params);
		$logsTable->insertLogs($logData);	
		
		$this->_response->setRedirect($this->baseUrl.'/default/security/chiefpage3/id/'.$params['report_id']);
		$this->_response->sendResponse();
		exit();
	
	}
	
	public function getchiefattachmentbyidAction() {
		$params = $this->_getAllParams();
		Zend_Loader::LoadClass('securityClass', $this->modelDir);
		$securityClass = new securityClass();
		
		if(!empty($params['id'])) 
		{
			echo json_encode($securityClass->getChiefAttachmentById($params['id']));
		}
	}
	
	public function deletechiefattachmentbyidAction() {
		$params = $this->_getAllParams();
		Zend_Loader::LoadClass('securityClass', $this->modelDir);
		$securityClass = new securityClass();
		
		$securityClass->deleteChiefAttachmentById($params['id']);

		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "Delete Chief Security Daily Report";
		$logData['data'] = json_encode($params);
		$logsTable->insertLogs($logData);	
		
		$this->_response->setRedirect($this->baseUrl.'/default/security/chiefpage3/id/'.$params['report_id']);
		$this->_response->sendResponse();
		exit();
	}

	function updatecommentsAction()
	{
		$params = $this->_getAllParams();
		$params['pagesize'] = 10;
	
		Zend_Loader::LoadClass('securityClass', $this->modelDir);
		$securityClass = new securityClass();
		
		$data= array();	
		$commentCacheName = "security_comments_".$this->site_id."_".$params["start"];
		//$data = $this->cache->load($commentCacheName);	
		$i=0;
		if(empty($data))
		{
			$security = $securityClass->getSecurityReports($params);	
			$commentsTable = $this->loadModel('securitycomments');

			foreach($security as $s) {
				$created_date = explode(" ",$s['created_date']);
				$data[$i]['report_date'] = $created_date[0];
				$data[$i]['security_id'] = $s['security_id'];
				//$comments = $commentsTable->getCommentsByChiefSecurityId( $s['chief_security_report_id'], '3');
				$comments = $commentsTable->getCommentsByReportDate($created_date[0], '3', $this->site_id);
				if(!empty($comments)) { 
					$comment_content = "";
					foreach($comments as $comment)
					{
						$comment_content .= '<div style="border-bottom:1px solid #ccc; text-align:left; clear:both; margin-bottom:5px; padding-bottom:5px;"><strong>'.$comment['name'].' :</strong> '.$comment['comment'].'<br/>';
						if(!empty($comment['filename'])) $comment_content .= '<a href="'.$this->baseUrl."/comments/".$comment['filename'].'" target="_blank"><i class="fa fa-paperclip"></i> '.$comment['filename'].'</a>';
						$comment_content .= '<div style="font-size:10px; color:#aaa; text-align:right;">'.$comment['comment_date']."</div></div>";
					}
					$data[$i]['comment'] = $comment_content;
				}
				$i++;
			}
			//$this->cache->save($data, $commentCacheName, array($commentCacheName), 60);
		}				
		echo json_encode($data);
	}

	function updatedchiefcommentsAction()
	{
		$params = $this->_getAllParams();
		$params['pagesize'] = 10;
		
		Zend_Loader::LoadClass('securityClass', $this->modelDir);
		$securityClass = new securityClass();
		
		$data= array();

		$commentCacheName = "chief_security_comments_".$this->site_id."_".$params["start"];
		//$data = $this->cache->load($commentCacheName);	

		$i=0;
		if(empty($data))
		{
			$security = $securityClass->getChiefSecurityReports($params);	
			$commentsTable = $this->loadModel('securitycomments');
			foreach($security as $s) {
				$created_date = explode(" ",$s['created_date']);
				$data[$i]['report_date'] = $created_date[0];
				//$comments = $commentsTable->getCommentsByChiefSecurityId( $s['chief_security_report_id'], '3');
				$comments = $commentsTable->getCommentsByReportDate($created_date[0], '3', $this->site_id);
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
			//$this->cache->save($data, $commentCacheName, array($commentCacheName), 60);
		}
				
		echo json_encode($data);
	}

	public function addmonthlyanalysisAction() {
		$params = $this->_getAllParams();

		if(!empty($params['id'])) 
		{
			$this->view->monthly_analysis_id = $params['id'];		
			Zend_Loader::LoadClass('securityClass', $this->modelDir);
			$securityClass = new securityClass();
			$monthly_analysis = $securityClass->geMonthlyAnalysisById($params['id']);
			$monthly_analysis_datetime = explode(" ", $monthly_analysis['save_date']);
			$monthly_analysis_date = explode("-", $monthly_analysis_datetime[0]);
			$ma['monthyear'] = date("F Y", mktime(0, 0, 0, $monthly_analysis_date[1]-1, $monthly_analysis_date[2], $monthly_analysis_date[0]));
			$ym = date("Ym", mktime(0, 0, 0, $monthly_analysis_date[1]-1, $monthly_analysis_date[2], $monthly_analysis_date[0]));
			$ymCur = date("Ym", mktime(0, 0, 0, $monthly_analysis_date[1], $monthly_analysis_date[2], $monthly_analysis_date[0]));
		}

		if(empty($ym)) 
		{
			$ym = date("Ym",strtotime("-1 month"));
			$ymCur = date("Ym",strtotime(date("Y-m-d")));
		}

		$this->view->year = $y = substr($ym, 0, 4);
		$this->view->month = $m = substr($ym, 4, 2);
		$yCur = substr($ymCur, 0, 4);
		$mCur = substr($ymCur, 4, 2);

		$this->view->monthYear = date("F Y", strtotime($y."-".$m."-01"));

		$this->view->ident = $this->ident;
		
		Zend_Loader::LoadClass('issueClass', $this->modelDir);
		$issueClass = new issueClass();

		//$modus = $this->cache->load("modus_".$this->site_id."_1_".$ym);
		if(empty($modus))
		{		
			Zend_Loader::LoadClass('modusClass', $this->modelDir);
			$modusClass = new modusClass();	
			$modus = $modusClass->getModus('1');
			//$this->cache->save($modus, "modus_".$this->site_id."_1_".$ym, array("modus_".$this->site_id."_1_".$ym), 0);
		}

		//$totalModusPerMonth = $this->cache->load("total_modus_per_month_".$this->site_id."_1_".$ym);
		if(empty($totalModusPerMonth))
		{	
			$totalModusPerMonth =  array();
			for($b=1; $b<=$m; $b++) // get rekapitulasi utk bulan ini dan bulan2 sblmnya
			{
				$totalModus = $issueClass->getIssuesByModus($b, $y, '1');
				foreach($totalModus as $tm) {
					$totalModusPerMonth[$b][$tm['modus_id']] = $tm['total_modus'];
				}
			}
			//$this->cache->save($totalModusPerMonth, "total_modus_per_month_".$this->site_id."_1_".$ym, array("total_modus_per_month_".$this->site_id."_1_".$ym), 0);
		}
		else{
			$totalModus = $issueClass->getIssuesByModus($m, $y, '1');
			foreach($totalModus as $tm) {
				$totalModusPerMonth[$m][$tm['modus_id']] = $tm['total_modus'];
			}
		}
		
		$i=0;
		$k = 0;
		$rekap = array();
		$total_modus_perjan = $total_modus_perfeb = $total_modus_permar = $total_modus_perapr = $total_modus_permay = $total_modus_perjun = $total_modus_perjul = $total_modus_peraug = $total_modus_persep = $total_modus_peroct = $total_modus_pernov = $total_modus_perdec = 0; 
		foreach($modus as $mo)
		{
			if($mo['kejadian'] != $modus[$k-1]['kejadian'])
			{
				if($i > 0) $rekap[$i-1]['total_modus'] = $j;
				$rekap[$i]['kejadian_name'] = $mo['kejadian'];
				$rekap[$i]['kejadian_id'] = $mo['kejadian_id'];
				$analisa_hari = $issueClass->getMonthlyIssuesByDay($mo['kejadian_id'], $m, $y, '1');
				if(!empty($analisa_hari))
				{
					foreach($analisa_hari as $ah) {
						$rekap[$i]['analisa_hari'][$ah['day']]= $ah['total'];
					}
				}
				$rekap[$i]['analisa_jam'][0] = $issueClass->getMonthlyIssuesByTime($mo['kejadian_id'], $m, $y, '09:00:00', '12:00:00', '1');
				$rekap[$i]['analisa_jam'][1] = $issueClass->getMonthlyIssuesByTime($mo['kejadian_id'], $m, $y, '12:00:00', '16:00:00', '1');
				$rekap[$i]['analisa_jam'][2] = $issueClass->getMonthlyIssuesByTime($mo['kejadian_id'], $m, $y, '16:00:00', '19:00:00', '1');
				$rekap[$i]['analisa_jam'][3] = $issueClass->getMonthlyIssuesByTime($mo['kejadian_id'], $m, $y, '19:00:00', '23:00:00', '1');
				$rekap[$i]['analisa_jam'][4] = $issueClass->getMonthlyIssuesByTime($mo['kejadian_id'], $m, $y, '23:00:00', '09:00:00', '1');
				$j = 0;
				$i++;	
			}
			$rekap[$i-1]['modus'][$j]['modus_name'] = $mo['modus']; 
			$rekap[$i-1]['modus'][$j]['total_modus_jan'] = $totalModusPerMonth['1'][$mo['modus_id']]; 
			$rekap[$i-1]['modus'][$j]['total_modus_feb'] = $totalModusPerMonth['2'][$mo['modus_id']]; 
			$rekap[$i-1]['modus'][$j]['total_modus_mar'] = $totalModusPerMonth['3'][$mo['modus_id']]; 
			$rekap[$i-1]['modus'][$j]['total_modus_apr'] = $totalModusPerMonth['4'][$mo['modus_id']]; 
			$rekap[$i-1]['modus'][$j]['total_modus_may'] = $totalModusPerMonth['5'][$mo['modus_id']]; 
			$rekap[$i-1]['modus'][$j]['total_modus_jun'] = $totalModusPerMonth['6'][$mo['modus_id']]; 
			$rekap[$i-1]['modus'][$j]['total_modus_jul'] = $totalModusPerMonth['7'][$mo['modus_id']]; 
			$rekap[$i-1]['modus'][$j]['total_modus_aug'] = $totalModusPerMonth['8'][$mo['modus_id']]; 
			$rekap[$i-1]['modus'][$j]['total_modus_sep'] = $totalModusPerMonth['9'][$mo['modus_id']]; 
			$rekap[$i-1]['modus'][$j]['total_modus_oct'] = $totalModusPerMonth['10'][$mo['modus_id']]; 
			$rekap[$i-1]['modus'][$j]['total_modus_nov'] = $totalModusPerMonth['11'][$mo['modus_id']]; 
			$rekap[$i-1]['modus'][$j]['total_modus_dec'] = $totalModusPerMonth['12'][$mo['modus_id']]; 
			$rekap[$i-1]['modus'][$j]['total_modus_peryear'] = intval($totalModusPerMonth['1'][$mo['modus_id']]) + intval($totalModusPerMonth['2'][$mo['modus_id']]) + intval($totalModusPerMonth['3'][$mo['modus_id']]) + intval($totalModusPerMonth['4'][$mo['modus_id']]) + intval($totalModusPerMonth['5'][$mo['modus_id']]) + intval($totalModusPerMonth['6'][$mo['modus_id']]) + intval($totalModusPerMonth['7'][$mo['modus_id']]) + intval($totalModusPerMonth['8'][$mo['modus_id']]) + intval($totalModusPerMonth['9'][$mo['modus_id']]) + intval($totalModusPerMonth['10'][$mo['modus_id']]) + intval($totalModusPerMonth['11'][$mo['modus_id']]) + intval($totalModusPerMonth['12'][$mo['modus_id']]); 
			$total_modus_perjan += intval($totalModusPerMonth['1'][$mo['modus_id']]);
			$total_modus_perfeb += intval($totalModusPerMonth['2'][$mo['modus_id']]);
			$total_modus_permar += intval($totalModusPerMonth['3'][$mo['modus_id']]);
			$total_modus_perapr += intval($totalModusPerMonth['4'][$mo['modus_id']]);
			$total_modus_permay += intval($totalModusPerMonth['5'][$mo['modus_id']]);
			$total_modus_perjun += intval($totalModusPerMonth['6'][$mo['modus_id']]);
			$total_modus_perjul += intval($totalModusPerMonth['7'][$mo['modus_id']]);
			$total_modus_peraug += intval($totalModusPerMonth['8'][$mo['modus_id']]);
			$total_modus_persep += intval($totalModusPerMonth['9'][$mo['modus_id']]);
			$total_modus_peroct += intval($totalModusPerMonth['10'][$mo['modus_id']]);
			$total_modus_pernov += intval($totalModusPerMonth['11'][$mo['modus_id']]);
			$total_modus_perdec += intval($totalModusPerMonth['12'][$mo['modus_id']]);
			$uraian_kejadian = $issueClass->getIssuesByModusId($mo['modus_id'], $m, $y, '1');
			$rekap[$i-1]['modus'][$j]['total_modus_cur_month'] = count($uraian_kejadian);
			if(!empty($uraian_kejadian))
			{
				$c = 1;
				foreach($uraian_kejadian as $uk) {
					$idate = explode(" ",$uk['issue_date']);
					$rekap[$i-1]['modus'][$j]['uraian_kejadian'] .= '<div style="padding-bottom:10px; margin-bottom:10px; border-bottom:1px solid #ddd;">'.$c.". ".date("l, j M Y", strtotime($idate[0]))." ".$idate[1]."</br>Location: ".$uk['floor']." - ".$uk['location']."<br/>Description: ".$uk['description']."</div>";
					$c++;
				}
			}
			$j++;
			$k++;
		}
		$rekap[$i-1]['total_modus'] = $j;
		$rekapTotal['total_modus_perjan'] = $total_modus_perjan;
		$rekapTotal['total_modus_perfeb'] = $total_modus_perfeb;
		$rekapTotal['total_modus_permar'] = $total_modus_permar;
		$rekapTotal['total_modus_perapr'] = $total_modus_perapr;
		$rekapTotal['total_modus_permay'] = $total_modus_permay;
		$rekapTotal['total_modus_perjun'] = $total_modus_perjun;
		$rekapTotal['total_modus_perjul'] = $total_modus_perjul;
		$rekapTotal['total_modus_peraug'] = $total_modus_peraug;
		$rekapTotal['total_modus_persep'] = $total_modus_persep;
		$rekapTotal['total_modus_peroct'] = $total_modus_peroct;
		$rekapTotal['total_modus_pernov'] = $total_modus_pernov;
		$rekapTotal['total_modus_perdec'] = $total_modus_perdec;
		$rekapTotal['total_modus_all'] = intval($rekapTotal['total_modus_perjan']) + intval($rekapTotal['total_modus_perfeb']) + intval($rekapTotal['total_modus_permar']) + intval($rekapTotal['total_modus_perapr']) + intval($rekapTotal['total_modus_permay']) + intval($rekapTotal['total_modus_perjun']) + intval($rekapTotal['total_modus_perjul']) + intval($rekapTotal['total_modus_peraug']) + intval($rekapTotal['total_modus_persep']) + intval($rekapTotal['total_modus_peroct']) + intval($rekapTotal['total_modus_pernov']) + intval($rekapTotal['total_modus_perdec']);
		//echo "<pre>"; print_r($rekap); exit();
		$this->view->rekap = $rekap;
		$this->view->rekapTotal = $rekapTotal;
		$this->view->urutan_hari_tertinggi = $issueClass->getTotalIssuesByDayDescending($m, $y, '1');
		$urutan_total_jam[0] = $issueClass->getTotalIssuesByTimePeriode($m, $y, '09:00:00', '12:00:00', '1');
		$urutan_total_jam[1] = $issueClass->getTotalIssuesByTimePeriode($m, $y, '12:00:00', '16:00:00', '1');
		$urutan_total_jam[2] = $issueClass->getTotalIssuesByTimePeriode($m, $y, '16:00:00', '19:00:00', '1');
		$urutan_total_jam[3] = $issueClass->getTotalIssuesByTimePeriode($m, $y, '19:00:00', '23:00:00', '1');
		$urutan_total_jam[4] = $issueClass->getTotalIssuesByTimePeriode($m, $y, '23:00:00', '09:00:00', '1');
		arsort($urutan_total_jam);

		$this->view->urutan_total_jam = $urutan_total_jam;

		$this->view->incidents = $issueClass->getSecurityIssueSummary($m, $y, $params['id']);

		$urutan_total_issue_tenant = $issueClass->getTotalIssuesByTenantPublik($m, $y, '0', '1');
		if(!empty($urutan_total_issue_tenant))
		{
			$urutan_total_all_issue_tenant = 0;
			foreach($urutan_total_issue_tenant as &$t)
			{
				$data = array();
				$data = $issueClass->getTotalIssuesByLocation($m, $y, '0', $t['location'], $t['floor_id'], '1');
				if(!empty($data))
				{
					foreach($data as $dt)
					{
							$t[$dt['kejadian_id']] = $dt['total_kejadian'];
					}
				}
				$urutan_total_all_issue_tenant += $t['total'];
			}
		}
		//echo "<pre>"; print_r($urutan_total_issue_tenant); exit();
		$this->view->urutan_total_issue_tenant = $urutan_total_issue_tenant;
		$this->view->urutan_total_all_issue_tenant = $urutan_total_all_issue_tenant;

		$urutan_total_issue_publik = $issueClass->getTotalIssuesByTenantPublik($m, $y, '1', '1');
		if(!empty($urutan_total_issue_publik))
		{
			$urutan_total_all_issue_publik = 0;
			foreach($urutan_total_issue_publik as &$p)
			{
				$data = array();
				$data = $issueClass->getTotalIssuesByLocation($m, $y, '1', $p['location'], $p['floor_id'], '1');
				
				if(!empty($data))
				{
					$dt = array();
					foreach($data as $dt)
					{
							$p[$dt['kejadian_id']] = $dt['total_kejadian'];
					}
				}
				$urutan_total_all_issue_publik += $p['total'];
			}
		}
		//echo "<pre>"; print_r($urutan_total_issue_publik); exit();
		$this->view->urutan_total_issue_publik = $urutan_total_issue_publik;
		$this->view->urutan_total_all_issue_publik = $urutan_total_all_issue_publik;

		$total_all_tangkapan = 0;
		$total_tangkapan_monthly = array();
		$list_tangkapan = $issueClass->getPelakuTertangkapByCategory($y, '1');
		foreach($list_tangkapan as &$tangkapan)
		{
			$pelaku_tertangkap_monthly = $issueClass->getMonthlyPelakuTertangkapByModus($m, $y, $tangkapan['modus_id']);
			foreach($pelaku_tertangkap_monthly as $ptm)
			{
				$tangkapan['monthly'][$ptm['mo']] = $ptm['total_permonth'];
				$total_tangkapan_monthly[$ptm['mo']] += intval($ptm['total_permonth']);
			}
			$total_all_tangkapan += $tangkapan['total_peryear'];
		}
		//echo "<pre>"; print_r($total_tangkapan_monthly); exit();
		$this->view->list_tangkapan = $list_tangkapan;
		$this->view->total_all_tangkapan = $total_all_tangkapan;
		$this->view->total_tangkapan_monthly = $total_tangkapan_monthly;

		$pelaku_tertangkap_detail = $issueClass->getPelakuTertangkapDetail($m, $y, '1');
		foreach($pelaku_tertangkap_detail as &$pelaku)
		{
			$tgl = explode(" ", $pelaku['issue_date']);
			$pelaku['date'] = date("j M Y", strtotime($tgl[0]))." ".$tgl[1];
		}
		$this->view->pelaku_tertangkap_detail = $pelaku_tertangkap_detail;

		$listIssues = $issueClass->getMonthlyAnalysisIssues($m, $y, '1');
		foreach($listIssues as &$issue)
		{
			$tgl = explode(" ", $issue['issue_date']);
			$issue['date'] = date("j M Y", strtotime($tgl[0]))." ".$tgl[1];
		}
		$this->view->listIssues = $listIssues;

		Zend_Loader::LoadClass('incidentClass', $this->modelDir);
		$incidentClass = new incidentClass();	
		$this->view->listKejadian = $incidentClass->getIncidentByCategoryId('1');


		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "Add Security Monthly Analysis";
		$logData['data'] = json_encode($params);
		$logsTable->insertLogs($logData);	

		$this->renderTemplate('form_security_monthly_analysis.tpl'); 
	}

	public function savemonthlyanalysisAction() {
		$params = $this->_getAllParams();

		$params['user_id'] = $this->ident['user_id'];
		if(empty($params['monthly_analysis_id']))
		{
			Zend_Loader::LoadClass('securityClass', $this->modelDir);
			$securityClass = new securityClass();
			$params['monthly_analysis_id'] = $securityClass->saveMonthlyAnalysis($params);
		}
		
		Zend_Loader::LoadClass('issueClass', $this->modelDir);
		$issueClass = new issueClass();
		$this->view->incidents = $issueClass->getSecurityIssueSummary(date("m"), date("Y"));

		Zend_Loader::LoadClass('monthlyanalysissummaryClass', $this->modelDir);
		$monthlyanalysissummaryClass = new monthlyanalysissummaryClass();
		$data = array();
		$i=0;
		foreach($params['summary_id'] as $summary_id)
		{
			$data['summary_id'] = $summary_id;
			$data['monthly_analysis_id'] = $params['monthly_analysis_id'];
			$data['kejadian_id'] = $params['kejadian_id'][$i];
			$data['analisa'] = addslashes(str_replace("\n","<br>",$params['analisa'][$i]));
			$data['tindakan'] = addslashes(str_replace("\n","<br>",$params['tindakan'][$i]));
			$data['user_id'] = $this->ident['user_id'];
			$monthlyanalysissummaryClass->saveMonthlyAnalysis($data, '1');
			$i++;
		}

		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "Save Security Monthly Analysis";
		$logData['data'] = json_encode($params);
		$logsTable->insertLogs($logData);	

		$this->_response->setRedirect($this->baseUrl.'/default/security/viewmonthlyanalysis');
		$this->_response->sendResponse();
		exit();
	}

	public function viewmonthlyanalysisAction() {
		$params = $this->_getAllParams();
		$this->view->ident = $this->ident;
		if(empty($params['start'])) $params['start'] = '0';
		$params['pagesize'] = 10;
		$this->view->start = $params['start'];
		Zend_Loader::LoadClass('securityClass', $this->modelDir);
		$securityClass = new securityClass();
		$monthlyAnalysis = $securityClass->getMonthlyAnalysis($params);
		foreach($monthlyAnalysis as &$ma)
		{
			$date = explode(" ", $ma['save_date']);
			$dt = explode("-", $date[0]);
			$ma['monthyear'] = date("F Y", mktime(0, 0, 0, $dt[1]-1, $dt[2], $dt[0]));			
			$ma['yearmonth'] = $dt[0].$dt[1];
			if((date("j") <= 10 && $ma['monthyear'] == date("F Y", strtotime("-1 month"))) || in_array(1, $this->ident['role_ids'])) 
			{
				$ma['allowEdit'] = 1;
			}
			else {
				$ma['allowEdit'] = 0;
			}
		}
		$this->view->monthlyAnalysis = $monthlyAnalysis;

		$totalMonthlyAnalysis = $securityClass->getTotalMonthlyAnalysis();
		if($totalMonthlyAnalysis > 10)
		{
			if($params['start'] >= 10)
			{
				$this->view->firstPageUrl = "/default/security/viewmonthlyanalysis";
				$this->view->prevUrl = "/default/security/viewmonthlyanalysis/start/".($params['start']-$params['pagesize']);
			}
			if($params['start'] < (floor(($totalMonthlyAnalysis-1)/10)*10))
			{
				$this->view->nextUrl = "/default/security/viewmonthlyanalysis/start/".($params['start']+$params['pagesize']);
				$this->view->lastPageUrl = "/default/security/viewmonthlyanalysis/start/".(floor(($totalMonthlyAnalysis-1)/10)*10);
			}
		}

		$this->view->curPage = ($params['start']/$params['pagesize'])+1;
		$this->view->totalPage = ceil($totalMonthlyAnalysis/$params['pagesize']);
		$this->view->startRec = $params['start'] + 1;
		$endRec = $params['start'] + $params['pagesize'];
		if($totalMonthlyAnalysis >=  $endRec) $this->view->endRec =  $endRec;
		else $this->view->endRec =  $totalMonthlyAnalysis;		
		$this->view->totalRec = $totalMonthlyAnalysis;

		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "View Security Monthly Analysis List";
		$logData['data'] = "";
		$logsTable->insertLogs($logData);	

		$this->renderTemplate('view_security_monthly_analysis.tpl'); 
	}

	public function viewdetailmonthlyanalysisAction() {
		$params = $this->_getAllParams();

		if(!empty($params['id'])) 
		{
			$this->view->monthly_analysis_id = $params['id'];		
			Zend_Loader::LoadClass('securityClass', $this->modelDir);
			$securityClass = new securityClass();
			$monthly_analysis = $securityClass->geMonthlyAnalysisById($params['id']);
			$monthly_analysis_datetime = explode(" ", $monthly_analysis['save_date']);
			$monthly_analysis_date = explode("-", $monthly_analysis_datetime[0]);	
			$ym = date("Ym", mktime(0, 0, 0, $monthly_analysis_date[1]-1, $monthly_analysis_date[2], $monthly_analysis_date[0]));
			$ymCur = date("Ym", mktime(0, 0, 0, $monthly_analysis_date[1], $monthly_analysis_date[2], $monthly_analysis_date[0]));
			
			$this->view->year = $y = substr($ym, 0, 4);
			$this->view->month = $m = substr($ym, 4, 2);	
			
			$yCur = substr($ymCur, 0, 4);
			$mCur = substr($ymCur, 4, 2);

			$this->view->monthYear = date("F Y", strtotime($y."-".$m."-01"));


			Zend_Loader::LoadClass('issueClass', $this->modelDir);
			$issueClass = new issueClass();

			//$modus = $this->cache->load("modus_".$this->site_id."_1_");
			if(empty($modus))
			{		
				Zend_Loader::LoadClass('modusClass', $this->modelDir);
				$modusClass = new modusClass();	
				$modus = $modusClass->getModus('1');
			}

			//$totalModusPerMonth = $this->cache->load("total_modus_per_month_".$this->site_id."_1_".$ym);
			if(empty($totalModusPerMonth))
			{	
				$totalModusPerMonth =  array();
				for($b=1; $b<=$m; $b++) // get rekapitulasi utk bulan ini dan bulan2 sblmnya
				{
					$totalModus = $issueClass->getIssuesByModus($b, $y, '1');
					foreach($totalModus as $tm) {
						$totalModusPerMonth[$b][$tm['modus_id']] = $tm['total_modus'];
					}
				}
			}
			else{
				$totalModus = $issueClass->getIssuesByModus($m, $y, '1');
				foreach($totalModus as $tm) {
					$totalModusPerMonth[$m][$tm['modus_id']] = $tm['total_modus'];
				}
			}
			
			$i=0;
			$k = 0;
			$rekap = array();
			$total_modus_perjan = $total_modus_perfeb = $total_modus_permar = $total_modus_perapr = $total_modus_permay = $total_modus_perjun = $total_modus_perjul = $total_modus_peraug = $total_modus_persep = $total_modus_peroct = $total_modus_pernov = $total_modus_perdec = 0; 
			if(!empty($modus))
			{
				foreach($modus as $mo)
				{
					if($mo['kejadian'] != $modus[$k-1]['kejadian'])
					{
						if($i > 0) $rekap[$i-1]['total_modus'] = $j;
						$rekap[$i]['kejadian_name'] = $mo['kejadian'];
						$rekap[$i]['kejadian_id'] = $mo['kejadian_id'];
						$analisa_hari = $issueClass->getMonthlyIssuesByDay($mo['kejadian_id'], $m, $y, '1');
						
						if(!empty($analisa_hari))
						{
							foreach($analisa_hari as $ah) {
								$rekap[$i]['analisa_hari'][$ah['day']]= $ah['total'];
							}
						}
						$rekap[$i]['analisa_jam'][0] = $issueClass->getMonthlyIssuesByTime($mo['kejadian_id'], $m, $y, '09:00:00', '12:00:00', '1');
						$rekap[$i]['analisa_jam'][1] = $issueClass->getMonthlyIssuesByTime($mo['kejadian_id'], $m, $y, '12:00:00', '16:00:00', '1');
						$rekap[$i]['analisa_jam'][2] = $issueClass->getMonthlyIssuesByTime($mo['kejadian_id'], $m, $y, '16:00:00', '19:00:00', '1');
						$rekap[$i]['analisa_jam'][3] = $issueClass->getMonthlyIssuesByTime($mo['kejadian_id'], $m, $y, '19:00:00', '23:00:00', '1');
						$rekap[$i]['analisa_jam'][4] = $issueClass->getMonthlyIssuesByTime($mo['kejadian_id'], $m, $y, '23:00:00', '09:00:00', '1');
						$j = 0;
						$i++;	
					}
					$rekap[$i-1]['modus'][$j]['modus_name'] = $mo['modus']; 
					$rekap[$i-1]['modus'][$j]['total_modus_jan'] = $totalModusPerMonth['1'][$mo['modus_id']]; 
					$rekap[$i-1]['modus'][$j]['total_modus_feb'] = $totalModusPerMonth['2'][$mo['modus_id']]; 
					$rekap[$i-1]['modus'][$j]['total_modus_mar'] = $totalModusPerMonth['3'][$mo['modus_id']]; 
					$rekap[$i-1]['modus'][$j]['total_modus_apr'] = $totalModusPerMonth['4'][$mo['modus_id']]; 
					$rekap[$i-1]['modus'][$j]['total_modus_may'] = $totalModusPerMonth['5'][$mo['modus_id']]; 
					$rekap[$i-1]['modus'][$j]['total_modus_jun'] = $totalModusPerMonth['6'][$mo['modus_id']]; 
					$rekap[$i-1]['modus'][$j]['total_modus_jul'] = $totalModusPerMonth['7'][$mo['modus_id']]; 
					$rekap[$i-1]['modus'][$j]['total_modus_aug'] = $totalModusPerMonth['8'][$mo['modus_id']]; 
					$rekap[$i-1]['modus'][$j]['total_modus_sep'] = $totalModusPerMonth['9'][$mo['modus_id']]; 
					$rekap[$i-1]['modus'][$j]['total_modus_oct'] = $totalModusPerMonth['10'][$mo['modus_id']]; 
					$rekap[$i-1]['modus'][$j]['total_modus_nov'] = $totalModusPerMonth['11'][$mo['modus_id']]; 
					$rekap[$i-1]['modus'][$j]['total_modus_dec'] = $totalModusPerMonth['12'][$mo['modus_id']]; 
					$rekap[$i-1]['modus'][$j]['total_modus_peryear'] = intval($totalModusPerMonth['1'][$mo['modus_id']]) + intval($totalModusPerMonth['2'][$mo['modus_id']]) + intval($totalModusPerMonth['3'][$mo['modus_id']]) + intval($totalModusPerMonth['4'][$mo['modus_id']]) + intval($totalModusPerMonth['5'][$mo['modus_id']]) + intval($totalModusPerMonth['6'][$mo['modus_id']]) + intval($totalModusPerMonth['7'][$mo['modus_id']]) + intval($totalModusPerMonth['8'][$mo['modus_id']]) + intval($totalModusPerMonth['9'][$mo['modus_id']]) + intval($totalModusPerMonth['10'][$mo['modus_id']]) + intval($totalModusPerMonth['11'][$mo['modus_id']]) + intval($totalModusPerMonth['12'][$mo['modus_id']]); 
					$total_modus_perjan += intval($totalModusPerMonth['1'][$mo['modus_id']]);
					$total_modus_perfeb += intval($totalModusPerMonth['2'][$mo['modus_id']]);
					$total_modus_permar += intval($totalModusPerMonth['3'][$mo['modus_id']]);
					$total_modus_perapr += intval($totalModusPerMonth['4'][$mo['modus_id']]);
					$total_modus_permay += intval($totalModusPerMonth['5'][$mo['modus_id']]);
					$total_modus_perjun += intval($totalModusPerMonth['6'][$mo['modus_id']]);
					$total_modus_perjul += intval($totalModusPerMonth['7'][$mo['modus_id']]);
					$total_modus_peraug += intval($totalModusPerMonth['8'][$mo['modus_id']]);
					$total_modus_persep += intval($totalModusPerMonth['9'][$mo['modus_id']]);
					$total_modus_peroct += intval($totalModusPerMonth['10'][$mo['modus_id']]);
					$total_modus_pernov += intval($totalModusPerMonth['11'][$mo['modus_id']]);
					$total_modus_perdec += intval($totalModusPerMonth['12'][$mo['modus_id']]);
					$uraian_kejadian = $issueClass->getIssuesByModusId($mo['modus_id'], $m, $y, '1');
					$rekap[$i-1]['modus'][$j]['total_modus_cur_month'] = count($uraian_kejadian);
					if(!empty($uraian_kejadian))
					{
						$c = 1;
						foreach($uraian_kejadian as $uk) {
							$idate = explode(" ",$uk['issue_date']);
							$rekap[$i-1]['modus'][$j]['uraian_kejadian'] .= '<div style="padding-bottom:10px; margin-bottom:10px; border-bottom:1px solid #ddd;">'.$c.". ".date("l, j M Y", strtotime($idate[0]))." ".$idate[1]."</br>Location: ".$uk['floor']." - ".$uk['location']."<br/>Description: ".$uk['description']."</div>";
							$c++;
						}
					}
					$j++;
					$k++;
				}
				$rekap[$i-1]['total_modus'] = $j;
				$rekapTotal['total_modus_perjan'] = $total_modus_perjan;
				$rekapTotal['total_modus_perfeb'] = $total_modus_perfeb;
				$rekapTotal['total_modus_permar'] = $total_modus_permar;
				$rekapTotal['total_modus_perapr'] = $total_modus_perapr;
				$rekapTotal['total_modus_permay'] = $total_modus_permay;
				$rekapTotal['total_modus_perjun'] = $total_modus_perjun;
				$rekapTotal['total_modus_perjul'] = $total_modus_perjul;
				$rekapTotal['total_modus_peraug'] = $total_modus_peraug;
				$rekapTotal['total_modus_persep'] = $total_modus_persep;
				$rekapTotal['total_modus_peroct'] = $total_modus_peroct;
				$rekapTotal['total_modus_pernov'] = $total_modus_pernov;
				$rekapTotal['total_modus_perdec'] = $total_modus_perdec;
				$rekapTotal['total_modus_all'] = intval($rekapTotal['total_modus_perjan']) + intval($rekapTotal['total_modus_perfeb']) + intval($rekapTotal['total_modus_permar']) + intval($rekapTotal['total_modus_perapr']) + intval($rekapTotal['total_modus_permay']) + intval($rekapTotal['total_modus_perjun']) + intval($rekapTotal['total_modus_perjul']) + intval($rekapTotal['total_modus_peraug']) + intval($rekapTotal['total_modus_persep']) + intval($rekapTotal['total_modus_peroct']) + intval($rekapTotal['total_modus_pernov']) + intval($rekapTotal['total_modus_perdec']);
				//echo "<pre>"; print_r($rekap); exit();
				$this->view->rekap = $rekap;
				$this->view->rekapTotal = $rekapTotal;
				$this->view->urutan_hari_tertinggi = $issueClass->getTotalIssuesByDayDescending($m, $y, '1');
				$urutan_total_jam[0] = $issueClass->getTotalIssuesByTimePeriode($m, $y, '09:00:00', '12:00:00', '1');
				$urutan_total_jam[1] = $issueClass->getTotalIssuesByTimePeriode($m, $y, '12:00:00', '16:00:00', '1');
				$urutan_total_jam[2] = $issueClass->getTotalIssuesByTimePeriode($m, $y, '16:00:00', '19:00:00', '1');
				$urutan_total_jam[3] = $issueClass->getTotalIssuesByTimePeriode($m, $y, '19:00:00', '23:00:00', '1');
				$urutan_total_jam[4] = $issueClass->getTotalIssuesByTimePeriode($m, $y, '23:00:00', '09:00:00', '1');
				arsort($urutan_total_jam);
				$this->view->urutan_total_jam = $urutan_total_jam;

				$this->view->incidents = $issueClass->getSecurityIssueSummary($m, $y, $params['id']);
				$urutan_total_issue_tenant = $issueClass->getTotalIssuesByTenantPublik($m, $y, '0', '1');
				if(!empty($urutan_total_issue_tenant))
				{
					$urutan_total_all_issue_tenant = 0;
					foreach($urutan_total_issue_tenant as &$t)
					{
						$data = array();
						$data = $issueClass->getTotalIssuesByLocation($m, $y, '0', $t['location'], $t['floor_id'], '1');
						if(!empty($data))
						{
							foreach($data as $dt)
							{
									$t[$dt['kejadian_id']] = $dt['total_kejadian'];
							}
						}
						$urutan_total_all_issue_tenant += $t['total'];
					}
				}
				//echo "<pre>"; print_r($urutan_total_issue_tenant); exit();
				$this->view->urutan_total_issue_tenant = $urutan_total_issue_tenant;
				$this->view->urutan_total_all_issue_tenant = $urutan_total_all_issue_tenant;

				$urutan_total_issue_publik = $issueClass->getTotalIssuesByTenantPublik($m, $y, '1', '1');
				if(!empty($urutan_total_issue_publik))
				{
					$urutan_total_all_issue_publik = 0;
					foreach($urutan_total_issue_publik as &$p)
					{
						$data = array();
						$data = $issueClass->getTotalIssuesByLocation($m, $y, '1', $p['location'], $p['floor_id'], '1');
						
						if(!empty($data))
						{
							$dt = array();
							foreach($data as $dt)
							{
									$p[$dt['kejadian_id']] = $dt['total_kejadian'];
							}
						}
						$urutan_total_all_issue_publik += $p['total'];
					}
				}
				//echo "<pre>"; print_r($urutan_total_issue_publik); exit();
				$this->view->urutan_total_issue_publik = $urutan_total_issue_publik;
				$this->view->urutan_total_all_issue_publik = $urutan_total_all_issue_publik;
			}

			$total_all_tangkapan = 0;
			$total_tangkapan_monthly = array();
			$list_tangkapan = $issueClass->getPelakuTertangkapByCategory($y, '1');
			foreach($list_tangkapan as &$tangkapan)
			{
				$pelaku_tertangkap_monthly = $issueClass->getMonthlyPelakuTertangkapByModus($m, $y, $tangkapan['modus_id']);
				foreach($pelaku_tertangkap_monthly as $ptm)
				{
					$tangkapan['monthly'][$ptm['mo']] = $ptm['total_permonth'];
					$total_tangkapan_monthly[$ptm['mo']] += intval($ptm['total_permonth']);
				}
				$total_all_tangkapan += $tangkapan['total_peryear'];
			}
			//echo "<pre>"; print_r($total_tangkapan_monthly); exit();
			$this->view->list_tangkapan = $list_tangkapan;
			$this->view->total_all_tangkapan = $total_all_tangkapan;
			$this->view->total_tangkapan_monthly = $total_tangkapan_monthly;

			$pelaku_tertangkap_detail = $issueClass->getPelakuTertangkapDetail($m, $y, '1');
			foreach($pelaku_tertangkap_detail as &$pelaku)
			{
				$tgl = explode(" ", $pelaku['issue_date']);
				$pelaku['date'] = date("j M Y", strtotime($tgl[0]))." ".$tgl[1];
			}
			$this->view->pelaku_tertangkap_detail = $pelaku_tertangkap_detail;

			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "View Security Monthly Analysis Detail";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);	

			//echo "<pre>"; print_r($pelaku_tertangkap_detail); exit();
			$this->renderTemplate('security_monthly_analysis_detail.tpl'); 
		}
	}

	public function downloadsecuritymonthlyanalysisAction() {		
		$params = $this->_getAllParams();

		if(!empty($params['id']))
		{			
			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "Export Security Monthly Analysis Report to PDF";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);

			$monthly_analysis_id = $params['id'];		
			Zend_Loader::LoadClass('securityClass', $this->modelDir);
			$securityClass = new securityClass();
			$monthly_analysis = $securityClass->geMonthlyAnalysisById($params['id']);
			$monthly_analysis_datetime = explode(" ", $monthly_analysis['save_date']);
			$monthly_analysis_date = explode("-", $monthly_analysis_datetime[0]);	
			$ym = date("Ym", mktime(0, 0, 0, $monthly_analysis_date[1]-1, $monthly_analysis_date[2], $monthly_analysis_date[0]));
			$ymCur = date("Ym", mktime(0, 0, 0, $monthly_analysis_date[1], $monthly_analysis_date[2], $monthly_analysis_date[0]));
			
			$y = substr($ym, 0, 4);
			$m = substr($ym, 4, 2);	
			
			$yCur = substr($ymCur, 0, 4);
			$mCur = substr($ymCur, 4, 2);

			$monthYear = date("F Y", strtotime($y."-".$m."-01"));


			Zend_Loader::LoadClass('issueClass', $this->modelDir);
			$issueClass = new issueClass();

			//$modus = $this->cache->load("modus_".$this->site_id."_1_");
			if(empty($modus))
			{		
				Zend_Loader::LoadClass('modusClass', $this->modelDir);
				$modusClass = new modusClass();	
				$modus = $modusClass->getModus('1');
			}

			//$totalModusPerMonth = $this->cache->load("total_modus_per_month_".$this->site_id."_1_".$ym);
			if(empty($totalModusPerMonth))
			{	
				$totalModusPerMonth =  array();
				for($b=1; $b<=$m; $b++) // get rekapitulasi utk bulan ini dan bulan2 sblmnya
				{
					$totalModus = $issueClass->getIssuesByModus($b, $y, '1');
					foreach($totalModus as $tm) {
						$totalModusPerMonth[$b][$tm['modus_id']] = $tm['total_modus'];
					}
				}
			}
			else{
				$totalModus = $issueClass->getIssuesByModus($m, $y, '1');
				foreach($totalModus as $tm) {
					$totalModusPerMonth[$m][$tm['modus_id']] = $tm['total_modus'];
				}
			}
			
			$i=0;
			$k = 0;
			$rekap = array();
			$total_modus_perjan = $total_modus_perfeb = $total_modus_permar = $total_modus_perapr = $total_modus_permay = $total_modus_perjun = $total_modus_perjul = $total_modus_peraug = $total_modus_persep = $total_modus_peroct = $total_modus_pernov = $total_modus_perdec = 0; 
			if(!empty($modus))
			{
				foreach($modus as $mo)
				{
					if($mo['kejadian'] != $modus[$k-1]['kejadian'])
					{
						if($i > 0) $rekap[$i-1]['total_modus'] = $j;
						$rekap[$i]['kejadian_name'] = $mo['kejadian'];
						$rekap[$i]['kejadian_id'] = $mo['kejadian_id'];
						$analisa_hari = $issueClass->getMonthlyIssuesByDay($mo['kejadian_id'], $m, $y, '1');
						
						if(!empty($analisa_hari))
						{
							foreach($analisa_hari as $ah) {
								$rekap[$i]['analisa_hari'][$ah['day']]= $ah['total'];
							}
						}
						$rekap[$i]['analisa_jam'][0] = $issueClass->getMonthlyIssuesByTime($mo['kejadian_id'], $m, $y, '09:00:00', '12:00:00', '1');
						$rekap[$i]['analisa_jam'][1] = $issueClass->getMonthlyIssuesByTime($mo['kejadian_id'], $m, $y, '12:00:00', '16:00:00', '1');
						$rekap[$i]['analisa_jam'][2] = $issueClass->getMonthlyIssuesByTime($mo['kejadian_id'], $m, $y, '16:00:00', '19:00:00', '1');
						$rekap[$i]['analisa_jam'][3] = $issueClass->getMonthlyIssuesByTime($mo['kejadian_id'], $m, $y, '19:00:00', '23:00:00', '1');
						$rekap[$i]['analisa_jam'][4] = $issueClass->getMonthlyIssuesByTime($mo['kejadian_id'], $m, $y, '23:00:00', '09:00:00', '1');
						$j = 0;
						$i++;	
					}
					$rekap[$i-1]['modus'][$j]['modus_name'] = $mo['modus']; 
					$rekap[$i-1]['modus'][$j]['total_modus_jan'] = $totalModusPerMonth['1'][$mo['modus_id']]; 
					$rekap[$i-1]['modus'][$j]['total_modus_feb'] = $totalModusPerMonth['2'][$mo['modus_id']]; 
					$rekap[$i-1]['modus'][$j]['total_modus_mar'] = $totalModusPerMonth['3'][$mo['modus_id']]; 
					$rekap[$i-1]['modus'][$j]['total_modus_apr'] = $totalModusPerMonth['4'][$mo['modus_id']]; 
					$rekap[$i-1]['modus'][$j]['total_modus_may'] = $totalModusPerMonth['5'][$mo['modus_id']]; 
					$rekap[$i-1]['modus'][$j]['total_modus_jun'] = $totalModusPerMonth['6'][$mo['modus_id']]; 
					$rekap[$i-1]['modus'][$j]['total_modus_jul'] = $totalModusPerMonth['7'][$mo['modus_id']]; 
					$rekap[$i-1]['modus'][$j]['total_modus_aug'] = $totalModusPerMonth['8'][$mo['modus_id']]; 
					$rekap[$i-1]['modus'][$j]['total_modus_sep'] = $totalModusPerMonth['9'][$mo['modus_id']]; 
					$rekap[$i-1]['modus'][$j]['total_modus_oct'] = $totalModusPerMonth['10'][$mo['modus_id']]; 
					$rekap[$i-1]['modus'][$j]['total_modus_nov'] = $totalModusPerMonth['11'][$mo['modus_id']]; 
					$rekap[$i-1]['modus'][$j]['total_modus_dec'] = $totalModusPerMonth['12'][$mo['modus_id']]; 
					$rekap[$i-1]['modus'][$j]['total_modus_peryear'] = intval($totalModusPerMonth['1'][$mo['modus_id']]) + intval($totalModusPerMonth['2'][$mo['modus_id']]) + intval($totalModusPerMonth['3'][$mo['modus_id']]) + intval($totalModusPerMonth['4'][$mo['modus_id']]) + intval($totalModusPerMonth['5'][$mo['modus_id']]) + intval($totalModusPerMonth['6'][$mo['modus_id']]) + intval($totalModusPerMonth['7'][$mo['modus_id']]) + intval($totalModusPerMonth['8'][$mo['modus_id']]) + intval($totalModusPerMonth['9'][$mo['modus_id']]) + intval($totalModusPerMonth['10'][$mo['modus_id']]) + intval($totalModusPerMonth['11'][$mo['modus_id']]) + intval($totalModusPerMonth['12'][$mo['modus_id']]); 
					$total_modus_perjan += intval($totalModusPerMonth['1'][$mo['modus_id']]);
					$total_modus_perfeb += intval($totalModusPerMonth['2'][$mo['modus_id']]);
					$total_modus_permar += intval($totalModusPerMonth['3'][$mo['modus_id']]);
					$total_modus_perapr += intval($totalModusPerMonth['4'][$mo['modus_id']]);
					$total_modus_permay += intval($totalModusPerMonth['5'][$mo['modus_id']]);
					$total_modus_perjun += intval($totalModusPerMonth['6'][$mo['modus_id']]);
					$total_modus_perjul += intval($totalModusPerMonth['7'][$mo['modus_id']]);
					$total_modus_peraug += intval($totalModusPerMonth['8'][$mo['modus_id']]);
					$total_modus_persep += intval($totalModusPerMonth['9'][$mo['modus_id']]);
					$total_modus_peroct += intval($totalModusPerMonth['10'][$mo['modus_id']]);
					$total_modus_pernov += intval($totalModusPerMonth['11'][$mo['modus_id']]);
					$total_modus_perdec += intval($totalModusPerMonth['12'][$mo['modus_id']]);
					$uraian_kejadian = $issueClass->getIssuesByModusId($mo['modus_id'], $m, $y, '1');
					$rekap[$i-1]['modus'][$j]['total_modus_cur_month'] = count($uraian_kejadian);
					if(!empty($uraian_kejadian))
					{
						$c = 1;
						foreach($uraian_kejadian as $uk) {
							$idate = explode(" ",$uk['issue_date']);
							$rekap[$i-1]['modus'][$j]['uraian_kejadian'][$c-1] = $c.". ".date("l, j M Y", strtotime($idate[0]))." ".$idate[1]."\nLocation: ".$uk['floor']." - ".$uk['location']."\nDescription: ".$uk['description'];
							$c++;
						}
					}
					$j++;
					$k++;
				}
				$rekap[$i-1]['total_modus'] = $j;
				$rekapTotal['total_modus_perjan'] = $total_modus_perjan;
				$rekapTotal['total_modus_perfeb'] = $total_modus_perfeb;
				$rekapTotal['total_modus_permar'] = $total_modus_permar;
				$rekapTotal['total_modus_perapr'] = $total_modus_perapr;
				$rekapTotal['total_modus_permay'] = $total_modus_permay;
				$rekapTotal['total_modus_perjun'] = $total_modus_perjun;
				$rekapTotal['total_modus_perjul'] = $total_modus_perjul;
				$rekapTotal['total_modus_peraug'] = $total_modus_peraug;
				$rekapTotal['total_modus_persep'] = $total_modus_persep;
				$rekapTotal['total_modus_peroct'] = $total_modus_peroct;
				$rekapTotal['total_modus_pernov'] = $total_modus_pernov;
				$rekapTotal['total_modus_perdec'] = $total_modus_perdec;
				$rekapTotal['total_modus_all'] = intval($rekapTotal['total_modus_perjan']) + intval($rekapTotal['total_modus_perfeb']) + intval($rekapTotal['total_modus_permar']) + intval($rekapTotal['total_modus_perapr']) + intval($rekapTotal['total_modus_permay']) + intval($rekapTotal['total_modus_perjun']) + intval($rekapTotal['total_modus_perjul']) + intval($rekapTotal['total_modus_peraug']) + intval($rekapTotal['total_modus_persep']) + intval($rekapTotal['total_modus_peroct']) + intval($rekapTotal['total_modus_pernov']) + intval($rekapTotal['total_modus_perdec']);
				
				$urutan_hari_tertinggi = $issueClass->getTotalIssuesByDayDescending($m, $y, '1');
				$urutan_total_jam[0] = $issueClass->getTotalIssuesByTimePeriode($m, $y, '09:00:00', '12:00:00', '1');
				$urutan_total_jam[1] = $issueClass->getTotalIssuesByTimePeriode($m, $y, '12:00:00', '16:00:00', '1');
				$urutan_total_jam[2] = $issueClass->getTotalIssuesByTimePeriode($m, $y, '16:00:00', '19:00:00', '1');
				$urutan_total_jam[3] = $issueClass->getTotalIssuesByTimePeriode($m, $y, '19:00:00', '23:00:00', '1');
				$urutan_total_jam[4] = $issueClass->getTotalIssuesByTimePeriode($m, $y, '23:00:00', '09:00:00', '1');
				arsort($urutan_total_jam);

				$incidents = $issueClass->getSecurityIssueSummary($m, $y, $params['id']);
				$urutan_total_issue_tenant = $issueClass->getTotalIssuesByTenantPublik($m, $y, '0', '1');
				
				if(!empty($urutan_total_issue_tenant))
				{
					$urutan_total_all_issue_tenant = 0;
					foreach($urutan_total_issue_tenant as &$tenant)
					{
						$data = array();
						$data = $issueClass->getTotalIssuesByLocation($m, $y, '0', $tenant['location'], $tenant['floor_id'], '1');
						if(!empty($data))
						{
							foreach($data as $dt)
							{
									$tenant[$dt['kejadian_id']] = $dt['total_kejadian'];
							}
						}
						$urutan_total_all_issue_tenant += $tenant['total'];
					}
				}

				$urutan_total_issue_publik = $issueClass->getTotalIssuesByTenantPublik($m, $y, '1', '1');
				if(!empty($urutan_total_issue_publik))
				{
					$urutan_total_all_issue_publik = 0;
					foreach($urutan_total_issue_publik as &$p)
					{
						$data = array();
						$data = $issueClass->getTotalIssuesByLocation($m, $y, '1', $p['location'], $p['floor_id'], '1');
						
						if(!empty($data))
						{
							$dt = array();
							foreach($data as $dt)
							{
									$p[$dt['kejadian_id']] = $dt['total_kejadian'];
							}
						}
						$urutan_total_all_issue_publik += $p['total'];
					}
				}
			}

			$total_all_tangkapan = 0;
			$total_tangkapan_monthly = array();
			$list_tangkapan = $issueClass->getPelakuTertangkapByCategory($y, '1');
			foreach($list_tangkapan as &$tangkapan)
			{
				$pelaku_tertangkap_monthly = $issueClass->getMonthlyPelakuTertangkapByModus($m, $y, $tangkapan['modus_id']);
				foreach($pelaku_tertangkap_monthly as $ptm)
				{
					$tangkapan['monthly'][$ptm['mo']] = $ptm['total_permonth'];
					$total_tangkapan_monthly[$ptm['mo']] += intval($ptm['total_permonth']);
				}
				$total_all_tangkapan += $tangkapan['total_peryear'];
			}

			$pelaku_tertangkap_detail = $issueClass->getPelakuTertangkapDetail($m, $y, '1');
			foreach($pelaku_tertangkap_detail as &$pelaku)
			{
				$tgl = explode(" ", $pelaku['issue_date']);
				$pelaku['date'] = date("j M Y", strtotime($tgl[0]))." ".$tgl[1];
			}
			
			require_once('fpdf/mc_table.php');
			$pdf=new PDF_MC_Table();
			$pdf->AddPage();
			
			$pdf->SetTitle($this->ident['initial']." - MONTHLY ANALYTICS - ".$monthYear);
			$pdf->SetFont('Arial','B',12);
			$pdf->Write(5, $this->ident['initial']." - MONTHLY ANALYTICS - ".$monthYear);
			$pdf->ln();
			$pdf->SetFont('Arial','B',10);
			$pdf->Write(5,$this->ident['site_fullname']);
			$pdf->ln(10);

			$pdf->SetFont('Arial','B',9);
			$pdf->Write(5,'PERFORMANCE');
			$pdf->Ln();
			$pdf->SetFont('Arial','B',8);
			$pdf->Write(5,'Rekapitulasi Kejadian');
			$pdf->Ln();
			$pdf->SetFont('Arial','',7);
			$pdf->SetFillColor(158,130,75);
			$pdf->SetTextColor(255,255,255);
			$pdf->SetFont('','B');
			$pdf->Cell(36,6,'','LTR',0,'C',true);
			$pdf->Cell(36,6,'','LTR',0,'C',true);
			$pdf->Cell(108,6,$y,1,0,'C',true);
			$pdf->Cell(15,6,'','LTR',0,'C',true);
			$pdf->Ln();
			$pdf->Cell(36,6,'Kejadian','LBR',0,'C',true);
			$pdf->Cell(36,6,'Modus','LBR',0,'C',true);
			$pdf->Cell(9,6,'Jan',1,0,'C',true);
			$pdf->Cell(9,6,'Feb',1,0,'C',true);
			$pdf->Cell(9,6,'Mar',1,0,'C',true);
			$pdf->Cell(9,6,'Apr',1,0,'C',true);
			$pdf->Cell(9,6,'Mei',1,0,'C',true);
			$pdf->Cell(9,6,'Jun',1,0,'C',true);
			$pdf->Cell(9,6,'Jul',1,0,'C',true);
			$pdf->Cell(9,6,'Agt',1,0,'C',true);
			$pdf->Cell(9,6,'Sep',1,0,'C',true);
			$pdf->Cell(9,6,'Okt',1,0,'C',true);
			$pdf->Cell(9,6,'Nov',1,0,'C',true);
			$pdf->Cell(9,6,'Des',1,0,'C',true);
			$pdf->Cell(15,6,'Total','LBR',0,'C',true);
			$pdf->Ln();
			$pdf->SetFont('');
			$pdf->SetTextColor(0,0,0);
			$pdf->SetWidths(array(36, 36, 9, 9, 9, 9, 9, 9, 9, 9, 9, 9, 9, 9, 15));	
			$i = 0;
			if(!empty($rekap))
			{
				foreach($rekap as $rekapitulasi)
				{	
					$j = 0;
					if(!empty($rekapitulasi['modus'])) {
						foreach($rekapitulasi['modus'] as $mo) {
							if($j == 0 || $j > $rekapitulasi['total_modus'])
							{	
								$j = 0;
								$pdf->Row(array($rekapitulasi['kejadian_name'],$mo['modus_name'],$mo['total_modus_jan'],$mo['total_modus_feb'],$mo['total_modus_mar'],$mo['total_modus_apr'],$mo['total_modus_may'],$mo['total_modus_jun'],$mo['total_modus_jul'],$mo['total_modus_aug'],$mo['total_modus_sep'],$mo['total_modus_oct'],$mo['total_modus_nov'],$mo['total_modus_dec'], $mo['total_modus_peryear']));
							}
							else{
								$pdf->Row(array("",$mo['modus_name'],$mo['total_modus_jan'],$mo['total_modus_feb'],$mo['total_modus_mar'],$mo['total_modus_apr'],$mo['total_modus_may'],$mo['total_modus_jun'],$mo['total_modus_jul'],$mo['total_modus_aug'],$mo['total_modus_sep'],$mo['total_modus_oct'],$mo['total_modus_nov'],$mo['total_modus_dec'], $mo['total_modus_peryear']));
							}							
							$j++; 
						}
						$i++; 
					} 
				} 
				$pdf->SetFillColor(158,130,75);
				$pdf->SetTextColor(255,255,255);
				$pdf->SetFont('','B');
				$pdf->Cell(72,6,'TOTAL KEJADIAN','LBR',0,'C',true);
				$pdf->Cell(9,6,$rekapTotal['total_modus_perjan'],1,0,'L',true);
				$pdf->Cell(9,6,$rekapTotal['total_modus_perfeb'],1,0,'L',true);
				$pdf->Cell(9,6,$rekapTotal['total_modus_permar'],1,0,'L',true);
				$pdf->Cell(9,6,$rekapTotal['total_modus_perapr'],1,0,'L',true);
				$pdf->Cell(9,6,$rekapTotal['total_modus_permay'],1,0,'L',true);
				$pdf->Cell(9,6,$rekapTotal['total_modus_perjun'],1,0,'L',true);
				$pdf->Cell(9,6,$rekapTotal['total_modus_perjul'],1,0,'L',true);
				$pdf->Cell(9,6,$rekapTotal['total_modus_peraug'],1,0,'L',true);
				$pdf->Cell(9,6,$rekapTotal['total_modus_persep'],1,0,'L',true);
				$pdf->Cell(9,6,$rekapTotal['total_modus_peroct'],1,0,'L',true);
				$pdf->Cell(9,6,$rekapTotal['total_modus_pernov'],1,0,'L',true);
				$pdf->Cell(9,6,$rekapTotal['total_modus_perdec'],1,0,'L',true);
				$pdf->Cell(15,6,$rekapTotal['total_modus_all'],'LBR',0,'L',true);				
			}
			$pdf->Ln(10);

			if(!empty($rekap)) {
				$pdf->SetFont('Arial','B',9);
				$pdf->SetTextColor(0,0,0);
				$pdf->Write(5,'DETAIL KEJADIAN '. $monthYear);
				$pdf->Ln();

				$pdf->SetFont('Arial','',7);
				$pdf->SetFillColor(158,130,75);
				$pdf->SetTextColor(255,255,255);
				$pdf->SetFont('','B');
				$pdf->Cell(30,6,'Kejadian',1,0,'C',true);
				$pdf->Cell(40,6,'Modus',1,0,'C',true);
				$pdf->Cell(113,6,'Uraian Kejadian',1,0,'C',true);
				$pdf->Cell(10,6,'Total',1,0,'C',true);
				$pdf->Ln();
				$pdf->SetFont('');
				$pdf->SetTextColor(0,0,0);
				$pdf->SetWidths(array(30,40,113,10));
				$i = 1;
				foreach($rekap as $rekapitulasi) {
					$j = 0;
					if(!empty($rekapitulasi['modus'])) {
						foreach($rekapitulasi['modus'] as $mo) {
							if(!empty($mo['uraian_kejadian']))
							{
								$k=0;
								foreach($mo['uraian_kejadian'] as $uk) {		
									$uk = str_replace("\r","", $uk);	
									$uk = str_replace("\n\n","\n", $uk);				
									if($j == 0 || $j > $rekapitulasi['total_modus'])
									{						
										$j = 0;
										$pdf->Row(array($rekapitulasi['kejadian_name'], $mo['modus_name'],trim($uk),$mo['total_modus_cur_month']));
									}
									else if($k == 0 || $k > count($mo['uraian_kejadian'])) 
									{
										$k = 0;
										$pdf->Row(array("", $mo['modus_name'],trim($uk),$mo['total_modus_cur_month']));
									}
									else
									{
										$pdf->Row(array("", "",trim($uk),""));
									}
									$k++;
								}
							}
							else {							
								if($j == 0 || $j > $rekapitulasi['total_modus'])
								{	
									$j = 0;
									$pdf->Row(array($rekapitulasi['kejadian_name'], $mo['modus_name'],"",$mo['total_modus_cur_month']));
								}
								else
								{
									$pdf->Row(array("", $mo['modus_name'],"",$mo['total_modus_cur_month']));
								}
							}
							$j++; 
						} 
					}
					$i++;
				}
				$pdf->Ln();

				$pdf->SetFont('Arial','B',9);
				$pdf->Write(5,'DETAIL ANALISA');
				$pdf->Ln();
			
				$pdf->SetFont('Arial','B',8);
				$pdf->Write(5,'Urutan Hari Dengan Jumlah Kejadian Tertinggi');
				$pdf->Ln();
				$pdf->SetFont('Arial','',7);
				$pdf->SetFillColor(158,130,75);
				$pdf->SetTextColor(255,255,255);
				$pdf->SetFont('','B');
				$pdf->Cell(20,6,'Hari',1,0,'C',true);
				$pdf->Cell(158,6,'Jenis Kejadian '.$monthYear,1,0,'C',true);
				$pdf->Cell(15,6,'Total',1,0,'C',true);
				$pdf->Ln();
				$dt1[0] = 'Hari';
				$w[0] = 20;
				$rwidth = 158/count($rekap);
				$h = 1;
				$startw = 0;
				$starty = $pdf->getY();
				foreach($rekap as $r) {	
					$dt1[$h] = $r['kejadian_name'];
					$w[$h] = $rwidth;
					/*$pdf->setX($startw+$rwidth);
					$pdf->setY($starty);
					$pdf->MultiCell($rwidth,5,$r['kejadian_name'],LRTB,L,true);
					$startw = $startw+$rwidth;*/
					$h++;
				} 
				
				$w[$h] = 15;
				$dt1[$h] = 'Total';
				$pdf->SetFont('');
				$pdf->SetTextColor(0,0,0);
				$pdf->SetWidths($w);
				$pdf->Row($dt1);
				
				if(!empty($urutan_hari_tertinggi)) { 
					$days = array('Sunday', 'Monday', 'Tuesday', 'Wednesday','Thursday','Friday', 'Saturday');
					$pdf->SetFont('');
					$pdf->SetTextColor(0,0,0);
					$pdf->SetWidths($w);	
					foreach($urutan_hari_tertinggi as $uht)
					{	
						$dt[0] = $days[$uht['day']-1];						
						$z = 1;
						if(!empty($rekap)) { 
							foreach($rekap as $r) {	
								$dt[$z] = $r['analisa_hari'][$uht['day']] ? $r['analisa_hari'][$uht['day']] : '-';
								$z++;
							}
						}
						$dt[$z] = $uht['total'];
						$pdf->Row($dt);
					} 
				}
				
				$pdf->SetWidths(array(178,15));
				$pdf->Row(array('TOTAL', $rekapTotal['total_modus_per'.strtolower(date("M", strtotime($y."-".$m."-01")))]));
				$pdf->Ln();
				

				$pdf->SetFont('Arial','B',8);
				$pdf->Write(5,'Periode Jam Dengan Jumlah Kejadian Tertinggi');
				$pdf->Ln();
				$pdf->SetFont('Arial','',7);
				$pdf->SetFillColor(158,130,75);
				$pdf->SetTextColor(255,255,255);
				$pdf->SetFont('','B');
				$pdf->Cell(20,6,'',1,0,'C',true);
				$pdf->Cell(158,6,'Jenis Kejadian '.$monthYear,1,0,'C',true);
				$pdf->Cell(15,6,'',1,0,'C',true);
				$pdf->Ln();
				$dt1[0] = 'Jam';
				$rwidth = 158/count($rekap);
				$f = 1;
				foreach($rekap as $r) {	
					$dt1[$f] = $r['kejadian_name'];
					$f++;
				} 
				$dt1[$f] = 'Total';
				$pdf->SetFont('');
				$pdf->SetTextColor(0,0,0);
				$pdf->SetWidths($w);
				$pdf->Row($dt1);

				if(!empty($urutan_total_jam)) { 
					$times = array('09:00 - 12:00', '12:00 - 16:00', '16:00 - 19:00', '19:00 - 23:00','23:00 - 09:00');
					$pdf->SetFont('');
					$pdf->SetTextColor(0,0,0);
					$pdf->SetWidths($w);	
					foreach($urutan_total_jam as $key=>$utj)
					{	
						$dt2[0] = $times[$key];						
						$z = 1;
						if(!empty($rekap)) { 
							foreach($rekap as $r) {	
								$dt2[$z] = $r['analisa_jam'][$key] ? $r['analisa_jam'][$key] : '-';
								$z++;
							}
						}
						$dt2[$z] = $utj;
						$pdf->Row($dt2);
					} 
				}
				
				$pdf->SetWidths(array(178,15));
				$pdf->Row(array('TOTAL', $rekapTotal['total_modus_per'.strtolower(date("M", strtotime($y."-".$m."-01")))]));
				$pdf->Ln();


				$pdf->SetFont('Arial','B',8);
				$pdf->Write(5,'Area Tenant yang rawan kejadian');
				$pdf->Ln();
				$pdf->SetFont('Arial','', 7);
				$pdf->SetFillColor(158,130,75);
				$pdf->SetTextColor(255,255,255);
				$pdf->SetFont('','B');
				$pdf->Cell(20,6,'',1,0,'C',true);
				$pdf->Cell(20,6,'',1,0,'C',true);
				$pdf->Cell(140,6,'Jenis Kejadian '.$monthYear,1,0,'C',true);
				$pdf->Cell(13,6,'',1,0,'C',true);
				$pdf->Ln();
				$dt1[0] = 'Tenant';
				$dt1[1] = 'Lantai';
				$rwidth = 140/count($rekap);
				$w[0] = 20;
				$w[1] = 20;
				$g = 2;
				foreach($rekap as $r) {	
					$w[$g] = $rwidth;
					$dt1[$g] = $r['kejadian_name'];
					$g++;
				} 
				$dt1[$g] = 'Total';
				$w[$g] = 13;
				$pdf->SetFont('Arial','B',8);
				$pdf->SetTextColor(0,0,0);
				$pdf->SetWidths($w);
				$pdf->Row($dt1);

				
				if(!empty($urutan_total_issue_tenant)) { 
					$pdf->SetFont('');
					$pdf->SetTextColor(0,0,0);
					$pdf->SetWidths($w);	
					foreach($urutan_total_issue_tenant as $utit)
					{	
						$dt2[0] = $utit['location'];	
						$dt2[1] = $utit['floor'];								
						$z = 2;
						if(!empty($rekap)) { 
							foreach($rekap as $r) {	
								$dt2[$z] = ($utit[$r['kejadian_id']] ? $utit[$r['kejadian_id']] : '-');
								$z++;
							}
						}
						$dt2[$z] = $utit['total'];
						$pdf->Row($dt2);
					} 
				}
				
				$pdf->SetWidths(array(180,13));
				$pdf->Row(array('TOTAL', $urutan_total_all_issue_tenant));
				$pdf->Ln();


				$pdf->SetFont('Arial','B',8);
				$pdf->Write(5,'Area Publik yang rawan kejadian');
				$pdf->Ln();
				$pdf->SetFont('Arial','',7);
				$pdf->SetFillColor(158,130,75);
				$pdf->SetTextColor(255,255,255);
				$pdf->SetFont('','B');
				$pdf->Cell(20,6,'',1,0,'C',true);
				$pdf->Cell(20,6,'',1,0,'C',true);
				$pdf->Cell(140,6,'Jenis Kejadian '.$monthYear,1,0,'C',true);
				$pdf->Cell(13,6,'',1,0,'C',true);
				$pdf->Ln();
				$dt1[0] = 'Fasilitas Umum';
				$dt1[1] = 'Lantai';
				$rwidth = 140/count($rekap);
				$w[0] = 20;
				$w[1] = 20;
				$e = 2;
				foreach($rekap as $r) {	
					$w[$e] = $rwidth;
					$dt1[$e] = $r['kejadian_name'];
					$e++;
				} 
				$dt1[$e] = 'Total';
				$w[$e] = 13;
				$pdf->SetFont('');
				$pdf->SetTextColor(0,0,0);
				$pdf->SetWidths($w);
				$pdf->Row($dt1);

				
				if(!empty($urutan_total_issue_publik)) { 
					$pdf->SetFont('');
					$pdf->SetTextColor(0,0,0);
					$pdf->SetWidths($w);	
					foreach($urutan_total_issue_publik as $utip)
					{	
						$dt2[0] = $utip['location'];	
						$dt2[1] = $utip['floor'];								
						$z = 2;
						if(!empty($rekap)) { 
							foreach($rekap as $r) {	
								$dt2[$z] = ($utip[$r['kejadian_id']] ? $utip[$r['kejadian_id']] : '-');
								$z++;
							}
						}
						$dt2[$z] = $utip['total'];
						$pdf->Row($dt2);
					} 
				}
				
				$pdf->SetWidths(array(180,13));
				$pdf->Row(array('TOTAL', $urutan_total_all_issue_publik));
				$pdf->Ln(10);
			}

			if(!empty($list_tangkapan)) {
				$pdf->SetFont('Arial','B',9);
				$pdf->SetTextColor(0,0,0);
				$pdf->Write(5,'REKAPITULASI HASIL PENANGKAPAN PELAKU KEJAHATAN');
				$pdf->Ln();
				$pdf->SetFont('Arial','',7);
				$pdf->SetFillColor(158,130,75);
				$pdf->SetTextColor(255,255,255);
				$pdf->SetFont('','B');
				$pdf->Cell(56,6,'','LTR',0,'C',true);
				$pdf->Cell(108,6,$y,1,0,'C',true);
				$pdf->Cell(31,6,'','LTR',0,'C',true);
				$pdf->Ln();
				$pdf->Cell(56,6,'Jenis Tangkapan','LBR',0,'C',true);
				$pdf->Cell(9,6,'Jan',1,0,'C',true);
				$pdf->Cell(9,6,'Feb',1,0,'C',true);
				$pdf->Cell(9,6,'Mar',1,0,'C',true);
				$pdf->Cell(9,6,'Apr',1,0,'C',true);
				$pdf->Cell(9,6,'Mei',1,0,'C',true);
				$pdf->Cell(9,6,'Jun',1,0,'C',true);
				$pdf->Cell(9,6,'Jul',1,0,'C',true);
				$pdf->Cell(9,6,'Agt',1,0,'C',true);
				$pdf->Cell(9,6,'Sep',1,0,'C',true);
				$pdf->Cell(9,6,'Okt',1,0,'C',true);
				$pdf->Cell(9,6,'Nov',1,0,'C',true);
				$pdf->Cell(9,6,'Des',1,0,'C',true);
				$pdf->Cell(31,6,'Total','LBR',0,'C',true);
				$pdf->Ln();
				$pdf->SetFont('');
				$pdf->SetTextColor(0,0,0);
				$pdf->SetWidths(array(56,9,9,9,9,9,9,9,9,9,9,9,9,31));	
				$i = 1;
				foreach($list_tangkapan as $lt) {				
					$pdf->Row(array($lt['modus'],$lt['monthly'][1],$lt['monthly'][2], $lt['monthly'][3], $lt['monthly'][4], $lt['monthly'][5], $lt['monthly'][6], $lt['monthly'][7], $lt['monthly'][8], $lt['monthly'][9], $lt['monthly'][10], $lt['monthly'][11], $lt['monthly'][12], $lt['total_peryear']));
				}
				$pdf->Row(array('TOTAL Hasil Tangkapan',$total_tangkapan_monthly[1],$total_tangkapan_monthly[2], $total_tangkapan_monthly[3], $total_tangkapan_monthly[4], $total_tangkapan_monthly[5], $total_tangkapan_monthly[6], $total_tangkapan_monthly[7], $total_tangkapan_monthly[8], $total_tangkapan_monthly[9], $total_tangkapan_monthly[10], $total_tangkapan_monthly[11], $total_tangkapan_monthly[12], $total_all_tangkapan));

				$pdf->Ln(10);
			} 
			
			if(!empty($pelaku_tertangkap_detail)) {
				$pdf->SetFont('Arial','B',7);
				$pdf->SetFillColor(158,130,75);
				$pdf->SetTextColor(255,255,255);
				$pdf->Cell(30,6,'Photo',1,0,'C',true);
				$pdf->Cell(133,6,'Description',1,0,'C',true);
				$pdf->Cell(30,6,'Date',1,0,'C',true);
				$pdf->Ln();
				$pdf->SetFont("");
				$pdf->SetTextColor(0,0,0);	
				$pdf->SetWidths(array(30, 133, 30));
				foreach($pelaku_tertangkap_detail as $pelaku2) {
					if($pelaku2['issue_date'] > "2019-10-23 14:30:00")
					{
						$issuedate = explode("-",$pelaku2['issue_date']);
						$imageURL = str_replace("https","http",$this->config->general->url)."images/issues/".$issuedate[0]."/";
						$imageDir = $this->config->paths->html.'/images/issues/'.$issuedate[0]."/";
					}
					else
					{
						$imageURL = str_replace("https","http",$this->config->general->url)."images/issues/";
						$imageDir = $this->config->paths->html.'/images/issues/';
					}

					$x1 = $pdf->GetY();
					$pdf->Row(array("", $pelaku2['description']."\n\n\n\n\n",$pelaku2['date']));
					$x2= $pdf->GetY();
					if($x2<$x1) $y = 12;
					else $y = $pdf->GetY()-($x2-$x1-2);

					if (file_exists($imageDir.str_replace(".","_thumb.",$pelaku2['picture']))) {
						$pelaku2['picture'] = str_replace(".","_thumb.",$pelaku2['picture']);
					}
					list($width, $height) = getimagesize($imageDir.$pelaku2['picture']);
					if($width > $height)
					{
						$w = 20;
						$h = 0;
					}
					else {
						$w = 0;
						$h = 20;
					}
					$pdf->Image($imageURL.$pelaku2['picture'],15,$y, $w,$h);
				}
			}
			$pdf->Ln(10);
			$pdf->SetFont('Arial','B',9);
			$pdf->Write(5,'KESIMPULAN UMUM');
			$pdf->Ln();
			$pdf->SetFont('Arial','B',7);
			$pdf->SetFillColor(158,130,75);
			$pdf->SetTextColor(255,255,255);
			$pdf->Cell(6,6,'No',1,0,'C',true);
			$pdf->Cell(27,6,'Jenis Kejadian',1,0,'C',true);
			$pdf->Cell(15,6,'Jumlah',1,0,'C',true);
			$pdf->Cell(72,6,'Data hasil Investigasi',1,0,'C',true);
			$pdf->Cell(72,6,'Langkah Antisipatif',1,0,'C',true);
			$pdf->Ln();
			$pdf->SetFont('');
			$pdf->SetTextColor(0,0,0);	
			$pdf->SetWidths(array(6, 27, 15, 72, 72));
			if(!empty($incidents)) {
				$i = 1;
				foreach($incidents as $incident) {
					$pdf->Row(array($i,$incident['kejadian'],$incident['total_kejadian'], str_replace("<br>","\n",stripslashes($incident['analisa'])), str_replace("<br>","\n",stripslashes($incident['tindakan']))));
					$i++; 
				} 
			}

			$pdf->Output('I', $this->ident['initial']."_safety_monthly_analysis_report_".str_replace(" ","",$monthYear).".pdf", false);
		}		
	}
}

?>