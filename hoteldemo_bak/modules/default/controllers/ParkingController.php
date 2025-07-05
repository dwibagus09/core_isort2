<?php
require_once('actionControllerBase.php');

class ParkingController extends actionControllerBase
{
	public function addAction() {
		$this->view->ident = $this->ident;
		$this->view->title = "Add Parking & Traffic Report";
		
		$settingTable = $this->loadModel('setting');
		$this->view->setting = $settingTable->getOtherSetting();
		
		$equipmentTable = $this->loadModel('equipment');
		$this->view->equipments = $equipmentTable->getParkingEquipments('1');
		$this->view->parkingEquipments = $equipmentTable->getParkingEquipments('2');
		
		$trainingTable = $this->loadModel('training');
		$this->view->training_activity = $trainingTable->getParkingTrainingActivity();
		
		$issueTypeTable = $this->loadModel('issuetype');
		//$this->view->issue_type = $issueTypeTable->getIssueType('1');
		$this->view->issue_type = $issueTypeTable->getIssueTypeByIds('1, 2, 3, 4, 5, 6');

		$this->view->site_id = $this->site_id;

		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "Add Parking & Traffic Daily Report";
		$logData['data'] = "Opening the form page";
		$logsTable->insertLogs($logData);	
		
		$this->renderTemplate('form_daily_parking.tpl'); 
	}
	
	public function savereportAction() {
		$params = $this->_getAllParams();
		$params['user_id'] = $this->ident['user_id'];
		Zend_Loader::LoadClass('parkingClass', $this->modelDir);
		$parkingClass = new parkingClass();
		
		$report = $parkingClass->getReportByDate(date("Y-m-d"));
		if(empty($params['parking_report_id']) && !empty($report))
		{
			$this->view->title = "Add Parking Report";
			$this->view->message="Report is already exist";
			$this->view->parking = $params; 
			
			$settingTable = $this->loadModel('setting');
			$this->view->setting = $settingTable->getOtherSetting();
			
			$equipmentTable = $this->loadModel('equipment');
			$this->view->equipments = $equipmentTable->getParkingEquipments('1');
			$this->view->parkingEquipments = $equipmentTable->getParkingEquipments('2');
			
			$trainingTable = $this->loadModel('training');
			$this->view->training_activity = $trainingTable->getParkingTrainingActivity();
			
			$issueTypeTable = $this->loadModel('issuetype');
			//$this->view->issue_type = $issueTypeTable->getIssueType('1');
			$this->view->issue_type = $issueTypeTable->getIssueTypeByIds('1, 2, 3, 4, 5, 6');

			$this->renderTemplate('form_daily_parking.tpl'); 
			exit();
		}
		
		$params['parking_report_id'] = $parkingClass->saveReport($params);
		
		$equipmentTable = $this->loadModel('equipment');
		$equipmentTable->deletePerlengkapanByParkingReportId($params['parking_report_id']);
		if(!empty($params['id_equipment_list']))
		{
			$i = 0;
			foreach($params['id_equipment_list'] as $id_equipment_list)
			{
				if(!empty($params['total_equipment'][$i]) || !empty($params['ok_condition'][$i]) || !empty($params['bad_condition'][$i]) || !empty($params['description'][$i])) {
					$dt['parking_report_id'] = $params['parking_report_id'];
					$dt['parking_equipment_list_id'] = $id_equipment_list;
					$dt['total_equipment'] = $params['total_equipment'][$i];
					$dt['ok_condition'] = $params['ok_condition'][$i];
					$dt['bad_condition'] = $params['bad_condition'][$i];
					$dt['description'] = $params['description'][$i];
					$equipmentTable->addPerlengkapanParking($dt);
				}
				$i++;
			}
		}
		
		$trainingTable = $this->loadModel('training');
		$trainingTable->deleteParkingTrainingByReportId($params['parking_report_id']);
		if(!empty($params['training_type']))
		{		
			$k = 0;
			foreach($params['training_type'] as $training_type)
			{
				$dt=array();
					$dt2['parking_report_id'] = $params['parking_report_id'];
					$dt2['training_type'] = $training_type;
					$dt2['training_activity'] = $params['training_activity'][$k];
					$dt2['description_training'] = $params['description_training'][$k];
					$trainingTable->addParkingTraining($dt2);
				$k++;
			}			
		}

		$parkingClass->deleteSpecificReportByParkingId($params['parking_report_id']);
		if(!empty($params['issue_type']))
		{		
			$j = 0;
			foreach($params['issue_type'] as $issue_type)
			{
				$dt=array();
				$dt['parking_report_id'] = $params['parking_report_id'];
				$dt['issue_type'] = $issue_type;
				$dt['time'] = $params['time-sr'][$j];
				$dt['detail'] = $params['description-sr'][$j];
				$dt['status'] = $params['status-sr'][$j];
				$dt['area'] = $params['time-sr'][$j];
				$dt['issue_id'] = $params['id-issue-sr'][$j];
				$parkingClass->addSpecificReport($dt);
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
					$dt3[$m] = $parkingClass->getAttachmentById($params['attachment_id'][$m]);
					if(!empty($description)) $dt3[$m]['description'] = $description;
				}
				else
				{
					$dt3[$m]['site_id'] = $this->site_id;
					$dt3[$m]['report_id'] = $params['parking_report_id'];
					$dt3[$m]['description'] = $description;
					
				}	
				if(!empty($_FILES["attachment_file"]['name'][$m]))
				{	
					$dt3[$m]['attachment'] = $_FILES["attachment_file"];
				}
				$m++;
			}			
			$parkingClass->deleteAttachmentByReportId($params['parking_report_id']);
			$m=0;
			foreach($dt3 as $dt)
			{
				$attachment = $dt['attachment'];
				unset($dt['attachment']);
				$attachment_id = $parkingClass->addAttachment($dt);
				if($attachment_id > 0)
				{
					if(!empty($attachment['name'][$m]))
					{
						$ext = explode(".",$attachment['name'][$m]);
						$filename = $attachment_id.".".$ext[count($ext)-1];
						if(move_uploaded_file($attachment["tmp_name"][$m], $this->config->paths->html."/attachment/parking/".$filename))
							$parkingClass->updateAttachment($attachment_id,'filename', $filename);
					}
				}
				$m++;
			}			
		}*/

		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "Save Parking & Traffic Daily Report - Page 1";
		$logData['data'] = json_encode($params);
		$logsTable->insertLogs($logData);	
		
