<?php
require_once('actionControllerBase.php');
class StatisticController extends actionControllerBase
{
	public function dashboardAction() {
		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "View Statistic Dashboard";
		$logData['data'] = "View Statistic Dashboard";
		$logsTable->insertLogs($logData);	

		$this->renderTemplate('statistic_dashboard.tpl'); 
	}

	function viewAction()
	{
		$params = $this->_getAllParams();
		
		$this->view->site_id = $this->site_id;

		if(empty($params['start_date'])) $this->view->start_date = $params['start_date'] = date("Y-m-d", mktime(0, 0, 0, date("m")-1, date("d"),   date("Y")));
		else $this->view->start_date = $params['start_date'];
		if(empty($params['end_date'])) $this->view->end_date = $params['end_date'] = date("Y-m-d");
		else $this->view->end_date = $params['end_date'];

		Zend_Loader::LoadClass('issueClass', $this->modelDir);
		$issueClass = new issueClass();
		
		$this->view->totalAllIssue = $issueClass->getTotalIssues($params);

		/*** ISSUE PER TYPE GRAPH ***/
		/*$this->view->totalIncident = $issueClass->getTotalIssues($params, 1);
		$this->view->totalGlitch = $issueClass->getTotalIssues($params, 2);
		$totalLostFound = $issueClass->getTotalIssues($params, 3);
		$totalLost = $issueClass->getTotalIssues($params, 13);
		$this->view->totalLostFound['total'] = $totalLostFound['total'] + $totalLost['total'];
		$this->view->totalDefectList = $issueClass->getTotalIssues($params, 4);
		$this->view->totalUnsafeCondition = $issueClass->getTotalIssues($params, 11);
		$this->view->totalTrafficReport = $issueClass->getTotalIssues($params, 6);
		$this->view->totalSafetyActivity = $issueClass->getTotalIssues($params, 7);
		$this->view->totalFitOutActivity = $issueClass->getTotalIssues($params, 8);
		$this->view->totalFalseAlarm = $issueClass->getTotalIssues($params, 10);
		$this->view->totalPotentialHazard = $issueClass->getTotalIssues($params, 9);
		$this->view->totalBuildingService = $issueClass->getTotalIssues($params, 12);*/
		$this->view->totalGeneralChecklist = $issueClass->getTotalIssues($params, 14);
		if($this->site_id == 1)
		{
			$this->view->totalRoomChecklist = $issueClass->getTotalIssues($params, 15);
			$this->view->totalVillaChecklist = $issueClass->getTotalIssues($params, 16);
		}
		else if($this->site_id == 2)
		{
			$this->view->totalEngineeringChecklist = $issueClass->getTotalIssues($params, 17);
			$this->view->totalSafety = $issueClass->getTotalIssues($params, 5);
		}
		/*** OPEN & CLOSE ISSUE PER CATEGORY ***/
		/*$this->view->totalOpenIncident = $issueClass->getTotalIssues($params, 1,1);
		$this->view->totalCloseIncident = $issueClass->getTotalIssues($params, 1,0,1);
		$this->view->totalOpenGlitch = $issueClass->getTotalIssues($params, 2,1);
		$this->view->totalCloseGlitch = $issueClass->getTotalIssues($params, 2,0,1);
		$totalOpenLostFound = $issueClass->getTotalIssues($params, 3,1);
		$totalOpenLost = $issueClass->getTotalIssues($params, 13,1);
		$this->view->totalOpenLostFound['total'] = $totalOpenLostFound['total'] + $totalOpenLost['total'];
		$totalCloseLostFound = $issueClass->getTotalIssues($params, 3,0,1);
		$totalCloseLost = $issueClass->getTotalIssues($params, 13,0,1);
		$this->view->totalCloseLostFound['total'] = $totalCloseLostFound['total'] + $totalCloseLost['total'];
		$this->view->totalOpenDefectList = $issueClass->getTotalIssues($params, 4,1);
		$this->view->totalCloseDefectList = $issueClass->getTotalIssues($params, 4,0,1);
		$this->view->totalOpenUnsafeCondition = $issueClass->getTotalIssues($params, 11,1);
		$this->view->totalCloseUnsafeCondition = $issueClass->getTotalIssues($params, 11,0,1);
		$this->view->totalOpenTrafficReport = $issueClass->getTotalIssues($params, 6,1);
		$this->view->totalCloseTrafficReport = $issueClass->getTotalIssues($params, 6,0,1);
		$this->view->totalOpenSafetyActivity = $issueClass->getTotalIssues($params, 7,1);
		$this->view->totalCloseSafetyActivity = $issueClass->getTotalIssues($params, 7,0,1);
		$this->view->totalOpenFitOutActivity = $issueClass->getTotalIssues($params, 8,1);
		$this->view->totalCloseFitOutActivity = $issueClass->getTotalIssues($params, 8,0,1);
		$this->view->totalOpenFalseAlarm = $issueClass->getTotalIssues($params, 10,1);
		$this->view->totalCloseFalseAlarm = $issueClass->getTotalIssues($params, 10,0,1);
		$this->view->totalOpenPotentialHazard = $issueClass->getTotalIssues($params, 9,1);
		$this->view->totalClosePotentialHazard = $issueClass->getTotalIssues($params, 9,0,1);
		$this->view->totalOpenBuildingService = $issueClass->getTotalIssues($params, 12,1);
		$this->view->totalCloseBuildingService = $issueClass->getTotalIssues($params, 12,0,1);*/
		$this->view->totalOpenGeneralChecklist = $issueClass->getTotalIssues($params, 14,1);
		$this->view->totalCloseGeneralChecklist = $issueClass->getTotalIssues($params, 14,0,1);
		
		if($this->site_id == 1)
		{
			$this->view->totalOpenRoomChecklist = $issueClass->getTotalIssues($params, 15,1);
			$this->view->totalCloseRoomChecklist = $issueClass->getTotalIssues($params, 15,0,1);
			$this->view->totalOpenVillaChecklist = $issueClass->getTotalIssues($params, 16,1);
			$this->view->totalCloseVillaChecklist = $issueClass->getTotalIssues($params, 16,0,1);		
		}
		else if($this->site_id == 2)
		{
			$this->view->totalOpenEngineeringChecklist = $issueClass->getTotalIssues($params, 17,1);
			$this->view->totalCloseEngineeringChecklist = $issueClass->getTotalIssues($params, 17,0,1);
			$this->view->totalOpenSafety = $issueClass->getTotalIssues($params, 5,1);
			$this->view->totalCloseSafety = $issueClass->getTotalIssues($params, 5,0,1);		
		}
		
		
		if($this->site_id == 2)
		{		
			/***** SECURITY *****/

			$params['category_id'] = 1;

			$this->view->totalAllSecurityIssue = $issueClass->getTotalIssues($params);
			
			/*** SECURITY ISSUE PER TYPE GRAPH ***/ /*
			$this->view->totalSecurityIncident = $issueClass->getTotalIssues($params, 1);
			$this->view->totalSecurityGlitch = $issueClass->getTotalIssues($params, 2);
			$this->view->totalSecurityLostFound = $issueClass->getTotalIssues($params, 13);
			$this->view->totalSecurityDefectList = $issueClass->getTotalIssues($params, 4);
			$this->view->totalSecurityUnsafeCondition = $issueClass->getTotalIssues($params, 11); */
			
			$this->view->totalSecGeneralChecklist = $issueClass->getTotalIssues($params, 14);		
			$this->view->totalSecEngineeringChecklist = $issueClass->getTotalIssues($params, 17);
			$this->view->totalSecSafety = $issueClass->getTotalIssues($params, 5);

			/*** SECURITY OPEN & CLOSE ISSUE ***/ /*
			$this->view->totalSecurityOpenIncident = $issueClass->getTotalIssues($params, 1,1);
			$this->view->totalSecurityCloseIncident = $issueClass->getTotalIssues($params, 1,0,1);
			$this->view->totalSecurityOpenGlitch = $issueClass->getTotalIssues($params, 2,1);
			$this->view->totalSecurityCloseGlitch = $issueClass->getTotalIssues($params, 2,0,1);
			$this->view->totalSecurityOpenLostFound = $issueClass->getTotalIssues($params, 13,1);
			$this->view->totalSecurityCloseLostFound = $issueClass->getTotalIssues($params, 13,0,1);
			$this->view->totalSecurityOpenDefectList = $issueClass->getTotalIssues($params, 4,1);
			$this->view->totalSecurityCloseDefectList = $issueClass->getTotalIssues($params, 4,0,1);	
			$this->view->totalSecurityOpenUnsafeCondition = $issueClass->getTotalIssues($params, 11,1);
			$this->view->totalSecurityCloseUnsafeCondition = $issueClass->getTotalIssues($params, 11,0,1); */
			
			$this->view->totalSecOpenGeneralChecklist = $issueClass->getTotalIssues($params, 14,1);
			$this->view->totalSecCloseGeneralChecklist = $issueClass->getTotalIssues($params, 14,0,1);		
			$this->view->totalSecOpenEngineeringChecklist = $issueClass->getTotalIssues($params, 17,1);
			$this->view->totalSecCloseEngineeringChecklist = $issueClass->getTotalIssues($params, 17,0,1);
			$this->view->totalSecOpenSafety = $issueClass->getTotalIssues($params, 5,1);
			$this->view->totalSecCloseSafety = $issueClass->getTotalIssues($params, 5,0,1);

			/***** SAFETY *****/

			/*$params['category_id'] = 3;

			$this->view->totalAllSafetyIssue = $issueClass->getTotalIssues($params);*/

			/*** SAFETY ISSUE PER TYPE GRAPH ***/
			/*$this->view->totalSafetyIncident = $issueClass->getTotalIssues($params, 1);
			$this->view->totalSafetyGlitch = $issueClass->getTotalIssues($params, 2);
			$this->view->totalSafetyDefectList = $issueClass->getTotalIssues($params, 4);
			$this->view->totalSafetyTrafficReport = $issueClass->getTotalIssues($params, 6);
			$this->view->totalSafetyActivity = $issueClass->getTotalIssues($params, 7);
			$this->view->totalSafetyFitOutActivity = $issueClass->getTotalIssues($params, 8);
			$this->view->totalSafeftyFalseAlarm = $issueClass->getTotalIssues($params, 10);
			$this->view->totalSafeftyPotentialHazard = $issueClass->getTotalIssues($params, 9);*/

			/*** SAFETY OPEN & CLOSE ISSUE ***/
			/*$this->view->totalSafetyOpenIncident = $issueClass->getTotalIssues($params, 1,1);
			$this->view->totalSafetyCloseIncident = $issueClass->getTotalIssues($params, 1,0,1);
			$this->view->totalSafetyOpenGlitch = $issueClass->getTotalIssues($params, 2,1);
			$this->view->totalSafetyCloseGlitch = $issueClass->getTotalIssues($params, 2,0,1);
			$this->view->totalSafetyOpenDefectList = $issueClass->getTotalIssues($params, 4,1);
			$this->view->totalSafetyCloseDefectList = $issueClass->getTotalIssues($params, 4,0,1);	
			$this->view->totalSafetyOpenTrafficReport = $issueClass->getTotalIssues($params, 6,1);
			$this->view->totalSafetyCloseTrafficReport = $issueClass->getTotalIssues($params, 6,0,1);
			$this->view->totalSafetyOpenActivity = $issueClass->getTotalIssues($params, 7,1);
			$this->view->totalSafetyCloseActivity = $issueClass->getTotalIssues($params, 7,0,1);
			$this->view->totalSafetyOpenFitOutActivity = $issueClass->getTotalIssues($params, 8,1);
			$this->view->totalSafetyCloseFitOutActivity = $issueClass->getTotalIssues($params, 8,0,1);
			$this->view->totalSafetyOpenFalseAlarm = $issueClass->getTotalIssues($params, 10,1);
			$this->view->totalSafetyCloseFalseAlarm = $issueClass->getTotalIssues($params, 10,0,1);
			$this->view->totalSafetyOpenPotentialHazard = $issueClass->getTotalIssues($params, 9,1);
			$this->view->totalSafetyClosePotentialHazard = $issueClass->getTotalIssues($params, 9,0,1);*/
			
			/***** PARKING *****/

			$params['category_id'] = 5;

			$this->view->totalAllParkingIssue = $issueClass->getTotalIssues($params);
			
			/*** PARKING ISSUE PER TYPE GRAPH ***/ 
			
			$this->view->totalParkGeneralChecklist = $issueClass->getTotalIssues($params, 14);		
			$this->view->totalParkEngineeringChecklist = $issueClass->getTotalIssues($params, 17);
			$this->view->totalParkSafety = $issueClass->getTotalIssues($params, 5);

			/*** PARKING OPEN & CLOSE ISSUE ***/ 
			
			$this->view->totalParkOpenGeneralChecklist = $issueClass->getTotalIssues($params, 14,1);
			$this->view->totalParkCloseGeneralChecklist = $issueClass->getTotalIssues($params, 14,0,1);		
			$this->view->totalParkOpenEngineeringChecklist = $issueClass->getTotalIssues($params, 17,1);
			$this->view->totalParkCloseEngineeringChecklist = $issueClass->getTotalIssues($params, 17,0,1);
			$this->view->totalParkOpenSafety = $issueClass->getTotalIssues($params, 5,1);
			$this->view->totalParkCloseSafety = $issueClass->getTotalIssues($params, 5,0,1);
			
			/***** TENANT RELATION *****/

			$params['category_id'] = 11;

			$this->view->totalAllTRIssue = $issueClass->getTotalIssues($params);
			
			/*** TENANT RELATION ISSUE PER TYPE GRAPH ***/ 
			
			$this->view->totalTRGeneralChecklist = $issueClass->getTotalIssues($params, 14);		
			$this->view->totalTREngineeringChecklist = $issueClass->getTotalIssues($params, 17);
			$this->view->totalTRSafety = $issueClass->getTotalIssues($params, 5);

			/*** TENANT RELATION OPEN & CLOSE ISSUE ***/ 
			
			$this->view->totalTROpenGeneralChecklist = $issueClass->getTotalIssues($params, 14,1);
			$this->view->totalTRCloseGeneralChecklist = $issueClass->getTotalIssues($params, 14,0,1);		
			$this->view->totalTROpenEngineeringChecklist = $issueClass->getTotalIssues($params, 17,1);
			$this->view->totalTRCloseEngineeringChecklist = $issueClass->getTotalIssues($params, 17,0,1);
			$this->view->totalTROpenSafety = $issueClass->getTotalIssues($params, 5,1);
			$this->view->totalTRCloseSafety = $issueClass->getTotalIssues($params, 5,0,1);
		}
		
		/***** HOUSEKEEPING *****/

		$params['category_id'] = 2;

		$this->view->totalAllHKIssue = $issueClass->getTotalIssues($params);

		/*** HOUSEKEEPING ISSUE PER TYPE GRAPH ***/
		/*$this->view->totalHKIncident = $issueClass->getTotalIssues($params, 1);
		$this->view->totalHKGlitch = $issueClass->getTotalIssues($params, 2);
		$this->view->totalHKLostFound = $issueClass->getTotalIssues($params, 3);
		$this->view->totalHKDefectList = $issueClass->getTotalIssues($params, 4);
		$this->view->totalHKUnsafeCondition = $issueClass->getTotalIssues($params, 11);*/
		
		$this->view->totalHKGeneralChecklist = $issueClass->getTotalIssues($params, 14);
		
		if($this->site_id == 1)
		{
			$this->view->totalHKRoomChecklist = $issueClass->getTotalIssues($params, 15);
			$this->view->totalHKVillaChecklist = $issueClass->getTotalIssues($params, 16);
		}
		else if($this->site_id == 2)
		{			
			$this->view->totalHKEngineeringChecklist = $issueClass->getTotalIssues($params, 17);
			$this->view->totalHKSafety = $issueClass->getTotalIssues($params, 5);
		}

		/*** HOUSEKEEPING OPEN & CLOSE ISSUE ***/
		/*$this->view->totalHKOpenIncident = $issueClass->getTotalIssues($params, 1,1);
		$this->view->totalHKCloseIncident = $issueClass->getTotalIssues($params, 1,0,1);
		$this->view->totalHKOpenGlitch = $issueClass->getTotalIssues($params, 2,1);
		$this->view->totalHKCloseGlitch = $issueClass->getTotalIssues($params, 2,0,1);
		$this->view->totalHKOpenLostFound = $issueClass->getTotalIssues($params, 3,1);
		$this->view->totalHKCloseLostFound = $issueClass->getTotalIssues($params, 3,0,1);
		$this->view->totalHKOpenDefectList = $issueClass->getTotalIssues($params, 4,1);
		$this->view->totalHKCloseDefectList = $issueClass->getTotalIssues($params, 4,0,1);	
		$this->view->totalHKOpenUnsafeCondition = $issueClass->getTotalIssues($params, 11,1);
		$this->view->totalHKCloseUnsafeCondition = $issueClass->getTotalIssues($params, 11,0,1);*/

		$this->view->totalHKOpenGeneralChecklist = $issueClass->getTotalIssues($params, 14,1);
		$this->view->totalHKCloseGeneralChecklist = $issueClass->getTotalIssues($params, 14,0,1);
		
		if($this->site_id == 1)
		{
			$this->view->totalHKOpenRoomChecklist = $issueClass->getTotalIssues($params, 15,1);
			$this->view->totalHKCloseRoomChecklist = $issueClass->getTotalIssues($params, 15,0,1);
			$this->view->totalHKOpenVillaChecklist = $issueClass->getTotalIssues($params, 16,1);
			$this->view->totalHKCloseVillaChecklist = $issueClass->getTotalIssues($params, 16,0,1);
		}
		else if($this->site_id == 2)
		{			
			$this->view->totalHKOpenEngineeringChecklist = $issueClass->getTotalIssues($params, 17,1);
			$this->view->totalHKCloseEngineeringChecklist = $issueClass->getTotalIssues($params, 17,0,1);
			$this->view->totalHKOpenSafety = $issueClass->getTotalIssues($params, 5,1);
			$this->view->totalHKCloseSafety = $issueClass->getTotalIssues($params, 5,0,1);
		}
		


		/***** ENGINEERING *****/

		$params['category_id'] = 6;

		$this->view->totalAllEngineeringIssue = $issueClass->getTotalIssues($params);

		/*** ENGINEERING ISSUE PER TYPE GRAPH ***/
		/*$this->view->totalEngineeringIncident = $issueClass->getTotalIssues($params, 1);
		$this->view->totalEngineeringGlitch = $issueClass->getTotalIssues($params, 2);
		$this->view->totalEngineeringDefectList = $issueClass->getTotalIssues($params, 4);
		$this->view->totalEngineeringUnsafeCondition = $issueClass->getTotalIssues($params, 11);		
		$this->view->totalEngineeringBuildingService = $issueClass->getTotalIssues($params, 12);*/
		$this->view->totalEngineeringGeneralChecklist = $issueClass->getTotalIssues($params, 14);
		
		if($this->site_id == 1)
		{
			$this->view->totalEngineeringRoomChecklist = $issueClass->getTotalIssues($params, 15);
			$this->view->totalEngineeringVillaChecklist = $issueClass->getTotalIssues($params, 16);
		}
		else if($this->site_id == 2)
		{			
			$this->view->totalEngEngineeringChecklist = $issueClass->getTotalIssues($params, 17);
			$this->view->totalEngSafety = $issueClass->getTotalIssues($params, 5);
		}

		/*** ENGINEERING OPEN & CLOSE ISSUE ***/
		/*$this->view->totalEngineeringOpenIncident = $issueClass->getTotalIssues($params, 1,1);
		$this->view->totalEngineeringCloseIncident = $issueClass->getTotalIssues($params, 1,0,1);
		$this->view->totalEngineeringOpenGlitch = $issueClass->getTotalIssues($params, 2,1);
		$this->view->totalEngineeringCloseGlitch = $issueClass->getTotalIssues($params, 2,0,1);
		$this->view->totalEngineeringOpenDefectList = $issueClass->getTotalIssues($params, 4,1);
		$this->view->totalEngineeringCloseDefectList = $issueClass->getTotalIssues($params, 4,0,1);	
		$this->view->totalEngineeringOpenUnsafeCondition = $issueClass->getTotalIssues($params, 11,1);
		$this->view->totalEngineeringCloseUnsafeCondition = $issueClass->getTotalIssues($params, 11,0,1);
		$this->view->totalEngineeringOpenBuildingService = $issueClass->getTotalIssues($params, 12,1);
		$this->view->totalEngineeringCloseBuildingService = $issueClass->getTotalIssues($params, 12,0,1);*/
		$this->view->totalEngineeringOpenGeneralChecklist = $issueClass->getTotalIssues($params, 14,1);
		$this->view->totalEngineeringCloseGeneralChecklist = $issueClass->getTotalIssues($params, 14,0,1);
		
		if($this->site_id == 1)
		{
			$this->view->totalEngineeringOpenRoomChecklist = $issueClass->getTotalIssues($params, 15,1);
			$this->view->totalEngineeringCloseRoomChecklist = $issueClass->getTotalIssues($params, 15,0,1);
			$this->view->totalEngineeringOpenVillaChecklist = $issueClass->getTotalIssues($params, 16,1);
			$this->view->totalEngineeringCloseVillaChecklist = $issueClass->getTotalIssues($params, 16,0,1);
		}
		else if($this->site_id == 2)
		{			
			$this->view->totalEngOpenEngineeringChecklist = $issueClass->getTotalIssues($params, 17,1);
			$this->view->totalEngCloseEngineeringChecklist = $issueClass->getTotalIssues($params, 17,0,1);
			$this->view->totalEngOpenSafety = $issueClass->getTotalIssues($params, 5,1);
			$this->view->totalEngCloseSafety = $issueClass->getTotalIssues($params, 5,0,1);
		}
		


		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "View Issue Statistic";
		$logData['data'] = json_encode($params);
		$logsTable->insertLogs($logData);

		$this->renderTemplate('view_issue_statistic.tpl');	
	}

