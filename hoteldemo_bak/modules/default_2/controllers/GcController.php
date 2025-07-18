<?php
require_once('actionControllerBase.php');

class GcController extends actionControllerBase
{
	public function addmonthlyanalysisAction() {
		if($this->addGcMonthlyAnalysis)
		{
			$params = $this->_getAllParams();			
			
			$issue_type_id = 19;

			if(!empty($params['id'])) 
			{
				$this->view->monthly_analysis_id = $params['id'];		
				Zend_Loader::LoadClass('gcClass', $this->modelDir);
				$gcClass = new gcClass();
				$monthly_analysis = $gcClass->getMonthlyAnalysisById($params['id']);
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

			//$modus = $this->cache->load("modus_".$this->site_id."_2_".$ym);
			if(empty($modus))
			{		
				Zend_Loader::LoadClass('modusClass', $this->modelDir);
				$modusClass = new modusClass();	
				$modus1 = $modusClass->getModus('2', $issue_type_id);
				if(!empty($modus1))
				{
					foreach($modus1 as &$m1)
					{
						$m1['category'] = "Housekeeping";
						$m1['category_id'] = 2;
					}
				}
				$modus2 = $modusClass->getModus('6', $issue_type_id);
				if(!empty($modus2))
				{
					foreach($modus2 as &$m2)
					{
						$m2['category'] = "Engineering";
						$m2['category_id'] = 6;
					}
				}
				$modus = array_merge($modus1, $modus2);
				//$this->cache->save($modus, "modus_".$this->site_id."_2_".$ym, array("modus_".$this->site_id."_2_".$ym), 0);
			}

			//$totalModusPerMonth = $this->cache->load("total_modus_per_month_".$this->site_id."_1_".$ym);
			if(empty($totalModusPerMonth))
			{	
				$totalModusPerMonth =  array();
				for($b=1; $b<=$m; $b++) // get rekapitulasi utk bulan ini dan bulan2 sblmnya
				{
					$totalModus = $issueClass->getIssuesByModus($b, $y, '2', 19);
					foreach($totalModus as $tm) {
						$totalModusPerMonth[$b]["2_".$tm['modus_id']] = $tm['total_modus'];
					}
					
					$totalModus2 = $issueClass->getIssuesByModus($b, $y, '6', 19);
					foreach($totalModus2 as $tm2) {
						$totalModusPerMonth[$b]["6_".$tm2['modus_id']] = $tm2['total_modus'];
					}
				}
				//$this->cache->save($totalModusPerMonth, "total_modus_per_month_".$this->site_id."_1_".$ym, array("total_modus_per_month_".$this->site_id."_1_".$ym), 0);
			}
			else{
				$totalModus = $issueClass->getIssuesByModus($m, $y, '2', 19);
				foreach($totalModus as $tm) {
					$totalModusPerMonth[$m]["2_".$tm['modus_id']] = $tm['total_modus'];
				}
				
				$totalModus2 = $issueClass->getIssuesByModus($m, $y, '6', 19);
				foreach($totalModus2 as $tm2) {
					$totalModusPerMonth[$m]["6_".$tm2['modus_id']] = $tm2['total_modus'];
				}
			}
			$i=0;
			$k = 0;
			$rekap = array();
			$total_modus_perjan = $total_modus_perfeb = $total_modus_permar = $total_modus_perapr = $total_modus_permay = $total_modus_perjun = $total_modus_perjul = $total_modus_peraug = $total_modus_persep = $total_modus_peroct = $total_modus_pernov = $total_modus_perdec = 0; 
			
			foreach($modus as $mo)
			{
				if($mo['category_id'] != $modus[$k-1]['category_id'])
				{
					if($i > 0) $rekap[$i-1]['total_modus'] = $j;
					$rekap[$i]['category'] = $mo['category'];
					$rekap[$i]['kejadian_id'] = $mo['kejadian_id'];
					
					$analisa_hari = $issueClass->getMonthlyIssuesByDay($mo['kejadian_id'], $m, $y, $mo['category_id']);
					if(!empty($analisa_hari))
					{
						foreach($analisa_hari as $ah) {
							$rekap[$i]['analisa_hari'][$ah['day']]= $ah['total'];
						}
					}
					
					$rekap[$i]['analisa_jam'][0] = $issueClass->getMonthlyIssuesByTime($mo['kejadian_id'], $m, $y, '09:00:00', '12:00:00', $mo['category_id']);
					$rekap[$i]['analisa_jam'][1] = $issueClass->getMonthlyIssuesByTime($mo['kejadian_id'], $m, $y, '12:00:00', '16:00:00', $mo['category_id']);
					$rekap[$i]['analisa_jam'][2] = $issueClass->getMonthlyIssuesByTime($mo['kejadian_id'], $m, $y, '16:00:00', '19:00:00', $mo['category_id']);
					$rekap[$i]['analisa_jam'][3] = $issueClass->getMonthlyIssuesByTime($mo['kejadian_id'], $m, $y, '19:00:00', '23:00:00', $mo['category_id']);
					$rekap[$i]['analisa_jam'][4] = $issueClass->getMonthlyIssuesByTime($mo['kejadian_id'], $m, $y, '23:00:00', '09:00:00', $mo['category_id']);
					$j = 0;
					$i++;	
				}
				
				$rekap[$i-1]['modus'][$j]['modus_name'] = $mo['modus']; 
				$rekap[$i-1]['modus'][$j]['total_modus_jan'] = $totalModusPerMonth['1'][$mo['category_id']."_".$mo['modus_id']]; 
				$rekap[$i-1]['modus'][$j]['total_modus_feb'] = $totalModusPerMonth['2'][$mo['category_id']."_".$mo['modus_id']]; 
				$rekap[$i-1]['modus'][$j]['total_modus_mar'] = $totalModusPerMonth['3'][$mo['category_id']."_".$mo['modus_id']]; 
				$rekap[$i-1]['modus'][$j]['total_modus_apr'] = $totalModusPerMonth['4'][$mo['category_id']."_".$mo['modus_id']]; 
				$rekap[$i-1]['modus'][$j]['total_modus_may'] = $totalModusPerMonth['5'][$mo['category_id']."_".$mo['modus_id']]; 
				$rekap[$i-1]['modus'][$j]['total_modus_jun'] = $totalModusPerMonth['6'][$mo['category_id']."_".$mo['modus_id']]; 
				$rekap[$i-1]['modus'][$j]['total_modus_jul'] = $totalModusPerMonth['7'][$mo['category_id']."_".$mo['modus_id']]; 
				$rekap[$i-1]['modus'][$j]['total_modus_aug'] = $totalModusPerMonth['8'][$mo['category_id']."_".$mo['modus_id']]; 
				$rekap[$i-1]['modus'][$j]['total_modus_sep'] = $totalModusPerMonth['9'][$mo['category_id']."_".$mo['modus_id']]; 
				$rekap[$i-1]['modus'][$j]['total_modus_oct'] = $totalModusPerMonth['10'][$mo['category_id']."_".$mo['modus_id']]; 
				$rekap[$i-1]['modus'][$j]['total_modus_nov'] = $totalModusPerMonth['11'][$mo['category_id']."_".$mo['modus_id']]; 
				$rekap[$i-1]['modus'][$j]['total_modus_dec'] = $totalModusPerMonth['12'][$mo['category_id']."_".$mo['modus_id']]; 
				$rekap[$i-1]['modus'][$j]['total_modus_peryear'] = intval($totalModusPerMonth['1'][$mo['category_id']."_".$mo['modus_id']]) + intval($totalModusPerMonth['2'][$mo['category_id']."_".$mo['modus_id']]) + intval($totalModusPerMonth['3'][$mo['category_id']."_".$mo['modus_id']]) + intval($totalModusPerMonth['4'][$mo['category_id']."_".$mo['modus_id']]) + intval($totalModusPerMonth['5'][$mo['category_id']."_".$mo['modus_id']]) + intval($totalModusPerMonth['6'][$mo['category_id']."_".$mo['modus_id']]) + intval($totalModusPerMonth['7'][$mo['category_id']."_".$mo['modus_id']]) + intval($totalModusPerMonth['8'][$mo['category_id']."_".$mo['modus_id']]) + intval($totalModusPerMonth['9'][$mo['category_id']."_".$mo['modus_id']]) + intval($totalModusPerMonth['10'][$mo['category_id']."_".$mo['modus_id']]) + intval($totalModusPerMonth['11'][$mo['category_id']."_".$mo['modus_id']]) + intval($totalModusPerMonth['12'][$mo['category_id']."_".$mo['modus_id']]); 
				$total_modus_perjan += intval($totalModusPerMonth['1'][$mo['category_id']."_".$mo['modus_id']]);
				$total_modus_perfeb += intval($totalModusPerMonth['2'][$mo['category_id']."_".$mo['modus_id']]);
				$total_modus_permar += intval($totalModusPerMonth['3'][$mo['category_id']."_".$mo['modus_id']]);
				$total_modus_perapr += intval($totalModusPerMonth['4'][$mo['category_id']."_".$mo['modus_id']]);
				$total_modus_permay += intval($totalModusPerMonth['5'][$mo['category_id']."_".$mo['modus_id']]);
				$total_modus_perjun += intval($totalModusPerMonth['6'][$mo['category_id']."_".$mo['modus_id']]);
				$total_modus_perjul += intval($totalModusPerMonth['7'][$mo['category_id']."_".$mo['modus_id']]);
				$total_modus_peraug += intval($totalModusPerMonth['8'][$mo['category_id']."_".$mo['modus_id']]);
				$total_modus_persep += intval($totalModusPerMonth['9'][$mo['category_id']."_".$mo['modus_id']]);
				$total_modus_peroct += intval($totalModusPerMonth['10'][$mo['category_id']."_".$mo['modus_id']]);
				$total_modus_pernov += intval($totalModusPerMonth['11'][$mo['category_id']."_".$mo['modus_id']]);
				$total_modus_perdec += intval($totalModusPerMonth['12'][$mo['category_id']."_".$mo['modus_id']]);
				
				$uraian_kejadian = $issueClass->getIssuesByModusId($mo['modus_id'], $m, $y, $mo['category_id']);
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
			
			$urutan_hari_tertinggi1 = $issueClass->getTotalIssuesByDayDescending($m, $y, '2', $issue_type_id);
			$urutan_total_jam1[0] = $issueClass->getTotalIssuesByTimePeriode($m, $y, '09:00:00', '12:00:00', '2', $issue_type_id);
			$urutan_total_jam1[1] = $issueClass->getTotalIssuesByTimePeriode($m, $y, '12:00:00', '16:00:00', '2', $issue_type_id);
			$urutan_total_jam1[2] = $issueClass->getTotalIssuesByTimePeriode($m, $y, '16:00:00', '19:00:00', '2', $issue_type_id);
			$urutan_total_jam1[3] = $issueClass->getTotalIssuesByTimePeriode($m, $y, '19:00:00', '23:00:00', '2', $issue_type_id);
			$urutan_total_jam1[4] = $issueClass->getTotalIssuesByTimePeriode($m, $y, '23:00:00', '09:00:00', '2', $issue_type_id);
			
			$urutan_hari_tertinggi2 = $issueClass->getTotalIssuesByDayDescending($m, $y, '6', $issue_type_id);
			$urutan_total_jam2[0] = $issueClass->getTotalIssuesByTimePeriode($m, $y, '09:00:00', '12:00:00', '6', $issue_type_id);
			$urutan_total_jam2[1] = $issueClass->getTotalIssuesByTimePeriode($m, $y, '12:00:00', '16:00:00', '6', $issue_type_id);
			$urutan_total_jam2[2] = $issueClass->getTotalIssuesByTimePeriode($m, $y, '16:00:00', '19:00:00', '6', $issue_type_id);
			$urutan_total_jam2[3] = $issueClass->getTotalIssuesByTimePeriode($m, $y, '19:00:00', '23:00:00', '6', $issue_type_id);
			$urutan_total_jam2[4] = $issueClass->getTotalIssuesByTimePeriode($m, $y, '23:00:00', '09:00:00', '6', $issue_type_id);
			
			$urutan_total_jam[0] = intval($urutan_total_jam1[0]) + intval($urutan_total_jam2[0]);
			$urutan_total_jam[1] = intval($urutan_total_jam1[1]) + intval($urutan_total_jam2[1]);
			$urutan_total_jam[2] = intval($urutan_total_jam1[2]) + intval($urutan_total_jam2[2]);
			$urutan_total_jam[3] = intval($urutan_total_jam1[3]) + intval($urutan_total_jam2[3]);
			$urutan_total_jam[4] = intval($urutan_total_jam1[4]) + intval($urutan_total_jam2[4]);
			arsort($urutan_total_jam);
			
			$urutan_hari_tertinggi3 = array_merge($urutan_hari_tertinggi1, $urutan_hari_tertinggi2);
			$ht = array();
			if(!empty($urutan_hari_tertinggi3))
			{
				foreach($urutan_hari_tertinggi3 as $uht)
				{
					$ht[$uht['day']] = intval($ht[$uht['day']]) + $uht['total'];
				}
			}
			$urutan_hari_tertinggi = array();
			if(!empty($ht))
			{
				$i = 0;
				foreach($ht as $key=>$val)
				{
					$urutan_hari_tertinggi[$i]['day'] = $key;
					$urutan_hari_tertinggi[$i]['total'] = $val;
					$i++;
				}
			}
			$this->view->urutan_hari_tertinggi = $urutan_hari_tertinggi;
			
			$this->view->urutan_total_jam = $urutan_total_jam;
			
			$incidents1 = $issueClass->getGcIssueSummary($m, $y, 2, $params['id'], $issue_type_id);
			$incidents2 = $issueClass->getGcIssueSummary($m, $y, 6, $params['id'], $issue_type_id);
			$incidents = array_merge($incidents1, $incidents2);
			if(!empty($incidents))
			{
				foreach($incidents as &$inc)
				{
					if($inc['category_id'] == 2) $inc['category'] = "Housekeeping";
					if($inc['category_id'] == 6) $inc['category'] = "Engineering";
				}
			}
			$this->view->incidents = $incidents;

			$listIssues1 = $issueClass->getMonthlyAnalysisIssues($m, $y, '2', $issue_type_id);
			$listIssues2 = $issueClass->getMonthlyAnalysisIssues($m, $y, '6', $issue_type_id);
			$listIssues = array_merge($listIssues1, $listIssues2);
			foreach($listIssues as &$issue)
			{
				$tgl = explode(" ", $issue['issue_date']);
				$issue['date'] = date("j M Y", strtotime($tgl[0]))." ".$tgl[1];
			}
			$this->view->listIssues = $listIssues;


			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "Add Gc Monthly Analysis";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);	

			$this->renderTemplate('form_gc_monthly_analysis.tpl'); 
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

		$params['user_id'] = $this->ident['user_id'];
		if(empty($params['monthly_analysis_id']))
		{
			Zend_Loader::LoadClass('gcClass', $this->modelDir);
			$gcClass = new gcClass();
			$params['monthly_analysis_id'] = $gcClass->saveMonthlyAnalysis($params);
		}

		Zend_Loader::LoadClass('monthlyanalysissummaryClass', $this->modelDir);
		$monthlyanalysissummaryClass = new monthlyanalysissummaryClass();
		$data = array();
		$i=0;
		foreach($params['summary_id'] as $summary_id)
		{
			$data['summary_id'] = $summary_id;
			$data['monthly_analysis_id'] = $params['monthly_analysis_id'];
			$data['category_id'] = $params['category_id'][$i];
			$data['modus_id'] = $params['modus_id'][$i];
			$data['analisa'] = addslashes(str_replace("\n","<br>",$params['analisa'][$i]));
			$data['tindakan'] = addslashes(str_replace("\n","<br>",$params['tindakan'][$i]));
			$data['user_id'] = $this->ident['user_id'];
			$monthlyanalysissummaryClass->saveGcMonthlyAnalysis($data);
			$i++;
		}

		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "Save Gc Monthly Analysis";
		$logData['data'] = json_encode($params);
		$logsTable->insertLogs($logData);	

		$this->_response->setRedirect($this->baseUrl.'/default/gc/viewmonthlyanalysis');
		$this->_response->sendResponse();
		exit();
	}

	public function viewmonthlyanalysisAction() {
		$params = $this->_getAllParams();
		$this->view->ident = $this->ident;
		if(empty($params['start'])) $params['start'] = '0';
		$params['pagesize'] = 10;
		$this->view->start = $params['start'];
		Zend_Loader::LoadClass('gcClass', $this->modelDir);
		$gcClass = new gcClass();
		$monthlyAnalysis = $gcClass->getMonthlyAnalysis($params);
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

		$totalMonthlyAnalysis = $gcClass->getTotalMonthlyAnalysis();
		if($totalMonthlyAnalysis > 10)
		{
			if($params['start'] >= 10)
			{
				$this->view->firstPageUrl = "/default/gc/viewmonthlyanalysis";
				$this->view->prevUrl = "/default/gc/viewmonthlyanalysis/start/".($params['start']-$params['pagesize']);
			}
			if($params['start'] < (floor(($totalMonthlyAnalysis-1)/10)*10))
			{
				$this->view->nextUrl = "/default/gc/viewmonthlyanalysis/start/".($params['start']+$params['pagesize']);
				$this->view->lastPageUrl = "/default/gc/viewmonthlyanalysis/start/".(floor(($totalMonthlyAnalysis-1)/10)*10);
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
		$logData['action'] = "View Gc Monthly Analysis List";
		$logData['data'] = "";
		$logsTable->insertLogs($logData);	

		$this->renderTemplate('view_gc_monthly_analysis.tpl'); 
	}

	public function viewdetailmonthlyanalysisAction() {
		$params = $this->_getAllParams();
		
		$issue_type_id = 19;

		if(!empty($params['id'])) 
		{
			$this->view->monthly_analysis_id = $params['id'];		
			Zend_Loader::LoadClass('gcClass', $this->modelDir);
			$gcClass = new gcClass();
			$monthly_analysis = $gcClass->getMonthlyAnalysisById($params['id']);
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

			//$modus = $this->cache->load("modus_".$this->site_id."_2_");
			if(empty($modus))
			{		
				Zend_Loader::LoadClass('modusClass', $this->modelDir);
				$modusClass = new modusClass();	
				$modus1 = $modusClass->getModus('2', $issue_type_id);
				if(!empty($modus1))
				{
					foreach($modus1 as &$m1)
					{
						$m1['category'] = "Housekeeping";
						$m1['category_id'] = 2;
					}
				}
				$modus2 = $modusClass->getModus('6', $issue_type_id);
				if(!empty($modus2))
				{
					foreach($modus2 as &$m2)
					{
						$m2['category'] = "Engineering";
						$m2['category_id'] = 6;
					}
				}
				$modus = array_merge($modus1, $modus2);
			}

			//$totalModusPerMonth = $this->cache->load("total_modus_per_month_".$this->site_id."_1_".$ym);
			if(empty($totalModusPerMonth))
			{	
				$totalModusPerMonth =  array();
				for($b=1; $b<=$m; $b++) // get rekapitulasi utk bulan ini dan bulan2 sblmnya
				{
					$totalModus = $issueClass->getIssuesByModus($b, $y, '2', $issue_type_id);
					foreach($totalModus as $tm) {
						$totalModusPerMonth[$b]["2_".$tm['modus_id']] = $tm['total_modus'];
					}
					
					$totalModus2 = $issueClass->getIssuesByModus($b, $y, '6', $issue_type_id);
					foreach($totalModus2 as $tm2) {
						$totalModusPerMonth[$b]["6_".$tm2['modus_id']] = $tm2['total_modus'];
					}
				}
			}
			else{
				$totalModus = $issueClass->getIssuesByModus($m, $y, '2', $issue_type_id);
				foreach($totalModus as $tm) {
					$totalModusPerMonth[$m]["2_".$tm['modus_id']] = $tm['total_modus'];
				}
				
				$totalModus2 = $issueClass->getIssuesByModus($m, $y, '6', $issue_type_id);
				foreach($totalModus2 as $tm2) {
					$totalModusPerMonth[$m]["6_".$tm2['modus_id']] = $tm2['total_modus'];
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
					if($mo['category_id'] != $modus[$k-1]['category_id'])
					{
						if($i > 0) $rekap[$i-1]['total_modus'] = $j;
						$rekap[$i]['category'] = $mo['category'];
						$rekap[$i]['kejadian_id'] = $mo['kejadian_id'];
						$analisa_hari = $issueClass->getMonthlyIssuesByDay($mo['kejadian_id'], $m, $y, $mo['category_id']);
						if(!empty($analisa_hari))
						{
							foreach($analisa_hari as $ah) {
								$rekap[$i]['analisa_hari'][$ah['day']]= $ah['total'];
							}
						}
						
						$rekap[$i]['analisa_jam'][0] = $issueClass->getMonthlyIssuesByTime($mo['kejadian_id'], $m, $y, '09:00:00', '12:00:00', $mo['category_id']);
						$rekap[$i]['analisa_jam'][1] = $issueClass->getMonthlyIssuesByTime($mo['kejadian_id'], $m, $y, '12:00:00', '16:00:00', $mo['category_id']);
						$rekap[$i]['analisa_jam'][2] = $issueClass->getMonthlyIssuesByTime($mo['kejadian_id'], $m, $y, '16:00:00', '19:00:00', $mo['category_id']);
						$rekap[$i]['analisa_jam'][3] = $issueClass->getMonthlyIssuesByTime($mo['kejadian_id'], $m, $y, '19:00:00', '23:00:00', $mo['category_id']);
						$rekap[$i]['analisa_jam'][4] = $issueClass->getMonthlyIssuesByTime($mo['kejadian_id'], $m, $y, '23:00:00', '09:00:00', $mo['category_id']);
						$j = 0;
						$i++;	
					}
					$rekap[$i-1]['modus'][$j]['modus_name'] = $mo['modus']; 
					$rekap[$i-1]['modus'][$j]['total_modus_jan'] = $totalModusPerMonth['1'][$mo['category_id']."_".$mo['modus_id']]; 
					$rekap[$i-1]['modus'][$j]['total_modus_feb'] = $totalModusPerMonth['2'][$mo['category_id']."_".$mo['modus_id']]; 
					$rekap[$i-1]['modus'][$j]['total_modus_mar'] = $totalModusPerMonth['3'][$mo['category_id']."_".$mo['modus_id']]; 
					$rekap[$i-1]['modus'][$j]['total_modus_apr'] = $totalModusPerMonth['4'][$mo['category_id']."_".$mo['modus_id']]; 
					$rekap[$i-1]['modus'][$j]['total_modus_may'] = $totalModusPerMonth['5'][$mo['category_id']."_".$mo['modus_id']]; 
					$rekap[$i-1]['modus'][$j]['total_modus_jun'] = $totalModusPerMonth['6'][$mo['category_id']."_".$mo['modus_id']]; 
					$rekap[$i-1]['modus'][$j]['total_modus_jul'] = $totalModusPerMonth['7'][$mo['category_id']."_".$mo['modus_id']]; 
					$rekap[$i-1]['modus'][$j]['total_modus_aug'] = $totalModusPerMonth['8'][$mo['category_id']."_".$mo['modus_id']]; 
					$rekap[$i-1]['modus'][$j]['total_modus_sep'] = $totalModusPerMonth['9'][$mo['category_id']."_".$mo['modus_id']]; 
					$rekap[$i-1]['modus'][$j]['total_modus_oct'] = $totalModusPerMonth['10'][$mo['category_id']."_".$mo['modus_id']]; 
					$rekap[$i-1]['modus'][$j]['total_modus_nov'] = $totalModusPerMonth['11'][$mo['category_id']."_".$mo['modus_id']]; 
					$rekap[$i-1]['modus'][$j]['total_modus_dec'] = $totalModusPerMonth['12'][$mo['category_id']."_".$mo['modus_id']]; 
					$rekap[$i-1]['modus'][$j]['total_modus_peryear'] = intval($totalModusPerMonth['1'][$mo['category_id']."_".$mo['modus_id']]) + intval($totalModusPerMonth['2'][$mo['category_id']."_".$mo['modus_id']]) + intval($totalModusPerMonth['3'][$mo['category_id']."_".$mo['modus_id']]) + intval($totalModusPerMonth['4'][$mo['category_id']."_".$mo['modus_id']]) + intval($totalModusPerMonth['5'][$mo['category_id']."_".$mo['modus_id']]) + intval($totalModusPerMonth['6'][$mo['category_id']."_".$mo['modus_id']]) + intval($totalModusPerMonth['7'][$mo['category_id']."_".$mo['modus_id']]) + intval($totalModusPerMonth['8'][$mo['category_id']."_".$mo['modus_id']]) + intval($totalModusPerMonth['9'][$mo['category_id']."_".$mo['modus_id']]) + intval($totalModusPerMonth['10'][$mo['category_id']."_".$mo['modus_id']]) + intval($totalModusPerMonth['11'][$mo['category_id']."_".$mo['modus_id']]) + intval($totalModusPerMonth['12'][$mo['category_id']."_".$mo['modus_id']]); 
					$total_modus_perjan += intval($totalModusPerMonth['1'][$mo['category_id']."_".$mo['modus_id']]);
					$total_modus_perfeb += intval($totalModusPerMonth['2'][$mo['category_id']."_".$mo['modus_id']]);
					$total_modus_permar += intval($totalModusPerMonth['3'][$mo['category_id']."_".$mo['modus_id']]);
					$total_modus_perapr += intval($totalModusPerMonth['4'][$mo['category_id']."_".$mo['modus_id']]);
					$total_modus_permay += intval($totalModusPerMonth['5'][$mo['category_id']."_".$mo['modus_id']]);
					$total_modus_perjun += intval($totalModusPerMonth['6'][$mo['category_id']."_".$mo['modus_id']]);
					$total_modus_perjul += intval($totalModusPerMonth['7'][$mo['category_id']."_".$mo['modus_id']]);
					$total_modus_peraug += intval($totalModusPerMonth['8'][$mo['category_id']."_".$mo['modus_id']]);
					$total_modus_persep += intval($totalModusPerMonth['9'][$mo['category_id']."_".$mo['modus_id']]);
					$total_modus_peroct += intval($totalModusPerMonth['10'][$mo['category_id']."_".$mo['modus_id']]);
					$total_modus_pernov += intval($totalModusPerMonth['11'][$mo['category_id']."_".$mo['modus_id']]);
					$total_modus_perdec += intval($totalModusPerMonth['12'][$mo['category_id']."_".$mo['modus_id']]);
					
					$uraian_kejadian = $issueClass->getIssuesByModusId($mo['modus_id'], $m, $y, $mo['category_id']);
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
			
			$urutan_hari_tertinggi1 = $issueClass->getTotalIssuesByDayDescending($m, $y, '2', $issue_type_id);
			$urutan_total_jam1[0] = $issueClass->getTotalIssuesByTimePeriode($m, $y, '09:00:00', '12:00:00', '2', $issue_type_id);
			$urutan_total_jam1[1] = $issueClass->getTotalIssuesByTimePeriode($m, $y, '12:00:00', '16:00:00', '2', $issue_type_id);
			$urutan_total_jam1[2] = $issueClass->getTotalIssuesByTimePeriode($m, $y, '16:00:00', '19:00:00', '2', $issue_type_id9);
			$urutan_total_jam1[3] = $issueClass->getTotalIssuesByTimePeriode($m, $y, '19:00:00', '23:00:00', '2', $issue_type_id);
			$urutan_total_jam1[4] = $issueClass->getTotalIssuesByTimePeriode($m, $y, '23:00:00', '09:00:00', '2', $issue_type_id);
			
			$urutan_hari_tertinggi2 = $issueClass->getTotalIssuesByDayDescending($m, $y, '6', $issue_type_id);
			$urutan_total_jam2[0] = $issueClass->getTotalIssuesByTimePeriode($m, $y, '09:00:00', '12:00:00', '6', $issue_type_id);
			$urutan_total_jam2[1] = $issueClass->getTotalIssuesByTimePeriode($m, $y, '12:00:00', '16:00:00', '6', $issue_type_id);
			$urutan_total_jam2[2] = $issueClass->getTotalIssuesByTimePeriode($m, $y, '16:00:00', '19:00:00', '6', $issue_type_id);
			$urutan_total_jam2[3] = $issueClass->getTotalIssuesByTimePeriode($m, $y, '19:00:00', '23:00:00', '6', $issue_type_id);
			$urutan_total_jam2[4] = $issueClass->getTotalIssuesByTimePeriode($m, $y, '23:00:00', '09:00:00', '6', $issue_type_id);
			
			$urutan_total_jam[0] = intval($urutan_total_jam1[0]) + intval($urutan_total_jam2[0]);
			$urutan_total_jam[1] = intval($urutan_total_jam1[1]) + intval($urutan_total_jam2[1]);
			$urutan_total_jam[2] = intval($urutan_total_jam1[2]) + intval($urutan_total_jam2[2]);
			$urutan_total_jam[3] = intval($urutan_total_jam1[3]) + intval($urutan_total_jam2[3]);
			$urutan_total_jam[4] = intval($urutan_total_jam1[4]) + intval($urutan_total_jam2[4]);
			arsort($urutan_total_jam);
			
			$urutan_hari_tertinggi3 = array_merge($urutan_hari_tertinggi1, $urutan_hari_tertinggi2);
			$ht = array();
			if(!empty($urutan_hari_tertinggi3))
			{
				foreach($urutan_hari_tertinggi3 as $uht)
				{
					$ht[$uht['day']] = intval($ht[$uht['day']]) + $uht['total'];
				}
			}
			$urutan_hari_tertinggi = array();
			if(!empty($ht))
			{
				$i = 0;
				foreach($ht as $key=>$val)
				{
					$urutan_hari_tertinggi[$i]['day'] = $key;
					$urutan_hari_tertinggi[$i]['total'] = $val;
					$i++;
				}
			}
			$this->view->urutan_hari_tertinggi = $urutan_hari_tertinggi;
			
			$this->view->urutan_total_jam = $urutan_total_jam;
		
			$incidents1 = $issueClass->getGcIssueSummary($m, $y, 2, $params['id'], $issue_type_id);
			$incidents2 = $issueClass->getGcIssueSummary($m, $y, 6, $params['id'], $issue_type_id);
			$incidents = array_merge($incidents1, $incidents2);
			if(!empty($incidents))
			{
				foreach($incidents as &$inc)
				{
					if($inc['category_id'] == 2) $inc['category'] = "Housekeeping";
					if($inc['category_id'] == 6) $inc['category'] = "Engineering";
				}
			}
			$this->view->incidents = $incidents;				

			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "View Gc Monthly Analysis Detail";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);	

			//echo "<pre>"; print_r($pelaku_tertangkap_detail); exit();
			$this->renderTemplate('gc_monthly_analysis_detail.tpl'); 
		}
	}

	public function downloadmonthlyanalysisAction() {		
		$params = $this->_getAllParams();

		$issue_type_id = 19;
		
		if(!empty($params['id']))
		{			
			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "Export Gc Monthly Analysis Report to PDF";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);

			$monthly_analysis_id = $params['id'];		
			Zend_Loader::LoadClass('gcClass', $this->modelDir);
			$gcClass = new gcClass();
			$monthly_analysis = $gcClass->getMonthlyAnalysisById($params['id']);
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
				$modus1 = $modusClass->getModus('2', $issue_type_id);
				if(!empty($modus1))
				{
					foreach($modus1 as &$m1)
					{
						$m1['category'] = "Housekeeping";
						$m1['category_id'] = 2;
					}
				}
				$modus2 = $modusClass->getModus('6', $issue_type_id);
				if(!empty($modus2))
				{
					foreach($modus2 as &$m2)
					{
						$m2['category'] = "Engineering";
						$m2['category_id'] = 6;
					}
				}
				$modus = array_merge($modus1, $modus2);
			}

			//$totalModusPerMonth = $this->cache->load("total_modus_per_month_".$this->site_id."_1_".$ym);
			if(empty($totalModusPerMonth))
			{	
				$totalModusPerMonth =  array();
				for($b=1; $b<=$m; $b++) // get rekapitulasi utk bulan ini dan bulan2 sblmnya
				{
					$totalModus = $issueClass->getIssuesByModus($b, $y, '2', 19);
					foreach($totalModus as $tm) {
						$totalModusPerMonth[$b]["2_".$tm['modus_id']] = $tm['total_modus'];
					}
					
					$totalModus2 = $issueClass->getIssuesByModus($b, $y, '6', 19);
					foreach($totalModus2 as $tm2) {
						$totalModusPerMonth[$b]["6_".$tm2['modus_id']] = $tm2['total_modus'];
					}
				}
			}
			else{
				$totalModus = $issueClass->getIssuesByModus($m, $y, '2', 19);
				foreach($totalModus as $tm) {
					$totalModusPerMonth[$m]["2_".$tm['modus_id']] = $tm['total_modus'];
				}
				
				$totalModus2 = $issueClass->getIssuesByModus($m, $y, '6', 19);
				foreach($totalModus2 as $tm2) {
					$totalModusPerMonth[$m]["6_".$tm2['modus_id']] = $tm2['total_modus'];
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
					if($mo['category_id'] != $modus[$k-1]['category_id'])
					{
						if($i > 0) $rekap[$i-1]['total_modus'] = $j;
						$rekap[$i]['category'] = $mo['category'];
						$rekap[$i]['kejadian_id'] = $mo['kejadian_id'];
						$analisa_hari = $issueClass->getMonthlyIssuesByDay($mo['kejadian_id'], $m, $y, $mo['category_id']);
						if(!empty($analisa_hari))
						{
							foreach($analisa_hari as $ah) {
								$rekap[$i]['analisa_hari'][$ah['day']]= $ah['total'];
							}
						}
						
						$rekap[$i]['analisa_jam'][0] = $issueClass->getMonthlyIssuesByTime($mo['kejadian_id'], $m, $y, '09:00:00', '12:00:00', $mo['category_id']);
						$rekap[$i]['analisa_jam'][1] = $issueClass->getMonthlyIssuesByTime($mo['kejadian_id'], $m, $y, '12:00:00', '16:00:00', $mo['category_id']);
						$rekap[$i]['analisa_jam'][2] = $issueClass->getMonthlyIssuesByTime($mo['kejadian_id'], $m, $y, '16:00:00', '19:00:00', $mo['category_id']);
						$rekap[$i]['analisa_jam'][3] = $issueClass->getMonthlyIssuesByTime($mo['kejadian_id'], $m, $y, '19:00:00', '23:00:00', $mo['category_id']);
						$rekap[$i]['analisa_jam'][4] = $issueClass->getMonthlyIssuesByTime($mo['kejadian_id'], $m, $y, '23:00:00', '09:00:00', $mo['category_id']);
						$j = 0;
						$i++;	
					}
					$rekap[$i-1]['modus'][$j]['modus_name'] = $mo['modus']; 
					$rekap[$i-1]['modus'][$j]['total_modus_jan'] = $totalModusPerMonth['1'][$mo['category_id']."_".$mo['modus_id']]; 
					$rekap[$i-1]['modus'][$j]['total_modus_feb'] = $totalModusPerMonth['2'][$mo['category_id']."_".$mo['modus_id']]; 
					$rekap[$i-1]['modus'][$j]['total_modus_mar'] = $totalModusPerMonth['3'][$mo['category_id']."_".$mo['modus_id']]; 
					$rekap[$i-1]['modus'][$j]['total_modus_apr'] = $totalModusPerMonth['4'][$mo['category_id']."_".$mo['modus_id']]; 
					$rekap[$i-1]['modus'][$j]['total_modus_may'] = $totalModusPerMonth['5'][$mo['category_id']."_".$mo['modus_id']]; 
					$rekap[$i-1]['modus'][$j]['total_modus_jun'] = $totalModusPerMonth['6'][$mo['category_id']."_".$mo['modus_id']]; 
					$rekap[$i-1]['modus'][$j]['total_modus_jul'] = $totalModusPerMonth['7'][$mo['category_id']."_".$mo['modus_id']]; 
					$rekap[$i-1]['modus'][$j]['total_modus_aug'] = $totalModusPerMonth['8'][$mo['category_id']."_".$mo['modus_id']]; 
					$rekap[$i-1]['modus'][$j]['total_modus_sep'] = $totalModusPerMonth['9'][$mo['category_id']."_".$mo['modus_id']]; 
					$rekap[$i-1]['modus'][$j]['total_modus_oct'] = $totalModusPerMonth['10'][$mo['category_id']."_".$mo['modus_id']]; 
					$rekap[$i-1]['modus'][$j]['total_modus_nov'] = $totalModusPerMonth['11'][$mo['category_id']."_".$mo['modus_id']]; 
					$rekap[$i-1]['modus'][$j]['total_modus_dec'] = $totalModusPerMonth['12'][$mo['category_id']."_".$mo['modus_id']]; 
					$rekap[$i-1]['modus'][$j]['total_modus_peryear'] = intval($totalModusPerMonth['1'][$mo['category_id']."_".$mo['modus_id']]) + intval($totalModusPerMonth['2'][$mo['category_id']."_".$mo['modus_id']]) + intval($totalModusPerMonth['3'][$mo['category_id']."_".$mo['modus_id']]) + intval($totalModusPerMonth['4'][$mo['category_id']."_".$mo['modus_id']]) + intval($totalModusPerMonth['5'][$mo['category_id']."_".$mo['modus_id']]) + intval($totalModusPerMonth['6'][$mo['category_id']."_".$mo['modus_id']]) + intval($totalModusPerMonth['7'][$mo['category_id']."_".$mo['modus_id']]) + intval($totalModusPerMonth['8'][$mo['category_id']."_".$mo['modus_id']]) + intval($totalModusPerMonth['9'][$mo['category_id']."_".$mo['modus_id']]) + intval($totalModusPerMonth['10'][$mo['category_id']."_".$mo['modus_id']]) + intval($totalModusPerMonth['11'][$mo['category_id']."_".$mo['modus_id']]) + intval($totalModusPerMonth['12'][$mo['category_id']."_".$mo['modus_id']]); 
					$total_modus_perjan += intval($totalModusPerMonth['1'][$mo['category_id']."_".$mo['modus_id']]);
					$total_modus_perfeb += intval($totalModusPerMonth['2'][$mo['category_id']."_".$mo['modus_id']]);
					$total_modus_permar += intval($totalModusPerMonth['3'][$mo['category_id']."_".$mo['modus_id']]);
					$total_modus_perapr += intval($totalModusPerMonth['4'][$mo['category_id']."_".$mo['modus_id']]);
					$total_modus_permay += intval($totalModusPerMonth['5'][$mo['category_id']."_".$mo['modus_id']]);
					$total_modus_perjun += intval($totalModusPerMonth['6'][$mo['category_id']."_".$mo['modus_id']]);
					$total_modus_perjul += intval($totalModusPerMonth['7'][$mo['category_id']."_".$mo['modus_id']]);
					$total_modus_peraug += intval($totalModusPerMonth['8'][$mo['category_id']."_".$mo['modus_id']]);
					$total_modus_persep += intval($totalModusPerMonth['9'][$mo['category_id']."_".$mo['modus_id']]);
					$total_modus_peroct += intval($totalModusPerMonth['10'][$mo['category_id']."_".$mo['modus_id']]);
					$total_modus_pernov += intval($totalModusPerMonth['11'][$mo['category_id']."_".$mo['modus_id']]);
					$total_modus_perdec += intval($totalModusPerMonth['12'][$mo['category_id']."_".$mo['modus_id']]);
					
					$uraian_kejadian = $issueClass->getIssuesByModusId($mo['modus_id'], $m, $y, $mo['category_id']);
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
				
				$urutan_hari_tertinggi1 = $issueClass->getTotalIssuesByDayDescending($m, $y, '2', $issue_type_id);
				$urutan_total_jam1[0] = $issueClass->getTotalIssuesByTimePeriode($m, $y, '09:00:00', '12:00:00', '2', $issue_type_id);
				$urutan_total_jam1[1] = $issueClass->getTotalIssuesByTimePeriode($m, $y, '12:00:00', '16:00:00', '2', $issue_type_id);
				$urutan_total_jam1[2] = $issueClass->getTotalIssuesByTimePeriode($m, $y, '16:00:00', '19:00:00', '2', $issue_type_id);
				$urutan_total_jam1[3] = $issueClass->getTotalIssuesByTimePeriode($m, $y, '19:00:00', '23:00:00', '2', $issue_type_id);
				$urutan_total_jam1[4] = $issueClass->getTotalIssuesByTimePeriode($m, $y, '23:00:00', '09:00:00', '2', $issue_type_id);
				
				$urutan_hari_tertinggi2 = $issueClass->getTotalIssuesByDayDescending($m, $y, '6', $issue_type_id);
				$urutan_total_jam2[0] = $issueClass->getTotalIssuesByTimePeriode($m, $y, '09:00:00', '12:00:00', '6', $issue_type_id);
				$urutan_total_jam2[1] = $issueClass->getTotalIssuesByTimePeriode($m, $y, '12:00:00', '16:00:00', '6', $issue_type_id);
				$urutan_total_jam2[2] = $issueClass->getTotalIssuesByTimePeriode($m, $y, '16:00:00', '19:00:00', '6', $issue_type_id);
				$urutan_total_jam2[3] = $issueClass->getTotalIssuesByTimePeriode($m, $y, '19:00:00', '23:00:00', '6', $issue_type_id);
				$urutan_total_jam2[4] = $issueClass->getTotalIssuesByTimePeriode($m, $y, '23:00:00', '09:00:00', '6', $issue_type_id);
				
				$urutan_total_jam[0] = intval($urutan_total_jam1[0]) + intval($urutan_total_jam2[0]);
				$urutan_total_jam[1] = intval($urutan_total_jam1[1]) + intval($urutan_total_jam2[1]);
				$urutan_total_jam[2] = intval($urutan_total_jam1[2]) + intval($urutan_total_jam2[2]);
				$urutan_total_jam[3] = intval($urutan_total_jam1[3]) + intval($urutan_total_jam2[3]);
				$urutan_total_jam[4] = intval($urutan_total_jam1[4]) + intval($urutan_total_jam2[4]);
				arsort($urutan_total_jam);
				
				$urutan_hari_tertinggi3 = array_merge($urutan_hari_tertinggi1, $urutan_hari_tertinggi2);
				$ht = array();
				if(!empty($urutan_hari_tertinggi3))
				{
					foreach($urutan_hari_tertinggi3 as $uht)
					{
						$ht[$uht['day']] = intval($ht[$uht['day']]) + $uht['total'];
					}
				}
				$urutan_hari_tertinggi = array();
				if(!empty($ht))
				{
					$i = 0;
					foreach($ht as $key=>$val)
					{
						$urutan_hari_tertinggi[$i]['day'] = $key;
						$urutan_hari_tertinggi[$i]['total'] = $val;
						$i++;
					}
				}

				$incidents1 = $issueClass->getGcIssueSummary($m, $y, 2, $params['id'], $issue_type_id);
				$incidents2 = $issueClass->getGcIssueSummary($m, $y, 6, $params['id'], $issue_type_id);
				$incidents = array_merge($incidents1, $incidents2);
				if(!empty($incidents))
				{
					foreach($incidents as &$inc)
					{
						if($inc['category_id'] == 2) $inc['category'] = "Housekeeping";
						if($inc['category_id'] == 6) $inc['category'] = "Engineering";
					}
				}
				$incidents = $incidents;
			} 

			require_once('fpdf/mc_table.php');
			$pdf=new PDF_MC_Table();
			$pdf->AddPage();
			
			$pdf->SetTitle($this->ident['initial']." - GUEST COMPLAIN MONTHLY ANALYTICS - ".$monthYear);
			$pdf->SetFont('Arial','B',12);
			$pdf->Write(5, $this->ident['initial']." - GUEST COMPLAIN MONTHLY ANALYTICS - ".$monthYear);
			$pdf->ln();
			$pdf->SetFont('Arial','B',10);
			$pdf->Write(5,$this->ident['site_fullname']);
			$pdf->ln(10);

			$pdf->SetFont('Arial','B',9);
			$pdf->Write(5,'PERFORMANCE');
			$pdf->Ln();
			$pdf->SetFont('Arial','B',8);
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
			$pdf->Cell(36,6,'Department','LBR',0,'C',true);
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
						
						//echo "<pre>"; print_r($rekapitulasi['modus']); exit();
						foreach($rekapitulasi['modus'] as $mo) {
							if($j == 0 || $j > $rekapitulasi['total_modus'])
							{	
								$j = 0;
								$pdf->Row(array($rekapitulasi['category'],$mo['modus_name'],$mo['total_modus_jan'],$mo['total_modus_feb'],$mo['total_modus_mar'],$mo['total_modus_apr'],$mo['total_modus_may'],$mo['total_modus_jun'],$mo['total_modus_jul'],$mo['total_modus_aug'],$mo['total_modus_sep'],$mo['total_modus_oct'],$mo['total_modus_nov'],$mo['total_modus_dec'], $mo['total_modus_peryear']));
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
				$pdf->Cell(30,6,'Department',1,0,'C',true);
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
										$pdf->Row(array($rekapitulasi['category'], $mo['modus_name'],trim($uk),$mo['total_modus_cur_month']));
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
									$pdf->Row(array($rekapitulasi['category'], $mo['modus_name'],"",$mo['total_modus_cur_month']));
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
				$pdf->Cell(20,6,'',1,0,'C',true);
				$pdf->Cell(158,6,'Jenis Kejadian '.$monthYear,1,0,'C',true);
				$pdf->Cell(15,6,'',1,0,'C',true);
				$pdf->Ln();
				$dt1[0] = 'Hari';
				$w[0] = 20;
				$rwidth = 158/count($rekap);
				$h = 1;
				$startw = 0;
				$starty = $pdf->getY();
				foreach($rekap as $r) {	
					$dt1[$h] = $r['category'];
					$w[$h] = $rwidth;
					/*$pdf->setX($startw+$rwidth);
					$pdf->setY($starty);
					$pdf->MultiCell($rwidth,5,$r['kejadian_name'],LRTB,L,true);
					$startw = $startw+$rwidth;*/
					$h++;
				} 
				
				$w[$h] = 15;
				$dt1[$h] = 'Total';
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
					$dt1[$f] = $r['category'];
					$f++;
				} 
				$dt1[$f] = 'Total';
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

			} 
			
			$pdf->Ln(10);
			$pdf->SetFont('Arial','B',9);
			$pdf->Write(5,'KESIMPULAN UMUM');
			$pdf->Ln();
			/*$pdf->SetFont('Arial','B',7);
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
			$pdf->SetWidths(array(6, 27, 15, 72, 72));*/
			if(!empty($incidents)) {
				$i = 1;
				foreach($incidents as $incident) {
					if($incident['category_id'] == 2) $incident['category'] = "Housekeeping";
					if($incident['category_id'] == 6) $incident['category'] = "Engineering";
					
					$pdf->SetFont('Arial','B',8);
					$pdf->SetFillColor(158,130,75);
					$pdf->SetTextColor(255,255,255);
					$pdf->Cell(190,6,$incident['category'].' - '.$incident['modus'].' ('.$incident['total_modus'].' Kaizen)',1,0,'C',true);
					$pdf->Ln();
					$pdf->Ln();
					$pdf->SetTextColor(0,0,0);	
					$pdf->Write(5,'Data hasil Investigasi');
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
					$pdf->Write(5,'Langkah Antisipatif');
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

			$pdf->Output('I', $this->ident['initial']."_gc_monthly_analysis_report_".str_replace(" ","",$monthYear).".pdf", false);
		}		
	}
	
}

?>
