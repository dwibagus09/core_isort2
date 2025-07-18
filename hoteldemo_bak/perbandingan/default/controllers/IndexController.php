<?php
require_once('actionControllerBase.php');
class IndexController extends actionControllerBase
{
	public function cleancacheAction() {
		//$this->cleancache();
		
		echo "Cache Cleaned.";	
	}

	public function docleancacheAction() {
		$this->cleancache();
		
		echo "Cache Cleaned.";	
	}
	
	function indexAction()
    {
		$this->view->ident = $this->ident;		

		$category = $this->loadModel('category');
		
		Zend_Loader::LoadClass('issueClass', $this->modelDir);
		$issueClass = new issueClass();

        $params['start_date'] = "2020-01-01";
        $params['end_date'] = date("Y-m-d");
        $this->view->totalIssues = $issueClass->getTotalAllIssues($params);
		$this->view->totalIssuesSite = $issueClass->getTotalIssuesBySites($params);
		$this->view->totalIssuesDept = $issueClass->getTotalIssuesByDept($params);
		$params['solved'] = "0";
        $this->view->totalOpenedIssues = $issueClass->getTotalIssuesBySites($params);
		$params['solved'] = "1";
        $this->view->totalClosedIssues = $issueClass->getTotalIssuesBySites($params);
		
		$issuesAvgDuration = $issueClass->getAvgIssuesDuration($params);
		$avgDuration = array();
		$s = 0;
		foreach($issuesAvgDuration as $avg) {
			$avgDuration[$s]['sitename'] = $avg['site_name'];
			$avgDuration[$s]['duration'] = round(($avg['time_diff'] / $avg['total_data'] / 86400),1);
			$s++;
		}
		$this->view->issuesAvgDuration = $avgDuration;
        
        /*Zend_Loader::LoadClass('actionplanClass', $this->modelDir);
		$actionplanClass = new actionplanClass();
        $this->view->done = $actionplanClass->getTotalDone($this->site_id, 1, date("Y"));
		$this->view->outstanding = $actionplanClass->getTotalOutstanding($this->site_id, 1, date("Y"));
		$this->view->reschedule = $actionplanClass->getTotalReschedule($this->site_id, 1, date("Y"));
		$this->view->upcoming = $actionplanClass->getTotalUpcoming($this->site_id, 1, date("Y"));*/
		
		$params['pagesize'] = 3;
		$params['start'] = 0;
		$params['sort'] = "desc";
		$params['solved'] = 0;
		$latestIssues = $issueClass->getIssues($params);
		if(!empty($latestIssues))
		{
		    foreach($latestIssues as &$issue)
		    {
		        $issue_date_time = explode(" ",$issue['issue_date']);
				$issue['issue_date_time'] = date("j M Y", strtotime($issue_date_time[0]))." ".$issue_date_time[1];

				if($issue['issue_date'] > "2019-10-23 14:30:00")
				{
					$issuedate = explode("-",$issue_date_time[0]);
					$imageURL = "/images/issues/".$issuedate[0]."/";
				}
				else
					$imageURL = "/images/issues/";

				$pic = explode(".", $issue['picture']);
				$issue['large_pic'] = $imageURL.$pic[0]."_large.".$pic[1];
				$issue['thumb_pic'] = $imageURL.$pic[0]."_thumb.".$pic[1];
				if(!empty($issue['category_id'])) {
					$issue['category'] = $category->getCategoryById($issue['category_id']);				
				}
				if($issue['issue_id'] == $params['id']) $newIssue = $issue;
			
				if(empty($params['category']))
				{
					$issue['kejadian'] = "";
					if(!empty($issue['kejadian_id'])) {
						$incidentTable = $this->loadModel('incident');
						$selIncident = $incidentTable->getIncidentById($issue['kejadian_id'],$issue['category_id']);	
						$issue['kejadian'] = $selIncident['kejadian'];
					}
					$issue['modus'] = "";
					if(!empty($issue['modus_id'])) {
						$modusTable = $this->loadModel('modus');
						$selModus = $modusTable->getModusById($issue['modus_id'],$issue['category_id']);
						$issue['modus'] = $selModus['modus'];
					}
					$issue['manpower_name'] = "";
					if(!empty($issue['manpower_id'])) {
						Zend_Loader::LoadClass('manpowerClass', $this->modelDir);
						$manpowerClass = new manpowerClass();
						$selManPower = $manpowerClass->getManPowerById($issue['manpower_id']);
						$issue['manpower_name'] = $selManPower['name'];
					}
					$issue['floor'] = "";
					if(!empty($issue['floor_id'])) {
						$floorTable = $this->loadModel('floor');
						$selFloor = $floorTable->getFloorById($issue['floor_id'],$issue['category_id']);
						$issue['floor'] = $selFloor['floor'];
					}
				}
				
				if(strlen($issue['description']) > 150) {
					$issue['description'] = substr($issue['description'], 0, 150)."...";
				}
		    }
		}
		$this->view->latestIssues = $latestIssues;

    	$this->renderTemplate('index.tpl');  
	}
	
