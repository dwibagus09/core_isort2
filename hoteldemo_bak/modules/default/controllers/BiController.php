<?php

require_once('actionControllerBase.php');
require_once('Zend/Json.php');

class BiController extends actionControllerBase
{		
	public function addmonthlyanalysisAction() {
		$params = $this->_getAllParams();
		if(($this->addSecurityMonthlyAnalysis && $params['c'] == 1) || ($this->addHousekeepingMonthlyAnalysis && $params['c'] == 2) || ($this->addSafetyMonthlyAnalysis && $params['c'] == 3) || ($this->addParkingMonthlyAnalysis && $params['c'] == 5) || ($this->addEngineeringMonthlyAnalysis && $params['c'] == 6) || ($this->addBuildingServiceMonthlyAnalysis && $params['c'] == 10) || ($this->addTenantRelationMonthlyAnalysis && $params['c'] == 11))
		{
			switch($params['c']) {
				case 1: $maClass = $this->loadModel('security');
						break;
				case 2: $maClass = $this->loadModel('housekeeping');
						 break;
				case 3: $maClass = $this->loadModel('safety');
						 break;
				case 5: $maClass = $this->loadModel('parking');
						 break;
				case 6: $maClass = $this->loadModel('engineering');
						 break;
				case 10: $maClass = $this->loadModel('bs');
						 break;
				case 11: $maClass = $this->loadModel('tr');						 
						 break;
			}
			
			if(!empty($params['id'])) 
			{
				
			
				$this->view->monthly_analysis_id = $params['id'];		
				$monthly_analysis = $maClass->geMonthlyAnalysisById($params['id']);
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

			if(empty($modus))
			{		
				Zend_Loader::LoadClass('modusClass', $this->modelDir);
				$modusClass = new modusClass();	
				$modus = $modusClass->getModus($params['c']);
			}

			if(empty($totalModusPerMonth))
			{	
				$totalModusPerMonth =  array();
				for($b=1; $b<=$m; $b++) // get rekapitulasi utk bulan ini dan bulan2 sblmnya
				{
					$totalModus = $issueClass->getIssuesByModus($b, $y, $params['c']);
					foreach($totalModus as $tm) {
						$totalModusPerMonth[$b][$tm['modus_id']] = $tm['total_modus'];
					}
				}
			}
			else{
				$totalModus = $issueClass->getIssuesByModus($m, $y, $params['c']);
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
					
					$analisa_hari = $issueClass->getMonthlyIssuesByDay($mo['kejadian_id'], $m, $y, $params['c']);
					if(!empty($analisa_hari))
					{
						foreach($analisa_hari as $ah) {
							$rekap[$i]['analisa_hari'][$ah['day']]= $ah['total'];
						}
					}
					
					$rekap[$i]['analisa_jam'][0] = $issueClass->getMonthlyIssuesByTime($mo['kejadian_id'], $m, $y, '09:00:00', '12:00:00', $params['c']);
					$rekap[$i]['analisa_jam'][1] = $issueClass->getMonthlyIssuesByTime($mo['kejadian_id'], $m, $y, '12:00:00', '16:00:00', $params['c']);
					$rekap[$i]['analisa_jam'][2] = $issueClass->getMonthlyIssuesByTime($mo['kejadian_id'], $m, $y, '16:00:00', '19:00:00', $params['c']);
					$rekap[$i]['analisa_jam'][3] = $issueClass->getMonthlyIssuesByTime($mo['kejadian_id'], $m, $y, '19:00:00', '23:00:00', $params['c']);
					$rekap[$i]['analisa_jam'][4] = $issueClass->getMonthlyIssuesByTime($mo['kejadian_id'], $m, $y, '23:00:00', '09:00:00', $params['c']);
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
				
				$uraian_kejadian = $issueClass->getIssuesByModusId($mo['modus_id'], $m, $y, $params['c']);

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
			
			$this->view->urutan_hari_tertinggi = $issueClass->getTotalIssuesByDayDescending($m, $y, $params['c']);
			$urutan_total_jam[0] = $issueClass->getTotalIssuesByTimePeriode($m, $y, '09:00:00', '12:00:00', $params['c']);
			$urutan_total_jam[1] = $issueClass->getTotalIssuesByTimePeriode($m, $y, '12:00:00', '16:00:00', $params['c']);
			$urutan_total_jam[2] = $issueClass->getTotalIssuesByTimePeriode($m, $y, '16:00:00', '19:00:00', $params['c']);
			$urutan_total_jam[3] = $issueClass->getTotalIssuesByTimePeriode($m, $y, '19:00:00', '23:00:00', $params['c']);
			$urutan_total_jam[4] = $issueClass->getTotalIssuesByTimePeriode($m, $y, '23:00:00', '09:00:00', $params['c']);
			arsort($urutan_total_jam);

			$this->view->urutan_total_jam = $urutan_total_jam;
			
			$this->view->incidents = $issueClass->getIssueSummary($m, $y, $params['c'], $params['id']);

			$listIssues = $issueClass->getMonthlyAnalysisIssues($m, $y, $params['c']);
			foreach($listIssues as &$issue)
			{
				$tgl = explode(" ", $issue['issue_date']);
				$issue['date'] = date("j M Y", strtotime($tgl[0]))." ".$tgl[1];
			}
			$this->view->listIssues = $listIssues;

			Zend_Loader::LoadClass('incidentClass', $this->modelDir);
			$incidentClass = new incidentClass();	
			$this->view->listKejadian = $incidentClass->getIncidentByCategoryId($params['c']);

			$categoryTable = $this->loadModel('category');
			$this->view->category = $thisCategory = $categoryTable->getCategoryById($params['c']);

			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "Add ".$thisCategory['category_name']." Monthly Analysis";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);	

			$this->renderTemplate('form_monthly_analysis.tpl'); 
		}
		else
		{
			$this->_response->setRedirect($this->baseUrl.'/default');
			$this->_response->sendResponse();
			exit();
		}
	}
	
	public function savemonthlyanalysisAction() {
		$params = $this->_getAllParams();
		
		switch($params['c']) {
			case 1: $maClass = $this->loadModel('security');
					break;
			case 2: $maClass = $this->loadModel('housekeeping');
					 break;
			case 3: $maClass = $this->loadModel('safety');
					 break;
			case 5: $maClass = $this->loadModel('parking');
					 break;
			case 6: $maClass = $this->loadModel('engineering');
					 break;
			case 10: $maClass = $this->loadModel('bs');
					 break;
			case 11: $maClass = $this->loadModel('tr');						 
					 break;
		}

		$params['user_id'] = $this->ident['user_id'];
		if(empty($params['monthly_analysis_id']))
		{
			$params['monthly_analysis_id'] = $maClass->saveMonthlyAnalysis($params);
		}
		

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
			$monthlyanalysissummaryClass->saveMonthlyAnalysis($data, $params['c']);
			$i++;
		}
		
		$categoryTable = $this->loadModel('category');
		$thisCategory = $categoryTable->getCategoryById($params['c']);

		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "Save ".$thisCategory['category_name']." Monthly Analysis";
		$logData['data'] = json_encode($params);
		$logsTable->insertLogs($logData);	

		$this->_response->setRedirect($this->baseUrl.'/default/bi/viewmonthlyanalysis/c/'.$params['c']);
		$this->_response->sendResponse();
		exit();
	}
	