	function savegraphAction()
	{
		$params = $this->_getAllParams();

		$curdate = date("YmdHis");

		$magickPath = "/usr/bin/convert";
		$all_total_issue = $this->config->paths->html."/stat/".'all_total_issue_'.$curdate.'.png';
		$all_type_issue = $this->config->paths->html."/stat/".'all_type_issue_'.$curdate.'.png';
		$all_open_close = $this->config->paths->html."/stat/".'all_open_close_'.$curdate.'.png';
		$hk_total_issue = $this->config->paths->html."/stat/".'hk_total_issue_'.$curdate.'.png';
		$hk_type_issue = $this->config->paths->html."/stat/".'hk_type_issue_'.$curdate.'.png';
		$hk_open_close = $this->config->paths->html."/stat/".'hk_open_close_'.$curdate.'.png';
		$eng_total_issue = $this->config->paths->html."/stat/".'eng_total_issue_'.$curdate.'.png';
		$eng_type_issue = $this->config->paths->html."/stat/".'eng_type_issue_'.$curdate.'.png';
		$eng_open_close = $this->config->paths->html."/stat/".'eng_open_close_'.$curdate.'.png';
		
		if($this->site_id == 2)
		{
			$sec_total_issue = $this->config->paths->html."/stat/".'sec_total_issue_'.$curdate.'.png';
			$sec_type_issue = $this->config->paths->html."/stat/".'sec_type_issue_'.$curdate.'.png';
			$sec_open_close = $this->config->paths->html."/stat/".'sec_open_close_'.$curdate.'.png';
			$park_total_issue = $this->config->paths->html."/stat/".'park_total_issue_'.$curdate.'.png';
			$park_type_issue = $this->config->paths->html."/stat/".'park_type_issue_'.$curdate.'.png';
			$park_open_close = $this->config->paths->html."/stat/".'park_open_close_'.$curdate.'.png';		
			$tr_total_issue = $this->config->paths->html."/stat/".'tr_total_issue_'.$curdate.'.png';
			$tr_type_issue = $this->config->paths->html."/stat/".'tr_type_issue_'.$curdate.'.png';
			$tr_open_close = $this->config->paths->html."/stat/".'tr_open_close_'.$curdate.'.png';
		}

		// remove "data:image/png;base64,"
		$all_total_issue_uri =  str_replace("data:image/png;base64","", $params['all_total_issue']);
		file_put_contents($all_total_issue, base64_decode($all_total_issue_uri));
		$all_type_issue_uri =  str_replace("data:image/png;base64","", $params['all_type_issue']);
		file_put_contents($all_type_issue, base64_decode($all_type_issue_uri));
		$all_open_close_uri =  str_replace("data:image/png;base64","", $params['all_open_close']);
		file_put_contents($all_open_close, base64_decode($all_open_close_uri));

		$hk_total_issue_uri =  str_replace("data:image/png;base64","", $params['hk_total_issue']);
		file_put_contents($hk_total_issue, base64_decode($hk_total_issue_uri));
		$hk_type_issue_uri =  str_replace("data:image/png;base64","", $params['hk_type_issue']);
		file_put_contents($hk_type_issue, base64_decode($hk_type_issue_uri));
		$hk_open_close_uri =  str_replace("data:image/png;base64","", $params['hk_open_close']);
		file_put_contents($hk_open_close, base64_decode($hk_open_close_uri));

		$eng_total_issue_uri =  str_replace("data:image/png;base64","", $params['eng_total_issue']);
		file_put_contents($eng_total_issue, base64_decode($eng_total_issue_uri));
		$eng_type_issue_uri =  str_replace("data:image/png;base64","", $params['eng_type_issue']);
		file_put_contents($eng_type_issue, base64_decode($eng_type_issue_uri));
		$eng_open_close_uri =  str_replace("data:image/png;base64","", $params['eng_open_close']);
		file_put_contents($eng_open_close, base64_decode($eng_open_close_uri));
		
		if($this->site_id == 2)
		{
			$sec_total_issue_uri =  str_replace("data:image/png;base64","", $params['sec_total_issue']);
			file_put_contents($sec_total_issue, base64_decode($sec_total_issue_uri));
			$sec_type_issue_uri =  str_replace("data:image/png;base64","", $params['sec_type_issue']);
			file_put_contents($sec_type_issue, base64_decode($sec_type_issue_uri));
			$sec_open_close_uri =  str_replace("data:image/png;base64","", $params['sec_open_close']);
			file_put_contents($sec_open_close, base64_decode($sec_open_close_uri));

			$park_total_issue_uri =  str_replace("data:image/png;base64","", $params['park_total_issue']);
			file_put_contents($park_total_issue, base64_decode($park_total_issue_uri));
			$park_type_issue_uri =  str_replace("data:image/png;base64","", $params['park_type_issue']);
			file_put_contents($park_type_issue, base64_decode($park_type_issue_uri));
			$park_open_close_uri =  str_replace("data:image/png;base64","", $params['park_open_close']);
			file_put_contents($park_open_close, base64_decode($park_open_close_uri));
			
			$tr_total_issue_uri =  str_replace("data:image/png;base64","", $params['tr_total_issue']);
			file_put_contents($tr_total_issue, base64_decode($tr_total_issue_uri));
			$tr_type_issue_uri =  str_replace("data:image/png;base64","", $params['tr_type_issue']);
			file_put_contents($tr_type_issue, base64_decode($tr_type_issue_uri));
			$tr_open_close_uri =  str_replace("data:image/png;base64","", $params['tr_open_close']);
			file_put_contents($tr_open_close, base64_decode($tr_open_close_uri));
		}
		
		echo $curdate;
	}
	

	function exporttopdfAction() {
		$params = $this->_getAllParams();

		$curdate = $params['cd'];
		$start_date = date("j M Y", mktime(0, 0, 0, substr($params['sd'],4,2), substr($params['sd'],6,2), substr($params['sd'],0,4)));
		$end_date = date("j M Y", mktime(0, 0, 0, substr($params['ed'],4,2), substr($params['ed'],6,2), substr($params['ed'],0,4)));

		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "Export Issue Statistic to PDF";
		$logData['data'] = json_encode($params);
		$logsTable->insertLogs($logData);

		require('fpdf/fpdf.php');

		$pdf = new FPDF();
		$pdf->AddPage();
		$pdf->SetFont('Arial','B',15);
		$pdf->Cell(80);
		$pdf->Cell(20,10,$this->ident['initial'].' - Issue Statistic',0,0,'C');
		$pdf->Ln(7);
		$pdf->SetFont('Arial','B',12);
		$pdf->Cell(80);
		$pdf->Cell(20,10,$start_date." - ".$end_date,0,0,'C');
		$pdf->Ln(15);
		$pdf->SetFont('Arial','B',10);
		$pdf->SetTextColor(255);
		$pdf->SetFillColor(158,130,75);
		$pdf->Cell(0,6,'  All Department',0,1,'L', true);
		$pdf->Image($this->config->paths->html."/stat/".'all_total_issue_'.$curdate.'.png',10,$pdf->getY(), 0, 50);
		$pdf->Image($this->config->paths->html."/stat/".'all_type_issue_'.$curdate.'.png',60,$pdf->getY(), 0, 50);
		$pdf->Ln(55);			
		$pdf->Image($this->config->paths->html."/stat/".'all_open_close_'.$curdate.'.png',10,$pdf->getY(), 0, 50);
		$pdf->Ln(65);
		$pdf->Cell(0,6,'  Housekeeping',0,1,'L', true);
		$pdf->Image($this->config->paths->html."/stat/".'hk_total_issue_'.$curdate.'.png',10,$pdf->getY(), 0, 50);
		$pdf->Image($this->config->paths->html."/stat/".'hk_type_issue_'.$curdate.'.png',60,$pdf->getY(), 0, 50);
		$pdf->Ln(55);
		$pdf->Image($this->config->paths->html."/stat/".'hk_open_close_'.$curdate.'.png',10,$pdf->getY(), 0, 50);
		$pdf->Ln(85);
		$pdf->Cell(0,6,'  Engineering',0,1,'L', true);
		$pdf->Image($this->config->paths->html."/stat/".'eng_total_issue_'.$curdate.'.png',10,$pdf->getY(), 0, 50);
		$pdf->Image($this->config->paths->html."/stat/".'eng_type_issue_'.$curdate.'.png',60,$pdf->getY(), 0, 50);
		$pdf->Ln(55);
		$pdf->Image($this->config->paths->html."/stat/".'eng_open_close_'.$curdate.'.png',10,$pdf->getY(), 0, 50);
		$pdf->Ln(85);
		if($this->site_id == 2)
		{
			$pdf->Cell(0,6,'  Security, Safety, Parking',0,1,'L', true);
			$pdf->Image($this->config->paths->html."/stat/".'sec_total_issue_'.$curdate.'.png',10,$pdf->getY(), 0, 50);
			$pdf->Image($this->config->paths->html."/stat/".'sec_type_issue_'.$curdate.'.png',60,$pdf->getY(), 0, 50);
			$pdf->Ln(55);
			$pdf->Image($this->config->paths->html."/stat/".'sec_open_close_'.$curdate.'.png',10,$pdf->getY(), 0, 50);
			$pdf->Ln(85);
			$pdf->Cell(0,6,'  Fit Out',0,1,'L', true);
			$pdf->Image($this->config->paths->html."/stat/".'park_total_issue_'.$curdate.'.png',10,$pdf->getY(), 0, 50);
			$pdf->Image($this->config->paths->html."/stat/".'park_type_issue_'.$curdate.'.png',60,$pdf->getY(), 0, 50);
			$pdf->Ln(55);
			$pdf->Image($this->config->paths->html."/stat/".'park_open_close_'.$curdate.'.png',10,$pdf->getY(), 0, 50);
			$pdf->Ln(85);
			$pdf->Cell(0,6,'  Tenant Relation',0,1,'L', true);
			$pdf->Image($this->config->paths->html."/stat/".'tr_total_issue_'.$curdate.'.png',10,$pdf->getY(), 0, 50);
			$pdf->Image($this->config->paths->html."/stat/".'tr_type_issue_'.$curdate.'.png',60,$pdf->getY(), 0, 50);
			$pdf->Ln(55);
			$pdf->Image($this->config->paths->html."/stat/".'tr_open_close_'.$curdate.'.png',10,$pdf->getY(), 0, 50);
			$pdf->Ln(85);
		}
		$pdf->Output();
	}

	function deletegraphAction() {

		$folder = $this->config->paths->html."/stat";

		//Get a list of all of the file names in the folder.
		$files = glob($folder . '/*');
		
		//Loop through the file list.
		foreach($files as $file){
			//Make sure that this is a file and not a directory.
			if(is_file($file)){
				unlink($file);
			}
		}
	}