    /*function indexAction()
    {
		$this->view->ident = $this->ident;
		$category = $this->loadModel('category');
		$this->view->categories = $category->getCategories(6);
		$issuetype = $this->loadModel('issuetype');
		$this->view->type = $issuetype->getIssueType('1','6');
		

		$securityClass = $this->loadModel('security');
		$securityReport = $securityClass->getSecurityReportByDateAndChief(date("Y-m-d"));
		if(!empty($securityReport))
		{
			$readSecurityReportLog = $securityClass->getReadReportLogByReportIdUser($securityReport['chief_security_report_id'], $this->ident['user_id']);
			if(empty($readSecurityReportLog)) $this->view->showSecurityStarNotif = 1;
		}

		$safetyClass = $this->loadModel('safety');
		$this->view->safetyReport = $safetyReport = $safetyClass->getSafetyReportByDate(date("Y-m-d"));
		if(!empty($safetyReport))
		{
			$readSafetyReportLog = $safetyClass->getReadReportLogByReportIdUser($safetyReport['report_id'], $this->ident['user_id']);
			if(empty($readSafetyReportLog)) $this->view->showSafetyStarNotif = 1;
		}

		$parkingClass = $this->loadModel('parking');
		$this->view->parkingReport = $parkingReport = $parkingClass->getReportByDate(date("Y-m-d"));
		if(!empty($parkingReport))
		{
			$readParkingReportLog = $parkingClass->getReadReportLogByReportIdUser($parkingReport['parking_report_id'], $this->ident['user_id']);
			if(empty($readParkingReportLog)) $this->view->showParkingStarNotif = 1;
		}

		$housekeepingClass = $this->loadModel('housekeeping');
		$this->view->housekeepingReport = $housekeepingReport = $housekeepingClass->getReportByDate(date("Y-m-d"));
		if(!empty($housekeepingReport))
		{
			$readHousekeepingReportLog = $housekeepingClass->getReadReportLogByReportIdUser($housekeepingReport['housekeeping_report_id'], $this->ident['user_id']);
			if(empty($readHousekeepingReportLog)) $this->view->showHousekeepingStarNotif = 1;
		}

		$omClass = $this->loadModel('operational');
		$this->view->omReport = $omReport = $omClass->getReportByDate(date("Y-m-d"));
		if(!empty($omReport))
		{
			$readOMReportLog = $omClass->getReadReportLogByReportIdUser($omReport['operation_mall_report_id'], $this->ident['user_id']);
			if(empty($readOMReportLog)) $this->view->showOMStarNotif = 1;
		}

		$modClass = $this->loadModel('mod');
		$this->view->modReport = $modReport = $modClass->getReportByDate(date("Y-m-d"));
		if(!empty($modReport))
		{
			$readMODReportLog = $modClass->getReadReportLogByReportIdUser($modReport['mod_report_id'], $this->ident['user_id']);
			if(empty($readMODReportLog)) $this->view->showMODStarNotif = 1;
		}

		$bmClass = $this->loadModel('bm');
		$this->view->bmReport = $bmReport = $bmClass->getReportByDate(date("Y-m-d"));
		if(!empty($bmReport))
		{
			//$readBMReportLog = $bmClass->getReadReportLogByReportIdUser($bmReport['report_id'], $this->ident['user_id']);
			if(empty($readBMReportLog)) $this->view->showBMStarNotif = 1;
		}


		Zend_Loader::LoadClass('issueClass', $this->modelDir);
		$issueClass = new issueClass();

		$params['category'] = 1; // Security
		$this->view->totalSecIssues = $issueClass->getTotalPendingIssues('0', $this->site_id, $params);
		$params['category'] = 3; // Safety
		$this->view->totalSafIssues = $issueClass->getTotalPendingIssues('0', $this->site_id, $params);
		$params['category'] = 5; // Parking & Traffic
		$this->view->totalParkIssues = $issueClass->getTotalPendingIssues('0', $this->site_id, $params);
		$params['category'] = 2; // Housekeeping
		$this->view->totalHKIssues = $issueClass->getTotalPendingIssues('0', $this->site_id, $params);
		$params['category'] = 6; // Engineering
		$this->view->totalEngIssues = $issueClass->getTotalPendingIssues('0', $this->site_id, $params);
		$params['category'] = 10; // Building Service
		$this->view->totalBSIssues = $issueClass->getTotalPendingIssues('0', $this->site_id, $params);
		$params['category'] = 11; // Tenant Relation
		$this->view->totalTRIssues = $issueClass->getTotalPendingIssues('0', $this->site_id, $params);

		

    	$this->renderTemplate('index.tpl');  
	} */
	
