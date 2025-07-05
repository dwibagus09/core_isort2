<?php
require_once('actionControllerBase.php');
class StatisticController extends actionControllerBase
{
	function viewAction()
	{
		$params = $this->_getAllParams();

		if(empty($params['start_date'])) $this->view->start_date = $params['start_date'] = date("Y-m-d", mktime(0, 0, 0, date("m")-1, date("d"),   date("Y")));
		else $this->view->start_date = $params['start_date'];
		if(empty($params['end_date'])) $this->view->end_date = $params['end_date'] = date("Y-m-d");
		else $this->view->end_date = $params['end_date'];

		Zend_Loader::LoadClass('issueClass', $this->modelDir);
		$issueClass = new issueClass();
		
		$this->view->totalAllIssue = $issueClass->getTotalIssues($params);

		/*** ISSUE PER TYPE GRAPH ***/
		$this->view->totalIncident = $issueClass->getTotalIssues($params, 1);
		$this->view->totalGlitch = $issueClass->getTotalIssues($params, 2);
		$this->view->totalLostFound = $issueClass->getTotalIssues($params, 3);
		$this->view->totalDefectList = $issueClass->getTotalIssues($params, 4);
		$this->view->totalNearlyMiss = $issueClass->getTotalIssues($params, 12);
		$this->view->totalUnsafeCondition = $issueClass->getTotalIssues($params, 11);

		/*** OPEN & CLOSE ISSUE PER CATEGORY ***/
		$this->view->totalOpenIncident = $issueClass->getTotalIssues($params, 1,1);
		$this->view->totalCloseIncident = $issueClass->getTotalIssues($params, 1,0,1);
		$this->view->totalOpenGlitch = $issueClass->getTotalIssues($params, 2,1);
		$this->view->totalCloseGlitch = $issueClass->getTotalIssues($params, 2,0,1);
		$this->view->totalOpenLostFound = $issueClass->getTotalIssues($params, 3,1);
		$this->view->totalCloseLostFound = $issueClass->getTotalIssues($params, 3,0,1);
		$this->view->totalOpenDefectList = $issueClass->getTotalIssues($params, 4,1);
		$this->view->totalCloseDefectList = $issueClass->getTotalIssues($params, 4,0,1);
		$this->view->totalOpenUnsafeCondition = $issueClass->getTotalIssues($params, 11,1);
		$this->view->totalCloseUnsafeCondition = $issueClass->getTotalIssues($params, 11,0,1);
		$this->view->totalOpenNearlyMiss = $issueClass->getTotalIssues($params, 12,1);
		$this->view->totalCloseNearlyMiss = $issueClass->getTotalIssues($params, 12,0,1);
		
		/***** SECURITY *****/

		$params['category_id'] = 1;

		$this->view->totalAllSecurityIssue = $issueClass->getTotalIssues($params);

		/*** SECURITY ISSUE PER TYPE GRAPH ***/
		$this->view->totalSecurityIncident = $issueClass->getTotalIssues($params, 1);
		$this->view->totalSecurityGlitch = $issueClass->getTotalIssues($params, 2);
		$this->view->totalSecurityLostFound = $issueClass->getTotalIssues($params, 3);
		$this->view->totalSecurityDefectList = $issueClass->getTotalIssues($params, 4);
		$this->view->totalSecurityNearlyMiss = $issueClass->getTotalIssues($params, 12);
		$this->view->totalSecurityUnsafeCondition = $issueClass->getTotalIssues($params, 11);

		/*** SECURITY OPEN & CLOSE ISSUE ***/
		$this->view->totalSecurityOpenIncident = $issueClass->getTotalIssues($params, 1,1);
		$this->view->totalSecurityCloseIncident = $issueClass->getTotalIssues($params, 1,0,1);
		$this->view->totalSecurityOpenGlitch = $issueClass->getTotalIssues($params, 2,1);
		$this->view->totalSecurityCloseGlitch = $issueClass->getTotalIssues($params, 2,0,1);
		$this->view->totalSecurityOpenLostFound = $issueClass->getTotalIssues($params, 3,1);
		$this->view->totalSecurityCloseLostFound = $issueClass->getTotalIssues($params, 3,0,1);
		$this->view->totalSecurityOpenDefectList = $issueClass->getTotalIssues($params, 4,1);
		$this->view->totalSecurityCloseDefectList = $issueClass->getTotalIssues($params, 4,0,1);	
		$this->view->totalSecurityOpenUnsafeCondition = $issueClass->getTotalIssues($params, 11,1);
		$this->view->totalSecurityCloseUnsafeCondition = $issueClass->getTotalIssues($params, 11,0,1);
		$this->view->totalSecurityOpenNearlyMiss = $issueClass->getTotalIssues($params, 12,1);
		$this->view->totalSecurityCloseNearlyMiss = $issueClass->getTotalIssues($params, 12,0,1);

		/***** SAFETY *****/

		$params['category_id'] = 3;

		$this->view->totalAllSafetyIssue = $issueClass->getTotalIssues($params);

		/*** SAFETY ISSUE PER TYPE GRAPH ***/
		$this->view->totalSafetyIncident = $issueClass->getTotalIssues($params, 1);
		$this->view->totalSafetyGlitch = $issueClass->getTotalIssues($params, 2);
		$this->view->totalSafetyLostFound = $issueClass->getTotalIssues($params, 3);
		$this->view->totalSafetyDefectList = $issueClass->getTotalIssues($params, 4);
		$this->view->totalSafetyNearlyMiss = $issueClass->getTotalIssues($params, 12);
		$this->view->totalSafeftyUnsafeCondition = $issueClass->getTotalIssues($params, 11);


		/*** SAFETY OPEN & CLOSE ISSUE ***/
		$this->view->totalSafetyOpenIncident = $issueClass->getTotalIssues($params, 1,1);
		$this->view->totalSafetyCloseIncident = $issueClass->getTotalIssues($params, 1,0,1);
		$this->view->totalSafetyOpenGlitch = $issueClass->getTotalIssues($params, 2,1);
		$this->view->totalSafetyCloseGlitch = $issueClass->getTotalIssues($params, 2,0,1);
		$this->view->totalSafetyOpenLostFound = $issueClass->getTotalIssues($params, 3,1);
		$this->view->totalSafetyCloseLostFound = $issueClass->getTotalIssues($params, 3,0,1);
		$this->view->totalSafetyOpenDefectList = $issueClass->getTotalIssues($params, 4,1);
		$this->view->totalSafetyCloseDefectList = $issueClass->getTotalIssues($params, 4,0,1);	

		/***** PARKING & TRAFFIC *****/

		$params['category_id'] = 5;

		$this->view->totalAllParkingIssue = $issueClass->getTotalIssues($params);

		/*** PARKING & TRAFFIC ISSUE PER TYPE GRAPH ***/
		$this->view->totalParkingIncident = $issueClass->getTotalIssues($params, 1);
		$this->view->totalParkingGlitch = $issueClass->getTotalIssues($params, 2);
		$this->view->totalParkingLostFound = $issueClass->getTotalIssues($params, 3);
		$this->view->totalParkingDefectList = $issueClass->getTotalIssues($params, 4);
		$this->view->totalParkingNearlyMiss = $issueClass->getTotalIssues($params, 12);
		$this->view->totalParkingUnsafeCondition = $issueClass->getTotalIssues($params, 11);


		/*** PARKING & TRAFFIC OPEN & CLOSE ISSUE ***/
		$this->view->totalParkingOpenIncident = $issueClass->getTotalIssues($params, 1,1);
		$this->view->totalParkingCloseIncident = $issueClass->getTotalIssues($params, 1,0,1);
		$this->view->totalParkingOpenGlitch = $issueClass->getTotalIssues($params, 2,1);
		$this->view->totalParkingCloseGlitch = $issueClass->getTotalIssues($params, 2,0,1);
		$this->view->totalParkingOpenLostFound = $issueClass->getTotalIssues($params, 3,1);
		$this->view->totalParkingCloseLostFound = $issueClass->getTotalIssues($params, 3,0,1);
		$this->view->totalParkingOpenDefectList = $issueClass->getTotalIssues($params, 4,1);
		$this->view->totalParkingCloseDefectList = $issueClass->getTotalIssues($params, 4,0,1);	


		/***** HOUSEKEEPING *****/

		$params['category_id'] = 2;

		$this->view->totalAllHKIssue = $issueClass->getTotalIssues($params);

		/*** HOUSEKEEPING ISSUE PER TYPE GRAPH ***/
		$this->view->totalHKIncident = $issueClass->getTotalIssues($params, 1);
		$this->view->totalHKGlitch = $issueClass->getTotalIssues($params, 2);
		$this->view->totalHKLostFound = $issueClass->getTotalIssues($params, 3);
		$this->view->totalHKDefectList = $issueClass->getTotalIssues($params, 4);
		$this->view->totalHKNearlyMiss = $issueClass->getTotalIssues($params, 12);
		$this->view->totalHKUnsafeCondition = $issueClass->getTotalIssues($params, 11);


		/*** HOUSEKEEPING OPEN & CLOSE ISSUE ***/
		$this->view->totalHKOpenIncident = $issueClass->getTotalIssues($params, 1,1);
		$this->view->totalHKCloseIncident = $issueClass->getTotalIssues($params, 1,0,1);
		$this->view->totalHKOpenGlitch = $issueClass->getTotalIssues($params, 2,1);
		$this->view->totalHKCloseGlitch = $issueClass->getTotalIssues($params, 2,0,1);
		$this->view->totalHKOpenLostFound = $issueClass->getTotalIssues($params, 3,1);
		$this->view->totalHKCloseLostFound = $issueClass->getTotalIssues($params, 3,0,1);
		$this->view->totalHKOpenDefectList = $issueClass->getTotalIssues($params, 4,1);
		$this->view->totalHKCloseDefectList = $issueClass->getTotalIssues($params, 4,0,1);	


		/***** ENGINEERING *****/

		$params['category_id'] = 6;

		$this->view->totalAllEngineeringIssue = $issueClass->getTotalIssues($params);

		/*** HOUSEKEEPING ISSUE PER TYPE GRAPH ***/
		$this->view->totalEngineeringIncident = $issueClass->getTotalIssues($params, 1);
		$this->view->totalEngineeringGlitch = $issueClass->getTotalIssues($params, 2);
		$this->view->totalEngineeringLostFound = $issueClass->getTotalIssues($params, 3);
		$this->view->totalEngineeringDefectList = $issueClass->getTotalIssues($params, 4);
		$this->view->totalEngineeringNearlyMiss = $issueClass->getTotalIssues($params, 12);
		$this->view->totalEngineeringUnsafeCondition = $issueClass->getTotalIssues($params, 11);


		/*** HOUSEKEEPING OPEN & CLOSE ISSUE ***/
		$this->view->totalEngineeringOpenIncident = $issueClass->getTotalIssues($params, 1,1);
		$this->view->totalEngineeringCloseIncident = $issueClass->getTotalIssues($params, 1,0,1);
		$this->view->totalEngineeringOpenGlitch = $issueClass->getTotalIssues($params, 2,1);
		$this->view->totalEngineeringCloseGlitch = $issueClass->getTotalIssues($params, 2,0,1);
		$this->view->totalEngineeringOpenLostFound = $issueClass->getTotalIssues($params, 3,1);
		$this->view->totalEngineeringCloseLostFound = $issueClass->getTotalIssues($params, 3,0,1);
		$this->view->totalEngineeringOpenDefectList = $issueClass->getTotalIssues($params, 4,1);
		$this->view->totalEngineeringCloseDefectList = $issueClass->getTotalIssues($params, 4,0,1);	


		/***** UTILITY *****/

		$params['category_id'] = 4;

		$this->view->totalAllUtilityIssue = $issueClass->getTotalIssues($params);

		/*** HOUSEKEEPING ISSUE PER TYPE GRAPH ***/
		$this->view->totalUtilityIncident = $issueClass->getTotalIssues($params, 1);
		$this->view->totalUtilityGlitch = $issueClass->getTotalIssues($params, 2);
		$this->view->totalUtilityLostFound = $issueClass->getTotalIssues($params, 3);
		$this->view->totalUtilityDefectList = $issueClass->getTotalIssues($params, 4);
		$this->view->totalUtilityyNearlyMiss = $issueClass->getTotalIssues($params, 12);
		$this->view->totalUtilityUnsafeCondition = $issueClass->getTotalIssues($params, 11);


		/*** HOUSEKEEPING OPEN & CLOSE ISSUE ***/
		$this->view->totalUtilityOpenIncident = $issueClass->getTotalIssues($params, 1,1);
		$this->view->totalUtilityCloseIncident = $issueClass->getTotalIssues($params, 1,0,1);
		$this->view->totalUtilityOpenGlitch = $issueClass->getTotalIssues($params, 2,1);
		$this->view->totalUtilityCloseGlitch = $issueClass->getTotalIssues($params, 2,0,1);
		$this->view->totalUtilityOpenLostFound = $issueClass->getTotalIssues($params, 3,1);
		$this->view->totalUtilityCloseLostFound = $issueClass->getTotalIssues($params, 3,0,1);
		$this->view->totalUtilityOpenDefectList = $issueClass->getTotalIssues($params, 4,1);
		$this->view->totalUtilityCloseDefectList = $issueClass->getTotalIssues($params, 4,0,1);	
		
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
		$sec_total_issue = $this->config->paths->html."/stat/".'sec_total_issue_'.$curdate.'.png';
		$sec_type_issue = $this->config->paths->html."/stat/".'sec_type_issue_'.$curdate.'.png';
		$sec_open_close = $this->config->paths->html."/stat/".'sec_open_close_'.$curdate.'.png';
		$saf_total_issue = $this->config->paths->html."/stat/".'saf_total_issue_'.$curdate.'.png';
		$saf_type_issue = $this->config->paths->html."/stat/".'saf_type_issue_'.$curdate.'.png';
		$saf_open_close = $this->config->paths->html."/stat/".'saf_open_close_'.$curdate.'.png';
		$park_total_issue = $this->config->paths->html."/stat/".'park_total_issue_'.$curdate.'.png';
		$park_type_issue = $this->config->paths->html."/stat/".'park_type_issue_'.$curdate.'.png';
		$park_open_close = $this->config->paths->html."/stat/".'park_open_close_'.$curdate.'.png';
		$hk_total_issue = $this->config->paths->html."/stat/".'hk_total_issue_'.$curdate.'.png';
		$hk_type_issue = $this->config->paths->html."/stat/".'hk_type_issue_'.$curdate.'.png';
		$hk_open_close = $this->config->paths->html."/stat/".'hk_open_close_'.$curdate.'.png';
		$eng_total_issue = $this->config->paths->html."/stat/".'eng_total_issue_'.$curdate.'.png';
		$eng_type_issue = $this->config->paths->html."/stat/".'eng_type_issue_'.$curdate.'.png';
		$eng_open_close = $this->config->paths->html."/stat/".'eng_open_close_'.$curdate.'.png';
		$uti_total_issue = $this->config->paths->html."/stat/".'uti_total_issue_'.$curdate.'.png';
		$uti_type_issue = $this->config->paths->html."/stat/".'uti_type_issue_'.$curdate.'.png';
		$uti_open_close = $this->config->paths->html."/stat/".'uti_open_close_'.$curdate.'.png';

		// remove "data:image/png;base64,"
		$all_total_issue_uri =  str_replace("data:image/png;base64","", $params['all_total_issue']);
		file_put_contents($all_total_issue, base64_decode($all_total_issue_uri));
		$all_type_issue_uri =  str_replace("data:image/png;base64","", $params['all_type_issue']);
		file_put_contents($all_type_issue, base64_decode($all_type_issue_uri));
		$all_open_close_uri =  str_replace("data:image/png;base64","", $params['all_open_close']);
		file_put_contents($all_open_close, base64_decode($all_open_close_uri));

		$sec_total_issue_uri =  str_replace("data:image/png;base64","", $params['sec_total_issue']);
		file_put_contents($sec_total_issue, base64_decode($sec_total_issue_uri));
		$sec_type_issue_uri =  str_replace("data:image/png;base64","", $params['sec_type_issue']);
		file_put_contents($sec_type_issue, base64_decode($sec_type_issue_uri));
		$sec_open_close_uri =  str_replace("data:image/png;base64","", $params['sec_open_close']);
		file_put_contents($sec_open_close, base64_decode($sec_open_close_uri));

		$saf_total_issue_uri =  str_replace("data:image/png;base64","", $params['saf_total_issue']);
		file_put_contents($saf_total_issue, base64_decode($saf_total_issue_uri));
		$saf_type_issue_uri =  str_replace("data:image/png;base64","", $params['saf_type_issue']);
		file_put_contents($saf_type_issue, base64_decode($saf_type_issue_uri));
		$saf_open_close_uri =  str_replace("data:image/png;base64","", $params['saf_open_close']);
		file_put_contents($saf_open_close, base64_decode($saf_open_close_uri));

		$park_total_issue_uri =  str_replace("data:image/png;base64","", $params['park_total_issue']);
		file_put_contents($park_total_issue, base64_decode($park_total_issue_uri));
		$park_type_issue_uri =  str_replace("data:image/png;base64","", $params['park_type_issue']);
		file_put_contents($park_type_issue, base64_decode($park_type_issue_uri));
		$park_open_close_uri =  str_replace("data:image/png;base64","", $params['park_open_close']);
		file_put_contents($park_open_close, base64_decode($park_open_close_uri));

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

		$uti_total_issue_uri =  str_replace("data:image/png;base64","", $params['uti_total_issue']);
		file_put_contents($uti_total_issue, base64_decode($uti_total_issue_uri));
		$uti_type_issue_uri =  str_replace("data:image/png;base64","", $params['uti_type_issue']);
		file_put_contents($uti_type_issue, base64_decode($uti_type_issue_uri));
		$uti_open_close_uri =  str_replace("data:image/png;base64","", $params['uti_open_close']);
		file_put_contents($uti_open_close, base64_decode($uti_open_close_uri));



		echo $curdate;
	}
	

