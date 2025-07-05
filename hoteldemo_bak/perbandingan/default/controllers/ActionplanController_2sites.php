<?php
require_once('actionControllerBase.php');

class ActionplanController extends actionControllerBase
{
	public function viewAction() {
		$params = $this->_getAllParams();
		
		Zend_Loader::LoadClass('actionplanClass', $this->modelDir);
		$actionplanClass = new actionplanClass();
		
		$this->view->modules = $schedule = $modules = $actionplanClass->getActionPlanModules($params['c']);
		
		Zend_Loader::LoadClass('siteClass', $this->modelDir);
		$siteClass = new siteClass();
		
		if($this->site_id%2==0)
		{
			$site1 = $siteClass->getSiteById($this->site_id-1);
			$site2 = $siteClass->getSiteById($this->site_id);
		}
		else
		{
			$site1 = $siteClass->getSiteById($this->site_id);
			$site2 = $siteClass->getSiteById($this->site_id+1);
		}
		
		$this->view->site1 = $site1;
		$this->view->site2 = $site2;
		
		if(!empty($schedule))
		{
			foreach($schedule as &$sch)
			{
				$sch['target'] = $actionplanClass->getActionPlanTargetByModuleId($sch['action_plan_module_id']);
				if(!empty($sch['target']))
				{
					foreach($sch['target'] as &$target)
					{
						$target['activity'] = $actionplanClass->getActionPlanActivityByTargetId($target['action_plan_target_id']);
						 
						if(!empty($target['activity']))
						{
							$scheduleDates = array();
							$a = 0;
							foreach($target['activity'] as &$activity) {
								$totalDone1 = $actionplanClass->getTotalDoneSchedule($activity['action_plan_activity_id'], $site1['site_id']);
								$totalDone2 = $actionplanClass->getTotalDoneSchedule($activity['action_plan_activity_id'], $site2['site_id']);
								$total1 = $actionplanClass->getTotalSchedule($activity['action_plan_activity_id'], $site1['site_id']);
								$total2 = $actionplanClass->getTotalSchedule($activity['action_plan_activity_id'], $site2['site_id']);
								if($total1>0) $activity['percentage1'] = ($totalDone1/$total1)*100;
								else $activity['percentage1'] = 0;
								if($total2>0) $activity['percentage2'] = ($totalDone2/$total2)*100;
								else $activity['percentage2'] = 0;
								
								for($m=0; $m<12; $m++) {
									$month[$m]['month_name'] = date("F", mktime(0, 0, 0, $m+1, 1, date('Y')));
									$month[$m]['no_of_weeks'] = $this->numberOfWeeks($m+1,date('Y'));
									for($w=1; $w <= $month[$m]['no_of_weeks']; $w++) {	
										$site1data = $actionplanClass->getScheduleDateByMonthWeek($site1['site_id'], $m+1, $w, $activity['action_plan_activity_id']);
										$site1_date = $site1data['schedule_date'];
										$site1_date1 = explode(" ", $site1_date);
										$site1_date2 = explode("-", $site1_date1[0]);
										$activity['month'][$m][$w]['site1'] = $site1_date2[2];
										$activity['month'][$m][$w]['site1_schedule_id'] = $site1data['schedule_id'];
										$activity['month'][$m][$w]['site1_site_id'] = $site1data['site_id'];
										
										$site2data = $actionplanClass->getScheduleDateByMonthWeek($site2['site_id'], $m+1, $w, $activity['action_plan_activity_id']);
										$site2_date = $site2data['schedule_date'];
										$site2_date1 = explode(" ", $site2_date);
										$site2_date2 = explode("-", $site2_date1[0]);
										$activity['month'][$m][$w]['site2'] = $site2_date2[2];
										$activity['month'][$m][$w]['site2_schedule_id'] = $site2data['schedule_id'];
										$activity['month'][$m][$w]['site2_site_id'] = $site2data['site_id'];
									}
								}
								$a++;
							}
						}	
					}
				}
			}
		}
		$this->view->schedule = $schedule;
		
		
		$this->view->calendar = $month;
		$this->view->category_id = $params['c'];
		
		$this->renderTemplate('action_plan_view.tpl'); 
	}
	