		$this->_response->setRedirect($this->baseUrl.'/default/parking/page2/id/'.$params['parking_report_id']);
		$this->_response->sendResponse();
		exit();
	}
	
	public function page2Action() {
		$params = $this->_getAllParams();
		
		Zend_Loader::LoadClass('parkingClass', $this->modelDir);
		$parkingClass = new parkingClass();
		
		$parking = $parkingClass->getReportById($params['id']);
		$datetime = explode(" ",$parking['created_date']);

		$date = explode("-",$datetime[0]);
		$r_date = mktime(0, 0, 0, intval($date[1]), intval($date[2]), intval($date[0]));
		$parking['report_date'] = date("l, j F Y", $r_date);	
		
		$this->view->parking = $parking;
		
		$settingTable = $this->loadModel('setting');
		$this->view->setting = $settingTable->getOtherSetting();
		
		$this->view->attachment = $parkingClass->getAttachments($params['id']);
		
		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "Add/Edit Parking & Traffic Daily Report - Page 2";
		$logData['data'] = json_encode($params);
		$logsTable->insertLogs($logData);	

		$this->renderTemplate('form_daily_parking2.tpl'); 
	}
	
	public function viewreportAction() {
		$params = $this->_getAllParams();
		Zend_Loader::LoadClass('parkingClass', $this->modelDir);
		$parkingClass = new parkingClass();
		Zend_Loader::LoadClass('parkingcommentsClass', $this->modelDir);
		$parkingcommentsClass = new parkingcommentsClass();
		if(empty($params['start'])) $params['start'] = '0';
		$params['pagesize'] = 10;
		$this->view->start = $params['start'];
		$parking = $parkingClass->getReports($params);
		foreach($parking as &$s)
		{
			$date = explode(" ", $s['created_date']);
			if($s['created_date'] >= date("Y-m-d", mktime(0, 0, 0, date("m"), date("d")-1,   date("Y")))) $s['allowEdit'] = 1;
			else $s['allowEdit'] = 0;
			$arr_date = explode("-",$date[0]);
			$s['day_date'] = date("l, j F Y", mktime(0, 0, 0, $arr_date[1], $arr_date[2], $arr_date[0]));
			$s['comments'] = $parkingcommentsClass->getCommentsByParkingReportId($s['parking_report_id'], '3');
		}
		$this->view->parking = $parking;
		
		
		$totalReport = $parkingClass->getTotalReport();
		if($totalReport['total'] > 10)
		{
			if($params['start'] >= 10)
			{
				$this->view->firstPageUrl = "/default/parking/viewreport";
				$this->view->prevUrl = "/default/parking/viewreport/start/".($params['start']-$params['pagesize']);
			}
			if($params['start'] < (floor(($totalReport['total']-1)/10)*10))
			{
				$this->view->nextUrl = "/default/parking/viewreport/start/".($params['start']+$params['pagesize']);
				$this->view->lastPageUrl = "/default/parking/viewreport/start/".(floor(($totalReport['total']-1)/10)*10);
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
		$logData['action'] = "View Parking & Traffic Daily Report List";
		$logData['data'] = json_encode($params);
		$logsTable->insertLogs($logData);	
		
    	$this->renderTemplate('view_daily_parking.tpl');  
	}
	
	public function editAction() {
		$params = $this->_getAllParams();
		Zend_Loader::LoadClass('parkingClass', $this->modelDir);
		$parkingClass = new parkingClass();
		
		if(!empty($params['id'])) 
		{
			$parking = $parkingClass->getReportById($params['id']);
			$datetime = explode(" ",$parking['created_date']);
			/*if($datetime[0] != date("Y-m-d")) 
			{
				$this->_response->setRedirect($this->baseUrl.'/default/parking/viewreport');
				$this->_response->sendResponse();
				exit();
			}*/
			$date = explode("-",$datetime[0]);

			$r_date = mktime(0, 0, 0, intval($date[1]), intval($date[2]), intval($date[0]));
			$parking['report_date'] = date("l, j F Y", $r_date);	
			$this->view->parking = $parking;
		
			$settingTable = $this->loadModel('setting');
			$this->view->setting = $settingTable->getOtherSetting();
			
			$equipmentTable = $this->loadModel('equipment');
			$this->view->equipments = $equipmentTable->getParkingEquipments('1', $params['id']);
			$this->view->parkingEquipments = $equipmentTable->getParkingEquipments('2', $params['id']);
			
			$trainingTable = $this->loadModel('training');
			$this->view->training_activity = $trainingTable->getParkingTrainingActivity();
			
			$trainingTable = $this->loadModel('training');
			$training_activity = $trainingTable->getParkingTrainingActivity();
			
			if(!empty($params['id']))
			{
				$this->view->outdoorTraining = $trainingTable->getParkingTrainingByType($params['id'],'1');
				$this->view->inHouseTraining = $trainingTable->getParkingTrainingByType($params['id'],'2');
				if(count($outdoorTraining) > count($inHouseTraining)) $totalTraining = count($outdoorTraining);
				else $totalTraining = count($inHouseTraining);
			}
		
			/*** SPECIFIC REPORT ***/
			
			$issueTypeTable = $this->loadModel('issuetype');
			//$this->view->issue_type = $issueTypeTable->getIssueType('1');
			$this->view->issue_type = $issueTypeTable->getIssueTypeByIds('1, 2, 3, 4, 5, 6');
			
			$specific_report = $parkingClass->getSpecificReportById($params['id']);
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
			$this->view->specific_report = $specific_reports;
			
			$this->view->attachment = $parkingClass->getAttachments($params['id']);
		}
		
		$this->view->site_id = $this->site_id;
		$this->view->title = "Edit Parking Report";
		$this->view->editMode = 1;

		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "Edit Parking & Traffic Daily Report - Page 1";
		$logData['data'] = json_encode($params);
		$logsTable->insertLogs($logData);	

		$this->renderTemplate('form_daily_parking.tpl');  
	}

	public function viewdetailreportAction() {
		if($this->showParkingTraffic == 1)
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
						$this->_response->setRedirect($this->baseUrl."/default/parking/viewdetailreport/id/".$params['id']);
						$this->_response->sendResponse();
						exit();
					}
				}

				Zend_Loader::LoadClass('parkingClass', $this->modelDir);
				$parkingClass = new parkingClass();
				
				$params['user_id'] = $this->ident['user_id'];
				$parkingClass->addReadParkingReportLog($params);

				$parking = $parkingClass->getReportById($params['id']);
				$datetime = explode(" ",$parking['created_date']);

				$filename = $this->config->paths->html.'/pdf_report/parking/' . $this->site_id."_parkingtraffic_".$params['id'].".pdf";

				$date = explode("-",$datetime[0]);
				$r_date = mktime(0, 0, 0, intval($date[1]), intval($date[2]), intval($date[0]));
				$parking['report_date'] = date("l, j F Y", $r_date);	
				$this->view->parking = $parking;
				
				$settingTable = $this->loadModel('setting');
				$this->view->setting = $settingTable->getOtherSetting();
				
				$equipmentTable = $this->loadModel('equipment');
				$this->view->equipments = $equipmentTable->getParkingEquipments('1', $params['id']);
				$this->view->parkingEquipments = $equipmentTable->getParkingEquipments('2', $params['id']);
				
				$trainingTable = $this->loadModel('training');
				$this->view->training_activity = $trainingTable->getParkingTrainingActivity();
				
				if(!empty($params['id']))
				{
					$this->view->outsourceTraining = $trainingTable->getParkingTrainingByType($params['id'],'1');
					$this->view->inHouseTraining = $trainingTable->getParkingTrainingByType($params['id'],'2');
				}
			
				/*** SPECIFIC REPORT ***/
				
				$specific_report = $parkingClass->getSpecificReportById($params['id']);
				foreach($specific_report as &$sr)
				{
					if(!empty($sr['issue_id']))
					{
						$sr['detail'] = $sr['description'];
						$datetime = explode(" ",$sr['issue_date']);
						$sr['time'] = $datetime[1];
					}
				}
				$this->view->specific_reports = $specific_report;
				
				$this->view->attachment = $parkingClass->getAttachments($params['id']);

				if($this->site_id == 3) $this->view->vendor1 = "CP";
				else $this->view->vendor1 = "SPI";

				$this->view->ident = $this->ident;

				$parkingCommentsTable = $this->loadModel('parkingcomments');
				$this->view->comments = $parkingCommentsTable->getCommentsByParkingReportId($params['id'], 0, 'asc');

				$logsTable = $this->loadModel('logs');
				$logData['user_id'] = intval($this->ident['user_id']);
				$logData['action'] = "View Detail Parking & Traffic Daily Report";
				$logData['data'] = json_encode($params);
				$logsTable->insertLogs($logData);	

				$this->renderTemplate('view_parking_detail_report.tpl');   
			}
		}
		else {
			$this->_response->setRedirect($this->baseUrl);
			$this->_response->sendResponse();
			exit();
		}
	}

	public function downloadparkingtrafficreportAction() {		
		$params = $this->_getAllParams();
		if(!empty($params['id']))
		{			
			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "Export Parking & Traffic Daily Report to PDF";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);	

			$this->exportparkingtopdf($params['id'], "", 1);
		}		
	}
	
	public function exporttopdf2Action() {
		$params = $this->_getAllParams();
		Zend_Loader::LoadClass('parkingClass', $this->modelDir);
		$parkingClass = new parkingClass();
		
		if(!empty($params['id'])) 
		{
			$parking = $parkingClass->getReportById($params['id']);
			$datetime = explode(" ",$parking['created_date']);
			$date = explode("-",$datetime[0]);
			$r_date = mktime(0, 0, 0, intval($date[1]), intval($date[2]), intval($date[0]));
			$parking['report_date'] = date("l, j F Y", $r_date);	
			
		
			$settingTable = $this->loadModel('setting');
			$setting = $settingTable->getOtherSetting();
			
			$equipmentTable = $this->loadModel('equipment');
			$equipments = $equipmentTable->getParkingEquipments('1', $params['id']);
			$parkingEquipments = $equipmentTable->getParkingEquipments('2', $params['id']);
			
			$trainingTable = $this->loadModel('training');
			$training_activity = $trainingTable->getParkingTrainingActivity();
			
			if(!empty($params['id']))
			{
				$outdoorTraining = $trainingTable->getParkingTrainingByType($params['id'],'1');
				$inHouseTraining = $trainingTable->getParkingTrainingByType($params['id'],'2');
				if(count($outdoorTraining) > count($inHouseTraining)) $totalTraining = count($outdoorTraining);
				else $totalTraining = count($inHouseTraining);
			}
		
			/*** SPECIFIC REPORT ***/
			
			$specific_report = $parkingClass->getSpecificReportById($params['id']);
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
			
			$attachment = $parkingClass->getAttachments($params['id']);

			if($this->site_id == 3) $vendor1 = "CP";
			else $vendor1 = "SPI";
			
			/*** END OF SPECIFIC REPORT ***/
			
			require('PHPpdf/html2fpdf.php');


			$html= '<html>
			<head>
			<title>Daily Parking Report</title>
			 
			</head>
			<body>
			<h2>Daily Parking Report</h2>
			'.$parking['site_fullname'].'
			
			<h3>DAY / DATE</h3>
			<table width="100%" cellspacing="6" cellpadding="5" align="center" border="1" valign="top">
				<tr><td><strong>Day / Date</strong></td><td colspan="3">'.$parking['report_date'].'</td></tr>
				<tr><td><strong>Time</strong></td><td colspan="3">'.$setting['parking_traffic_reporting_time'].'</td></tr>
			</table>
			
			<h3>MAN POWER</h3>
		<table width="100%" cellspacing="6" cellpadding="5" align="center" border="1" valign="top">
			<tr bgcolor="#afd9af"><th><strong>IN HOUSE</strong></th><th><strong>Vendor</strong></th></tr>
			<tr>
				<td>SUPERVISOR<br>
					Malam : '.$parking['inhouse_spv_malam'].'<br>
					Pagi : '.$parking['inhouse_spv_pagi'].'<br>
					Siang : '.$parking['inhouse_spv_siang'].'<br>&nbsp;<br>
					ADMIN<br>
					Malam : '.$parking['inhouse_admin_malam'].'<br>
					Pagi : '.$parking['inhouse_admin_pagi'].'<br>
					Siang : '.$parking['inhouse_admin_siang'].'<br>&nbsp;<br>
					KEKUATAN<br>
					Malam : '.$parking['inhouse_kekuatan_malam'].'<br>
					Pagi : '.$parking['inhouse_kekuatan_pagi'].'<br>
					Siang : '.$parking['inhouse_kekuatan_siang'].'<br>&nbsp;<br>
					CAR COUNT<br>
					Mobil : '.$parking['inhouse_carcount_mobil'].'<br>
					Motor : '.$parking['inhouse_carcount_motor'].'<br>
					Box : '.$parking['inhouse_carcount_box'].'<br>
					Valet Reg : '.$parking['inhouse_carcount_valet_reg'].'<br>
					Self Valet : '.$parking['inhouse_carcount_self_valet'].'<br>
					Drop Off : '.$parking['inhouse_carcount_drop_off'].'<br>
					Taxi : '.$parking['inhouse_carcount_taxi'].'<br>
					Total : '.$parking['inhouse_carcount_total'].'
				</td>
				<td>CPM/ACPM<br>
					'.$vendor1.' : '.$parking['vendor_cpm_acpm_spi'].'<br>
					Valet : '.$parking['vendor_cpm_acpm_valet'].'<br>&nbsp;<br>&nbsp;<br>
					PENGAWAS<br>
					'.$vendor1.' : '.$parking['vendor_pengawas_spi'].'<br>
					Valet : '.$parking['vendor_pengawas_valet'].'<br>&nbsp;<br>&nbsp;<br>
					ADMIN<br>
					'.$vendor1.' : '.$parking['vendor_admin_spi'].'<br>
					Valet : '.$parking['vendor_admin_valet'].'<br>&nbsp;<br>
					KEKUATAN<br>
					'.$vendor1.' Pagi : '.$parking['vendor_kekuatan_spi_pagi'].'<br>
					'.$vendor1.' Siang : '.$parking['vendor_kekuatan_spi_siang'].'<br>
					'.$vendor1.' Malam : '.$parking['vendor_kekuatan_spi_malam'].'<br>
					Valet Pagi : '.$parking['vendor_kekuatan_valet_pagi'].'<br>
					Valet Siang : '.$parking['vendor_kekuatan_valet_siang'].'<br>
					Valet Malam : '.$parking['vendor_kekuatan_valet_malam'].'<br>
					Taxi Pagi : '.$parking['vendor_kekuatan_taxi_pagi'].'<br>
					Taxi Siang : '.$parking['vendor_kekuatan_taxi_siang'].'<br>
					Taxi Malam : '.$parking['vendor_kekuatan_taxi_malam'].'<br>
					Taxi Online Pagi : '.$parking['vendor_kekuatan_taxionline_pagi'].'<br>
					Taxi Online Siang : '.$parking['vendor_kekuatan_taxionline_siang'].'<br>
					Taxi Online Malam : '.$parking['vendor_kekuatan_taxionline_malam'].'<br>
				</td>
			</tr>
		</table>
			
			<h3>PERLENGKAPAN</h3>
			<table width="100%" cellspacing="6" cellpadding="5" align="center" border="1" valign="top">
				<tr bgcolor="#afd9af">
				  <th rowspan="2">Nama Perlengkapan</th>
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
						<td>'.$equipment['total_equipment'].'</td>
						<td>'.$equipment['ok_condition'].'</td>
						<td>'.$equipment['bad_condition'].'</td>
						<td>'.$equipment['description'].'</td>
					</tr>';
					$i++; } 
					}
				  $html .= '</table>	

			<h3>PERLENGKAPAN PARKIR</h3>
			<table width="100%" cellspacing="6" cellpadding="5" align="center" border="1" valign="top">
				<tr bgcolor="#afd9af">
				  <th rowspan="2">Nama Perlengkapan</th>
				  <th rowspan="2">Jumlah</th>
				  <th width="150" colspan="2">Kondisi</th>
				  <th rowspan="2">Keterangan</th>
				</tr>
				<tr bgcolor="#afd9af">
				  <th width="75">Ok</th>
				  <th width="75">Tidak Ok</th>
				</tr>';
				  
				if(!empty($parkingEquipments)) {
					$i = 0;
					foreach($parkingEquipments as $equipment) {
						$html .= '<tr>
						<td>'.$equipment['equipment_name'].'</td>
						<td>'.$equipment['total_equipment'].'</td>
						<td>'.$equipment['ok_condition'].'</td>
						<td>'.$equipment['bad_condition'].'</td>
						<td>'.$equipment['description'].'</td>
					</tr>';
					$i++; } 
					}
				  $html .= '</table>
				  
			<h3>BRIEFING</h3>';
			if(!empty($parking['briefing1']))
				$html .= '<div>'.str_replace("<br />", "<br>",nl2br($parking['briefing1'])).'</div><br>
			<hr>';
			if(!empty($parking['briefing2']))
				$html .= '<div>'.str_replace("<br />", "<br>",nl2br($parking['briefing2'])).'</div><br>
			<hr>';
			if(!empty($parking['briefing3']))
				$html .= '<div>'.str_replace("<br />", "<br>",nl2br($parking['briefing3'])).'</div><br>
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
	'.$parking['sop1'].'<br><hr>
	'.$parking['sop2'].'<br><hr>
	'.$parking['sop3'].'<br><hr>';
				
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
						<td><a href="'.$this->baseUrl.'/default/attachment/openattachment/c/5/f/'.$att['filename'].'">'.$att['description'].'</a></td>
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
			Zend_Loader::LoadClass('parkingClass', $this->modelDir);
			$parkingClass = new parkingClass();
			$parking = $parkingClass->getReportById($params['id']);

			$params['user_id'] = $this->ident['user_id'];
			$parkingClass->addReadParkingReportLog($params);

			$filename = $this->config->paths->html.'/pdf_report/parking/' . $this->site_id."_parkingtraffic_".$params['id'].".pdf";
			if (!file_exists($filename) || date("Y-m-d", mktime(0, 0, 0, date("m"), date("d")-1, date("Y"))) < $parking['created_date']) {		
				$this->exportparkingtopdf($params['id']);
			} 
			// Header content type 
			header('Content-type: application/pdf'); 				
			header('Content-Disposition: inline; filename="' . $filename . '"'); 				
			// Read the file 
			readfile($filename); 
			exit();			
		}
	}
	
	
	function getcommentsbyreportidAction()
	{
		$params = $this->_getAllParams();
		$this->view->ident = $this->ident;
		$category = $this->loadModel('category');
		$parkingCommentsTable = $this->loadModel('parkingcomments');
		$comments = $parkingCommentsTable->getCommentsByParkingReportId($params['id']);
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
		$commentsTable = $this->loadModel('parkingcomments');
		$params['user_id'] = $this->ident['user_id'];
		$params['site_id'] = $this->site_id;
		if($_FILES["attachment"]["size"] > 0)
		{
			$ext = explode(".",$_FILES["attachment"]['name']);
			$filename = "park_".date("YmdHis").".".$ext[count($ext)-1];
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
		
		Zend_Loader::LoadClass('parkingClass', $this->modelDir);
		$parkingClass = new parkingClass();
		$parking = $parkingClass->getReportById($params['report_id']);
		
		$datetime = explode(" ",$parking['created_date']);
		$date = explode("-",$datetime[0]);
		$r_date = mktime(0, 0, 0, intval($date[1]), intval($date[2]), intval($date[0]));
		$report_date = date("l, j F Y", $r_date);
		
		$categoryClass = $this->loadModel('category');
		$category = $categoryClass->getCategoryById('5');	

		
		$botToken = '716005387:AAFhUdDGlXjK5YFMYqwx_lCpqpp8760S_TE';
		/*if($this->site_id < 4)	$botToken = '476346623:AAFlB9X5-bShAkdTK9ziNDrfZ0TCePXl2cY';
		else $botToken = '716005387:AAFhUdDGlXjK5YFMYqwx_lCpqpp8760S_TE';*/
		$website="https://api.telegram.org/bot".$716005387:AAFhUdDGlXjK5YFMYqwx_lCpqpp8760S_TE;
		//$chatId=$category['telegram_channel_id'];  //Receiver Chat Id 
		$chatId=$category['site_'.$this->site_id];  //Receiver Chat Id 

		if(!empty($params['filename'])) $attachmenttext = '
attachment : '.$this->config->general->url."comments/".$params['filename'];

		$txt = '[NEW COMMENT] 
'.$this->ident['name']." : ".$params['comment'].$attachmenttext.'

[PARKING & TRAFFIC REPORT]
Report Date : '.$report_date.'
Report Link : '.$this->config->general->url."default/parking/viewdetailreport/s/".$this->site_id."/id/".$params['report_id'];
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
		$logData['action'] = "Add Comment to Parking & Traffic Daily Report";
		$logData['data'] = json_encode($allParams);
		$logsTable->insertLogs($logData);	

		echo $allParams['filename'];
	}	
	
	function getupdatedcommentsAction()
	{
		$params = $this->_getAllParams();
		$params['pagesize'] = 10;
		
		Zend_Loader::LoadClass('parkingClass', $this->modelDir);
		$parkingClass = new parkingClass();
		
		$data= array();
		$parkingReports = $parkingClass->getReportIds($params);	
		$commentsTable = $this->loadModel('parkingcomments');
		$i=0;
		foreach($parkingReports as $s) {
			$data[$i]['parking_report_id'] = $s['parking_report_id'];
			$comments = $commentsTable->getCommentsByParkingReportId($s['parking_report_id'], '3');
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
		
		Zend_Loader::LoadClass('parkingClass', $this->modelDir);
		$parkingClass = new parkingClass();
		
		$data= array();

		$commentCacheName = "parking_comments_".$this->site_id."_".$params["start"];
		$data = $this->cache->load($commentCacheName);
		$i=0;
		if(empty($data))
		{
			$parkingReports = $parkingClass->getReportIds($params);	
			$commentsTable = $this->loadModel('parkingcomments');
			foreach($parkingReports as $s) {
				$data[$i]['parking_report_id'] = $s['parking_report_id'];
				$comments = $commentsTable->getCommentsByParkingReportId($s['parking_report_id'], '3');
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
	
	/*** ATTACHMENT ***/
	
	function addattachmentAction()
	{
		$params = $this->_getAllParams();
		
		Zend_Loader::LoadClass('parkingClass', $this->modelDir);
		$parkingClass = new parkingClass();
		
		$attachment_id = $parkingClass->addAttachment($params);
		
		if(!empty($_FILES["attachment_file"]))
		{
			$ext = explode(".",$_FILES["attachment_file"]['name']);
			$filename = $attachment_id.".".$ext[count($ext)-1];
			$datafolder = $this->config->paths->html."/attachment/parking/";
			if(move_uploaded_file($_FILES["attachment_file"]["tmp_name"], $datafolder.$filename))
			{
				$parkingClass->updateAttachment($attachment_id,'filename', $filename);
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
		$logData['action'] = "Add Attachment to Parking & Traffic Daily Report";
		$logData['data'] = json_encode($params);
		$logsTable->insertLogs($logData);	
		
		$this->_response->setRedirect($this->baseUrl.'/default/parking/page2/id/'.$params['report_id']);
		$this->_response->sendResponse();
		exit();
	
	}
	
	public function getattachmentbyidAction() {
		$params = $this->_getAllParams();
		Zend_Loader::LoadClass('parkingClass', $this->modelDir);
		$parkingClass = new parkingClass();
		
		if(!empty($params['id'])) 
		{
			echo json_encode($parkingClass->getAttachmentById($params['id']));
		}
	}
	
	public function deleteattachmentbyidAction() {
		$params = $this->_getAllParams();
		Zend_Loader::LoadClass('parkingClass', $this->modelDir);
		$parkingClass = new parkingClass();
		
		$parkingClass->deleteAttachmentById($params['id']);

		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "Delete Parking & Traffic Daily Report Attachement";
		$logData['data'] = json_encode($params);
		$logsTable->insertLogs($logData);	
		
		$this->_response->setRedirect($this->baseUrl.'/default/parking/page2/id/'.$params['report_id']);
		$this->_response->sendResponse();
		exit();
	}
	
	/*** PARKING & TRAFFIC MONTHLY ANALYSIS ***/

	public function addmonthlyanalysisAction() {
		$params = $this->_getAllParams();

		if(!empty($params['id'])) 
		{
			$this->view->monthly_analysis_id = $params['id'];	
			Zend_Loader::LoadClass('parkingClass', $this->modelDir);
			$parkingClass = new parkingClass();
			$monthly_analysis = $parkingClass->geMonthlyAnalysisById($params['id']);
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
		$this->view->monthYear = date("F Y", strtotime($y."-".$m."-01"));

		$yCur = substr($ymCur, 0, 4);
		$mCur = substr($ymCur, 4, 2);

		$this->view->ident = $this->ident;
		
		Zend_Loader::LoadClass('issueClass', $this->modelDir);
		$issueClass = new issueClass();

		//$modus = $this->cache->load("modus_".$this->site_id."_5_".$ym);
		if(empty($modus))
		{		
			Zend_Loader::LoadClass('modusClass', $this->modelDir);
			$modusClass = new modusClass();	
			$modus = $modusClass->getModus('5');
			//$this->cache->save($modus, "modus_".$this->site_id."_5_".$ym, array("modus_".$this->site_id."_5_".$ym), 0);
		}

		//$totalModusPerMonth = $this->cache->load("total_modus_per_month_".$this->site_id."_5_".$ym);
		if(empty($totalModusPerMonth))
		{	
			$totalModusPerMonth =  array();
			for($b=1; $b<=$m; $b++) // get rekapitulasi utk bulan ini dan bulan2 sblmnya
			{
				$totalModus = $issueClass->getIssuesByModus($b, $y, '5');
				foreach($totalModus as $tm) {
					$totalModusPerMonth[$b][$tm['modus_id']] = $tm['total_modus'];
				}
			}
			//$this->cache->save($totalModusPerMonth, "total_modus_per_month_".$this->site_id."_5_".$ym, array("total_modus_per_month_".$this->site_id."_5_".$ym), 0);
		}
		else{
			$totalModus = $issueClass->getIssuesByModus($m, $y, '5');
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
				$analisa_hari = $issueClass->getMonthlyIssuesByDay($mo['kejadian_id'], $m, $y, '5');
				if(!empty($analisa_hari))
				{
					foreach($analisa_hari as $ah) {
						$rekap[$i]['analisa_hari'][$ah['day']]= $ah['total'];
					}
				}
				$rekap[$i]['analisa_jam'][0] = $issueClass->getMonthlyIssuesByTime($mo['kejadian_id'], $m, $y, '09:00:00', '12:00:00', '5');
				$rekap[$i]['analisa_jam'][1] = $issueClass->getMonthlyIssuesByTime($mo['kejadian_id'], $m, $y, '12:00:00', '16:00:00', '5');
				$rekap[$i]['analisa_jam'][2] = $issueClass->getMonthlyIssuesByTime($mo['kejadian_id'], $m, $y, '16:00:00', '19:00:00', '5');
				$rekap[$i]['analisa_jam'][3] = $issueClass->getMonthlyIssuesByTime($mo['kejadian_id'], $m, $y, '19:00:00', '23:00:00', '5');
				$rekap[$i]['analisa_jam'][4] = $issueClass->getMonthlyIssuesByTime($mo['kejadian_id'], $m, $y, '23:00:00', '09:00:00', '5');
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
			$uraian_kejadian = $issueClass->getIssuesByModusId($mo['modus_id'], $m, $y, '5');
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
		$this->view->urutan_hari_tertinggi = $issueClass->getTotalIssuesByDayDescending($m, $y, '5');
		$urutan_total_jam[0] = $issueClass->getTotalIssuesByTimePeriode($m, $y, '09:00:00', '12:00:00', '5');
		$urutan_total_jam[1] = $issueClass->getTotalIssuesByTimePeriode($m, $y, '12:00:00', '16:00:00', '5');
		$urutan_total_jam[2] = $issueClass->getTotalIssuesByTimePeriode($m, $y, '16:00:00', '19:00:00', '5');
		$urutan_total_jam[3] = $issueClass->getTotalIssuesByTimePeriode($m, $y, '19:00:00', '23:00:00', '5');
		$urutan_total_jam[4] = $issueClass->getTotalIssuesByTimePeriode($m, $y, '23:00:00', '09:00:00', '5');
		arsort($urutan_total_jam);

		$this->view->urutan_total_jam = $urutan_total_jam;

		$this->view->incidents = $issueClass->getParkingIssueSummary($m, $y, $params['id']);

		$urutan_total_issue_tenant = $issueClass->getTotalIssuesByTenantPublik($m, $y, '0', '5');
		if(!empty($urutan_total_issue_tenant))
		{
			$urutan_total_all_issue_tenant = 0;
			foreach($urutan_total_issue_tenant as &$t)
			{
				$data = array();
				$data = $issueClass->getTotalIssuesByLocation($m, $y, '0', $t['location'], $t['floor_id'], '5');
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

		$urutan_total_issue_publik = $issueClass->getTotalIssuesByTenantPublik($m, $y, '1', '5');
		if(!empty($urutan_total_issue_publik))
		{
			$urutan_total_all_issue_publik = 0;
			foreach($urutan_total_issue_publik as &$p)
			{
				$data = array();
				$data = $issueClass->getTotalIssuesByLocation($m, $y, '1', $p['location'], $p['floor_id'], '5');
				
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
		$list_tangkapan = $issueClass->getPelakuTertangkapByCategory($y, '5');
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

		$pelaku_tertangkap_detail = $issueClass->getPelakuTertangkapDetail($m, $y, '5');
		foreach($pelaku_tertangkap_detail as &$pelaku)
		{
			$tgl = explode(" ", $pelaku['issue_date']);
			$pelaku['date'] = date("j M Y", strtotime($tgl[0]))." ".$tgl[1];
		}
		$this->view->pelaku_tertangkap_detail = $pelaku_tertangkap_detail;

		$listIssues = $issueClass->getMonthlyAnalysisIssues($m, $y, '5');
		foreach($listIssues as &$issue)
		{
			$tgl = explode(" ", $issue['issue_date']);
			$issue['date'] = date("j M Y", strtotime($tgl[0]))." ".$tgl[1];
		}
		$this->view->listIssues = $listIssues;

		Zend_Loader::LoadClass('incidentClass', $this->modelDir);
		$incidentClass = new incidentClass();	
		$this->view->listKejadian = $incidentClass->getIncidentByCategoryId('5');

		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "Add Parking & Traffic Monthly Analysis";
		$logData['data'] = json_encode($params);
		$logsTable->insertLogs($logData);	

		$this->renderTemplate('form_parking_monthly_analysis.tpl'); 
	}

	public function savemonthlyanalysisAction() {
		$params = $this->_getAllParams();

		$params['user_id'] = $this->ident['user_id'];
		if(empty($params['monthly_analysis_id']))
		{
			Zend_Loader::LoadClass('parkingClass', $this->modelDir);
			$parkingClass = new parkingClass();
			$params['monthly_analysis_id'] = $parkingClass->saveMonthlyAnalysis($params);
		}
		
		Zend_Loader::LoadClass('issueClass', $this->modelDir);
		$issueClass = new issueClass();
		$this->view->incidents = $issueClass->getParkingIssueSummary(date("m"), date("Y"));

		Zend_Loader::LoadClass('monthlyanalysissummaryClass', $this->modelDir);
		$monthlyanalysissummaryClass = new monthlyanalysissummaryClass();
		$data = array();
		$i=0;
		if(!empty($params['summary_id']))
		{
			foreach($params['summary_id'] as $summary_id)
			{
				$data['summary_id'] = $summary_id;
				$data['monthly_analysis_id'] = $params['monthly_analysis_id'];
				$data['kejadian_id'] = $params['kejadian_id'][$i];
				$data['analisa'] = addslashes(str_replace("\n","<br>",$params['analisa'][$i]));
				$data['tindakan'] = addslashes(str_replace("\n","<br>",$params['tindakan'][$i]));
				$data['user_id'] = $this->ident['user_id'];
				$monthlyanalysissummaryClass->saveMonthlyAnalysis($data, '5');
				$i++;
			}
		}

		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "Save Parking & Traffic Monthly Analysis";
		$logData['data'] = json_encode($params);
		$logsTable->insertLogs($logData);	

		$this->_response->setRedirect($this->baseUrl.'/default/parking/viewmonthlyanalysis');
		$this->_response->sendResponse();
		exit();
	}

	public function viewmonthlyanalysisAction() {
		$params = $this->_getAllParams();
		$this->view->ident = $this->ident;
		if(empty($params['start'])) $params['start'] = '0';
		$params['pagesize'] = 10;
		$this->view->start = $params['start'];
		Zend_Loader::LoadClass('parkingClass', $this->modelDir);
		$parkingClass = new parkingClass();
		$monthlyAnalysis = $parkingClass->getMonthlyAnalysis($params);
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

		$totalMonthlyAnalysis = $parkingClass->getTotalMonthlyAnalysis();
		if($totalMonthlyAnalysis > 10)
		{
			if($params['start'] >= 10)
			{
				$this->view->firstPageUrl = "/default/parking/viewmonthlyanalysis";
				$this->view->prevUrl = "/default/parking/viewmonthlyanalysis/start/".($params['start']-$params['pagesize']);
			}
			if($params['start'] < (floor(($totalMonthlyAnalysis-1)/10)*10))
			{
				$this->view->nextUrl = "/default/parking/viewmonthlyanalysis/start/".($params['start']+$params['pagesize']);
				$this->view->lastPageUrl = "/default/parking/viewmonthlyanalysis/start/".(floor(($totalMonthlyAnalysis-1)/10)*10);
			}
		}

		if(empty($totalMonthlyAnalysis)) $this->view->curPage = 0;
		else $this->view->curPage = ($params['start']/$params['pagesize'])+1;
		$this->view->totalPage = ceil($totalMonthlyAnalysis/$params['pagesize']);
		if(empty($totalMonthlyAnalysis)) $this->view->startRec = $params['start'];
		else $this->view->startRec = $params['start'] + 1;
		$endRec = $params['start'] + $params['pagesize'];
		if($totalMonthlyAnalysis >=  $endRec) $this->view->endRec =  $endRec;
		else $this->view->endRec =  $totalMonthlyAnalysis;		
		$this->view->totalRec = $totalMonthlyAnalysis;

		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "View Parking & Traffic Monthly Analysis List";
		$logData['data'] = json_encode($params);
		$logsTable->insertLogs($logData);	

		$this->renderTemplate('view_parking_monthly_analysis.tpl'); 
	}

	public function viewdetailmonthlyanalysisAction() {
		$params = $this->_getAllParams();

		if(!empty($params['id'])) 
		{
			$this->view->monthly_analysis_id = $params['id'];		
			Zend_Loader::LoadClass('parkingClass', $this->modelDir);
			$parkingClass = new parkingClass();
			$monthly_analysis = $parkingClass->geMonthlyAnalysisById($params['id']);
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

			//$modus = $this->cache->load("modus_".$this->site_id."_5_".$ym);
			if(empty($modus))
			{		
				Zend_Loader::LoadClass('modusClass', $this->modelDir);
				$modusClass = new modusClass();	
				$modus = $modusClass->getModus('5');
			}

			if(empty($totalModusPerMonth))
			{	
				$totalModusPerMonth =  array();
				for($b=1; $b<=$m; $b++) // get rekapitulasi utk bulan ini dan bulan2 sblmnya
				{
					$totalModus = $issueClass->getIssuesByModus($b, $y, '5');
					foreach($totalModus as $tm) {
						$totalModusPerMonth[$b][$tm['modus_id']] = $tm['total_modus'];
					}
				}
			}
			else{
				$totalModus = $issueClass->getIssuesByModus($m, $y, '5');
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
						$analisa_hari = $issueClass->getMonthlyIssuesByDay($mo['kejadian_id'], $m, $y, '5');
						
						if(!empty($analisa_hari))
						{
							foreach($analisa_hari as $ah) {
								$rekap[$i]['analisa_hari'][$ah['day']]= $ah['total'];
							}
						}
						$rekap[$i]['analisa_jam'][0] = $issueClass->getMonthlyIssuesByTime($mo['kejadian_id'], $m, $y, '09:00:00', '12:00:00', '5');
						$rekap[$i]['analisa_jam'][1] = $issueClass->getMonthlyIssuesByTime($mo['kejadian_id'], $m, $y, '12:00:00', '16:00:00', '5');
						$rekap[$i]['analisa_jam'][2] = $issueClass->getMonthlyIssuesByTime($mo['kejadian_id'], $m, $y, '16:00:00', '19:00:00', '5');
						$rekap[$i]['analisa_jam'][3] = $issueClass->getMonthlyIssuesByTime($mo['kejadian_id'], $m, $y, '19:00:00', '23:00:00', '5');
						$rekap[$i]['analisa_jam'][4] = $issueClass->getMonthlyIssuesByTime($mo['kejadian_id'], $m, $y, '23:00:00', '09:00:00', '5');
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
					$uraian_kejadian = $issueClass->getIssuesByModusId($mo['modus_id'], $m, $y, '5');
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
				
				$this->view->rekap = $rekap;
				$this->view->rekapTotal = $rekapTotal;
				$this->view->urutan_hari_tertinggi = $issueClass->getTotalIssuesByDayDescending($m, $y, '5');
				$urutan_total_jam[0] = $issueClass->getTotalIssuesByTimePeriode($m, $y, '09:00:00', '12:00:00', '5');
				$urutan_total_jam[1] = $issueClass->getTotalIssuesByTimePeriode($m, $y, '12:00:00', '16:00:00', '5');
				$urutan_total_jam[2] = $issueClass->getTotalIssuesByTimePeriode($m, $y, '16:00:00', '19:00:00', '5');
				$urutan_total_jam[3] = $issueClass->getTotalIssuesByTimePeriode($m, $y, '19:00:00', '23:00:00', '5');
				$urutan_total_jam[4] = $issueClass->getTotalIssuesByTimePeriode($m, $y, '23:00:00', '09:00:00', '5');
				arsort($urutan_total_jam);
				$this->view->urutan_total_jam = $urutan_total_jam;

				$this->view->incidents = $issueClass->getParkingIssueSummary($m, $y, $params['id']);
				$urutan_total_issue_tenant = $issueClass->getTotalIssuesByTenantPublik($m, $y, '0', '5');
				if(!empty($urutan_total_issue_tenant))
				{
					$urutan_total_all_issue_tenant = 0;
					foreach($urutan_total_issue_tenant as &$t)
					{
						$data = array();
						$data = $issueClass->getTotalIssuesByLocation($m, $y, '0', $t['location'], $t['floor_id'], '5');
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
				$this->view->urutan_total_issue_tenant = $urutan_total_issue_tenant;
				$this->view->urutan_total_all_issue_tenant = $urutan_total_all_issue_tenant;

				$urutan_total_issue_publik = $issueClass->getTotalIssuesByTenantPublik($m, $y, '1', '5');
				if(!empty($urutan_total_issue_publik))
				{
					$urutan_total_all_issue_publik = 0;
					foreach($urutan_total_issue_publik as &$p)
					{
						$data = array();
						$data = $issueClass->getTotalIssuesByLocation($m, $y, '1', $p['location'], $p['floor_id'], '5');
						
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
				$this->view->urutan_total_issue_publik = $urutan_total_issue_publik;
				$this->view->urutan_total_all_issue_publik = $urutan_total_all_issue_publik;
			}

			$total_all_tangkapan = 0;
			$total_tangkapan_monthly = array();
			$list_tangkapan = $issueClass->getPelakuTertangkapByCategory($y, '5');
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
			$this->view->list_tangkapan = $list_tangkapan;
			$this->view->total_all_tangkapan = $total_all_tangkapan;
			$this->view->total_tangkapan_monthly = $total_tangkapan_monthly;

			$pelaku_tertangkap_detail = $issueClass->getPelakuTertangkapDetail($m, $y, '5');
			foreach($pelaku_tertangkap_detail as &$pelaku)
			{
				$tgl = explode(" ", $pelaku['issue_date']);
				$pelaku['date'] = date("j M Y", strtotime($tgl[0]))." ".$tgl[1];
			}
			$this->view->pelaku_tertangkap_detail = $pelaku_tertangkap_detail;

			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "View Parking & Traffic Monthly Analysis Detail";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);	
			
			$this->renderTemplate('parking_monthly_analysis_detail.tpl'); 
		}
	}

	public function downloadparkingmonthlyanalysisAction() {		
		$params = $this->_getAllParams();

		if(!empty($params['id']))
		{			
			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "Export Parking & Traffic Monthly Analysis Report to PDF";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);

			$monthly_analysis_id = $params['id'];		
			Zend_Loader::LoadClass('parkingClass', $this->modelDir);
			$parkingClass = new parkingClass();
			$monthly_analysis = $parkingClass->geMonthlyAnalysisById($params['id']);
			$monthly_analysis_datetime = explode(" ", $monthly_analysis['save_date']);
			$monthly_analysis_date = explode("-", $monthly_analysis_datetime[0]);	
			$ym = date("Ym", mktime(0, 0, 0, $monthly_analysis_date[1]-1, $monthly_analysis_date[2], $monthly_analysis_date[0]));		
			$ymCur = date("Ym", mktime(0, 0, 0, $monthly_analysis_date[1], $monthly_analysis_date[2], $monthly_analysis_date[0]));
			$year = $y = substr($ym, 0, 4);
			$month = $m = substr($ym, 4, 2);
			$yCur = substr($ymCur, 0, 4);
			$mCur = substr($ymCur, 4, 2);		

			$monthYear = date("F Y", strtotime($y."-".$m."-01"));

			Zend_Loader::LoadClass('issueClass', $this->modelDir);
			$issueClass = new issueClass();

			//$modus = $this->cache->load("modus_".$this->site_id."_5_".$ym);
			if(empty($modus))
			{		
				Zend_Loader::LoadClass('modusClass', $this->modelDir);
				$modusClass = new modusClass();	
				$modus = $modusClass->getModus('5');
			}

			if(empty($totalModusPerMonth))
			{	
				$totalModusPerMonth =  array();
				for($b=1; $b<=$m; $b++) // get rekapitulasi utk bulan ini dan bulan2 sblmnya
				{
					$totalModus = $issueClass->getIssuesByModus($b, $y, '5');
					foreach($totalModus as $tm) {
						$totalModusPerMonth[$b][$tm['modus_id']] = $tm['total_modus'];
					}
				}
			}
			else{
				$totalModus = $issueClass->getIssuesByModus($m, $y, '5');
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
						$analisa_hari = $issueClass->getMonthlyIssuesByDay($mo['kejadian_id'], $m, $y, '5');
						
						if(!empty($analisa_hari))
						{
							foreach($analisa_hari as $ah) {
								$rekap[$i]['analisa_hari'][$ah['day']]= $ah['total'];
							}
						}
						$rekap[$i]['analisa_jam'][0] = $issueClass->getMonthlyIssuesByTime($mo['kejadian_id'], $m, $y, '09:00:00', '12:00:00', '5');
						$rekap[$i]['analisa_jam'][1] = $issueClass->getMonthlyIssuesByTime($mo['kejadian_id'], $m, $y, '12:00:00', '16:00:00', '5');
						$rekap[$i]['analisa_jam'][2] = $issueClass->getMonthlyIssuesByTime($mo['kejadian_id'], $m, $y, '16:00:00', '19:00:00', '5');
						$rekap[$i]['analisa_jam'][3] = $issueClass->getMonthlyIssuesByTime($mo['kejadian_id'], $m, $y, '19:00:00', '23:00:00', '5');
						$rekap[$i]['analisa_jam'][4] = $issueClass->getMonthlyIssuesByTime($mo['kejadian_id'], $m, $y, '23:00:00', '09:00:00', '5');
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
					$uraian_kejadian = $issueClass->getIssuesByModusId($mo['modus_id'], $m, $y, '5');
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
				
				$urutan_hari_tertinggi = $issueClass->getTotalIssuesByDayDescending($m, $y, '5');
				$urutan_total_jam[0] = $issueClass->getTotalIssuesByTimePeriode($m, $y, '09:00:00', '12:00:00', '5');
				$urutan_total_jam[1] = $issueClass->getTotalIssuesByTimePeriode($m, $y, '12:00:00', '16:00:00', '5');
				$urutan_total_jam[2] = $issueClass->getTotalIssuesByTimePeriode($m, $y, '16:00:00', '19:00:00', '5');
				$urutan_total_jam[3] = $issueClass->getTotalIssuesByTimePeriode($m, $y, '19:00:00', '23:00:00', '5');
				$urutan_total_jam[4] = $issueClass->getTotalIssuesByTimePeriode($m, $y, '23:00:00', '09:00:00', '5');
				arsort($urutan_total_jam);

				$incidents = $issueClass->getParkingIssueSummary($m, $y, $params['id']);
				$urutan_total_issue_tenant = $issueClass->getTotalIssuesByTenantPublik($m, $y, '0', '5');
				if(!empty($urutan_total_issue_tenant))
				{
					$urutan_total_all_issue_tenant = 0;
					foreach($urutan_total_issue_tenant as &$t)
					{
						$data = array();
						$data = $issueClass->getTotalIssuesByLocation($m, $y, '0', $t['location'], $t['floor_id'], '5');
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
				$urutan_total_issue_tenant = $urutan_total_issue_tenant;
				$urutan_total_all_issue_tenant = $urutan_total_all_issue_tenant;

				$urutan_total_issue_publik = $issueClass->getTotalIssuesByTenantPublik($m, $y, '1', '5');
				if(!empty($urutan_total_issue_publik))
				{
					$urutan_total_all_issue_publik = 0;
					foreach($urutan_total_issue_publik as &$p)
					{
						$data = array();
						$data = $issueClass->getTotalIssuesByLocation($m, $y, '1', $p['location'], $p['floor_id'], '5');
						
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
			$list_tangkapan = $issueClass->getPelakuTertangkapByCategory($y, '5');
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

			$pelaku_tertangkap_detail = $issueClass->getPelakuTertangkapDetail($m, $y, '5');
			foreach($pelaku_tertangkap_detail as &$pelaku)
			{
				$tgl = explode(" ", $pelaku['issue_date']);
				$pelaku['date'] = date("j M Y", strtotime($tgl[0]))." ".$tgl[1];
			}
			
			require_once('fpdf/mc_table.php');
			$pdf=new PDF_MC_Table();
			$pdf->AddPage();
			
			$pdf->SetTitle($this->ident['initial']." - PARKING & TRAFFIC MONTHLY ANALYSIS REPORT - ".$monthYear);
			$pdf->SetFont('Arial','B',12);
			$pdf->Write(5, $this->ident['initial']." - PARKING & TRAFFIC  MONTHLY ANALYSIS REPORT - ".$monthYear);
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
			$pdf->SetFillColor(9,41,102);
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
				$pdf->SetFillColor(9,41,102);
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
				$pdf->SetFillColor(9,41,102);
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
				$pdf->SetFillColor(9,41,102);
				$pdf->SetTextColor(255,255,255);
				$pdf->SetFont('','B');
				$pdf->Cell(20,6,'Hari',1,0,'C',true);
				$pdf->Cell(158,6,'Jenis Kejadian '.$monthYear,1,0,'C',true);
				$pdf->Cell(15,6,'Total',1,0,'C',true);
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
				$pdf->SetFillColor(9,41,102);
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
				$pdf->SetFillColor(9,41,102);
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
				$pdf->Write(5,'Area Publik yang rawan kejadian');
				$pdf->Ln();
				$pdf->SetFont('Arial','',7);
				$pdf->SetFillColor(9,41,102);
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
				$pdf->Ln(10);
			}
			
			if(!empty($list_tangkapan)) {
				$pdf->SetFont('Arial','B',9);
				$pdf->SetTextColor(0,0,0);
				$pdf->Write(5,'REKAPITULASI HASIL PENANGKAPAN PELAKU KEJAHATAN');
				$pdf->Ln();
				$pdf->SetFont('Arial','',7);
				$pdf->SetFillColor(9,41,102);
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
				$pdf->SetFillColor(9,41,102);
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
			$pdf->Ln(10);
			$pdf->SetFont('Arial','B',9);
			$pdf->Write(5,'JENIS KEJADIAN YANG SERING TERJADI BERDASARKAN JUMLAH KASUS BULAN '.strtoupper($monthYear));
			$pdf->Ln();
			$pdf->SetFont('Arial','B',7);
			$pdf->SetFillColor(9,41,102);
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

			$pdf->Output('I', $this->ident['initial']."_parking_monthly_analysis_report_".str_replace(" ","",$monthYear).".pdf", false);
		}		
	}
}

?>