	function userAction()
	{
		$params = $this->_getAllParams();
		if(empty($params['start_date'])) $this->view->start_date = $params['start_date'] = date("Y-m-d", mktime(0, 0, 0, date("m")-1, date("d"),   date("Y")));
		else $this->view->start_date = $params['start_date'];
		if(empty($params['end_date'])) $this->view->end_date = $params['end_date'] = date("Y-m-d");
		else $this->view->end_date = $params['end_date'];

		Zend_Loader::LoadClass('userClass', $this->modelDir);
		$userClass = new userClass();

		$departments = $userClass->getDepartmentByRoleId();
		foreach($departments as $d)
		{
			if($d['category_id'] > 0)
			{
				$dept[$d['role_id']] = $d['category_name'];
			}
		}
		
		Zend_Loader::LoadClass('userlogClass', $this->modelDir);
		$userlogClass = new userlogClass();
		
		$users = $userlogClass->getUserLog($params['start_date'], $params['end_date'], 10);
		if(!empty($users))
		{
			foreach($users as &$user)
			{
				$detail = $userlogClass->getLastUserLog($user['user_id']);
				if(!empty($detail))
				{
					$lastLogin = $detail['login_date'];
					$last_login = explode(" ",$lastLogin);
					$user['last_login'] = date("j M Y", strtotime($last_login[0]))." ".$last_login[1];
					$role = explode(",", $user['role_id']);
					$user['department'] = $dept[$role[0]];
				}
			}
		}
		$this->view->users = $users;

		Zend_Loader::LoadClass('issueClass', $this->modelDir);
		$issueClass = new issueClass();

		$userIssues = $issueClass->getUserIssuesStatistic($params['start_date'], $params['end_date'], 10);
		foreach($userIssues as &$ui)
		{
			$role = explode(",", $ui['role_id']);
			$ui['department'] = $dept[$role[0]];
		}
		$this->view->userIssues = $userIssues;

		$users2 = $userClass->getUsers($this->site_id);

		Zend_Loader::LoadClass('commentsClass', $this->modelDir);
		$commentsClass = new commentsClass();

		Zend_Loader::LoadClass('securitycommentsClass', $this->modelDir);
		$securitycommentsClass = new securitycommentsClass();

		Zend_Loader::LoadClass('safetycommentsClass', $this->modelDir);
		$safetycommentsClass = new safetycommentsClass();

		Zend_Loader::LoadClass('parkingcommentsClass', $this->modelDir);
		$parkingcommentsClass = new parkingcommentsClass();

		Zend_Loader::LoadClass('housekeepingcommentsClass', $this->modelDir);
		$housekeepingcommentsClass = new housekeepingcommentsClass();

		Zend_Loader::LoadClass('operationalcommentsClass', $this->modelDir);
		$operationalcommentsClass = new operationalcommentsClass();

		Zend_Loader::LoadClass('modcommentsClass', $this->modelDir);
		$modcommentsClass = new modcommentsClass();

		Zend_Loader::LoadClass('bmcommentsClass', $this->modelDir);
		$bmcommentsClass = new bmcommentsClass();

		$userComments = array();
		$userCommentsDept = array();
		$totalComments = 0;
		foreach($users2 as $user2)
		{
			$comments = $commentsClass->getTotalCommentsByUser($user2['user_id'],$params['start_date'], $params['end_date']);
			$securityComments = $securitycommentsClass->getTotalCommentsByUser($user2['user_id'],$params['start_date'], $params['end_date']);
			$safetyComments = $safetycommentsClass->getTotalCommentsByUser($user2['user_id'],$params['start_date'], $params['end_date']);
			$parkingComments = $parkingcommentsClass->getTotalCommentsByUser($user2['user_id'],$params['start_date'], $params['end_date']);
			$housekeepingComments = $housekeepingcommentsClass->getTotalCommentsByUser($user2['user_id'],$params['start_date'], $params['end_date']);
			$operationalComments = $operationalcommentsClass->getTotalCommentsByUser($user2['user_id'],$params['start_date'], $params['end_date']);
			$modComments = $modcommentsClass->getTotalCommentsByUser($user2['user_id'],$params['start_date'], $params['end_date']);
			$bmComments = $bmcommentsClass->getTotalCommentsByUser($user2['user_id'],$params['start_date'], $params['end_date']);
			$userComments[$user2['name']] = $comments['total_comment'] + $securityComments['total_comment'] + $safetyComments['total_comment'] + $parkingComments['total_comment'] + $housekeepingComments['total_comment'] + $operationalComments['total_comment'] + $modComments['total_comment'] + $bmComments['total_comment'];
			$totalComments = $totalComments + $userComments[$user2['name']];
			$role = explode(",", $user2['role_id']);
			$userCommentsDept[$user2['name']] = $dept[$role[0]];
		}
		arsort($userComments);
		$this->view->userComments = $userComments;
		$this->view->userCommentsDept = $userCommentsDept;

		$om = $userlogClass->getOMLoginStat($params['start_date'], $params['end_date']);
		if(!empty($om))
		{
			foreach($om as &$o)
			{
				$detail2 = $userlogClass->getLastUserLog($o['user_id']);
				if(!empty($detail2))
				{
					$lastLogin = $detail2['login_date'];
					$last_login = explode(" ",$lastLogin);
					$o['last_login'] = date("j M Y", strtotime($last_login[0]))." ".$last_login[1];
					$role = explode(",", $o['role_id']);
					$o['department'] = $dept[$role[0]];
				}
			}
		}
		$this->view->om = $om;

		Zend_Loader::LoadClass('siteClass', $this->modelDir);
		$siteClass = new siteClass();
		$userStatisticSummary = $siteClass->getSites();

		foreach($userStatisticSummary as &$site)
		{
			$site['total_login'] = $userlogClass->getTotalUserLog($site['site_id'], $params['start_date'], $params['end_date']);
			$params['site_id'] = $site['site_id'];
			$totalissues = $issueClass->getTotalIssues($params);
			$site['total_issues'] = $totalissues['total'];
	
			$comments2 = $commentsClass->getTotalCommentsBySiteId($site['site_id'],$params['start_date'], $params['end_date']);
			$securityComments2 = $securitycommentsClass->getTotalCommentsBySiteId($site['site_id'],$params['start_date'], $params['end_date']);
			$safetyComments2 = $safetycommentsClass->getTotalCommentsBySiteId($site['site_id'],$params['start_date'], $params['end_date']);
			$parkingComments2 = $parkingcommentsClass->getTotalCommentsBySiteId($site['site_id'],$params['start_date'], $params['end_date']);
			$housekeepingComments2 = $housekeepingcommentsClass->getTotalCommentsBySiteId($site['site_id'],$params['start_date'], $params['end_date']);
			$operationalComments2 = $operationalcommentsClass->getTotalCommentsBySiteId($site['site_id'],$params['start_date'], $params['end_date']);
			$modComments2 = $modcommentsClass->getTotalCommentsBySiteId($site['site_id'],$params['start_date'], $params['end_date']);
			$bmComments2 = $bmcommentsClass->getTotalCommentsBySiteId($site['site_id'],$params['start_date'], $params['end_date']);
			$site['total_comments'] = $comments2['total_comment'] + $securityComments2['total_comment'] + $safetyComments2['total_comment'] + $parkingComments2['total_comment'] + $housekeepingComments2['total_comment'] + $operationalComments2['total_comment'] + $modComments2['total_comment'] + $bmComments2['total_comment'];		
		}
		$this->view->userStatisticSummary = $userStatisticSummary;

		$this->renderTemplate('view_user_statistic.tpl');	
	}

	function updatesiteidcommentsAction()
	{
		$params = $this->_getAllParams();

		Zend_Loader::LoadClass('issueClass', $this->modelDir);
		$issueClass = new issueClass();

		$issues = $issueClass->getAllIssues();
		
		if(!empty($issues))
		{
			foreach($issues as $issue)
			{
				Zend_Loader::LoadClass('commentsClass', $this->modelDir);
				$commentsClass = new commentsClass();
				$commentsClass->updateSiteIdComment($issue['issue_id'], $issue['site_id']);
			}
		}
		
	}

	function saveusergraphAction()
	{
		$params = $this->_getAllParams();

		$curdate = date("YmdHis");

		$magickPath = "/usr/bin/convert";
		$user_by_login = $this->config->paths->html."/stat/".'user_by_login_'.$curdate.'.png';
		$user_by_issue = $this->config->paths->html."/stat/".'user_by_issue_'.$curdate.'.png';
		$user_by_comment = $this->config->paths->html."/stat/".'user_by_comment_'.$curdate.'.png';
		$ap_outstanding_sec = $this->config->paths->html."/stat/".'ap_outstanding_sec_'.$curdate.'.png';
		$ap_outstanding_saf = $this->config->paths->html."/stat/".'ap_outstanding_saf_'.$curdate.'.png';
		$ap_outstanding_park = $this->config->paths->html."/stat/".'ap_outstanding_park_'.$curdate.'.png';

		// remove "data:image/png;base64,"
		$user_by_login_uri =  str_replace("data:image/png;base64","", $params['login']);
		file_put_contents($user_by_login, base64_decode($user_by_login_uri));
		$user_by_issue_uri =  str_replace("data:image/png;base64","", $params['issue']);
		file_put_contents($user_by_issue, base64_decode($user_by_issue_uri));
		$user_by_comment_uri =  str_replace("data:image/png;base64","", $params['comment']);
		file_put_contents($user_by_comment, base64_decode($user_by_comment_uri));
		$ap_outstanding_sec_uri =  str_replace("data:image/png;base64","", $params['apoutstandingsec']);
		file_put_contents($ap_outstanding_sec, base64_decode($ap_outstanding_sec_uri));
		$ap_outstanding_saf_uri =  str_replace("data:image/png;base64","", $params['apoutstandingsaf']);
		file_put_contents($ap_outstanding_saf, base64_decode($ap_outstanding_saf_uri));
		$ap_outstanding_park_uri =  str_replace("data:image/png;base64","", $params['apoutstandingpark']);
		file_put_contents($ap_outstanding_park, base64_decode($ap_outstanding_park_uri));
		echo $curdate;
	}

	function exportuserstatistictopdfAction() {
		$params = $this->_getAllParams();

		$curdate = $params['cd'];
		$start_date = date("j M Y", mktime(0, 0, 0, substr($params['sd'],4,2), substr($params['sd'],6,2), substr($params['sd'],0,4)));
		$end_date = date("j M Y", mktime(0, 0, 0, substr($params['ed'],4,2), substr($params['ed'],6,2), substr($params['ed'],0,4)));

		Zend_Loader::LoadClass('userClass', $this->modelDir);
		$userClass = new userClass();
		$departments = $userClass->getDepartmentByRoleId();
		foreach($departments as $d)
		{
			if($d['category_id'] > 0)
			{
				$dept[$d['role_id']] = $d['category_name'];
			}
		}

		Zend_Loader::LoadClass('userlogClass', $this->modelDir);
		$userlogClass = new userlogClass();
		$users = $userlogClass->getUserLog($params['sd'], $params['ed'], 10);
		if(!empty($users))
		{
			foreach($users as &$user)
			{
				$detail = $userlogClass->getLastUserLog($user['user_id']);
				if(!empty($detail))
				{
					$lastLogin = $detail['login_date'];
					$last_login = explode(" ",$lastLogin);
					$user['last_login'] = date("j M Y", strtotime($last_login[0]))." ".$last_login[1];	
				} 
				$role = explode(",", $user['role_id']);
				$user['department'] = $dept[$role[0]];
			}
		}

		Zend_Loader::LoadClass('issueClass', $this->modelDir);
		$issueClass = new issueClass();

		$userIssues = $issueClass->getUserIssuesStatistic($params['sd'], $params['ed'], 10);
		foreach($userIssues as &$ui)
		{
			$role = explode(",", $ui['role_id']);
			$ui['department'] = $dept[$role[0]];
		}

		$users2 = $userClass->getUsers($this->site_id);

		Zend_Loader::LoadClass('commentsClass', $this->modelDir);
		$commentsClass = new commentsClass();

		Zend_Loader::LoadClass('securitycommentsClass', $this->modelDir);
		$securitycommentsClass = new securitycommentsClass();

		Zend_Loader::LoadClass('safetycommentsClass', $this->modelDir);
		$safetycommentsClass = new safetycommentsClass();

		Zend_Loader::LoadClass('parkingcommentsClass', $this->modelDir);
		$parkingcommentsClass = new parkingcommentsClass();

		Zend_Loader::LoadClass('housekeepingcommentsClass', $this->modelDir);
		$housekeepingcommentsClass = new housekeepingcommentsClass();

		Zend_Loader::LoadClass('operationalcommentsClass', $this->modelDir);
		$operationalcommentsClass = new operationalcommentsClass();

		Zend_Loader::LoadClass('modcommentsClass', $this->modelDir);
		$modcommentsClass = new modcommentsClass();

		Zend_Loader::LoadClass('bmcommentsClass', $this->modelDir);
		$bmcommentsClass = new bmcommentsClass();

		$userComments = array();
		$userCommentsDept = array();
		foreach($users2 as &$user2)
		{
			$comments = $commentsClass->getTotalCommentsByUser($user2['user_id'],$params['sd'], $params['ed']);
			$securityComments = $securitycommentsClass->getTotalCommentsByUser($user2['user_id'],$params['sd'], $params['ed']);
			$safetyComments = $safetycommentsClass->getTotalCommentsByUser($user2['user_id'],$params['sd'], $params['ed']);
			$parkingComments = $parkingcommentsClass->getTotalCommentsByUser($user2['user_id'],$params['sd'], $params['ed']);
			$housekeepingComments = $housekeepingcommentsClass->getTotalCommentsByUser($user2['user_id'],$params['sd'], $params['ed']);
			$operationalComments = $operationalcommentsClass->getTotalCommentsByUser($user2['user_id'],$params['sd'], $params['ed']);
			$modComments = $modcommentsClass->getTotalCommentsByUser($user2['user_id'],$params['sd'], $params['ed']);
			$bmComments = $bmcommentsClass->getTotalCommentsByUser($user2['user_id'],$params['sd'], $params['ed']);
			$userComments[$user2['name']] = $comments['total_comment'] + $securityComments['total_comment'] + $safetyComments['total_comment'] + $parkingComments['total_comment'] + $housekeepingComments['total_comment'] + $operationalComments['total_comment'] + $modComments['total_comment'] + $bmComments['total_comment'];
			$role = explode(",", $user2['role_id']);
			$userCommentsDept[$user2['name']] = $dept[$role[0]];
		}
		arsort($userComments);

		Zend_Loader::LoadClass('siteClass', $this->modelDir);
		$siteClass = new siteClass();
		$userStatisticSummary = $siteClass->getSites();
		$params['start_date'] = $params['sd'];
		$params['end_date'] = $params['ed'];
		foreach($userStatisticSummary as &$site)
		{
			$site['total_login'] = $userlogClass->getTotalUserLog($site['site_id'], $params['sd'], $params['ed']);
			$params['site_id'] = $site['site_id'];
			
			$totalissues = $issueClass->getTotalIssues($params);
			$site['total_issues'] = $totalissues['total'];
	
			$comments2 = $commentsClass->getTotalCommentsBySiteId($site['site_id'],$params['sd'], $params['ed']);
			$securityComments2 = $securitycommentsClass->getTotalCommentsBySiteId($site['site_id'],$params['sd'], $params['ed']);
			$safetyComments2 = $safetycommentsClass->getTotalCommentsBySiteId($site['site_id'],$params['sd'], $params['ed']);
			$parkingComments2 = $parkingcommentsClass->getTotalCommentsBySiteId($site['site_id'],$params['sd'], $params['ed']);
			$housekeepingComments2 = $housekeepingcommentsClass->getTotalCommentsBySiteId($site['site_id'],$params['sd'], $params['ed']);
			$operationalComments2 = $operationalcommentsClass->getTotalCommentsBySiteId($site['site_id'],$params['sd'], $params['ed']);
			$modComments2 = $modcommentsClass->getTotalCommentsBySiteId($site['site_id'],$params['sd'], $params['ed']);
			$bmComments2 = $bmcommentsClass->getTotalCommentsBySiteId($site['site_id'],$params['sd'], $params['ed']);
			$site['total_comments'] = $comments2['total_comment'] + $securityComments2['total_comment'] + $safetyComments2['total_comment'] + $parkingComments2['total_comment'] + $housekeepingComments2['total_comment'] + $operationalComments2['total_comment'] + $modComments2['total_comment'] + $bmComments2['total_comment'];		
		}


		require('fpdf/fpdf.php');

		$pdf = new FPDF();
		$pdf->AddPage();
		$pdf->SetFont('Arial','B',15);
		$pdf->Cell(80);
		$pdf->Cell(20,10,$this->ident['initial'].' - User Statistic',0,0,'C');
		$pdf->Ln(7);
		$pdf->SetFont('Arial','B',12);
		$pdf->Cell(80);
		$pdf->Cell(20,10,$start_date." - ".$end_date,0,0,'C');
		$pdf->Ln(15);

		/*** TOP TEN USERS BY LOGIN ***/
		$pdf->SetFont('Arial','B',10);
		$pdf->SetTextColor(255);
		$pdf->SetFillColor(158,130,75);
		$pdf->Cell(0,7,'  Top Ten Users By Login',0,1,'L', true);
		$pdf->SetFillColor(158,130,75);
		$pdf->SetTextColor(255);
		$pdf->SetDrawColor(153);
		$pdf->SetLineWidth(.3);
		$pdf->SetFont('','B');
		$pdf->Ln(3);
		// Header
		$pdf->Cell(10,6,'No',1,0,'C',true);
		$pdf->Cell(60,6,'Name',1,0,'C',true);
		$pdf->Cell(40,6,'Department',1,0,'C',true);
		$pdf->Cell(30,6,'Total Login',1,0,'C',true);
		$pdf->Cell(50,6,'Last Login',1,0,'C',true);
		$pdf->Ln();
		// Color and font restoration
		$pdf->SetFillColor(238,238,238);
		$pdf->SetTextColor(0);
		$pdf->SetFont('');
		// Data
		$fill = false;
		if(!empty($users))
		{
			$i = 1;
			foreach($users as $u)
			{
				$pdf->Cell(10,6,$i,'LR',0,'R',$fill);
				$pdf->Cell(60,6,$u['name'],'LR',0,'L',$fill);
				$pdf->Cell(40,6,$u['department'],'LR',0,'L',$fill);
				$pdf->Cell(30,6,number_format($u['total_login']),'LR',0,'R',$fill);
				$pdf->Cell(50,6,$u['last_login'],'LR',0,'C',$fill);
				$pdf->Ln();
				$fill = !$fill;
				$i++;
			}
		}
		// Closing line
		$pdf->Cell(190,0,'','T');
		$pdf->Ln(8);

		/*** TOP TEN USERS BY SUBMITTING ISSUE ***/
		$pdf->SetFont('Arial','B',10);
		$pdf->SetTextColor(255);
		$pdf->SetFillColor(158,130,75);
		$pdf->Cell(0,7,'  Top Ten Users By Submitting Issues',0,1,'L', true);
		$pdf->SetFillColor(158,130,75);
		$pdf->SetTextColor(255);
		$pdf->SetDrawColor(153);
		$pdf->SetLineWidth(.3);
		$pdf->SetFont('','B');
		$pdf->Ln(3);
		// Header
		$pdf->Cell(10,6,'No',1,0,'C',true);
		$pdf->Cell(85,6,'Name',1,0,'C',true);
		$pdf->Cell(55,6,'Department',1,0,'C',true);
		$pdf->Cell(40,6,'Total Issues',1,0,'C',true);
		$pdf->Ln();
		// Color and font restoration
		$pdf->SetFillColor(238,238,238);
		$pdf->SetTextColor(0);
		$pdf->SetFont('');
		// Data
		$fill = false;
		if(!empty($userIssues))
		{
			$i = 1;
			foreach($userIssues as $ui2)
			{
				$pdf->Cell(10,6,$i,'LR',0,'R',$fill);
				$pdf->Cell(85,6,$ui2['name'],'LR',0,'L',$fill);
				$pdf->Cell(55,6,$ui2['department'],'LR',0,'L',$fill);
				$pdf->Cell(40,6,number_format($ui2['total_issues']),'LR',0,'R',$fill);
				$pdf->Ln();
				$fill = !$fill;
				$i++;
			}
		}
		// Closing line
		$pdf->Cell(190,0,'','T');
		$pdf->Ln(8);

		/*** TOP TEN USERS BY COMMENTS ***/

		$pdf->SetFont('Arial','B',10);
		$pdf->SetTextColor(255);
		$pdf->SetFillColor(158,130,75);
		$pdf->Cell(0,7,'  Top Ten Users By Comments',0,1,'L', true);
		$pdf->SetFillColor(158,130,75);
		$pdf->SetTextColor(255);
		$pdf->SetDrawColor(153);
		$pdf->SetLineWidth(.3);
		$pdf->SetFont('','B');
		$pdf->Ln(3);
		// Header
		$pdf->Cell(10,6,'No',1,0,'C',true);
		$pdf->Cell(85,6,'Name',1,0,'C',true);
		$pdf->Cell(55,6,'Department',1,0,'C',true);
		$pdf->Cell(40,6,'Total Issues',1,0,'C',true);
		$pdf->Ln();
		// Color and font restoration
		$pdf->SetFillColor(238,238,238);
		$pdf->SetTextColor(0);
		$pdf->SetFont('');
		// Data
		$fill = false;
		if(!empty($userComments))
		{
			$i = 1;
			foreach($userComments as  $key => $value) 
			{
				if($i < 11)
				{
					$pdf->Cell(10,6,$i,'LR',0,'R',$fill);
					$pdf->Cell(85,6,$key,'LR',0,'L',$fill);
					$pdf->Cell(55,6,$userCommentsDept[$key],'LR',0,'L',$fill);
					$pdf->Cell(40,6,number_format($value),'LR',0,'R',$fill);
					$pdf->Ln();
					$fill = !$fill;
				}
				$i++;
			}
		}
		// Closing line
		$pdf->Cell(190,0,'','T');
		$pdf->Ln(8);

		/*** USER STATISTIC FOR ALL SITES ***/

		$pdf->SetFont('Arial','B',10);
		$pdf->SetTextColor(255);
		$pdf->SetFillColor(158,130,75);
		$pdf->Cell(0,7,'  User Statistic for All Sites',0,1,'L', true);
		$pdf->SetFillColor(158,130,75);
		$pdf->SetTextColor(255);
		$pdf->SetDrawColor(153);
		$pdf->SetLineWidth(.3);
		$pdf->SetFont('','B');
		$pdf->Ln(3);
		// Header
		$pdf->Cell(40,6,'Sites',1,0,'C',true);
		$pdf->Cell(50,6,'By Login',1,0,'C',true);
		$pdf->Cell(50,6,'By Submitting Issues',1,0,'C',true);
		$pdf->Cell(50,6,'By Comments',1,0,'C',true);
		$pdf->Ln();
		// Color and font restoration
		$pdf->SetFillColor(238,238,238);
		$pdf->SetTextColor(0);
		$pdf->SetFont('');
		// Data
		$fill = false;
		if(!empty($userStatisticSummary))
		{
			$i = 1;
			foreach($userStatisticSummary as  $summary) 
			{
				if($i < 11)
				{
					$pdf->SetFillColor(158,130,75);
					$pdf->SetTextColor(255);
					$pdf->SetFont('','B');
					$pdf->Cell(40,6,$summary['initial'],'LR',0,'C',true);
					$pdf->SetFillColor(238,238,238);
					$pdf->SetTextColor(0);
					$pdf->SetFont('');
					$pdf->Cell(50,6,$summary['total_login'],'LR',0,'R',$fill);
					$pdf->Cell(50,6,$summary['total_issues'],'LR',0,'R',$fill);
					$pdf->Cell(50,6,$summary['total_comments'],'LR',0,'R',$fill);
					$pdf->Ln();
					$fill = !$fill;
				}
				$i++;
			}
		}
		// Closing line
		$pdf->Cell(190,0,'','T');
		$pdf->Ln(100);

		$pdf->Image($this->config->paths->html."/stat/".'user_by_login_'.$curdate.'.png',10,77);
		$pdf->Image($this->config->paths->html."/stat/".'user_by_issue_'.$curdate.'.png',110,77);
		$pdf->Ln(100);
		$pdf->Image($this->config->paths->html."/stat/".'user_by_comment_'.$curdate.'.png',10,137);
		$pdf->Output();
	}

