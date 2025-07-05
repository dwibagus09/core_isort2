<?php
require_once('actionControllerBase.php');

class SafetyController extends actionControllerBase
{
	public function addAction() {
		$this->view->ident = $this->ident;
		$this->view->title = "Add Safety Report";
		
		$settingTable = $this->loadModel('setting');
		$this->view->setting = $settingTable->getOtherSetting();
		
		$trainingTable = $this->loadModel('training');
		$this->view->training_activity = $trainingTable->getSafetyTrainingActivity();
		
		$issueTypeTable = $this->loadModel('issuetype');
		//$this->view->issue_type = $issueTypeTable->getIssueType('3');
		$this->view->issue_type = $issueTypeTable->getIssueTypeByIds('1, 5, 6, 7, 8, 9, 10');

		$equipmentTable = $this->loadModel('equipment');
		$this->view->equipments_ab = $equipmentTable->getSafetyEquipments('ab');
		$this->view->equipments_c1 = $equipmentTable->getSafetyEquipments('c1');
		$this->view->equipments_c2 = $equipmentTable->getSafetyEquipments('c2');

		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "Add Safety Daily Report";
		$logData['data'] = "Opening the form page";
		$logsTable->insertLogs($logData);	
		
		$this->renderTemplate('form_daily_safety.tpl'); 
	}
	
	public function savereportAction() {
		$params = $this->_getAllParams();
		$params['user_id'] = $this->ident['user_id'];
		Zend_Loader::LoadClass('safetyClass', $this->modelDir);
		$safetyClass = new safetyClass();
		
		$report = $safetyClass->getSafetyReportByDate(date("Y-m-d"));
		if(empty($params['safety_report_id']) && !empty($report))
		{
			$this->view->title = "Add Safety Report";
			$this->view->message="Report is already exist";
			$this->view->safety = $params; 
			
			$settingTable = $this->loadModel('setting');
			$this->view->setting = $settingTable->getOtherSetting();
			
			$trainingTable = $this->loadModel('training');
			$this->view->training_activity = $trainingTable->getSafetyTrainingActivity();
			
			$issueTypeTable = $this->loadModel('issuetype');
			//$this->view->issue_type = $issueTypeTable->getIssueType('3');
			$this->view->issue_type = $issueTypeTable->getIssueTypeByIds('1, 5, 6, 7, 8, 9, 10');
			
			$equipmentTable = $this->loadModel('equipment');
			$this->view->equipments_ab = $equipmentTable->getSafetyEquipments('ab');
			$this->view->equipments_c1 = $equipmentTable->getSafetyEquipments('c1');
			$this->view->equipments_c2 = $equipmentTable->getSafetyEquipments('c2');
			
			$this->renderTemplate('form_daily_safety.tpl'); 
			exit();
		}
		
		$params['report_id'] = $safetyClass->saveReport($params);
		
		$equipmentTable = $this->loadModel('equipment');
		$equipmentTable->deleteEquipmentBySafetyReportId($params['report_id']);
		if(!empty($params['equipment_item_id']))
		{
			$i = 0;
			foreach($params['equipment_item_id'] as $equipment_item_id)
			{
				if($params['status_cut_in'][$i] != "" || $params['status_cut_off'][$i]  != "" || $params['shift2'][$i] != "" || $params['shift3'][$i]  != "") {
					$dt['safety_report_id'] = $params['report_id'];
					$dt['equipment_item_id'] = $equipment_item_id;
					$dt['status_pressure_cut_in'] = $params['status_cut_in'][$i];
					$dt['status_pressure_cut_off'] = $params['status_cut_off'][$i];
					$dt['shift2'] = $params['shift2'][$i];
					$dt['shift3'] = $params['shift3'][$i];
					$equipmentTable->addSafetyEquipment($dt);
				}
				$i++;
			}
		}
		
		$trainingTable = $this->loadModel('training');
		$trainingTable->deleteSafetyTrainingByReportId($params['report_id']);
		if(!empty($params['training_type']))
		{		
			$k = 0;
			foreach($params['training_type'] as $training_type)
			{
				$dt2=array();
				$dt2['safety_report_id'] = $params['report_id'];
				$dt2['training_type'] = $training_type;
				$dt2['safety_training_id'] = $params['training_id'][$k];
				$dt2['training_activity'] = $params['training_activity'][$k];
				$dt2['description_training'] = $params['description_training'][$k];
				$dt2['participant_training'] = $params['participant_training'][$k];
				$dt2['remark_training'] = $params['remark_training'][$k];
				$dt2['dokumen_training2'] = $params['dokumen_training2'][$k];
				$training_id = $trainingTable->addSafetyTraining($dt2);
				if(!empty($_FILES["dokumen_training"]['name'][$k]))
				{
					$ext = explode(".",$_FILES["dokumen_training"]['name'][$k]);
					$filename = $training_id.".".$ext[count($ext)-1];
					$datafolder = $this->config->paths->html."/safety_training/".date("Y")."/";

					if(!is_dir($datafolder)) mkdir($datafolder, 0777, true);

					if(move_uploaded_file($_FILES["dokumen_training"]["tmp_name"][$k], $datafolder.$filename))
					{
						$trainingTable->updateTrainingDocument($training_id,'document', $filename);
						if(in_array(strtolower($ext[count($ext)-1]), array("jpg", "jpeg", "png","bmp")))
						{
							$magickPath = "/usr/bin/convert";
							/*** resize image if size greater than 500 Kb ***/
							if(filesize($datafolder.$filename) > 500000) exec($magickPath . ' ' . $datafolder.$filename . ' -resize 800x800\> ' . $datafolder.$filename);
						}
					}
				}

				$k++;
			}			
		}

		$safetyClass->deleteSpecificReportBySafetyId($params['report_id']);
		if(!empty($params['issue_type']))
		{		
			$j = 0;
			foreach($params['issue_type'] as $issue_type)
			{
				$dt=array();
				$dt['safety_report_id'] = $params['report_id'];
				$dt['issue_type'] = $issue_type;
				$dt['time'] = $params['time-sr'][$j];
				$dt['detail'] = $params['description-sr'][$j];
				$dt['status'] = $params['status-sr'][$j];
				$dt['security_id'] = $params['security-id-sr'][$j];
				$dt['issue_id'] = $params['id-issue-sr'][$j];
				$safetyClass->addSpecificReport($dt);
				$j++;
			}			
		}
		
		/*if(!empty($params['attachment-description']))
		{		
			$m = 0;
			$dt3=array();
			foreach($params['attachment-description'] as $description)
			{
				if($params['attachment_id'][$m] > 0)
				{
					$existingAttachment = array();
					$dt3[$m] = $safetyClass->getAttachmentById($params['attachment_id'][$m]);
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
			$safetyClass->deleteAttachmentByReportId($params['report_id']);
			$m=0;
			foreach($dt3 as $dt)
			{
				$attachment = $dt['attachment'];
				unset($dt['attachment']);
				$attachment_id = $safetyClass->addAttachment($dt);
				if($attachment_id > 0)
				{
					if(!empty($attachment['name'][$m]))
					{
						$ext = explode(".",$attachment['name'][$m]);
						$filename = $attachment_id.".".$ext[count($ext)-1];
						if(move_uploaded_file($attachment["tmp_name"][$m], $this->config->paths->html."/attachment/safety/".$filename))
							$safetyClass->updateAttachment($attachment_id,'filename', $filename);
					}
				}
				$m++;
			}			
		}*/

		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "Save Safety Daily Report - Page 1";
		$logData['data'] = json_encode($params);
		$logsTable->insertLogs($logData);
		
		$this->_response->setRedirect($this->baseUrl.'/default/safety/page2/id/'.$params['report_id']);
		$this->_response->sendResponse();
		exit();
	}
	