	function numberOfWeeks($month, $year){
		$firstday = date("w", mktime(0, 0, 0, $month, 1, $year)); 
		$lastday = date("t", mktime(0, 0, 0, $month, 1, $year));
		$count_weeks = 1 + ceil(($lastday-8+$firstday)/7);
		return $count_weeks;
	} 
	
	public function savescheduleAction() {
		$params = $this->_getAllParams();
		$params['user_id'] = $this->ident['user_id'];
		
		Zend_Loader::LoadClass('actionplanClass', $this->modelDir);
		$actionplanClass = new actionplanClass();
	
		if(!empty($params['schedule_date'])) {
			foreach($params['schedule_date'] as $schedule_date)
			{
				$params['schedule'] = $schedule_date;
				$params['week'] = $this->weekOfMonth($schedule_date);
				$dt = explode("-", $schedule_date);
				$params['month'] = $dt[1];
				$actionplanClass->saveActionPlanSchedule($params);
			}			
		}
	}
	
	function weekOfMonth($date) {		
		$currentWeek = ceil((date("d",strtotime($date)) - date("w",strtotime($date)) - 1) / 7) + 1;
		return $currentWeek;
	}
	
	public function updatestatusscheduleAction()
	{
		$params = $this->_getAllParams();
		$params['user_id'] = $this->ident['user_id'];
		
		Zend_Loader::LoadClass('actionplanClass', $this->modelDir);
		$actionplanClass = new actionplanClass();
		if($params['update_status_schedule'] == "done")
		{
			Zend_Loader::LoadClass('categoryClass', $this->modelDir);
			$categoryClass = new categoryClass();
		
			$category = $categoryClass->getCategoryById($params['category_id']);
			$attachment_id = $actionplanClass->addScheduleAttachment($params);
			if(!empty($_FILES["attachment"]))
			{
				$ext = explode(".",$_FILES["attachment"]['name']);
				$filename = $attachment_id.".".$ext[count($ext)-1];
				$datafolder = $this->config->paths->html."/actionplan/".strtolower($category['category_name'])."/";
				if(move_uploaded_file($_FILES["attachment"]["tmp_name"], $datafolder.$filename))
				{
					$actionplanClass->updateScheduleAttachment($attachment_id,'filename', $filename);
					
					if(in_array(strtolower($ext[count($ext)-1]), array("jpg", "jpeg", "png","bmp")))
					{
						$magickPath = "/usr/bin/convert";
						/*** resize image if size greater than 500 Kb ***/
						if(filesize($datafolder.$filename) > 500000) exec($magickPath . ' ' . $datafolder.$filename . ' -resize 800x800\> ' . $datafolder.$filename);
					}
				}
			}
			$actionplanClass->updateschedulestatus($params['action_plan_schedule_id'], '1');
		}
		elseif($params['update_status_schedule'] == "reschedule")
		{
			$actionplanClass->reschedule($params);
			$actionplanClass->updateschedulestatus($params['action_plan_schedule_id'], '2');
		}
		
		$this->_response->setRedirect($this->baseUrl.'/default/actionplan/view/c/'.$params['category_id']);
		$this->_response->sendResponse();
		exit();
	}
	
	public function getschedulebyidAction() {
		$params = $this->_getAllParams();
		Zend_Loader::LoadClass('actionplanClass', $this->modelDir);
		$actionplanClass = new actionplanClass();
		
		$schedule = $actionplanClass->getActionPlanScheduleById($params['id'], $params['site_id']);
		$date = explode(" ",$schedule['schedule_date']);
		$schedule['date']=date("j M Y", strtotime($date[0]));
		
		$date2 = explode(" ",$schedule['reschedule_date']);
		$schedule['reschedule']=date("j M Y", strtotime($date2[0]));
		
		if(!empty($params['id'])) 
		{
			echo json_encode($schedule);
		}
	}
	
	
	/*** ACTION PLAN TARGET ***/
	
