<?php
require_once('actionControllerBase.php');

class SecurityController extends actionControllerBase
{
	public function addAction() {
		$this->view->ident = $this->ident;
		$this->view->title = "Add Security Report";
		
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
			
			$securityClass->deleteSpecificReportBySecurityId($security_id);
			
			$securityClass->deleteDefectListBySecurityId($security_id);
			if(!empty($params['area-defect-list']))
			{
				foreach($params['area-defect-list'] as $area)
				{
					$dt['security_id'] = $security_id;
					$dt['sdl_id'] = $params['id-defect-list'][$i];
					$dt['area'] = $area;
					$dt['detail'] = $params['detil-defect-list'][$i];
					$dt['status'] = $dt['follow_up'] = $params['followup-defect-list'][$i];
					$dt['issue_type'] = '4';
					$dt['issue_id'] = '0';
					if(empty($dt['area'] ) && empty($dt['detail']) && empty($dt['status'])) ;
					else {
						$securityClass->addDefectList($dt);
						$securityClass->addSpecificReport($dt);
					}
					$i++;
				}
			}
			
			$j = 0;
			$securityClass->deleteIncidentBySecurityId($security_id);
			if(!empty($params['id-incident']))
			{
				foreach($params['id-incident'] as $id_incident)
				{
					$dt2['security_id'] = $security_id;
					$dt2['issue_id'] = $id_incident;
					$dt2['status'] = $params['status-incident'][$j];
					$dt2['issue_type'] = '1';
					if(!empty($dt2['issue_id'] ))
					{
						$securityClass->addIncident($dt2);					
						$securityClass->addSpecificReport($dt2);
					}
					$j++;
				}
			}
			
			$k = 0;
			$securityClass->deleteGlitchBySecurityId($security_id);
			if(!empty($params['id-glitch']))
			{
				foreach($params['id-glitch'] as $id_glitch)
				{
					$dt3['security_id'] = $security_id;
					$dt3['issue_id'] = $id_glitch;
					$dt3['status'] = $params['status-glitch'][$k];
					$dt3['issue_type'] = '2';
					if(!empty($dt3['issue_id']))
					{
						$securityClass->addGlitch($dt3);					
						$securityClass->addSpecificReport($dt3);
					}
					$k++;
				}
			}
			
			$l = 0;
			$securityClass->deleteLostFoundBySecurityId($security_id);
			if(!empty($params['id-lost-found']))
			{
				foreach($params['id-lost-found'] as $id_lost_found)
				{
					$dt4['security_id'] = $security_id;
					$dt4['issue_id'] = $id_lost_found;
					$dt4['status'] = $params['status-lost-found'][$l];
					$dt4['issue_type'] = '3';
					if(!empty($dt4['issue_id']))
					{
						$securityClass->addLostFound($dt4);
						$securityClass->addSpecificReport($dt4);
					}
					$l++;
				}
			}
			
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
			if($params['start'] < (floor($totalSpvReport['total']/10)*10))
			{
				$this->view->nextUrl = "/default/security/view/start/".($params['start']+$params['pagesize']);
				$this->view->lastPageUrl = "/default/security/view/start/".(floor($totalSpvReport['total']/10)*10);
			}
		}
		