	public function page2Action() {
		$params = $this->_getAllParams();
		
		Zend_Loader::LoadClass('safetyClass', $this->modelDir);
		$safetyClass = new safetyClass();
		
		$safety = $safetyClass->getReportById($params['id']);
		$datetime = explode(" ",$safety['created_date']);

		$date = explode("-",$datetime[0]);
		$r_date = mktime(0, 0, 0, intval($date[1]), intval($date[2]), intval($date[0]));
		$safety['report_date'] = date("l, j F Y", $r_date);	
		
		$this->view->safety = $safety;
		
		$settingTable = $this->loadModel('setting');
		$this->view->setting = $settingTable->getOtherSetting();
		
		$this->view->attachment = $safetyClass->getAttachments($params['id']);

		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "Open Safety Daily Report - Page 2";
		$logData['data'] = json_encode($params);
		$logsTable->insertLogs($logData);

		$this->renderTemplate('form_daily_safety2.tpl'); 
	}
	
	
	public function viewreportAction() {
		$params = $this->_getAllParams();
		if(empty($params['start'])) $params['start'] = '0';
		$params['pagesize'] = 10;
		$this->view->start = $params['start'];
		Zend_Loader::LoadClass('safetyClass', $this->modelDir);
		$safetyClass = new safetyClass();
		$commentsTable = $this->loadModel('safetycomments');
		
		$safety = $safetyClass->getReports($params);
		foreach($safety as &$s)
		{
			$report_date = explode(" ",$s['created_date']);
			if($s['created_date'] >= date("Y-m-d", mktime(0, 0, 0, date("m"), date("d")-1,   date("Y")))) $s['allowEdit'] = 1;
			else $s['allowEdit'] = 0;
			$s['day_date'] = date("l, j F Y", strtotime($report_date[0]));
			$s['comments'] = $commentsTable->getCommentsBySafetyReportId($s['report_id'], '3', $this->site_id);
		}
		$this->view->safety = $safety;
		
		$totalReport = $safetyClass->getTotalReport();
		if($totalReport['total'] > 10)
		{
			if($params['start'] >= 10)
			{
				$this->view->firstPageUrl = "/default/safety/viewreport";
				$this->view->prevUrl = "/default/safety/viewreport/start/".($params['start']-$params['pagesize']);
			}
			if($params['start'] < (floor(($totalReport['total']-1)/10)*10))
			{
				$this->view->nextUrl = "/default/safety/viewreport/start/".($params['start']+$params['pagesize']);
				$this->view->lastPageUrl = "/default/safety/viewreport/start/".(floor(($totalReport['total']-1)/10)*10);
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
		$logData['action'] = "View Safety Daily Report List";
		$logData['data'] = json_encode($params);
		$logsTable->insertLogs($logData);
		
    	$this->renderTemplate('view_daily_safety.tpl');  
	}
	
	public function editAction() {
		$this->view->ident = $this->ident;
		$params = $this->_getAllParams();
		Zend_Loader::LoadClass('safetyClass', $this->modelDir);
		$safetyClass = new safetyClass();
		$safety = $safetyClass->getReportById($params['id']);
		$datetime = explode(" ",$safety['created_date']);
		
		/*if($datetime[0] != date("Y-m-d")) 
		{
			$this->_response->setRedirect($this->baseUrl.'/default/safety/viewreport');
			$this->_response->sendResponse();
			exit();
		}*/
		
		$date = explode("-",$datetime[0]);
		$r_date = mktime(0, 0, 0, intval($date[1]), intval($date[2]), intval($date[0]));
		$safety['report_date'] = date("l, j F Y", $r_date);
		$safety['yesterday_date'] = date("d", mktime(0, 0, 0, intval($date[1]), intval($date[2])-1, intval($date[0])));
		$safety['today_date'] = date("d", mktime(0, 0, 0, intval($date[1]), intval($date[2]), intval($date[0])));
		$this->view->safety = $safety;
		$this->view->y = date("Y", $r_date);
		
		$settingTable = $this->loadModel('setting');
		$this->view->setting = $settingTable->getOtherSetting();
		
		$equipmentTable = $this->loadModel('equipment');
		$this->view->equipments_ab = $equipmentTable->getSafetyEquipments('ab', $safety['report_id']);
		$this->view->equipments_c1 = $equipmentTable->getSafetyEquipments('c1', $safety['report_id']);
		$this->view->equipments_c2 = $equipmentTable->getSafetyEquipments('c2', $safety['report_id']);	
		
		$trainingTable = $this->loadModel('training');
		$this->view->training_activity = $trainingTable->getSafetyTrainingActivity();
		
		if(!empty($safety['report_id']))
		{
			$this->view->outdoorTraining = $trainingTable->getSafetyTrainingByType($safety['report_id'],'1');
			$this->view->inHouseTraining = $trainingTable->getSafetyTrainingByType($safety['report_id'],'2');
		}
		
		$issueTypeTable = $this->loadModel('issuetype');
		//$this->view->issue_type = $issueTypeTable->getIssueType('3');
		$this->view->issue_type = $issueTypeTable->getIssueTypeByIds('1, 5, 6, 7, 8, 9, 10');

		$specific_report = $safetyClass->getSpecificReportById($params['id']);
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
		
		//$this->view->attachment = $safetyClass->getAttachments($params['id']);

		$this->view->editMode = 1;

		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "Edit Safety Daily Report";
		$logData['data'] = json_encode($params);
		$logsTable->insertLogs($logData);
		
		$this->view->title = "Edit Safety Report";
		$this->renderTemplate('form_daily_safety.tpl');  
	}
	
	public function deleteAction() {
		$params = $this->_getAllParams();
		Zend_Loader::LoadClass('safetyClass', $this->modelDir);
		$safetyClass = new safetyClass();
		
		$safetyClass->deleteReportById($params['id']);
		
		Zend_Loader::LoadClass('trainingClass', $this->modelDir);
		$trainingClass = new trainingClass();
		$trainingClass->deleteSafetyTrainingByReportId($params['id']);
		
		Zend_Loader::LoadClass('safetycommentsClass', $this->modelDir);
		$safetycommentsClass = new safetycommentsClass();
		$safetycommentsClass->deleteCommentsBySafetyReportId($params['id']);

		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "Delete Safety Daily Report";
		$logData['data'] = json_encode($params);
		$logsTable->insertLogs($logData);
		
		self::viewreportAction();
	}

	public function viewdetailreportAction() {
		if($this->showSafety == 1)
		{
			$params = $this->_getAllParams();

			if(!empty($params['id']))
			{
				if($this->showSiteSelection == 1 && !empty($params['s']))
				{
					$siteTable = $this->loadModel('site');
					if($params['s'] != $this->ident['site_id'])
					{
						$siteTable->setSite($params['s']);
						$this->ident['site_id'] = $params['s'];
						$this->_response->setRedirect($this->baseUrl."/default/safety/viewdetailreport/id/".$params['id']);
						$this->_response->sendResponse();
						exit();
					}
				}

				Zend_Loader::LoadClass('safetyClass', $this->modelDir);
				$safetyClass = new safetyClass();
				
				$params['user_id'] = $this->ident['user_id'];
				$safetyClass->addReadSafetyReportLog($params);	

				$safety = $safetyClass->getReportById($params['id']);
				$datetime = explode(" ",$safety['created_date']);

				$filename = $this->config->paths->html.'/pdf_report/safety/' . $this->site_id."_safety_".$params['id'].".pdf";		
				$date = explode("-",$datetime[0]);
				$r_date = mktime(0, 0, 0, intval($date[1]), intval($date[2]), intval($date[0]));
				$safety['report_date'] = date("l, j F Y", $r_date);	
				$safety['yesterday_date'] = date("d", mktime(0, 0, 0, intval($date[1]), intval($date[2])-1, intval($date[0])));
				$safety['today_date'] = date("d", mktime(0, 0, 0, intval($date[1]), intval($date[2]), intval($date[0])));
			
				$this->view->safety = $safety;
				$settingTable = $this->loadModel('setting');
				$setting = $settingTable->getOtherSetting();
				$this->view->setting = $setting;

				$equipmentTable = $this->loadModel('equipment');
				$this->view->equipments_ab = $equipmentTable->getSafetyEquipments('ab', $params['id']);
				$this->view->equipments_c1 = $equipmentTable->getSafetyEquipments('c1', $params['id']);
				$this->view->equipments_c2 = $equipmentTable->getSafetyEquipments('c2', $params['id']);	
				
				$trainingTable = $this->loadModel('training');
				$this->view->training_activity = $trainingTable->getSafetyTrainingActivity();
				
				if(!empty($params['id']))
				{
					$this->view->outsourceTraining = $trainingTable->getSafetyTrainingByType($params['id'],'1');
					$this->view->inHouseTraining = $trainingTable->getSafetyTrainingByType($params['id'],'2');
				}
				
				/*** SPECIFIC REPORT ***/
				
				$specific_report = $safetyClass->getSpecificReportById($params['id']);
				foreach($specific_report as &$sr)
				{
					if(!empty($sr['issue_id']))
					{
						$sr['detail'] = $sr['description'];
						$datetime = explode(" ",$sr['issue_date']);
						$sr['time'] = $datetime[1];
					}
				}
				$specific_reports = $specific_report;
				foreach($specific_reports as &$sr)
				{
					if(!empty($sr['issue_id']))
					{
						$sr['detail'] = $sr['description'];
						$datetime = explode(" ",$sr['issue_date']);
						$sr['time'] = $datetime[1];
					}
				}
				$this->view->specific_reports = $specific_reports;
				$this->view->attachment = $safetyClass->getAttachments($params['id']);

				$this->view->ident = $this->ident;

				Zend_Loader::LoadClass('safetycommentsClass', $this->modelDir);
				$safetycommentsClass = new safetycommentsClass();
				$this->view->comments = $safetycommentsClass->getCommentsBySafetyReportId($params['id'], 0, $this->site_id, 'asc');


				$logsTable = $this->loadModel('logs');
				$logData['user_id'] = intval($this->ident['user_id']);
				$logData['action'] = "View Detail Safety Daily Report";
				$logData['data'] = json_encode($params);
				$logsTable->insertLogs($logData);

				$this->renderTemplate('view_safety_detail_report.tpl');   
			}
		}
		else {
			$this->_response->setRedirect($this->baseUrl);
			$this->_response->sendResponse();
			exit();
		}
	}

	public function downloadsafetyreportAction() {		
		$params = $this->_getAllParams();
		if(!empty($params['id']))
		{			
			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "Export Safety Daily Report to PDF";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);

			$this->exportsafetytopdf($params['id'], "", 1);
		}		
	}
	
	public function exporttopdf2Action() {
		$params = $this->_getAllParams();
		Zend_Loader::LoadClass('safetyClass', $this->modelDir);
		$safetyClass = new safetyClass();
		
		if(!empty($params['id'])) 
		{
			$safety = $safetyClass->getReportById($params['id']);
			$datetime = explode(" ",$safety['created_date']);
			$date = explode("-",$datetime[0]);
			$r_date = mktime(0, 0, 0, intval($date[1]), intval($date[2]), intval($date[0]));
			$safety['report_date'] = date("l, j F Y", $r_date);	
			$safety['yesterday_date'] = date("d", mktime(0, 0, 0, intval($date[1]), intval($date[2])-1, intval($date[0])));
			$safety['today_date'] = date("d", mktime(0, 0, 0, intval($date[1]), intval($date[2]), intval($date[0])));
		
			$settingTable = $this->loadModel('setting');
			$setting = $settingTable->getOtherSetting();
			
			$equipmentTable = $this->loadModel('equipment');
			$equipments_ab = $equipmentTable->getSafetyEquipments('ab', $params['id']);
			$equipments_c1 = $equipmentTable->getSafetyEquipments('c1', $params['id']);
			$equipments_c2 = $equipmentTable->getSafetyEquipments('c2', $params['id']);	
			
			$trainingTable = $this->loadModel('training');
			$training_activity = $trainingTable->getSafetyTrainingActivity();
			
			if(!empty($params['id']))
			{
				$outdoorTraining = $trainingTable->getSafetyTrainingByType($params['id'],'1');
				$inHouseTraining = $trainingTable->getSafetyTrainingByType($params['id'],'2');
				if(count($outdoorTraining) > count($inHouseTraining)) $totalTraining = count($outdoorTraining);
				else $totalTraining = count($inHouseTraining);
			}
			
			/*** SPECIFIC REPORT ***/
			
			$specific_report = $safetyClass->getSpecificReportById($params['id']);
			foreach($specific_report as &$sr)
			{
				if(!empty($sr['issue_id']))
				{
					$sr['detail'] = $sr['description'];
					$datetime = explode(" ",$sr['issue_date']);
					$sr['time'] = $datetime[1];
				}
			}
			$specific_reports = $specific_report;
			foreach($specific_reports as &$sr)
			{
				if(!empty($sr['issue_id']))
				{
					$sr['detail'] = $sr['description'];
					$datetime = explode(" ",$sr['issue_date']);
					$sr['time'] = $datetime[1];
				}
			}
			
			$attachment = $safetyClass->getAttachments($params['id']);
			
			/*** END OF SPECIFIC REPORT ***/
			
			require('PHPpdf/html2fpdf.php');


			$html= '<html>
			<head>
			<title>Daily Safety Report</title>
			 
			</head>
			<body>
			<h2>Daily Safety Report</h2>
			'.$safety['site_fullname'].'
			
			<h3>DAY / DATE</h3>
			<table width="100%" cellspacing="6" cellpadding="5" align="center" border="1" valign="top">
				<tr><td><strong>Day / Date</strong></td><td colspan="3">'.$safety['report_date'].'</td></tr>
				<tr><td><strong>Reporting Date</strong></td><td colspan="2" align="center">'.$safety['yesterday_date'].'</td><td align="center">'.$safety['today_date'].'</td></tr>
				<tr><td><strong>Reporting Time</strong></td><td align="center">'.$setting['safety_afternoon_reporting_time'].'</td><td align="center">'.$setting['safety_night_reporting_time'].'</td><td align="center">'.$setting['safety_morning_reporting_time'].'</td></tr>
			</table>
			
			<h3>MAN POWER</h3>
			<table width="100%" cellspacing="6" cellpadding="5" align="center" border="1" valign="top">
				<tr bgcolor="#afd9af"><th><strong>'.$setting['safety_afternoon_reporting_time'].'</strong></th><th><strong>'.$setting['safety_night_reporting_time'].'</strong></th><th><strong>'.$setting['safety_morning_reporting_time'].'</strong></th></tr>
				<tr>
					<td>'.$safety['man_power_afternoon'].'</td>
					<td>'.$safety['man_power_night'].'</td>
					<td>'.$safety['man_power_morning'].'</td>
				</tr>
			</table>
			
			<h3>PERLENGKAPAN</h3>
			<table width="100%" cellspacing="6" cellpadding="5" align="center" border="1" valign="top">
				<tr bgcolor="#afd9af">
				  <th>No</th>
				  <th>Equipment Name</th>
				  <th>Item</th>
				  <th>Status Normal</th>
				  <th>Shift 3<br>23:00</th>
				  <th>Shift 1<br>07:00</th>
				</tr>';
				  
				if(!empty($equipments_ab)) {
					$i = 0;
					foreach($equipments_ab as $equipmentab) {
						$html .= '<tr>
						<td>'.$equipmentab['no'].'</td>
						<td>'.$equipmentab['equipment_name'].'</td>
						<td>'.$equipmentab['item_name'].'</td>
						<td>'.$equipmentab['status'].'</td>
						<td>'.$equipmentab['shift2'].'</td>
						<td>'.$equipmentab['shift3'].'</td>
					</tr>';
					$i++; } 
					}
				  $html .= '</table>	

			<table width="100%" cellspacing="6" cellpadding="5" align="center" border="1" valign="top">
				<tr bgcolor="#afd9af">
				  <th rowspan="2">No</th>
				  <th rowspan="2">Equipment Name</th>
				  <th rowspan="2">Item</th>
				  <th colspan="2">Status Pressure<br>(bar or PSI or Kgf / cm2)</th>
				  <th colspan="2">Actual Pressure<br>(bar or PSI or Kgf / cm2)</th>
				</tr>
				<tr bgcolor="#afd9af">
					<th>Cut In</th>
					<th>Cut Off</th>
					<th>Shift 3<br>23:00</th>
				  <th>Shift 1<br>07:00</th>
				</tr>
				';
				  
				if(!empty($equipments_c1)) {
					$i = 0;
					foreach($equipments_c1 as $equipmentc1) {
						if(empty($equipmentc1['status_pressure_cut_in'])) $equipmentc1['status_pressure_cut_in'] = $equipmentc1['status_cut_in'];
						if(empty($equipmentc1['status_pressure_cut_off'])) $equipmentc1['status_pressure_cut_off'] = $equipmentc1['status_cut_off'];
						$html .= '<tr>
						<td>'.$equipmentc1['no'].'</td>
						<td>'.$equipmentc1['equipment_name'].'</td>
						<td>'.$equipmentc1['item_name'].'</td>
						<td>'.$equipmentc1['status_pressure_cut_in'].'</td>
						<td>'.$equipmentc1['status_pressure_cut_off'].'</td>
						<td>'.$equipmentc1['shift2'].'</td>
						<td>'.$equipmentc1['shift3'].'</td>
					</tr>';
					$i++; } 
					}
				  $html .= '</table>

			<table width="100%" cellspacing="6" cellpadding="5" align="center" border="1" valign="top">
				<tr bgcolor="#afd9af">
				  <th>No</th>
				  <th>Tank Condition</th>
				  <th>Status Normal</th>
				  <th>Shift 3<br>23:00</th>
				  <th>Shift 1<br>07:00</th>
				</tr>';
				  
				if(!empty($equipments_c2)) {
					$i = 0;
					foreach($equipments_c2 as $equipmentc2) {
						$html .= '<tr>
						<td>'.$equipmentc2['no'].'</td>
						<td>'.$equipmentc2['item_name'].'</td>
						<td>'.$equipmentc2['status'].'</td>
						<td>'.$equipmentc2['shift2'].'</td>
						<td>'.$equipmentc2['shift3'].'</td>
					</tr>';
					$i++; } 
					}
				  $html .= '</table>
				  
			<h3>BRIEFING</h3>';
			if(!empty($safety['briefing1']))
				$html .= '<div>'.str_replace("<br />", "<br>",nl2br($safety['briefing1'])).'</div><br>
			<hr>';
			if(!empty($safety['briefing2']))
				$html .= '<div>'.str_replace("<br />", "<br>",nl2br($safety['briefing2'])).'</div><br>
			<hr>';
			if(!empty($safety['briefing3']))
				$html .= '<div>'.str_replace("<br />", "<br>",nl2br($safety['briefing3'])).'</div><br>
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
	'.$safety['sop1'].'<br><hr>
	'.$safety['sop2'].'<br><hr>
	'.$safety['sop3'].'<br><hr>';
				
			if(!empty($specific_reports)) { 
			$html .= '<h3>SPECIFIC REPORT</h3>
			<table width="100%" cellspacing="6" cellpadding="5" align="center" border="1" valign="top">';
				foreach($specific_reports as $specific_report) { 
					if($specific_report['issue_type_id'] < 4)
					{
						$specific_report['detail'] = $specific_report['description'];
					}
					//$issueDate = explode(" ",$specific_report['issue_date']);
					//$specific_report['time'] = $issueDate[1];
					$html .= '<tr>
						<td>'.strtoupper($specific_report['issue_type_name']).'<br>Detail : '.$specific_report['detail'].'</td>
						<td><br>Status :<br>'.$specific_report['status'].'</td>
					</tr>';
				 }
			$html .= '</table>';
			}
			
			$html .= '<h4>Attachment</h4>			
			<table width="100%" cellspacing="6" cellpadding="5" align="center" border="1" valign="top">
			<tr bgcolor="#afd9af">
				<th><strong>Description</strong></th>
			</tr>';
			if(!empty($attachment)) {
				foreach($attachment as $att) {
					$html .= '<tr>
						<td><a target="_blank" href="'.$this->baseUrl.'/default/attachment/openattachment/c/3/f/'.$att['filename'].'">'.$att['description'].'</a></td>
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
	}

	public function exporttopdfAction() {		
		$params = $this->_getAllParams();
		if(!empty($params['id']))
		{
			Zend_Loader::LoadClass('safetyClass', $this->modelDir);
			$safetyClass = new safetyClass();
			$safety = $safetyClass->getReportById($params['id']);

			$params['user_id'] = $this->ident['user_id'];
			$safetyClass->addReadSafetyReportLog($params);

			$filename = $this->config->paths->html.'/pdf_report/safety/' . $this->site_id."_safety_".$params['id'].".pdf";
			if (!file_exists($filename) || date("Y-m-d", mktime(0, 0, 0, date("m"), date("d")-1, date("Y"))) < $safety['created_date']) {		
				$this->exportsafetytopdf($params['id']);
			}
			// Header content type 
			header('Content-type: application/pdf'); 				
			header('Content-Disposition: inline; filename="' . $filename . '"'); 				
			// Read the file 
			readfile($filename); 
			exit();
		}
	}
	
	
	function getsafetycommentsbyreportidAction()
	{
		$params = $this->_getAllParams();
		$this->view->ident = $this->ident;
		$category = $this->loadModel('category');
		$safetyCommentsTable = $this->loadModel('safetycomments');
		$comments = $safetyCommentsTable->getCommentsBySafetyReportId($params['id'], 0, $this->site_id);
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
		$commentsTable = $this->loadModel('safetycomments');
		$params['user_id'] = $this->ident['user_id'];
		$params['site_id'] = $this->site_id;
		if($_FILES["attachment"]["size"] > 0)
		{
			$ext = explode(".",$_FILES["attachment"]['name']);
			$filename = "saf_".date("YmdHis").".".$ext[count($ext)-1];
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
		Zend_Loader::LoadClass('safetyClass', $this->modelDir);
		$safetyClass = new safetyClass();
		$safety = $safetyClass->getReportById($params['report_id']);
		
		$datetime = explode(" ",$safety['created_date']);
		$date = explode("-",$datetime[0]);
		$r_date = mktime(0, 0, 0, intval($date[1]), intval($date[2]), intval($date[0]));
		$report_date = date("l, j F Y", $r_date);
		
		$categoryClass = $this->loadModel('category');
		$category = $categoryClass->getCategoryById('3');	
					
		$botToken = '716005387:AAFhUdDGlXjK5YFMYqwx_lCpqpp8760S_TE';
		/*if($this->site_id < 4)	$botToken = '476346623:AAFlB9X5-bShAkdTK9ziNDrfZ0TCePXl2cY';
		else $botToken = '716005387:AAFhUdDGlXjK5YFMYqwx_lCpqpp8760S_TE';*/
		$website="https://api.telegram.org/bot".$botToken;
		//$chatId=$category['telegram_channel_id'];  //Receiver Chat Id 
		$chatId=$category['site_'.$this->site_id];  //Receiver Chat Id 

		if(!empty($params['filename'])) $attachmenttext = '
attachment : '.$this->config->general->url."comments/".$params['filename'];

		$txt = '[NEW COMMENT] 
'.$this->ident['name']." : ".$params['comment'].$attachmenttext.'

[SAFETY REPORT]
Report Date : '.$report_date.'
Report Link : '.$this->config->general->url."default/safety/viewdetailreport/s/".$this->site_id."/id/".$params['report_id'];
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
		$logData['action'] = "Add Comment to Safety Daily Report";
		$logData['data'] = json_encode($allParams);
		$logsTable->insertLogs($logData);

		echo $allParams['filename'];
	}	
	
	function getupdatedcommentsAction()
	{
		$params = $this->_getAllParams();
		$params['pagesize'] = 10;
		
		Zend_Loader::LoadClass('safetyClass', $this->modelDir);
		$safetyClass = new safetyClass();
		
		$data= array();
		$safetyReports = $safetyClass->getReports($params);	
		$commentsTable = $this->loadModel('safetycomments');
		$i=0;
		foreach($safetyReports as $s) {
			$data[$i]['report_id'] = $s['report_id'];
			$comments = $commentsTable->getCommentsBySafetyReportId($s['report_id'], '3', $this->site_id);
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
		
		Zend_Loader::LoadClass('safetyClass', $this->modelDir);
		$safetyClass = new safetyClass();
		
		$data= array();

		$commentCacheName = "safety_comments_".$this->site_id."_".$params["start"];
		//$data = $this->cache->load($commentCacheName);
		$i=0;
		if(empty($data))
		{
			$safetyReports = $safetyClass->getReports($params);	
			$commentsTable = $this->loadModel('safetycomments');
			foreach($safetyReports as $s) {
				$data[$i]['report_id'] = $s['report_id'];
				$comments = $commentsTable->getCommentsBySafetyReportId($s['report_id'], '3', $this->site_id);
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

	/*** ATTACHMENT ***/
	
	function addattachmentAction()
	{
		$params = $this->_getAllParams();
		
		Zend_Loader::LoadClass('safetyClass', $this->modelDir);
		$safetyClass = new safetyClass();
		
		$attachment_id = $safetyClass->addAttachment($params);
		
		if(!empty($_FILES["attachment_file"]))
		{
			$ext = explode(".",$_FILES["attachment_file"]['name']);
			$filename = $attachment_id.".".$ext[count($ext)-1];
			$datafolder = $this->config->paths->html."/attachment/safety/";
			if(move_uploaded_file($_FILES["attachment_file"]["tmp_name"], $datafolder.$filename))
			{
				$safetyClass->updateAttachment($attachment_id,'filename', $filename);
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
		$logData['action'] = "Add Attachment to Safety Daily Report";
		$logData['data'] = json_encode($params);
		$logsTable->insertLogs($logData);
		
		$this->_response->setRedirect($this->baseUrl.'/default/safety/page2/id/'.$params['report_id']);
		$this->_response->sendResponse();
		exit();
	
	}
	
	public function getattachmentbyidAction() {
		$params = $this->_getAllParams();
		Zend_Loader::LoadClass('safetyClass', $this->modelDir);
		$safetyClass = new safetyClass();
		
		if(!empty($params['id'])) 
		{
			echo json_encode($safetyClass->getAttachmentById($params['id']));
		}
	}
	
	public function deleteattachmentbyidAction() {
		$params = $this->_getAllParams();
		Zend_Loader::LoadClass('safetyClass', $this->modelDir);
		$safetyClass = new safetyClass();
		
		$safetyClass->deleteAttachmentById($params['id']);

		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "Delete Attachment to Safety Daily Report";
		$logData['data'] = json_encode($params);
		$logsTable->insertLogs($logData);
		
		$this->_response->setRedirect($this->baseUrl.'/default/safety/page2/id/'.$params['report_id']);
		$this->_response->sendResponse();
		exit();
	}

	/*** MONTHLY ANALYSIS ***/

	public function addmonthlyanalysisAction() {
		$params = $this->_getAllParams();

		if(!empty($params['id'])) 
		{
			$this->view->monthly_analysis_id = $params['id'];		
			Zend_Loader::LoadClass('safetyClass', $this->modelDir);
			$safetyClass = new safetyClass();
			$monthly_analysis = $safetyClass->geMonthlyAnalysisById($params['id']);
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
			$modus = $modusClass->getModus('3');
			//$this->cache->save($modus, "modus_".$this->site_id."_1_".$ym, array("modus_".$this->site_id."_1_".$ym), 0);
		}

		//$totalModusPerMonth = $this->cache->load("total_modus_per_month_".$this->site_id."_1_".$ym);
		if(empty($totalModusPerMonth))
		{	
			$totalModusPerMonth =  array();
			for($b=1; $b<=$m; $b++) // get rekapitulasi utk bulan ini dan bulan2 sblmnya
			{
				$totalModus = $issueClass->getIssuesByModus($b, $y, '3');
				foreach($totalModus as $tm) {
					$totalModusPerMonth[$b][$tm['modus_id']] = $tm['total_modus'];
				}
			}
			//$this->cache->save($totalModusPerMonth, "total_modus_per_month_".$this->site_id."_1_".$ym, array("total_modus_per_month_".$this->site_id."_1_".$ym), 0);
		}
		else{
			$totalModus = $issueClass->getIssuesByModus($m, $y, '3');
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
				$analisa_hari = $issueClass->getMonthlyIssuesByDay($mo['kejadian_id'], $m, $y, '3');
				if(!empty($analisa_hari))
				{
					foreach($analisa_hari as $ah) {
						$rekap[$i]['analisa_hari'][$ah['day']]= $ah['total'];
					}
				}
				$rekap[$i]['analisa_jam'][0] = $issueClass->getMonthlyIssuesByTime($mo['kejadian_id'], $m, $y, '09:00:00', '12:00:00', '3');
				$rekap[$i]['analisa_jam'][1] = $issueClass->getMonthlyIssuesByTime($mo['kejadian_id'], $m, $y, '12:00:00', '16:00:00', '3');
				$rekap[$i]['analisa_jam'][2] = $issueClass->getMonthlyIssuesByTime($mo['kejadian_id'], $m, $y, '16:00:00', '19:00:00', '3');
				$rekap[$i]['analisa_jam'][3] = $issueClass->getMonthlyIssuesByTime($mo['kejadian_id'], $m, $y, '19:00:00', '23:00:00', '3');
				$rekap[$i]['analisa_jam'][4] = $issueClass->getMonthlyIssuesByTime($mo['kejadian_id'], $m, $y, '23:00:00', '09:00:00', '3');
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

		/*$urutan_total_issue_tenant = $issueClass->getTotalIssuesByTenantPublik($m, $y, '0', '1');
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
		$this->view->urutan_total_all_issue_publik = $urutan_total_all_issue_publik;*/

		$total_all_tangkapan = 0;
		$total_tangkapan_monthly = array();
		$list_tangkapan = $issueClass->getPelakuTertangkapByCategory($y, '3');
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

		$pelaku_tertangkap_detail = $issueClass->getPelakuTertangkapDetail($m, $y, '3');
		foreach($pelaku_tertangkap_detail as &$pelaku)
		{
			$tgl = explode(" ", $pelaku['issue_date']);
			$pelaku['date'] = date("j M Y", strtotime($tgl[0]))." ".$tgl[1];
		}
		$this->view->pelaku_tertangkap_detail = $pelaku_tertangkap_detail;

		$listIssues = $issueClass->getMonthlyAnalysisIssues($m, $y, '3');
		foreach($listIssues as &$issue)
		{
			$tgl = explode(" ", $issue['issue_date']);
			$issue['date'] = date("j M Y", strtotime($tgl[0]))." ".$tgl[1];
		}
		$this->view->listIssues = $listIssues;

		Zend_Loader::LoadClass('incidentClass', $this->modelDir);
		$incidentClass = new incidentClass();	
		$this->view->listKejadian = $incidentClass->getIncidentByCategoryId('3');


		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "Add Security Monthly Analysis";
		$logData['data'] = json_encode($params);
		$logsTable->insertLogs($logData);	

		$this->renderTemplate('form_safety_monthly_analysis3.tpl'); 
	}

	public function savemonthlyanalysisAction() {
		$params = $this->_getAllParams();

		$params['user_id'] = $this->ident['user_id'];
		if(empty($params['monthly_analysis_id']))
		{
			Zend_Loader::LoadClass('safetyClass', $this->modelDir);
			$safetyClass = new safetyClass();
			$params['monthly_analysis_id'] = $safetyClass->saveMonthlyAnalysis($params);
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
			$monthlyanalysissummaryClass->saveMonthlyAnalysis($data, '3');
			$i++;
		}

		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "Save Security Monthly Analysis";
		$logData['data'] = json_encode($params);
		$logsTable->insertLogs($logData);	

		$this->_response->setRedirect($this->baseUrl.'/default/safety/viewmonthlyanalysis');
		$this->_response->sendResponse();
		exit();
	}
	
	/*public function addmonthlyanalysisAction() {
		$params = $this->_getAllParams();
		
		Zend_Loader::LoadClass('equipmentClass', $this->modelDir);
		$equipmentClass = new equipmentClass();

		Zend_Loader::LoadClass('safetyClass', $this->modelDir);
		$safetyClass = new safetyClass();

		if(!empty($params['id'])) 
		{
			$this->view->monthly_analysis_id = $params['id'];			
			$this->view->false_alarm = $equipmentClass->getFalseAlarm($params['id']);
			$monthlyAnalysis = $safetyClass->getMonthlyAnalysisById($params['id']);
			$savedate = explode(" ", $monthlyAnalysis['save_date']);
			$monthly_analysis_date = explode("-", $savedate[0]);
			$this->view->monthYear = date("F Y", mktime(0, 0, 0, $monthly_analysis_date[1]-1, $monthly_analysis_date[2], $monthly_analysis_date[0]));
			$this->view->buildingActiveProtection = $equipmentClass->getBuildingProtectionEquipment('Aktif', $params['id']);
			$this->view->buildingPassiveProtection = $equipmentClass->getBuildingProtectionEquipment('Pasif', $params['id']);
		}
		else {
			$this->view->monthYear = date("F Y", strtotime("-1 month"));
			$lastMonthlyAnalysis = $safetyClass->getLastMonthlyAnalysis(date("Y-m-d H:i:s"));
			$buildingActiveProtection = $equipmentClass->getBuildingProtectionEquipment('Aktif', $lastMonthlyAnalysis['monthly_analysis_id']);
			foreach($buildingActiveProtection as &$bap) {
				$bap['item_detail_id'] = "";
			}
			$this->view->buildingActiveProtection = $buildingActiveProtection;
			$buildingPassiveProtection = $equipmentClass->getBuildingProtectionEquipment('Pasif', $lastMonthlyAnalysis['monthly_analysis_id']);
			foreach($buildingPassiveProtection as &$bpp) {
				$bpp['item_detail_id'] = "";
			}
			$this->view->buildingPassiveProtection = $buildingPassiveProtection;
		}
		if(empty($params['ym'])) $params['ym'] = date("Y").date("m");
		$this->view->year = $y = substr($params['ym'],0,4);
		$this->view->month = $m = substr($params['ym'],4,2);		

		$this->view->ident = $this->ident;

		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "Add Safety Monthly Analysis";
		$logData['data'] = json_encode($params);
		$logsTable->insertLogs($logData);

		$this->renderTemplate('form_safety_monthly_analysis.tpl'); 
	}

	public function savemonthlyanalysisAction() {
		$params = $this->_getAllParams();
		//print_r($params);
		$this->view->monthly_analysis_id = $params['monthly_analysis_id'];
		$params['user_id'] = $this->ident['user_id'];
		if(empty($params['monthly_analysis_id']))
		{
			Zend_Loader::LoadClass('safetyClass', $this->modelDir);
			$safetyClass = new safetyClass();
			$params['monthly_analysis_id'] = $safetyClass->saveMonthlyAnalysis($params);
		}

		Zend_Loader::LoadClass('equipmentClass', $this->modelDir);
		$equipmentClass = new equipmentClass();
		
		$buildingProtection = $equipmentClass->getBuildingProtectionEquipment('');
		foreach($buildingProtection as $bp) {	
			$data["safety_monthly_analysis_id"] = $params['monthly_analysis_id'];			
			$data["equipment_item_id"] = $bp['equipment_item_id'];
			if($bp['type'] == "Aktif")
			{
				$data["item_detail_id"] = $params['building_active_protection_id'][$bp['equipment_item_id']];
				$data["description"] = addslashes(str_replace("\n","<br>",$params['building_active_protection_description'][$bp['equipment_item_id']]));
				$data["location"] = addslashes(str_replace("\n","<br>",$params['building_active_protection_location'][$bp['equipment_item_id']]));
				$data["total_item"] = $params['building_active_protection_total_item'][$bp['equipment_item_id']];
				$data["item_condition"] = addslashes(str_replace("\n","<br>",$params['building_active_protection_condition'][$bp['equipment_item_id']]));
			}
			else {
				$data["item_detail_id"] = $params['building_passive_protection_id'][$bp['equipment_item_id']];
				$data["description"] = addslashes(str_replace("\n","<br>",$params['building_passive_protection_description'][$bp['equipment_item_id']]));
				$data["location"] = addslashes(str_replace("\n","<br>",$params['building_passive_protection_location'][$bp['equipment_item_id']]));
				$data["total_item"] = $params['building_passive_protection_total_item'][$bp['equipment_item_id']];
				$data["item_condition"] = addslashes(str_replace("\n","<br>",$params['building_passive_protection_condition'][$bp['equipment_item_id']]));
			}
			$equipmentClass->saveBuildingProtectionDetail($data);
		}

		$equipmentClass->saveFalseAlarm($params);

		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "Save Safety Monthly Analysis - Page 1";
		$logData['data'] = json_encode($params);
		$logsTable->insertLogs($logData);

		$this->_response->setRedirect($this->baseUrl.'/default/safety/monthlyanalysis2/id/'.$params['monthly_analysis_id']);
		$this->_response->sendResponse();
		exit();	
	} */

	public function monthlyanalysis2Action() {
		$params = $this->_getAllParams();
		
		Zend_Loader::LoadClass('equipmentClass', $this->modelDir);
		$equipmentClass = new equipmentClass();

		Zend_Loader::LoadClass('safetyClass', $this->modelDir);
		$safetyClass = new safetyClass();

		$monthlyAnalysis = $safetyClass->getMonthlyAnalysisById($params['id']);
		$savedate = explode(" ", $monthlyAnalysis['save_date']);
		$monthly_analysis_date = explode("-", $savedate[0]);
		$this->view->monthYear = date("F Y", mktime(0, 0, 0, $monthly_analysis_date[1]-1, $monthly_analysis_date[2], $monthly_analysis_date[0]));

		if(!empty($params['id'])) 
		{
			$this->view->monthly_analysis_id = $params['id'];	
			//$this->view->fireProtectionTenantEquipment = $equipmentClass->getFireProtectionTenant($params['id']);
			Zend_Loader::LoadClass('issueClass', $this->modelDir);
			$issueClass = new issueClass();
			if($monthly_analysis_date[1] == "01") $curMonth = 12;
			else $curMonth = $monthly_analysis_date[1];
			//$this->view->fireProtectionTenantEquipment = $issueClass->getPotentialHazardFindingsTenant(($curMonth));	
			$totalFireAccidentEquipment = $equipmentClass->getFireAccidentEquipmentDetail($params['id']);
			if($totalFireAccidentEquipment > 0) 
			{
				$this->view->fire_accident_equipment_detail = $equipmentClass->getFireAccidentEquipment($params['id']);
			}
			else
			{
				$lastMonthlyAnalysis = $safetyClass->getLastMonthlyAnalysis($monthlyAnalysis['save_date']);	
				if($lastMonthlyAnalysis['monthly_analysis_id'] > 0)
				{
					$fire_accident_equipment_detail = $equipmentClass->getFireAccidentEquipment($lastMonthlyAnalysis['monthly_analysis_id']);
					
					foreach($fire_accident_equipment_detail as &$faed) {
						$faed['equipment_detail_id'] = "";
					}
				}
				$this->view->fire_accident_equipment_detail = $fire_accident_equipment_detail;
			}

			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "Add/Edit Safety Monthly Analysis - Page 2";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);
			
			$this->renderTemplate('form_safety_monthly_analysis2.tpl'); 	
		}	
		else
		{
			$this->_response->setRedirect($this->baseUrl.'/default');
			$this->_response->sendResponse();
			exit();	
		}
	}

	public function savemonthlyanalysis2Action() {
		$params = $this->_getAllParams();
		
		$this->view->monthly_analysis_id = $params['monthly_analysis_id'];
		
		Zend_Loader::LoadClass('equipmentClass', $this->modelDir);
		$equipmentClass = new equipmentClass();

		if(!empty($params['fireProtectionIssueid']))
		{
			$i = 0;
			//$equipmentClass->deleteFireProtectionTenant($params['monthly_analysis_id']);
			foreach($params['fireProtectionIssueid'] as $fireProtectionIssueid)
			{
				$data = array();
				$data['perlengkapan_tenant_id'] = $params['fireProtectionId'][$i];
				$data['safety_monthly_analysis_id'] = $params['monthly_analysis_id'];
				/*$data['tenant_name'] = $fireProtectionTenantName;
				$data['floor'] = $params['fireProtectionFloor'][$i];
				$data['proteksi_kebakaran'] = $params['fireProtectionProteksiKebakaran'][$i];*/
				$data['issue_id'] = $fireProtectionIssueid;
				$data['potensi_bahaya'] = $params['fireProtectionPotensiBahaya'][$i];
				$data['keterangan'] = $params['fireProtectionKeterangan'][$i];
				$equipmentClass->saveFireProtectionTenant($data);
				$i++;
			}
		}

		if(!empty($params['fire_accident_equipment_id']))
		{
			$i = 0;
			foreach($params['fire_accident_equipment_id'] as $equipment_id)
			{
				$data = array();
				$data['safety_monthly_analysis_id'] = $params['monthly_analysis_id'];
				$data['fire_accident_equipment_id'] = $equipment_id;
				$data['equipment_detail_id'] = $params['fire_accident_equipment_detail_id'][$i];
				$data['location'] = $params['fire_accident_equipment_lokasi'][$i];
				$data['total'] = $params['fire_accident_equipment_jumlah'][$i];
				$data['item_condition'] = $params['fire_accident_equipment_kondisi'][$i];
				$equipmentClass->saveFireAccidentEquipmentDetail($data);
				$i++;
			}
		}
		
		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "Save Safety Monthly Analysis - Page 2";
		$logData['data'] = json_encode($params);
		$logsTable->insertLogs($logData);

		$this->_response->setRedirect($this->baseUrl.'/default/safety/monthlyanalysis3/id/'.$params['monthly_analysis_id']);
		$this->_response->sendResponse();
		exit();	
	}

	public function monthlyanalysis3Action() {
		$params = $this->_getAllParams();
		
		if(!empty($params['id'])) 
		{
			$this->view->monthly_analysis_id = $params['id'];

			Zend_Loader::LoadClass('issueClass', $this->modelDir);
			$issueClass = new issueClass();

			Zend_Loader::LoadClass('safetyClass', $this->modelDir);
			$safetyClass = new safetyClass();

			$safetyMonthlyAnalysis = $safetyClass->getMonthlyAnalysisById($params['id']);
			
			$report_date = explode(" ", $safetyMonthlyAnalysis['save_date']);
			$monthly_analysis_date = explode("-", $report_date[0]);
			$ym = date("Ym", mktime(0, 0, 0, $monthly_analysis_date[1]-1, $monthly_analysis_date[2], $monthly_analysis_date[0]));
			$ymCur = date("Ym", mktime(0, 0, 0, $monthly_analysis_date[1], $monthly_analysis_date[2], $monthly_analysis_date[0]));
			$this->view->year = $y = substr($ym, 0, 4);
			$this->view->month = $m = substr($ym, 4, 2);

			$yCur = substr($ymCur, 0, 4);
			$mCur = substr($ymCur, 4, 2);

			$this->view->monthYear = date("F Y", mktime(0, 0, 0, $monthly_analysis_date[1]-1, $monthly_analysis_date[2], $monthly_analysis_date[0]));

			/*$unsafe_condition = $issueClass->getIssuesByMonthCatType($m, $y, 3, 11);
			foreach($unsafe_condition as &$uc)
			{
				$uc_date = explode(" ", $uc['issue_date']);
				$uc['date'] = date("D, j M Y", strtotime($uc_date[0]))." ".$uc_date[1];
			}
			$this->view->unsafe_condition = $unsafe_condition;*/

			if(empty($modus))
			{		
				Zend_Loader::LoadClass('modusClass', $this->modelDir);
				$modusClass = new modusClass();	
				$modus = $modusClass->getModus('3');
				//$this->cache->save($modus, "modus_".$this->site_id."_3_".$ym, array("modus_".$this->site_id."_3_".$ym), 0);
			}
			
			//$totalModusPerMonth = $this->cache->load("total_modus_per_month_".$this->site_id."_3_".$ym);
			if(empty($totalModusPerMonth))
			{	
				$totalModusPerMonth =  array();
				for($b=1; $b<=$m; $b++) // get rekapitulasi utk bulan ini dan bulan2 sblmnya
				{
					$totalModus = $issueClass->getIssuesByModus($b, $y, '3');
					foreach($totalModus as $tm) {
						$totalModusPerMonth[$b][$tm['modus_id']] = $tm['total_modus'];
					}
				}
				//$this->cache->save($totalModusPerMonth, "total_modus_per_month_".$this->site_id."_3_".$ym, array("total_modus_per_month_".$this->site_id."_3_".$ym), 0);
			}
			else{
				$totalModus = $issueClass->getIssuesByModus($m, $y, '3');
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
					$analisa_hari = $issueClass->getMonthlyIssuesByDay($mo['kejadian_id'], $m, $y, '3');
					if(!empty($analisa_hari))
					{
						foreach($analisa_hari as $ah) {
							$rekap[$i]['analisa_hari'][$ah['day']]= $ah['total'];
						}
					}
					$rekap[$i]['analisa_jam'][0] = $issueClass->getMonthlyIssuesByTime($mo['kejadian_id'], $m, $y, '09:00:00', '12:00:00', '3');
					$rekap[$i]['analisa_jam'][1] = $issueClass->getMonthlyIssuesByTime($mo['kejadian_id'], $m, $y, '12:00:00', '16:00:00', '3');
					$rekap[$i]['analisa_jam'][2] = $issueClass->getMonthlyIssuesByTime($mo['kejadian_id'], $m, $y, '16:00:00', '19:00:00', '3');
					$rekap[$i]['analisa_jam'][3] = $issueClass->getMonthlyIssuesByTime($mo['kejadian_id'], $m, $y, '19:00:00', '23:00:00', '3');
					$rekap[$i]['analisa_jam'][4] = $issueClass->getMonthlyIssuesByTime($mo['kejadian_id'], $m, $y, '23:00:00', '09:00:00', '3');
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
				$uraian_kejadian = $issueClass->getIssuesByModusId($mo['modus_id'], $m, $y, '3');
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
			$this->view->urutan_hari_tertinggi = $issueClass->getTotalIssuesByDayDescending($m, $y, '3');
			$urutan_total_jam[0] = $issueClass->getTotalIssuesByTimePeriode($m, $y, '09:00:00', '12:00:00', '3');
			$urutan_total_jam[1] = $issueClass->getTotalIssuesByTimePeriode($m, $y, '12:00:00', '16:00:00', '3');
			$urutan_total_jam[2] = $issueClass->getTotalIssuesByTimePeriode($m, $y, '16:00:00', '19:00:00', '3');
			$urutan_total_jam[3] = $issueClass->getTotalIssuesByTimePeriode($m, $y, '19:00:00', '23:00:00', '3');
			$urutan_total_jam[4] = $issueClass->getTotalIssuesByTimePeriode($m, $y, '23:00:00', '09:00:00', '3');
			arsort($urutan_total_jam);

			$this->view->urutan_total_jam = $urutan_total_jam;

			$dataExist = $issueClass->checkSafetyIssueSummaryExist($params['id']);
			if($dataExist) $id = $params['id'];
			else $id = 0;

			$this->view->incidents = $issueClass->getSafetyIssueSummary($m, $y, $id);

			/*$urutan_total_issue_tenant = $issueClass->getTotalIssuesByTenantPublik($m, $y, '0', '3');
			if(!empty($urutan_total_issue_tenant))
			{
				$urutan_total_all_issue_tenant = 0;
				foreach($urutan_total_issue_tenant as &$t)
				{
					$data = array();
					$data = $issueClass->getTotalIssuesByLocation($m, $y, '0', $t['location'], $t['floor_id'], '3');
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

			$urutan_total_issue_publik = $issueClass->getTotalIssuesByTenantPublik($m, $y, '1', '3');
			if(!empty($urutan_total_issue_publik))
			{
				$urutan_total_all_issue_publik = 0;
				foreach($urutan_total_issue_publik as &$p)
				{
					$data = array();
					$data = $issueClass->getTotalIssuesByLocation($m, $y, '1', $p['location'], $p['floor_id'], '3');
					
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
			*/
			
			$total_all_tangkapan = 0;
			$total_tangkapan_monthly = array();
			$list_tangkapan = $issueClass->getPelakuTertangkapByCategory($y, '3');
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

			$pelaku_tertangkap_detail = $issueClass->getPelakuTertangkapDetail($m, $y, '3');
			foreach($pelaku_tertangkap_detail as &$pelaku)
			{
				$tgl = explode(" ", $pelaku['issue_date']);
				$pelaku['date'] = date("j M Y", strtotime($tgl[0]))." ".$tgl[1];
			}
			$this->view->pelaku_tertangkap_detail = $pelaku_tertangkap_detail;

			$listIssues = $issueClass->getMonthlyAnalysisIssues($m, $y, '3');
			foreach($listIssues as &$issue)
			{
				$tgl = explode(" ", $issue['issue_date']);
				$issue['date'] = date("j M Y", strtotime($tgl[0]))." ".$tgl[1];
			}
			$this->view->listIssues = $listIssues;

			Zend_Loader::LoadClass('incidentClass', $this->modelDir);
			$incidentClass = new incidentClass();	
			$this->view->listKejadian = $incidentClass->getIncidentByCategoryId('3');

			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "Add/Edit Safety Monthly Analysis - Page 3";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);
			
			$this->renderTemplate('form_safety_monthly_analysis3.tpl'); 
		}	
		else
		{
			$this->_response->setRedirect($this->baseUrl.'/default');
			$this->_response->sendResponse();
			exit();	
		}
	}

	public function savemonthlyanalysis3Action() {
		$params = $this->_getAllParams();
		$this->view->monthly_analysis_id = $params['monthly_analysis_id'];
		
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
			$data['rekomendasi'] = addslashes(str_replace("\n","<br>",$params['rekomendasi'][$i]));
			$data['user_id'] = $this->ident['user_id'];
			$monthlyanalysissummaryClass->saveMonthlyAnalysis($data, '3');
			$i++;
		}	
		
		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "Save Safety Monthly Analysis - Page 3";
		$logData['data'] = json_encode($params);
		$logsTable->insertLogs($logData);

		$this->_response->setRedirect($this->baseUrl.'/default/safety/viewmonthlyanalysis');
		$this->_response->sendResponse();
		exit();	
	}

	public function viewmonthlyanalysisAction() {
		$params = $this->_getAllParams();
		$this->view->ident = $this->ident;
		if(empty($params['start'])) $params['start'] = '0';
		$params['pagesize'] = 10;
		$this->view->start = $params['start'];
		Zend_Loader::LoadClass('safetyClass', $this->modelDir);
		$safetyClass = new safetyClass();
		$monthlyAnalysis = $safetyClass->getMonthlyAnalysis($params);
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

		$totalMonthlyAnalysis = $safetyClass->getTotalMonthlyAnalysis();
		if($totalMonthlyAnalysis > 10)
		{
			if($params['start'] >= 10)
			{
				$this->view->firstPageUrl = "/default/safety/viewmonthlyanalysis";
				$this->view->prevUrl = "/default/safety/viewmonthlyanalysis/start/".($params['start']-$params['pagesize']);
			}
			if($params['start'] < (floor(($totalMonthlyAnalysis-1)/10)*10))
			{
				$this->view->nextUrl = "/default/safety/viewmonthlyanalysis/start/".($params['start']+$params['pagesize']);
				$this->view->lastPageUrl = "/default/safety/viewmonthlyanalysis/start/".(floor(($totalMonthlyAnalysis-1)/10)*10);
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
		$logData['action'] = "View Safety Monthly Analysis List";
		$logData['data'] = json_encode($params);
		$logsTable->insertLogs($logData);

		$this->renderTemplate('view_safety_monthly_analysis.tpl'); 
	}

	public function viewdetailmonthlyanalysisAction() {
		$params = $this->_getAllParams();

		Zend_Loader::LoadClass('safetyClass', $this->modelDir);
		$safetyClass = new safetyClass();

		$this->view->monthly_analysis_id = $params['id'];

		$safetyMonthlyAnalysis = $safetyClass->getMonthlyAnalysisById($params['id']);
		$report_date = explode(" ", $safetyMonthlyAnalysis['save_date']);
		$monthly_analysis_date = explode("-", $report_date[0]);	
		$ym = date("Ym", mktime(0, 0, 0, $monthly_analysis_date[1]-1, $monthly_analysis_date[2], $monthly_analysis_date[0]));
		$ymCur = date("Ym", mktime(0, 0, 0, $monthly_analysis_date[1], $monthly_analysis_date[2], $monthly_analysis_date[0]));
			
		$this->view->year = $y = substr($ym, 0, 4);
		$this->view->month = $m = substr($ym, 4, 2);	
		$yCur = substr($ymCur, 0, 4);
		$mCur = substr($ymCur, 4, 2);

		$this->view->monthYear = date("F Y", strtotime($y."-".$m."-01"));

		$this->view->ident = $this->ident;
		
		Zend_Loader::LoadClass('issueClass', $this->modelDir);
		$issueClass = new issueClass();

		//$modus = $this->cache->load("modus_".$this->site_id."_3_".$ym);
		if(empty($modus))
		{		
			Zend_Loader::LoadClass('modusClass', $this->modelDir);
			$modusClass = new modusClass();	
			$modus = $modusClass->getModus('3');
			//$this->cache->save($modus, "modus_".$this->site_id."_3_".$ym, array("modus_".$this->site_id."_3_".$ym), 0);
		}

		//$totalModusPerMonth = $this->cache->load("total_modus_per_month_".$this->site_id."_3_".$ym);
		if(empty($totalModusPerMonth))
		{	
			$totalModusPerMonth =  array();
			for($b=1; $b<=$m; $b++) // get rekapitulasi utk bulan ini dan bulan2 sblmnya
			{
				$totalModus = $issueClass->getIssuesByModus($b, $y, '3');
				foreach($totalModus as $tm) {
					$totalModusPerMonth[$b][$tm['modus_id']] = $tm['total_modus'];
				}
			}
			//$this->cache->save($totalModusPerMonth, "total_modus_per_month_".$this->site_id."_3_".$ym, array("total_modus_per_month_".$this->site_id."_3_".$ym), 0);
		}
		else{
			$totalModus = $issueClass->getIssuesByModus($m, $y, '3');
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
				$analisa_hari = $issueClass->getMonthlyIssuesByDay($mo['kejadian_id'], $m, $y, '3');
				if(!empty($analisa_hari))
				{
					foreach($analisa_hari as $ah) {
						$rekap[$i]['analisa_hari'][$ah['day']]= $ah['total'];
					}
				}
				$rekap[$i]['analisa_jam'][0] = $issueClass->getMonthlyIssuesByTime($mo['kejadian_id'], $m, $y, '09:00:00', '12:00:00', '3');
				$rekap[$i]['analisa_jam'][1] = $issueClass->getMonthlyIssuesByTime($mo['kejadian_id'], $m, $y, '12:00:00', '16:00:00', '3');
				$rekap[$i]['analisa_jam'][2] = $issueClass->getMonthlyIssuesByTime($mo['kejadian_id'], $m, $y, '16:00:00', '19:00:00', '3');
				$rekap[$i]['analisa_jam'][3] = $issueClass->getMonthlyIssuesByTime($mo['kejadian_id'], $m, $y, '19:00:00', '23:00:00', '3');
				$rekap[$i]['analisa_jam'][4] = $issueClass->getMonthlyIssuesByTime($mo['kejadian_id'], $m, $y, '23:00:00', '09:00:00', '3');
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
			$uraian_kejadian = $issueClass->getIssuesByModusId($mo['modus_id'], $m, $y, '3');
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
		$this->view->urutan_hari_tertinggi = $issueClass->getTotalIssuesByDayDescending($m, $y, '3');
		$urutan_total_jam[0] = $issueClass->getTotalIssuesByTimePeriode($m, $y, '09:00:00', '12:00:00', '3');
		$urutan_total_jam[1] = $issueClass->getTotalIssuesByTimePeriode($m, $y, '12:00:00', '16:00:00', '3');
		$urutan_total_jam[2] = $issueClass->getTotalIssuesByTimePeriode($m, $y, '16:00:00', '19:00:00', '3');
		$urutan_total_jam[3] = $issueClass->getTotalIssuesByTimePeriode($m, $y, '19:00:00', '23:00:00', '3');
		$urutan_total_jam[4] = $issueClass->getTotalIssuesByTimePeriode($m, $y, '23:00:00', '09:00:00', '3');
		arsort($urutan_total_jam);

		$this->view->urutan_total_jam = $urutan_total_jam;

		$this->view->incidents = $issueClass->getSafetyIssueSummary($m, $y, $params['id']);

		$urutan_total_issue_tenant = $issueClass->getTotalIssuesByTenantPublik($m, $y, '0', '3');
		if(!empty($urutan_total_issue_tenant))
		{
			$urutan_total_all_issue_tenant = 0;
			foreach($urutan_total_issue_tenant as &$t)
			{
				$data = array();
				$data = $issueClass->getTotalIssuesByLocation($m, $y, '0', $t['location'], $t['floor_id'], '3');
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

		$urutan_total_issue_publik = $issueClass->getTotalIssuesByTenantPublik($m, $y, '1', '3');
		if(!empty($urutan_total_issue_publik))
		{
			$urutan_total_all_issue_publik = 0;
			foreach($urutan_total_issue_publik as &$p)
			{
				$data = array();
				$data = $issueClass->getTotalIssuesByLocation($m, $y, '1', $p['location'], $p['floor_id'], '3');
				
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
		$list_tangkapan = $issueClass->getPelakuTertangkapByCategory($y, '3');
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

		$pelaku_tertangkap_detail = $issueClass->getPelakuTertangkapDetail($m, $y, '3');
		foreach($pelaku_tertangkap_detail as &$pelaku)
		{
			$tgl = explode(" ", $pelaku['issue_date']);
			$pelaku['date'] = date("j M Y", strtotime($tgl[0]))." ".$tgl[1];
		}
		$this->view->pelaku_tertangkap_detail = $pelaku_tertangkap_detail;

		Zend_Loader::LoadClass('equipmentClass', $this->modelDir);
		$equipmentClass = new equipmentClass();

		$this->view->buildingActiveProtection = $equipmentClass->getBuildingProtectionEquipment('Aktif', $params['id']);
		$this->view->buildingPassiveProtection = $equipmentClass->getBuildingProtectionEquipment('Pasif', $params['id']);
		$this->view->false_alarm = $equipmentClass->getFalseAlarm($params['id']);

		$this->view->fireProtectionTenantEquipment = $equipmentClass->getFireProtectionTenant($params['id']);
		$this->view->fire_accident_equipment_detail = $equipmentClass->getFireAccidentEquipment($params['id']);
		
		Zend_Loader::LoadClass('safetyClass', $this->modelDir);
		$safetyClass = new safetyClass();

		$ph1 = $safetyClass->getPotentialHazardSpecificReport($m, $y);
		$potential_hazard = array();
		if(!empty($ph1)) {
			$k = 0;
			foreach($ph1 as $phazard)
			{
				$created_date_ph = explode(" ", $phazard['created_date']);
				$phazard['date_time'] = date("j F Y", strtotime($created_date_ph[0]));
				$day = date("N", strtotime($created_date_ph[0]));
				switch($day)
				{
					case 1: $phazard['date_time'] = "Senin, ".$phazard['date_time']; break;
					case 2: $phazard['date_time'] = "Selasa, ".$phazard['date_time']; break;
					case 3: $phazard['date_time'] = "Rabu, ".$phazard['date_time']; break;
					case 4: $phazard['date_time'] = "Kamis, ".$phazard['date_time']; break;
					case 5: $phazard['date_time'] = "Jumat, ".$phazard['date_time']; break;
					case 6: $phazard['date_time'] = "Sabtu, ".$phazard['date_time']; break;
					case 7: $phazard['date_time'] = "Minggu, ".$phazard['date_time']; break;
				}
				$potential_hazard[$k]['date_time'] = $phazard['date_time'];
				$potential_hazard[$k]['description'] = $phazard['detail'];
				$potential_hazard[$k]['status'] = $phazard['status'];
				$k++;
			}
		}

		Zend_Loader::LoadClass('commentsClass', $this->modelDir);
		$commentsClass = new commentsClass();

		$ph2 = $issueClass->getPotentialHazardIssues($m, $y);
		if(!empty($ph2)) {
			$k = 0;
			foreach($ph2 as $ph)
			{
				$created_date = explode(" ", $ph['issue_date']);
				$ph['date_time'] = date("j F Y", strtotime($created_date[0]));
				$day = date("N", strtotime($created_date[0]));
				switch($day)
				{
					case 1: $ph['date_time'] = "Senin, ".$ph['date_time']; break;
					case 2: $ph['date_time'] = "Selasa, ".$ph['date_time']; break;
					case 3: $ph['date_time'] = "Rabu, ".$ph['date_time']; break;
					case 4: $ph['date_time'] = "Kamis, ".$ph['date_time']; break;
					case 5: $ph['date_time'] = "Jumat, ".$ph['date_time']; break;
					case 6: $ph['date_time'] = "Sabtu, ".$ph['date_time']; break;
					case 7: $ph['date_time'] = "Minggu, ".$ph['date_time']; break;
				}
				$potential_hazard[$k]['date_time'] = $ph['date_time'];
				$potential_hazard[$k]['description'] = $ph['description'];
				if($ph['solved'] == '1') 
				{
					$status = $commentsClass->getStatusByIssueId($ph['issue_id']);
					$potential_hazard[$k]['status'] = $status['comment'];
				}
				$k++;
			}
		}
		$this->view->potential_hazard = $potential_hazard;

		$training_safety_induction = $safetyClass->getTrainingSafetyInduction($m, $y);
		if(!empty($training_safety_induction)) {
			foreach($training_safety_induction as &$tsi) {
				$created_date = explode(" ", $tsi['created_date']);
				$tsi['training_date'] = date("j F Y", strtotime($created_date[0]));
				$day = date("N", strtotime($created_date[0]));
				switch($day)
				{
					case 1: $tsi['training_date'] = "Senin, ".$tsi['training_date']; break;
					case 2: $tsi['training_date'] = "Selasa, ".$tsi['training_date']; break;
					case 3: $tsi['training_date'] = "Rabu, ".$tsi['training_date']; break;
					case 4: $tsi['training_date'] = "Kamis, ".$tsi['training_date']; break;
					case 5: $tsi['training_date'] = "Jumat, ".$tsi['training_date']; break;
					case 6: $tsi['training_date'] = "Sabtu, ".$tsi['training_date']; break;
					case 7: $tsi['training_date'] = "Minggu, ".$tsi['training_date']; break;
				}
			}
		}
		$this->view->training_safety_induction = $training_safety_induction;

		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "View Safety Monthly Analysis Detail";
		$logData['data'] = json_encode($params);
		$logsTable->insertLogs($logData);

		$this->renderTemplate('safety_monthly_analysis_detail.tpl'); 
	}

	public function downloadsafetymonthlyanalysisAction() {		
		$params = $this->_getAllParams();

		if(!empty($params['id']))
		{			
			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "Export Safety Monthly Analysis Report to PDF";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);

			Zend_Loader::LoadClass('safetyClass', $this->modelDir);
			$safetyClass = new safetyClass();

			$monthly_analysis_id = $params['id'];

			$safetyMonthlyAnalysis = $safetyClass->getMonthlyAnalysisById($params['id']);
			$report_date = explode(" ", $safetyMonthlyAnalysis['save_date']);
			$monthly_analysis_date = explode("-", $report_date[0]);	
			$ym = date("Ym", mktime(0, 0, 0, $monthly_analysis_date[1]-1, $monthly_analysis_date[2], $monthly_analysis_date[0]));
			$ymCur = date("Ym", mktime(0, 0, 0, $monthly_analysis_date[1], $monthly_analysis_date[2], $monthly_analysis_date[0]));
			
			$y = substr($ym, 0, 4);
			$m = substr($ym, 4, 2);	
			$yCur = substr($ymCur, 0, 4);
			$mCur = substr($ymCur, 4, 2);
			
			$monthYear = date("F Y", strtotime($y."-".$m."-01"));

			
			Zend_Loader::LoadClass('issueClass', $this->modelDir);
			$issueClass = new issueClass();

			//$modus = $this->cache->load("modus_".$this->site_id."_3_".$ym);
			if(empty($modus))
			{		
				Zend_Loader::LoadClass('modusClass', $this->modelDir);
				$modusClass = new modusClass();	
				$modus = $modusClass->getModus('3');
				//$this->cache->save($modus, "modus_".$this->site_id."_3_".$ym, array("modus_".$this->site_id."_3_".$ym), 0);
			}

			//$totalModusPerMonth = $this->cache->load("total_modus_per_month_".$this->site_id."_3_".$ym);
			if(empty($totalModusPerMonth))
			{	
				$totalModusPerMonth =  array();
				for($b=1; $b<=$m; $b++) // get rekapitulasi utk bulan ini dan bulan2 sblmnya
				{
					$totalModus = $issueClass->getIssuesByModus($b, $y, '3');
					foreach($totalModus as $tm) {
						$totalModusPerMonth[$b][$tm['modus_id']] = $tm['total_modus'];
					}
				}
				//$this->cache->save($totalModusPerMonth, "total_modus_per_month_".$this->site_id."_3_".$ym, array("total_modus_per_month_".$this->site_id."_3_".$ym), 0);
			}
			else{
				$totalModus = $issueClass->getIssuesByModus($m, $y, '3');
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
					$analisa_hari = $issueClass->getMonthlyIssuesByDay($mo['kejadian_id'], $m, $y, '3');
					if(!empty($analisa_hari))
					{
						foreach($analisa_hari as $ah) {
							$rekap[$i]['analisa_hari'][$ah['day']]= $ah['total'];
						}
					}
					$rekap[$i]['analisa_jam'][0] = $issueClass->getMonthlyIssuesByTime($mo['kejadian_id'], $m, $y, '09:00:00', '12:00:00', '3');
					$rekap[$i]['analisa_jam'][1] = $issueClass->getMonthlyIssuesByTime($mo['kejadian_id'], $m, $y, '12:00:00', '16:00:00', '3');
					$rekap[$i]['analisa_jam'][2] = $issueClass->getMonthlyIssuesByTime($mo['kejadian_id'], $m, $y, '16:00:00', '19:00:00', '3');
					$rekap[$i]['analisa_jam'][3] = $issueClass->getMonthlyIssuesByTime($mo['kejadian_id'], $m, $y, '19:00:00', '23:00:00', '3');
					$rekap[$i]['analisa_jam'][4] = $issueClass->getMonthlyIssuesByTime($mo['kejadian_id'], $m, $y, '23:00:00', '09:00:00', '3');
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
				$uraian_kejadian = $issueClass->getIssuesByModusId($mo['modus_id'], $m, $y, '3');
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
	
			$urutan_hari_tertinggi = $issueClass->getTotalIssuesByDayDescending($m, $y, '3');
			$urutan_total_jam[0] = $issueClass->getTotalIssuesByTimePeriode($m, $y, '09:00:00', '12:00:00', '3');
			$urutan_total_jam[1] = $issueClass->getTotalIssuesByTimePeriode($m, $y, '12:00:00', '16:00:00', '3');
			$urutan_total_jam[2] = $issueClass->getTotalIssuesByTimePeriode($m, $y, '16:00:00', '19:00:00', '3');
			$urutan_total_jam[3] = $issueClass->getTotalIssuesByTimePeriode($m, $y, '19:00:00', '23:00:00', '3');
			$urutan_total_jam[4] = $issueClass->getTotalIssuesByTimePeriode($m, $y, '23:00:00', '09:00:00', '3');
			arsort($urutan_total_jam);

			$urutan_total_jam = $urutan_total_jam;

			$incidents = $issueClass->getSafetyIssueSummary($m, $y, $params['id']);

			$urutan_total_issue_tenant = $issueClass->getTotalIssuesByTenantPublik($m, $y, '0', '3');
			if(!empty($urutan_total_issue_tenant))
			{
				$urutan_total_all_issue_tenant = 0;
				foreach($urutan_total_issue_tenant as &$utit)
				{
					$data = array();
					$data = $issueClass->getTotalIssuesByLocation($m, $y, '0', $utit['location'], $utit['floor_id'], '3');
					if(!empty($data))
					{
						foreach($data as $dt)
						{
								$utit[$dt['kejadian_id']] = $dt['total_kejadian'];
						}
					}
					$urutan_total_all_issue_tenant += $utit['total'];
				}
			}
			
			$urutan_total_issue_publik = $issueClass->getTotalIssuesByTenantPublik($m, $y, '1', '3');
			if(!empty($urutan_total_issue_publik))
			{
				$urutan_total_all_issue_publik = 0;
				foreach($urutan_total_issue_publik as &$p)
				{
					$data = array();
					$data = $issueClass->getTotalIssuesByLocation($m, $y, '1', $p['location'], $p['floor_id'], '3');
					
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

			$total_all_tangkapan = 0;
			$total_tangkapan_monthly = array();
			$list_tangkapan = $issueClass->getPelakuTertangkapByCategory($y, '3');
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
			
			$list_tangkapan = $list_tangkapan;
			$total_all_tangkapan = $total_all_tangkapan;
			$total_tangkapan_monthly = $total_tangkapan_monthly;

			$pelaku_tertangkap_detail = $issueClass->getPelakuTertangkapDetail($m, $y, '3');
			foreach($pelaku_tertangkap_detail as &$pelaku)
			{
				$tgl = explode(" ", $pelaku['issue_date']);
				$pelaku['date'] = date("j M Y", strtotime($tgl[0]))." ".$tgl[1];
			}
			
			Zend_Loader::LoadClass('equipmentClass', $this->modelDir);
			$equipmentClass = new equipmentClass();

			$buildingActiveProtection = $equipmentClass->getBuildingProtectionEquipment('Aktif', $params['id']);
			$buildingPassiveProtection = $equipmentClass->getBuildingProtectionEquipment('Pasif', $params['id']);
			$false_alarm = $equipmentClass->getFalseAlarm($params['id']);

			$fireProtectionTenantEquipment = $equipmentClass->getFireProtectionTenant($params['id']);
			$fire_accident_equipment_detail = $equipmentClass->getFireAccidentEquipment($params['id']);
			
			Zend_Loader::LoadClass('safetyClass', $this->modelDir);
			$safetyClass = new safetyClass();

			$ph1 = $safetyClass->getPotentialHazardSpecificReport($m, $y);
			$potential_hazard = array();
			if(!empty($ph1)) {
				$k = 0;
				foreach($ph1 as $phazard)
				{
					$created_date_ph = explode(" ", $phazard['created_date']);
					$phazard['date_time'] = date("j F Y", strtotime($created_date_ph[0]));
					$day = date("N", strtotime($created_date_ph[0]));
					switch($day)
					{
						case 1: $phazard['date_time'] = "Senin, ".$phazard['date_time']; break;
						case 2: $phazard['date_time'] = "Selasa, ".$phazard['date_time']; break;
						case 3: $phazard['date_time'] = "Rabu, ".$phazard['date_time']; break;
						case 4: $phazard['date_time'] = "Kamis, ".$phazard['date_time']; break;
						case 5: $phazard['date_time'] = "Jumat, ".$phazard['date_time']; break;
						case 6: $phazard['date_time'] = "Sabtu, ".$phazard['date_time']; break;
						case 7: $phazard['date_time'] = "Minggu, ".$phazard['date_time']; break;
					}
					$potential_hazard[$k]['date_time'] = $phazard['date_time'];
					$potential_hazard[$k]['description'] = $phazard['detail'];
					$potential_hazard[$k]['status'] = $phazard['status'];
					$k++;
				}
			}

			Zend_Loader::LoadClass('commentsClass', $this->modelDir);
			$commentsClass = new commentsClass();

			$ph2 = $issueClass->getPotentialHazardIssues($m, $y);
			if(!empty($ph2)) {
				$k = 0;
				foreach($ph2 as $ph)
				{
					$created_date = explode(" ", $ph['issue_date']);
					$ph['date_time'] = date("j F Y", strtotime($created_date[0]));
					$day = date("N", strtotime($created_date[0]));
					switch($day)
					{
						case 1: $ph['date_time'] = "Senin, ".$ph['date_time']; break;
						case 2: $ph['date_time'] = "Selasa, ".$ph['date_time']; break;
						case 3: $ph['date_time'] = "Rabu, ".$ph['date_time']; break;
						case 4: $ph['date_time'] = "Kamis, ".$ph['date_time']; break;
						case 5: $ph['date_time'] = "Jumat, ".$ph['date_time']; break;
						case 6: $ph['date_time'] = "Sabtu, ".$ph['date_time']; break;
						case 7: $ph['date_time'] = "Minggu, ".$ph['date_time']; break;
					}
					$potential_hazard[$k]['date_time'] = $ph['date_time'];
					$potential_hazard[$k]['description'] = $ph['description'];
					if($ph['solved'] == '1') 
					{
						$status = $commentsClass->getStatusByIssueId($ph['issue_id']);
						$potential_hazard[$k]['status'] = $status['comment'];
					}
					$k++;
				}
			}

			$training_safety_induction = $safetyClass->getTrainingSafetyInduction($m, $y);
			if(!empty($training_safety_induction)) {
				foreach($training_safety_induction as &$tsi) {
					$created_date = explode(" ", $tsi['created_date']);
					$tsi['training_date'] = date("j F Y", strtotime($created_date[0]));
					$day = date("N", strtotime($created_date[0]));
					switch($day)
					{
						case 1: $tsi['training_date'] = "Senin, ".$tsi['training_date']; break;
						case 2: $tsi['training_date'] = "Selasa, ".$tsi['training_date']; break;
						case 3: $tsi['training_date'] = "Rabu, ".$tsi['training_date']; break;
						case 4: $tsi['training_date'] = "Kamis, ".$tsi['training_date']; break;
						case 5: $tsi['training_date'] = "Jumat, ".$tsi['training_date']; break;
						case 6: $tsi['training_date'] = "Sabtu, ".$tsi['training_date']; break;
						case 7: $tsi['training_date'] = "Minggu, ".$tsi['training_date']; break;
					}
				}
			}
			
			require_once('fpdf/mc_table.php');
			$pdf=new PDF_MC_Table();
			$pdf->AddPage();
			
			$pdf->SetTitle($this->ident['initial']." - SAFETY MONTHLY ANALYTICS - ".$monthYear);
			$pdf->SetFont('Arial','B',12);
			$pdf->Write(5, $this->ident['initial']." - SAFETY MONTHLY ANALYTICS - ".$monthYear);
			$pdf->ln();
			$pdf->SetFont('Arial','B',10);
			$pdf->Write(5,$this->ident['site_fullname']);
			$pdf->ln(10);

			$pdf->SetFont('Arial','B',9);
			$pdf->Write(5,'REKAPITULASI KEJADIAN DAN KECELAKAAN');
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
			
			$pdf->Ln(15);
			$pdf->SetTextColor(0,0,0);
			$pdf->SetFont('Arial','B',9);
			$pdf->Write(5,'PERALATAN DAN PERLENGKAPAN');
			$pdf->Ln();
			$pdf->SetFont('Arial','B',8);
			$pdf->Write(5,'KONDISI PERALATAN DAN PERLENGKAPAN');
			$pdf->Ln();
			$pdf->SetFont('Arial','B',8);
			$pdf->Write(5,'PROTEKSI AKTIF GEDUNG');
			$pdf->Ln();

			if(!empty($buildingActiveProtection)) {
				$i=1;
				$equipment_name = "";
				foreach($buildingActiveProtection as $bap)
				{ 
					if($bap['equipment_name'] != $equipment_name)
					{	
						$j=1;
						if($i > 1) $pdf->Ln(3);
						
						if(strpos(strtolower($equipment_name), "alarm") !== false)
						{
							$pdf->Cell(50,6,'','LTR',0,'C',false);
							$pdf->Cell(124,6,'Akibat Kerusakan Alat Pendeteksi',1,0,'L',false);
							$pdf->Cell(20,6,$false_alarm['kerusakan_alat_pendeteksi'],1,0,'C',false);
							$pdf->Ln();
							$pdf->Cell(50,6,'False Alarm','LR',0,'C',false);
							$pdf->Cell(124,6,'Akibat Kerusakan System',1,0,'L',false);
							$pdf->Cell(20,6,$false_alarm['kerusakan_system'],1,0,'C',false);
							$pdf->Ln();
							$pdf->Cell(50,6,'','LBR',0,'C',false);
							$pdf->Cell(124,6,'Akibat Keteledoran Pekerja-customer',1,0,'L',false);
							$pdf->Cell(20,6,$false_alarm['keteledoran_pekerja_customer'],1,0,'C',false);
							$pdf->Ln();
							$pdf->SetFont('Arial','',7);
							$pdf->SetFillColor(158,130,75);
							$pdf->SetTextColor(255,255,255);
							$pdf->SetFont('','B');
							$pdf->Cell(174,6,'Total','LTR',0,'C',true);
							$pdf->Cell(20,6,($false_alarm['kerusakan_system']+$false_alarm['kerusakan_alat_pendeteksi']+$false_alarm['keteledoran_pekerja_customer']),'LTR',0,'C',true);
							$pdf->Ln(8);
						}

						$pdf->SetTextColor(0,0,0);
						$pdf->SetFont('Arial','B',7);
						$pdf->Write(5,$bap['equipment_name']);
						$pdf->Ln();

						$pdf->SetFont('Arial','',7);
						$pdf->SetFillColor(158,130,75);
						$pdf->SetTextColor(255,255,255);
						$pdf->SetFont('','B');
						$pdf->Cell(8,6,'No',1,0,'C',true);
						$pdf->Cell(33,6,$bap['column_name'],1,0,'C',true);
						$pdf->Cell(50,6,'Deskripsi',1,0,'C',true);
						$pdf->Cell(35,6,'Lokasi',1,0,'C',true);
						$pdf->Cell(33,6,'Jumlah',1,0,'C',true);
						$pdf->Cell(35,6,'Kondisi',1,0,'C',true);
						$pdf->Ln();
						$pdf->SetFont('');
						$pdf->SetTextColor(0,0,0);
						$pdf->SetWidths(array(8, 33, 50, 35, 33, 35));	
						$pdf->Row(array($j,$bap['item_name'], str_replace("<br>","\n",stripslashes($bap['description'])), str_replace("<br>","\n",stripslashes($bap['location'])),$bap['total_item'], str_replace("<br>","\n",stripslashes($bap['item_condition']))));

						$equipment_name = $bap['equipment_name'];
					    $i++;
					    $j++;
					}
					else { 
						$pdf->SetFont('');
						$pdf->SetTextColor(0,0,0);
						$pdf->SetWidths(array(8, 33, 50, 35, 33, 35));	
						$pdf->Row(array($j,$bap['item_name'], str_replace("<br>","\n",stripslashes($bap['description'])), str_replace("<br>","\n",stripslashes($bap['location'])),$bap['total_item'], str_replace("<br>","\n",stripslashes($bap['item_condition']))));
						$j++; 
					}
				}
			}
			$pdf->Ln(5);
			$pdf->SetFont('Arial','B',8);
			$pdf->Write(5,'PROTEKSI PASIF GEDUNG');
			$pdf->Ln();
			if(!empty($buildingPassiveProtection)) {
				$i=1;
				$equipment_name = "";
				foreach($buildingPassiveProtection as $bpp)
				{ 
					if($bpp['equipment_name'] != $equipment_name)
					{	
						$j=1;
						if($i > 1) $pdf->Ln(3);

						$pdf->SetTextColor(0,0,0);
						$pdf->SetFont('Arial','b',8);
						$pdf->Write(5,$bpp['equipment_name']);
						$pdf->Ln();

						$pdf->SetFont('Arial','',7);
						$pdf->SetFillColor(158,130,75);
						$pdf->SetTextColor(255,255,255);
						$pdf->SetFont('','B');
						$pdf->Cell(8,6,'No',1,0,'C',true);
						$pdf->Cell(33,6,$bpp['column_name'],1,0,'C',true);
						$pdf->Cell(50,6,'Deskripsi',1,0,'C',true);
						$pdf->Cell(35,6,'Lokasi',1,0,'C',true);
						$pdf->Cell(33,6,'Jumlah',1,0,'C',true);
						$pdf->Cell(35,6,'Kondisi',1,0,'C',true);
						$pdf->Ln();
						$pdf->SetFont('');
						$pdf->SetTextColor(0,0,0);
						$pdf->SetWidths(array(8, 33, 50, 35, 33, 35));	
						$pdf->Row(array($j,$bpp['item_name'], str_replace("<br>","\n",stripslashes($bpp['description'])), str_replace("<br>","\n",stripslashes($bpp['location'])),$bpp['total_item'], str_replace("<br>","\n",stripslashes($bpp['item_condition']))));

						$equipment_name = $bpp['equipment_name'];
					    $i++;
					    $j++;
					}
					else {
						$pdf->SetFont('');
						$pdf->SetTextColor(0,0,0);
						$pdf->SetWidths(array(8, 33, 50, 35, 33, 35));	
						$pdf->Row(array($j,$bpp['item_name'], str_replace("<br>","\n",stripslashes($bpp['description'])), str_replace("<br>","\n",stripslashes($bpp['location'])),$bpp['total_item'], str_replace("<br>","\n",stripslashes($bpp['item_condition']))));
						$j++; 
					}
				}
			}
			$pdf->Ln(5);
			$pdf->SetFont('Arial','B',9);
			$pdf->Write(5,'PERLENGKAPAN PROTEKSI KEBAKARAN PADA TENANT YANG BERMASALAH');
			$pdf->Ln();

			$pdf->SetFont('Arial','',7);
			$pdf->SetFillColor(158,130,75);
			$pdf->SetTextColor(255,255,255);
			$pdf->SetFont('','B');
			$pdf->Cell(40,6,'','LTR',0,'C',true);
			$pdf->Cell(35,6,'','LTR',0,'C',true);
			$pdf->Cell(70,6,'Jenis Temuan Di Lapangan',1,0,'C',true);
			$pdf->Cell(48,6,'','LTR',0,'C',true);
			$pdf->Ln();
			$pdf->Cell(40,6,'Tenant','LBR',0,'C',true);
			$pdf->Cell(35,6,'LT','LBR',0,'C',true);
			$pdf->Cell(35,6,'Proteksi Kebakaran',1,0,'C',true);
			$pdf->Cell(35,6,'Potensi Bahaya',1,0,'C',true);
			$pdf->Cell(48,6,'Keterangan','LBR',0,'C',true);
			$pdf->Ln();
			if(!empty($fireProtectionTenantEquipment)) {
				$pdf->SetFont('');
				$pdf->SetTextColor(0,0,0);
				$pdf->SetWidths(array(40,35,35,35,48));	
				foreach($fireProtectionTenantEquipment as $fpte) {
					$pdf->Row(array($fpte['tenant_name'],$fpte['floor'],$fpte['proteksi_kebakaran'],$fpte['potensi_bahaya'],$fpte['keterangan']));
				}
			}
			
			$pdf->Ln(5);
			$pdf->SetFont('Arial','B',9);
			$pdf->Write(5,'PERLENGKAPAN PENANGGULANGAN KEBAKARAN DAN KECELAKAAN GEDUNG');
			$pdf->Ln();

			$pdf->SetFont('Arial','',7);
			$pdf->SetFillColor(158,130,75);
			$pdf->SetTextColor(255,255,255);
			$pdf->SetFont('','B');
			$pdf->Cell(10,6,'No',1,0,'C',true);
			$pdf->Cell(50,6,'Jenis Perlengkapan',1,0,'C',true);
			$pdf->Cell(50,6,'Lokasi Penempatan',1,0,'C',true);
			$pdf->Cell(38,6,'Jumlah',1,0,'C',true);
			$pdf->Cell(45,6,'Kondisi',1,0,'C',true);
			$pdf->Ln();
			if(!empty($fire_accident_equipment_detail)) {
				$pdf->SetFont('');
				$pdf->SetTextColor(0,0,0);
				$pdf->SetWidths(array(10,50,50,38,45));	
				$i = 1;
				foreach($fire_accident_equipment_detail as $faed) {
					$pdf->Row(array($i,$faed['equipment_name'],$faed['location'],$faed['total'],$faed['item_condition']));
					$i++;
				}
			}
			$pdf->Ln(5);

			if(!empty($potential_hazard) || !empty($training_safety_induction))
			{
				$pdf->SetFont('Arial','B',9);
				$pdf->Write(5,'DETAIL HASIL DAN PENCAPAIAN');
				$pdf->Ln();
				
				if(!empty($potential_hazard)) {
					
					$pdf->SetFont('Arial','B',8);
					$pdf->Write(5,'HASIL TEMUAN DI LAPANGAN');
					$pdf->Ln();

					$pdf->SetFont('Arial','',7);
					$pdf->SetFillColor(158,130,75);
					$pdf->SetTextColor(255,255,255);
					$pdf->SetFont('','B');
					$pdf->Cell(50,6,'Hari & Tanggal',1,0,'C',true);
					$pdf->Cell(71,6,'Uraian',1,0,'C',true);
					$pdf->Cell(72,6,'Langkah Antisipasi Awal',1,0,'C',true);
					$pdf->Ln();
					$pdf->SetFont('');
					$pdf->SetTextColor(0,0,0);
					$pdf->SetWidths(array(50,71,72));	
					$i = 1;
					foreach($potential_hazard as $ph) {
						$pdf->Row(array($ph['date_time'],$ph['description'],$ph['status']));
					}
					$pdf->Ln();
				}

				if(!empty($training_safety_induction)) {				
					$pdf->SetFont('Arial','B',9);
					$pdf->Write(5,'PELAKSANAAN PELATIHAN DAN SAFETY INDUCTION');
					$pdf->Ln();

					$pdf->SetFont('Arial','',7);
					$pdf->SetFillColor(158,130,75);
					$pdf->SetTextColor(255,255,255);
					$pdf->SetFont('','B');
					$pdf->Cell(40,6,'Hari & Tanggal',1,0,'C',true);
					$pdf->Cell(40,6,'Jenis Pelatihan',1,0,'C',true);
					$pdf->Cell(40,6,'Peserta',1,0,'C',true);
					$pdf->Cell(32,6,'Dokumen',1,0,'C',true);
					$pdf->Cell(41,6,'Keterangan',1,0,'C',true);
					$pdf->Ln();
					$pdf->SetFont('');
					$pdf->SetTextColor(0,0,0);
					$pdf->SetWidths(array(40,40,40,32,41));	
					$i = 1;
					foreach($training_safety_induction as $tsi) {
						$pdf->Row(array($tsi['training_date'],$tsi['activity']." : ".$tsi['description'], $tsi['participant'], $tsi['document'], $tsi['remark']));
					}
					$pdf->Ln();
				}
			}

			if(!empty($rekap)) {
				$pdf->SetFont('Arial','B',9);
				$pdf->Write(5,'DETAIL REKAPITULASI KEJADIAN K3');
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
				$pdf->Write(5,'ANALISA SAFETY');
				$pdf->Ln();
			
				$pdf->SetFont('Arial','B',8);
				$pdf->Write(5,'Urutan Hari Dengan Jumlah Kejadian dan Kecelakaan K3 Tertinggi');
				$pdf->Ln();
				$pdf->SetFont('Arial','',7);
				$pdf->SetFillColor(158,130,75);
				$pdf->SetTextColor(255,255,255);
				$pdf->SetFont('','B');
				$pdf->Cell(20,6,'',1,0,'C',true);
				$pdf->Cell(158,6,'Jenis Kejadian '.$monthYear,1,0,'C',true);
				$pdf->Cell(15,6,'',1,0,'C',true);
				$pdf->Ln();
				$dt1[0] = 'Hari';
				$w[0] = 20;
				$rwidth = 158/count($rekap);
				$t = 1;
				$startw = 0;
				$starty = $pdf->getY();
				foreach($rekap as $r) {	
					$dt1[$t] = $r['kejadian_name'];
					$w[$t] = $rwidth;
					/*$pdf->setX($startw+$rwidth);
					$pdf->setY($starty);
					$pdf->MultiCell($rwidth,5,$r['kejadian_name'],LRTB,L,true);
					$startw = $startw+$rwidth;*/
					$t++;
				} 
				
				$w[$t] = 15;
				$dt1[$t] = 'Total';
				$pdf->SetFont('', 'B');
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
				$pdf->Write(5,'Periode Jam Dengan Jumlah Kejadian dan Kecelakaan K3 Tertinggi');
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
				$t = 1;
				foreach($rekap as $r) {	
					$dt1[$t] = $r['kejadian_name'];
					$t++;
				} 
				$dt1[$t] = 'Total';
				$pdf->SetFont('', 'B');
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


				/*$pdf->SetFont('Arial','B',8);
				$pdf->Write(5,'Area Tenant yang Rawan Kejadian dan Kecelakaan K3');
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
				$t = 2;
				foreach($rekap as $r) {	
					$w[$t] = $rwidth;
					$dt1[$t] = $r['kejadian_name'];
					$t++;
				} 
				$dt1[$t] = 'Total';
				$w[$t] = 13;
				$pdf->SetFont('');
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
				$pdf->Write(5,'Area Publik yang Rawan Kejadian dan Kecelakaan K3');
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
				$t = 2;
				foreach($rekap as $r) {	
					$w[$t] = $rwidth;
					$dt1[$t] = $r['kejadian_name'];
					$t++;
				} 
				$dt1[$t] = 'Total';
				$w[$t] = 13;
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
				$pdf->Ln();*/
			}

			if(!empty($this->list_tangkapan)) {
				$pdf->SetFont('Arial','B',9);
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

				
			} 
			
			if(!empty($pelaku_tertangkap_detail)) {
				$pdf->SetFont('Arial','B',7);
				$pdf->SetFillColor(158,130,75);
				$pdf->SetTextColor(255,255,255);
				$pdf->Cell(30,6,'Photo',1,0,'C',true);
				$pdf->Cell(133,6,'Description',1,0,'C',true);
				$pdf->Cell(30,6,'Date',1,0,'C',true);
				$pdf->Ln();
				$pdf->SetFont('');
				$pdf->SetTextColor(0,0,0);	
				$pdf->SetWidths(array(30, 133, 30));
				foreach($pelaku_tertangkap_detail as $pelaku) {
					if($pelaku['issue_date'] > "2019-10-23 14:30:00")
					{
						$issuedate = explode("-",$pelaku['issue_date']);
						$imageURL = str_replace("https","http",$this->config->general->url)."images/issues/".$issuedate[0]."/";
						$imageDir = $this->config->paths->html.'/images/issues/'.$issuedate[0]."/";
					}
					else
					{
						$imageURL = str_replace("https","http",$this->config->general->url)."images/issues/";
						$imageDir = $this->config->paths->html.'/images/issues/';
					}

					$x1 = $pdf->GetY();
					$pdf->Row(array("", $pelaku['description']."\n\n\n\n\n",$pelaku['date']));
					$x2= $pdf->GetY();
					if($x2<$x1) $y = 12;
					else $y = $pdf->GetY()-($x2-$x1-2);

					if (file_exists($imageDir.str_replace(".","_thumb.",$pelaku['picture']))) {
						$pelaku['picture'] = str_replace(".","_thumb.",$pelaku['picture']);
					}
					list($width, $height) = getimagesize($imageDir.$pelaku['picture']);
					if($width > $height)
					{
						$w = 20;
						$h = 0;
					}
					else {
						$w = 0;
						$h = 20;
					}
					$pdf->Image($imageURL.$pelaku['picture'],15,$y, $w,$h);
				}
			}

			$pdf->SetFont('Arial','B',9);
			$pdf->Write(5,'JENIS KECELAKAAN TERTINGGI');
			$pdf->Ln();
			$pdf->SetFont('Arial','B',7);
			$pdf->SetFillColor(158,130,75);
			$pdf->SetTextColor(255,255,255);
			$pdf->Cell(6,6,'No',1,0,'C',true);
			$pdf->Cell(27,6,'Jenis Kejadian',1,0,'C',true);
			$pdf->Cell(15,6,'Jumlah',1,0,'C',true);
			$pdf->Cell(48,6,'Data hasil Investigasi',1,0,'C',true);
			$pdf->Cell(48,6,'Langkah Antisipatif',1,0,'C',true);
			$pdf->Cell(48,6,'Rekomendasi',1,0,'C',true);
			$pdf->Ln();
			$pdf->SetFont('');
			$pdf->SetTextColor(0,0,0);	
			$pdf->SetWidths(array(6, 27, 15, 48, 48, 48));
			if(!empty($incidents)) {
				$i = 1;
				foreach($incidents as $incident) {
					$pdf->Row(array($i,$incident['kejadian'],$incident['total_kejadian'], str_replace("<br>","\n",stripslashes($incident['analisa'])), str_replace("<br>","\n",stripslashes($incident['tindakan'])), str_replace("<br>","\n",stripslashes($incident['rekomendasi']))));
					$i++; 
				} 
			}

			$pdf->Output('I', $this->ident['initial']."_safety_monthly_analysis_report_".str_replace(" ","",$monthYear).".pdf", false);
		}		
	}

	public function viewsafetyboardAction() {
		if($this->showSafetyBoard)
			$this->renderTemplate('safety_board.tpl'); 
		else {
			$this->_response->setRedirect($this->baseUrl);
			$this->_response->sendResponse();
			exit();
		}
	}

	public function viewsafetyboardimageslistAction() {
		if($this->showSafetyBoard)
		{
			$params = $this->_getAllParams();
			Zend_Loader::LoadClass('safetyClass', $this->modelDir);
			$safetyClass = new safetyClass();

			$this->view->year = $year = substr($params['ym'],0,4);
			$this->view->month = $month = substr($params['ym'],4,2);

			$this->view->safetyBoard = $safetyClass->getSafetyBoard('1', $year, $month);

			$this->renderTemplate('safety_board_view.tpl'); 
		}
		else {
			$this->_response->setRedirect($this->baseUrl);
			$this->_response->sendResponse();
			exit();
		}
	}

	public function viewsafetyboardimagesAction() {
		if($this->uploadSafetyBoard)
		{
			Zend_Loader::LoadClass('safetyClass', $this->modelDir);
			$safetyClass = new safetyClass();

			$this->view->safetyBoard = $safetyClass->getSafetyBoard();

			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "View Safety Board";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);

			$this->renderTemplate('safety_board_images.tpl'); 
		}
		else {
			$this->_response->setRedirect($this->baseUrl);
			$this->_response->sendResponse();
			exit();
		}
	}

	public function savesafetyboardAction() {
		if($this->uploadSafetyBoard)
		{
			$params = $this->_getAllParams();

			$params['user_id'] = $this->ident['user_id'];
			Zend_Loader::LoadClass('safetyClass', $this->modelDir);
			$safetyClass = new safetyClass();

			if($params['enable'] == "on") $params['enable'] = "1";
			else $params['enable'] = "0";

			$safetyBoardId = $safetyClass->saveSafetyBoard($params);

			if(!empty($_FILES["filename"]['name'])) {
				$ext = explode(".",$_FILES["filename"]['name']);
				$filename = $safetyBoardId.".".$ext[count($ext)-1];
				$datafolder = $this->config->paths->html."/safety_board/";
				if(move_uploaded_file($_FILES["filename"]["tmp_name"], $datafolder."large/".$filename))
				{
					$safetyClass->updateSafetyBoard($safetyBoardId,'img', $filename);

					$magickPath = "/usr/bin/convert";

					/*** create thumbnail image ***/
					exec($magickPath . ' ' . $datafolder."large/".$filename . ' -resize 150x150\> ' . $datafolder."thumb/".$filename);
					
					/*if(in_array(strtolower($ext[count($ext)-1]), array("jpg", "jpeg", "png","bmp")))
					{
						/*** resize image if size greater than 500 Kb ***
						if(filesize($datafolder."large/".$filename) > 500000) exec($magickPath . ' ' . $datafolder."large/".$filename . ' -resize 1000x1000\> ' . $datafolder."large/".$filename);
					}*/								
				}	
			}
			

			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "Save Image Safety Board";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);
		}
		else {
			$this->_response->setRedirect($this->baseUrl);
			$this->_response->sendResponse();
			exit();
		}
	}

	public function getsafetyboardbyidAction() {
		if($this->uploadSafetyBoard)
		{
			$params = $this->_getAllParams();

			Zend_Loader::LoadClass('safetyClass', $this->modelDir);
			$safetyClass = new safetyClass();

			$safetyBoard = $safetyClass->getSafetyBoardById($params['id']);
			echo json_encode($safetyBoard);
		}
		else {
			$this->_response->setRedirect($this->baseUrl);
			$this->_response->sendResponse();
			exit();
		}
	}

	public function deletesafetyboardbyidAction() {
		if($this->uploadSafetyBoard)
		{
			$params = $this->_getAllParams();
			Zend_Loader::LoadClass('safetyClass', $this->modelDir);
			$safetyClass = new safetyClass();
			$safetyClass->deleteSafetyBoardById($params['id']);

			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "Delete Safety Board";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);
			
			$this->_response->setRedirect($this->baseUrl.'/default/safety/viewsafetyboardimages');
			$this->_response->sendResponse();
			exit();
		}
		else {
			$this->_response->setRedirect($this->baseUrl);
			$this->_response->sendResponse();
			exit();
		}
	}

}

?>