	function loginAction()
	{		
		$params = $this->_getAllParams();
		$this->view->s = $params['s'];
		$this->renderTemplate('statistic_login.tpl');	
	}

	function dologinAction()
	{		
		$params = $this->_getAllParams();
		if(md5($params['password']) == "c11f0083bf47d58437118c9aa3b4df73" || md5($params['password']) == "13d749cc1ddaad802c4c9d83e138ea9d")
		{
			$this->session->statPasswd = md5($params['password']);
		}

		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "Login to Statistic Page";
		$logData['data'] = json_encode($params);
		$logsTable->insertLogs($logData);

		$this->_response->setRedirect("/default/statistic/".$params['s']);
		$this->_response->sendResponse();
		exit();
	}

	function siteAction()
	{
		$params = $this->_getAllParams();
		if(empty($params['start_date'])) $this->view->start_date = $params['start_date'] = date("Y-m-d", mktime(0, 0, 0, date("m")-1, date("d"),   date("Y")));
		else $this->view->start_date = $params['start_date'];
		if(empty($params['end_date'])) $this->view->end_date = $params['end_date'] = date("Y-m-d");
		else $this->view->end_date = $params['end_date'];

		Zend_Loader::LoadClass('userClass', $this->modelDir);
		$userClass = new userClass();

		$departments = $userClass->getDepartmentByRoleId();
		foreach($departments as $d)
		{
			if($d['category_id'] > 0)
			{
				$dept[$d['role_id']] = $d['category_name'];
			}
		}
		
		Zend_Loader::LoadClass('userlogClass', $this->modelDir);
		$userlogClass = new userlogClass();
		
		/** SECURITY **/
		$usersSec = $userlogClass->getUserLogByCatId($params['start_date'], $params['end_date'], 1, $this->site_id, 10);
		if(!empty($usersSec))
		{
			foreach($usersSec as &$use)
			{
				$detail = $userlogClass->getLastUserLog($use['user_id']);
				if(!empty($detail))
				{
					$lastLogin = $detail['login_date'];
					$last_login = explode(" ",$lastLogin);
					$use['last_login'] = date("j M Y", strtotime($last_login[0]))." ".substr($last_login[1],0,5);
					$role = explode(",", $use['role_id']);
					$use['department'] = $dept[$role[0]];
				}
			}
		}
		$this->view->usersSec = $usersSec;

		/** SAFETY **/
		$usersSaf = $userlogClass->getUserLogByCatId($params['start_date'], $params['end_date'], 3, $this->site_id, 10);
		if(!empty($usersSaf))
		{
			foreach($usersSaf as &$usa)
			{
				$detail = $userlogClass->getLastUserLog($usa['user_id']);
				if(!empty($detail))
				{
					$lastLogin = $detail['login_date'];
					$last_login = explode(" ",$lastLogin);
					$usa['last_login'] = date("j M Y", strtotime($last_login[0]))." ".substr($last_login[1],0,5);
					$role = explode(",", $usa['role_id']);
					$usa['department'] = $dept[$role[0]];
				}
			}
		}
		$this->view->usersSaf = $usersSaf;

		/** Engineering **/
		$usersPt = $userlogClass->getUserLogByCatId($params['start_date'], $params['end_date'], 6, $this->site_id, 10);
		if(!empty($usersPt))
		{
			foreach($usersPt as &$upt)
			{
				$detail = $userlogClass->getLastUserLog($upt['user_id']);
				if(!empty($detail))
				{
					$lastLogin = $detail['login_date'];
					$last_login = explode(" ",$lastLogin);
					$upt['last_login'] = date("j M Y", strtotime($last_login[0]))." ".substr($last_login[1],0,5);
					$role = explode(",", $upt['role_id']);
					$upt['department'] = $dept[$role[0]];
				}
			}
		}
		$this->view->usersPt = $usersPt;

		/** HOUSEKEEPING **/
		$usersHk = $userlogClass->getUserLogByCatId($params['start_date'], $params['end_date'], 2, $this->site_id, 10);
		if(!empty($usersHk))
		{
			foreach($usersHk as &$uhk)
			{
				$detail = $userlogClass->getLastUserLog($uhk['user_id']);
				if(!empty($detail))
				{
					$lastLogin = $detail['login_date'];
					$last_login = explode(" ",$lastLogin);
					$uhk['last_login'] = date("j M Y", strtotime($last_login[0]))." ".substr($last_login[1],0,5);
					$role = explode(",", $uhk['role_id']);
					$uhk['department'] = $dept[$role[0]];
				}
			}
		}
		$this->view->usersHk = $usersHk;

		if($this->site_id == 2) 
		{
			$limitUsers = 500;
			$allUsers = $userClass->getAllUsersNameDept($this->site_id);
			$allUsersLogin = array();
			$allUsersSubmitKaizen = array();
			if(!empty($allUsers))
			{
				foreach($allUsers as $au)
				{
					if($au['user_id'] > 2)
					{
						$allUsersLogin[$au['user_id']] = $allUsersSubmitKaizen[$au['user_id']] = $au;
						$allUsersLogin[$au['user_id']]['department'] = $allUsersSubmitKaizen[$au['user_id']]['department'] = $au['category_name'];
						$allUsersLogin[$au['user_id']]['last_login'] = "";
						$allUsersSubmitKaizen[$au['user_id']]['total_issues'] = 0;
					}
				}
			}
		}
		else $limitUsers = 10;
		
		$users = $userlogClass->getUserLog($params['start_date'], $params['end_date'], $limitUsers);
		if(!empty($users))
		{
			foreach($users as &$user)
			{
				$detail = $userlogClass->getLastUserLog($user['user_id']);
				if(!empty($detail))
				{
					$lastLogin = $detail['login_date'];
					$last_login = explode(" ",$lastLogin);
					$user['last_login'] = date("j M Y", strtotime($last_login[0]))." ".substr($last_login[1],0,5);
					$role = explode(",", $user['role_id']);
					$user['department'] = $dept[$role[0]];
				}
				if($this->site_id == 2) unset($allUsersLogin[$user['user_id']]);
			}
		}
		if($this->site_id == 2) $users = array_merge($users,$allUsersLogin);
		$this->view->users = $users;

		Zend_Loader::LoadClass('issueClass', $this->modelDir);
		$issueClass = new issueClass();

		$userIssues = $issueClass->getUserIssuesStatistic($params['start_date'], $params['end_date'], $limitUsers);
		foreach($userIssues as &$ui)
		{
			$role = explode(",", $ui['role_id']);
			$ui['department'] = $dept[$role[0]];
			if($this->site_id == 2) unset($allUsersSubmitKaizen[$ui['user_id']]);
		}
		if($this->site_id == 2) $userIssues = array_merge($userIssues,$allUsersSubmitKaizen);
		$this->view->userIssues = $userIssues;

		$users2 = $userClass->getUsers($this->site_id);

		Zend_Loader::LoadClass('commentsClass', $this->modelDir);
		$commentsClass = new commentsClass();

		Zend_Loader::LoadClass('securitycommentsClass', $this->modelDir);
		$securitycommentsClass = new securitycommentsClass();


		$userComments = array();
		$userCommentsDept = array();
		$totalComments = 0;
		foreach($users2 as $user2)
		{
			$comments = $commentsClass->getTotalCommentsByUser($user2['user_id'],$params['start_date'], $params['end_date']);
			$securityComments = $securitycommentsClass->getTotalCommentsByUser($user2['user_id'],$params['start_date'], $params['end_date']);
			$userComments[$user2['name']] = $comments['total_comment'] + $securityComments['total_comment'];
			$totalComments = $totalComments + $userComments[$user2['name']];
			$role = explode(",", $user2['role_id']);
			$userCommentsDept[$user2['name']] = $dept[$role[0]];
		}
		arsort($userComments);
		$this->view->userComments = $userComments;
		$this->view->userCommentsDept = $userCommentsDept;

		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "View Site Statistic";
		$logData['data'] = json_encode($params);
		$logsTable->insertLogs($logData);

		$this->renderTemplate('view_site_statistic.tpl');	
	}