    	$this->renderTemplate('view_daily_security.tpl');  
	}
	
	public function editAction() {
		$this->view->ident = $this->ident;
		$params = $this->_getAllParams();
		Zend_Loader::LoadClass('securityClass', $this->modelDir);
		$securityClass = new securityClass();
		$security = $securityClass->getSecurityReportById($params['id']);
		$security['defect_list'] = $securityClass->getSpecificReportBySecurityIdIssueType($params['id'], '4');
		$security['incident'] = $securityClass->getSpecificReportBySecurityIdIssueType($params['id'], '1');
		$security['glitch'] = $securityClass->getSpecificReportBySecurityIdIssueType($params['id'], '2');
		$security['lost_found'] = $securityClass->getSpecificReportBySecurityIdIssueType($params['id'], '3');
    	/*$security['defect_list'] = $securityClass->getDefectListBySecurityId($params['id']);
		$security['incident'] = $securityClass->getIncidentBySecurityId($params['id']);
		$security['glitch'] = $securityClass->getGlitchBySecurityId($params['id']);
		$security['lost_found'] = $securityClass->getLostFoundBySecurityId($params['id']);*/
		$datetime = explode(" ",$security['created_date']);
		$date = explode("-",$datetime[0]);
		$r_date = mktime(0, 0, 0, intval($date[1]), intval($date[2]), intval($date[0]));
		$security['report_date'] = date("l, j F Y", $r_date);
		$this->view->security = $security;
		
		Zend_Loader::LoadClass('shiftClass', $this->modelDir);
		$shiftClass = new shiftClass();
		$this->view->shift = $shiftClass->getShift();
		
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
		
		$this->view->title = "Edit Daily Security Report";
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
		
		self::viewAction();
	}
	
	public function exporttopdfAction() {
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
		<title>Security Daily Report</title>
		 
		</head>
		<body>
		<h1>Daily Report (Security Report)</h1>
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
		
		$this->_response->setRedirect($this->baseUrl.'/default/security/page2/id/'.$params['report_id']);
		$this->_response->sendResponse();
		exit();
	}
	
	public function exporttopdf2Action() {
		require('fpdf/html_table.php');
		$params = $this->_getAllParams();
		Zend_Loader::LoadClass('securityClass', $this->modelDir);
		$securityClass = new securityClass();
		$security = $securityClass->getSecurityReportById($params['id']);
		$datetime = explode(" ",$security['created_date']);
		$date = explode("-",$datetime[0]);
		$r_date = mktime(0, 0, 0, intval($date[1]), intval($date[2]), intval($date[0]));
		$report_date = date("l, j F Y", $r_date);
		
		
		$defect_list = $securityClass->getDefectListBySecurityId($params['id']);
		$incident = $securityClass->getIncidentBySecurityId($params['id']);
		$glitch = $securityClass->getGlitchBySecurityId($params['id']);
		$lost_found = $securityClass->getLostFoundBySecurityId($params['id']);

		$pdf = new FPDF();
		$pdf->AddPage();
		$pdf->SetFont('Arial','B',16);
		$pdf->Write(10,'Daily Report (Security Report)');
		$pdf->ln();
		$pdf->SetFont('Arial','B',10);
		$pdf->Write(5,'Security');
		$pdf->ln();
		$pdf->Write(5,'PT. Artisan Wahyu Gandaria City');
		$pdf->ln();
		$pdf->ln();
		
		$pdf->SetFont('Arial','B',12);
		$pdf->Write(10,'1. Date / Shift');
		$pdf->Ln();
		$pdf->SetFont('Arial','',10);
		$pdf->Cell(40,7,'Day / Date',1,0,'L');
		$pdf->Cell(50,7,$report_date,1,0,'L');
		$pdf->Ln();
		$pdf->Cell(40,7,'Shift',1,0,'L');
		$pdf->Cell(50,7,$security['shift'],1,0,'L');
		$pdf->Ln();
		$pdf->Cell(40,7,'',1,0,'L');
		$pdf->Cell(50,7,$security['name'],1,0,'L');
		$pdf->Ln();
		$pdf->ln();
		
		$pdf->SetFont('Arial','B',12);
		$pdf->Write(10,'2. Man Power');
		$pdf->Ln();
		$pdf->SetFont('Arial','',10);
		$pdf->SetFillColor(224,235,255);
		$pdf->SetFont('','B');
		$pdf->Cell(63,7,'In House',1,0,'L',true);
		$pdf->Cell(63,7,'SPD',1,0,'L',true);
		$pdf->Cell(63,7,'ARMY',1,0,'L',true);
		$pdf->Ln();
		$pdf->SetFont('');
		$y = $pdf->GetY();
		$x = $pdf->GetX();
		$pdf->MultiCell(63,6,'Spv : '.$security['supervisor'],'LR');
		$pdf->SetXY($x + 63, $y);
		$pdf->MultiCell(63,6,'Waka : '.$security['chief_spd'],'LR');
		$pdf->SetXY($x + 63 + 63, $y);
		$pdf->MultiCell(63,6,'Chief : '.$security['chief_army'],'LR');
		$pdf->Ln();
		$y = $pdf->GetY();
		$x = $pdf->GetX();
		$pdf->MultiCell(63,6,'Posko : '.$security['staff_posko'],'LR');
		$pdf->SetXY($x + 63, $y);
		$pdf->MultiCell(63,6,'Panwas : '.$security['panwas_spd'],'LR');
		$pdf->SetXY($x + 63 + 63, $y);
		$pdf->MultiCell(63,6,'Panwas : '.$security['panwas_army'],'LR');
		$pdf->Ln();
		$y = $pdf->GetY();
		$x = $pdf->GetX();
		$pdf->MultiCell(63,6,'CCTV : '.$security['staff_cctv'],'LR');
		$pdf->SetXY($x + 63, $y);
		$pdf->MultiCell(63,6,'Danton / Danru : '.$security['danton_spd'],'LR');
		$pdf->SetXY($x + 63 + 63, $y);
		$pdf->MultiCell(63,6,'Danton / Danru : '.$security['danton_army'],'LR');
		$y = $pdf->GetY();
		$x = $pdf->GetX();
		$pdf->MultiCell(63,6,'Safety : '.$security['safety'],'LRB');
		$pdf->SetXY($x + 63, $y);
		$pdf->MultiCell(63,6,'Jumlah : '.$security['jumlah_spd'],'LRB');
		$pdf->SetXY($x + 63 + 63, $y);
		$pdf->MultiCell(63,6,'Jumlah : '.$security['jumlah_army'],'LRB');
		$pdf->Ln();
		$pdf->ln();
		
		$pdf->SetFont('Arial','B',12);
		$pdf->Write(10,'3. Briefing');
		$pdf->ln();
		$pdf->SetFont('');
		$pdf->Write(5,$security['briefing']);
		$pdf->Ln();
		$pdf->Ln();
	
		$pdf->SetFont('Arial','B',12);
		$pdf->Write(10,'4. Defect List');
		$pdf->Ln();
		$pdf->SetFont('Arial','',10);
		$pdf->SetFillColor(224,235,255);
		$pdf->SetFont('','B');
		$pdf->Cell(63,7,'Area',1,0,'L',true);
		$pdf->Cell(63,7,'Details',1,0,'L',true);
		$pdf->Cell(63,7,'Follow up',1,0,'L',true);
		$pdf->Ln();
		$pdf->SetFont('');		
		if(!empty($defect_list))
		{
			foreach($defect_list as $dl)
			{
				$pdf->Cell(63,6,$dl['area'],1,0,'L');
				$pdf->Cell(63,6,$dl['detail'],1,0,'L');
				$pdf->Cell(63,6,$dl['follow_up'],1,0,'L');
				$pdf->Ln();
			}
		}
		$pdf->Ln();
		
		$pdf->SetFont('Arial','B',12);
		$pdf->Write(10,'5. Incident Report');
		$pdf->Ln();
		$pdf->SetFont('Arial','',10);
		$pdf->SetFillColor(224,235,255);
		$pdf->SetFont('','B');
		$pdf->Cell(63,7,'Date & Time',1,0,'L',true);
		$pdf->Cell(63,7,'Description',1,0,'L',true);
		$pdf->Cell(63,7,'Status',1,0,'L',true);
		$pdf->Ln();
		$pdf->SetFont('');		
		if(!empty($incident))
		{
			foreach($incident as $i)
			{
				$issue_date_time = explode(" ",$i['issue_date']);
				$issue_datetime = date("j M Y", strtotime($issue_date_time[0]))." ".$issue_date_time[1];
				$pdf->Cell(63,6,$issue_datetime,1,0,'L');
				$pdf->Cell(63,6,$i['description'],1,0,'L');
				$pdf->Cell(63,6,$i['status'],1,0,'L');
				$pdf->Ln();
			}
		}
		$pdf->Ln();
		
		$pdf->SetFont('Arial','B',12);
		$pdf->Write(10,'6. Glitch');
		$pdf->Ln();
		$pdf->SetFont('Arial','',10);
		$pdf->SetFillColor(224,235,255);
		$pdf->SetFont('','B');
		$pdf->Cell(63,7,'Date & Time',1,0,'L',true);
		$pdf->Cell(63,7,'Description',1,0,'L',true);
		$pdf->Cell(63,7,'Status',1,0,'L',true);
		$pdf->Ln();
		$pdf->SetFont('');		
		if(!empty($glitch))
		{
			foreach($glitch as $g)
			{
				$issue_date_time = explode(" ",$g['issue_date']);
				$issue_datetime = date("j M Y", strtotime($issue_date_time[0]))." ".$issue_date_time[1];
				$pdf->Cell(63,6,$issue_datetime,1,0,'L');
				$pdf->Cell(63,6,$g['description'],1,0,'L');
				$pdf->Cell(63,6,$g['status'],1,0,'L');
				$pdf->Ln();
			}
		}
		$pdf->Ln();
		
		$pdf->SetFont('Arial','B',12);
		$pdf->Write(10,'7. Lost & Found');
		$pdf->Ln();
		$pdf->SetFont('Arial','',10);
		$pdf->SetFillColor(224,235,255);
		$pdf->SetFont('','B');
		$pdf->Cell(63,7,'Date & Time',1,0,'L',true);
		$pdf->Cell(63,7,'Description',1,0,'L',true);
		$pdf->Cell(63,7,'Status',1,0,'L',true);
		$pdf->Ln();
		$pdf->SetFont('');		
		if(!empty($lost_found))
		{
			foreach($lost_found as $lf)
			{
				$issue_date_time = explode(" ",$lf['issue_date']);
				$issue_datetime = date("j M Y", strtotime($issue_date_time[0]))." ".$issue_date_time[1];
				$pdf->Cell(63,6,$issue_datetime,1,0,'L');
				$pdf->Cell(63,6,$lf['description'],1,0,'L');
				$pdf->Cell(63,6,$lf['status'],1,0,'L');
				$pdf->Ln();
			}
		}
		$pdf->Ln();
		
		$pdf->Output();
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
				$commentText .= '<div style="border-bottom:1px solid #ccc; text-align:left; clear:both; margin-bottom:5px; padding-bottom:5px;">'.$comment['comment'].'<br/><div style="font-size:10px; color:#aaa; text-align:right;">'.$comment['comment_date']."</div></div>";
			}
		}
		
		echo $commentText;	
	}
	
	function addcommentAction() {
		$params = $this->_getAllParams();
		
		$commentsTable = $this->loadModel('securitycomments');
		$params['user_id'] = $this->ident['user_id'];
		$params['site_id'] = $this->site_id;
		$commentsTable->addComment($params);
		
		/*Zend_Loader::LoadClass('securityClass', $this->modelDir);
		$securityClass = new securityClass();
		$security = $securityClass->getSecurityReportById($params['security_id']);
		
		$datetime = explode(" ",$security['created_date']);
		$date = explode("-",$datetime[0]);
		$r_date = mktime(0, 0, 0, intval($date[1]), intval($date[2]), intval($date[0]));
		$report_date = date("l, j F Y", $r_date);*/
		
		$categoryClass = $this->loadModel('category');
		$category = $categoryClass->getCategoryById('1');	
			
		/*if($this->site_id < 4)	$botToken = '476346623:AAFlB9X5-bShAkdTK9ziNDrfZ0TCePXl2cY';
		else $botToken = '716005387:AAFhUdDGlXjK5YFMYqwx_lCpqpp8760S_TE';*/
		$botToken = '476346623:AAFlB9X5-bShAkdTK9ziNDrfZ0TCePXl2cY';
		$website="https://api.telegram.org/bot".$botToken;
		//$chatId=$category['telegram_channel_id'];  //Receiver Chat Id 
		$chatId=$category['site_'.$this->site_id];  //Receiver Chat Id 
		$txt = '[NEW COMMENT] 
'.$this->ident['name']." said: ".$params['comment'].'

[SECURITY REPORT]
Report Date : '.$params['report_date'];
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
		echo json_encode($params);
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
					$comment_content .= '<div style="border-bottom:1px solid #ccc; text-align:left; clear:both; margin-bottom:5px; padding-bottom:5px;"><strong>'.$comment['name'].' said :</strong> '.$comment['comment'].'<br/><div style="font-size:10px; color:#aaa; text-align:right;">'.$comment['comment_date']."</div></div>";
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
		$this->view->issue_type = $issueTypeTable->getIssueType('1');
		
		$settingTable = $this->loadModel('setting');
		$this->view->setting = $settingTable->getOtherSetting();
		
		$equipmentTable = $this->loadModel('equipment');
		$this->view->equipments = $equipmentTable->getSecurityEquipments();
		
		Zend_Loader::LoadClass('vendorClass', $this->modelDir);
		$vendorClass = new vendorClass();
		$this->view->vendor = $vendorClass->getVendor($this->site_id);

		$this->view->title = "Add Chief Security Report";
		$this->renderTemplate('form_chief_security.tpl'); 
	}
	
	public function savechiefreportAction() {
		$params = $this->_getAllParams();
		$params['user_id'] = $this->ident['user_id'];
		Zend_Loader::LoadClass('securityClass', $this->modelDir);
		$securityClass = new securityClass();
		
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

		$securityClass->deleteSpecificReportByChiefSecurityId($params['chief_security_report_id']);
		$securityClass->deleteSpecificReportBySecurityId($params['morning_security_report_id']);
		$securityClass->deleteSpecificReportBySecurityId($params['afternoon_security_report_id']);
		$securityClass->deleteSpecificReportBySecurityId($params['night_security_report_id']);
		/*$securityClass->deleteDefectListBySecurityId($params['morning_security_report_id']);
		$securityClass->deleteIncidentBySecurityId($params['morning_security_report_id']);
		$securityClass->deleteGlitchBySecurityId($params['morning_security_report_id']);
		$securityClass->deleteLostFoundBySecurityId($params['morning_security_report_id']);
		$securityClass->deleteDefectListBySecurityId($params['afternoon_security_report_id']);
		$securityClass->deleteIncidentBySecurityId($params['afternoon_security_report_id']);
		$securityClass->deleteGlitchBySecurityId($params['afternoon_security_report_id']);
		$securityClass->deleteLostFoundBySecurityId($params['afternoon_security_report_id']);
		$securityClass->deleteDefectListBySecurityId($params['night_security_report_id']);
		$securityClass->deleteIncidentBySecurityId($params['night_security_report_id']);
		$securityClass->deleteGlitchBySecurityId($params['night_security_report_id']);
		$securityClass->deleteLostFoundBySecurityId($params['night_security_report_id']);*/
		if(!empty($params['issue_type']))
		{		
			$j = 0;
			foreach($params['issue_type'] as $issue_type)
			{
				$dt=array();
				/*if($issue_type == 1) {
					$dt['security_id'] = $params['security-id-sr'][$j];
					$dt['issue_id'] = $params['id-issue-sr'][$j];
					$dt['status'] = $params['status-sr'][$j];
					$securityClass->addIncident($dt);
				}
				elseif($issue_type == 2) {
					$dt['security_id'] = $params['security-id-sr'][$j];
					$dt['issue_id'] = $params['id-issue-sr'][$j];
					$dt['status'] = $params['status-sr'][$j];
					$securityClass->addGlitch($dt);
				}
				elseif($issue_type == 3) {
					$dt['security_id'] = $params['security-id-sr'][$j];
					$dt['issue_id'] = $params['id-issue-sr'][$j];
					$dt['status'] = $params['status-sr'][$j];
					$securityClass->addLostFound($dt);
				}
				elseif($issue_type == 4) {
					$dt['security_id'] = $params['security-id-sr'][$j];
					$dt['issue_id'] = $params['id-issue-sr'][$j];
					$dt['area'] = $params['time-sr'][$j];
					$dt['detail'] = $params['description-sr'][$j];
					$dt['follow_up'] = $params['status-sr'][$j];
					$securityClass->addDefectList($dt);
				}
				else {*/
					$dt['chief_security_report_id'] = $params['chief_security_report_id'];
					$dt['issue_type'] = $issue_type;
					$dt['time'] = $params['time-sr'][$j];
					$dt['detail'] = $params['description-sr'][$j];
					$dt['status'] = $params['status-sr'][$j];
					$dt['area'] = $params['time-sr'][$j];
					$dt['security_id'] = $params['security-id-sr'][$j];
					$dt['issue_id'] = $params['id-issue-sr'][$j];
					$securityClass->addSpecificReport($dt);
				//}
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
					$dt3[$m] = $securityClass->getChiefAttachmentById($params['attachment_id'][$m]);
					if(!empty($description)) $dt3[$m]['description'] = $description;
				}
				else
				{
					$dt3[$m]['site_id'] = $this->site_id;
					$dt3[$m]['report_id'] = $params['chief_security_report_id'];
					$dt3[$m]['description'] = $description;
					
				}	
				if(!empty($_FILES["attachment_file"]['name'][$m]))
				{	
					$dt3[$m]['attachment'] = $_FILES["attachment_file"];
				}
				$m++;
			}			
			$securityClass->deleteChiefAttachmentByReportId($params['chief_security_report_id']);
			$m=0;
			foreach($dt3 as $dt)
			{
				$attachment = $dt['attachment'];
				unset($dt['attachment']);
				$attachment_id = $securityClass->addChiefAttachment($dt);
				if($attachment_id > 0)
				{
					if(!empty($attachment['name'][$m]))
					{
						$ext = explode(".",$attachment['name'][$m]);
						$filename = $attachment_id."_chief.".$ext[count($ext)-1];
						if(move_uploaded_file($attachment["tmp_name"][$m], $this->config->paths->html."/attachment/security/".$filename))
							$securityClass->updateChiefAttachment($attachment_id,'filename', $filename);
					}
				}
				$m++;
			}			
		}*/
		
		$this->_response->setRedirect($this->baseUrl.'/default/security/chiefpage2/id/'.$params['chief_security_report_id']);
		$this->_response->sendResponse();
		exit();
	}
	
	public function chiefpage2Action() {
		$params = $this->_getAllParams();
		
		Zend_Loader::LoadClass('securityClass', $this->modelDir);
		$securityClass = new securityClass();
		
		$security = $securityClass->getSecurityReportByChiefId($params['id']);		
		$datetime = explode(" ",$security[0]['created_date']);
		$date = explode("-",$datetime[0]);
		$r_date = mktime(0, 0, 0, intval($date[1]), intval($date[2]), intval($date[0]));
		$security['created_date'] = date("l, j F Y", $r_date);
		$security['report_date'] = $datetime[0];
		$this->view->security = $security;
		
		$settingTable = $this->loadModel('setting');
		$this->view->setting = $settingTable->getOtherSetting();
		
		if(!empty($params['id']))
			$this->view->attachment = $securityClass->getChiefAttachments($params['id']);
		
		$attachmentMorning = $attachmentAfternoon = $attachmentNight = array();
		if(!empty($security))
		{
			$i = 0;
			foreach($security as $sec)
			{
				$attachmentSpv[$i] =  $securityClass->getSpvAttachments($sec['security_id']);
				$i++;
			}			
			$this->view->attachmentSpv = array_merge($attachmentSpv[0], $attachmentSpv[1], $attachmentSpv[2]);
		}
		
		$this->renderTemplate('form_chief_security2.tpl'); 
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
				$this->view->prevUrl = "/default/security/viewchiefreport/view/start/".($params['start']-$params['pagesize']);
			}
			if($params['start'] < (floor($totalChiefReport['total']/10)*10))
			{
				$this->view->nextUrl = "/default/security/viewchiefreport/start/".($params['start']+$params['pagesize']);
				$this->view->lastPageUrl = "/default/security/viewchiefreport/start/".(floor($totalChiefReport['total']/10)*10);
			}
		}
		
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
		$this->view->issue_type = $issueTypeTable->getIssueType('1');
		
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
		$this->view->title = "Edit Chief Security Report";
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
				$commentText .= '<div style="border-bottom:1px solid #ccc; text-align:left; clear:both; margin-bottom:5px; padding-bottom:5px;"><strong>'.$comment['name'].' said : </strong>'.$comment['comment'].'<br/><div style="font-size:10px; color:#aaa; text-align:right;">'.$comment['comment_date']."</div></div>";
			}
		}
		
		echo $commentText;	
	}
	
	function addchiefcommentAction() {
		$params = $this->_getAllParams();
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
Date : '.$report_date;
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
					$comment_content .= '<div style="border-bottom:1px solid #ccc; text-align:left; clear:both; margin-bottom:5px; padding-bottom:5px;"><strong>'.$comment['name'].' said : </strong>'.$comment['comment'].'<br/><div style="font-size:10px; color:#aaa; text-align:right;">'.$comment['comment_date']."</div></div>";
				}
				$data[$i]['comment'] = $comment_content;
			}
			$i++;
		}
				
		echo json_encode($data);
	}
	
	public function exportchiefreporttopdfAction() {
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
		$attachment = array_merge($attachment, $attachmentMorning, $attachmentAfternoon, $attachmentNight);
		
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
		<title>Chief Security Report</title>
		 
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
		$pdf->Output("sample2.pdf","I");

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
		
		$this->_response->setRedirect($this->baseUrl.'/default/security/chiefpage2/id/'.$params['report_id']);
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
		
		$this->_response->setRedirect($this->baseUrl.'/default/security/chiefpage2/id/'.$params['report_id']);
		$this->_response->sendResponse();
		exit();
	}
}

?>