	public function viewmonthlyanalysisAction() {
		$params = $this->_getAllParams();
		if(!empty($params['c']))
		{
			switch($params['c']) {
				case 1: $maClass = $this->loadModel('security');
						break;
				case 2: $maClass = $this->loadModel('housekeeping');
						 break;
				case 3: $maClass = $this->loadModel('safety');
						 break;
				case 5: $maClass = $this->loadModel('parking');
						 break;
				case 6: $maClass = $this->loadModel('engineering');
						 break;
				case 10: $maClass = $this->loadModel('bs');
						 break;
				case 11: $maClass = $this->loadModel('tr');						 
						 break;
			}
		
			$this->view->ident = $this->ident;
			if(empty($params['start'])) $params['start'] = '0';
			$params['pagesize'] = 10;
			$this->view->start = $params['start'];
			$monthlyAnalysis = $maClass->getMonthlyAnalysis($params);
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

			$totalMonthlyAnalysis = $maClass->getTotalMonthlyAnalysis();
			if($totalMonthlyAnalysis > 10)
			{
				if($params['start'] >= 10)
				{
					$this->view->firstPageUrl = "/default/bi/viewmonthlyanalysis/c/".$params['c'];
					$this->view->prevUrl = "/default/bi/viewmonthlyanalysis/start/".($params['start']-$params['pagesize']).'/c/'.$params['c'];
				}
				if($params['start'] < (floor(($totalMonthlyAnalysis-1)/10)*10))
				{
					$this->view->nextUrl = "/default/bi/viewmonthlyanalysis/start/".($params['start']+$params['pagesize']).'/c/'.$params['c'];
					$this->view->lastPageUrl = "/default/bi/viewmonthlyanalysis/start/".(floor(($totalMonthlyAnalysis-1)/10)*10).'/c/'.$params['c'];
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

			$categoryTable = $this->loadModel('category');
			$this->view->category = $thisCategory = $categoryTable->getCategoryById($params['c']);
			
			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "View ".$thisCategory['category_name']." Monthly Analysis List";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);	

			$this->renderTemplate('view_monthly_analysis.tpl'); 
		}
		else
		{
			$this->_response->setRedirect($this->baseUrl.'/default');
			$this->_response->sendResponse();
			exit();
		}
	}

	public function viewdetailmonthlyanalysisAction() {
		$params = $this->_getAllParams();

		if(!empty($params['id']) && !empty($params['c']))
		{
			switch($params['c']) {
				case 1: $maClass = $this->loadModel('security');
						break;
				case 2: $maClass = $this->loadModel('housekeeping');
						 break;
				case 3: $maClass = $this->loadModel('safety');
						 break;
				case 5: $maClass = $this->loadModel('parking');
						 break;
				case 6: $maClass = $this->loadModel('engineering');
						 break;
				case 10: $maClass = $this->loadModel('bs');
						 break;
				case 11: $maClass = $this->loadModel('tr');						 
						 break;
			}
		
			$this->view->monthly_analysis_id = $params['id'];	
			$monthly_analysis = $maClass->geMonthlyAnalysisById($params['id']);
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

			if(empty($modus))
			{		
				Zend_Loader::LoadClass('modusClass', $this->modelDir);
				$modusClass = new modusClass();	
				$modus = $modusClass->getModus($params['c']);
			}

			if(empty($totalModusPerMonth))
			{	
				$totalModusPerMonth =  array();
				for($b=1; $b<=$m; $b++) // get rekapitulasi utk bulan ini dan bulan2 sblmnya
				{
					$totalModus = $issueClass->getIssuesByModus($b, $y, $params['c']);
					foreach($totalModus as $tm) {
						$totalModusPerMonth[$b][$tm['modus_id']] = $tm['total_modus'];
					}
				}
			}
			else{
				$totalModus = $issueClass->getIssuesByModus($m, $y, $params['c']);
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
						$analisa_hari = $issueClass->getMonthlyIssuesByDay($mo['kejadian_id'], $m, $y, $params['c']);
						
						if(!empty($analisa_hari))
						{
							foreach($analisa_hari as $ah) {
								$rekap[$i]['analisa_hari'][$ah['day']]= $ah['total'];
							}
						}
						$rekap[$i]['analisa_jam'][0] = $issueClass->getMonthlyIssuesByTime($mo['kejadian_id'], $m, $y, '09:00:00', '12:00:00', $params['c']);
						$rekap[$i]['analisa_jam'][1] = $issueClass->getMonthlyIssuesByTime($mo['kejadian_id'], $m, $y, '12:00:00', '16:00:00', $params['c']);
						$rekap[$i]['analisa_jam'][2] = $issueClass->getMonthlyIssuesByTime($mo['kejadian_id'], $m, $y, '16:00:00', '19:00:00', $params['c']);
						$rekap[$i]['analisa_jam'][3] = $issueClass->getMonthlyIssuesByTime($mo['kejadian_id'], $m, $y, '19:00:00', '23:00:00', $params['c']);
						$rekap[$i]['analisa_jam'][4] = $issueClass->getMonthlyIssuesByTime($mo['kejadian_id'], $m, $y, '23:00:00', '09:00:00', $params['c']);
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
					$uraian_kejadian = $issueClass->getIssuesByModusId($mo['modus_id'], $m, $y, $params['c']);
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
				$this->view->urutan_hari_tertinggi = $issueClass->getTotalIssuesByDayDescending($m, $y, $params['c']);
				$urutan_total_jam[0] = $issueClass->getTotalIssuesByTimePeriode($m, $y, '09:00:00', '12:00:00', $params['c']);
				$urutan_total_jam[1] = $issueClass->getTotalIssuesByTimePeriode($m, $y, '12:00:00', '16:00:00', $params['c']);
				$urutan_total_jam[2] = $issueClass->getTotalIssuesByTimePeriode($m, $y, '16:00:00', '19:00:00', $params['c']);
				$urutan_total_jam[3] = $issueClass->getTotalIssuesByTimePeriode($m, $y, '19:00:00', '23:00:00', $params['c']);
				$urutan_total_jam[4] = $issueClass->getTotalIssuesByTimePeriode($m, $y, '23:00:00', '09:00:00', $params['c']);
				arsort($urutan_total_jam);
				$this->view->urutan_total_jam = $urutan_total_jam;

				$this->view->incidents = $issueClass->getIssueSummary($m, $y, $params['c'], $params['id']);
				$urutan_total_issue_tenant = $issueClass->getTotalIssuesByTenantPublik($m, $y, '0', $params['c']);
				if(!empty($urutan_total_issue_tenant))
				{
					$urutan_total_all_issue_tenant = 0;
					foreach($urutan_total_issue_tenant as &$t)
					{
						$data = array();
						$data = $issueClass->getTotalIssuesByLocation($m, $y, '0', $t['location'], $t['floor_id'], $params['c']);
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

				$urutan_total_issue_publik = $issueClass->getTotalIssuesByTenantPublik($m, $y, '1', $params['c']);
				if(!empty($urutan_total_issue_publik))
				{
					$urutan_total_all_issue_publik = 0;
					foreach($urutan_total_issue_publik as &$p)
					{
						$data = array();
						$data = $issueClass->getTotalIssuesByLocation($m, $y, '1', $p['location'], $p['floor_id'], $params['c']);
						
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
			$categoryTable = $this->loadModel('category');
			$this->view->category = $thisCategory = $categoryTable->getCategoryById($params['c']);

			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "View ".$thisCategory['category_name']." Monthly Analysis Detail";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);	

			//echo "<pre>"; print_r($pelaku_tertangkap_detail); exit();
			$this->renderTemplate('monthly_analysis_detail.tpl'); 
		}
		else
		{
			$this->_response->setRedirect($this->baseUrl.'/default');
			$this->_response->sendResponse();
			exit();
		}
	}


	public function downloadmonthlyanalysisAction() {		
		$params = $this->_getAllParams();

		if(!empty($params['id']) && !empty($params['c']))
		{	
			$categoryTable = $this->loadModel('category');
			$thisCategory = $categoryTable->getCategoryById($params['c']);
		
			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "Export ".$thisCategory['category_name']." Monthly Analysis Report to PDF";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);

			$monthly_analysis_id = $params['id'];		
			
			switch($params['c']) {
				case 1: $maClass = $this->loadModel('security');
						break;
				case 2: $maClass = $this->loadModel('housekeeping');
						 break;
				case 3: $maClass = $this->loadModel('safety');
						 break;
				case 5: $maClass = $this->loadModel('parking');
						 break;
				case 6: $maClass = $this->loadModel('engineering');
						 break;
				case 10: $maClass = $this->loadModel('bs');
						 break;
				case 11: $maClass = $this->loadModel('tr');						 
						 break;
			}
			
			$monthly_analysis = $maClass->geMonthlyAnalysisById($params['id']);
			
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

			//$modus = $this->cache->load("modus_".$this->site_id."_2_");
			
			if(empty($modus))
			{		
				Zend_Loader::LoadClass('modusClass', $this->modelDir);
				$modusClass = new modusClass();	
				$modus = $modusClass->getModus($params['c']);
			}

			//$totalModusPerMonth = $this->cache->load("total_modus_per_month_".$this->site_id."_1_".$ym);
			if(empty($totalModusPerMonth))
			{	
				$totalModusPerMonth =  array();
				for($b=1; $b<=$m; $b++) // get rekapitulasi utk bulan ini dan bulan2 sblmnya
				{
					$totalModus = $issueClass->getIssuesByModus($b, $y, $params['c']);
					foreach($totalModus as $tm) {
						$totalModusPerMonth[$b][$tm['modus_id']] = $tm['total_modus'];
					}
				}
			}
			else{
				$totalModus = $issueClass->getIssuesByModus($m, $y, $params['c']);
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
						$analisa_hari = $issueClass->getMonthlyIssuesByDay($mo['kejadian_id'], $m, $y, $params['c']);
						
						if(!empty($analisa_hari))
						{
							foreach($analisa_hari as $ah) {
								$rekap[$i]['analisa_hari'][$ah['day']]= $ah['total'];
							}
						}
						$rekap[$i]['analisa_jam'][0] = $issueClass->getMonthlyIssuesByTime($mo['kejadian_id'], $m, $y, '09:00:00', '12:00:00', $params['c']);
						$rekap[$i]['analisa_jam'][1] = $issueClass->getMonthlyIssuesByTime($mo['kejadian_id'], $m, $y, '12:00:00', '16:00:00', $params['c']);
						$rekap[$i]['analisa_jam'][2] = $issueClass->getMonthlyIssuesByTime($mo['kejadian_id'], $m, $y, '16:00:00', '19:00:00', $params['c']);
						$rekap[$i]['analisa_jam'][3] = $issueClass->getMonthlyIssuesByTime($mo['kejadian_id'], $m, $y, '19:00:00', '23:00:00', $params['c']);
						$rekap[$i]['analisa_jam'][4] = $issueClass->getMonthlyIssuesByTime($mo['kejadian_id'], $m, $y, '23:00:00', '09:00:00', $params['c']);
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
					$uraian_kejadian = $issueClass->getIssuesByModusId($mo['modus_id'], $m, $y, $params['c']);
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
				
				$urutan_hari_tertinggi = $issueClass->getTotalIssuesByDayDescending($m, $y, $params['c']);
				$urutan_total_jam[0] = $issueClass->getTotalIssuesByTimePeriode($m, $y, '09:00:00', '12:00:00', $params['c']);
				$urutan_total_jam[1] = $issueClass->getTotalIssuesByTimePeriode($m, $y, '12:00:00', '16:00:00', $params['c']);
				$urutan_total_jam[2] = $issueClass->getTotalIssuesByTimePeriode($m, $y, '16:00:00', '19:00:00', $params['c']);
				$urutan_total_jam[3] = $issueClass->getTotalIssuesByTimePeriode($m, $y, '19:00:00', '23:00:00', $params['c']);
				$urutan_total_jam[4] = $issueClass->getTotalIssuesByTimePeriode($m, $y, '23:00:00', '09:00:00', $params['c']);
				arsort($urutan_total_jam);

				$incidents = $issueClass->getIssueSummary($m, $y, $params['c'], $params['id']);
			} 
			
			require_once('fpdf/mc_table.php');
			$pdf=new PDF_MC_Table();
			$pdf->AddPage();
			
			$pdf->SetTitle($this->ident['initial']." - ".$thisCategory['category_name']." Monthly Analytics - ".$monthYear);
			$pdf->SetFont('Arial','B',12);
			$pdf->Write(5, $this->ident['initial']." - ".$thisCategory['category_name']." Monthly Analytics - ".$monthYear);
			$pdf->ln();
			$pdf->SetFont('Arial','B',10);
			$pdf->Write(5,$this->ident['site_fullname']);
			$pdf->ln(10);

			$pdf->SetFont('Arial','B',9);
			$pdf->Write(5,'INCIDENT RECAPITULATION');
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
			$pdf->Cell(36,6,'Incident','LBR',0,'C',true);
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
				$pdf->Cell(72,6,'TOTAL INCIDENTS','LBR',0,'C',true);
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
				$pdf->Write(5,'INCIDENT DETAILS');
				$pdf->Ln();

				$pdf->SetFont('Arial','',7);
				$pdf->SetFillColor(158,130,75);
				$pdf->SetTextColor(255,255,255);
				$pdf->SetFont('','B');
				$pdf->Cell(30,6,'Kejadian',1,0,'C',true);
				$pdf->Cell(40,6,'Modus',1,0,'C',true);
				$pdf->Cell(113,6,'Detail',1,0,'C',true);
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
				$pdf->Write(5,'ANALYSIS DETAILS');
				$pdf->Ln();
			
				$pdf->SetFont('Arial','B',8);
				$pdf->Write(5,'Sequence of Days with the Highest Number of Incidents');
				$pdf->Ln();
				$pdf->SetFont('Arial','',7);
				$pdf->SetFillColor(158,130,75);
				$pdf->SetTextColor(255,255,255);
				$pdf->SetFont('','B');
				$pdf->Cell(50,6,'',1,0,'C',true);
				$pdf->Cell(144,6,'Days',1,0,'C',true);
				$pdf->Ln();
				$pdf->Cell(50,6,'Type of Incidents',1,0,'C',true);
				$w[0] = 50;
				$rwidth = 144/count($rekap);
				$h = 1;
				$startw = 0;
				$starty = $pdf->getY();
				/*foreach($rekap as $r) {	
					$dt[$h] = $r['kejadian_name'];
					$w[$h] = $rwidth;
					/*$pdf->setX($startw+$rwidth);
					$pdf->setY($starty);
					$pdf->MultiCell($rwidth,5,$r['kejadian_name'],LRTB,L,true);
					$startw = $startw+$rwidth;*/
					/*$h++;
				} */
				
				if(!empty($urutan_hari_tertinggi)) { 
					$days = array('Sunday', 'Monday', 'Tuesday', 'Wednesday','Thursday','Friday', 'Saturday');
					foreach($urutan_hari_tertinggi as $uht)
					{	
						$w[$h] = 144/count($urutan_hari_tertinggi);
						$pdf->Cell($w[$h],6,$days[$uht['day']-1],1,0,'C',true);
						$h++;
					} 
				}				
				$w[$h] = 18;
				$pdf->SetFont('');
				$pdf->SetTextColor(0,0,0);
				$pdf->SetWidths($w);
				$pdf->Ln();
				
				if(!empty($rekap)) { 
					foreach($rekap as $r) {												
						$dt[0] = $r['kejadian_name'];	
						$totalUht[0] = "TOTAL";
						$z = 1;
						foreach($urutan_hari_tertinggi as $uht)
						{								
							$dt[$z] = $r['analisa_hari'][$uht['day']] ? $r['analisa_hari'][$uht['day']] : '-';
							$totalUht[$z] = $totalUht[$z] + intval($r['analisa_hari'][$uht['day']]);
							$z++;
						} 
						$pdf->Row($dt);
					}
				}
				
				$pdf->SetFont('','B');
				$pdf->Row($totalUht);
				$pdf->Ln();
				

				$pdf->SetFont('Arial','B',8);
				$pdf->Write(5,'Time Period With Highest Number of Incidents');
				$pdf->Ln();
				$pdf->SetFont('Arial','',7);
				$pdf->SetFillColor(158,130,75);
				$pdf->SetTextColor(255,255,255);
				$pdf->SetFont('','B');
				$pdf->Cell(54,6,'',1,0,'C',true);
				$pdf->Cell(140,6,'Time Period ',1,0,'C',true);
				$pdf->Ln();		
				$pdf->Cell(54,6,'Type of Incidents',1,0,'C',true);
				$pdf->Cell(28,6,'09:00 - 12:00',1,0,'C',true);
				$pdf->Cell(28,6,'12:00 - 16:00',1,0,'C',true);
				$pdf->Cell(28,6,'16:00 - 19:00',1,0,'C',true);
				$pdf->Cell(28,6,'19:00 - 23:00',1,0,'C',true);
				$pdf->Cell(28,6,'23:00 - 09:00',1,0,'C',true);
				$pdf->Ln();	
				/*if(!empty($urutan_total_jam)) { 
					$times = array('09:00 - 12:00', '12:00 - 16:00', '16:00 - 19:00', '19:00 - 23:00','23:00 - 09:00');
					$pdf->SetFont('');
					$pdf->SetTextColor(0,0,0);
					$pdf->SetWidths(array(50,25,25,25,25,25,15));	
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
				}*/
				$pdf->SetFont('');
				$pdf->SetTextColor(0,0,0);
				$pdf->SetWidths(array(54,28,28,28,28,28));
				foreach($rekap as $r) {	
					$dt2[0] = $r['kejadian_name'];	
					$totalUtj[0] = "TOTAL";
					$z = 1;
					foreach($urutan_total_jam as $key=>$utj)
					{								
						$dt2[$z] = $r['analisa_jam'][$key] ? $r['analisa_jam'][$key] : '-';
						$totalUtj[$z] = $totalUtj[$z] + intval($r['analisa_jam'][$key]);
						$z++;
					} 
					$pdf->Row($dt2);
				} 
				$pdf->SetFont('','B');
				$pdf->Row($totalUtj);
				$pdf->Ln();
			}

			
			$pdf->Ln(10);
			$pdf->SetFont('Arial','B',9);
			$pdf->Write(5,'CONCLUSION');
			$pdf->Ln();
			if(!empty($incidents)) {
				$i = 1;
				foreach($incidents as $incident) {
					$pdf->SetFont('Arial','B',8);
					$pdf->SetFillColor(158,130,75);
					$pdf->SetTextColor(255,255,255);
					$pdf->Cell(190,6,$incident['kejadian'].' ('.$incident['total_kejadian'].' Kaizen)',1,0,'C',true);
					$pdf->Ln();
					$pdf->Ln();
					$pdf->SetTextColor(0,0,0);	
					$pdf->Write(5,'Analysis');
					$pdf->Ln();
					$pdf->SetFont('Arial','',7);
					$incident['analisa'] = stripslashes($incident['analisa']);
					$incident['analisa'] = trim(preg_replace('/\s\s+/', '
', str_replace("<br>", " ", $incident['analisa'])));
					$pdf->Write(6,$incident['analisa']);
					$pdf->Ln();
					$pdf->Ln();
					$pdf->SetFont('Arial','B',8);
					$pdf->SetTextColor(0,0,0);	
					$pdf->Write(5,'Plan & Action');
					$pdf->Ln();
					$pdf->SetFont('Arial','',7);
					$incident['tindakan'] = stripslashes($incident['tindakan']);
					$incident['tindakan'] = trim(preg_replace('/\s\s+/', '
', str_replace("<br>", " ", $incident['tindakan'])));
					$pdf->Write(6,$incident['tindakan']);
					
					/*$pdf->Row(array($i,$incident['kejadian'],$incident['total_kejadian'], str_replace("<br>","\n",stripslashes($incident['analisa'])), str_replace("<br>","\n",stripslashes($incident['tindakan']))));*/
					$pdf->Ln();
					$pdf->Ln();
					$pdf->Ln();
					$i++; 
				} 
			}

			$pdf->Output('I', $this->ident['initial']."_monthly_analysis_report_".str_replace(" ","",$monthYear)."_".$params['c'].".pdf", false);
		}
		else
		{
			$this->_response->setRedirect($this->baseUrl.'/default');
			$this->_response->sendResponse();
			exit();
		}		
	}
}
?>