	public function targetAction() {
		$params = $this->_getAllParams();
		
		$params['user_id'] = $this->ident['user_id'];
		Zend_Loader::LoadClass('actionplanClass', $this->modelDir);
		$actionplanClass = new actionplanClass();
		
		$this->view->target = $actionplanClass->getActionPlanTarget($params['c']);
		
		$this->view->modules = $actionplanClass->getActionPlanModules($params['c']);
		$this->view->category_id = $params['c'];

		$this->renderTemplate('action_plan_target.tpl');
	}
	
	public function savetargetAction() {
		$params = $this->_getAllParams();
	
		Zend_Loader::LoadClass('actionplanClass', $this->modelDir);
		$actionplanClass = new actionplanClass();
	
		$this->view->target = $actionplanClass->saveActionPlanTarget($params);
	}
	
	public function gettargetbyidAction() {
		$params = $this->_getAllParams();
		Zend_Loader::LoadClass('actionplanClass', $this->modelDir);
		$actionplanClass = new actionplanClass();
		
		if(!empty($params['id'])) 
		{
			echo json_encode($actionplanClass->getActionPlanTargetById($params['id']));
		}
	}
	
	public function deletetargetbyidAction() {
		$params = $this->_getAllParams();
		Zend_Loader::LoadClass('actionplanClass', $this->modelDir);
		$actionplanClass = new actionplanClass();
		
		$actionplanClass->deleteActionPlanTargetById($params['id']);
		
		$this->_response->setRedirect($this->baseUrl.'/default/actionplan/target/c/'.$params['c']);
		$this->_response->sendResponse();
		exit();
	}
	
	public function gettargetbymoduleidAction() {
		$params = $this->_getAllParams();
		Zend_Loader::LoadClass('actionplanClass', $this->modelDir);
		$actionplanClass = new actionplanClass();
		
		if(!empty($params['mid'])) 
		{
			echo json_encode($actionplanClass->getActionPlanTargetByModuleId($params['mid']));
		}
	}
	
	/*** ACTIVITY ***/
	
	public function activityAction() {
		$params = $this->_getAllParams();
		
		$params['user_id'] = $this->ident['user_id'];
		Zend_Loader::LoadClass('actionplanClass', $this->modelDir);
		$actionplanClass = new actionplanClass();
		
		$this->view->activity = $actionplanClass->getActionPlanActivity($params['c']);
		
		$this->view->target = $actionplanClass->getActionPlanTarget($params['c']);
		$this->view->category_id = $params['c'];

		$this->renderTemplate('action_plan_activity.tpl');
	}
	
	public function saveactivityAction() {
		$params = $this->_getAllParams();
	
		Zend_Loader::LoadClass('actionplanClass', $this->modelDir);
		$actionplanClass = new actionplanClass();
	
		$this->view->target = $actionplanClass->saveActionPlanActivity($params);
	}
	
	public function getactivitybyidAction() {
		$params = $this->_getAllParams();
		Zend_Loader::LoadClass('actionplanClass', $this->modelDir);
		$actionplanClass = new actionplanClass();
		
		if(!empty($params['id'])) 
		{
			echo json_encode($actionplanClass->getActionPlanActivityById($params['id']));
		}
	}
	
	public function deleteactivitybyidAction() {
		$params = $this->_getAllParams();
		Zend_Loader::LoadClass('actionplanClass', $this->modelDir);
		$actionplanClass = new actionplanClass();
		
		$actionplanClass->deleteActionPlanActivityById($params['id']);
		
		$this->_response->setRedirect($this->baseUrl.'/default/actionplan/activity/c/'.$params['c']);
		$this->_response->sendResponse();
		exit();
	}
	
	public function getactivitybytargetidAction() {
		$params = $this->_getAllParams();
		Zend_Loader::LoadClass('actionplanClass', $this->modelDir);
		$actionplanClass = new actionplanClass();
		
		if(!empty($params['tid'])) 
		{
			echo json_encode($actionplanClass->getActionPlanActivityByTargetId($params['tid']));
		}
	}
	
	/*** REMINDER ***/
	
	public function reminderAction() {
		$params = $this->_getAllParams();
		
		$params['user_id'] = $this->ident['user_id'];
		Zend_Loader::LoadClass('actionplanClass', $this->modelDir);
		$actionplanClass = new actionplanClass();
		
		$this->view->email = $actionplanClass->getActionPlanEmail($params['c']);
		
		$this->view->category_id = $params['c'];

		$this->renderTemplate('action_plan_reminder.tpl');
	}
	