	function exportsitestatistictopdfAction() {
		$params = $this->_getAllParams();

		$curdate = $params['cd'];
		$start_date = date("j M Y", mktime(0, 0, 0, substr($params['sd'],4,2), substr($params['sd'],6,2), substr($params['sd'],0,4)));
		$end_date = date("j M Y", mktime(0, 0, 0, substr($params['ed'],4,2), substr($params['ed'],6,2), substr($params['ed'],0,4)));

		Zend_Loader::LoadClass('userClass', $this->modelDir);
		$userClass = new userClass();
		
		Zend_Loader::LoadClass('userlogClass', $this->modelDir);
		$userlogClass = new userlogClass();
		
		$departments = $userClass->getDepartmentByRoleId();
		foreach($departments as $d)
		{
			if($d['category_id'] > 0)
			{
				$dept[$d['role_id']] = $d['category_name'];
			}
		}
		
		/** SECURITY **
		$usersSec = $userlogClass->getUserLogByCatId($params['sd'], $params['ed'], 1, $this->site_id, 0);
		if(!empty($usersSec))
		{
			foreach($usersSec as &$use)
			{
				$detail = $userlogClass->getLastUserLog($use['user_id']);
				if(!empty($detail))
				{
					$lastLogin = $detail['login_date'];
					$last_login = explode(" ",$lastLogin);
					$use['last_login'] = date("j M Y", strtotime($last_login[0]))." ".$last_login[1];
					$role = explode(",", $use['role_id']);
					$use['department'] = $dept[$role[0]];
				}
			}
		}

		/** SAFETY **
		$usersSaf = $userlogClass->getUserLogByCatId($params['sd'], $params['ed'], 3, $this->site_id, 0);
		if(!empty($usersSaf))
		{
			foreach($usersSaf as &$usa)
			{
				$detail = $userlogClass->getLastUserLog($usa['user_id']);
				if(!empty($detail))
				{
					$lastLogin = $detail['login_date'];
					$last_login = explode(" ",$lastLogin);
					$usa['last_login'] = date("j M Y", strtotime($last_login[0]))." ".$last_login[1];
					$role = explode(",", $usa['role_id']);
					$usa['department'] = $dept[$role[0]];
				}
			}
		}

		/** HOUSEKEEPING **/
		$usersHk = $userlogClass->getUserLogByCatId($params['sd'], $params['ed'], 2, $this->site_id, 0);
		if(!empty($usersHk))
		{
			foreach($usersHk as &$uhk)
			{
				$detail = $userlogClass->getLastUserLog($uhk['user_id']);
				if(!empty($detail))
				{
					$lastLogin = $detail['login_date'];
					$last_login = explode(" ",$lastLogin);
					$uhk['last_login'] = date("j M Y", strtotime($last_login[0]))." ".$last_login[1];
					$role = explode(",", $uhk['role_id']);
					$uhk['department'] = $dept[$role[0]];
				}
			}
		}
		
		/** Engineering **/
		$usersPt = $userlogClass->getUserLogByCatId($params['sd'], $params['ed'], 6, $this->site_id, 10);
		if(!empty($usersPt))
		{
			foreach($usersPt as &$upt)
			{
				$detail = $userlogClass->getLastUserLog($upt['user_id']);
				if(!empty($detail))
				{
					$lastLogin = $detail['login_date'];
					$last_login = explode(" ",$lastLogin);
					$upt['last_login'] = date("j M Y", strtotime($last_login[0]))." ".$last_login[1];
					$role = explode(",", $upt['role_id']);
					$upt['department'] = $dept[$role[0]];
				}
			}
		}
		
		if($this->site_id == 2) 
		{
			$limitUsers = 500;
			$allUsers = $userClass->getAllUsersNameDept($this->site_id);
			$allUsersLogin = array();
			$allUsersSubmitKaizen = array();
			if(!empty($allUsers))
			{
				foreach($allUsers as $au)
				{
					if($au['user_id'] > 2)
					{
						$allUsersLogin[$au['user_id']] = $allUsersSubmitKaizen[$au['user_id']] = $au;
						$allUsersLogin[$au['user_id']]['department'] = $allUsersSubmitKaizen[$au['user_id']]['department'] = $au['category_name'];
						$allUsersLogin[$au['user_id']]['last_login'] = "";
						$allUsersSubmitKaizen[$au['user_id']]['total_issues'] = 0;
					}
				}
			}
		}
		else $limitUsers = 10;
		
		$users = $userlogClass->getUserLog($params['sd'], $params['ed'], $limitUsers);
		if(!empty($users))
		{
			foreach($users as &$user)
			{
				$detail = $userlogClass->getLastUserLog($user['user_id']);
				if(!empty($detail))
				{
					$lastLogin = $detail['login_date'];
					$last_login = explode(" ",$lastLogin);
					$user['last_login'] = date("j M Y", strtotime($last_login[0]))." ".substr($last_login[1],0,5);
					$role = explode(",", $user['role_id']);
					$user['department'] = $dept[$role[0]];
				}				
				if($this->site_id == 2) unset($allUsersLogin[$user['user_id']]);
			}
		}
		if($this->site_id == 2) $users = array_merge($users,$allUsersLogin);

		Zend_Loader::LoadClass('issueClass', $this->modelDir);
		$issueClass = new issueClass();

		$userIssues = $issueClass->getUserIssuesStatistic($params['sd'], $params['ed'], $limitUsers);
		foreach($userIssues as &$ui)
		{
			$role = explode(",", $ui['role_id']);
			$ui['department'] = $dept[$role[0]];
			if($this->site_id == 2) unset($allUsersSubmitKaizen[$ui['user_id']]);
		}
		if($this->site_id == 2) $userIssues = array_merge($userIssues,$allUsersSubmitKaizen);
		
		$users2 = $userClass->getUsers($this->site_id);

		Zend_Loader::LoadClass('commentsClass', $this->modelDir);
		$commentsClass = new commentsClass();

		Zend_Loader::LoadClass('securitycommentsClass', $this->modelDir);
		$securitycommentsClass = new securitycommentsClass();


		$userComments = array();
		$userCommentsDept = array();
		$totalComments = 0;
		foreach($users2 as $user2)
		{
			$comments = $commentsClass->getTotalCommentsByUser($user2['user_id'],$params['sd'], $params['ed']);
			$securityComments = $securitycommentsClass->getTotalCommentsByUser($user2['user_id'],$params['sd'], $params['ed']);
			$userComments[$user2['name']] = $comments['total_comment'] + $securityComments['total_comment'];
			$totalComments = $totalComments + $userComments[$user2['name']];
			$role = explode(",", $user2['role_id']);
			$userCommentsDept[$user2['name']] = $dept[$role[0]];
		}
		arsort($userComments);
		

		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "Export Site Statistic to PDF";
		$logData['data'] = json_encode($params);
		$logsTable->insertLogs($logData);

		require('fpdf/fpdf.php');

		$pdf = new FPDF();
		$pdf->AddPage();
		$pdf->SetFont('Arial','B',15);
		$pdf->Cell(80);
		$pdf->Cell(20,10,'Site User Statistic',0,0,'C');
		$pdf->Ln(7);
		$pdf->SetFont('Arial','B',12);
		$pdf->Cell(80);
		$pdf->Cell(20,10,$start_date." - ".$end_date,0,0,'C');
		$pdf->Ln(15);

		/*** SECURITY ***
		$pdf->SetFont('Arial','B',10);
		$pdf->SetTextColor(0);
		$pdf->Cell(0,7,'Security',0,1,'L', false);
		$pdf->SetFillColor(158,130,75);
		$pdf->SetTextColor(255);
		$pdf->SetDrawColor(153);
		$pdf->SetLineWidth(.3);
		$pdf->SetFont('','B');
		$pdf->Ln(3);
		// Header
		$pdf->Cell(10,6,'No',1,0,'C',true);
		$pdf->Cell(80,6,'Name',1,0,'C',true);
		$pdf->Cell(40,6,'Total Login',1,0,'C',true);
		$pdf->Cell(60,6,'Last Login',1,0,'C',true);
		$pdf->Ln();
		// Color and font restoration
		$pdf->SetFillColor(238,238,238);
		$pdf->SetTextColor(0);
		$pdf->SetFont('');
		// Data
		$fill = false;
		if(!empty($usersSec))
		{
			$i = 1;
			foreach($usersSec as $use2)
			{
				$pdf->Cell(10,6,$i,'LR',0,'R',$fill);
				$pdf->Cell(80,6,$use2['name'],'LR',0,'L',$fill);
				$pdf->Cell(40,6,number_format($use2['total_login']),'LR',0,'R',$fill);
				$pdf->Cell(60,6,$use2['last_login'],'LR',0,'C',$fill);
				$pdf->Ln();
				$fill = !$fill;
				$i++;
			}
		}
		// Closing line
		$pdf->Cell(190,0,'','T');
		$pdf->Ln(8);

		/*** SAFETY ***
		$pdf->SetFont('Arial','B',10);
		$pdf->SetTextColor(0);
		$pdf->Cell(0,7,'Safety',0,1,'L', false);
		$pdf->SetFillColor(158,130,75);
		$pdf->SetTextColor(255);
		$pdf->SetDrawColor(153);
		$pdf->SetLineWidth(.3);
		$pdf->SetFont('','B');
		$pdf->Ln(3);
		// Header
		$pdf->Cell(10,6,'No',1,0,'C',true);
		$pdf->Cell(80,6,'Name',1,0,'C',true);
		$pdf->Cell(40,6,'Total Login',1,0,'C',true);
		$pdf->Cell(60,6,'Last Login',1,0,'C',true);
		$pdf->Ln();
		// Color and font restoration
		$pdf->SetFillColor(238,238,238);
		$pdf->SetTextColor(0);
		$pdf->SetFont('');
		// Data
		$fill = false;
		if(!empty($usersSaf))
		{
			$l = 1;
			foreach($usersSaf as $usa2)
			{
				$pdf->Cell(10,6,$l,'LR',0,'R',$fill);
				$pdf->Cell(80,6,$usa2['name'],'LR',0,'L',$fill);
				$pdf->Cell(40,6,number_format($usa2['total_login']),'LR',0,'R',$fill);
				$pdf->Cell(60,6,$usa2['last_login'],'LR',0,'C',$fill);
				$pdf->Ln();
				$fill = !$fill;
				$l++;
			}
		}
		// Closing line
		$pdf->Cell(190,0,'','T');
		$pdf->Ln(8);

		/*** HOUSEKEEPING ***/
		$pdf->SetFont('Arial','B',9);
		$pdf->SetTextColor(0);
		$pdf->Cell(0,7,'Housekeeping',0,1,'L', false);
		$pdf->SetFillColor(158,130,75);
		$pdf->SetTextColor(255);
		$pdf->SetDrawColor(153);
		$pdf->SetLineWidth(.3);
		$pdf->SetFont('','B');
		$pdf->Ln(3);
		// Header
		$pdf->Cell(10,6,'No',1,0,'C',true);
		$pdf->Cell(90,6,'Name',1,0,'C',true);
		$pdf->Cell(30,6,'Total Login',1,0,'C',true);
		$pdf->Cell(60,6,'Last Login',1,0,'C',true);
		$pdf->Ln();
		// Color and font restoration
		$pdf->SetFillColor(238,238,238);
		$pdf->SetTextColor(0);
		$pdf->SetFont('');
		// Data
		$fill = false;
		if(!empty($usersHk))
		{
			$m = 1;
			foreach($usersHk as $uhk2)
			{
				$pdf->Cell(10,6,$m,'LR',0,'R',$fill);
				$pdf->Cell(90,6,ucwords(strtolower($uhk2['name'])),'LR',0,'L',$fill);
				$pdf->Cell(30,6,number_format($uhk2['total_login']),'LR',0,'R',$fill);
				$pdf->Cell(60,6,$uhk2['last_login'],'LR',0,'C',$fill);
				$pdf->Ln();
				$fill = !$fill;
				$m++;
			}
		}
		// Closing line
		$pdf->Cell(190,0,'','T');
		$pdf->Ln(8);
		
		/*** ENGINEERING ***/
		$pdf->SetFont('Arial','B',9);
		$pdf->SetTextColor(0);
		$pdf->Cell(0,7,'Engineering',0,1,'L', false);
		$pdf->SetFillColor(158,130,75);
		$pdf->SetTextColor(255);
		$pdf->SetDrawColor(153);
		$pdf->SetLineWidth(.3);
		$pdf->SetFont('','B');
		$pdf->Ln(3);
		// Header
		$pdf->Cell(10,6,'No',1,0,'C',true);
		$pdf->Cell(90,6,'Name',1,0,'C',true);
		$pdf->Cell(30,6,'Total Login',1,0,'C',true);
		$pdf->Cell(60,6,'Last Login',1,0,'C',true);
		$pdf->Ln();
		// Color and font restoration
		$pdf->SetFillColor(238,238,238);
		$pdf->SetTextColor(0);
		$pdf->SetFont('');
		// Data
		$fill = false;
		if(!empty($usersPt))
		{
			$m = 1;
			foreach($usersPt as $upt2)
			{
				$pdf->Cell(10,6,$m,'LR',0,'R',$fill);
				$pdf->Cell(90,6,ucwords(strtolower($upt2['name'])),'LR',0,'L',$fill);
				$pdf->Cell(30,6,number_format($upt2['total_login']),'LR',0,'R',$fill);
				$pdf->Cell(60,6,$upt2['last_login'],'LR',0,'C',$fill);
				$pdf->Ln();
				$fill = !$fill;
				$m++;
			}
		}
		// Closing line
		$pdf->Cell(190,0,'','T');
		$pdf->Ln(8);


		
		/*** TOP 10 BY LOGIN ***/
		
		if($this->site_id == 1) $title = "Top 10 ";
		else $title = "";
		
		$pdf->SetFont('Arial','B',9);
		$pdf->SetTextColor(0);
		$pdf->Cell(0,7,$title.'User Statistic by Login',0,1,'L', false);
		$pdf->SetFillColor(158,130,75);
		$pdf->SetTextColor(255);
		$pdf->SetDrawColor(153);
		$pdf->SetLineWidth(.3);
		$pdf->SetFont('','B');
		$pdf->Ln(3);
		// Header
		$pdf->Cell(10,6,'No',1,0,'C',true);
		$pdf->Cell(70,6,'Name',1,0,'C',true);
		$pdf->Cell(45,6,'Department',1,0,'C',true);
		$pdf->Cell(25,6,'Total Login',1,0,'C',true);
		$pdf->Cell(40,6,'Last Login',1,0,'C',true);
		$pdf->Ln();
		// Color and font restoration
		$pdf->SetFillColor(238,238,238);
		$pdf->SetTextColor(0);
		$pdf->SetFont('');
		// Data
		$fill = false;
		if(!empty($users))
		{
			$n = 1;
			foreach($users as $user1)
			{
				$pdf->Cell(10,6,$n,'LR',0,'R',$fill);
				$pdf->Cell(70,6,ucwords(strtolower($user1['name'])),'LR',0,'L',$fill);
				$pdf->Cell(45,6,$user1['department'],'LR',0,'L',$fill);
				$pdf->Cell(25,6,number_format($user1['total_login']),'LR',0,'R',$fill);
				$pdf->Cell(40,6,$user1['last_login'],'LR',0,'C',$fill);
				$pdf->Ln();
				$fill = !$fill;
				$n++;
			}
		}
		// Closing line
		$pdf->Cell(190,0,'','T');
		$pdf->Ln(8);
	
		/*** TOP 10 BY SUBMITTING ISSUES ***/
		$pdf->SetFont('Arial','B',9);
		$pdf->SetTextColor(0);
		$pdf->Cell(0,7,$title.'User Statistic by Submitting Issues',0,1,'L', false);
		$pdf->SetFillColor(158,130,75);
		$pdf->SetTextColor(255);
		$pdf->SetDrawColor(153);
		$pdf->SetLineWidth(.3);
		$pdf->SetFont('','B');
		$pdf->Ln(3);
		// Header
		$pdf->Cell(10,6,'No',1,0,'C',true);
		$pdf->Cell(90,6,'Name',1,0,'C',true);
		$pdf->Cell(50,6,'Department',1,0,'C',true);
		$pdf->Cell(40,6,'Last Login',1,0,'C',true);
		$pdf->Ln();
		// Color and font restoration
		$pdf->SetFillColor(238,238,238);
		$pdf->SetTextColor(0);
		$pdf->SetFont('');
		// Data
		$fill = false;
		if(!empty($userIssues))
		{
			$j = 1;
			foreach($userIssues as $user2)
			{
				$pdf->Cell(10,6,$j,'LR',0,'R',$fill);
				$pdf->Cell(90,6,ucwords(strtolower($user2['name'])),'LR',0,'L',$fill);
				$pdf->Cell(50,6,$user2['department'],'LR',0,'L',$fill);
				$pdf->Cell(40,6,$user2['total_issues'],'LR',0,'C',$fill);
				$pdf->Ln();
				$fill = !$fill;
				$j++;
			}
		}
		// Closing line
		$pdf->Cell(190,0,'','T');
		$pdf->Ln(8);
	
		/*** TOP 10 BY COMMENTS ***/
		$pdf->SetFont('Arial','B',9);
		$pdf->SetTextColor(0);
		$pdf->Cell(0,7,'Top 10 Users by Comments',0,1,'L', true);
		$pdf->SetFillColor(158,130,75);
		$pdf->SetTextColor(255);
		$pdf->SetDrawColor(153);
		$pdf->SetLineWidth(.3);
		$pdf->SetFont('','B');
		$pdf->Ln(3);
		// Header
		$pdf->Cell(10,6,'No',1,0,'C',true);
		$pdf->Cell(90,6,'Name',1,0,'C',true);
		$pdf->Cell(50,6,'Department',1,0,'C',true);
		$pdf->Cell(40,6,'Total Comments',1,0,'C',true);
		$pdf->Ln();
		// Color and font restoration
		$pdf->SetFillColor(238,238,238);
		$pdf->SetTextColor(0);
		$pdf->SetFont('');
		// Data
		$fill = false;
		if(!empty($userComments))
		{
			$k=1; 
			foreach($userComments as $key => $value)
			{
				if($k > 10) continue;
				$pdf->Cell(10,6,$k,'LR',0,'R',$fill);
				$pdf->Cell(90,6,ucwords(strtolower($key)),'LR',0,'L',$fill);
				$pdf->Cell(50,6,$userCommentsDept[$key],'LR',0,'L',$fill);
				$pdf->Cell(40,6,intval($value),'LR',0,'C',$fill);
				$pdf->Ln();
				$fill = !$fill;
				$k++;
			}
		}
		// Closing line
		$pdf->Cell(190,0,'','T');
		$pdf->Ln(8);
	
		$pdf->Output();
	}