	function openedissuesdashboardAction()
    {
		$this->view->ident = $this->ident;
		$category = $this->loadModel('category');
		$this->view->categories = $category->getCategories();
		$issuetype = $this->loadModel('issuetype');
		$this->view->type = $issuetype->getIssueType('1','6');
		
		if($this->showSiteSelection == 1)
		{
			$siteClass = $this->loadModel('site');
			$this->view->sitesSelections = $siteClass->getSites();
		}

		Zend_Loader::LoadClass('issueClass', $this->modelDir);
		$issueClass = new issueClass();

		$params['category'] = 1; // Security
		$this->view->totalSecIssues = $issueClass->getTotalPendingIssues('0', $this->site_id, $params);
		$params['category'] = 3; // Safety
		$this->view->totalSafIssues = $issueClass->getTotalPendingIssues('0', $this->site_id, $params);
		$params['category'] = 5; // Parking & Traffic
		$this->view->totalParkIssues = $issueClass->getTotalPendingIssues('0', $this->site_id, $params);
		$params['category'] = 2; // Housekeeping
		$this->view->totalHKIssues = $issueClass->getTotalPendingIssues('0', $this->site_id, $params);
		$params['category'] = 6; // Engineering
		$this->view->totalEngIssues = $issueClass->getTotalPendingIssues('0', $this->site_id, $params);
		$params['category'] = 10; // Building Service
		$this->view->totalBSIssues = $issueClass->getTotalPendingIssues('0', $this->site_id, $params);
		$params['category'] = 11; // Tenant Relation
		$this->view->totalTRIssues = $issueClass->getTotalPendingIssues('0', $this->site_id, $params);

    	$this->renderTemplate('opened_issue_dashboard.tpl');  
	}
	
	function reportdashboardAction()
    {
    	$this->renderTemplate('report_dashboard.tpl');  
	}
	
	function statisticdashboardAction()
    {
    	$this->renderTemplate('statistic_dashboard.tpl');  
	}
	
	function kpidashboardAction()
    {
		$kpiClass = $this->loadModel('kpi');
		$securityKPI = $kpiClass->getKPITotalByCatId(1);
		$this->view->securityKPI = $securityKPI['total_chief']."%";
		$safetyKPI = $kpiClass->getKPITotalByCatId(3);
		$this->view->safetyKPI = $safetyKPI['total_chief']."%";
		$parkingKPI = $kpiClass->getKPITotalByCatId(5);
		$this->view->parkingKPI = $parkingKPI['total_chief']."%";
		$monthlySecurityKPI = $kpiClass->getMonthlyKPITotalByCatId(1);
		$this->view->monthlySecurityKPI = $monthlySecurityKPI['total_chief']."%";
		$monthlySafetyKPI = $kpiClass->getMonthlyKPITotalByCatId(3);
		$this->view->monthlySafetyKPI = $monthlySafetyKPI['total_chief']."%";
		$monthlyParkingKPI = $kpiClass->getMonthlyKPITotalByCatId(5);
		$this->view->monthlyParkingKPI = $monthlyParkingKPI['total_chief']."%";

    	$this->renderTemplate('kpi_dashboard.tpl');  
    }
	
	function issueimageAction()
	{
		$params = $this->_getAllParams();
		Zend_Loader::LoadClass('issueClass', $this->modelDir);
    	$issueClass = new issueClass();
		$issue = $issueClass->getIssueById($params['id']);
		$pic = explode(".", $issue['picture']);
		$issue['large_pic'] = $pic[0]."_large.".$pic[1];
		$this->view->img_url = $this->config->general->url."images/issues/".$issue['large_pic'];
		echo $this->view->render('showimage.php');
		exit;
	}	

