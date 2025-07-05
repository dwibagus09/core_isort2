<?php
require_once('actionControllerBase.php');

class KpiController extends actionControllerBase
{
	public function viewAction() {
		$params = $this->_getAllParams();
		if(($this->showSecurityKpi && $params['c'] == 1) || ($this->showSafetyKpi && $params['c'] == 3) || ($this->showParkingKpi && $params['c'] == 5)) {

			Zend_Loader::LoadClass('actionplanClass', $this->modelDir);
			$actionplanClass = new actionplanClass();

			Zend_Loader::LoadClass('ratingClass', $this->modelDir);
			$ratingClass = new ratingClass();

			if(empty($params['y'])) $year = date("Y");
			else $year = $params['y'];

			$this->view->selectedYear = $year;

			$chief = $spv = $staff = $admin = 0;
			switch($params['tab']) {
				case 1: $chief = 1; 
						$curTab = "chief"; 
						break;
				case 2: $spv = 1; 
						$curTab = "spv"; 
						break;
				case 3: $staff = 1; 
						$curTab = "staff"; 
						break;
				case 4: $admin = 1; 
						$curTab = "admin"; 
						break;
				default: $chief = 1;
						 $params['tab'] = 1;
						 $curTab = "chief"; 
			}

			Zend_Loader::LoadClass('kpiClass', $this->modelDir);
			$kpiClass = new kpiClass();
			$this->view->kpi_c_section = $kpiClass->getCSection($params['c'], $params['tab'], $year);

			$kpi = $actionplanClass->getActionPlanForKPI($params['c'], $year, $chief, $spv, $staff, $admin);

			if($curTab != "chief")
			{
				$modules = $actionplanClass->getActionPlanModulesForKPI($params['c'], $year);
				$this->view->moduleName = $modules[count($modules)-1]['module_name'];
			}
			$target_id = 0;
			$i = 0;
			$j = 0;
			$activityBobot = array();
			$module_id = 0;
			$m = 0;
			foreach($kpi as &$k)
			{
				if($module_id != $k['action_plan_module_id'])	{ 
					$module_id = $k['action_plan_module_id'];
					$m++;
				}
				if($target_id != $k['action_plan_target_id'])	{
					if($target_id > 0)
					{
						if(empty($kpi[$j-1]['use_activity_bobot']))
						{
							$activityBobot[$kpi[$j-1]['action_plan_target_id']] = round(($kpi[$j-1][$curTab.'_bobot']/$i),2);
						}
					}
					$target_id = $k['action_plan_target_id'];
					$i = 0;
				}

				if(empty($totalActivity[$k['action_plan_module_id']])) $totalActivity[$k['action_plan_module_id']] = $actionplanClass->getTotalActivitiesByModule($k['action_plan_module_id'], $params['c'], $chief, $spv, $staff, $admin);
				$k['bobot'] = round($k['total_bobot']/$totalActivity[$k['action_plan_module_id']],2);
				$totalDone = $actionplanClass->getTotalDoneScheduleForNewKPI($k['action_plan_activity_id'], $year);
				if($totalDone > 0) $scheduleMark=round((($totalDone/intval($k['total_schedule']*3))*100), 2);
				else $scheduleMark=0;
				if($k['kpi_only'] == '1')
				{
					$customRating = $kpiClass->getCustomRating($params['c'], $params['tab'], $year, $k['action_plan_activity_id']);
					$k['rating'] = $customRating['rating'];
				}
				else
				{
					$rating = $ratingClass->getRating($scheduleMark);
					$k['rating'] = $rating['rating'];
				}				
				//$k['nilai'] = round((($k['bobot']*$rating['rating'])/100),2);
				$i++;
				$j++;
			}
			if($target_id > 0)
			{
				if(empty($kpi[$j-1]['use_activity_bobot']))
				{
					$activityBobot[$kpi[$j-1]['action_plan_target_id']] = round(($kpi[$j-1][$curTab.'_bobot']/$i),2);
				}
			}
			$this->view->kpi = $kpi;
			//echo "<pre>"; print_r($kpi); exit();
			$this->view->activityBobot = $activityBobot;
			//echo "<pre>"; print_r($activityBobot); exit();
			$this->view->height = $params['h'];
			$this->view->curTab = $curTab;

			Zend_Loader::LoadClass('categoryClass', $this->modelDir);
			$categoryClass = new categoryClass();
		
			$this->view->category = $categoryClass->getCategoryById($params['c']);

			Zend_Loader::LoadClass('achievementcategoryClass', $this->modelDir);
			$achievementcategoryClass = new achievementcategoryClass();
			$this->view->achievementCategory = $achievementcategoryClass->getAchievementCategories();

			Zend_Loader::LoadClass('achievementcategorymoduleClass', $this->modelDir);
			$achievementcategorymoduleClass = new achievementcategorymoduleClass();
			$achievementCatModules = $achievementcategorymoduleClass->getAchievementCategoriesModule($params['c']);
			$achievementCategoryModules = array();
			foreach($achievementCatModules as $key => $val)
			{		
				$achievementCategoryModules[$val['module_id']][$key] = $val; 
			}
			$this->view->achievementCategoryModule = $achievementCategoryModules;

			$this->view->hideScrollbar = 1;

			if($this->site_id < 4) $this->view->mdl_ctr = "A";
			else $this->view->mdl_ctr = "B";

			$first6MonthTotal = $actionplanClass->getTotalSchedulesSelectedMonth($year."-01-01", $year."-06-30", $params['c'], $chief, $spv, $staff, $admin);
			$first6MonthTotalApproved = $actionplanClass->getTotalApprovedSchedulesSelectedMonth($year."-01-01", $year."-06-30", $params['c'], $chief, $spv, $staff, $admin);
			if($first6MonthTotal > 0) $first6MonthScore=round(((intval($first6MonthTotalApproved)/(intval($first6MonthTotal)*3))*100), 2);
			else $first6MonthScore=0;
			//echo $first6MonthTotal."#".$first6MonthTotalApproved."#".$first6MonthScore; exit();
			$r1 = $ratingClass->getRating($first6MonthScore);
			$this->view->first6MonthScore = $r1['rating'];

			$second6MonthTotal = $actionplanClass->getTotalSchedulesSelectedMonth($year."-07-01", $year."-12-12", $params['c'], $chief, $spv, $staff, $admin);
			$second6MonthTotalApproved = $actionplanClass->getTotalApprovedSchedulesSelectedMonth($year."-07-01", $year."-12-12", $params['c'], $chief, $spv, $staff, $admin);
			$second6MonthScore=round(((intval($second6MonthTotalApproved)/(intval($second6MonthTotal)*3))*100), 2);
			$r2 = $ratingClass->getRating($second6MonthScore);
			$this->view->second6MonthScore = $r2['rating'];

			$kpiUser = $kpiClass->checkIfUserIsChief($this->ident['user_id'], $params['c']);
			if(!empty($kpiUser) || $this->ident['role_id'] == 1) $this->view->allowFillSpvStaffAdmin = 1;
			
			if(!empty($params['t']) && $params['t'] > 1)
			{
				$this->view->openTab = $params['t'];
			}

			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "View KPI";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);	

			if($params['open_tab'] == 1) echo $this->view->render('kpi_view_tab.tpl');
			else $this->renderTemplate('kpi_view.tpl'); 
		}
		else
		{
			$this->_response->setRedirect($this->baseUrl.'/default');
			$this->_response->sendResponse();
			exit();
		}
	}

	public function viewmonthlyAction() {
		$params = $this->_getAllParams();
		if(($this->showSecurityKpi && $params['c'] == 1) || ($this->showSafetyKpi && $params['c'] == 3) || ($this->showParkingKpi && $params['c'] == 5) || ($this->showHousekeepingKpi && $params['c'] == 2) || ($this->showEngineeringKpi && $params['c'] == 6)) {			
			Zend_Loader::LoadClass('actionplanClass', $this->modelDir);
			$actionplanClass = new actionplanClass();

			if(empty($params['y'])) $year = date("Y");
			else $year = $params['y'];

			$this->view->selectedYear = $year;

			$chief = $spv = $staff = $admin = 0;
			switch($params['tab']) {
				case 1: $chief = 1; 
						$curTab = "chief"; 
						break;
				case 2: $spv = 1; 
						$curTab = "spv"; 
						break;
				case 3: $staff = 1; 
						$curTab = "staff"; 
						break;
				case 4: $admin = 1; 
						$curTab = "admin"; 
						break;
				default: $chief = 1;
						 $curTab = "chief"; 
			}

			/*** LAST YEAR PERCENTAGE ***/

			$totalSchedulesLastYear = $actionplanClass->getTotalMonthlySchedules($year-1, $params['c'], $chief, $spv, $staff, $admin);
			$totalMonthlyLastYear = array();
			foreach($totalSchedulesLastYear as $tsly) {
				$totalMonthlyLastYear[$tsly['month']] = $tsly['total_schedule'] * 3;
			}

			$totalDoneSchedulesLastYear = $actionplanClass->getTotalDoneMonthlySchedules($year-1, $params['c'], $chief, $spv, $staff, $admin);
			$totalDoneMonthlyLastYear = array();
			foreach($totalDoneSchedulesLastYear as $tdly) {
				$totalDoneMonthlyLastYear[$tdly['month']] = $tdly['total_rating'];
			}

			$curMonthTotalLastYear = $actionplanClass->getCurrentMonthTotalSchedules($year-1, $params['c'], $chief, $spv, $staff, $admin);
			$curMonthTotalDoneLastYear = $actionplanClass->getCurrentMonthTotalDoneSchedules($year-1, $params['c'], $chief, $spv, $staff, $admin);
			$totalMonthlyLastYear[date('n')] = $curMonthTotalLastYear['total_schedule'] * 3;
			$totalDoneMonthlyLastYear[date('n')] = $curMonthTotalDoneLastYear['total_rating'];

			if(intval($totalMonthlyLastYear[1]) > 0) $lastYearMonthlyPercentage[1] = round(((intval($totalDoneMonthlyLastYear[1])/intval($totalMonthlyLastYear[1]))*100),2);
			else  $lastYearMonthlyPercentage[1] = 0;

			if(intval($totalMonthlyLastYear[2]) > 0) $lastYearMonthlyPercentage[2] = round(((intval($totalDoneMonthlyLastYear[2])/intval($totalMonthlyLastYear[2]))*100),2);
			else  $lastYearMonthlyPercentage[2] = 0;

			if(intval($totalMonthlyLastYear[3]) > 0) $lastYearMonthlyPercentage[3] = round(((intval($totalDoneMonthlyLastYear[3])/intval($totalMonthlyLastYear[3]))*100),2);
			else  $lastYearMonthlyPercentage[3] = 0;

			if(intval($totalMonthlyLastYear[4]) > 0) $lastYearMonthlyPercentage[4] = round(((intval($totalDoneMonthlyLastYear[4])/intval($totalMonthlyLastYear[4]))*100),2);
			else  $lastYearMonthlyPercentage[4] = 0;

			if(intval($totalMonthlyLastYear[5]) > 0) $lastYearMonthlyPercentage[5] = round(((intval($totalDoneMonthlyLastYear[5])/intval($totalMonthlyLastYear[5]))*100),2);
			else  $lastYearMonthlyPercentage[5] = 0;

			if(intval($totalMonthlyLastYear[6]) > 0) $lastYearMonthlyPercentage[6] = round(((intval($totalDoneMonthlyLastYear[6])/intval($totalMonthlyLastYear[6]))*100),2);
			else  $lastYearMonthlyPercentage[6] = 0;

			if(intval($totalMonthlyLastYear[7]) > 0) $lastYearMonthlyPercentage[7] = round(((intval($totalDoneMonthlyLastYear[7])/intval($totalMonthlyLastYear[7]))*100),2);
			else  $lastYearMonthlyPercentage[7] = 0;

			if(intval($totalMonthlyLastYear[8]) > 0) $lastYearMonthlyPercentage[8] = round(((intval($totalDoneMonthlyLastYear[8])/intval($totalMonthlyLastYear[8]))*100),2);
			else  $lastYearMonthlyPercentage[8] = 0;

			if(intval($totalMonthlyLastYear[9]) > 0) $lastYearMonthlyPercentage[9] = round(((intval($totalDoneMonthlyLastYear[9])/intval($totalMonthlyLastYear[9]))*100),2);
			else  $lastYearMonthlyPercentage[9] = 0;

			if(intval($totalMonthlyLastYear[10]) > 0) $lastYearMonthlyPercentage[10] = round(((intval($totalDoneMonthlyLastYear[10])/intval($totalMonthlyLastYear[10]))*100),2);
			else  $lastYearMonthlyPercentage[10] = 0;

			if(intval($totalMonthlyLastYear[11]) > 0) $lastYearMonthlyPercentage[11] = round(((intval($totalDoneMonthlyLastYear[11])/intval($totalMonthlyLastYear[11]))*100),2);
			else  $lastYearMonthlyPercentage[11] = 0;

			if(intval($totalMonthlyLastYear[12]) > 0) $lastYearMonthlyPercentage[12] = round(((intval($totalDoneMonthlyLastYear[12])/intval($totalMonthlyLastYear[12]))*100),2);
			else $lastYearMonthlyPercentage[12] = 0;			
			
			$this->view->lastYearMonthlyPercentage = $lastYearMonthlyPercentage;
			$this->view->lastYearGrandTotal = $lastYearGrandTotal =  round(array_sum($lastYearMonthlyPercentage)/12,2);


			/*** THIS YEAR PERCENTAGE ***/

			$totalSchedules = $actionplanClass->getTotalMonthlySchedules($year, $params['c'], $chief, $spv, $staff, $admin);
			$totalMonthly = array();
			foreach($totalSchedules as $ts) {
				$totalMonthly[$ts['month']] = $ts['total_schedule'] * 3;
			}
			//$this->view->totalMonthly = $totalMonthly;

			$totalDoneSchedules = $actionplanClass->getTotalDoneMonthlySchedules($year, $params['c'], $chief, $spv, $staff, $admin);
			$totalDoneMonthly = array();
			foreach($totalDoneSchedules as $td) {
				$totalDoneMonthly[$td['month']] = $td['total_rating'];
			}

			$curMonthTotal = $actionplanClass->getCurrentMonthTotalSchedules($year, $params['c'], $chief, $spv, $staff, $admin);
			$curMonthTotalDone = $actionplanClass->getCurrentMonthTotalDoneSchedules($year, $params['c'], $chief, $spv, $staff, $admin);
			$totalMonthly[date('n')] = $curMonthTotal['total_schedule'] * 3;
			$totalDoneMonthly[date('n')] = $curMonthTotalDone['total_rating'];

			//$this->view->totalDoneMonthly = $totalDoneMonthly;

			if(intval($totalMonthly[1]) > 0) $monthlyPercentage[1] = round(((intval($totalDoneMonthly[1])/intval($totalMonthly[1]))*100),2);
			else  $monthlyPercentage[1] = 0;

			if(intval($totalMonthly[2]) > 0) $monthlyPercentage[2] = round(((intval($totalDoneMonthly[2])/intval($totalMonthly[2]))*100),2);
			else  $monthlyPercentage[2] = 0;

			if(intval($totalMonthly[3]) > 0) $monthlyPercentage[3] = round(((intval($totalDoneMonthly[3])/intval($totalMonthly[3]))*100),2);
			else  $monthlyPercentage[3] = 0;

			if(intval($totalMonthly[4]) > 0) $monthlyPercentage[4] = round(((intval($totalDoneMonthly[4])/intval($totalMonthly[4]))*100),2);
			else  $monthlyPercentage[4] = 0;

			if(intval($totalMonthly[5]) > 0) $monthlyPercentage[5] = round(((intval($totalDoneMonthly[5])/intval($totalMonthly[5]))*100),2);
			else  $monthlyPercentage[5] = 0;

			if(intval($totalMonthly[6]) > 0) $monthlyPercentage[6] = round(((intval($totalDoneMonthly[6])/intval($totalMonthly[6]))*100),2);
			else  $monthlyPercentage[6] = 0;

			if(intval($totalMonthly[7]) > 0) $monthlyPercentage[7] = round(((intval($totalDoneMonthly[7])/intval($totalMonthly[7]))*100),2);
			else  $monthlyPercentage[7] = 0;

			if(intval($totalMonthly[8]) > 0) $monthlyPercentage[8] = round(((intval($totalDoneMonthly[8])/intval($totalMonthly[8]))*100),2);
			else  $monthlyPercentage[8] = 0;

			if(intval($totalMonthly[9]) > 0) $monthlyPercentage[9] = round(((intval($totalDoneMonthly[9])/intval($totalMonthly[9]))*100),2);
			else  $monthlyPercentage[9] = 0;

			if(intval($totalMonthly[10]) > 0) $monthlyPercentage[10] = round(((intval($totalDoneMonthly[10])/intval($totalMonthly[10]))*100),2);
			else  $monthlyPercentage[10] = 0;

			if(intval($totalMonthly[11]) > 0) $monthlyPercentage[11] = round(((intval($totalDoneMonthly[11])/intval($totalMonthly[11]))*100),2);
			else  $monthlyPercentage[11] = 0;

			if(intval($totalMonthly[12]) > 0) $monthlyPercentage[12] = round(((intval($totalDoneMonthly[12])/intval($totalMonthly[12]))*100),2);
			else $monthlyPercentage[12] = 0;

			if($year == date("Y")) $curMonth = date("n");
			else $curMonth = 12;
			/*if($year == date("Y") && date("n") < 13)
			{
				if(date("j") <= 15) $curMonth = date("n")-1;
				else $curMonth = date("n");
			} 
			else $curMonth = 12;*/
			
			
			$this->view->monthlyPercentage = $monthlyPercentage;
			$this->view->grandTotal = $grandTotal =  round(array_sum($monthlyPercentage)/$curMonth,2);


			if($chief == 1)
			{
				Zend_Loader::LoadClass('kpiClass', $this->modelDir);
				$kpiClass = new kpiClass();
				$existingMonthlyKPI = $kpiClass->getMonthlyKPITotalByCatId($params['c']);
				if(!empty($existingMonthlyKPI)) $data['id'] = $existingMonthlyKPI['id'];
				$data['category_id'] = $params['c'];
				$data['total_chief'] = $grandTotal;
				$kpiClass->saveMonthlyKPITotal($data);
			}

			/*foreach($totalSchedules as &$ts) {
				// utk dpt-in schedule id yg di reschedule
				$totalReschedule = $actionplanClass->getMonthlyReschedulesForKPI($year, $ts['month'], $params['c'], $chief, $spv, $staff, $admin);
				
				
				foreach($totalReschedule as $tr)
				{
					$rescheduleDate = $actionplanClass->getFinalRescheduleDate($tr['schedule_id']);
					$ori_date= explode(" ",$rescheduleDate['ori_date']);
					$final_date= explode(" ",$rescheduleDate['final_date']);
					if($rescheduleDate['total_reschedule'] > 1 && date("n", strtotime($ori_date[0])) == $ts['month'] && date("n", strtotime($final_date[0])) != $ts['month'])
					{
						$ts['total_schedule'] += 1;
					}
					elseif($rescheduleDate['total_reschedule'] == 1 && date("n", strtotime($ori_date[0])) == $ts['month'] && date("n", strtotime($final_date[0])) != $ts['month'])
					{
				
					}
				}
			}*/
		
			$this->view->height = $params['h'];
			$this->view->curTab = $curTab;

			Zend_Loader::LoadClass('categoryClass', $this->modelDir);
			$categoryClass = new categoryClass();
		
			$this->view->category = $categoryClass->getCategoryById($params['c']);

			//$this->view->hideScrollbar = 1;

			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "View Monthly KPI";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);	
			
			if($params['open_tab'] == 1) echo $this->view->render('kpi_view_monthly_tab.tpl');
			else $this->renderTemplate('kpi_view_monthly.tpl'); 
		}
		else
		{
			$this->_response->setRedirect($this->baseUrl.'/default');
			$this->_response->sendResponse();
			exit();
		}
	}

	public function exportkpitopdfAction() {	
		//ob_start();
		require('fpdf/mc_table.php');
		$params = $this->_getAllParams();
		
		if(($this->showSecurityKpi && $params['c'] == 1) || ($this->showSafetyKpi && $params['c'] == 3) || ($this->showParkingKpi && $params['c'] == 5)) {			
			Zend_Loader::LoadClass('userClass', $this->modelDir);
			$userClass = new userClass();
			$users = $userClass->getKPIUsers($params['c']);

			Zend_Loader::LoadClass('categoryClass', $this->modelDir);
			$categoryClass = new categoryClass();
		
			$category = $categoryClass->getCategoryById($params['c']);

			Zend_Loader::LoadClass('ratingClass', $this->modelDir);
			$ratingClass = new ratingClass();

			Zend_Loader::LoadClass('actionplanClass', $this->modelDir);
			$actionplanClass = new actionplanClass();

			Zend_Loader::LoadClass('achievementcategoryClass', $this->modelDir);
			$achievementcategoryClass = new achievementcategoryClass();
			$achievementCategory = $achievementcategoryClass->getAchievementCategories();

			Zend_Loader::LoadClass('achievementcategorymoduleClass', $this->modelDir);
			$achievementcategorymoduleClass = new achievementcategorymoduleClass();
			$achievementCatModules = $achievementcategorymoduleClass->getAchievementCategoriesModule($params['c']);
			$achievementCategoryModules = array();
			foreach($achievementCatModules as $key => $val)
			{		
				$achievementCategoryModules[$val['module_id']][$key] = $val; 
			}
			if(empty($params['y'])) $year = date("Y");
			else $year = $params['y'];

			$position[1] = "Chief";
			$position[2] = "Spv";
			$position[3] = "Staff";
			$position[4] = "Admin";

			Zend_Loader::LoadClass('kpiClass', $this->modelDir);
			$kpiClass = new kpiClass();
			$kpi_c_section[$position[1]] = $kpiClass->getCSection($params['c'], '1', $year);
			$kpi_c_section[$position[2]] = $kpiClass->getCSection($params['c'], '2', $year);
			$kpi_c_section[$position[3]] = $kpiClass->getCSection($params['c'], '3', $year);
			$kpi_c_section[$position[4]] = $kpiClass->getCSection($params['c'], '4', $year);

			$first6MonthTotal = $actionplanClass->getTotalSchedulesSelectedMonth($year."-01-01", $year."-06-30", $params['c'], 1,0,0,0);
			$first6MonthTotalApproved = $actionplanClass->getTotalApprovedSchedulesSelectedMonth($year."-01-01", $year."-06-30", $params['c'], 1,0,0,0);
			$first6MonthScore1 =round((($first6MonthTotalApproved/intval($first6MonthTotal))*100), 2);
			$r1 = $ratingClass->getRating($first6MonthScore1);
			$first6MonthScore[$position[1]] = $r1['rating'];

			$second6MonthTotal = $actionplanClass->getTotalSchedulesSelectedMonth($year."-07-01", $year."-12-12", $params['c'], 1,0,0,0);
			$second6MonthTotalApproved = $actionplanClass->getTotalApprovedSchedulesSelectedMonth($year."-07-01", $year."-12-12", $params['c'], 1,0,0,0);
			$second6MonthScore2=round((($second6MonthTotalApproved/intval($second6MonthTotal))*100), 2);
			$r2 = $ratingClass->getRating($second6MonthScore2);
			$second6MonthScore[$position[1]] = $r2['rating'];

			$first6MonthTotal = $actionplanClass->getTotalSchedulesSelectedMonth($year."-01-01", $year."-06-30", $params['c'], 0,1,0,0);
			$first6MonthTotalApproved = $actionplanClass->getTotalApprovedSchedulesSelectedMonth($year."-01-01", $year."-06-30", $params['c'], 0,1,0,0);
			$first6MonthScore1 =round((($first6MonthTotalApproved/intval($first6MonthTotal))*100), 2);
			$r1 = $ratingClass->getRating($first6MonthScore1);
			$first6MonthScore[$position[2]] = $r1['rating'];

			$second6MonthTotal = $actionplanClass->getTotalSchedulesSelectedMonth($year."-07-01", $year."-12-12", $params['c'], 0,1,0,0);
			$second6MonthTotalApproved = $actionplanClass->getTotalApprovedSchedulesSelectedMonth($year."-07-01", $year."-12-12", $params['c'], 0,1,0,0);
			$second6MonthScore2=round((($second6MonthTotalApproved/intval($second6MonthTotal))*100), 2);
			$r2 = $ratingClass->getRating($second6MonthScore2);
			$second6MonthScore[$position[2]] = $r2['rating'];

			$first6MonthTotal = $actionplanClass->getTotalSchedulesSelectedMonth($year."-01-01", $year."-06-30", $params['c'], 0,0,1,0);
			$first6MonthTotalApproved = $actionplanClass->getTotalApprovedSchedulesSelectedMonth($year."-01-01", $year."-06-30", $params['c'], 0,0,1,0);
			$first6MonthScore1 =round((($first6MonthTotalApproved/intval($first6MonthTotal))*100), 2);
			$r1 = $ratingClass->getRating($first6MonthScore1);
			$first6MonthScore[$position[3]] = $r1['rating'];

			$second6MonthTotal = $actionplanClass->getTotalSchedulesSelectedMonth($year."-07-01", $year."-12-12", $params['c'], 0,0,1,0);
			$second6MonthTotalApproved = $actionplanClass->getTotalApprovedSchedulesSelectedMonth($year."-07-01", $year."-12-12", $params['c'], 0,0,1,0);
			$second6MonthScore2=round((($second6MonthTotalApproved/intval($second6MonthTotal))*100), 2);
			$r2 = $ratingClass->getRating($second6MonthScore2);
			$second6MonthScore[$position[3]] = $r2['rating'];

			$first6MonthTotal = $actionplanClass->getTotalSchedulesSelectedMonth($year."-01-01", $year."-06-30", $params['c'], 0,0,0,1);
			$first6MonthTotalApproved = $actionplanClass->getTotalApprovedSchedulesSelectedMonth($year."-01-01", $year."-06-30", $params['c'], 0,0,0,1);
			$first6MonthScore1 =round((($first6MonthTotalApproved/intval($first6MonthTotal))*100), 2);
			$r1 = $ratingClass->getRating($first6MonthScore1);
			$first6MonthScore[$position[4]] = $r1['rating'];

			$second6MonthTotal = $actionplanClass->getTotalSchedulesSelectedMonth($year."-07-01", $year."-12-12", $params['c'], 0,0,0,1);
			$second6MonthTotalApproved = $actionplanClass->getTotalApprovedSchedulesSelectedMonth($year."-07-01", $year."-12-12", $params['c'], 0,0,0,1);
			$second6MonthScore2=round((($second6MonthTotalApproved/intval($second6MonthTotal))*100), 2);
			$r2 = $ratingClass->getRating($second6MonthScore2);
			$second6MonthScore[$position[4]] = $r2['rating'];


			// KPI Chief
			$kpi[1] = $actionplanClass->getActionPlanForKPI($params['c'], $year, 1, 0, 0, 0);
			$target_id = 0;
			$i = 0;
			$j = 0;
			$activityBobot = array();
			$module_id = 0;
			foreach($kpi[1] as &$k1)
			{
				if($module_id != $k1['action_plan_module_id'])	{ 
					$module_id = $k1['action_plan_module_id'];
				}
				if($target_id != $k1['action_plan_target_id'])	{
					if($target_id > 0)
					{
						if(empty($kpi[1][$j-1]['use_activity_bobot']))
						{
							$activityBobot[1][$kpi[1][$j-1]['action_plan_target_id']] = round(($kpi[1][$j-1]['chief_bobot']/$i),2);
						}
					}
					$target_id = $k1['action_plan_target_id'];
					$i = 0;
				}
				if(empty($totalActivity[$k1['action_plan_module_id']])) $totalActivity[$k1['action_plan_module_id']] = $actionplanClass->getTotalActivitiesByModule($k1['action_plan_module_id'], $params['c'], 1, 0, 0, 0);
				$k1['bobot'] = round($k1['total_bobot']/$totalActivity[$k1['action_plan_module_id']],2);
				//$totalDone = $actionplanClass->getTotalDoneSchedule($k1['action_plan_activity_id'], $this->site_id, $year);
				$totalDone = $actionplanClass->getTotalDoneScheduleForNewKPI($k1['action_plan_activity_id'], $year);
				if($totalDone > 0) $scheduleMark=round((($totalDone/intval($k1['total_schedule']))*100), 2);
				else $scheduleMark=0;
				if($k1['kpi_only'] == '1')
				{
					$customRating = $kpiClass->getCustomRating($params['c'], '1', $year, $k2['action_plan_activity_id']);
					$k1['rating'] = $customRating['rating'];
				}
				else
				{
					$rating = $ratingClass->getRating($scheduleMark);
					$k1['rating'] = $rating['rating'];
				}				
				$i++;
				$j++;
			}
			if($target_id > 0)
			{
				if(empty($kpi[1][$j-1]['use_activity_bobot']))
				{
					$activityBobot[1][$kpi[1][$j-1]['action_plan_target_id']] = round(($kpi[1][$j-1]['chief_bobot']/$i),2);
				}
			}
			
			// KPI Spv
			$kpi[2] = $actionplanClass->getActionPlanForKPI($params['c'], $year, 0, 1, 0, 0);
			$target_id = 0;
			$i = 0;
			$j = 0;
			//$activityBobot = array();
			$module_id = 0;
			foreach($kpi[2] as &$k2)
			{
				if($module_id != $k2['action_plan_module_id'])	{ 
					$module_id = $k2['action_plan_module_id'];
				}
				if($target_id != $k2['action_plan_target_id'])	{
					if($target_id > 0)
					{
						if(empty($kpi[2][$j-1]['use_activity_bobot']))
						{
							$activityBobot[2][$kpi[2][$j-1]['action_plan_target_id']] = round(($kpi[2][$j-1]['spv_bobot']/$i),2);
						}
					}
					$target_id = $k2['action_plan_target_id'];
					$i = 0;
				}
				if(empty($totalActivity[$k2['action_plan_module_id']])) $totalActivity[$k2['action_plan_module_id']] = $actionplanClass->getTotalActivitiesByModule($k2['action_plan_module_id'], $params['c'], 1, 0, 0, 0);
				$k2['bobot'] = round($k2['total_bobot']/$totalActivity[$k2['action_plan_module_id']],2);
				//$totalDone = $actionplanClass->getTotalDoneSchedule($k2['action_plan_activity_id'], $this->site_id, $year);
				$totalDone = $actionplanClass->getTotalDoneScheduleForNewKPI($k2['action_plan_activity_id'], $year);
				if($totalDone > 0) $scheduleMark=round((($totalDone/intval($k2['total_schedule']))*100), 2);
				else $scheduleMark=0;
				if($k2['kpi_only'] == '1')
				{
					$customRating = $kpiClass->getCustomRating($params['c'], '2', $year, $k2['action_plan_activity_id']);
					$k2['rating'] = $customRating['rating'];
				}
				else
				{
					$rating = $ratingClass->getRating($scheduleMark);
					$k2['rating'] = $rating['rating'];
				}	
				$i++;
				$j++;
			}
			if($target_id > 0)
			{
				if(empty($kpi[2][$j-1]['use_activity_bobot']))
				{
					$activityBobot[2][$kpi[2][$j-1]['action_plan_target_id']] = round(($kpi[2][$j-1]['spv_bobot']/$i),2);
				}
			}

			// KPI Staff
			$kpi[3] = $actionplanClass->getActionPlanForKPI($params['c'], $year, 0, 0, 1, 0);
			$target_id = 0;
			$i = 0;
			$j = 0;
			//$activityBobot = array();
			$module_id = 0;
			foreach($kpi[3] as &$k3)
			{
				if($module_id != $k3['action_plan_module_id'])	{ 
					$module_id = $k3['action_plan_module_id'];
				}
				if($target_id != $k3['action_plan_target_id'])	{
					if($target_id > 0)
					{
						if(empty($kpi[3][$j-1]['use_activity_bobot']))
						{
							$activityBobot[3][$kpi[3][$j-1]['action_plan_target_id']] = round(($kpi[3][$j-1]['staff_bobot']/$i),2);
						}
					}
					$target_id = $k3['action_plan_target_id'];
					$i = 0;
				}
				if(empty($totalActivity[$k3['action_plan_module_id']])) $totalActivity[$k3['action_plan_module_id']] = $actionplanClass->getTotalActivitiesByModule($k3['action_plan_module_id'], $params['c'], 1, 0, 0, 0);
				$k3['bobot'] = round($k3['total_bobot']/$totalActivity[$k3['action_plan_module_id']],2);
				//$totalDone = $actionplanClass->getTotalDoneSchedule($k3['action_plan_activity_id'], $this->site_id, $year);
				$totalDone = $actionplanClass->getTotalDoneScheduleForNewKPI($k3['action_plan_activity_id'], $year);
				if($totalDone > 0) $scheduleMark=round((($totalDone/intval($k3['total_schedule']))*100), 2);
				else $scheduleMark=0;
				if($k3['kpi_only'] == '1')
				{
					$customRating = $kpiClass->getCustomRating($params['c'], '3', $year, $k3['action_plan_activity_id']);
					$k3['rating'] = $customRating['rating'];
				}
				else
				{
					$rating = $ratingClass->getRating($scheduleMark);
					$k3['rating'] = $rating['rating'];
				}		
				$i++;
				$j++;
			}
			if($target_id > 0)
			{
				if(empty($kpi[3][$j-1]['use_activity_bobot']))
				{
					$activityBobot[3][$kpi[3][$j-1]['action_plan_target_id']] = round(($kpi[3][$j-1]['staff_bobot']/$i),2);
				}
			}


			// KPI Admin
			$kpi[4] = $actionplanClass->getActionPlanForKPI($params['c'], $year, 0, 0, 0, 1);
			$target_id = 0;
			$i = 0;
			$j = 0;
			//$activityBobot = array();
			$module_id = 0;
			foreach($kpi[4] as &$k4)
			{
				if($module_id != $k4['action_plan_module_id'])	{ 
					$module_id = $k4['action_plan_module_id'];
				}
				if($target_id != $k4['action_plan_target_id'])	{
					if($target_id > 0)
					{
						if(empty($kpi[4][$j-1]['use_activity_bobot']))
						{
							$activityBobot[4][$kpi[4][$j-1]['action_plan_target_id']] = round(($kpi[4][$j-1]['admin_bobot']/$i),2);
						}
					}
					$target_id = $k4['action_plan_target_id'];
					$i = 0;
				}
				if(empty($totalActivity[$k4['action_plan_module_id']])) $totalActivity[$k4['action_plan_module_id']] = $actionplanClass->getTotalActivitiesByModule($k4['action_plan_module_id'], $params['c'], 1, 0, 0, 0);
				$k4['bobot'] = round($k4['total_bobot']/$totalActivity[$k4['action_plan_module_id']],2);
				//$totalDone = $actionplanClass->getTotalDoneSchedule($k4['action_plan_activity_id'], $this->site_id, $year);
				$totalDone = $actionplanClass->getTotalDoneScheduleForNewKPI($k4['action_plan_activity_id'], $year);
				if($totalDone > 0) $scheduleMark=round((($totalDone/intval($k4['total_schedule']))*100), 2);
				else $scheduleMark=0;
				if($k4['kpi_only'] == '1')
				{
					$customRating = $kpiClass->getCustomRating($params['c'], '4', $year, $k4['action_plan_activity_id']);
					$k4['rating'] = $customRating['rating'];
				}
				else
				{
					$rating = $ratingClass->getRating($scheduleMark);
					$k4['rating'] = $rating['rating'];
				}	
				$i++;
				$j++;
			}
			if($target_id > 0)
			{
				if(empty($kpi[4][$j-1]['use_activity_bobot']))
				{
					$activityBobot[4][$kpi[4][$j-1]['action_plan_target_id']] = round(($kpi[4][$j-1]['admin_bobot']/$i),2);
				}
			}
			
			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "Export KPI to PDF";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);	
			
			$pdf=new PDF_MC_Table();
			foreach($users as $user) {
				$pdf->AddPage();
				$pdf->SetTitle($this->ident['initial']." - ".$category['category_name']." New KPI");
				$pdf->SetFont('Arial','B',12);
				$pdf->SetFillColor(9,41,102);
				$pdf->SetTextColor(255,255,255);
				$pdf->Cell(0,7, 'PENILAIAN KERJA PAKUWON GROUP / PERFORMANCE APPRAISAL PAKUWON GROUP',0,1,'C',true);
				$pdf->SetTextColor(0,0,0);
				$pdf->SetFont('Arial','B',10);
				$pdf->ln(2);
				$pdf->Cell(70,5,'Name : '.$user['name'],0,0,'L',false);
				$pdf->Cell(70,5,'Department : '.$category['category_name'],0,0,'C',false);
				$pdf->Cell(50,5,'Position : '.$position[$user['position_id']],0,0,'R',false);
				$pdf->ln(5);
				if(date("Y") != $year) $endOfPeriod = " to 31 December ".$year;
				else $endOfPeriod = " to ".date("j F Y");
				$pdf->SetFont('Arial','B',9);
				$pdf->Cell(70,5,'Performance Assessment Periode : 1 January '.$year.$endOfPeriod,0,0,'L',false);
				$pdf->ln(8);

				$pdf->SetFillColor(9,41,102);
				$pdf->SetTextColor(255,255,255);
				$pdf->SetFont('','B', 7);
				$pdf->Cell(85,10,'TARGET PENCAPAIAN KINERJA',1,0,'C',true);
				$pdf->Cell(50,5,'INDIKATOR PENGUKURAN','LTR',0,'C',true);
				$pdf->Cell(15,10,'BOBOT (%)',1,0,'C',true);
				$pdf->Cell(20,10,'RATING',1,0,'C',true);
				$pdf->Cell(20,5,'NILAI','LTR',0,'C',true);
				$pdf->Cell(0,5,'',0,1);
				$pdf->Cell(85,5,'',0,0);
				$pdf->Cell(50,5,'KEBERHASILAN KERJA','LRB',0,'C',true);
				$pdf->Cell(35,5,'',0,0);
				$pdf->Cell(20,5,'Bobot x Rating','LRB',0,'C',true);
				$pdf->Ln();

				if(strtolower($position[$user['position_id']] != "chief"))
				{
					$modules = $actionplanClass->getActionPlanModulesForKPI($params['c'], $year);
					$moduleName = $modules[count($modules)-1]['module_name'];
				}

				
				if($user['position_id'] == 1) $chief = 1;
				if($user['position_id'] == 2) $spv = 1;
				if($user['position_id'] == 3) $staff = 1;
				if($user['position_id'] == 4) $admin = 1;				
				
				$module_id = 0;
				$target_id = 0;
				$skipTotal = 0;
				$totalBobotPresentase = 0;
				$summaryTotalNilai = 0;
				$summaryTotalCapaian = 0;
				$s = 0;
				$k=0;
				$userKPI =  array();
				$userKPI = $kpi[$user['position_id']];
				if($this->site_id < 4) $mdl_ctr = "A";
				else $mdl_ctr = "B";
				foreach($userKPI as $ukpi) {
					if(strtolower($position[$user['position_id']]) != "chief" && $k == 0)
					{
						$pdf->SetTextColor(0,0,0);
						$pdf->SetWidths(array(190));	
						$pdf->SetAligns(array('L'));		
						$pdf->Row(array("B. ".strtoupper($moduleName)));
						$i = 1; 
						$totalBobotCapaian = 0;
						$totalNilaiCapaian = 0;
					}
					$chief = $spv = $staff = $admin = 0;
					if($module_id != $ukpi['action_plan_module_id'] && strtolower($position[$user['position_id']]) == "chief")	{ 
						if($module_id > 0)
						{
							$totalBobotPresentase += $userKPI[$k-1][strtolower($position[$user['position_id']])."_bobot"]; 
							$totalNilaiCapaian += $totalNilai;
							$totalNilaiPresentase = (($totalBobotPresentase*3)/100);
							$totalBobotCapaian = (($totalNilaiCapaian/$totalNilaiPresentase)*$totalBobotPresentase);
								
							
							if(!empty($achievementCategoryModules[$module_id]))
							{
								foreach($achievementCategoryModules[$module_id] as $acm) {
									if($acm['start_range'] <= $totalNilaiCapaian)
									{
										$kategoriCapaianKinerjaModul = $acm['description'];
										break;
									}
								}	
							}

							$pdf->SetTextColor(0,0,0);
							$pdf->SetFont('','B', 7);		
							$pdf->SetFillColor(255,208,63);
							$pdf->Cell(135,5,'Total',1,0,'R',true);
							$pdf->Cell(15,5,$userKPI[$k-1][strtolower($position[$user['position_id']])."_bobot"]."%",1,0,'C',true);
							$pdf->Cell(20,5," ",1,0,'C',true);
							$pdf->Cell(20,5,$totalNilai,1,0,'C',true);
							$pdf->Ln();
							$pdf->Cell(135,5,'Total Bobot presentase',1,0,'R',false);
							$pdf->Cell(15,5,$totalBobotPresentase."%",1,0,'C',false);
							$pdf->Cell(20,5," ",1,0,'C',false);
							$pdf->Cell(20,5,$totalNilaiPresentase,1,0,'C',false);
							$pdf->Ln();
							$totalBobot += $ukpi['bobot']; 
							$pdf->SetFillColor(61,203,255);
							$pdf->SetWidths(array(135, 15, 20, 20));	
							$pdf->SetAligns(array('R','C','C','C'));		
							$pdf->Row(array('Hasil Capaian', round($totalBobotCapaian,2)."%", $kategoriCapaianKinerjaModul, $totalNilaiCapaian));
							$pdf->Ln();
							$pdf->Ln();
							$summary[$s]['total'] = $totalNilaiCapaian;
							$summaryTotalNilai += $totalNilaiCapaian;
							$summaryTotalCapaian += $totalBobotCapaian;
							$totalBobot = 0;
							$totalNilai = 0;
							$skipTotal = 1;
							$totalBobotPresentase = 0;
							$totalNilaiCapaian = 0;
							$s++;
						}
			
						$totalBobot = 0;
						$totalNilai = 0;
						$module_id = $ukpi['action_plan_module_id'];
						$i = 1; 
						$summary[$s]['module_name'] = $ukpi['module_name'];
		
						if(strtolower($position[$user['position_id']]) == "chief") {
							$pdf->SetTextColor(0,0,0);
							$pdf->SetWidths(array(190));	
							$pdf->SetAligns(array('L'));		
							$pdf->Row(array($mdl_ctr.". ".strtoupper($ukpi['module_name'])));
							$mdl_ctr++;
						}
						$m++;
					}

					if($target_id != $ukpi['action_plan_target_id']) {
						if($target_id > 0 && empty($skipTotal))
						{ 
							$pdf->SetTextColor(0,0,0);
							$pdf->SetFont('','B', 7);		
							$pdf->SetFillColor(255,208,63);
							$pdf->Cell(135,5,'Total',1,0,'R',true);
							$pdf->Cell(15,5,$userKPI[$k-1][strtolower($position[$user['position_id']])."_bobot"]."%",1,0,'C',true);
							$pdf->Cell(20,5," ",1,0,'C',true);
							$pdf->Cell(20,5,$totalNilai,1,0,'C',true);
							$pdf->Ln();
							$totalBobotPresentase += $userKPI[$k-1][strtolower($position[$user['position_id']])."_bobot"];
							$totalNilaiCapaian += $totalNilai;
						}
						$totalBobot = 0;
						$totalNilai = 0;
						$skipTotal = 0;
						$target_id = $ukpi['action_plan_target_id'];
						$j = 1; 
						if($userKPI[$k-1]["target_sort_order"] == $userKPI[$k]["target_sort_order"]) $target_no = "";
						else $target_no = $i;

						$pdf->SetFont('','B', 7);
						$pdf->SetTextColor(0,0,0);
						$pdf->SetWidths(array(10,180));	
						$pdf->SetAligns(array('C','L'));		
						$pdf->Row(array($target_no, $ukpi['target_name']));
						if($userKPI[$k-1]["target_sort_order"] != $userKPI[$k]["target_sort_order"]) $i++;
					} 

					if(empty($ukpi['use_activity_bobot'])) $bobot = $activityBobot[$user['position_id']][$ukpi['action_plan_target_id']];
					else $bobot = $ukpi['activity_'.strtolower($position[$user['position_id']]).'_bobot'];
					

					$nilai = round((($bobot*$ukpi['rating'])/100),2);
					$pdf->SetTextColor(0,0,0);
					$pdf->SetFont('','', 7);
					$pdf->SetWidths(array(5,5,75, 50, 15, 20, 20));			
					$pdf->SetAligns(array('C','C','L','C','C','C','C'));
					$pdf->Row(array("", $j, $ukpi['activity_name'], $ukpi['document_as_approve'], $bobot."%",$ukpi['rating'],$nilai));
					$totalBobot += $ukpi['bobot']; 
					$totalNilai += $nilai; 
					$j++;						 
					$k++;
				}


				$totalBobotPresentase += $ukpi[strtolower($position[$user['position_id']]).'_bobot']; 
				$totalNilaiCapaian += $totalNilai;
				$totalNilaiPresentase = (($totalBobotPresentase*3)/100);
				$totalBobotCapaian = (($totalNilaiCapaian/$totalNilaiPresentase)*$totalBobotPresentase);
				$summary[$s]['total'] = $totalNilaiCapaian;
				$summaryTotalNilai += $totalNilaiCapaian;
				$summaryTotalCapaian += $totalBobotCapaian;
				if(strtolower($position[$user['position_id']]) == "chief")
				{
					if(!empty($achievementCategoryModules[$module_id]))
					{
						foreach($achievementCategoryModules[$module_id] as $acm) {
							if($acm['start_range'] <= $totalNilaiCapaian)
							{
								$kategoriCapaianKinerjaModul = $acm['description'];
								break;
							}
						}	
					}
				}
				else {
					if(2.4 == $totalNilaiCapaian)	$kategoriCapaianKinerjaModul = 'Target terpenuhi';
					elseif(1.7 <= $totalNilaiCapaian)	$kategoriCapaianKinerjaModul = 'Target hampir terpenuhi';
					elseif(0.1 <= $totalNilaiCapaian)	$kategoriCapaianKinerjaModul = 'Target kurang terpenuhi';
					else $kategoriCapaianKinerjaModul = 'Target tidak terpenuhi';
				}

				$pdf->SetTextColor(0,0,0);
				$pdf->SetFont('','B', 7);		
				$pdf->SetFillColor(255,208,63);
				$pdf->Cell(135,5,'Total',1,0,'R',true);
				$pdf->Cell(15,5,$ukpi[strtolower($position[$user['position_id']]).'_bobot']."%",1,0,'C',true);
				$pdf->Cell(20,5," ",1,0,'C',true);
				$pdf->Cell(20,5,$totalNilai,1,0,'C',true);
				$pdf->Ln();
				$pdf->Cell(135,5,'Total Bobot presentase',1,0,'R',false);
				$pdf->Cell(15,5,$totalBobotPresentase."%",1,0,'C',false);
				$pdf->Cell(20,5," ",1,0,'C',false);
				$pdf->Cell(20,5,$totalNilaiPresentase."%",1,0,'C',false);
				$pdf->Ln();
				$pdf->SetWidths(array(135, 15, 20, 20));	
				$pdf->SetAligns(array('R','C','C','C'));		
				$pdf->SetFillColor(61,203,255);
				$pdf->Row(array('Hasil Capaian', round($totalBobotCapaian,2)."%", $kategoriCapaianKinerjaModul, $totalNilaiCapaian));	
				$pdf->Ln();
				$pdf->Ln(); 

				$nilaic13 =  round(($kpi_c_section[$position[$user['position_id']]]['c13']*9/100),2);
				$nilaic21 =  round(($kpi_c_section[$position[$user['position_id']]]['c21']*1/100),2);
				$nilaic22 =  round(($kpi_c_section[$position[$user['position_id']]]['c22']*1/100),2);								
				$nilaic23 =  round(($kpi_c_section[$position[$user['position_id']]]['c23']*1/100),2);
				$nilaiFirst6Month = round(($first6MonthScore[$position[$user['position_id']]]*4/100),2);
				$nilaiSecond6Month = round(($second6MonthScore[$position[$user['position_id']]]*4/100),2);
				$totalNilaiC1 = $nilaiFirst6Month + $nilaiSecond6Month + $nilaic13;
				$totalNilaiC2 =  $nilaic21 +  $nilaic22 + $nilaic23;
				$totalNilaiCapaianC = $totalNilaiC1 + $totalNilaiC2;
				$totalBobotCapaianC = ($totalNilaiCapaianC/0.6)*20;
				if(0.6 == $totalNilaiCapaianC)	$kategoriCapaianKinerjaModulC = 'Target terpenuhi';
				elseif(0.43 <= $totalNilaiCapaianC)	$kategoriCapaianKinerjaModulC = 'Target hampir terpenuhi';
				elseif(0.1 <= $totalNilaiCapaianC)	$kategoriCapaianKinerjaModulC = 'Target kurang terpenuhi';
				else $kategoriCapaianKinerjaModulC = 'Target tidak terpenuhi';

				
				$pdf->SetTextColor(0,0,0);
				$pdf->SetWidths(array(190));	
				$pdf->SetAligns(array('L'));
				if(strtolower($position[$user['position_id']]) == "chief")
					$pdf->Row(array($mdl_ctr.". KEPATUHAN PADA STANDAR KERJA DAN  KEPEMIMPINAN"));
				else
					$pdf->Row(array("C. KEPATUHAN PADA STANDAR KERJA DAN KEPRIBADIAN"));
				$pdf->SetFont('','B', 7);
				$pdf->SetTextColor(0,0,0);
				$pdf->SetWidths(array(10,180));	
				$pdf->SetAligns(array('C','L'));		
				$pdf->Row(array('1', 'Kepatuhan dan konsistensi terhadap Rencana kerja dan perusahaan serta kualitas Kerja'));
				$pdf->SetTextColor(0,0,0);
				$pdf->SetFont('','', 7);
				$pdf->SetWidths(array(5,5,75, 50, 15, 20, 20));			
				$pdf->SetAligns(array('C','C','L','C','C','C','C'));
				$pdf->Row(array("", '1', 'Kualitas Implementasi Action Plan Enam Bulan pertama', '' , "4%",$first6MonthScore[$position[$user['position_id']]],$nilaiFirst6Month));
				$pdf->SetTextColor(0,0,0);
				$pdf->SetFont('','', 7);
				$pdf->SetWidths(array(5,5,75, 50, 15, 20, 20));			
				$pdf->SetAligns(array('C','C','L','C','C','C','C'));
				$pdf->Row(array("", '2', 'Kualitas Implementasi Action Plan Enam Bulan kedua', '' , "4%",$second6MonthScore[$position[$user['position_id']]],$nilaiSecond6Month));
				$pdf->SetTextColor(0,0,0);
				$pdf->SetFont('','', 7);
				$pdf->SetWidths(array(5,5,75, 50, 15, 20, 20));			
				$pdf->SetAligns(array('C','C','L','C','C','C','C'));
				$pdf->Row(array("", '3', 'Hasil Audit Security tahunan', '' , "9%",$kpi_c_section[$position[$user['position_id']]]['c13'],$nilaic13));
				$pdf->SetTextColor(0,0,0);
				$pdf->SetFont('','B', 7);		
				$pdf->SetFillColor(255,208,63);
				$pdf->Cell(135,5,'Total',1,0,'R',true);
				$pdf->Cell(15,5,"17%",1,0,'C',true);
				$pdf->Cell(20,5," ",1,0,'C',true);
				$pdf->Cell(20,5,$totalNilaiC1,1,0,'C',true);
				$pdf->Ln(); 
				$pdf->SetFont('','B', 7);
				$pdf->SetTextColor(0,0,0);
				$pdf->SetWidths(array(10,180));	
				$pdf->SetAligns(array('C','L'));		
				if(strtolower($position[$user['position_id']]) == "chief")
					$pdf->Row(array('2', 'Leadership'));
				else
					$pdf->Row(array('2', 'Kepribadian'));
				$pdf->SetTextColor(0,0,0);
				$pdf->SetFont('','', 7);
				$pdf->SetWidths(array(5,5,75, 50, 15, 20, 20));			
				$pdf->SetAligns(array('C','C','L','C','C','C','C'));
				if(strtolower($position[$user['position_id']]) == "chief")
					$pdf->Row(array("", '1', 'Kepemimpinan (Leadership)', '' , "1%",$kpi_c_section[$position[$user['position_id']]]['c21'],$nilaic21));
				else
					$pdf->Row(array("", '1', 'Integritas (Integrity)', '' , "1%",$kpi_c_section[$position[$user['position_id']]]['c21'],$nilaic21));
				$pdf->SetTextColor(0,0,0);
				$pdf->SetFont('','', 7);
				$pdf->SetWidths(array(5,5,75, 50, 15, 20, 20));			
				$pdf->SetAligns(array('C','C','L','C','C','C','C'));
				if(strtolower($position[$user['position_id']]) == "chief")
					$pdf->Row(array("", '2', 'Pengambilan Keputusan (Decision Making)', '' , "1%",$kpi_c_section[$position[$user['position_id']]]['c22'],$nilaic22));
				else
					$pdf->Row(array("", '2', 'Disipline Kehadiran (Attendance)', '' , "1%",$kpi_c_section[$position[$user['position_id']]]['c22'],$nilaic22));
				$pdf->SetTextColor(0,0,0);
				$pdf->SetFont('','', 7);
				$pdf->SetWidths(array(5,5,75, 50, 15, 20, 20));			
				$pdf->SetAligns(array('C','C','L','C','C','C','C'));
				if(strtolower($position[$user['position_id']]) == "chief")
					$pdf->Row(array("", '3', 'Pengembangan bawahan (Developing Others) ', '' , "1%",$kpi_c_section[$position[$user['position_id']]]['c23'],$nilaic23));
				else
					$pdf->Row(array("", '3', 'Kerjasama (Team Work)', '' , "1%",$kpi_c_section[$position[$user['position_id']]]['c23'],$nilaic23));
				$pdf->SetTextColor(0,0,0);
				$pdf->SetFont('','B', 7);		
				$pdf->SetFillColor(255,208,63);
				$pdf->Cell(135,5,'Total',1,0,'R',true);
				$pdf->Cell(15,5,"17%",1,0,'C',true);
				$pdf->Cell(20,5," ",1,0,'C',true);
				$pdf->Cell(20,5,$totalNilaiC2,1,0,'C',true);
				$pdf->Ln(); 
				$pdf->Cell(135,5,'Total Bobot presentase',1,0,'R',false);
				$pdf->Cell(15,5,"20%",1,0,'C',false);
				$pdf->Cell(20,5," ",1,0,'C',false);
				$pdf->Cell(20,5,"0.6",1,0,'C',false);
				$pdf->Ln(); 
				$pdf->SetWidths(array(135, 15, 20, 20));	
				$pdf->SetAligns(array('R','C','C','C'));		
				$pdf->SetFillColor(61,203,255);
				$pdf->Row(array('Hasil Capaian', round($totalBobotCapaianC,2)."%", $kategoriCapaianKinerjaModulC, $totalNilaiCapaianC));	
				$pdf->Ln();
				$pdf->Ln(); 

				$pdf->SetFillColor(9,41,102);
				$pdf->SetTextColor(255,255,255);
				$pdf->SetFont('','B', 7);
				$pdf->Cell(85,5,'KESIMPULAN AKHIR','LTR',0,'C',true);
				$pdf->Cell(20,5,'NILAI','LTR',0,'C',true);
				$pdf->Cell(20,5,'TOTAL NILAI','LTR',0,'C',true);
				$pdf->Cell(25,5,'TOTAL % CAPAIAN','LTR',0,'C',true);
				$pdf->Cell(40,5,'KATEGORI CAPAIAN KINERJA','LTR',0,'C',true);
				$pdf->Ln();
				$pdf->SetTextColor(0,0,0);	
				if(!empty($summary)) { 
					$z = 0;
					if(strtolower($position[$user['position_id']]) == "chief")
					{
						foreach($summary as $su) {	
							if($z == 0) { 
								$summaryTotalNilai = round($summaryTotalNilai+$totalNilaiCapaianC,2);
								foreach($achievementCategory as $ac) {
									if($ac['start_range'] <= $summaryTotalNilai)
									{
										$kategoriCapaianKinerja = $ac['description'];
										break;
									}
								}
								$curX = $pdf->getX();
								$curY = $pdf->getY();
								$pdf->SetWidths(array(85, 20));	
								$pdf->SetAligns(array('L','C'));
								$pdf->Row(array($su['module_name'], $su['total']));	
								$pdf->setXY($curX, $curY);					
								$pdf->Cell(85,5,'',0,0);
								$pdf->Cell(20,5,'',0,0);
								$pdf->Cell(20,25,$summaryTotalNilai,1,0,'C',false);
								$pdf->Cell(25,25,round(($summaryTotalCapaian+$totalBobotCapaianC),2)."%",1,0,'C',false);
								$pdf->Cell(40,25,$kategoriCapaianKinerja,1,0,'C',false);					
							} 
							else{
								$pdf->setXY($curX, $curY+10);
								$pdf->SetWidths(array(85, 20));			
								$pdf->SetAligns(array('L','C'));	
								$pdf->Row(array($su['module_name'], $su['total']));
							}
							$z++; 
						}
						$pdf->setXY($curX, $curY+20);
						$pdf->SetWidths(array(85, 20));			
						$pdf->SetAligns(array('L','C'));	
						$pdf->Row(array('Kompetensi Inti Dan Kepemimpinan', $totalNilaiCapaianC)); 
					}	
					else
					{
						$z = 0;
						foreach($summary as $su) {	
							if($z == 0) { 
								$summaryTotalNilai = round($summaryTotalNilai+$totalNilaiCapaianC,2);
								foreach($achievementCategory as $ac) {
									if($ac['start_range'] <= $summaryTotalNilai)
									{
										$kategoriCapaianKinerja = $ac['description'];
										break;
									}
								}	
								$curX = $pdf->getX();
								$curY = $pdf->getY();
								$pdf->SetWidths(array(85, 20));	
								$pdf->SetAligns(array('L','C'));
								$pdf->Row(array(strtoupper($moduleName), $su['total']));	
								$pdf->setXY($curX, $curY);					
								$pdf->Cell(85,5,'',0,0);
								$pdf->Cell(20,5,'',0,0);
								$pdf->Cell(20,15,$summaryTotalNilai,1,0,'C',false);
								$pdf->Cell(25,15,round(($summaryTotalCapaian+$totalBobotCapaianC),2)."%",1,0,'C',false);
								$pdf->Cell(40,15,$kategoriCapaianKinerja,1,0,'C',false);
								$pdf->setXY($curX, $curY+10);
								$pdf->SetWidths(array(85, 20));			
								$pdf->SetAligns(array('L','C'));	
								$pdf->Row(array('Kepatuhan Pada Standar Kerja Dan Kepribadian', $totalNilaiCapaianC));
							}
							$z++; 
						}
					}
				}
			}

			$pdf->Output('I', $this->ident['initial']."_".$category['category_name']."_New_KPI.pdf");
		}
		//ob_end_flush(); 
	}

	public function savetotalkpiAction() {	
		$params = $this->_getAllParams();

		Zend_Loader::LoadClass('kpiClass', $this->modelDir);
		$kpiClass = new kpiClass();
		$existingKPI = $kpiClass->getKPITotalByCatId($params['c']);
		if(!empty($existingKPI)) $data['id'] = $existingKPI['id'];
		$data['category_id'] = $params['c'];
		$data['total_chief'] = $params['score'];

		/*$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "Save Total KPI";
		$logData['data'] = json_encode($params);
		$logsTable->insertLogs($logData);*/	

		$kpiClass->saveKPITotal($data);
	}

	public function savekpiAction() {	
		$params = $this->_getAllParams();

		Zend_Loader::LoadClass('kpiClass', $this->modelDir);
		$kpiClass = new kpiClass();

		$kpi_c_section = $kpiClass->getCSection($params['c'], $params['datatab'], $params['year']);
		if(!empty($kpi_c_section)) $params['id'] =  $kpi_c_section['id'];

		switch($params['datatab']) {
			case 1: $params['c13'] = $params['chiefc13'];	
					$params['c21'] = $params['chiefc21'];	
					$params['c22'] = $params['chiefc22'];	
					$params['c23'] = $params['chiefc23'];
					$kw = "chief";
					break;
			case 2: $params['c13'] = $params['spvc13'];	
					$params['c21'] = $params['spvc21'];	
					$params['c22'] = $params['spvc22'];	
					$params['c23'] = $params['spvc23'];
					$kw = "spv";
					break;
			case 3: $params['c13'] = $params['staffc13'];	
					$params['c21'] = $params['staffc21'];	
					$params['c22'] = $params['staffc22'];	
					$params['c23'] = $params['staffc23']; 
					$kw = "staff";
					break;
			case 4: $params['c13'] = $params['adminc13'];	
					$params['c21'] = $params['adminc21'];	
					$params['c22'] = $params['adminc22'];	
					$params['c23'] = $params['adminc23'];
					$kw = "admin";
					break;
			default: $params['c13'] = $params['chiefc13'];	
					$params['c21'] = $params['chiefc21'];	
					$params['c22'] = $params['chiefc22'];	
					$params['c23'] = $params['chiefc23'];
					$kw = "chief";
		}

		if((!$this->allowFillChiefRating == 1 && $params['datatab'] == 1) || ($this->allowFillChiefRating == 1 && $params['datatab'] > 1 && $this->ident['role_id'] != 1) ) ;
		else
		{ 
			$kpiClass->saveCSection($params);
		
			foreach($params as $key => $p) 
			{
				$params['custom_id'] = 0;
				if (strpos($key, $kw."_") !== false)
				{
					$field = explode("_", $key);
					$params['action_plan_activity_id'] = $field[1];
					$params['rating'] = $p;
					$kpi_b_section = $kpiClass->getCustomRating($params['c'], $params['datatab'], $params['year'], $params['action_plan_activity_id']);
					if(!empty($kpi_b_section)) $params['custom_id'] =  $kpi_b_section['id'];
					$kpiClass->saveCustomRating($params);
				}
			}
		}

		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "Save KPI";
		$logData['data'] = json_encode($params);
		$logsTable->insertLogs($logData);	

		$this->_response->setRedirect($this->baseUrl.'/default/kpi/view/c/'.$params['c'].'/t/'.$params['datatab']);
		$this->_response->sendResponse();
		exit();
	}
	
}

?>