	function exporttopdfAction() {
		$params = $this->_getAllParams();

		$curdate = $params['cd'];
		$start_date = date("j M Y", mktime(0, 0, 0, substr($params['sd'],4,2), substr($params['sd'],6,2), substr($params['sd'],0,4)));
		$end_date = date("j M Y", mktime(0, 0, 0, substr($params['ed'],4,2), substr($params['ed'],6,2), substr($params['ed'],0,4)));

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
		$pdf->SetFillColor(92,138,138);
		$pdf->Cell(0,6,'  All Department',0,1,'L', true);
		$pdf->Image($this->config->paths->html."/stat/".'all_total_issue_'.$curdate.'.png',10,37);
		$pdf->Image($this->config->paths->html."/stat/".'all_type_issue_'.$curdate.'.png',43,37);
		$pdf->Image($this->config->paths->html."/stat/".'all_open_close_'.$curdate.'.png',120,37);
		$pdf->Ln(55);
		$pdf->Cell(0,6,'  Security',0,1,'L', true);
		$pdf->Image($this->config->paths->html."/stat/".'sec_total_issue_'.$curdate.'.png',10,98);
		$pdf->Image($this->config->paths->html."/stat/".'sec_type_issue_'.$curdate.'.png',43,98);
		$pdf->Image($this->config->paths->html."/stat/".'sec_open_close_'.$curdate.'.png',120,98);
		$pdf->Ln(55);
		$pdf->Cell(0,6,'  Safety',0,1,'L', true);
		$pdf->Image($this->config->paths->html."/stat/".'saf_total_issue_'.$curdate.'.png',10,159);
		$pdf->Image($this->config->paths->html."/stat/".'saf_type_issue_'.$curdate.'.png',43,159);
		$pdf->Image($this->config->paths->html."/stat/".'saf_open_close_'.$curdate.'.png',120,159);
		$pdf->Ln(55);
		$pdf->Cell(0,6,'  Parking & Traffic',0,1,'L', true);
		$pdf->Image($this->config->paths->html."/stat/".'park_total_issue_'.$curdate.'.png',10,220);
		$pdf->Image($this->config->paths->html."/stat/".'park_type_issue_'.$curdate.'.png',43,220);
		$pdf->Image($this->config->paths->html."/stat/".'park_open_close_'.$curdate.'.png',120,220);
		$pdf->Ln(75);
		$pdf->Cell(0,6,'  Housekeeping',0,1,'L', true);
		$pdf->Image($this->config->paths->html."/stat/".'hk_total_issue_'.$curdate.'.png',10,15);
		$pdf->Image($this->config->paths->html."/stat/".'hk_type_issue_'.$curdate.'.png',43,15);
		$pdf->Image($this->config->paths->html."/stat/".'hk_open_close_'.$curdate.'.png',120,15);
		$pdf->Ln(55);
		$pdf->Cell(0,6,'  Engineering',0,1,'L', true);
		$pdf->Image($this->config->paths->html."/stat/".'eng_total_issue_'.$curdate.'.png',10,76);
		$pdf->Image($this->config->paths->html."/stat/".'eng_type_issue_'.$curdate.'.png',43,76);
		$pdf->Image($this->config->paths->html."/stat/".'eng_open_close_'.$curdate.'.png',120,76);
		$pdf->Ln(55);
		$pdf->Cell(0,6,'  Utility',0,1,'L', true);
		$pdf->Image($this->config->paths->html."/stat/".'uti_total_issue_'.$curdate.'.png',10,137);
		$pdf->Image($this->config->paths->html."/stat/".'uti_type_issue_'.$curdate.'.png',43,137);
		$pdf->Image($this->config->paths->html."/stat/".'uti_open_close_'.$curdate.'.png',120,137);
		$pdf->Ln(55);
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
			print_r($d); exit();
			if($d['category_id'] > 0)
			{
				$dept[$d['']];
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


				}
			}
		}
		$this->view->users = $users;

		Zend_Loader::LoadClass('issueClass', $this->modelDir);
		$issueClass = new issueClass();

		$this->view->userIssues = $issueClass->getUserIssuesStatistic($params['start_date'], $params['end_date'], 10);

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
		}
		arsort($userComments);
		$this->view->userComments = $userComments;

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

	function exportuserstatistictopdfAction() {
		$params = $this->_getAllParams();

		$curdate = $params['cd'];
		$start_date = date("j M Y", mktime(0, 0, 0, substr($params['sd'],4,2), substr($params['sd'],6,2), substr($params['sd'],0,4)));
		$end_date = date("j M Y", mktime(0, 0, 0, substr($params['ed'],4,2), substr($params['ed'],6,2), substr($params['ed'],0,4)));


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
			}
		}

		Zend_Loader::LoadClass('issueClass', $this->modelDir);
		$issueClass = new issueClass();

		$userIssues = $issueClass->getUserIssuesStatistic($params['sd'], $params['ed'], 10);

		Zend_Loader::LoadClass('userClass', $this->modelDir);
		$userClass = new userClass();

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
		foreach($users2 as $user2)
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
		}
		arsort($userComments);

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
		$pdf->SetFillColor(92,138,138);
		$pdf->Cell(0,7,'  Top Ten Users By Login',0,1,'L', true);
		$pdf->SetFillColor(92,138,138);
		$pdf->SetTextColor(255);
		$pdf->SetDrawColor(153);
		$pdf->SetLineWidth(.3);
		$pdf->SetFont('','B');
		$pdf->Ln(3);
		// Header
		$pdf->Cell(15,6,'No',1,0,'C',true);
		$pdf->Cell(80,6,'Name',1,0,'C',true);
		$pdf->Cell(40,6,'Total Login',1,0,'C',true);
		$pdf->Cell(55,6,'Last Login',1,0,'C',true);
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
			foreach($users as $user)
			{
				$pdf->Cell(15,6,$i,'LR',0,'R',$fill);
				$pdf->Cell(80,6,$user['name'],'LR',0,'L',$fill);
				$pdf->Cell(40,6,number_format($user['total_login']),'LR',0,'R',$fill);
				$pdf->Cell(55,6,$user['last_login'],'LR',0,'C',$fill);
				$pdf->Ln();
				$fill = !$fill;
				$i++;
			}
		}
		// Closing line
		$pdf->Cell(190,0,'','T');
		$pdf->Ln(10);

		/*** TOP TEN USERS BY SUBMITTING ISSUE ***/
		$pdf->SetFont('Arial','B',10);
		$pdf->SetTextColor(255);
		$pdf->SetFillColor(92,138,138);
		$pdf->Cell(90,7,'  Top Ten Users By Submitting Issue','LR',0,'L', true);
		$pdf->SetFillColor(255);
		$pdf->Cell(10,7,' ','LR',0,'L', true);
		$pdf->SetFillColor(92,138,138);
		$pdf->Cell(90,7,'  Top Ten Users By Comments','LR',0,'L', true);
		$pdf->SetFillColor(92,138,138);
		$pdf->SetTextColor(255);
		$pdf->SetDrawColor(153);
		$pdf->SetLineWidth(.3);
		$pdf->SetFont('','B');
		$pdf->Ln(10);
		// Header
		$pdf->Cell(10,6,'No',1,0,'C',true);
		$pdf->Cell(55,6,'Name',1,0,'C',true);
		$pdf->Cell(25,6,'Total Issues',1,0,'C',true);
		$pdf->SetFillColor(255);
		$pdf->Cell(10,7,' ','LR',0,'L', true);
		$pdf->SetFillColor(92,138,138);
		$pdf->Cell(10,6,'No',1,0,'C',true);
		$pdf->Cell(55,6,'Name',1,0,'C',true);
		$pdf->Cell(25,6,'Total Issues',1,0,'C',true);
		$pdf->Ln();
		// Color and font restoration
		$pdf->SetFillColor(238,238,238);
		$pdf->SetTextColor(0);
		$pdf->SetFont('');
		// Data
		$fill = false;
		if(!empty($userIssues) || !empty($userComments))
		{
			$j = 0;
			foreach($userComments as  $key => $value) 
			{
				if($j < 10)
				{
					$pdf->Cell(10,6,($j+1),'LR',0,'R',$fill);
					$pdf->Cell(55,6,$userIssues[$j]['name'],'LR',0,'L',$fill);
					$pdf->Cell(25,6,$userIssues[$j]['total_issues'],'LR',0,'R',$fill);
					$pdf->Cell(10,6,' ','LR',0,'L', false);
					$pdf->Cell(10,6,($j+1),'LR',0,'R',$fill);
					$pdf->Cell(55,6,$key,'LR',0,'L',$fill);
					$pdf->Cell(25,6,$value,'LR',0,'R',$fill);
					$pdf->Ln();
					$fill = !$fill;
				}
				$j++;
			}
		}
		// Closing line
		$pdf->Cell(90,0,'','T');
		$pdf->Cell(10,0,'', 0);
		$pdf->Cell(90,0,'','T');
		$pdf->Ln(10);		
	
		$pdf->Output();
	}
}
?>