	function savesecuritypdfAction()
	{
		$lockedDate = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d")-2,   date("Y")));
		Zend_Loader::LoadClass('securityClass', $this->modelDir);
		$securityClass = new securityClass();
		$security = $securityClass->getSecurityAllSitesByDate($lockedDate);
		if(!empty($security))
		{
			foreach($security as $s)
			{
				echo "SPV Security<br/>".$s['site_id']."<br/>".$s['security_id']."<br/>";
				$this->exportspvsecuritytopdf($s['security_id'], $s['site_id']);
			}
		}

		$chiefSecurity = $securityClass->getChiefSecurityAllSitesByDate($lockedDate);
		if(!empty($chiefSecurity))
		{
			foreach($chiefSecurity as $cs)
			{
				echo "Chief Security<br/>".$cs['site_id']."<br/>".$cs['chief_security_report_id']."<br/>";
				$this->exportchiefsecuritytopdf($cs['chief_security_report_id'], $cs['site_id']);
			}
		}
		
		exit();
	}	

	

	function savesafetyparkingpdfAction()
	{
		$lockedDate = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d")-2,   date("Y")));

		Zend_Loader::LoadClass('safetyClass', $this->modelDir);
		$safetyClass = new safetyClass();
		$safety = $safetyClass->getSafetyAllSitesByDate($lockedDate);
		if(!empty($safety))
		{
			foreach($safety as $s)
			{
				echo "Safety<br/>".$s['site_id']."<br/>".$s['report_id']."<br/>";
				$this->exportsafetytopdf($s['report_id'], $s['site_id']);
			}
		}

		Zend_Loader::LoadClass('parkingClass', $this->modelDir);
		$parkingClass = new parkingClass();
		$parking = $parkingClass->getParkingAllSitesByDate($lockedDate);
		echo "<br/>";
		if(!empty($parking))
		{
			foreach($parking as $p)
			{
				echo "<br/>PARKING<br/>".$p['site_id']."<br/>".$p['parking_report_id']."<br/>";
				$this->exportparkingtopdf($p['parking_report_id'], $p['site_id']);
			}
		}
		exit();
	}
	
	function savehkpdfAction()
	{
		$lockedDate = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d")-2,   date("Y")));

		Zend_Loader::LoadClass('housekeepingClass', $this->modelDir);
		$housekeepingClass = new housekeepingClass();
		$housekeeping = $housekeepingClass->getHousekeepingAllSitesByDate($lockedDate);
		echo "<br/>";
		if(!empty($housekeeping))
		{
			foreach($housekeeping as $hk)
			{
				echo "<br/>HOUSEKEEPING<br/>".$hk['site_id']."<br/>".$hk['housekeeping_report_id']."<br/>";
				$this->exporthousekeepingtopdf($hk['housekeeping_report_id'], $hk['site_id']);
			}
		}
		exit();
	}

	function saveommodpdfAction()
	{
		$lockedDate = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d")-2,   date("Y")));

		Zend_Loader::LoadClass('operationalClass', $this->modelDir);
		$operationalClass = new operationalClass();
		$operational = $operationalClass->getOMAllSitesByDate($lockedDate);
		if(!empty($operational))
		{
			foreach($operational as $om)
			{
				echo "OM<br/>".$om['site_id']."<br/>".$om['operation_mall_report_id']."<br/>";
				$this->exportomtopdf($om['operation_mall_report_id'], $om['site_id']);
			}
		}
		Zend_Loader::LoadClass('modClass', $this->modelDir);
		$modClass = new modClass();
		$mod = $modClass->getMODAllSitesByDate($lockedDate);
		echo "<br/>";
		if(!empty($mod))
		{
			foreach($mod as $m)
			{
				echo "<br/>MOD<br/>".$m['site_id']."<br/>".$m['mod_report_id']."<br/>";
				$this->exportmodtopdf($m['mod_report_id'], $m['site_id']);
			}
		}
		exit();
	}	
	
	function clearmonthlylogsAction()
	{
		Zend_Loader::LoadClass('logsClass', $this->modelDir);
		$logsClass = new logsClass();
		$logsClass->clearMonthlyLogs();
	}
	
	public function loginnewAction() {
		echo $this->view->render('login2.php');
	}
}

?>