	function corporateAction()
	{
		$params = $this->_getAllParams();
		if(empty($params['start_date'])) $this->view->start_date = $params['start_date'] = date("Y-m-d", mktime(0, 0, 0, date("m")-1, date("d"),   date("Y")));
		else $this->view->start_date = $params['start_date'];
		if(empty($params['end_date'])) $this->view->end_date = $params['end_date'] = date("Y-m-d");
		else $this->view->end_date = $params['end_date'];

		Zend_Loader::LoadClass('userClass', $this->modelDir);
		$userClass = new userClass();
		
		Zend_Loader::LoadClass('userlogClass', $this->modelDir);
		$userlogClass = new userlogClass();
		
		/** SECURITY **/
		$usersSec = $userlogClass->getUserLogByCatId($params['start_date'], $params['end_date'], 1, 0, 10);
		if(!empty($usersSec))
		{
			foreach($usersSec as &$use)
			{
				$detail = $userlogClass->getLastUserLog($use['user_id']);
				if(!empty($detail))
				{
					$lastLogin = $detail['login_date'];
					$last_login = explode(" ",$lastLogin);
					$use['last_login'] = date("j M Y", strtotime($last_login[0]))." ".$last_login[1];
					$role = explode(",", $use['role_id']);
					$use['department'] = $dept[$role[0]];
				}
			}
		}
		$this->view->usersSec = $usersSec;

		$this->view->totalSecurity = $userClass->getTotalUserPerDepartment(1);

		/** SAFETY **/
		$usersSaf = $userlogClass->getUserLogByCatId($params['start_date'], $params['end_date'], 3, 0, 10);
		if(!empty($usersSaf))
		{
			foreach($usersSaf as &$usa)
			{
				$detail = $userlogClass->getLastUserLog($usa['user_id']);
				if(!empty($detail))
				{
					$lastLogin = $detail['login_date'];
					$last_login = explode(" ",$lastLogin);
					$usa['last_login'] = date("j M Y", strtotime($last_login[0]))." ".$last_login[1];
					$role = explode(",", $usa['role_id']);
					$usa['department'] = $dept[$role[0]];
				}
			}
		}
		$this->view->usersSaf = $usersSaf;

		$this->view->totalSafety = $userClass->getTotalUserPerDepartment(3);

		/** PARKING & TRAFFIC *
		$usersPt = $userlogClass->getUserLogByCatId($params['start_date'], $params['end_date'], 5, 0, 10);
		if(!empty($usersPt))
		{
			foreach($usersPt as &$upt)
			{
				$detail = $userlogClass->getLastUserLog($upt['user_id']);
				if(!empty($detail))
				{
					$lastLogin = $detail['login_date'];
					$last_login = explode(" ",$lastLogin);
					$upt['last_login'] = date("j M Y", strtotime($last_login[0]))." ".$last_login[1];
					$role = explode(",", $upt['role_id']);
					$upt['department'] = $dept[$role[0]];
				}
			}
		}
		$this->view->usersPt = $usersPt;

		$this->view->totalParking = $userClass->getTotalUserPerDepartment(5); */
		
		/** HOUSEKEEPING **/
		$usersHk = $userlogClass->getUserLogByCatId($params['start_date'], $params['end_date'], 2, 0, 10);
		if(!empty($usersHk))
		{
			foreach($usersHk as &$uhk)
			{
				$detail = $userlogClass->getLastUserLog($uhk['user_id']);
				if(!empty($detail))
				{
					$lastLogin = $detail['login_date'];
					$last_login = explode(" ",$lastLogin);
					$uhk['last_login'] = date("j M Y", strtotime($last_login[0]))." ".$last_login[1];
					$role = explode(",", $uhk['role_id']);
					$uhk['department'] = $dept[$role[0]];
				}
			}
		}
		$this->view->usersHk = $usersHk;

		$this->view->totalHousekeeping = $userClass->getTotalUserPerDepartment(2);
		
		/** ENGINEERING **/
		$usersEng = $userlogClass->getUserLogByCatId($params['start_date'], $params['end_date'], 6, 0, 10);
		if(!empty($usersEng))
		{
			foreach($usersEng as &$ue)
			{
				$detail = $userlogClass->getLastUserLog($ue['user_id']);
				if(!empty($detail))
				{
					$lastLogin = $detail['login_date'];
					$last_login = explode(" ",$lastLogin);
					$ue['last_login'] = date("j M Y", strtotime($last_login[0]))." ".$last_login[1];
					$role = explode(",", $ue['role_id']);
					$ue['department'] = $dept[$role[0]];
				}
			}
		}
		$this->view->usersEng = $usersEng;

		$this->view->totalEngineering = $userClass->getTotalUserPerDepartment(6);
		
		
		Zend_Loader::LoadClass('commentsClass', $this->modelDir);
		$commentsClass = new commentsClass();

		/*** OM Login **
		$om = $userlogClass->getOMLoginStat($params['start_date'], $params['end_date']);
		if(!empty($om))
		{
			foreach($om as &$o)
			{
				$detail2 = $userlogClass->getLastUserLog($o['user_id']);
				if(!empty($detail2))
				{
					$lastLogin = $detail2['login_date'];
					$last_login = explode(" ",$lastLogin);
					$o['last_login'] = date("j M Y", strtotime($last_login[0]))." ".$last_login[1];
					$role = explode(",", $o['role_id']);
					$o['department'] = $dept[$role[0]];
				}
			}
		}
		$this->view->om = $om;*/

		
		/*** OM Comments **

		$omcm = $userClass->getOMUsers();
		if(!empty($omcm))
		{
			foreach($omcm as &$oc)
			{
				$oc['total_issue_finding'] = $commentsClass->getTotalCommentIssueFindingByUser($oc['user_id'], $params['start_date'], $params['end_date']);
				$oc['total_daily_report'] = $commentsClass->getTotalCommentDailyReportByUser($params['start_date'], $params['end_date'], $oc['user_id']);
				$oc['total_comment'] = $oc['total_issue_finding'] + $oc['total_daily_report'];
			}
		}

		usort($omcm, function($a, $b) {
			return $b['total_comment'] - $a['total_comment']; // utk sort asc: $a['total_comment'] - $b['total_comment']
		});

		$this->view->omcm = $omcm; */

		Zend_Loader::LoadClass('issueClass', $this->modelDir);
		$issueClass = new issueClass();

		Zend_Loader::LoadClass('securitycommentsClass', $this->modelDir);
		$securitycommentsClass = new securitycommentsClass();

		Zend_Loader::LoadClass('actionplanClass', $this->modelDir);
		$actionplanClass = new actionplanClass();

		$userStatisticSummary = $issueClass->getTotalIssuesBySites($params);
		foreach($userStatisticSummary as &$site)
		{
			$site['total_login'] = $userlogClass->getTotalUserLog($site['site_id'], $params['start_date'], $params['end_date']);
	
			$comments2 = $commentsClass->getTotalCommentsBySiteId($site['site_id'],$params['start_date'], $params['end_date']);
			$securityComments2 = $securitycommentsClass->getTotalCommentsBySiteId($site['site_id'],$params['start_date'], $params['end_date']);
			
			$site['total_comments'] = $comments2['total_comment'] + $securityComments2['total_comment'];		
		
			$outstandingSec = $actionplanClass->getTotalOutstanding($site['site_id'], 1, date("Y"));
			$site['outstandingSec'] = $outstandingSec['total'];
			$outstandingSaf = $actionplanClass->getTotalOutstanding($site['site_id'], 3, date("Y"));
			$site['outstandingSaf'] = $outstandingSaf['total'];
			$outstandingPark = $actionplanClass->getTotalOutstanding($site['site_id'], 5, date("Y"));
			$site['outstandingPark'] = $outstandingPark['total'];
		}
		$this->view->userStatisticSummary = $userStatisticSummary;

		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "View Corporate Statistic";
		$logData['data'] = json_encode($params);
		$logsTable->insertLogs($logData);

		$this->renderTemplate('view_corporate_statistic.tpl');	
	}

