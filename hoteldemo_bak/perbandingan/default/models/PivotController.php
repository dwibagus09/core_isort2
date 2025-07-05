<?php

require_once('actionControllerBase.php');
require_once('Zend/Json.php');

class PivotController extends actionControllerBase
{

	public function indexAction()
	{	
		if($this->showSecurityPivotChart || $this->showSafetyPivotChart || $this->showParkingPivotChart)
		{
			$params = $this->_getAllParams();

			if($params['c'] == 1) $table = "Security";
			elseif($params['c'] == 3) $table = "Safety";
			elseif($params['c'] == 5) $table = "Parking &amp; Traffic";

			$this->view->category_name = $table;

			Zend_Loader::LoadClass('floorClass', $this->modelDir);
			$floorClass = new floorClass();
			$this->view->floor = $floorClass->getFloorByCategoryId($params['c']);

			Zend_Loader::LoadClass('incidentClass', $this->modelDir);
			$incidentClass = new incidentClass();
			$this->view->incident = $incidentClass->getIncidentByCategoryId($params['c']);

			if(empty($params['year'])) $year = date("Y");
			else $year = $params['year'];

			$this->view->year = $year;

			if(!empty($params['month']))
			{
				$months = implode(",",$params['month']);
				$mo = array();
				foreach($params['month'] as $m)
				{
					$mo[$m] = 1;
				}
				$this->view->month = $mo;
			}
			else $months = 0;

			if(!empty($params['day']))
			{
				$days = implode(",",$params['day']);
				$day = array();
				foreach($params['day'] as $d)
				{
					$day[$d] = 1;
				}
				$this->view->day = $day;
			}
			else $days = 0;

			
			if(!empty($params['floor']))
			{
				$floors = implode(",",$params['floor']);
				$floor = array();
				foreach($params['floor'] as $f)
				{
					$floor[$f] = 1;
				}
				$this->view->floors = $floor;
			}
			else $floors = 0;

			if(count($params['tenant_umum']) == 1)
			{
				$tenant_umum = $params['tenant_umum'][0];
			}
			else if(!empty($params['tenant_umum']))
			{
				$tu = array();
				foreach($params['tenant_umum'] as $t)
				{
					$tu[$t] = 1;
				}
				$this->view->tenant_umum = $tu;
			}
			else {
				$tenant_umum = "";
			}

			if(!empty($params['kejadian']))
			{
				$incidents = implode(",",$params['kejadian']);
				$incident = array();
				foreach($params['kejadian'] as $i)
				{
					$incident[$i] = 1;
				}
				$this->view->incidents = $incident;
			}
			else $incidents = 0;

			Zend_Loader::LoadClass('modusClass', $this->modelDir);
			$modusClass = new modusClass();

			if($incidents > 0)
			{
				$this->view->modus = $modusClass->getModusByKejadianIds($incidents, $params['c']);
			}

			
			if(!empty($params['modus']))
			{
				$modus = implode(",",$params['modus']);
				$mod = array();
				foreach($params['modus'] as $md)
				{
					$mod[$md] = 1;
				}
				$this->view->mods = $mod;
			}
			else $modus = 0;
			
			if(!empty($params['time_period']))
			{
				$period = $params['time_period'];
				$pd = array();
				foreach($params['time_period'] as $p)
				{
					$pd[$p] = 1;
				}
				$this->view->period = $pd;
			}

			$this->view->totalMonthly = $incidentClass->getIncidentPerMonth($params['c'], $year, $months, $days, $period, $floors, $tenant_umum, $incidents, $modus, $this->site_id);

			$totalDay = $incidentClass->getIncidentPerDay($params['c'], $year, $months, $days, $period, $floors, $tenant_umum, $incidents, $modus, $this->site_id);
			$tday = array();
			foreach($totalDay as $td)
			{
				$tday[$td['d']] = $td['total'];
			}
			$this->view->totalDay = $tday;
		
			$this->view->totalEachIncidents = $incidentClass->getTotalEachIncidents($params['c'], $year, $months, $days, $period, $floors, $tenant_umum,  $incidents, $modus, $this->site_id);
			
			$this->view->totalEachModus = $modusClass->getTotalEachModus($params['c'], $year, $months, $days, $period, $floors, $tenant_umum,  $incidents, $modus, $this->site_id);

			Zend_Loader::LoadClass('issueClass', $this->modelDir);
			$issueClass = new issueClass();
			
			$timePeriod = array();
			if(empty($params['time_period']) || count($params['time_period']) == 5) {
				$timePeriod[1]['time'] = "09:01 - 12:00";
				$timePeriod[1]['total'] = $issueClass->getTotalIssuesStatByTimePeriode('09:00:00', '12:00:00', $params['c'], $year, $months, $days, $period, $floors, $incidents, $tenant_umum, $modus, $this->site_id);
				$timePeriod[2]['time'] = "12:01 - 16:00";
				$timePeriod[2]['total'] = $issueClass->getTotalIssuesStatByTimePeriode('12:00:00', '16:00:00', $params['c'], $year, $months, $days, $period, $floors, $incidents, $tenant_umum, $modus, $this->site_id);
				$timePeriod[3]['time'] = "16:01 - 19:00";
				$timePeriod[3]['total'] = $issueClass->getTotalIssuesStatByTimePeriode('16:00:00', '19:00:00', $params['c'], $year, $months, $days, $period, $floors, $incidents, $tenant_umum, $modus, $this->site_id);
				$timePeriod[4]['time'] = "19:01 - 23:00";
				$timePeriod[4]['total'] = $issueClass->getTotalIssuesStatByTimePeriode('19:00:00', '23:00:00', $params['c'], $year, $months, $days, $period, $floors, $incidents, $tenant_umum, $modus, $this->site_id);
				$timePeriod[5]['time'] = "23:01 - 09:00";
				$timePeriod[5]['total'] = $issueClass->getTotalIssuesStatByTimePeriode('23:00:00', '09:00:00', $params['c'], $year, $months, $days, $period, $floors, $incidents, $tenant_umum, $modus, $this->site_id);
			}
			else {
				$k = 0;
				foreach($params['time_period'] as $tp)
				{
					switch($tp)
					{
						case 1: $start = "09"; $end = "12"; break;
						case 2: $start = "12"; $end = "16"; break;
						case 3: $start = "16"; $end = "19"; break;
						case 4: $start = "19"; $end = "23"; break;
						case 5: $start = "23"; $end = "09"; break;
					}
					$timePeriod[$k]['time'] = $start.":01 - ".$end.":00";
					$timePeriod[$k]['total'] = $issueClass->getTotalIssuesStatByTimePeriode($start.':00:00', $end.':00:00', $params['c'], $year, $months, $days, $period, $floors, $incidents, $tenant_umum, $modus, $this->site_id);
					$k++;
				}
			}
			$this->view->totalEachPeriod = $timePeriod;

			Zend_Loader::LoadClass('floorClass', $this->modelDir);
			$floorClass = new floorClass();

			$pagesize = 15;

			$this->view->totalEachArea = $floorClass->getTotalEachModus($params['c'], $year, $months, $days, $period, $floors, $tenant_umum, $incidents, $modus, $this->site_id);

			$detailIssues = $issueClass->getDetailIssuesandSummary($params['c'], $year, $months, $days, $period, $floors, $tenant_umum, $incidents, $modus, $this->site_id);
			$dis = 0;
			$curKejadianId = "";
			$detail = array();
			$ctr = 1;
			foreach($detailIssues as $di)
			{
				if($curKejadianId != $di['kejadian_id'])
				{
					$ctr = 1;
					$detail[$dis]['kejadian'] = $di['kejadian'];
					if(!empty($di['analisa']))
						$detail[$dis]['analisa'] = $ctr.". ".$di['analisa'];
					if(!empty($di['tindakan']))
						$detail[$dis]['tindakan'] = $ctr.". ".$di['tindakan'];
					$curKejadianId = $di['kejadian_id'];
					$dis++;
				}
				else {
					if(!empty($di['analisa']))
						$detail[$dis-1]['analisa'] .= "<br/>".$ctr.". ".$di['analisa'];
					if(!empty($di['tindakan']))
						$detail[$dis-1]['tindakan'] .= "<br/>".$ctr.". ".$di['tindakan'];
				}
				$ctr++;
			}
			$this->view->detailIssues = $detail;
			/*$this->view->totalRec = $totalRec = $issueClass->getTotalDetailIssuesandSummary($params['c'], $year, $months, $days, $period, $floors, $tenant_umum, $incidents, $modus, $this->site_id);
			$this->view->totalPage = ceil($totalRec/$pagesize);
			if($totalRec== 0) $this->view->startRec = 0;
			else $this->view->startRec = $params['start'] + 1;		
			$this->view->curPage = ($params['start']/$pagesize)+1;
			$endRec =  $params['start'] + $pagesize;
			if($totalRec >=  $endRec) $this->view->endRec =  $endRec;
			else $this->view->endRec =  $totalRec;	
			if($totalRec > 15)
			{
				if($params['start'] >= 15)
				{
					$this->view->firstPageUrl = "/default/pivot/getdetailsummary";
					$this->view->prevUrl = "/default/pivot/getdetailsummary/start/".($params['start']-$pagesize);
				}
				if($params['start'] < (floor(($totalRec-1)/15)*15))
				{
					$this->view->nextUrl = "/default/pivot/getdetailsummary/start/".($params['start']+$pagesize);
					$this->view->lastPageUrl = "/default/pivot/getdetailsummary/start/".(floor(($totalRec-1)/$pagesize)*$pagesize);
				}
			} */

			$this->view->cat_id = $params['c'];

			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "View Pivot Chart";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);	

			$this->renderTemplate('pivot_chart.tpl'); 
		}
		else
		{
			$this->_response->setRedirect($this->config->paths->url);
    		$this->_response->sendResponse();
    		exit();
		}
	}

	public function getdetailsummaryAction()
	{	
		if($this->showSecurityPivotChart || $this->showSafetyPivotChart || $this->showParkingPivotChart)
		{
			$params = $this->_getAllParams();

			if($params['c'] == 1) $table = "Security";
			elseif($params['c'] == 3) $table = "Safety";
			elseif($params['c'] == 5) $table = "Parking &amp; Traffic";

			$this->view->category_name = $table;

			if(empty($params['year'])) $year = date("Y");
			else $year = $params['year'];

			if(!empty($params['month']))
			{
				$months = implode(",",$params['month']);
				$mo = array();
				foreach($params['month'] as $m)
				{
					$mo[$m] = 1;
				}
			}
			else $months = 0;

			if(!empty($params['day']))
			{
				$days = implode(",",$params['day']);
				$day = array();
				foreach($params['day'] as $d)
				{
					$day[$d] = 1;
				}
			}
			else $days = 0;

			
			if(!empty($params['floor']))
			{
				$floors = implode(",",$params['floor']);
				$floor = array();
				foreach($params['floor'] as $f)
				{
					$floor[$f] = 1;
				}
			}
			else $floors = 0;

			if(count($params['tenant_umum']) == 1)
			{
				$tenant_umum = $params['tenant_umum'][0];
			}
			else if(!empty($params['tenant_umum']))
			{
				$tu = array();
				foreach($params['tenant_umum'] as $t)
				{
					$tu[$t] = 1;
				}
			}
			else {
				$tenant_umum = "";
			}

			if(!empty($params['kejadian']))
			{
				$incidents = implode(",",$params['kejadian']);
				$incident = array();
				foreach($params['kejadian'] as $i)
				{
					$incident[$i] = 1;
				}
			}
			else $incidents = 0;

			Zend_Loader::LoadClass('modusClass', $this->modelDir);
			$modusClass = new modusClass();
			
			if(!empty($params['modus']))
			{
				$modus = implode(",",$params['modus']);
				$mod = array();
				foreach($params['modus'] as $md)
				{
					$mod[$md] = 1;
				}
			}
			else $modus = 0;
			
			if(!empty($params['time_period']))
			{
				$period = $params['time_period'];
				$pd = array();
				foreach($params['time_period'] as $p)
				{
					$pd[$p] = 1;
				}
			}
			
			Zend_Loader::LoadClass('issueClass', $this->modelDir);
			$issueClass = new issueClass();

			$pagesize = 15;

			$this->view->detailIssues = $issueClass->getDetailIssuesandSummary($params['c'], $year, $months, $days, $period, $floors, $tenant_umum, $incidents, $modus, $this->site_id, $params['start'], $pagesize);

			$this->view->totalRec = $totalRec = $issueClass->getTotalDetailIssuesandSummary($params['c'], $year, $months, $days, $period, $floors, $tenant_umum, $incidents, $modus, $this->site_id);
			
			$this->view->totalPage = ceil($totalRec/$pagesize);
			if($totalRec== 0) $this->view->startRec = 0;
			else $this->view->startRec = $params['start'] + 1;		
			$this->view->curPage = ($params['start']/$pagesize)+1;
			$endRec =  $params['start'] + $pagesize;
			if($totalRec >=  $endRec) $this->view->endRec =  $endRec;
			else $this->view->endRec =  $totalRec;	
			if($totalRec > 15)
			{
				if($params['start'] >= 15)
				{
					$this->view->firstPageUrl = "/default/pivot/getdetailsummary";
					$this->view->prevUrl = "/default/pivot/getdetailsummary/start/".($params['start']-$pagesize);
				}
				if($params['start'] < (floor(($totalRec-1)/15)*15))
				{
					$this->view->nextUrl = "/default/pivot/getdetailsummary/start/".($params['start']+$pagesize);
					$this->view->lastPageUrl = "/default/pivot/getdetailsummary/start/".(floor(($totalRec-1)/$pagesize)*$pagesize);
				}
			}

			$this->view->cat_id = $params['c'];

			echo $this->view->render("pivotdetailsummary.tpl");
		}
		else
		{
			$this->_response->setRedirect($this->config->paths->url);
    		$this->_response->sendResponse();
    		exit();
		}
	}

	function savepivotgraphAction()
	{
		if($this->showSecurityPivotChart || $this->showSafetyPivotChart || $this->showParkingPivotChart)
		{
			$params = $this->_getAllParams();

			if(empty($params['year'])) $this->session->pivot['year'] = date("Y");
			else $this->session->pivot['year'] = $params['year'];

			if(!empty($params['month']))
			{
				$this->session->pivot['months'] = implode(",",$params['month']);
			}
			else $this->session->pivot['months'] = 0;

			if(!empty($params['day']))
			{
				$this->session->pivot['days'] = implode(",",$params['day']);
			}
			else $this->session->pivot['days'] = 0;

			
			if(!empty($params['floor']))
			{
				$this->session->pivot['floors'] = implode(",",$params['floor']);
			}
			else $this->session->pivot['floors'] = 0;

			if(count($params['tenant_umum']) == 1)
			{
				$this->session->pivot['tenant_umum'] = $params['tenant_umum'][0];
			}
			else {
				$this->session->pivot['tenant_umum'] = "";
			}

			if(!empty($params['kejadian']))
			{
				$this->session->pivot['incidents'] = implode(",",$params['kejadian']);
			}
			else $this->session->pivot['incidents'] = 0;

			Zend_Loader::LoadClass('modusClass', $this->modelDir);
			$modusClass = new modusClass();
			
			if(!empty($params['modus']))
			{
				$this->session->pivot['modus'] = implode(",",$params['modus']);
			}
			else $this->session->pivot['modus'] = 0;
			
			if(!empty($params['time_period']))
			{
				$this->session->pivot['period'] = $params['time_period'];
			}

			$curdate = date("YmdHis");

			$magickPath = "/usr/bin/convert";
			
			$totalAllChart = $this->config->paths->html."/stat/".'totalAllChart_'.$params['prefix'].'_'.$curdate.'.png';
			$totalAllChart_uri =  str_replace("data:image/png;base64","", $params['totalAllChart']);
			file_put_contents($totalAllChart, base64_decode($totalAllChart_uri));

			$totalDayChart = $this->config->paths->html."/stat/".'totalDayChart_'.$params['prefix'].'_'.$curdate.'.png';
			$totalDayChart_uri =  str_replace("data:image/png;base64","", $params['totalDayChart']);
			file_put_contents($totalDayChart, base64_decode($totalDayChart_uri));

			$totalEachIncident = $this->config->paths->html."/stat/".'totalEachIncident_'.$params['prefix'].'_'.$curdate.'.png';
			$totalEachIncident_uri =  str_replace("data:image/png;base64","", $params['totalEachIncident']);
			file_put_contents($totalEachIncident, base64_decode($totalEachIncident_uri));

			$totalEachPeriod = $this->config->paths->html."/stat/".'totalEachPeriod_'.$params['prefix'].'_'.$curdate.'.png';
			$totalEachPeriod_uri =  str_replace("data:image/png;base64","", $params['totalEachPeriod']);
			file_put_contents($totalEachPeriod, base64_decode($totalEachPeriod_uri));

			$totalMonthlyChart = $this->config->paths->html."/stat/".'totalMonthlyChart_'.$params['prefix'].'_'.$curdate.'.png';
			$totalMonthlyChart_uri =  str_replace("data:image/png;base64","", $params['totalMonthlyChart']);
			file_put_contents($totalMonthlyChart, base64_decode($totalMonthlyChart_uri));

			$totalEachArea = $this->config->paths->html."/stat/".'totalEachArea_'.$params['prefix'].'_'.$curdate.'.png';
			$totalEachArea_uri =  str_replace("data:image/png;base64","", $params['totalEachArea']);
			file_put_contents($totalEachArea, base64_decode($totalEachArea_uri));

			$totalEachModus = $this->config->paths->html."/stat/".'totalEachModus_'.$params['prefix'].'_'.$curdate.'.png';
			$totalEachModus_uri =  str_replace("data:image/png;base64","", $params['totalEachModus']);
			file_put_contents($totalEachModus, base64_decode($totalEachModus_uri));

			echo $curdate;	
		}	
	}

	function exportpivottopdfAction() {
		if($this->showSecurityPivotChart || $this->showSafetyPivotChart || $this->showParkingPivotChart)
		{
			$params = $this->_getAllParams();

			$curdate = $params['cd'];

			$prefix = explode("_", $params['pf']);
			
			Zend_Loader::LoadClass('categoryClass', $this->modelDir);
			$categoryClass = new categoryClass();
		
			$category = $categoryClass->getCategoryById($prefix[1]);

			Zend_Loader::LoadClass('issueClass', $this->modelDir);
			$issueClass = new issueClass();

			$detailIssues = $issueClass->getDetailIssuesandSummary($prefix[1], $this->session->pivot['year'], $this->session->pivot['months'], $this->session->pivot['days'], $this->session->pivot['period'], $this->session->pivot['floors'], $this->session->pivot['tenant_umum'], $this->session->pivot['incidents'], $this->session->pivot['modus'], $this->site_id, 0, 0);
			
			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "Export Pivot Chart to PDF";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);	

			require_once('fpdf/mc_table.php');

			$pdf = new PDF_MC_Table();
			$pdf->AddPage();
			$pdf->SetFont('Arial','B',14);
			$pdf->Cell(80);
			$pdf->Cell(20,10,$prefix[0].' - '.$category['category_name'].' Monthly Analysis Pivot Chart ',0,0,'C');
			$pdf->Ln();
			$pdf->SetFont('Arial','B',8);
			$filter="";
			if(!empty($this->session->pivot['year'])) 
			{
				$pdf->SetTextColor(0,0,0);
				$pdf->write(5,"Year: ");
				$pdf->SetTextColor(255,0,0);
				$pdf->write(5,$this->session->pivot['year']."; ");
			}
			if(!empty($this->session->pivot['months'])) 
			{
				$pdf->SetTextColor(0,0,0);
				$pdf->write(5,"Month: ");
				$pdf->SetTextColor(255,0,0);
				$months = explode(",",$this->session->pivot['months']);
				$monthList = "";
				foreach($months as $mo)
				{
					$monthList .= date("M", strtotime(date("Y")."-".$mo."-01")).", ";
				}
				$monthList = substr($monthList, 0, -2);
				$pdf->write(5,$monthList."; ");
			}
			if(!empty($this->session->pivot['days'])) 
			{
				$pdf->SetTextColor(0,0,0);
				$pdf->write(5,"Days: ");
				$pdf->SetTextColor(255,0,0);
				$days = explode(",",$this->session->pivot['days']);
				$dayList = "";
				foreach($days as $d)
				{
					switch($d)
					{
						case 1: $dayList .= "Sun, "; break;
						case 2: $dayList .= "Mon, "; break;
						case 3: $dayList .= "Tue, "; break;
						case 4: $dayList .= "Wed, "; break;
						case 5: $dayList .= "Thu, "; break;
						case 6: $dayList .= "Fri, "; break;
						case 7: $dayList .= "Sat, "; break;
					}
					
				}
				$dayList = substr($dayList, 0, -2);
				$pdf->write(5,$dayList."; ");
			}
			if(!empty($this->session->pivot['period'])) 
			{
				$pdf->SetTextColor(0,0,0);
				$pdf->write(5,"Time Period: ");
				$pdf->SetTextColor(255,0,0);
				$periodList = "";
				foreach($this->session->pivot['period'] as $p)
				{
					switch($p)
					{
						case 1: $periodList .= "09:01 - 12:00, "; break;
						case 2: $periodList .= "12:01 - 16:00, "; break;
						case 3: $periodList .= "16:01 - 19:00, "; break;
						case 4: $periodList .= "19:01 - 23:00, "; break;
						case 5: $periodList .= "23:01 - 09:00, "; break;
					}
					
				}
				$periodList = substr($periodList, 0, -2);
				$pdf->write(5,$periodList."; ");
			}
			if(!empty($this->session->pivot['floors'])) 
			{
				Zend_Loader::LoadClass('floorClass', $this->modelDir);
				$floorClass = new floorClass();
				$pdf->SetTextColor(0,0,0);
				$pdf->write(5,"Floor: ");
				$pdf->SetTextColor(255,0,0);
				$floors = explode(",",$this->session->pivot['floors']);
				$floorList = "";
				foreach($floors as $f)
				{
					$fl = $floorClass->getFloorById($f, $prefix[1]);
					$floorList .= $fl['floor'].", ";		
				}
				$floorList = substr($floorList, 0, -2);
				$pdf->write(5,$floorList."; ");
			}
			if(!empty($this->session->pivot['tenant_umum'])) 
			{
				$pdf->SetTextColor(0,0,0);
				$pdf->write(5,"Tenant/Umum: ");
				$pdf->SetTextColor(255,0,0);
				$tenant_umum = explode(",",$this->session->pivot['tenant_umum']);
				foreach($tenant_umum as $t)
				{
					switch($t)
					{
						case '0': $filter .= "Tenant, "; break;
						case '1': $filter .= "Umum, "; break;
					}
					
				}
				$filter = substr($filter, 0, -2);
				$pdf->write(5,$filter."; ");
			}
			if(!empty($this->session->pivot['incidents'])) 
			{
				Zend_Loader::LoadClass('incidentClass', $this->modelDir);
				$incidentClass = new incidentClass();
				$pdf->SetTextColor(0,0,0);
				$pdf->write(5,"Incident: ");
				$pdf->SetTextColor(255,0,0);
				$incidents = explode(",",$this->session->pivot['incidents']);
				$incidentList = "";
				foreach($incidents as $in)
				{
					$inc = $incidentClass->getIncidentById($in, $prefix[1]);
					$incidentList .= $inc['kejadian'].", ";		
				}
				$incidentList = substr($incidentList, 0, -2);
				$pdf->write(5,$incidentList."; ");
			}
			if(!empty($this->session->pivot['modus'])) 
			{
				Zend_Loader::LoadClass('modusClass', $this->modelDir);
				$modusClass = new modusClass();
				$pdf->SetTextColor(0,0,0);
				$pdf->write(5,"Modus: ");
				$pdf->SetTextColor(255,0,0);
				$modus = explode(",",$this->session->pivot['modus']);
				$modusList = "";
				foreach($modus as $m)
				{
					$mod = $modusClass->getModusById($m, $prefix[1]);
					$modusList .= $mod['modus'].", ";		
				}
				$modusList = substr($modusList, 0, -2);
				$pdf->write(5,$modusList."; ");
			}

			
			unset($this->session->pivot);

			$pdf->Image($this->config->paths->html."/stat/".'totalAllChart_'.$params['pf'].'_'.$curdate.'.png',-5,38,0,34);	
			$pdf->Image($this->config->paths->html."/stat/".'totalDayChart_'.$params['pf'].'_'.$curdate.'.png',40,35,0,39);	
			$pdf->Image($this->config->paths->html."/stat/".'totalEachIncident_'.$params['pf'].'_'.$curdate.'.png',115,35,0,40);
			$pdf->Image($this->config->paths->html."/stat/".'totalEachPeriod_'.$params['pf'].'_'.$curdate.'.png',5,85,0,47);
			$pdf->Image($this->config->paths->html."/stat/".'totalEachArea_'.$params['pf'].'_'.$curdate.'.png',100,85,0,50);	
			$pdf->Image($this->config->paths->html."/stat/".'totalMonthlyChart_'.$params['pf'].'_'.$curdate.'.png',10,145,0,53);
			$pdf->Image($this->config->paths->html."/stat/".'totalEachModus_'.$params['pf'].'_'.$curdate.'.png',10,205,0,75);

			if(!empty($detailIssues))
			{
				$pdf->AddPage();
				$pdf->SetFillColor(158,130,75);
				$pdf->SetTextColor(255,255,255);
				$pdf->SetFont('Arial','B',8);
				$pdf->Cell(50,6,'Kejadian - Modus',1,0,'C',true);
				if($prefix[1] == 3) 
				{	
					$pdf->Cell(45,6,"Data hasil Investigasi",1,0,'C',true);
					$pdf->Cell(45,6,'Langkah Antisipatif',1,0,'C',true);
					$pdf->Cell(45,6,'Rekomendasi',1,0,'C',true);
				}
				else 
				{	
					$pdf->Cell(70,6,"Analisa",1,0,'C',true);
					$pdf->Cell(70,6,'Rencana & Tindakan',1,0,'C',true);
				}
				$pdf->Ln();
				$pdf->SetFont('');
				$pdf->SetTextColor(0,0,0);
				if($prefix[1] == 3) 
				{
					$pdf->SetWidths(array(50, 45, 45, 45));	
					foreach($detailIssues as $detail) {						
						$pdf->Row(array($detail['kejadian']." - ".$detail['modus'], $detail['analisa'], $detail['tindakan'], $detail['rekomendasi']));
					}
				}
				else
				{
					$pdf->SetWidths(array(50, 70, 70));	
					foreach($detailIssues as $detail) {
						$pdf->Row(array($detail['kejadian']." - ".$detail['modus'], $detail['analisa'], $detail['tindakan']));
					}
				}
			}
			$pdf->Output();
		}
	}

	public function corporateAction()
	{	
		if($this->showCorporateSecurityPivotChart || $this->showCorporateSafetyPivotChart || $this->showCorporateParkingPivotChart)
		{
			$params = $this->_getAllParams();

			if($params['c'] == 1) $table = "Security";
			elseif($params['c'] == 3) $table = "Safety";
			elseif($params['c'] == 5) $table = "Parking &amp; Traffic";

			$this->view->category_name = $table;

			Zend_Loader::LoadClass('citiesClass', $this->modelDir);
			$citiesClass = new citiesClass();
			$this->view->cities = $citiesClass->getCities();

			Zend_Loader::LoadClass('siteClass', $this->modelDir);
			$siteClass = new siteClass();
			$this->view->sites = $siteClass->getSites();

			Zend_Loader::LoadClass('floorClass', $this->modelDir);
			$floorClass = new floorClass();

			Zend_Loader::LoadClass('incidentClass', $this->modelDir);
			$incidentClass = new incidentClass();

			if(empty($params['year'])) $year = date("Y");
			else $year = $params['year'];

			$this->view->year = $year;

			//print_r($params); exit();

			if(!empty($params['site_id']))
			{
				$site_id = implode(",",$params['site_id']);
				$si = array();
				foreach($params['site_id'] as $s)
				{
					$si[$s] = 1;
				}
				$this->view->site_id = $si;
			}
			else if(!empty($params['city']))
			{
				$city = implode(",",$params['city']);
				$this->view->sites = $sites = $siteClass->getSitesByCityId($city);
				$site_id = "";
				if(!empty($sites))
				{
					foreach($sites as $site)
					{
						$site_id .= $site['site_id'].",";
					}
				}	
				$site_id = substr($site_id, 0, -1);

				$ci = array();
				foreach($params['city'] as $cit)
				{
					$ci[$cit] = 1;
				}
				$this->view->city = $ci;
			}
			else $site_id = 0;
			
			if(!empty($params['month']))
			{
				$months = implode(",",$params['month']);
				$mo = array();
				foreach($params['month'] as $m)
				{
					$mo[$m] = 1;
				}
				$this->view->month = $mo;
			}
			else $months = 0;

			if(!empty($params['day']))
			{
				$days = implode(",",$params['day']);
				$day = array();
				foreach($params['day'] as $d)
				{
					$day[$d] = 1;
				}
				$this->view->day = $day;
			}
			else $days = 0;

			
			if(!empty($params['floor']))
			{
				$floors = "";
				$floor = array();
				foreach($params['floor'] as $f)
				{
					$floor[$f] = 1;
					$floorids = $floorClass->getFloorIdByFloorNameAndSites($params['c'], $f, $site_id);
					$floors .= implode(',', array_column($floorids, 'floor_id')).",";
				}
				$floors = substr($floors, 0, -1);
				$this->view->floors = $floor;
			}

			if(count($params['tenant_umum']) == 1)
			{
				$tenant_umum = $params['tenant_umum'][0];
			}
			else if(!empty($params['tenant_umum']))
			{
				$tu = array();
				foreach($params['tenant_umum'] as $t)
				{
					$tu[$t] = 1;
				}
				$this->view->tenant_umum = $tu;
			}
			else {
				$tenant_umum = "";
			}

			if(!empty($params['kejadian']))
			{
				$incidents = "";
				$incident = array();
				foreach($params['kejadian'] as $i)
				{
					$incident[$i] = 1;
					$incidentids = $incidentClass->getIncidentIdByIncidentNameAndSites($params['c'], $i, $site_id);
					$incidents .= implode(',', array_column($incidentids, 'kejadian_id')).",";
				}
				$incidents = substr($incidents, 0, -1);
				$this->view->incidents = $incident;
			}
			else $incidents = 0;

			
			$this->view->floor = $floorClass->getFloorByCategoryIdAndSites($params['c'], $site_id);

			
			$this->view->incident = $incidentClass->getIncidentByCategoryIdAndSites($params['c'], $site_id);

			Zend_Loader::LoadClass('modusClass', $this->modelDir);
			$modusClass = new modusClass();

			if($incidents > 0)
			{
				$this->view->modus = $modusClass->getModusByKejadianIds($incidents, $params['c']);
			}

			
			if(!empty($params['modus']))
			{
				$modus = "";
				$mod = array();
				foreach($params['modus'] as $md)
				{
					$mod[$md] = 1;
					$modusids = $modusClass->getModusIdByModusNameAndSites($params['c'], $md, $site_id);
					$modus .= implode(',', array_column($modusids, 'modus_id')).",";
				}
				$modus = substr($modus, 0, -1);
				$this->view->mods = $mod;
			}
			else $modus = 0;
			
			if(!empty($params['time_period']))
			{
				$period = $params['time_period'];
				$pd = array();
				foreach($params['time_period'] as $p)
				{
					$pd[$p] = 1;
				}
				$this->view->period = $pd;
			}
			
			Zend_Loader::LoadClass('issueClass', $this->modelDir);
			$issueClass = new issueClass();

			$this->view->totalSites = $issueClass->getTotalIssuesStatBySites($params['c'], $year, $months, $days, $period, $floors, $incidents, $tenant_umum, $modus, $site_id);

			$this->view->totalMonthly = $incidentClass->getIncidentPerMonth($params['c'], $year, $months, $days, $period, $floors, $tenant_umum, $incidents, $modus, $site_id);

			$totalDay = $incidentClass->getIncidentPerDay($params['c'], $year, $months, $days, $period, $floors, $tenant_umum, $incidents, $modus, $site_id);
			$tday = array();
			foreach($totalDay as $td)
			{
				$tday[$td['d']] = $td['total'];
			}
			$this->view->totalDay = $tday;
		
			$this->view->totalEachIncidents = $incidentClass->getTotalEachIncidents($params['c'], $year, $months, $days, $period, $floors, $tenant_umum,  $incidents, $modus, $site_id);
			
			$this->view->totalEachModus = $modusClass->getTotalEachModus($params['c'], $year, $months, $days, $period, $floors, $tenant_umum,  $incidents, $modus, $site_id);
			
			$timePeriod = array();
			if(empty($params['time_period']) || count($params['time_period']) == 5) {
				$timePeriod[1]['time'] = "09:01 - 12:00";
				$timePeriod[1]['total'] = $issueClass->getTotalIssuesStatByTimePeriode('09:00:00', '12:00:00', $params['c'], $year, $months, $days, $period, $floors, $incidents, $tenant_umum, $modus, $site_id);
				$timePeriod[2]['time'] = "12:01 - 16:00";
				$timePeriod[2]['total'] = $issueClass->getTotalIssuesStatByTimePeriode('12:00:00', '16:00:00', $params['c'], $year, $months, $days, $period, $floors, $incidents, $tenant_umum, $modus, $site_id);
				$timePeriod[3]['time'] = "16:01 - 19:00";
				$timePeriod[3]['total'] = $issueClass->getTotalIssuesStatByTimePeriode('16:00:00', '19:00:00', $params['c'], $year, $months, $days, $period, $floors, $incidents, $tenant_umum, $modus, $site_id);
				$timePeriod[4]['time'] = "19:01 - 23:00";
				$timePeriod[4]['total'] = $issueClass->getTotalIssuesStatByTimePeriode('19:00:00', '23:00:00', $params['c'], $year, $months, $days, $period, $floors, $incidents, $tenant_umum, $modus, $site_id);
				$timePeriod[5]['time'] = "23:01 - 09:00";
				$timePeriod[5]['total'] = $issueClass->getTotalIssuesStatByTimePeriode('23:00:00', '09:00:00', $params['c'], $year, $months, $days, $period, $floors, $incidents, $tenant_umum, $modus, $site_id);
			}
			else {
				$k = 0;
				foreach($params['time_period'] as $tp)
				{
					switch($tp)
					{
						case 1: $start = "09"; $end = "12"; break;
						case 2: $start = "12"; $end = "16"; break;
						case 3: $start = "16"; $end = "19"; break;
						case 4: $start = "19"; $end = "23"; break;
						case 5: $start = "23"; $end = "09"; break;
					}
					$timePeriod[$k]['time'] = $start.":01 - ".$end.":00";
					$timePeriod[$k]['total'] = $issueClass->getTotalIssuesStatByTimePeriode($start.':00:00', $end.':00:00', $params['c'], $year, $months, $days, $period, $floors, $incidents, $tenant_umum, $modus, $site_id);
					$k++;
				}
			}
			$this->view->totalEachPeriod = $timePeriod;

			Zend_Loader::LoadClass('floorClass', $this->modelDir);
			$floorClass = new floorClass();

			$pagesize = 15;

			$this->view->totalEachArea = $floorClass->getTotalEachModus($params['c'], $year, $months, $days, $period, $floors, $tenant_umum, $incidents, $modus, $site_id);

			$this->view->detailIssues = $issueClass->getDetailIssuesandSummary($params['c'], $year, $months, $days, $period, $floors, $tenant_umum, $incidents, $modus, $site_id);

			$this->view->totalRec = $totalRec = $issueClass->getTotalDetailIssuesandSummary($params['c'], $year, $months, $days, $period, $floors, $tenant_umum, $incidents, $modus, $site_id);
			$this->view->totalPage = ceil($totalRec/$pagesize);
			if($totalRec== 0) $this->view->startRec = 0;
			else $this->view->startRec = $params['start'] + 1;		
			$this->view->curPage = ($params['start']/$pagesize)+1;
			$endRec =  $params['start'] + $pagesize;
			if($totalRec >=  $endRec) $this->view->endRec =  $endRec;
			else $this->view->endRec =  $totalRec;	
			if($totalRec > 15)
			{
				if($params['start'] >= 15)
				{
					$this->view->firstPageUrl = "/default/pivot/getcorporatedetailsummary";
					$this->view->prevUrl = "/default/pivot/getcorporatedetailsummary/start/".($params['start']-$pagesize);
				}
				if($params['start'] < (floor(($totalRec-1)/15)*15))
				{
					$this->view->nextUrl = "/default/pivot/getcorporatedetailsummary/start/".($params['start']+$pagesize);
					$this->view->lastPageUrl = "/default/pivot/getcorporatedetailsummary/start/".(floor(($totalRec-1)/$pagesize)*$pagesize);
				}
			}

			$this->view->cat_id = $params['c'];

			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "View Corporate Pivot Chart";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);	

			$this->renderTemplate('corporate_pivot_chart.tpl'); 
		}
		else
		{
			$this->_response->setRedirect($this->config->paths->url);
    		$this->_response->sendResponse();
    		exit();
		}
	}

	public function getcorporatedetailsummaryAction()
	{	
		if($this->showCorporateSecurityPivotChart || $this->showCorporateSafetyPivotChart || $this->showCorporateParkingPivotChart)
		{
			$params = $this->_getAllParams();

			Zend_Loader::LoadClass('siteClass', $this->modelDir);
			$siteClass = new siteClass();

			Zend_Loader::LoadClass('floorClass', $this->modelDir);
			$floorClass = new floorClass();

			Zend_Loader::LoadClass('incidentClass', $this->modelDir);
			$incidentClass = new incidentClass();

			if($params['c'] == 1) $table = "Security";
			elseif($params['c'] == 3) $table = "Safety";
			elseif($params['c'] == 5) $table = "Parking &amp; Traffic";

			$this->view->category_name = $table;

			if(empty($params['year'])) $year = date("Y");
			else $year = $params['year'];

			if(!empty($params['site_id']))
			{
				$site_id = implode(",",$params['site_id']);
			}
			else if(!empty($params['city']))
			{
				$city = implode(",",$params['city']);
				$this->view->sites = $sites = $siteClass->getSitesByCityId($city);
				$site_id = "";
				if(!empty($sites))
				{
					foreach($sites as $site)
					{
						$site_id .= $site['site_id'].",";
					}
				}	
				$site_id = substr($site_id, 0, -1);
			}
			else $site_id = 0;

			if(!empty($params['month']))
			{
				$months = implode(",",$params['month']);
				$mo = array();
				foreach($params['month'] as $m)
				{
					$mo[$m] = 1;
				}
			}
			else $months = 0;

			if(!empty($params['day']))
			{
				$days = implode(",",$params['day']);
				$day = array();
				foreach($params['day'] as $d)
				{
					$day[$d] = 1;
				}
			}
			else $days = 0;

			if(!empty($params['floor']))
			{
				$floors = "";
				foreach($params['floor'] as $f)
				{
					$floorids = $floorClass->getFloorIdByFloorNameAndSites($params['c'], $f, $site_id);
					$floors .= implode(',', array_column($floorids, 'floor_id')).",";
				}
				$floors = substr($floors, 0, -1);
			}

			if(count($params['tenant_umum']) == 1)
			{
				$tenant_umum = $params['tenant_umum'][0];
			}
			else if(!empty($params['tenant_umum']))
			{
				$tu = array();
				foreach($params['tenant_umum'] as $t)
				{
					$tu[$t] = 1;
				}
			}
			else {
				$tenant_umum = "";
			}

			if(!empty($params['kejadian']))
			{
				$incidents = "";
				foreach($params['kejadian'] as $i)
				{
					$incidentids = $incidentClass->getIncidentIdByIncidentNameAndSites($params['c'], $i, $site_id);
					$incidents .= implode(',', array_column($incidentids, 'kejadian_id')).",";
				}
				$incidents = substr($incidents, 0, -1);
			}
			else $incidents = 0;

			Zend_Loader::LoadClass('modusClass', $this->modelDir);
			$modusClass = new modusClass();
			
			if(!empty($params['modus']))
			{
				$modus = "";
				foreach($params['modus'] as $md)
				{
					$modusids = $modusClass->getModusIdByModusNameAndSites($params['c'], $md, $site_id);
					$modus .= implode(',', array_column($modusids, 'modus_id')).",";
				}
				$modus = substr($modus, 0, -1);
			}
			else $modus = 0;
			
			if(!empty($params['time_period']))
			{
				$period = $params['time_period'];
				$pd = array();
				foreach($params['time_period'] as $p)
				{
					$pd[$p] = 1;
				}
			}
			
			Zend_Loader::LoadClass('issueClass', $this->modelDir);
			$issueClass = new issueClass();

			$pagesize = 15;

			$this->view->detailIssues = $issueClass->getDetailIssuesandSummary($params['c'], $year, $months, $days, $period, $floors, $tenant_umum, $incidents, $modus, $site_id, $params['start'], $pagesize);

			$this->view->totalRec = $totalRec = $issueClass->getTotalDetailIssuesandSummary($params['c'], $year, $months, $days, $period, $floors, $tenant_umum, $incidents, $modus, $site_id);
			
			$this->view->totalPage = ceil($totalRec/$pagesize);
			if($totalRec== 0) $this->view->startRec = 0;
			else $this->view->startRec = $params['start'] + 1;		
			$this->view->curPage = ($params['start']/$pagesize)+1;
			$endRec =  $params['start'] + $pagesize;
			if($totalRec >=  $endRec) $this->view->endRec =  $endRec;
			else $this->view->endRec =  $totalRec;	
			if($totalRec > 15)
			{
				if($params['start'] >= 15)
				{
					$this->view->firstPageUrl = "/default/pivot/getcorporatedetailsummary";
					$this->view->prevUrl = "/default/pivot/getcorporatedetailsummary/start/".($params['start']-$pagesize);
				}
				if($params['start'] < (floor(($totalRec-1)/15)*15))
				{
					$this->view->nextUrl = "/default/pivot/getcorporatedetailsummary/start/".($params['start']+$pagesize);
					$this->view->lastPageUrl = "/default/pivot/getcorporatedetailsummary/start/".(floor(($totalRec-1)/$pagesize)*$pagesize);
				}
			}

			$this->view->cat_id = $params['c'];

			echo $this->view->render("pivotdetailsummary.tpl");
		}
		else
		{
			$this->_response->setRedirect($this->config->paths->url);
    		$this->_response->sendResponse();
    		exit();
		}
	}

	function savecorporatepivotgraphAction()
	{
		if($this->showCorporateSecurityPivotChart || $this->showCorporateSafetyPivotChart || $this->showCorporateParkingPivotChart)
		{
			$params = $this->_getAllParams();

			if(empty($params['year'])) $this->session->pivot['year'] = date("Y");
			else $this->session->pivot['year'] = $params['year'];

			Zend_Loader::LoadClass('siteClass', $this->modelDir);
			$siteClass = new siteClass();

			Zend_Loader::LoadClass('floorClass', $this->modelDir);
			$floorClass = new floorClass();

			Zend_Loader::LoadClass('incidentClass', $this->modelDir);
			$incidentClass = new incidentClass();

			$prefix = explode("_", $params['prefix']);
			if(!empty($params['sites']))
			{
				$this->session->pivot['sites'] = $site_id = implode(",",$params['sites']);
			}
			else if(!empty($params['cities']))
			{
				$city = implode(",",$params['cities']);
				$sites = $siteClass->getSitesByCityId($city);
				$site_id = "";
				if(!empty($sites))
				{
					foreach($sites as $site)
					{
						$site_id .= $site['site_id'].",";
					}
				}	
				$this->session->pivot['sites'] = $site_id = substr($site_id, 0, -1);
			}
			else $this->session->pivot['sites'] = $site_id = 0;

			if(!empty($params['month']))
			{
				$this->session->pivot['months'] = implode(",",$params['month']);
			}
			else $this->session->pivot['months'] = 0;

			if(!empty($params['day']))
			{
				$this->session->pivot['days'] = implode(",",$params['day']);
			}
			else $this->session->pivot['days'] = 0;

			
			if(!empty($params['floor']))
			{
				$floors = "";
				foreach($params['floor'] as $f)
				{
					$floorids = $floorClass->getFloorIdByFloorNameAndSites($prefix[1], $f, $site_id);
					$floors .= implode(',', array_column($floorids, 'floor_id')).",";
				}
				$floors = substr($floors, 0, -1);
				$this->session->pivot['floors'] = $floors;
			}
			else $this->session->pivot['floors'] = 0;

			if(count($params['tenant_umum']) == 1)
			{
				$this->session->pivot['tenant_umum'] = $params['tenant_umum'][0];
			}
			else {
				$this->session->pivot['tenant_umum'] = "";
			}

			if(!empty($params['kejadian']))
			{
				$incidents = "";
				foreach($params['kejadian'] as $i)
				{
					$incidentids = $incidentClass->getIncidentIdByIncidentNameAndSites($prefix[1], $i, $site_id);
					$incidents .= implode(',', array_column($incidentids, 'kejadian_id')).",";
				}
				$incidents = substr($incidents, 0, -1);
				$this->session->pivot['incidents'] = $incidents;
			}
			else $this->session->pivot['incidents'] = 0;

			Zend_Loader::LoadClass('modusClass', $this->modelDir);
			$modusClass = new modusClass();
			
			if(!empty($params['modus']))
			{
				$modus = "";
				foreach($params['modus'] as $md)
				{
					$modusids = $modusClass->getModusIdByModusNameAndSites($prefix[1], $md, $site_id);
					$modus .= implode(',', array_column($modusids, 'modus_id')).",";
				}
				$modus = substr($modus, 0, -1);
				$this->session->pivot['modus'] = $modus;
			}
			else $this->session->pivot['modus'] = 0;
			
			if(!empty($params['time_period']))
			{
				$this->session->pivot['period'] = $params['time_period'];
			}

			$curdate = date("YmdHis");

			$magickPath = "/usr/bin/convert";
			
			$totalAllChart = $this->config->paths->html."/stat/".'totalAllChart_'.$params['prefix'].'_'.$curdate.'.png';
			$totalAllChart_uri =  str_replace("data:image/png;base64","", $params['totalAllChart']);
			file_put_contents($totalAllChart, base64_decode($totalAllChart_uri));

			$totalDayChart = $this->config->paths->html."/stat/".'totalDayChart_'.$params['prefix'].'_'.$curdate.'.png';
			$totalDayChart_uri =  str_replace("data:image/png;base64","", $params['totalDayChart']);
			file_put_contents($totalDayChart, base64_decode($totalDayChart_uri));

			$totalEachIncident = $this->config->paths->html."/stat/".'totalEachIncident_'.$params['prefix'].'_'.$curdate.'.png';
			$totalEachIncident_uri =  str_replace("data:image/png;base64","", $params['totalEachIncident']);
			file_put_contents($totalEachIncident, base64_decode($totalEachIncident_uri));

			$totalEachPeriod = $this->config->paths->html."/stat/".'totalEachPeriod_'.$params['prefix'].'_'.$curdate.'.png';
			$totalEachPeriod_uri =  str_replace("data:image/png;base64","", $params['totalEachPeriod']);
			file_put_contents($totalEachPeriod, base64_decode($totalEachPeriod_uri));

			$totalMonthlyChart = $this->config->paths->html."/stat/".'totalMonthlyChart_'.$params['prefix'].'_'.$curdate.'.png';
			$totalMonthlyChart_uri =  str_replace("data:image/png;base64","", $params['totalMonthlyChart']);
			file_put_contents($totalMonthlyChart, base64_decode($totalMonthlyChart_uri));

			$totalEachArea = $this->config->paths->html."/stat/".'totalEachArea_'.$params['prefix'].'_'.$curdate.'.png';
			$totalEachArea_uri =  str_replace("data:image/png;base64","", $params['totalEachArea']);
			file_put_contents($totalEachArea, base64_decode($totalEachArea_uri));

			$totalEachModus = $this->config->paths->html."/stat/".'totalEachModus_'.$params['prefix'].'_'.$curdate.'.png';
			$totalEachModus_uri =  str_replace("data:image/png;base64","", $params['totalEachModus']);
			file_put_contents($totalEachModus, base64_decode($totalEachModus_uri));

			echo $curdate;		
		}
	}

	function exportcorporatepivottopdfAction() {
		if($this->showCorporateSecurityPivotChart || $this->showCorporateSafetyPivotChart || $this->showCorporateParkingPivotChart)
		{
			$params = $this->_getAllParams();

			$curdate = $params['cd'];
			
			Zend_Loader::LoadClass('categoryClass', $this->modelDir);
			$categoryClass = new categoryClass();
		
			$category = $categoryClass->getCategoryById($params['c']);

			Zend_Loader::LoadClass('issueClass', $this->modelDir);
			$issueClass = new issueClass();

			$detailIssues = $issueClass->getDetailIssuesandSummary($params['c'], $this->session->pivot['year'], $this->session->pivot['months'], $this->session->pivot['days'], $this->session->pivot['period'], $this->session->pivot['floors'], $this->session->pivot['tenant_umum'], $this->session->pivot['incidents'], $this->session->pivot['modus'], $this->session->pivot['sites'], 0, 0);
			
			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "Export Corporate Pivot Chart to PDF";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);	

			require_once('fpdf/mc_table.php');

			$pdf = new PDF_MC_Table();
			$pdf->AddPage();
			$pdf->SetFont('Arial','B',14);
			$pdf->Cell(80);
			$pdf->Cell(20,10,'Corporate '.$category['category_name'].' Monthly Analysis Pivot Chart ',0,0,'C');
			$pdf->Ln();
			$pdf->SetFont('Arial','B',8);
			$filter="";
			if(!empty($this->session->pivot['year'])) 
			{
				$pdf->SetTextColor(0,0,0);
				$pdf->write(5,"Year: ");
				$pdf->SetTextColor(255,0,0);
				$pdf->write(5,$this->session->pivot['year']."; ");
			}
			if(!empty($this->session->pivot['months'])) 
			{
				$pdf->SetTextColor(0,0,0);
				$pdf->write(5,"Month: ");
				$pdf->SetTextColor(255,0,0);
				$months = explode(",",$this->session->pivot['months']);
				$monthList = "";
				foreach($months as $mo)
				{
					$monthList .= date("M", strtotime(date("Y")."-".$mo."-01")).", ";
				}
				$monthList = substr($monthList, 0, -2);
				$pdf->write(5,$monthList."; ");
			}
			if(!empty($this->session->pivot['days'])) 
			{
				$pdf->SetTextColor(0,0,0);
				$pdf->write(5,"Days: ");
				$pdf->SetTextColor(255,0,0);
				$days = explode(",",$this->session->pivot['days']);
				$dayList = "";
				foreach($days as $d)
				{
					switch($d)
					{
						case 1: $dayList .= "Sun, "; break;
						case 2: $dayList .= "Mon, "; break;
						case 3: $dayList .= "Tue, "; break;
						case 4: $dayList .= "Wed, "; break;
						case 5: $dayList .= "Thu, "; break;
						case 6: $dayList .= "Fri, "; break;
						case 7: $dayList .= "Sat, "; break;
					}
					
				}
				$dayList = substr($dayList, 0, -2);
				$pdf->write(5,$dayList."; ");
			}
			if(!empty($this->session->pivot['period'])) 
			{
				$pdf->SetTextColor(0,0,0);
				$pdf->write(5,"Time Period: ");
				$pdf->SetTextColor(255,0,0);
				$periodList = "";
				foreach($this->session->pivot['period'] as $p)
				{
					switch($p)
					{
						case 1: $periodList .= "09:01 - 12:00, "; break;
						case 2: $periodList .= "12:01 - 16:00, "; break;
						case 3: $periodList .= "16:01 - 19:00, "; break;
						case 4: $periodList .= "19:01 - 23:00, "; break;
						case 5: $periodList .= "23:01 - 09:00, "; break;
					}
					
				}
				$periodList = substr($periodList, 0, -2);
				$pdf->write(5,$periodList."; ");
			}
			if(!empty($this->session->pivot['floors'])) 
			{
				Zend_Loader::LoadClass('floorClass', $this->modelDir);
				$floorClass = new floorClass();
				$pdf->SetTextColor(0,0,0);
				$pdf->write(5,"Floor: ");
				$pdf->SetTextColor(255,0,0);
				$floors = explode(",",$this->session->pivot['floors']);
				$floorList = "";
				foreach($floors as $f)
				{
					$fl = $floorClass->getFloorById($f, $params['c']);
					$floorList .= $fl['floor'].", ";		
				}
				$floorList = substr($floorList, 0, -2);
				$pdf->write(5,$floorList."; ");
			}
			if(!empty($this->session->pivot['tenant_umum'])) 
			{
				$pdf->SetTextColor(0,0,0);
				$pdf->write(5,"Tenant/Umum: ");
				$pdf->SetTextColor(255,0,0);
				$tenant_umum = explode(",",$this->session->pivot['tenant_umum']);
				foreach($tenant_umum as $t)
				{
					switch($t)
					{
						case '0': $filter .= "Tenant, "; break;
						case '1': $filter .= "Umum, "; break;
					}
					
				}
				$filter = substr($filter, 0, -2);
				$pdf->write(5,$filter."; ");
			}
			if(!empty($this->session->pivot['incidents'])) 
			{
				Zend_Loader::LoadClass('incidentClass', $this->modelDir);
				$incidentClass = new incidentClass();
				$pdf->SetTextColor(0,0,0);
				$pdf->write(5,"Incident: ");
				$pdf->SetTextColor(255,0,0);
				$incidents = explode(",",$this->session->pivot['incidents']);
				$incidentList = "";
				$prevInc = "";
				foreach($incidents as $in)
				{
					$inc = $incidentClass->getIncidentById($in, $params['c']);
					if($inc['kejadian'] != $prevInc)
					{
						$incidentList .= $inc['kejadian'].", ";		
						$prevInc = $inc['kejadian'];
					}
				}
				$incidentList = substr($incidentList, 0, -2);
				$pdf->write(5,$incidentList."; ");
			}
			if(!empty($this->session->pivot['modus'])) 
			{
				Zend_Loader::LoadClass('modusClass', $this->modelDir);
				$modusClass = new modusClass();
				$pdf->SetTextColor(0,0,0);
				$pdf->write(5,"Modus: ");
				$pdf->SetTextColor(255,0,0);
				$modus = explode(",",$this->session->pivot['modus']);
				$modusList = "";
				$prevMod = "";
				foreach($modus as $m)
				{
					$mod = $modusClass->getModusById($m, $params['c']);
					if($mod['modus'] != $prevMod)
					{
						$modusList .= $mod['modus'].", ";		
						$prevMod = $mod['modus'];
					}	
				}
				$modusList = substr($modusList, 0, -2);
				$pdf->write(5,$modusList."; ");
			}

			
			unset($this->session->pivot);

			$pdf->Image($this->config->paths->html."/stat/".'totalAllChart_corp_'.$params['c'].'_'.$curdate.'.png',-5,35,0,34);	
			$pdf->Image($this->config->paths->html."/stat/".'totalDayChart_corp_'.$params['c'].'_'.$curdate.'.png',57,35,0,34);	
			$pdf->Image($this->config->paths->html."/stat/".'totalEachIncident_corp_'.$params['c'].'_'.$curdate.'.png',127,35,0,34);
			$pdf->Image($this->config->paths->html."/stat/".'totalEachPeriod_corp_'.$params['c'].'_'.$curdate.'.png',5,85,0,47);
			$pdf->Image($this->config->paths->html."/stat/".'totalEachArea_corp_'.$params['c'].'_'.$curdate.'.png',100,85,0,50);	
			$pdf->Image($this->config->paths->html."/stat/".'totalMonthlyChart_corp_'.$params['c'].'_'.$curdate.'.png',10,145,0,53);
			$pdf->Image($this->config->paths->html."/stat/".'totalEachModus_corp_'.$params['c'].'_'.$curdate.'.png',10,205,0,75);

			/*if(!empty($detailIssues))
			{
				$pdf->AddPage();
				$pdf->SetFillColor(158,130,75);
				$pdf->SetTextColor(255,255,255);
				$pdf->SetFont('Arial','B',8);
				$pdf->Cell(19,6,'Date',1,0,'C',true);
				$pdf->Cell(30,6,'Kejadian - Modus',1,0,'C',true);
				if($params['c'] == 3) 
				{
					$pdf->Cell(53,6,'Detail',1,0,'C',true);		
					$pdf->Cell(30,6,"Data hasil Investigasi",1,0,'C',true);
					$pdf->Cell(30,6,'Langkah Antisipatif',1,0,'C',true);
					$pdf->Cell(30,6,'Rekomendasi',1,0,'C',true);
				}
				else 
				{
					$pdf->Cell(73,6,'Detail',1,0,'C',true);		
					$pdf->Cell(35,6,"Analisa",1,0,'C',true);
					$pdf->Cell(35,6,'Rencana & Tindakan',1,0,'C',true);
				}
				$pdf->Ln();
				$pdf->SetFont('');
				$pdf->SetTextColor(0,0,0);
				if($params['c'] == 3) 
				{
					$pdf->SetWidths(array(19, 30, 53, 30, 30, 30));	
					foreach($detailIssues as $detail) {
						$issuedate = explode(" ", $detail['issue_date']);
						$issue_date = date("j M Y", strtotime($issuedate[0]))." ".$issuedate[1];
						$pdf->Row(array($issue_date,$detail['kejadian']." - ".$detail['modus'], $detail['description'], $detail['analisa'], $detail['tindakan'], $detail['rekomendasi']));
					}
				}
				else
				{
					$pdf->SetWidths(array(19, 30, 73, 35, 35));	
					foreach($detailIssues as $detail) {
						$issuedate = explode(" ", $detail['issue_date']);
						$issue_date = date("j M Y", strtotime($issuedate[0]))." ".$issuedate[1];
						$pdf->Row(array($issue_date,$detail['kejadian']." - ".$detail['modus'], $detail['description'], $detail['analisa'], $detail['tindakan']));
					}
				}
			}*/
			$pdf->Output();
		}
	}
}
?>