	public function saveemailAction() {
		$params = $this->_getAllParams();
	
		Zend_Loader::LoadClass('actionplanClass', $this->modelDir);
		$actionplanClass = new actionplanClass();
	
		$this->view->email = $actionplanClass->saveActionPlanEmail($params);
	}
	
	public function getemailbyidAction() {
		$params = $this->_getAllParams();
		Zend_Loader::LoadClass('actionplanClass', $this->modelDir);
		$actionplanClass = new actionplanClass();
		
		if(!empty($params['id'])) 
		{
			echo json_encode($actionplanClass->getActionPlanEmailById($params['id']));
		}
	}
	
	public function deleteemailbyidAction() {
		$params = $this->_getAllParams();
		Zend_Loader::LoadClass('actionplanClass', $this->modelDir);
		$actionplanClass = new actionplanClass();
		
		$actionplanClass->deleteActionPlanEmailById($params['id']);
		
		$this->_response->setRedirect($this->baseUrl.'/default/actionplan/reminder/c/'.$params['c']);
		$this->_response->sendResponse();
		exit();
	}
	
	public function sendreminderAction() {
		$params = $this->_getAllParams();
		
		$categoryClass = $this->loadModel('category');
		$category = $categoryClass->getCategoryById('1');	
					
		$botToken = '476346623:AAFlB9X5-bShAkdTK9ziNDrfZ0TCePXl2cY';
		$website="https://api.telegram.org/bot".$botToken;
		$chatId=$category['site_'.$this->site_id];  //Receiver Chat Id 
		
		Zend_Loader::LoadClass('actionplanClass', $this->modelDir);
		$actionplanClass = new actionplanClass();
		
		$schedule = $actionplanClass->getTomorrowSchedule($params['category_id'], $params['site_id']);
		$html = '<table cellpadding="0" cellspacing="0">
					<tr>
						<th style="background-color:#555; color: #fff; border:1px solid #fff; padding:5px;">No.</th>
						<th style="background-color:#555; color: #fff; border:1px solid #fff; padding:5px;">Module</th>
						<th style="background-color:#555; color: #fff; border:1px solid #fff; padding:5px;">Target</th>
						<th style="background-color:#555; color: #fff; border:1px solid #fff; padding:5px;">Activity</th>
						<th style="background-color:#555; color: #fff; border:1px solid #fff; padding:5px;">Date</th>
					</tr>';
		$i=1;
		foreach($schedule as $sch)
		{
			$date = explode(" ",$sch['schedule_date']);
			$schedule_date = date("j F Y");
			$html.='<tr>
					<td style="border:1px solid #bbb; padding:5px;">'.$i.'</td>
					<td style="border:1px solid #bbb; padding:5px;">'.$sch['module_name'].'</td>
					<td style="border:1px solid #bbb; padding:5px;">'.$sch['target_name'].'</td>
					<td style="border:1px solid #bbb; padding:5px;">'.$sch['activity_name'].'</td>
					<td style="border:1px solid #bbb; padding:5px;">'.$schedule_date.'</td>
					</tr>';
					
			$txt = '[ACTION PLAN REMINDER] 
'.$sch['activity_name'].'
'.$schedule_date;
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
					
			$i++;
		}
		$html .= "</table>";
		
		
		require_once 'Zend/Mail.php';
		$mail = new Zend_Mail();
		$mail->setBodyHtml($html);
		$mail->setFrom("srt@pakuwon.com");
		
		$emails = $actionplanClass->getActionPlanEmail($params['category_id']);
		
		Zend_Loader::LoadClass('siteClass', $this->modelDir);
		$siteClass = new siteClass();
		
		$site = $siteClass->getSiteById($params['site_id']);
		
		if(!empty($emails))		
		{
			foreach($emails as $email)
			{
				$mail->addTo($email['email']);
			}
		
			$mail->setSubject($site['site_name'] . ' - Action Plan Reminder for Upcoming Schedule');
			
			try {
				$mail->send();
				echo "success";
			}
			catch (Exception  $ex) {
				echo "failed=".$ex;
			}
			unset($mail);
				
			echo $html;
		}
	}
	