	function exportcorporatestatistictopdfAction() {
		$params = $this->_getAllParams();

		$curdate = $params['cd'];
		$start_date = date("j M Y", mktime(0, 0, 0, substr($params['sd'],4,2), substr($params['sd'],6,2), substr($params['sd'],0,4)));
		$end_date = date("j M Y", mktime(0, 0, 0, substr($params['ed'],4,2), substr($params['ed'],6,2), substr($params['ed'],0,4)));

		Zend_Loader::LoadClass('userClass', $this->modelDir);
		$userClass = new userClass();
		
		Zend_Loader::LoadClass('userlogClass', $this->modelDir);
		$userlogClass = new userlogClass();
		
		/** SECURITY **/
		$usersSec = $userlogClass->getUserLogByCatId($params['sd'], $params['ed'], 1, 0, 10);
		if(!empty($usersSec))
		{
			foreach($usersSec as &$use)
			{
				$detail = $userlogClass->getLastUserLog($use['user_id']);
				if(!empty($detail))
				{
					$lastLogin = $detail['login_date'];
					$last_login = explode(" ",$lastLogin);
					$use['last_login'] = date("j M Y", strtotime($last_login[0]))." ".$last_login[1];
					$role = explode(",", $use['role_id']);
					$use['department'] = $dept[$role[0]];
				}
			}
		}

		/** SAFETY **/
		$usersSaf = $userlogClass->getUserLogByCatId($params['sd'], $params['ed'], 3, 0, 10);
		if(!empty($usersSaf))
		{
			foreach($usersSaf as &$usa)
			{
				$detail = $userlogClass->getLastUserLog($usa['user_id']);
				if(!empty($detail))
				{
					$lastLogin = $detail['login_date'];
					$last_login = explode(" ",$lastLogin);
					$usa['last_login'] = date("j M Y", strtotime($last_login[0]))." ".$last_login[1];
					$role = explode(",", $usa['role_id']);
					$usa['department'] = $dept[$role[0]];
				}
			}
		}

		/** PARKING & TRAFFIC **/
		$usersPt = $userlogClass->getUserLogByCatId($params['sd'], $params['ed'], 5, 0, 10);
		if(!empty($usersPt))
		{
			foreach($usersPt as &$upt)
			{
				$detail = $userlogClass->getLastUserLog($upt['user_id']);
				if(!empty($detail))
				{
					$lastLogin = $detail['login_date'];
					$last_login = explode(" ",$lastLogin);
					$upt['last_login'] = date("j M Y", strtotime($last_login[0]))." ".$last_login[1];
					$role = explode(",", $upt['role_id']);
					$upt['department'] = $dept[$role[0]];
				}
			}
		}

		/** HOUSEKEEPING **/
		$usersHk = $userlogClass->getUserLogByCatId($params['sd'], $params['ed'], 2, 0, 10);
		if(!empty($usersHk))
		{
			foreach($usersHk as &$uhk)
			{
				$detail = $userlogClass->getLastUserLog($uhk['user_id']);
				if(!empty($detail))
				{
					$lastLogin = $detail['login_date'];
					$last_login = explode(" ",$lastLogin);
					$uhk['last_login'] = date("j M Y", strtotime($last_login[0]))." ".$last_login[1];
					$role = explode(",", $uhk['role_id']);
					$uhk['department'] = $dept[$role[0]];
				}
			}
		}

		/*** OM Login ***/
		$om = $userlogClass->getOMLoginStat($params['sd'], $params['ed']);
		if(!empty($om))
		{
			foreach($om as &$o)
			{
				$detail2 = $userlogClass->getLastUserLog($o['user_id']);
				if(!empty($detail2))
				{
					$lastLogin = $detail2['login_date'];
					$last_login = explode(" ",$lastLogin);
					$o['last_login'] = date("j M Y", strtotime($last_login[0]))." ".$last_login[1];
					$role = explode(",", $o['role_id']);
					$o['department'] = $dept[$role[0]];
				}
			}
		}

		/*** OM Comments ***/

		Zend_Loader::LoadClass('commentsClass', $this->modelDir);
		$commentsClass = new commentsClass();

		Zend_Loader::LoadClass('userClass', $this->modelDir);
		$userClass = new userClass();

		$omcm = $userClass->getOMUsers();
		if(!empty($omcm))
		{
			foreach($omcm as &$oc)
			{
				$oc['total_issue_finding'] = $commentsClass->getTotalCommentIssueFindingByUser($oc['user_id'], $params['sd'], $params['ed']);
				$oc['total_daily_report'] = $commentsClass->getTotalCommentDailyReportByUser($params['sd'], $params['ed'], $oc['user_id']);
				$oc['total_comment'] = $oc['total_issue_finding'] + $oc['total_daily_report'];
			}
		}

		usort($omcm, function($a, $b) {
			return $b['total_comment'] - $a['total_comment']; // utk sort asc: $a['total_comment'] - $b['total_comment']
		});

		Zend_Loader::LoadClass('issueClass', $this->modelDir);
		$issueClass = new issueClass();

		Zend_Loader::LoadClass('siteClass', $this->modelDir);
		$siteClass = new siteClass();
		$userStatisticSummary = $siteClass->getSites();

		/*Zend_Loader::LoadClass('securitycommentsClass', $this->modelDir);
		$securitycommentsClass = new securitycommentsClass();

		Zend_Loader::LoadClass('safetycommentsClass', $this->modelDir);
		$safetycommentsClass = new safetycommentsClass();

		Zend_Loader::LoadClass('parkingcommentsClass', $this->modelDir);
		$parkingcommentsClass = new parkingcommentsClass();

		Zend_Loader::LoadClass('housekeepingcommentsClass', $this->modelDir);
		$housekeepingcommentsClass = new housekeepingcommentsClass();

		Zend_Loader::LoadClass('operationalcommentsClass', $this->modelDir);
		$operationalcommentsClass = new operationalcommentsClass();

		Zend_Loader::LoadClass('modcommentsClass', $this->modelDir);
		$modcommentsClass = new modcommentsClass();

		Zend_Loader::LoadClass('bmcommentsClass', $this->modelDir);
		$bmcommentsClass = new bmcommentsClass();*/

		foreach($userStatisticSummary as &$site)
		{
			$site['total_login'] = $userlogClass->getTotalUserLog($site['site_id'], $params['sd'], $params['ed']);
			$params['site_id'] = $site['site_id'];
			$params['start_date'] = $params['sd'];
			$params['end_date'] = $params['ed'];
			$totalissues = $issueClass->getTotalIssues($params);
			$site['total_issues'] = $totalissues['total'];
	
			$comments2 = $commentsClass->getTotalCommentsBySiteId($site['site_id'],$params['sd'], $params['ed']);
			/*$securityComments2 = $securitycommentsClass->getTotalCommentsBySiteId($site['site_id'],$params['sd'], $params['ed']);
			$safetyComments2 = $safetycommentsClass->getTotalCommentsBySiteId($site['site_id'],$params['sd'], $params['ed']);
			$parkingComments2 = $parkingcommentsClass->getTotalCommentsBySiteId($site['site_id'],$params['sd'], $params['ed']);
			$housekeepingComments2 = $housekeepingcommentsClass->getTotalCommentsBySiteId($site['site_id'],$params['sd'], $params['ed']);
			$operationalComments2 = $operationalcommentsClass->getTotalCommentsBySiteId($site['site_id'],$params['sd'], $params['ed']);
			$modComments2 = $modcommentsClass->getTotalCommentsBySiteId($site['site_id'],$params['sd'], $params['ed']);
			$bmComments2 = $bmcommentsClass->getTotalCommentsBySiteId($site['site_id'],$params['sd'], $params['ed']);*/
			$site['total_comments'] = $comments2['total_comment'] /*+ $securityComments2['total_comment'] + $safetyComments2['total_comment'] + $parkingComments2['total_comment'] + $housekeepingComments2['total_comment'] + $operationalComments2['total_comment'] + $modComments2['total_comment'] + $bmComments2['total_comment']*/;		
		}

		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "Export Corporate Statistic to PDF";
		$logData['data'] = json_encode($params);
		$logsTable->insertLogs($logData);

		require('fpdf/fpdf.php');

		$pdf = new FPDF();
		$pdf->AddPage();
		$pdf->SetFont('Arial','B',15);
		$pdf->Cell(80);
		$pdf->Cell(20,10,'Corporate User Statistic',0,0,'C');
		$pdf->Ln(7);
		$pdf->SetFont('Arial','B',12);
		$pdf->Cell(80);
		$pdf->Cell(20,10,$start_date." - ".$end_date,0,0,'C');
		$pdf->Ln(15);

		/*** SECURITY ***/
		$pdf->SetFont('Arial','B',10);
		$pdf->SetTextColor(255);
		$pdf->SetFillColor(158,130,75);
		$pdf->Cell(0,7,'Corporate - Security by Login',0,1,'L', true);
		$pdf->SetFillColor(158,130,75);
		$pdf->SetTextColor(255);
		$pdf->SetDrawColor(153);
		$pdf->SetLineWidth(.3);
		$pdf->SetFont('','B',9);
		$pdf->Ln(3);
		// Header
		$pdf->Cell(10,6,'No',1,0,'C',true);
		$pdf->Cell(70,6,'Name',1,0,'C',true);
		$pdf->Cell(30,6,'Site',1,0,'C',true);
		$pdf->Cell(30,6,'Total Login',1,0,'C',true);
		$pdf->Cell(50,6,'Last Login',1,0,'C',true);
		$pdf->Ln();
		// Color and font restoration
		$pdf->SetFillColor(238,238,238);
		$pdf->SetTextColor(0);
		$pdf->SetFont('Arial','',9);
		// Data
		$fill = false;
		if(!empty($usersSec))
		{
			$i = 1;
			foreach($usersSec as $use2)
			{
				$pdf->Cell(10,6,$i,'LR',0,'R',$fill);
				$pdf->Cell(70,6,$use2['name'],'LR',0,'L',$fill);
				$pdf->Cell(30,6,$use2['initial'],'LR',0,'C',$fill);
				$pdf->Cell(30,6,number_format($use2['total_login']),'LR',0,'R',$fill);
				$pdf->Cell(50,6,$use2['last_login'],'LR',0,'C',$fill);
				$pdf->Ln();
				$fill = !$fill;
				$i++;
			}
		}
		// Closing line
		$pdf->Cell(190,0,'','T');
		$pdf->Ln(8);

		/*** SAFETY ***/
		$pdf->SetFont('Arial','B',10);
		$pdf->SetTextColor(255);
		$pdf->SetFillColor(158,130,75);
		$pdf->Cell(0,7,'Corporate - Safety by Login',0,1,'L', true);
		$pdf->SetFillColor(158,130,75);
		$pdf->SetTextColor(255);
		$pdf->SetDrawColor(153);
		$pdf->SetLineWidth(.3);
		$pdf->SetFont('','B',9);
		$pdf->Ln(3);
		// Header
		$pdf->Cell(10,6,'No',1,0,'C',true);
		$pdf->Cell(70,6,'Name',1,0,'C',true);
		$pdf->Cell(30,6,'Site',1,0,'C',true);
		$pdf->Cell(30,6,'Total Login',1,0,'C',true);
		$pdf->Cell(50,6,'Last Login',1,0,'C',true);
		$pdf->Ln();
		// Color and font restoration
		$pdf->SetFillColor(238,238,238);
		$pdf->SetTextColor(0);
		$pdf->SetFont('Arial','',9);
		// Data
		$fill = false;
		if(!empty($usersSaf))
		{
			$i = 1;
			foreach($usersSaf as $usa2)
			{
				$pdf->Cell(10,6,$i,'LR',0,'R',$fill);
				$pdf->Cell(70,6,$usa2['name'],'LR',0,'L',$fill);
				$pdf->Cell(30,6,$usa2['initial'],'LR',0,'C',$fill);
				$pdf->Cell(30,6,number_format($usa2['total_login']),'LR',0,'R',$fill);
				$pdf->Cell(50,6,$usa2['last_login'],'LR',0,'C',$fill);
				$pdf->Ln();
				$fill = !$fill;
				$i++;
			}
		}
		// Closing line
		$pdf->Cell(190,0,'','T');
		$pdf->Ln(8);

		/*** PARKING & TRAFFIC ***/
		$pdf->SetFont('Arial','B',10);
		$pdf->SetTextColor(255);
		$pdf->SetFillColor(158,130,75);
		$pdf->Cell(0,7,'Corporate - Parking & Traffic by Login',0,1,'L', true);
		$pdf->SetFillColor(158,130,75);
		$pdf->SetTextColor(255);
		$pdf->SetDrawColor(153);
		$pdf->SetLineWidth(.3);
		$pdf->SetFont('','B',9);
		$pdf->Ln(3);
		// Header
		$pdf->Cell(10,6,'No',1,0,'C',true);
		$pdf->Cell(70,6,'Name',1,0,'C',true);
		$pdf->Cell(30,6,'Site',1,0,'C',true);
		$pdf->Cell(30,6,'Total Login',1,0,'C',true);
		$pdf->Cell(50,6,'Last Login',1,0,'C',true);
		$pdf->Ln();
		// Color and font restoration
		$pdf->SetFillColor(238,238,238);
		$pdf->SetTextColor(0);
		$pdf->SetFont('Arial','',9);
		// Data
		$fill = false;
		if(!empty($usersPt))
		{
			$i = 1;
			foreach($usersPt as $upt2)
			{
				$pdf->Cell(10,6,$i,'LR',0,'R',$fill);
				$pdf->Cell(70,6,$upt2['name'],'LR',0,'L',$fill);
				$pdf->Cell(30,6,$upt2['initial'],'LR',0,'C',$fill);
				$pdf->Cell(30,6,number_format($upt2['total_login']),'LR',0,'R',$fill);
				$pdf->Cell(50,6,$upt2['last_login'],'LR',0,'C',$fill);
				$pdf->Ln();
				$fill = !$fill;
				$i++;
			}
		}
		// Closing line
		$pdf->Cell(190,0,'','T');
		$pdf->Ln(8);

		/*** HOUSEKEEPING ***/
		$pdf->SetFont('Arial','B',10);
		$pdf->SetTextColor(255);
		$pdf->SetFillColor(158,130,75);
		$pdf->Cell(0,7,'Corporate - Housekeeping by Login',0,1,'L', true);
		$pdf->SetFillColor(158,130,75);
		$pdf->SetTextColor(255);
		$pdf->SetDrawColor(153);
		$pdf->SetLineWidth(.3);
		$pdf->SetFont('','B',9);
		$pdf->Ln(3);
		// Header
		$pdf->Cell(10,6,'No',1,0,'C',true);
		$pdf->Cell(70,6,'Name',1,0,'C',true);
		$pdf->Cell(30,6,'Site',1,0,'C',true);
		$pdf->Cell(30,6,'Total Login',1,0,'C',true);
		$pdf->Cell(50,6,'Last Login',1,0,'C',true);
		$pdf->Ln();
		// Color and font restoration
		$pdf->SetFillColor(238,238,238);
		$pdf->SetTextColor(0);
		$pdf->SetFont('Arial','',9);
		// Data
		$fill = false;
		if(!empty($usersHk))
		{
			$i = 1;
			foreach($usersHk as $uhk2)
			{
				$pdf->Cell(10,6,$i,'LR',0,'R',$fill);
				$pdf->Cell(70,6,$uhk2['name'],'LR',0,'L',$fill);
				$pdf->Cell(30,6,$uhk2['initial'],'LR',0,'C',$fill);
				$pdf->Cell(30,6,number_format($uhk2['total_login']),'LR',0,'R',$fill);
				$pdf->Cell(50,6,$uhk2['last_login'],'LR',0,'C',$fill);
				$pdf->Ln();
				$fill = !$fill;
				$i++;
			}
		}
		// Closing line
		$pdf->Cell(190,0,'','T');
		$pdf->Ln(8);

		/*** OM LOGIN ***/
		/*$pdf->SetFont('Arial','B',10);
		$pdf->SetTextColor(255);
		$pdf->SetFillColor(158,130,75);
		$pdf->Cell(0,7,'Corporate - Operational Managers by Login',0,1,'L', true);
		$pdf->SetFillColor(158,130,75);
		$pdf->SetTextColor(255);
		$pdf->SetDrawColor(153);
		$pdf->SetLineWidth(.3);
		$pdf->SetFont('','B',9);
		$pdf->Ln(3);
		// Header
		$pdf->Cell(10,6,'No',1,0,'C',true);
		$pdf->Cell(70,6,'Name',1,0,'C',true);
		$pdf->Cell(30,6,'Site',1,0,'C',true);
		$pdf->Cell(30,6,'Total Login',1,0,'C',true);
		$pdf->Cell(50,6,'Last Login',1,0,'C',true);
		$pdf->Ln();
		// Color and font restoration
		$pdf->SetFillColor(238,238,238);
		$pdf->SetTextColor(0);
		$pdf->SetFont('Arial','',9);
		// Data
		$fill = false;
		if(!empty($om))
		{
			$i = 1;
			foreach($om as $om2)
			{
				$pdf->Cell(10,6,$i,'LR',0,'R',$fill);
				$pdf->Cell(70,6,$om2['name'],'LR',0,'L',$fill);
				$pdf->Cell(30,6,$om2['initial'],'LR',0,'C',$fill);
				$pdf->Cell(30,6,number_format($om2['total_login']),'LR',0,'R',$fill);
				$pdf->Cell(50,6,$om2['last_login'],'LR',0,'C',$fill);
				$pdf->Ln();
				$fill = !$fill;
				$i++;
			}
		}
		// Closing line
		$pdf->Cell(190,0,'','T');
		$pdf->Ln(8);

		/*** OM COMMENT ***/
		/*$pdf->SetFont('Arial','B',10);
		$pdf->SetTextColor(255);
		$pdf->SetFillColor(158,130,75);
		$pdf->Cell(0,7,'Corporate - Operational Managers by Comment',0,1,'L', true);
		$pdf->SetFillColor(158,130,75);
		$pdf->SetTextColor(255);
		$pdf->SetDrawColor(153);
		$pdf->SetLineWidth(.3);
		$pdf->SetFont('','B',9);
		$pdf->Ln(3);
		// Header
		$pdf->Cell(10,6,'No',1,0,'C',true);
		$pdf->Cell(64,6,'Name',1,0,'C',true);
		$pdf->Cell(20,6,'Site',1,0,'C',true);
		$pdf->Cell(48,6,'Total Comments Issue Finding',1,0,'C',true);
		$pdf->Cell(48,6,'Total Comments Daily Report',1,0,'C',true);
		$pdf->Ln();
		// Color and font restoration
		$pdf->SetFillColor(238,238,238);
		$pdf->SetTextColor(0);
		$pdf->SetFont('Arial','',9);
		// Data
		$fill = false;
		if(!empty($omcm))
		{
			$i = 1;
			foreach($omcm as $oc)
			{
				$pdf->Cell(10,6,$i,'LR',0,'R',$fill);
				$pdf->Cell(64,6,$oc['name'],'LR',0,'L',$fill);
				$pdf->Cell(20,6,$oc['initial'],'LR',0,'C',$fill);
				$pdf->Cell(48,6,number_format($oc['total_issue_finding']),'LR',0,'R',$fill);
				$pdf->Cell(48,6,number_format($oc['total_daily_report']),'LR',0,'R',$fill);
				$pdf->Ln();
				$fill = !$fill;
				$i++;
			}
		}
		// Closing line
		$pdf->Cell(190,0,'','T');
		$pdf->Ln(8);


		/*** USER STATISTIC FOR ALL SITES ***/

		$pdf->SetFont('Arial','B',10);
		$pdf->SetTextColor(255);
		$pdf->SetFillColor(158,130,75);
		$pdf->Cell(0,7,'  User Statistic for All Sites',0,1,'L', true);
		$pdf->SetFillColor(158,130,75);
		$pdf->SetTextColor(255);
		$pdf->SetDrawColor(153);
		$pdf->SetLineWidth(.3);
		$pdf->SetFont('','B',9);
		$pdf->Ln(3);
		// Header
		$pdf->Cell(40,6,'Sites',1,0,'C',true);
		$pdf->Cell(50,6,'By Login',1,0,'C',true);
		$pdf->Cell(50,6,'By Submitting Issues',1,0,'C',true);
		$pdf->Cell(50,6,'By Comments',1,0,'C',true);
		$pdf->Ln();
		// Color and font restoration
		$pdf->SetFillColor(238,238,238);
		$pdf->SetTextColor(0);
		$pdf->SetFont('Arial','',9);
		// Data
		$fill = false;
		if(!empty($userStatisticSummary))
		{
			$i = 1;
			foreach($userStatisticSummary as  $summary) 
			{
				if($i < 11)
				{
					$pdf->SetFillColor(158,130,75);
					$pdf->SetTextColor(255);
					$pdf->SetFont('','B');
					$pdf->Cell(40,6,$summary['initial'],'LR',0,'C',true);
					$pdf->SetFillColor(238,238,238);
					$pdf->SetTextColor(0);
					$pdf->SetFont('');
					$pdf->Cell(50,6,$summary['total_login'],'LR',0,'R',$fill);
					$pdf->Cell(50,6,$summary['total_issues'],'LR',0,'R',$fill);
					$pdf->Cell(50,6,$summary['total_comments'],'LR',0,'R',$fill);
					$pdf->Ln();
					$fill = !$fill;
				}
				$i++;
			}
		}
		// Closing line
		$pdf->Cell(190,0,'','T');
		$pdf->Ln(10);

		$yaxis = $pdf->getY();

		$pdf->Image($this->config->paths->html."/stat/".'user_by_login_'.$curdate.'.png',5,$yaxis,80);
		$pdf->Image($this->config->paths->html."/stat/".'user_by_issue_'.$curdate.'.png',100,$yaxis,80);
		$pdf->Ln();
		$pdf->Image($this->config->paths->html."/stat/".'user_by_comment_'.$curdate.'.png',5,$yaxis+50,80);
		
		/*$pdf->Cell(20,10,'',0,0,'C');
		$pdf->Image($this->config->paths->html."/stat/".'ap_outstanding_sec_'.$curdate.'.png',5,$yaxis+50,70);			
		$pdf->Image($this->config->paths->html."/stat/".'ap_outstanding_saf_'.$curdate.'.png',75,$yaxis+50,70);
		$pdf->Image($this->config->paths->html."/stat/".'ap_outstanding_park_'.$curdate.'.png',140,$yaxis+50,70);*/
		$pdf->Ln(100);
		$pdf->Output();
	}

	function exportapindividualtopdfAction() {
		$params = $this->_getAllParams();

		$curdate = $params['cd'];
		
		Zend_Loader::LoadClass('actionplanClass', $this->modelDir);
		$actionplanClass = new actionplanClass();

		$prefix = explode("_", $params['pf']);

		$outstanding = $actionplanClass->getActionPlanCurrentYearReviewSchedule2($this->site_id, $prefix[1], date("Y-m-d"));

		$reschedule = $actionplanClass->getActionPlanCurrentYearReschedule($this->site_id, $prefix[1], $params['y']);

		$done = $actionplanClass->getActionPlanCurrentYearDone($this->site_id, $prefix[1], $params['y']);
		
		Zend_Loader::LoadClass('categoryClass', $this->modelDir);
		$categoryClass = new categoryClass();
	
		$category = $categoryClass->getCategoryById($prefix[1]);

		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "Export Individual Action Plan to PDF";
		$logData['data'] = json_encode($params);
		$logsTable->insertLogs($logData);

		require('fpdf/fpdf.php');

		if($category['category_id'] == 6)	$title = "Preventive Maintenance";
		else $title = "Action Plan";
		
       		$title = $prefix[0].' - '.$category['category_name'].' - '.$title.' '.$params['y'].' Statistic';
       	
		$pdf = new FPDF();
		$pdf->AddPage();
		$pdf->SetFont('Arial','B',14);
		$pdf->Cell(80);
		$pdf->Cell(20,10,$title,0,0,'C');
		$pdf->Ln(20);

		$pdf->Image($this->config->paths->html."/stat/".$params['pf'].'_'.$curdate.'.png',10,20);

		$pdf->Ln(30);
		$pdf->SetFont('Arial','B',10);
		$pdf->Cell(20,10,'Outstanding - '.$title.' '.$params['y'],0,0,'L');
		$pdf->Ln();
		$pdf->SetFillColor(158,130,75);
		$pdf->SetTextColor(255);
		$pdf->SetDrawColor(153);
		$pdf->SetLineWidth(.3);
		$pdf->SetFont('','B', 9);		
		// Header
		$pdf->Cell(10,6,'No',1,0,'C',true);
		$pdf->Cell(30,6,'Date',1,0,'C',true);
		$pdf->Cell(150,6,'Planning Action',1,0,'C',true);
		$pdf->Ln();
		
		// Color and font restoration
		$pdf->SetFillColor(238,238,238);
		$pdf->SetTextColor(0);
		$pdf->SetFont('');
		// Data
		$fill = false;
		if(!empty($outstanding))
		{
			$i = 1;
			foreach($outstanding as $o)
			{
				$date = explode(" ", $o['schedule_date']);
				$formatted_schedule_date = date("j M Y", strtotime($date[0]));
				$pdf->Cell(10,6,$i,'LR',0,'R',$fill);
				$pdf->Cell(30,6,$formatted_schedule_date,'LR',0,'L',$fill);
				$pdf->Cell(150,6,$o['activity_name'],'LR',0,'L',$fill);
				$pdf->Ln();
				$fill = !$fill;
				$i++;
			}
		}
		$pdf->Cell(190,0,'','T');

		$pdf->Ln(5);
		$pdf->SetFont('Arial','B',10);
		$pdf->Cell(20,10,$title.' Reshedule List '.$params['y'],0,0,'L');
		$pdf->Ln();
		$pdf->SetFillColor(158,130,75);
		$pdf->SetTextColor(255);
		$pdf->SetDrawColor(153);
		$pdf->SetLineWidth(.3);
		$pdf->SetFont('','B', 9);		
		// Header
		$pdf->Cell(7,6,'No',1,0,'C',true);		
		$pdf->Cell(131,6,'Planning Action',1,0,'C',true);
		$pdf->Cell(52,6,'Reschedule Date',1,0,'C',true);
		$pdf->Ln();
		
		// Color and font restoration
		$pdf->SetFillColor(238,238,238);
		$pdf->SetTextColor(0);
		$pdf->SetFont('');
		// Data
		$fill = false;
		if(!empty($reschedule))
		{
			$j = 1;
			$schedule_id = 0;
			foreach($reschedule as $r)
			{
				$date = explode(" ", $r['original_date']);
				$original_date = date("j M Y", strtotime($date[0]));
				$date = explode(" ", $r['reschedule_date']);
				$reschedule_date = date("j M Y", strtotime($date[0]));
				$keterangan = "From ". $original_date ." to ". $reschedule_date;
				if($r['schedule_id'] == $schedule_id)
				{
					$pdf->Cell(7,6,'','LR',0,'R',$fill);				
					$pdf->Cell(131,6,'','LR',0,'L',$fill);
					$pdf->Cell(52,6,$keterangan,'LR',0,'L',$fill);
					$pdf->Ln();
				}
				else {					
					$fill = !$fill;
					$pdf->Cell(7,6,$j,'LR',0,'R',$fill);				
					$pdf->Cell(131,6,$r['activity_name'],'LR',0,'L',$fill);
					$pdf->Cell(52,6,$keterangan,'LR',0,'L',$fill);
					$pdf->Ln();
					$j++;
				}
				$schedule_id = $r['schedule_id'];				
			}
		}
		// Closing line
		$pdf->Cell(190,0,'','T');
		
		$pdf->Ln(5);
		$pdf->SetFont('Arial','B',10);
		$pdf->Cell(20,10,'Done - '.$title.' '.$params['y'],0,0,'L');
		$pdf->Ln();
		$pdf->SetFillColor(158,130,75);
		$pdf->SetTextColor(255);
		$pdf->SetDrawColor(153);
		$pdf->SetLineWidth(.3);
		$pdf->SetFont('','B', 9);		
		// Header
		$pdf->Cell(10,6,'No',1,0,'C',true);
		$pdf->Cell(30,6,'Date',1,0,'C',true);
		$pdf->Cell(150,6,'Planning Action',1,0,'C',true);
		$pdf->Ln();
		
		// Color and font restoration
		$pdf->SetFillColor(238,238,238);
		$pdf->SetTextColor(0);
		$pdf->SetFont('');
		// Data
		$fill = false;
		if(!empty($done))
		{
			$i = 1;
			foreach($done as $d)
			{
				$date = explode(" ", $d['schedule_date']);
				$formatted_schedule_date = date("j M Y", strtotime($date[0]));
				$pdf->Cell(10,6,$i,'LR',0,'R',$fill);
				$pdf->Cell(30,6,$formatted_schedule_date,'LR',0,'L',$fill);
				$pdf->Cell(150,6,$d['activity_name'],'LR',0,'L',$fill);
				$pdf->Ln();
				$fill = !$fill;
				$i++;
			}
		}
		$pdf->Cell(190,0,'','T');
	
		$pdf->Output();
	}

	function saveactionplangraphAction()
	{
		$params = $this->_getAllParams();

		$curdate = date("YmdHis");

		$magickPath = "/usr/bin/convert";
		$apgraph = $this->config->paths->html."/stat/".$params['prefix'].'_'.$curdate.'.png';

		// remove "data:image/png;base64,"
		$apgraph_uri =  str_replace("data:image/png;base64","", $params['pie']);
		file_put_contents($apgraph, base64_decode($apgraph_uri));
		echo $curdate;
		
	}

	function actionplanAction()
	{
		$params = $this->_getAllParams();

		if(empty($params['year'])) $params['year'] = date("Y");
		$this->view->year = $year = $params['year'];

		Zend_Loader::LoadClass('actionplanClass', $this->modelDir);
		$actionplanClass = new actionplanClass();

		$this->view->doneSec = $actionplanClass->getTotalDone($this->site_id, 1, $year);
		$this->view->outstandingSec = $actionplanClass->getTotalOutstanding($this->site_id, 1, $year);
		$this->view->rescheduleSec = $actionplanClass->getTotalReschedule($this->site_id, 1, $year);
		$this->view->upcomingSec = $actionplanClass->getTotalUpcoming($this->site_id, 1, $year);

		$outstandingSec = $actionplanClass->getActionPlanCurrentYearReviewSchedule2($this->site_id, 1, date("Y-m-d"));
		foreach($outstandingSec as &$osec) {
			$date = explode(" ", $osec['schedule_date']);
			$osec['formatted_schedule_date'] = date("j M Y", strtotime($date[0]));
		}
		$this->view->outstandingListSec = $outstandingSec;

		$this->view->doneSaf = $actionplanClass->getTotalDone($this->site_id, 3, $year);
		$this->view->outstandingSaf = $actionplanClass->getTotalOutstanding($this->site_id, 3, $year);
		$this->view->rescheduleSaf = $actionplanClass->getTotalReschedule($this->site_id, 3, $year);
		$this->view->upcomingSaf = $actionplanClass->getTotalUpcoming($this->site_id, 3, $year);

		$outstandingSaf = $actionplanClass->getActionPlanCurrentYearReviewSchedule2($this->site_id, 3, date("Y-m-d"));
		foreach($outstandingSaf as &$osaf) {
			$date = explode(" ", $osaf['schedule_date']);
			$osaf['formatted_schedule_date'] = date("j M Y", strtotime($date[0]));
		}
		$this->view->outstandingListSaf = $outstandingSaf;

		$this->view->donePark = $actionplanClass->getTotalDone($this->site_id, 5, $year);
		$this->view->outstandingPark = $actionplanClass->getTotalOutstanding($this->site_id, 5, $year);
		$this->view->reschedulePark = $actionplanClass->getTotalReschedule($this->site_id, 5, $year);
		$this->view->upcomingPark = $actionplanClass->getTotalUpcoming($this->site_id, 5, $year);

		$outstandingPark = $actionplanClass->getActionPlanCurrentYearReviewSchedule2($this->site_id, 5, date("Y-m-d"));
		foreach($outstandingPark as &$opark) {
			$date = explode(" ", $opark['schedule_date']);
			$opark['formatted_schedule_date'] = date("j M Y", strtotime($date[0]));
		}
		$this->view->outstandingListPark = $outstandingPark;

		$this->view->doneHk = $actionplanClass->getTotalDone($this->site_id, 2, $year);
		$this->view->outstandingHk = $actionplanClass->getTotalOutstanding($this->site_id, 2, $year);
		$this->view->rescheduleHk = $actionplanClass->getTotalReschedule($this->site_id, 2, $year);
		$this->view->upcomingHk = $actionplanClass->getTotalUpcoming($this->site_id, 2, $year);

		$outstandingHk = $actionplanClass->getActionPlanCurrentYearReviewSchedule2($this->site_id, 2, date("Y-m-d"));
		foreach($outstandingHk as &$ohk) {
			$date = explode(" ", $ohk['schedule_date']);
			$ohk['formatted_schedule_date'] = date("j M Y", strtotime($date[0]));
		}
		$this->view->outstandingListHk = $outstandingHk;

		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "View Action Plan Statistic";
		$logData['data'] = json_encode($params);
		$logsTable->insertLogs($logData);

		$this->renderTemplate('view_actionplan_statistic.tpl');	
	}

	function saveapgraphAction()
	{
		$params = $this->_getAllParams();

		$curdate = date("YmdHis");

		$magickPath = "/usr/bin/convert";
		$ap_graph_sec = $this->config->paths->html."/stat/".'ap_graph_sec_'.$curdate.'.png';
		$ap_graph_saf = $this->config->paths->html."/stat/".'ap_graph_saf_'.$curdate.'.png';
		$ap_graph_park = $this->config->paths->html."/stat/".'ap_graph_park_'.$curdate.'.png';
		$ap_graph_hk = $this->config->paths->html."/stat/".'ap_graph_hk_'.$curdate.'.png';

		// remove "data:image/png;base64,"
		$ap_graph_sec_uri =  str_replace("data:image/png;base64","", $params['ap_graph_sec']);
		file_put_contents($ap_graph_sec, base64_decode($ap_graph_sec_uri));
		$ap_graph_saf_uri =  str_replace("data:image/png;base64","", $params['ap_graph_saf']);
		file_put_contents($ap_graph_saf, base64_decode($ap_graph_saf_uri));
		$ap_graph_park_uri =  str_replace("data:image/png;base64","", $params['ap_graph_park']);
		file_put_contents($ap_graph_park, base64_decode($ap_graph_park_uri));
		$ap_graph_hk_uri =  str_replace("data:image/png;base64","", $params['ap_graph_hk']);
		file_put_contents($ap_graph_hk, base64_decode($ap_graph_hk_uri));
		echo $curdate;
	}

	function exportapstatistictopdfAction() {
		$params = $this->_getAllParams();

		$curdate = $params['cd'];
		
		Zend_Loader::LoadClass('actionplanClass', $this->modelDir);
		$actionplanClass = new actionplanClass();

		$outstandingSec = $actionplanClass->getActionPlanCurrentYearReviewSchedule2($this->site_id, 1, date("Y-m-d"));

		$outstandingSaf = $actionplanClass->getActionPlanCurrentYearReviewSchedule2($this->site_id, 3, date("Y-m-d"));

		$outstandingPark = $actionplanClass->getActionPlanCurrentYearReviewSchedule2($this->site_id, 5, date("Y-m-d"));

		$outstandingHk = $actionplanClass->getActionPlanCurrentYearReviewSchedule2($this->site_id, 2, date("Y-m-d"));

		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "Export Action Plan Statistic to PDF";
		$logData['data'] = json_encode($params);
		$logsTable->insertLogs($logData);

		require('fpdf/fpdf.php');

		$pdf = new FPDF();
		$pdf->AddPage();
		$pdf->SetFont('Arial','B',15);
		$pdf->Cell(80);
		$pdf->Cell(20,10,$this->ident['initial'].' - Action Plan '.$params['y'].' Statistic',0,0,'C');
		$pdf->Ln(14);
		$pdf->SetFont('Arial','B',10);
		$pdf->SetTextColor(255);
		$pdf->SetFillColor(158,130,75);
		$pdf->Cell(0,7,'Security - Action Plan '.$params['y'],0,1,'L', true);
		$pdf->SetFillColor(158,130,75);
		$pdf->SetTextColor(255);
		$pdf->SetDrawColor(153);
		$pdf->SetLineWidth(.3);
		$pdf->SetFont('','B');
		$pdf->Ln(10);
		$pdf->Image($this->config->paths->html."/stat/".'ap_graph_sec_'.$curdate.'.png',10,$pdf->getY()-7, 90, 45);
		$pdf->Ln(45);
		
		if(!empty($outstandingSec))
		{		
			$pdf->SetFont('Arial','B',10);
			$pdf->SetTextColor(0);
			$pdf->Cell(0,7,'Outstanding List',0,1,'L', false);
			$pdf->SetFillColor(158,130,75);
			$pdf->SetTextColor(255);
			$pdf->SetDrawColor(153);
			$pdf->SetLineWidth(.3);
			$pdf->SetFont('','B', 8);
			
			// Header
			$pdf->Cell(10,5,'No',1,0,'C',true);
			$pdf->Cell(30,5,'Date',1,0,'C',true);
			$pdf->Cell(150,5,'Planning Action',1,0,'C',true);
			$pdf->Ln();
			
			// Color and font restoration
			$pdf->SetFillColor(238,238,238);
			$pdf->SetTextColor(0);
			$pdf->SetFont('');
			// Data
			$fill = false;
		
			$i = 1;
			foreach($outstandingSec as $osec)
			{
				$date = explode(" ", $osec['schedule_date']);
				$formatted_schedule_date = date("j M Y", strtotime($date[0]));
				$pdf->Cell(10,5,$i,'LR',0,'R',$fill);
				$pdf->Cell(30,5,$formatted_schedule_date,'LR',0,'L',$fill);
				$pdf->Cell(150,5,$osec['activity_name'],'LR',0,'L',$fill);
				$pdf->Ln();
				$fill = !$fill;
				$i++;
			}
			// Closing line
			$pdf->Cell(190,0,'','T');
		}
		$pdf->Ln(10);

		if($pdf->getY() > 200)
		{
			$pdf->AddPage();
			$pdf->setY(10);
		}

		$pdf->SetFont('Arial','B',10);
		$pdf->SetTextColor(255);
		$pdf->SetFillColor(158,130,75);
		$pdf->Cell(0,7,'Safety - Action Plan '.$params['y'],0,1,'L', true);
		$pdf->SetFillColor(158,130,75);
		$pdf->SetTextColor(255);
		$pdf->SetDrawColor(153);
		$pdf->SetLineWidth(.3);
		$pdf->SetFont('','B');
		$pdf->Ln(10);
		$pdf->Image($this->config->paths->html."/stat/".'ap_graph_saf_'.$curdate.'.png',10,$pdf->getY()-7, 90, 45);
		$pdf->Ln(45);	
		
		if(!empty($outstandingSaf))
		{
			$pdf->SetFont('Arial','B',10);
			$pdf->SetTextColor(0);
			$pdf->Cell(0,7,'Outstanding List',0,1,'L', false);
			$pdf->SetFillColor(158,130,75);
			$pdf->SetTextColor(255);
			$pdf->SetDrawColor(153);
			$pdf->SetLineWidth(.3);
			$pdf->SetFont('','B', 8);	
			
			// Header
			$pdf->Cell(10,5,'No',1,0,'C',true);
			$pdf->Cell(30,5,'Date',1,0,'C',true);
			$pdf->Cell(150,5,'Planning Action',1,0,'C',true);
			$pdf->Ln();
			
			// Color and font restoration
			$pdf->SetFillColor(238,238,238);
			$pdf->SetTextColor(0);
			$pdf->SetFont('');
			// Data
			$fill = false;
			
			$i = 1;
			foreach($outstandingSaf as $osaf)
			{
				$date = explode(" ", $osaf['schedule_date']);
				$formatted_schedule_date = date("j M Y", strtotime($date[0]));
				$pdf->Cell(10,5,$i,'LR',0,'R',$fill);
				$pdf->Cell(30,5,$formatted_schedule_date,'LR',0,'L',$fill);
				$pdf->Cell(150,5,$osaf['activity_name'],'LR',0,'L',$fill);
				$pdf->Ln();
				$fill = !$fill;
				$i++;
			}
			// Closing line
			$pdf->Cell(190,0,'','T');
		}
		$pdf->Ln(10);

		if($pdf->getY() > 200)
		{
			$pdf->AddPage();
			$pdf->setY(10);
		}

		$pdf->SetFont('Arial','B',10);
		$pdf->SetTextColor(255);
		$pdf->SetFillColor(158,130,75);
		$pdf->Cell(0,7,'Parking & traffic - Action Plan '.$params['y'],0,1,'L', true);
		$pdf->SetFillColor(158,130,75);
		$pdf->SetTextColor(255);
		$pdf->SetDrawColor(153);
		$pdf->SetLineWidth(.3);
		$pdf->SetFont('','B');
		$pdf->Ln(10);
		$pdf->Image($this->config->paths->html."/stat/".'ap_graph_park_'.$curdate.'.png',10,$pdf->getY()-7,90, 45);
		$pdf->Ln(45);
		
		// Data
		$fill = false;
		if(!empty($outstandingPark))
		{
			$pdf->SetFont('Arial','B',10);
			$pdf->SetTextColor(0);
			$pdf->Cell(0,7,'Outstanding List',0,1,'L', false);
			$pdf->SetFillColor(158,130,75);
			$pdf->SetTextColor(255);
			$pdf->SetDrawColor(153);
			$pdf->SetLineWidth(.3);
			$pdf->SetFont('','B', 8);		
			// Header
			$pdf->Cell(10,5,'No',1,0,'C',true);
			$pdf->Cell(30,5,'Date',1,0,'C',true);
			$pdf->Cell(150,5,'Planning Action',1,0,'C',true);
			$pdf->Ln();
			
			// Color and font restoration
			$pdf->SetFillColor(238,238,238);
			$pdf->SetTextColor(0);
			$pdf->SetFont('');
			$i = 1;
			foreach($outstandingPark as $opark)
			{
				$date = explode(" ", $opark['schedule_date']);
				$formatted_schedule_date = date("j M Y", strtotime($date[0]));
				$pdf->Cell(10,5,$i,'LR',0,'R',$fill);
				$pdf->Cell(30,5,$formatted_schedule_date,'LR',0,'L',$fill);
				$pdf->Cell(150,5,$opark['activity_name'],'LR',0,'L',$fill);
				$pdf->Ln();
				$fill = !$fill;
				$i++;
			}
			// Closing line
			$pdf->Cell(190,0,'','T');
		}

		$pdf->Ln(10);

		if($pdf->getY() > 200)
		{
			$pdf->AddPage();
			$pdf->setY(10);
		}

		$pdf->SetFont('Arial','B',10);
		$pdf->SetTextColor(255);
		$pdf->SetFillColor(158,130,75);
		$pdf->Cell(0,7,'Housekeeping - Action Plan '.$params['y'],0,1,'L', true);
		$pdf->SetFillColor(158,130,75);
		$pdf->SetTextColor(255);
		$pdf->SetDrawColor(153);
		$pdf->SetLineWidth(.3);
		$pdf->SetFont('','B');
		$pdf->Ln(10);
		$pdf->Image($this->config->paths->html."/stat/".'ap_graph_hk_'.$curdate.'.png',10,$pdf->getY()-7,90, 45);
		$pdf->Ln(45);
		

		// Data
		$fill = false;
		if(!empty($outstandingHk))
		{
			$pdf->SetFont('Arial','B',10);
			$pdf->SetTextColor(0);
			$pdf->Cell(0,7,'Outstanding List',0,1,'L', false);
			$pdf->SetFillColor(158,130,75);
			$pdf->SetTextColor(255);
			$pdf->SetDrawColor(153);
			$pdf->SetLineWidth(.3);
			$pdf->SetFont('','B', 8);		
			// Header
			$pdf->Cell(10,5,'No',1,0,'C',true);
			$pdf->Cell(30,5,'Date',1,0,'C',true);
			$pdf->Cell(150,5,'Planning Action',1,0,'C',true);
			$pdf->Ln();
			
			// Color and font restoration
			$pdf->SetFillColor(238,238,238);
			$pdf->SetTextColor(0);
			$pdf->SetFont('');
			$i = 1;
			foreach($outstandingHk as $ohk)
			{
				$date = explode(" ", $ohk['schedule_date']);
				$formatted_schedule_date = date("j M Y", strtotime($date[0]));
				$pdf->Cell(10,5,$i,'LR',0,'R',$fill);
				$pdf->Cell(30,5,$formatted_schedule_date,'LR',0,'L',$fill);
				$pdf->Cell(150,5,$ohk['activity_name'],'LR',0,'L',$fill);
				$pdf->Ln();
				$fill = !$fill;
				$i++;
			}
			// Closing line
			$pdf->Cell(190,0,'','T');
		}
		
		$pdf->Ln(45);

		$pdf->Output();
	}

}
?>