	/*** ACTION PLAN RESCHEDULE ***/
	
	public function reschedulelistAction() {
		$params = $this->_getAllParams();
		
		Zend_Loader::LoadClass('actionplanClass', $this->modelDir);
		$actionplanClass = new actionplanClass();
		
		$this->view->rescheduleList = $actionplanClass->getActionPlanRescheduleList();
		
		$this->renderTemplate('action_plan_reschedule_list.tpl');
	}
	
	public function approverescheduleAction() {
		$params = $this->_getAllParams();
		
		Zend_Loader::LoadClass('actionplanClass', $this->modelDir);
		$actionplanClass = new actionplanClass();
		
		$actionplanClass->approveReschedule($params['id']);
		
		$reschedule = $actionplanClass->getRescheduleById($params['id']);
		$actionplanClass->updateScheduleDate($reschedule['action_plan_schedule_id'], $reschedule['reschedule_date']);
		
		$this->_response->setRedirect($this->baseUrl.'/default/actionplan/reschedulelist');
		$this->_response->sendResponse();
		exit();
	}
	
	/*** ACTION PLAN ATTACHMENT ***/
	
	public function getattachmentbyscheduleidAction() {
		$params = $this->_getAllParams();
		Zend_Loader::LoadClass('actionplanClass', $this->modelDir);
		$actionplanClass = new actionplanClass();
		
		if(!empty($params['id'])) 
		{
			$attachments = $actionplanClass->getActionPlanAttachmentByScheduleId($params['id']);

			Zend_Loader::LoadClass('categoryClass', $this->modelDir);
			$categoryClass = new categoryClass();
		
			$category = $categoryClass->getCategoryById($params['category_id']);
			
			if(!empty($attachments))
			{
				$html='<ul style="padding-left: 20px;">';
				foreach($attachments as $attachment)
				{
					$html.='<li><a href="'.$this->baseUrl.'/actionplan/'.strtolower($category['category_name']).'/'.$attachment['filename'].'" target="_blank">'.$attachment['filename'].'</a></li>';
				}
				$html.="</ul>";
			}
			echo $html;
		}
	}
	
	
	/*** UPCOMING SCHEDULE ***/
	
	function getWeekDates($year, $week, $start=true)
	{
		$from = date("Y-m-d", strtotime("{$year}-W{$week}-1")); //Returns the date of monday in week
		$to = date("Y-m-d", strtotime("{$year}-W{$week}-7"));   //Returns the date of sunday in week
	 
		if($start) {
			return $from;
		} else {
			return $to;
		}
		//return "Week {$week} in {$year} is from {$from} to {$to}.";
	}
	
	public function upcomingAction() {
		$params = $this->_getAllParams();
		
		$params['user_id'] = $this->ident['user_id'];
		Zend_Loader::LoadClass('actionplanClass', $this->modelDir);
		$actionplanClass = new actionplanClass();
		
		$currentWeek = date('W', strtotime(date("Y-m-d")));
		
		$startdate = $this->getWeekDates(date("Y"), ($currentWeek+1), true);
		$enddate = $this->getWeekDates(date("Y"), ($currentWeek+1), false);
		$this->view->startdate = date("j F Y", strtotime($startdate));
		$this->view->enddate = date("j F Y", strtotime($enddate));
		$this->view->month = date("F", strtotime($startdate));
		$this->view->week = $this->weekOfMonth($startdate);
		
		$this->view->sitename = $this->ident['site_fullname'];
		
		$schedule = $actionplanClass->getActionPlanUpcomingSchedule($params['c'], $startdate, $enddate);
		
		foreach($schedule as &$sch)
		{
			$date = explode(" ", $sch['schedule_date']);
			$sch['formatted_schedule_date'] = date("j F Y", strtotime($date[0]));
		}
		
		$this->view->schedule = $schedule;
		
		$this->view->category_id = $params['c'];

		$this->renderTemplate('action_plan_upcoming.tpl');
	}
	
}

?>