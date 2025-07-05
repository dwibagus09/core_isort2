<?php
require_once('actionControllerBase.php');

class HousekeepingController extends actionControllerBase
{
	public function addAction() {
		$this->view->ident = $this->ident;
		$this->view->title = "Add Housekeeping Report";
		
		$settingTable = $this->loadModel('setting');
		$this->view->setting = $settingTable->getOtherSetting();
		
		$tangkapanTable = $this->loadModel('tangkapan');
		$this->view->hasilTangkapan = $tangkapanTable->getHousekeepingHasilTangkapan();

		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "Add Housekeeping Daily Report";
		$logData['data'] = "Opening the form page";
		$logsTable->insertLogs($logData);	
		
		$this->renderTemplate('form_daily_housekeeping.tpl'); 
	}
	
	public function savereportAction() {
		set_time_limit(7200);
		$params = $this->_getAllParams();
		$params['user_id'] = $this->ident['user_id'];
		Zend_Loader::LoadClass('housekeepingClass', $this->modelDir);
		$housekeepingClass = new housekeepingClass();
		
		$report = $housekeepingClass->getReportByDate(date("Y-m-d"));
		if(empty($params['housekeeping_report_id']) && !empty($report))
		{
			$this->view->title = "Add Housekeeping Report";
			$this->view->message="Report is already exist";
			$this->view->housekeeping = $params; 
			
			$settingTable = $this->loadModel('setting');
			$this->view->setting = $settingTable->getOtherSetting();
			
			$tangkapanTable = $this->loadModel('tangkapan');
			$this->view->hasilTangkapan = $tangkapanTable->getHousekeepingHasilTangkapan();
			
			$this->renderTemplate('form_daily_housekeeping.tpl'); 
			exit();
		}
		
		$params['housekeeping_report_id'] = $housekeepingClass->saveReport($params);
		
		$tangkapanTable = $this->loadModel('tangkapan');
		$tangkapanTable->deleteTangkapanByHousekeepingReportId($params['housekeeping_report_id']);
		if(!empty($params['tangkapan_id']))
		{
			$i = 0;
			foreach($params['tangkapan_id'] as $tangkapan_id)
			{
				if(!empty($params['hasil_tangkapan_shift1'][$i]) || !empty($params['hasil_tangkapan_shift2'][$i]) || !empty($params['hasil_tangkapan_shift3'][$i])) {
					$dt['housekeeping_report_id'] = $params['housekeeping_report_id'];
					$dt['tangkapan_id'] = $tangkapan_id;
					$dt['hasil_tangkapan_shift1'] = $params['hasil_tangkapan_shift1'][$i];
					$dt['hasil_tangkapan_shift2'] = $params['hasil_tangkapan_shift2'][$i];
					$dt['hasil_tangkapan_shift3'] = $params['hasil_tangkapan_shift3'][$i];
					$tangkapanTable->addHousekeepingTangkapan($dt);
				}
				$i++;
			}
		}
		
		$workTargetTable = $this->loadModel('worktarget');
		$workTargetTable->deleteHousekeepingWorkTargetByReportId($params['housekeeping_report_id']);
		if(!empty($params['work_target']))
		{		
			$k = 0;
			foreach($params['work_target'] as $work_target)
			{
				$dt2=array();
				$dt2['housekeeping_report_id'] = $params['housekeeping_report_id'];
				$dt2['work_target'] = $work_target;
				$dt2['shift1'] = $params['work_target_shift1'][$k];
				$dt2['shift2'] = $params['work_target_shift2'][$k];
				$dt2['shift3'] = $params['work_target_shift3'][$k];
				$workTargetTable->addHousekeepingWorkTarget($dt2);
				$k++;
			}			
		}
		
		$trainingTable = $this->loadModel('training');
		$trainingTable->deleteHousekeepingTrainingByReportId($params['housekeeping_report_id']);
		if(!empty($params['training_name']))
		{		
			$l = 0;
			foreach($params['training_name'] as $training_name)
			{
				$dt3=array();
				$dt3['housekeeping_report_id'] = $params['housekeeping_report_id'];
				$dt3['training_name'] = $training_name;
				$dt3['shift1'] = $params['training_shift1'][$l];
				$dt3['shift2'] = $params['training_shift2'][$l];
				$dt3['shift3'] = $params['training_shift3'][$l];
				$trainingTable->addHousekeepingTraining($dt3);
				$l++;
			}			
		}

		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "Save Housekeeping Daily Report - Page 1";
		$logData['data'] = json_encode($params);
		$logsTable->insertLogs($logData);	
		
		$this->_response->setRedirect($this->baseUrl.'/default/housekeeping/page2/id/'.$params['housekeeping_report_id']);
		$this->_response->sendResponse();
		exit();
	}
	
	function page2Action() {
		$params = $this->_getAllParams();

		Zend_Loader::LoadClass('housekeepingClass', $this->modelDir);
		$housekeepingClass = new housekeepingClass();
		$housekeeping = $housekeepingClass->getReportById($params['id']);
		$datetime = explode(" ",$housekeeping['created_date']);
		$date = explode("-",$datetime[0]);
		$r_date = mktime(0, 0, 0, intval($date[1]), intval($date[2]), intval($date[0]));
		$housekeeping['report_date'] = date("l, j F Y", $r_date);
		$this->view->housekeeping = $housekeeping;
		
		$settingTable = $this->loadModel('setting');
		$this->view->setting = $settingTable->getOtherSetting();
		
		$progressreportTable = $this->loadModel('progressreport');
		
		$progress_report_shift12 = $progressreportTable->getHousekeepingProgressReport($params['id'], '12');
		foreach($progress_report_shift12 as &$pr12)
		{
			if($pr12['upload_date'] > "2019-10-23 23:59:59")
				$imageURL = "/images/progress_report_root/".date("Ym")."/";
			else
				$imageURL = "/images/progress_report/";
			
			if(!empty($pr12['img_before']))	$pr12['img_before'] = $imageURL.$pr12['img_before'];
			if(!empty($pr12['img_progress']))	$pr12['img_progress'] = $imageURL.$pr12['img_progress'];
			if(!empty($pr12['img_after']))	$pr12['img_after'] = $imageURL.$pr12['img_after'];
		}
		$this->view->progress_report_shift12 = $progress_report_shift12;
		$progress_report_shift3 = $progressreportTable->getHousekeepingProgressReport($params['id'], '3');
		foreach($progress_report_shift3 as &$pr3)
		{
			if($pr3['upload_date'] > "2019-10-23 23:59:59")
				$imageURL = "/images/progress_report_root/".date("Ym")."/";
			else
				$imageURL = "/images/progress_report/";
			
			if(!empty($pr3['img_before']))	$pr3['img_before'] = $imageURL.$pr3['img_before'];
			if(!empty($pr3['img_progress']))	$pr3['img_progress'] = $imageURL.$pr3['img_progress'];
			if(!empty($pr3['img_after']))	$pr3['img_after'] = $imageURL.$pr3['img_after'];
		}
		$this->view->progress_report_shift3 = $progress_report_shift3;
		$other_info = $progressreportTable->getHousekeepingOtherInfo($params['id']);
		foreach($other_info as &$oi)
		{
			if($oi['upload_date'] > "2019-10-23 23:59:59")
				$imageURL = "/images/progress_report_root/".date("Ym")."/";
			else
				$imageURL = "/images/progress_report/";
			
			if(!empty($oi['img_progress']))	$oi['img_progress'] = $imageURL.$oi['img_progress'];
		}
		$this->view->other_info = $other_info;
		
		$this->view->attachment = $housekeepingClass->getAttachments($params['id']);

		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "Open Housekeeping Daily Report - Page 2";
		$logData['data'] = json_encode($params);
		$logsTable->insertLogs($logData);	
		
		$this->renderTemplate('form_daily_housekeeping2.tpl'); 
		
	}
	
	public function savereport2Action() {
		set_time_limit(7200);
		$params = $this->_getAllParams();
		$params['user_id'] = $this->ident['user_id'];
		Zend_Loader::LoadClass('housekeepingClass', $this->modelDir);
		$housekeepingClass = new housekeepingClass();

		$progressreportTable = $this->loadModel('progressreport');
		
		/*** Progress report shift 1 & 2 ***/
		if(!empty($params['area_progress_shift12']))
		{		
			$m = 0;
			$dt4=array();
			foreach($params['area_progress_shift12'] as $area_progress_shift12)
			{
				if($params['progress_report_id_shift12'][$m] > 0)
				{
					$existingProgressReport = array();
					$dt4[$m] = $progressreportTable->getHousekeepingProgressReportById($params['progress_report_id_shift12'][$m]);
					if(!empty($area_progress_shift12)) $dt4[$m]['area'] = $area_progress_shift12;
					if(!empty($params['status_progress_shift12'][$m])) $dt4[$m]['status'] = $params['status_progress_shift12'][$m];
				}
				else
				{
					$dt4[$m]['housekeeping_report_id'] = $params['housekeeping_report_id'];
					$dt4[$m]['area'] = $area_progress_shift12;
					$dt4[$m]['shift'] = '12';
					$dt4[$m]['status'] = $params['status_progress_shift12'][$m];
				}				
				if(!empty($_FILES["img_before_shift12"]['name'][$m]))
				{
					$dt4[$m]['img_before_shift12'] = $_FILES["img_before_shift12"];
				}
				if(!empty($_FILES["img_progress_shift12"]['name'][$m]))
				{
					$dt4[$m]['img_progress_shift12'] = $_FILES["img_progress_shift12"];
				}
				if(!empty($_FILES["img_after_shift12"]['name'][$m]))
				{
					$dt4[$m]['img_after_shift12'] = $_FILES["img_after_shift12"];
				}
				$m++;
			}			
			$progressreportTable->deleteHousekeepingProgressReportByReportId($params['housekeeping_report_id'], '12');
			$m=0;
			
			foreach($dt4 as $dt)
			{
				$img_before_shift12 = $dt['img_before_shift12'];
				unset($dt['img_before_shift12']);
				$img_progress_shift12 = $dt['img_progress_shift12'];
				unset($dt['img_progress_shift12']);
				$img_after_shift12 = $dt['img_after_shift12'];
				unset($dt['img_after_shift12']);
				$progress_report_id = $progressreportTable->addHousekeepingProgressReport($dt);
				if($progress_report_id > 0)
				{
					if(!empty($img_before_shift12['name'][$m]))
					{
						$ext = explode(".",$img_before_shift12['name'][$m]);
						$filename = $progress_report_id."_before_shift12.".$ext[count($ext)-1];
						if(move_uploaded_file($img_before_shift12["tmp_name"][$m], $this->config->paths->html."/images/progress_report/".$filename))
							$progressreportTable->updateFileName($progress_report_id,'img_before', $filename);
					}
					
					if(!empty($img_progress_shift12['name'][$m]))
					{
						$ext = explode(".",$img_progress_shift12['name'][$m]);
						$filename = $progress_report_id."_progress_shift12.".$ext[count($ext)-1];
						if(move_uploaded_file($img_progress_shift12["tmp_name"][$m], $this->config->paths->html."/images/progress_report/".$filename))
							$progressreportTable->updateFileName($progress_report_id,'img_progress', $filename);
					}
					
					if(!empty($img_after_shift12['name'][$m]))
					{
						$ext = explode(".",$img_after_shift12['name'][$m]);
						$filename = $progress_report_id."_after_shift12.".$ext[count($ext)-1];
						if(move_uploaded_file($img_after_shift12["tmp_name"][$m], $this->config->paths->html."/images/progress_report/".$filename))
							$progressreportTable->updateFileName($progress_report_id,'img_after', $filename);
					}
				}
				$m++;
			}			
		}
		
		/*** Progress report shift 3 ***/
		if(!empty($params['area_progress_shift3']))
		{		
			$m = 0;
			$dt5=array();
			foreach($params['area_progress_shift3'] as $area_progress_shift3)
			{
				if($params['progress_report_id_shift3'][$m] > 0)
				{
					$existingProgressReport = array();
					$dt5[$m] = $progressreportTable->getHousekeepingProgressReportById($params['progress_report_id_shift3'][$m]);
					if(!empty($area_progress_shift3)) $dt5[$m]['area'] = $area_progress_shift3;
					if(!empty($params['status_progress_shift3'][$m])) $dt5[$m]['status'] = $params['status_progress_shift3'][$m];
				}
				else
				{
					$dt5[$m]['housekeeping_report_id'] = $params['housekeeping_report_id'];
					$dt5[$m]['area'] = $area_progress_shift3;
					$dt5[$m]['shift'] = '3';
					$dt5[$m]['status'] = $params['status_progress_shift3'][$m];
					
				}	
				if(!empty($_FILES["img_before_shift3"]['name'][$m]))
				{
					$dt5[$m]['img_before_shift3'] = $_FILES["img_before_shift3"];
				}
					
				if(!empty($_FILES["img_progress_shift3"]['name'][$m]))
				{
					$dt5[$m]['img_progress_shift3'] = $_FILES["img_progress_shift3"];
				}
				
				if(!empty($_FILES["img_after_shift3"]['name'][$m]))
				{
					$dt5[$m]['img_after_shift3'] = $_FILES["img_after_shift3"];
				}
				$m++;
			}			
			$progressreportTable->deleteHousekeepingProgressReportByReportId($params['housekeeping_report_id'], '3');
			$m=0;
			foreach($dt5 as $dt)
			{
				$img_before_shift3 = $dt['img_before_shift3'];
				unset($dt['img_before_shift3']);
				$img_progress_shift3 = $dt['img_progress_shift3'];
				unset($dt['img_progress_shift3']);
				$img_after_shift3 = $dt['img_after_shift3'];
				unset($dt['img_after_shift3']);
				$progress_report_id = $progressreportTable->addHousekeepingProgressReport($dt);
				if($progress_report_id > 0)
				{
					if(!empty($img_before_shift3['name'][$m]))
					{
						$ext = explode(".",$img_before_shift3['name'][$m]);
						$filename = $progress_report_id."_before_shift1.".$ext[count($ext)-1];
						if(move_uploaded_file($img_before_shift3["tmp_name"][$m], $this->config->paths->html."/images/progress_report/".$filename))
							$progressreportTable->updateFileName($progress_report_id,'img_before', $filename);
					}
					
					if(!empty($img_progress_shift3['name'][$m]))
					{
						$ext = explode(".",$img_progress_shift3['name'][$m]);
						$filename = $progress_report_id."_progress_shift3.".$ext[count($ext)-1];
						if(move_uploaded_file($img_progress_shift3["tmp_name"][$m], $this->config->paths->html."/images/progress_report/".$filename))
							$progressreportTable->updateFileName($progress_report_id,'img_progress', $filename);
					}
					
					if(!empty($img_after_shift3['name'][$m]))
					{
						$ext = explode(".",$img_after_shift3['name'][$m]);
						$filename = $progress_report_id."_after_shift3.".$ext[count($ext)-1];
						if(move_uploaded_file($img_after_shift3["tmp_name"][$m], $this->config->paths->html."/images/progress_report/".$filename))
							$progressreportTable->updateFileName($progress_report_id,'img_after', $filename);
					}
				}
				$m++;
			}			
		}
		
		/*** Pest Control and Other Information ***/
		if(!empty($params['other_info_area']))
		{		
			$m = 0;
			$dt5=array();
			foreach($params['other_info_area'] as $other_info_area)
			{
				if($params['other_info_id'][$m] > 0)
				{
					$existingProgressReport = array();
					$dt5[$m] = $progressreportTable->getHousekeepingOtherInfoById($params['other_info_id'][$m]);
					if(!empty($other_info_area)) $dt5[$m]['area'] = $other_info_area;
					if(!empty($params['other_info_status'][$m])) $dt5[$m]['status'] = $params['other_info_status'][$m];
				}
				else
				{
					$dt5[$m]['housekeeping_report_id'] = $params['housekeeping_report_id'];
					$dt5[$m]['area'] = $other_info_area;
					$dt5[$m]['shift'] = '3';
					$dt5[$m]['status'] = $params['other_info_status'][$m];
					
				}	
				if(!empty($_FILES["other_info_progress"]['name'][$m]))
				{
					$dt5[$m]['other_info_progress'] = $_FILES["other_info_progress"];
				}
				$m++;
			}			
			$progressreportTable->deleteHousekeepingOtherInfoByReportId($params['housekeeping_report_id']);
			$m=0;
			foreach($dt5 as $dt)
			{
				$other_info_progress = $dt['other_info_progress'];
				unset($dt['other_info_progress']);
				$progress_report_id = $progressreportTable->addHousekeepingOtherInfo($dt);
				if($progress_report_id > 0)
				{
					if(!empty($other_info_progress['name'][$m]))
					{
						$ext = explode(".",$other_info_progress['name'][$m]);
						$filename = $progress_report_id."_other_info.".$ext[count($ext)-1];
						if(move_uploaded_file($other_info_progress["tmp_name"][$m], $this->config->paths->html."/images/progress_report/".$filename))
							$progressreportTable->updateHKOtherInfoFileName($progress_report_id,'img_progress', $filename);
					}
				}
				$m++;
			}			
		}
		
		if(!empty($params['attachment-description']))
		{		
			$r = 0;
			$dt7=array();
			foreach($params['attachment-description'] as $description)
			{
				if($params['attachment_id'][$r] > 0)
				{
					$existingAttachment = array();
					$dt7[$r] = $housekeepingClass->getAttachmentById($params['attachment_id'][$r]);
					if(!empty($description)) $dt7[$r]['description'] = $description;
				}
				else
				{
					$dt7[$r]['site_id'] = $this->site_id;
					$dt7[$r]['report_id'] = $params['housekeeping_report_id'];
					$dt7[$r]['description'] = $description;
					
				}	
				if(!empty($_FILES["attachment_file"]['name'][$r]))
				{	
					$dt7[$r]['attachment'] = $_FILES["attachment_file"];
				}
				$r++;
			}			
			$housekeepingClass->deleteAttachmentByReportId($params['housekeeping_report_id']);
			$r=0;
			foreach($dt7 as $dt)
			{
				$attachment = $dt['attachment'];
				unset($dt['attachment']);
				$attachment_id = $housekeepingClass->addAttachment($dt);
				if($attachment_id > 0)
				{
					if(!empty($attachment['name'][$r]))
					{
						$ext = explode(".",$attachment['name'][$r]);
						$filename = $attachment_id.".".$ext[count($ext)-1];
						if(move_uploaded_file($attachment["tmp_name"][$r], $this->config->paths->html."/attachment/housekeeping/".$filename))
							$housekeepingClass->updateAttachment($attachment_id,'filename', $filename);
					}
				}
				$r++;
			}			
		}
		
		$housekeeping['housekeeping_report_id'] = $params['housekeeping_report_id'];
		$housekeeping['report_date'] = $params['report_date'];
		$this->view->housekeeping = $housekeeping;
		
		$settingTable = $this->loadModel('setting');
		$this->view->setting = $settingTable->getOtherSetting();
		
		$this->view->other_info = $progressreportTable->getHousekeepingOtherInfo($params['housekeeping_report_id']);
		
		$this->view->attachment = $housekeepingClass->getAttachments($params['housekeeping_report_id']);

		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "Open Housekeeping Daily Report - Page 3";
		$logData['data'] = json_encode($params);
		$logsTable->insertLogs($logData);	
		
		$this->renderTemplate('form_daily_housekeeping3.tpl'); 
	}
	
	public function savereport3Action() {
		set_time_limit(7200);
		$params = $this->_getAllParams();
		$params['user_id'] = $this->ident['user_id'];
		Zend_Loader::LoadClass('housekeepingClass', $this->modelDir);
		$housekeepingClass = new housekeepingClass();

		$progressreportTable = $this->loadModel('progressreport');
		
		/*** Pest Control and Other Information ***/
		if(!empty($params['other_info_area']))
		{		
			$m = 0;
			$dt5=array();
			foreach($params['other_info_area'] as $other_info_area)
			{
				if($params['other_info_id'][$m] > 0)
				{
					$existingProgressReport = array();
					$dt5[$m] = $progressreportTable->getHousekeepingOtherInfoById($params['other_info_id'][$m]);
					if(!empty($other_info_area)) $dt5[$m]['area'] = $other_info_area;
					if(!empty($params['other_info_status'][$m])) $dt5[$m]['status'] = $params['other_info_status'][$m];
				}
				else
				{
					$dt5[$m]['housekeeping_report_id'] = $params['housekeeping_report_id'];
					$dt5[$m]['area'] = $other_info_area;
					$dt5[$m]['shift'] = '3';
					$dt5[$m]['status'] = $params['other_info_status'][$m];
					
				}	
				if(!empty($_FILES["other_info_progress"]['name'][$m]))
				{
					$dt5[$m]['other_info_progress'] = $_FILES["other_info_progress"];
				}
				$m++;
			}			
			$progressreportTable->deleteHousekeepingOtherInfoByReportId($params['housekeeping_report_id']);
			$m=0;
			foreach($dt5 as $dt)
			{
				$other_info_progress = $dt['other_info_progress'];
				unset($dt['other_info_progress']);
				$progress_report_id = $progressreportTable->addHousekeepingOtherInfo($dt);
				if($progress_report_id > 0)
				{
					if(!empty($other_info_progress['name'][$m]))
					{
						$ext = explode(".",$other_info_progress['name'][$m]);
						$filename = $progress_report_id."_other_info.".$ext[count($ext)-1];
						if(move_uploaded_file($other_info_progress["tmp_name"][$m], $this->config->paths->html."/images/progress_report/".$filename))
							$progressreportTable->updateHKOtherInfoFileName($progress_report_id,'img_progress', $filename);
					}
				}
				$m++;
			}			
		}
		
		if(!empty($params['attachment-description']))
		{		
			$r = 0;
			$dt7=array();
			foreach($params['attachment-description'] as $description)
			{
				if($params['attachment_id'][$r] > 0)
				{
					$existingAttachment = array();
					$dt7[$r] = $housekeepingClass->getAttachmentById($params['attachment_id'][$r]);
					if(!empty($description)) $dt7[$r]['description'] = $description;
				}
				else
				{
					$dt7[$r]['site_id'] = $this->site_id;
					$dt7[$r]['report_id'] = $params['housekeeping_report_id'];
					$dt7[$r]['description'] = $description;
					
				}	
				if(!empty($_FILES["attachment_file"]['name'][$r]))
				{	
					$dt7[$r]['attachment'] = $_FILES["attachment_file"];
				}
				$r++;
			}			
			$housekeepingClass->deleteAttachmentByReportId($params['housekeeping_report_id']);
			$r=0;
			foreach($dt7 as $dt)
			{
				$attachment = $dt['attachment'];
				unset($dt['attachment']);
				$attachment_id = $housekeepingClass->addAttachment($dt);
				if($attachment_id > 0)
				{
					if(!empty($attachment['name'][$r]))
					{
						$ext = explode(".",$attachment['name'][$r]);
						$filename = $attachment_id.".".$ext[count($ext)-1];
						if(move_uploaded_file($attachment["tmp_name"][$r], $this->config->paths->html."/attachment/housekeeping/".$filename))
							$housekeepingClass->updateAttachment($attachment_id,'filename', $filename);
					}
				}
				$r++;
			}			
		}

		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "Save Housekeeping Daily Report - Page 3";
		$logData['data'] = json_encode($params);
		$logsTable->insertLogs($logData);	
		
		$this->_response->setRedirect($this->baseUrl.'/default/housekeeping/viewreport');
		$this->_response->sendResponse();
		exit();
	}
	
	public function viewreportAction() {
		$params = $this->_getAllParams();
		Zend_Loader::LoadClass('housekeepingClass', $this->modelDir);
		$housekeepingClass = new housekeepingClass();
		Zend_Loader::LoadClass('housekeepingcommentsClass', $this->modelDir);
		$housekeepingcommentsClass = new housekeepingcommentsClass();
		if(empty($params['start'])) $params['start'] = '0';
		$params['pagesize'] = 10;
		$this->view->start = $params['start'];
		$housekeeping = $housekeepingClass->getReports($params);
		foreach($housekeeping as &$hk)
		{
			$date = explode(" ", $hk['created_date']);
			$arr_date = explode("-",$date[0]);
			if($hk['created_date'] >= date("Y-m-d", mktime(0, 0, 0, date("m"), date("d")-1,   date("Y")))) $hk['allowEdit'] = 1;
			else $hk['allowEdit'] = 0;
			$hk['day_date'] = date("l, j F Y", mktime(0, 0, 0, $arr_date[1], $arr_date[2], $arr_date[0]));
			$hk['comments'] = $housekeepingcommentsClass->getCommentsByHousekeepingReportId($hk['housekeeping_report_id'], '3', $this->site_id);
		}
		$this->view->housekeeping = $housekeeping;
		
		
		$totalReport = $housekeepingClass->getTotalReport();

		if($totalReport['total'] > 10)
		{
			if($params['start'] >= 10)
			{
				$this->view->firstPageUrl = "/default/housekeeping/viewreport";
				$this->view->prevUrl = "/default/housekeeping/viewreport/start/".($params['start']-$params['pagesize']);
			}
			if($params['start'] < (floor(($totalReport['total']-1)/10)*10))
			{
				$this->view->nextUrl = "/default/housekeeping/viewreport/start/".($params['start']+$params['pagesize']);
				$this->view->lastPageUrl = "/default/housekeeping/viewreport/start/".(floor(($totalReport['total']-1)/10)*10);
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
		$logData['action'] = "View Housekeeping Daily Report List";
		$logData['data'] = json_encode($params);
		$logsTable->insertLogs($logData);	
		
    	$this->renderTemplate('view_daily_housekeeping.tpl');  
	}
	
	public function editAction() {
		$params = $this->_getAllParams();
		Zend_Loader::LoadClass('housekeepingClass', $this->modelDir);
		$housekeepingClass = new housekeepingClass();
		
		if(!empty($params['id'])) 
		{
			$housekeeping = $housekeepingClass->getReportById($params['id']);
			$datetime = explode(" ",$housekeeping['created_date']);
			/*if($datetime[0] != date("Y-m-d")) 
			{
				$this->_response->setRedirect($this->baseUrl.'/default/housekeeping/viewreport');
				$this->_response->sendResponse();
				exit();
			}*/
			$date = explode("-",$datetime[0]);
			$r_date = mktime(0, 0, 0, intval($date[1]), intval($date[2]), intval($date[0]));
			$housekeeping['report_date'] = date("l, j F Y", $r_date);	
			$this->view->housekeeping = $housekeeping;
		
			$settingTable = $this->loadModel('setting');
			$this->view->setting = $settingTable->getOtherSetting();
			
			$tangkapanTable = $this->loadModel('tangkapan');
			$this->view->hasilTangkapan = $tangkapanTable->getHousekeepingHasilTangkapanByReportId($params['id']);
			
			$workTargetTable = $this->loadModel('worktarget');
			$this->view->work_target = $workTargetTable->getHousekeepingWorkTarget($params['id']);
			
			$trainingTable = $this->loadModel('training');
			$this->view->training = $trainingTable->getHousekeepingTraining($params['id']);
			
			$progressreportTable = $this->loadModel('progressreport');
			$this->view->progress_report_shift12 = $progressreportTable->getHousekeepingProgressReport($params['id'], '12');
			/*$this->view->progress_report_shift3 = $progressreportTable->getHousekeepingProgressReport($params['id'], '3');
			$this->view->other_info = $progressreportTable->getHousekeepingOtherInfo($params['id']);
			
			$this->view->attachment = $housekeepingClass->getAttachments($params['id']);*/
		}
		
		$this->view->title = "Edit Housekeeping Report";
		$this->view->editMode = 1;

		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "Edit Housekeeping Daily Report";
		$logData['data'] = json_encode($params);
		$logsTable->insertLogs($logData);	

		$this->renderTemplate('form_daily_housekeeping.tpl');  
	}

	public function viewdetailreportAction() {
		if($this->showHousekeeping == 1)
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
						$this->_response->setRedirect($this->baseUrl."/default/housekeeping/viewdetailreport/id/".$params['id']);
						$this->_response->sendResponse();
						exit();
					}
				}

				if(!empty($site_id))
				{
					$this->site_id = $site_id;
					Zend_Registry::set('site_id', $this->site_id);
					Zend_Loader::LoadClass('siteClass', $this->modelDir);
					$siteClass = new siteClass();
					$curSite = $siteClass->getSiteById($site_id);
					$this->ident['site_fullname'] =  $curSite['site_fullname'];
				}			

				require_once('fpdf/mc_table.php');
				Zend_Loader::LoadClass('housekeepingClass', $this->modelDir);
				$housekeepingClass = new housekeepingClass();
			
				$housekeeping = $housekeepingClass->getReportById($params['id']);

				$params['user_id'] = $this->ident['user_id'];
				$housekeepingClass->addReadHousekeepingReportLog($params);
				
				$datetime = explode(" ",$housekeeping['created_date']);
				
				$date = explode("-",$datetime[0]);
				$r_date = mktime(0, 0, 0, intval($date[1]), intval($date[2]), intval($date[0]));
				$housekeeping['report_date'] = date("l, j F Y", $r_date);	
				$this->view->housekeeping = $housekeeping;
			
				$settingTable = $this->loadModel('setting');
				$this->view->setting = $settingTable->getOtherSetting();
				
				$tangkapanTable = $this->loadModel('tangkapan');
				$this->view->hasilTangkapan = $tangkapanTable->getHousekeepingHasilTangkapanByReportId($params['id']);
				
				$workTargetTable = $this->loadModel('worktarget');
				$this->view->work_target = $workTargetTable->getHousekeepingWorkTarget($params['id']);
				
				$trainingTable = $this->loadModel('training');
				$this->view->training = $trainingTable->getHousekeepingTraining($params['id']);
		
				$progressreportTable = $this->loadModel('progressreport');

				$progress_report_shift12 = $progressreportTable->getHousekeepingProgressReport($params['id'], '12');
				foreach($progress_report_shift12 as &$pr12) {
					if($pr12['upload_date'] > "2019-10-23 23:59:59")
					{
						$uploaddate1 = explode("-",$pr12['upload_date']); 
						$imageURL = "/images/progress_report_root/".$uploaddate1[0].$uploaddate1[1]."/";
					}
					else
						$imageURL = "/images/progress_report/";
					
					if(!empty($pr12['img_before']))	$pr12['img_before'] = $imageURL.$pr12['img_before'];
					if(!empty($pr12['img_progress']))	$pr12['img_progress'] = $imageURL.$pr12['img_progress'];
					if(!empty($pr12['img_after']))	$pr12['img_after'] = $imageURL.$pr12['img_after'];
				}
				$this->view->progress_report_shift12 = $progress_report_shift12;
				$progress_report_shift3 = $progressreportTable->getHousekeepingProgressReport($params['id'], '3');
				foreach($progress_report_shift3 as &$pr3) {
					if($pr3['upload_date'] > "2019-10-23 23:59:59")
					{
						$uploaddate = explode("-",$pr3['upload_date']); 	
						$imageURL = "/images/progress_report_root/".$uploaddate[0].$uploaddate[1]."/";
					}
					else
						$imageURL = "/images/progress_report/";
					
					if(!empty($pr3['img_before']))	$pr3['img_before'] = $imageURL.$pr3['img_before'];
					if(!empty($pr3['img_progress']))	$pr3['img_progress'] = $imageURL.$pr3['img_progress'];
					if(!empty($pr3['img_after']))	$pr3['img_after'] = $imageURL.$pr3['img_after'];
				}
				$this->view->progress_report_shift3 = $progress_report_shift3;
				$other_info = $progressreportTable->getHousekeepingOtherInfo($params['id']);
				foreach($other_info as &$oi) {
					if($oi['upload_date'] > "2019-10-23 23:59:59")
					{
						$uploaddate2 = explode("-",$oi['upload_date']); 
						$imageURL = "/images/progress_report_root/".$uploaddate2[0].$uploaddate2[1]."/";
					}
					else
						$imageURL = "/images/progress_report/";
					
					if(!empty($oi['img_progress']))	$oi['img_progress'] = $imageURL.$oi['img_progress'];
				}
				$this->view->other_info = $other_info;
				
				$this->view->attachment = $housekeepingClass->getAttachments($params['id']);

				$this->view->ident = $this->ident;

				$housekeepingCommentsTable = $this->loadModel('housekeepingcomments');
				$this->view->comments = $housekeepingCommentsTable->getCommentsByHousekeepingReportId($params['id'], 0, $this->site_id, 'asc');

				$logsTable = $this->loadModel('logs');
				$logData['user_id'] = intval($this->ident['user_id']);
				$logData['action'] = "View Detail Housekeeping Daily Report";
				$logData['data'] = json_encode($params);
				$logsTable->insertLogs($logData);	

				$this->renderTemplate('view_hk_detail_report.tpl');  
			}
		}
		else {
			$this->_response->setRedirect($this->baseUrl);
			$this->_response->sendResponse();
			exit();
		}
	}
	
	public function exporttopdf2Action() {
		$params = $this->_getAllParams();
		Zend_Loader::LoadClass('housekeepingClass', $this->modelDir);
		$housekeepingClass = new housekeepingClass();
		
		if(!empty($params['id'])) 
		{
			$housekeeping = $housekeepingClass->getReportById($params['id']);
			$datetime = explode(" ",$housekeeping['created_date']);
			$date = explode("-",$datetime[0]);
			$r_date = mktime(0, 0, 0, intval($date[1]), intval($date[2]), intval($date[0]));
			$housekeeping['report_date'] = date("l, j F Y", $r_date);	
			$housekeeping = $housekeeping;
		
			$settingTable = $this->loadModel('setting');
			$setting = $settingTable->getOtherSetting();
			
			$tangkapanTable = $this->loadModel('tangkapan');
			$hasilTangkapan = $tangkapanTable->getHousekeepingHasilTangkapanByReportId($params['id']);
			
			$workTargetTable = $this->loadModel('worktarget');
			$work_target = $workTargetTable->getHousekeepingWorkTarget($params['id']);
			
			$trainingTable = $this->loadModel('training');
			$training = $trainingTable->getHousekeepingTraining($params['id']);
	
			$progressreportTable = $this->loadModel('progressreport');
			$progress_report_shift12 = $progressreportTable->getHousekeepingProgressReport($params['id'], '12');
			$progress_report_shift3 = $progressreportTable->getHousekeepingProgressReport($params['id'], '3');
			$other_info = $progressreportTable->getHousekeepingOtherInfo($params['id']);
			
			$attachment = $housekeepingClass->getAttachments($params['id']);
			
			require('PHPpdf/html2fpdf.php');


			$html= '<html>
			<head>
			<title>Daily Housekeeping Report</title>
			 
			</head>
			<body>
			<h2>Daily Housekeeping Report</h2>
			'.$housekeeping['site_fullname'].'
			
			<h3>DAY / DATE</h3>
			<table width="100%" cellspacing="6" cellpadding="5" align="center" border="1" valign="top">
				<tr><td><strong>Day / Date</strong></td><td colspan="3">'.$housekeeping['report_date'].'</td></tr>
				<tr><td><strong>Time</strong></td><td colspan="3">'.$setting['housekeeping_reporting_time'].'</td></tr>
			</table>
			
			<h3>MAN POWER</h3>
			<h4>A. In House</h4>
		<table width="100%" cellspacing="6" cellpadding="5" align="center" border="1" valign="top">
			<tr bgcolor="#afd9af">
				<th><strong>Description</strong></th>
				<th width="150"><strong>Shift 1</strong></th>
				<th width="150"><strong>Shift 2</strong></th>
				<th width="150"><strong>Shift 3</strong></th>
			</tr>
			<tr>
				<td>Chief Housekeeping</td>
				<td>'.$housekeeping['inhouse_chief_housekeeping_shift1'].'</td>
				<td>'.$housekeeping['inhouse_chief_housekeeping_shift2'].'</td>
				<td>'.$housekeeping['inhouse_chief_housekeeping_shift3'].'</td>
			</tr>
			<tr>
				<td>Supervisor</td>
				<td>'.$housekeeping['inhouse_supervisor_shift1'].'</td>
				<td>'.$housekeeping['inhouse_supervisor_shift2'].'</td>
				<td>'.$housekeeping['inhouse_supervisor_shift3'].'</td>
			</tr>
			<tr>
				<td>Staff</td>
				<td>'.$housekeeping['inhouse_staff_shift1'].'</td>
				<td>'.$housekeeping['inhouse_staff_shift2'].'</td>
				<td>'.$housekeeping['inhouse_staff_shift3'].'</td>
			</tr>
			<tr>
				<td>Administrasi</td>
				<td>'.$housekeeping['inhouse_admin_shift1'].'</td>
				<td>'.$housekeeping['inhouse_admin_shift2'].'</td>
				<td>'.$housekeeping['inhouse_admin_shift3'].'</td>
			</tr>
		</table>
		
		<h4>B. Outsourcing</h4>
		<table width="100%" cellspacing="6" cellpadding="5" align="center" border="1" valign="top">
			<tr bgcolor="#afd9af">
				<th><strong>Cleaning Area</strong></th>
				<th width="150"><strong>Shift 1</strong></th>
				<th width="150"><strong>Shift 2</strong></th>
				<th width="150"><strong>Shift 3</strong></th>
			</tr>
			<tr>
				<td>Chief Housekeeping</td>
				<td>'.$housekeeping['outsource_chief_housekeeping_shift1'].'</td>
				<td>'.$housekeeping['outsource_chief_housekeeping_shift2'].'</td>
				<td>'.$housekeeping['outsource_chief_housekeeping_shift3'].'</td>
			</tr>
			<tr>
				<td>Supervisor</td>
				<td>'.$housekeeping['outsource_supervisor_shift1'].'</td>
				<td>'.$housekeeping['outsource_supervisor_shift2'].'</td>
				<td>'.$housekeeping['outsource_supervisor_shift3'].'</td>
			</tr>
			<tr>
				<td>Leader</td>
				<td>'.$housekeeping['outsource_leader_shift1'].'</td>
				<td>'.$housekeeping['outsource_leader_shift2'].'</td>
				<td>'.$housekeeping['outsource_leader_shift3'].'</td>
			</tr>
			<tr>
				<td>Crew</td>
				<td>'.$housekeeping['outsource_crew_shift1'].'</td>
				<td>'.$housekeeping['outsource_crew_shift2'].'</td>
				<td>'.$housekeeping['outsource_crew_shift3'].'</td>
			</tr>
			<tr>
				<td>Toilet Crew</td>
				<td>'.$housekeeping['outsource_toilet_crew_shift1'].'</td>
				<td>'.$housekeeping['outsource_toilet_crew_shift2'].'</td>
				<td>'.$housekeeping['outsource_toilet_crew_shift3'].'</td>
			</tr>
			<tr>
				<td>Gondola</td>
				<td>'.$housekeeping['outsource_gondola_shift1'].'</td>
				<td>'.$housekeeping['outsource_gondola_shift2'].'</td>
				<td>'.$housekeeping['outsource_gondola_shift3'].'</td>
			</tr>
			<tr>
				<td>Admin</td>
				<td>'.$housekeeping['outsource_admin_shift1'].'</td>
				<td>'.$housekeeping['outsource_admin_shift2'].'</td>
				<td>'.$housekeeping['outsource_admin_shift3'].'</td>
			</tr>
			<tr>
				<td>Total</td>
				<td>'.$housekeeping['outsource_total_shift1'].'</td>
				<td>'.$housekeeping['outsource_total_shift2'].'</td>
				<td>'.$housekeeping['outsource_total_shift3'].'</td>
			</tr>
		</table>
			
		<table width="100%" cellspacing="6" cellpadding="5" align="center" border="1" valign="top">
			<tr bgcolor="#afd9af">
				<th><strong>Pest Control</strong></th>
				<th width="150"><strong>Shift 1</strong></th>
				<th width="150"><strong>Shift 2</strong></th>
				<th width="150"><strong>Shift 3</strong></th>
			</tr>
			<tr>
				<td>Koordinator</td>
				<td>'.$housekeeping['pest_control_koordinator_shift1'].'</td>
				<td>'.$housekeeping['pest_control_koordinator_shift2'].'</td>
				<td>'.$housekeeping['pest_control_koordinator_shift3'].'</td>
			</tr>
			<tr>
				<td>Leader</td>
				<td>'.$housekeeping['pest_control_leader_shift1'].'</td>
				<td>'.$housekeeping['pest_control_leader_shift2'].'</td>
				<td>'.$housekeeping['pest_control_leader_shift3'].'</td>
			</tr>
			<tr>
				<td>Crew</td>
				<td>'.$housekeeping['pest_control_crew_shift1'].'</td>
				<td>'.$housekeeping['pest_control_crew_shift2'].'</td>
				<td>'.$housekeeping['pest_control_crew_shift3'].'</td>
			</tr>
		</table>
		
		<h3>TARGET PEKERJAAN</h3>
		<table width="100%" cellspacing="6" cellpadding="5" align="center" border="1" valign="top">
			<tr bgcolor="#afd9af">
				<th><strong>Target Perkerjaan</strong></th>
				<th width="150"><strong>Shift 1</strong></th>
				<th width="150"><strong>Shift 2</strong></th>
				<th width="150"><strong>Shift 3</strong></th>
			</tr>';
			
			if(!empty($work_target)) {
				foreach($work_target as $wt) {
					$html .= '<tr>
						<td>'.$wt['work_target'].'</td>
						<td>'.$wt['shift1'].'</td>
						<td>'.$wt['shift2'].'</td>
						<td>'.$wt['shift3'].'</td>
					</tr>';
				}
			}
			$html .= '</table>
			
		<h3>HASIL TANGKAPAN</h3>
		<table width="100%" cellspacing="6" cellpadding="5" align="center" border="1" valign="top">
			<tr bgcolor="#afd9af">
				<th><strong>Hasil Tangkapan</strong></th>
				<th width="150"><strong>Shift 1</strong></th>
				<th width="150"><strong>Shift 2</strong></th>
				<th width="150"><strong>Shift 3</strong></th>
			</tr>';
			
			if(!empty($hasilTangkapan)) {
				foreach($hasilTangkapan as $ht) {
					$html .= '<tr>
						<td>'.$ht['hewan_tangkapan'].'</td>
						<td>'.$ht['shift1'].'</td>
						<td>'.$ht['shift2'].'</td>
						<td>'.$ht['shift3'].'</td>
					</tr>';
				}
			}
			$html .= '</table>
			
			<h3>TRAINING</h3>
		<table width="100%" cellspacing="6" cellpadding="5" align="center" border="1" valign="top">
			<tr bgcolor="#afd9af">
				<th><strong>Training</strong></th>
				<th width="150"><strong>Shift 1</strong></th>
				<th width="150"><strong>Shift 2</strong></th>
				<th width="150"><strong>Shift 3</strong></th>
			</tr>';
			
			if(!empty($training)) {
				foreach($training as $t) {
					$html .= '<tr>
						<td>'.$t['training_name'].'</td>
						<td>'.$t['shift1'].'</td>
						<td>'.$t['shift2'].'</td>
						<td>'.$t['shift3'].'</td>
					</tr>';
				}
			}
			$html .= '</table>
				  
			<h3>LAPORAN KEJADIAN</h3>';
			if(!empty($housekeeping['briefing1']))
				$html .= '<div>'.str_replace("<br />", "<br>",nl2br($housekeeping['briefing1'])).'</div><br>
			<hr>';
			if(!empty($housekeeping['briefing2']))
				$html .= '<div>'.str_replace("<br />", "<br>",nl2br($housekeeping['briefing2'])).'</div><br>
			<hr>';
			if(!empty($housekeeping['briefing3']))
				$html .= '<div>'.str_replace("<br />", "<br>",nl2br($housekeeping['briefing3'])).'</div><br>
			<hr>';
				
			$html .= '<h3>PROGRESS REPORT</h3>
			<h4>Progress Report Shift 1&2</h4>			
			<table width="100%" cellspacing="6" cellpadding="5" align="center" border="1" valign="top">
			<tr bgcolor="#afd9af">
				<th><strong>Area</strong></th>
				<th width="80"><strong>Before</strong></th>
				<th width="80"><strong>Progress</strong></th>
				<th width="80"><strong>After</strong></th>
				<th><strong>Status</strong></th>
			</tr>';
			
			if(!empty($progress_report_shift12)) {
				foreach($progress_report_shift12 as $pr) {
					$html .= '<tr>
						<td>'.$pr['area'].'</td>
						<td>';
					if(!empty($pr['img_before']) && @getimagesize($this->config->paths->html.'/images/progress_report/'.$pr['img_before'])) {
						$html .= '<img src="'.$this->config->general->url.'images/progress_report/'.$pr['img_before'].'" height="50px" />';
					}
					$html .= '</td>
						<td>';
					if(!empty($pr['img_progress']) && @getimagesize($this->config->paths->html.'/images/progress_report/'.$pr['img_progress'])) {
						$html .= '<img src="'.$this->config->general->url.'images/progress_report/'.$pr['img_progress'].'" height="50px" />';
					}
					$html .= '</td>
						<td>';
					if(!empty($pr['img_after']) && @getimagesize($this->config->paths->html.'/images/progress_report/'.$pr['img_after'])) {
						$html .= '<img src="'.$this->config->general->url.'images/progress_report/'.$pr['img_after'].'" height="50px" />';
					}
					$html .= '</td>
						<td>'.$pr['status'].'</td>
					</tr>';
				}
			}
			$html .= '</table>';
			
			$html .= '<h4>Progress Report Shift 3</h4>			
			<table width="100%" cellspacing="6" cellpadding="5" align="center" border="1" valign="top">
			<tr bgcolor="#afd9af">
				<th><strong>Area</strong></th>
				<th width="80"><strong>Before</strong></th>
				<th width="80"><strong>Progress</strong></th>
				<th width="80"><strong>After</strong></th>
				<th><strong>Status</strong></th>
			</tr>';
			
			if(!empty($progress_report_shift3)) {
				foreach($progress_report_shift3 as $pr) {
					$html .= '<tr>
						<td>'.$pr['area'].'</td>
						<td>';
					if(!empty($pr['img_before']) && @getimagesize($this->config->paths->html.'/images/progress_report/'.$pr['img_before'])) {
						$html .= '<img src="'.$this->config->general->url.'images/progress_report/'.$pr['img_before'].'" height="50px" />';
					}
					$html .= '</td>
						<td>';
					if(!empty($pr['img_progress']) && @getimagesize($this->config->paths->html.'/images/progress_report/'.$pr['img_progress'])) {
						$html .= '<img src="'.$this->config->general->url.'images/progress_report/'.$pr['img_progress'].'" height="50px" />';
					}
					$html .= '</td>
						<td>';
					if(!empty($pr['img_after']) && @getimagesize($this->config->paths->html.'/images/progress_report/'.$pr['img_after'])) {
						$html .= '<img src="'.$this->config->general->url.'images/progress_report/'.$pr['img_after'].'" height="50px" />';
					}
					$html .= '</td>
						<td>'.$pr['status'].'</td>
					</tr>';
				}
			}
			$html .= '</table>';
			
			$html .= '<h4>Pest Control dan Informasi Lainnya</h4>			
			<table width="100%" cellspacing="6" cellpadding="5" align="center" border="1" valign="top">
			<tr bgcolor="#afd9af">
				<th><strong>Area</strong></th>
				<th width="100"><strong>Progress</strong></th>
				<th><strong>Status</strong></th>
			</tr>';
			if(!empty($other_info)) {
				foreach($other_info as $oi) {
					$ext = explode(".",$oi['img_progress']);
					$html .= '<tr>
						<td>'.$oi['area'].'</td>
						<td>';
						if(!empty($oi['img_progress']) && in_array(strtolower($ext[count($ext)-1]), array("jpg", "jpeg")))
						{
							if(@getimagesize($this->config->paths->html.'/images/progress_report/'.$oi['img_progress'])) {
								$html .= '<img src="'.$this->config->general->url.'images/progress_report/'.$oi['img_progress'].'" height="50px" />';
							}
						}
						else
							$html .= '<a href="'.$this->config->paths->html.'/images/progress_report/'.$oi['img_progress'].'">View Image</a>';
					$html .= '</td>
						<td>'.$oi['status'].'</td>
					</tr>';
				}
			}
			$html .= '</table>';
			
			$html .= '<h4>Attachment</h4>			
			<table width="100%" cellspacing="6" cellpadding="5" align="center" border="1" valign="top">
			<tr bgcolor="#afd9af">
				<th><strong>Description</strong></th>
			</tr>';
			if(!empty($attachment)) {
				foreach($attachment as $att) {
					$html .= '<tr>
						<td><a href="'.$this->baseUrl.'/default/attachment/openattachment/c/2/f/'.$att['filename'].'">'.$att['description'].'</a></td>
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
			Zend_Loader::LoadClass('housekeepingClass', $this->modelDir);
			$housekeepingClass = new housekeepingClass();
			//$housekeeping = $housekeepingClass->getReportById($params['id']);

			$params['user_id'] = $this->ident['user_id'];
			$housekeepingClass->addReadHousekeepingReportLog($params);

			$filename = $this->config->paths->html.'/pdf_report/housekeeping/' . $this->site_id."_hk_".$params['id'].".pdf";
			if (!file_exists($filename)/* || date("Y-m-d", mktime(0, 0, 0, date("m"), date("d")-1, date("Y"))) < $housekeeping['created_date']*/) {		
				$this->exporthousekeepingtopdf($params['id']);
			}
			// Header content type 
			header('Content-type: application/pdf'); 				
			header('Content-Disposition: inline; filename="' . $filename . '"'); 				
			// Read the file 
			readfile($filename); 
			exit();	
		}		
	}

	public function downloadhkreportAction() {		
		$params = $this->_getAllParams();
		if(!empty($params['id']))
		{			
			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "Export Housekeeping Daily Report to PDF";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);	
			
			$this->exporthousekeepingtopdf($params['id'], "", 1);
		}		
	}

	public function savepdfAction() {		
		$params = $this->_getAllParams();
		if(!empty($params['id']))
		{
			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "Export Housekeeping Daily Report to PDF";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);	
			
			$this->exporthousekeepingtopdf($params['id']);
		}		
	}
	
	
	function getcommentsbyreportidAction()
	{
		$params = $this->_getAllParams();
		$this->view->ident = $this->ident;
		$category = $this->loadModel('category');
		$housekeepingCommentsTable = $this->loadModel('housekeepingcomments');
		$comments = $housekeepingCommentsTable->getCommentsByHousekeepingReportId($params['id'], 0, $this->site_id);
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
		$commentsTable = $this->loadModel('housekeepingcomments');
		$params['user_id'] = $this->ident['user_id'];
		$params['site_id'] = $this->site_id;
		if($_FILES["attachment"]["size"] > 0)
		{
			$ext = explode(".",$_FILES["attachment"]['name']);
			$filename = "hk_".date("YmdHis").".".$ext[count($ext)-1];
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
		
		Zend_Loader::LoadClass('housekeepingClass', $this->modelDir);
		$housekeepingClass = new housekeepingClass();
		$housekeeping = $housekeepingClass->getReportById($params['report_id']);
		
		$datetime = explode(" ",$housekeeping['created_date']);
		$date = explode("-",$datetime[0]);
		$r_date = mktime(0, 0, 0, intval($date[1]), intval($date[2]), intval($date[0]));
		$report_date = date("l, j F Y", $r_date);
		
		$categoryClass = $this->loadModel('category');
		$category = $categoryClass->getCategoryById('2');	

		
		$botToken = '716005387:AAFhUdDGlXjK5YFMYqwx_lCpqpp8760S_TE';
		/*if($this->site_id == 4)	$botToken = '716005387:AAFhUdDGlXjK5YFMYqwx_lCpqpp8760S_TE';
		else $botToken = '476346623:AAFlB9X5-bShAkdTK9ziNDrfZ0TCePXl2cY';*/
		$website="https://api.telegram.org/bot".$botToken;
		//$chatId=$category['telegram_channel_id'];  //Receiver Chat Id 
		$chatId=$category['site_'.$this->site_id];  //Receiver Chat Id 

		if(!empty($params['filename'])) $attachmenttext = '
attachment : '.$this->config->general->url."comments/".$params['filename'];

		$txt = '[NEW COMMENT] 
'.$this->ident['name']." : ".$params['comment'].$attachmenttext.'

[HOUSEKEEPING REPORT]
Report Date : '.$report_date.'
Report Link : '.$this->config->general->url."default/housekeeping/viewdetailreport/s/".$this->site_id."/id/".$params['report_id'];
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
		$logData['action'] = "Add Comment to Housekeeping Daily Report";
		$logData['data'] = json_encode($allParams);
		$logsTable->insertLogs($logData);	

		echo $allParams['filename'];
	}	
	
	function getupdatedcommentsAction()
	{
		$params = $this->_getAllParams();
		$params['pagesize'] = 10;
	
		Zend_Loader::LoadClass('housekeepingClass', $this->modelDir);
		$housekeepingClass = new housekeepingClass();
		
		$data= array();
		$housekeepingReports = $housekeepingClass->getReports($params);	
		$commentsTable = $this->loadModel('housekeepingcomments');
		$i=0;
		foreach($housekeepingReports as $s) {
			$data[$i]['housekeeping_report_id'] = $s['housekeeping_report_id'];
			$comments = $commentsTable->getCommentsByHousekeepingReportId($s['housekeeping_report_id'], '3', $this->site_id);
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
		
		Zend_Loader::LoadClass('housekeepingClass', $this->modelDir);
		$housekeepingClass = new housekeepingClass();
		
		$data= array();

		$commentCacheName = "hk_comments_".$this->site_id."_".$params["start"];
		$data = $this->cache->load($commentCacheName);
		$i=0;
		if(empty($data))
		{
			$housekeepingReports = $housekeepingClass->getReports($params);	
			$commentsTable = $this->loadModel('housekeepingcomments');
			foreach($housekeepingReports as $s) {
				$data[$i]['housekeeping_report_id'] = $s['housekeeping_report_id'];
				$comments = $commentsTable->getCommentsByHousekeepingReportId($s['housekeeping_report_id'], '3', $this->site_id);
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
	
	function addprogressreportAction()
	{
		$params = $this->_getAllParams();
		
		$progressreportTable = $this->loadModel('progressreport');
		
		$progress_report_id = $progressreportTable->addHousekeepingProgressReport($params);
		
		if(date("Y-m-d") > "2019-10-23")
			$datafolder = $this->config->paths->html."/images/progress_report_root/".date("Ym")."/";
		else
			$datafolder = $this->config->paths->html."/images/progress_report/";

		if(!is_dir($datafolder)) mkdir($datafolder, 0777, true);

		$magickPath = "/usr/bin/convert";
		if(!empty($_FILES["img_before"]))
		{
			$ext = explode(".",$_FILES["img_before"]['name']);
			$filename = $progress_report_id."_before_shift".$params['shift'].".".$ext[count($ext)-1];
			if(move_uploaded_file($_FILES["img_before"]["tmp_name"], $datafolder.$filename))
			{
				/*** convert to jpg ***/
				$newFilename = $progress_report_id."_before_shift".$params['shift'].".jpg";
				if($ext[count($ext)-1] != "jpg")  exec($magickPath . ' ' . $datafolder.$filename . ' ' . $datafolder.$newFilename);
				else $newFilename = $progress_report_id."_before_shift".$params['shift'].".".$ext[count($ext)-1];

				$progressreportTable->updateFileName($progress_report_id,'img_before', $newFilename);
				
				/*** create thumbnail image ***/
				exec($magickPath . ' ' . $datafolder.$newFilename . ' -resize 128x128 ' . $datafolder.$progress_report_id."_before_shift".$params['shift']."_thumb.jpg");
				/*** resize image if size greater than 500 Kb ***/
				if(filesize($datafolder.$newFilename) > 500000) exec($magickPath . ' ' . $datafolder.$newFilename . ' -resize 800x800\> ' . $datafolder.$newFilename);
			}
		}
		
		if(!empty($_FILES["img_progress"]))
		{
			$ext = explode(".",$_FILES["img_progress"]['name']);
			$filename = $progress_report_id."_progress_shift".$params['shift'].".".$ext[count($ext)-1];
			if(move_uploaded_file($_FILES["img_progress"]["tmp_name"], $datafolder.$filename))
			{
				/*** convert to jpg ***/
				$newFilename = $progress_report_id."_progress_shift".$params['shift'].".jpg";
				if($ext[count($ext)-1] != "jpg")  exec($magickPath . ' ' . $datafolder.$filename . ' ' . $datafolder.$newFilename);
				else $newFilename = $progress_report_id."_progress_shift".$params['shift'].".".$ext[count($ext)-1];

				$progressreportTable->updateFileName($progress_report_id,'img_progress', $newFilename);
				/*** create thumbnail image ***/
				exec($magickPath . ' ' . $datafolder.$newFilename . ' -resize 128x128 ' . $datafolder.$progress_report_id."_progress_shift".$params['shift']."_thumb.jpg");
				/*** resize image if size greater than 500 Kb ***/
				if(filesize($datafolder.$newFilename) > 500000) exec($magickPath . ' ' . $datafolder.$newFilename . ' -resize 800x800\> ' . $datafolder.$newFilename);
			}
		}
		
		if(!empty($_FILES["img_after"]))
		{
			$ext = explode(".",$_FILES["img_after"]['name']);
			$filename = $progress_report_id."_after_shift".$params['shift'].".".$ext[count($ext)-1];
			if(move_uploaded_file($_FILES["img_after"]["tmp_name"], $datafolder.$filename))
			{
				/*** convert to jpg ***/
				$newFilename = $progress_report_id."_after_shift".$params['shift'].".jpg";
				if($ext[count($ext)-1] != "jpg")  exec($magickPath . ' ' . $datafolder.$filename . ' ' . $datafolder.$newFilename);
				else $newFilename = $progress_report_id."_after_shift".$params['shift'].".".$ext[count($ext)-1];

				$progressreportTable->updateFileName($progress_report_id,'img_after', $newFilename);
				/*** create thumbnail image ***/
				exec($magickPath . ' ' . $datafolder.$newFilename . ' -resize 128x128 ' . $datafolder.$progress_report_id."_after_shift".$params['shift']."_thumb.jpg");
				/*** resize image if size greater than 500 Kb ***/
				if(filesize($datafolder.$newFilename) > 500000) exec($magickPath . ' ' . $datafolder.$newFilename . ' -resize 800x800\> ' . $datafolder.$newFilename);
			}	
		}

		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "Add Housekeeping Daily Report Progress Report";
		$logData['data'] = json_encode($params);
		$logsTable->insertLogs($logData);	
		
		$this->_response->setRedirect($this->baseUrl.'/default/housekeeping/page2/id/'.$params['housekeeping_report_id']);
		$this->_response->sendResponse();
		exit();
	
	}
	
	public function getprogressreportbyidAction() {
		$params = $this->_getAllParams();
		$progressreportTable = $this->loadModel('progressreport');
		
		if(!empty($params['id'])) 
		{
			$pr = $progressreportTable->getHousekeepingProgressReportById($params['id']);
	
			if(!empty($pr['upload_date']) && $pr['upload_date'] > "2019-10-23 23:59:59")
				$imageURL = "/images/progress_report_root/".date("Ym")."/";
			else
				$imageURL = "/images/progress_report/";

			if(!empty($pr['img_before'])) $pr['img_before'] = $imageURL.$pr['img_before'];
			if(!empty($pr['img_progress'])) $pr['img_progress'] = $imageURL.$pr['img_progress'];
			if(!empty($pr['img_after'])) $pr['img_after'] = $imageURL.$pr['img_after'];


			echo json_encode($pr);
		}
	}
	
	public function deleteprogressreportAction() {
		$params = $this->_getAllParams();
		$progressreportTable = $this->loadModel('progressreport');
		
		$progressreportTable->deleteHousekeepingProgressReportById($params['id']);

		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "Delete Housekeeping Daily Report Progress Report";
		$logData['data'] = json_encode($params);
		$logsTable->insertLogs($logData);	
		
		$this->_response->setRedirect($this->baseUrl.'/default/housekeeping/page2/id/'.$params['hk_report_id']);
		$this->_response->sendResponse();
		exit();
	}
	
	function addotherinfoAction()
	{
		$params = $this->_getAllParams();
		
		$progressreportTable = $this->loadModel('progressreport');
		
		$other_info_id = $progressreportTable->addHousekeepingOtherInfo($params);
		
		if(!empty($_FILES["img_progress"]))
		{
			$ext = explode(".",$_FILES["img_progress"]['name']);
			$filename = $other_info_id."_other_info.".$ext[count($ext)-1];
 
			if(date("Y-m-d") > "2019-10-23")
				$datafolder = $this->config->paths->html."/images/progress_report_root/".date("Ym")."/";
			else
				$datafolder = $this->config->paths->html."/images/progress_report/";

			if(!is_dir($datafolder)) mkdir($datafolder, 0777, true);

			if(move_uploaded_file($_FILES["img_progress"]["tmp_name"], $datafolder.$filename))
			{
				/*** convert to jpg ***/
				if(!in_array($ext[count($ext)-1], array("jpg", "jpeg", "JPG", "JPEG"))) 
				{
					$newFilename =  $other_info_id."_other_info.jpg";
					exec($magickPath . ' ' . $datafolder.$filename . ' ' . $datafolder.$newFilename);
				}
				else  $newFilename =  $other_info_id."_other_info.".strtolower($ext[count($ext)-1]);
				
				$progressreportTable->updateHKOtherInfoFileName($other_info_id,'img_progress', $newFilename);
				
				$thumbImg = str_replace("other_info.", "other_info_thumb.",$newFilename);
				$magickPath = "/usr/bin/convert";
				/*** create thumbnail image ***/
				exec($magickPath . ' ' . $datafolder.$newFilename . ' -resize 128x128 ' . $datafolder.$thumbImg);
				/*** resize image if size greater than 500 Kb ***/
				if(filesize($datafolder.$newFilename) > 500000) exec($magickPath . ' ' . $datafolder.$newFilename . ' -resize 800x800\> ' . $datafolder.$newFilename);
			}
		}

		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "Add Housekeeping Daily Report Other Info";
		$logData['data'] = json_encode($params);
		$logsTable->insertLogs($logData);	
		
		$this->_response->setRedirect($this->baseUrl.'/default/housekeeping/page2/id/'.$params['housekeeping_report_id']);
		$this->_response->sendResponse();
		exit();
	
	}
	
	public function getotherinfobyidAction() {
		$params = $this->_getAllParams();
		$progressreportTable = $this->loadModel('progressreport');
		
		if(!empty($params['id'])) 
		{
			$other_info_list = $progressreportTable->getHousekeepingOtherInfoById($params['id']);

			if($other_info_list['upload_date'] > "2019-10-23 23:59:59")
				$imageURL = "/images/progress_report_root/".date("Ym")."/";
			else
				$imageURL = "/images/progress_report/";

			if(!empty($other_info_list['img_progress']))	$other_info_list['img_progress'] = $imageURL.$other_info_list['img_progress'];

			echo json_encode($other_info_list);
		}
	}
	
	public function deleteotherinfoAction() {
		$params = $this->_getAllParams();
		$progressreportTable = $this->loadModel('progressreport');
		
		$progressreportTable->deleteHousekeepingOtherInfoById($params['id']);

		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "Delete Housekeeping Daily Report Other Info";
		$logData['data'] = json_encode($params);
		$logsTable->insertLogs($logData);	
		
		$this->_response->setRedirect($this->baseUrl.'/default/housekeeping/page2/id/'.$params['hk_report_id']);
		$this->_response->sendResponse();
		exit();
	}
	
	function addattachmentAction()
	{
		$params = $this->_getAllParams();
		
		$housekeepingClass = $this->loadModel('housekeeping');
		
		$attachment_id = $housekeepingClass->addAttachment($params);
		
		if(!empty($_FILES["attachment_file"]))
		{
			$ext = explode(".",$_FILES["attachment_file"]['name']);
			$filename = $attachment_id.".".$ext[count($ext)-1];
			$datafolder = $this->config->paths->html."/attachment/housekeeping/";
			if(move_uploaded_file($_FILES["attachment_file"]["tmp_name"], $datafolder.$filename))
			{
				$housekeepingClass->updateAttachment($attachment_id,'filename', $filename);
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
		$logData['action'] = "Add Housekeeping Daily Report Attachment";
		$logData['data'] = json_encode($params);
		$logsTable->insertLogs($logData);	
		
		$this->_response->setRedirect($this->baseUrl.'/default/housekeeping/page2/id/'.$params['report_id']);
		$this->_response->sendResponse();
		exit();
	
	}
	
	public function getattachmentbyidAction() {
		$params = $this->_getAllParams();
		$housekeepingClass = $this->loadModel('housekeeping');
		
		if(!empty($params['id'])) 
		{
			echo json_encode($housekeepingClass->getAttachmentById($params['id']));
		}
	}
	
	public function deleteattachmentbyidAction() {
		$params = $this->_getAllParams();
		$housekeepingClass = $this->loadModel('housekeeping');
		
		$housekeepingClass->deleteAttachmentById($params['id']);

		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "Delete Housekeeping Daily Report Attachment";
		$logData['data'] = json_encode($params);
		$logsTable->insertLogs($logData);	
		
		$this->_response->setRedirect($this->baseUrl.'/default/housekeeping/page2/id/'.$params['hk_report_id']);
		$this->_response->sendResponse();
		exit();
	}

	public function addmonthlyanalysisAction() {
		if($this->addHousekeepingMonthlyAnalysis)
		{
			$params = $this->_getAllParams();

			if(!empty($params['id'])) 
			{
				$this->view->monthly_analysis_id = $params['id'];		
				Zend_Loader::LoadClass('housekeepingClass', $this->modelDir);
				$housekeepingClass = new housekeepingClass();
				$monthly_analysis = $housekeepingClass->geMonthlyAnalysisById($params['id']);
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
				$modus = $modusClass->getModus('2');
				//$this->cache->save($modus, "modus_".$this->site_id."_2_".$ym, array("modus_".$this->site_id."_2_".$ym), 0);
			}

			//$totalModusPerMonth = $this->cache->load("total_modus_per_month_".$this->site_id."_1_".$ym);
			if(empty($totalModusPerMonth))
			{	
				$totalModusPerMonth =  array();
				for($b=1; $b<=$m; $b++) // get rekapitulasi utk bulan ini dan bulan2 sblmnya
				{
					$totalModus = $issueClass->getIssuesByModus($b, $y, '2');
					foreach($totalModus as $tm) {
						$totalModusPerMonth[$b][$tm['modus_id']] = $tm['total_modus'];
					}
				}
				//$this->cache->save($totalModusPerMonth, "total_modus_per_month_".$this->site_id."_1_".$ym, array("total_modus_per_month_".$this->site_id."_1_".$ym), 0);
			}
			else{
				$totalModus = $issueClass->getIssuesByModus($m, $y, '2');
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
					
					$analisa_hari = $issueClass->getMonthlyIssuesByDay($mo['kejadian_id'], $m, $y, '2');
					if(!empty($analisa_hari))
					{
						foreach($analisa_hari as $ah) {
							$rekap[$i]['analisa_hari'][$ah['day']]= $ah['total'];
						}
					}
					
					$rekap[$i]['analisa_jam'][0] = $issueClass->getMonthlyIssuesByTime($mo['kejadian_id'], $m, $y, '09:00:00', '12:00:00', '2');
					$rekap[$i]['analisa_jam'][1] = $issueClass->getMonthlyIssuesByTime($mo['kejadian_id'], $m, $y, '12:00:00', '16:00:00', '2');
					$rekap[$i]['analisa_jam'][2] = $issueClass->getMonthlyIssuesByTime($mo['kejadian_id'], $m, $y, '16:00:00', '19:00:00', '2');
					$rekap[$i]['analisa_jam'][3] = $issueClass->getMonthlyIssuesByTime($mo['kejadian_id'], $m, $y, '19:00:00', '23:00:00', '2');
					$rekap[$i]['analisa_jam'][4] = $issueClass->getMonthlyIssuesByTime($mo['kejadian_id'], $m, $y, '23:00:00', '09:00:00', '2');
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
				
				$uraian_kejadian = $issueClass->getIssuesByModusId($mo['modus_id'], $m, $y, '2');

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
			
			$this->view->urutan_hari_tertinggi = $issueClass->getTotalIssuesByDayDescending($m, $y, '2');
			$urutan_total_jam[0] = $issueClass->getTotalIssuesByTimePeriode($m, $y, '09:00:00', '12:00:00', '2');
			$urutan_total_jam[1] = $issueClass->getTotalIssuesByTimePeriode($m, $y, '12:00:00', '16:00:00', '2');
			$urutan_total_jam[2] = $issueClass->getTotalIssuesByTimePeriode($m, $y, '16:00:00', '19:00:00', '2');
			$urutan_total_jam[3] = $issueClass->getTotalIssuesByTimePeriode($m, $y, '19:00:00', '23:00:00', '2');
			$urutan_total_jam[4] = $issueClass->getTotalIssuesByTimePeriode($m, $y, '23:00:00', '09:00:00', '2');
			arsort($urutan_total_jam);

			$this->view->urutan_total_jam = $urutan_total_jam;
			
			$this->view->incidents = $issueClass->getHousekeepingIssueSummary($m, $y, $params['id']);

			$urutan_total_issue_tenant = $issueClass->getTotalIssuesByTenantPublik($m, $y, '0', '2');
			if(!empty($urutan_total_issue_tenant))
			{
				$urutan_total_all_issue_tenant = 0;
				foreach($urutan_total_issue_tenant as &$t)
				{
					$data = array();
					$data = $issueClass->getTotalIssuesByLocation($m, $y, '0', $t['location'], $t['floor_id'], '2');
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

			$urutan_total_issue_publik = $issueClass->getTotalIssuesByTenantPublik($m, $y, '1', '2');
			if(!empty($urutan_total_issue_publik))
			{
				$urutan_total_all_issue_publik = 0;
				foreach($urutan_total_issue_publik as &$p)
				{
					$data = array();
					$data = $issueClass->getTotalIssuesByLocation($m, $y, '1', $p['location'], $p['floor_id'], '2');
					
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
			$list_tangkapan = $issueClass->getPelakuTertangkapByCategory($y, '2');
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

			$pelaku_tertangkap_detail = $issueClass->getPelakuTertangkapDetail($m, $y, '2');
			foreach($pelaku_tertangkap_detail as &$pelaku)
			{
				$tgl = explode(" ", $pelaku['issue_date']);
				$pelaku['date'] = date("j M Y", strtotime($tgl[0]))." ".$tgl[1];
			}
			$this->view->pelaku_tertangkap_detail = $pelaku_tertangkap_detail;

			$listIssues = $issueClass->getMonthlyAnalysisIssues($m, $y, '2');
			foreach($listIssues as &$issue)
			{
				$tgl = explode(" ", $issue['issue_date']);
				$issue['date'] = date("j M Y", strtotime($tgl[0]))." ".$tgl[1];
			}
			$this->view->listIssues = $listIssues;

			Zend_Loader::LoadClass('incidentClass', $this->modelDir);
			$incidentClass = new incidentClass();	
			$this->view->listKejadian = $incidentClass->getIncidentByCategoryId('2');


			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "Add Housekeeping Monthly Analysis";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);	

			$this->renderTemplate('form_housekeeping_monthly_analysis.tpl'); 
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
			Zend_Loader::LoadClass('housekeepingClass', $this->modelDir);
			$housekeepingClass = new housekeepingClass();
			$params['monthly_analysis_id'] = $housekeepingClass->saveMonthlyAnalysis($params);
		}
		
		Zend_Loader::LoadClass('issueClass', $this->modelDir);
		$issueClass = new issueClass();
		$this->view->incidents = $issueClass->getHousekeepingIssueSummary(date("m"), date("Y"));

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
			$monthlyanalysissummaryClass->saveMonthlyAnalysis($data, '2');
			$i++;
		}

		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "Save Housekeeping Monthly Analysis";
		$logData['data'] = json_encode($params);
		$logsTable->insertLogs($logData);	

		$this->_response->setRedirect($this->baseUrl.'/default/housekeeping/viewmonthlyanalysis');
		$this->_response->sendResponse();
		exit();
	}

	public function viewmonthlyanalysisAction() {
		$params = $this->_getAllParams();
		$this->view->ident = $this->ident;
		if(empty($params['start'])) $params['start'] = '0';
		$params['pagesize'] = 10;
		$this->view->start = $params['start'];
		Zend_Loader::LoadClass('housekeepingClass', $this->modelDir);
		$housekeepingClass = new housekeepingClass();
		$monthlyAnalysis = $housekeepingClass->getMonthlyAnalysis($params);
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

		$totalMonthlyAnalysis = $housekeepingClass->getTotalMonthlyAnalysis();
		if($totalMonthlyAnalysis > 10)
		{
			if($params['start'] >= 10)
			{
				$this->view->firstPageUrl = "/default/housekeeping/viewmonthlyanalysis";
				$this->view->prevUrl = "/default/housekeeping/viewmonthlyanalysis/start/".($params['start']-$params['pagesize']);
			}
			if($params['start'] < (floor(($totalMonthlyAnalysis-1)/10)*10))
			{
				$this->view->nextUrl = "/default/housekeeping/viewmonthlyanalysis/start/".($params['start']+$params['pagesize']);
				$this->view->lastPageUrl = "/default/housekeeping/viewmonthlyanalysis/start/".(floor(($totalMonthlyAnalysis-1)/10)*10);
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
		$logData['action'] = "View Housekeeping Monthly Analysis List";
		$logData['data'] = "";
		$logsTable->insertLogs($logData);	

		$this->renderTemplate('view_housekeeping_monthly_analysis.tpl'); 
	}

	public function viewdetailmonthlyanalysisAction() {
		$params = $this->_getAllParams();

		if(!empty($params['id'])) 
		{
			$this->view->monthly_analysis_id = $params['id'];		
			Zend_Loader::LoadClass('housekeepingClass', $this->modelDir);
			$housekeepingClass = new housekeepingClass();
			$monthly_analysis = $housekeepingClass->geMonthlyAnalysisById($params['id']);
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
				$modus = $modusClass->getModus('2');
			}

			//$totalModusPerMonth = $this->cache->load("total_modus_per_month_".$this->site_id."_1_".$ym);
			if(empty($totalModusPerMonth))
			{	
				$totalModusPerMonth =  array();
				for($b=1; $b<=$m; $b++) // get rekapitulasi utk bulan ini dan bulan2 sblmnya
				{
					$totalModus = $issueClass->getIssuesByModus($b, $y, '2');
					foreach($totalModus as $tm) {
						$totalModusPerMonth[$b][$tm['modus_id']] = $tm['total_modus'];
					}
				}
			}
			else{
				$totalModus = $issueClass->getIssuesByModus($m, $y, '2');
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
						$analisa_hari = $issueClass->getMonthlyIssuesByDay($mo['kejadian_id'], $m, $y, '2');
						
						if(!empty($analisa_hari))
						{
							foreach($analisa_hari as $ah) {
								$rekap[$i]['analisa_hari'][$ah['day']]= $ah['total'];
							}
						}
						$rekap[$i]['analisa_jam'][0] = $issueClass->getMonthlyIssuesByTime($mo['kejadian_id'], $m, $y, '09:00:00', '12:00:00', '2');
						$rekap[$i]['analisa_jam'][1] = $issueClass->getMonthlyIssuesByTime($mo['kejadian_id'], $m, $y, '12:00:00', '16:00:00', '2');
						$rekap[$i]['analisa_jam'][2] = $issueClass->getMonthlyIssuesByTime($mo['kejadian_id'], $m, $y, '16:00:00', '19:00:00', '2');
						$rekap[$i]['analisa_jam'][3] = $issueClass->getMonthlyIssuesByTime($mo['kejadian_id'], $m, $y, '19:00:00', '23:00:00', '2');
						$rekap[$i]['analisa_jam'][4] = $issueClass->getMonthlyIssuesByTime($mo['kejadian_id'], $m, $y, '23:00:00', '09:00:00', '2');
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
					$uraian_kejadian = $issueClass->getIssuesByModusId($mo['modus_id'], $m, $y, '2');
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
				$this->view->urutan_hari_tertinggi = $issueClass->getTotalIssuesByDayDescending($m, $y, '2');
				$urutan_total_jam[0] = $issueClass->getTotalIssuesByTimePeriode($m, $y, '09:00:00', '12:00:00', '2');
				$urutan_total_jam[1] = $issueClass->getTotalIssuesByTimePeriode($m, $y, '12:00:00', '16:00:00', '2');
				$urutan_total_jam[2] = $issueClass->getTotalIssuesByTimePeriode($m, $y, '16:00:00', '19:00:00', '2');
				$urutan_total_jam[3] = $issueClass->getTotalIssuesByTimePeriode($m, $y, '19:00:00', '23:00:00', '2');
				$urutan_total_jam[4] = $issueClass->getTotalIssuesByTimePeriode($m, $y, '23:00:00', '09:00:00', '2');
				arsort($urutan_total_jam);
				$this->view->urutan_total_jam = $urutan_total_jam;

				$this->view->incidents = $issueClass->getHousekeepingIssueSummary($m, $y, $params['id']);
				$urutan_total_issue_tenant = $issueClass->getTotalIssuesByTenantPublik($m, $y, '0', '2');
				if(!empty($urutan_total_issue_tenant))
				{
					$urutan_total_all_issue_tenant = 0;
					foreach($urutan_total_issue_tenant as &$t)
					{
						$data = array();
						$data = $issueClass->getTotalIssuesByLocation($m, $y, '0', $t['location'], $t['floor_id'], '2');
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

				$urutan_total_issue_publik = $issueClass->getTotalIssuesByTenantPublik($m, $y, '1', '2');
				if(!empty($urutan_total_issue_publik))
				{
					$urutan_total_all_issue_publik = 0;
					foreach($urutan_total_issue_publik as &$p)
					{
						$data = array();
						$data = $issueClass->getTotalIssuesByLocation($m, $y, '1', $p['location'], $p['floor_id'], '2');
						
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

			$total_all_tangkapan = 0;
			$total_tangkapan_monthly = array();
			$list_tangkapan = $issueClass->getPelakuTertangkapByCategory($y, '2');
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

			$pelaku_tertangkap_detail = $issueClass->getPelakuTertangkapDetail($m, $y, '2');
			foreach($pelaku_tertangkap_detail as &$pelaku)
			{
				$tgl = explode(" ", $pelaku['issue_date']);
				$pelaku['date'] = date("j M Y", strtotime($tgl[0]))." ".$tgl[1];
			}
			$this->view->pelaku_tertangkap_detail = $pelaku_tertangkap_detail;

			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "View Housekeeping Monthly Analysis Detail";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);	

			//echo "<pre>"; print_r($pelaku_tertangkap_detail); exit();
			$this->renderTemplate('housekeeping_monthly_analysis_detail.tpl'); 
		}
	}

	public function downloadhousekeepingmonthlyanalysisAction() {		
		$params = $this->_getAllParams();

		if(!empty($params['id']))
		{			
			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "Export Housekeeping Monthly Analysis Report to PDF";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);

			$monthly_analysis_id = $params['id'];		
			Zend_Loader::LoadClass('housekeepingClass', $this->modelDir);
			$housekeepingClass = new housekeepingClass();
			$monthly_analysis = $housekeepingClass->geMonthlyAnalysisById($params['id']);
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
				$modus = $modusClass->getModus('2');
			}

			//$totalModusPerMonth = $this->cache->load("total_modus_per_month_".$this->site_id."_1_".$ym);
			if(empty($totalModusPerMonth))
			{	
				$totalModusPerMonth =  array();
				for($b=1; $b<=$m; $b++) // get rekapitulasi utk bulan ini dan bulan2 sblmnya
				{
					$totalModus = $issueClass->getIssuesByModus($b, $y, '2');
					foreach($totalModus as $tm) {
						$totalModusPerMonth[$b][$tm['modus_id']] = $tm['total_modus'];
					}
				}
			}
			else{
				$totalModus = $issueClass->getIssuesByModus($m, $y, '2');
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
						$analisa_hari = $issueClass->getMonthlyIssuesByDay($mo['kejadian_id'], $m, $y, '2');
						
						if(!empty($analisa_hari))
						{
							foreach($analisa_hari as $ah) {
								$rekap[$i]['analisa_hari'][$ah['day']]= $ah['total'];
							}
						}
						$rekap[$i]['analisa_jam'][0] = $issueClass->getMonthlyIssuesByTime($mo['kejadian_id'], $m, $y, '09:00:00', '12:00:00', '2');
						$rekap[$i]['analisa_jam'][1] = $issueClass->getMonthlyIssuesByTime($mo['kejadian_id'], $m, $y, '12:00:00', '16:00:00', '2');
						$rekap[$i]['analisa_jam'][2] = $issueClass->getMonthlyIssuesByTime($mo['kejadian_id'], $m, $y, '16:00:00', '19:00:00', '2');
						$rekap[$i]['analisa_jam'][3] = $issueClass->getMonthlyIssuesByTime($mo['kejadian_id'], $m, $y, '19:00:00', '23:00:00', '2');
						$rekap[$i]['analisa_jam'][4] = $issueClass->getMonthlyIssuesByTime($mo['kejadian_id'], $m, $y, '23:00:00', '09:00:00', '2');
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
					$uraian_kejadian = $issueClass->getIssuesByModusId($mo['modus_id'], $m, $y, '2');
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
				
				$urutan_hari_tertinggi = $issueClass->getTotalIssuesByDayDescending($m, $y, '2');
				$urutan_total_jam[0] = $issueClass->getTotalIssuesByTimePeriode($m, $y, '09:00:00', '12:00:00', '2');
				$urutan_total_jam[1] = $issueClass->getTotalIssuesByTimePeriode($m, $y, '12:00:00', '16:00:00', '2');
				$urutan_total_jam[2] = $issueClass->getTotalIssuesByTimePeriode($m, $y, '16:00:00', '19:00:00', '2');
				$urutan_total_jam[3] = $issueClass->getTotalIssuesByTimePeriode($m, $y, '19:00:00', '23:00:00', '2');
				$urutan_total_jam[4] = $issueClass->getTotalIssuesByTimePeriode($m, $y, '23:00:00', '09:00:00', '2');
				arsort($urutan_total_jam);

				$incidents = $issueClass->getHousekeepingIssueSummary($m, $y, $params['id']);
				
				/*$urutan_total_issue_tenant = $issueClass->getTotalIssuesByTenantPublik($m, $y, '0', '2');
				if(!empty($urutan_total_issue_tenant))
				{
					$urutan_total_all_issue_tenant = 0;
					foreach($urutan_total_issue_tenant as &$tenant)
					{
						$data = array();
						$data = $issueClass->getTotalIssuesByLocation($m, $y, '0', $tenant['location'], $tenant['floor_id'], '2');
						if(!empty($data))
						{
							foreach($data as $dt)
							{
									$tenant[$dt['kejadian_id']] = $dt['total_kejadian'];
							}
						}
						$urutan_total_all_issue_tenant += $tenant['total'];
					}
				}

				$urutan_total_issue_publik = $issueClass->getTotalIssuesByTenantPublik($m, $y, '1', '2');
				if(!empty($urutan_total_issue_publik))
				{
					$urutan_total_all_issue_publik = 0;
					foreach($urutan_total_issue_publik as &$p)
					{
						$data = array();
						$data = $issueClass->getTotalIssuesByLocation($m, $y, '1', $p['location'], $p['floor_id'], '2');
						
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
				}*/
			} 

			$total_all_tangkapan = 0;
			$total_tangkapan_monthly = array();
			$list_tangkapan = $issueClass->getPelakuTertangkapByCategory($y, '2');
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

			$pelaku_tertangkap_detail = $issueClass->getPelakuTertangkapDetail($m, $y, '2');
			foreach($pelaku_tertangkap_detail as &$pelaku)
			{
				$tgl = explode(" ", $pelaku['issue_date']);
				$pelaku['date'] = date("j M Y", strtotime($tgl[0]))." ".$tgl[1];
			}
			
			require_once('fpdf/mc_table.php');
			$pdf=new PDF_MC_Table();
			$pdf->AddPage();
			
			$pdf->SetTitle($this->ident['initial']." - HOUSEKEEPING MONTHLY ANALYTICS - ".$monthYear);
			$pdf->SetFont('Arial','B',12);
			$pdf->Write(5, $this->ident['initial']." - HOUSEKEEPING MONTHLY ANALYTICS - ".$monthYear);
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
					$dt1[$h] = $r['kejadian_name'];
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
					$dt1[$f] = $r['kejadian_name'];
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


				/*$pdf->SetFont('Arial','B',8);
				$pdf->Write(5,'Area Tenant yang rawan kejadian');
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
				$g = 2;
				foreach($rekap as $r) {	
					$w[$g] = $rwidth;
					$dt1[$g] = $r['kejadian_name'];
					$g++;
				} 
				$dt1[$g] = 'Total';
				$w[$g] = 13;
				$pdf->SetFont('Arial','B',8);
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
				$e = 2;
				foreach($rekap as $r) {	
					$w[$e] = $rwidth;
					$dt1[$e] = $r['kejadian_name'];
					$e++;
				} 
				$dt1[$e] = 'Total';
				$w[$e] = 13;
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
				$pdf->Ln(10);*/
			}

			if(!empty($list_tangkapan)) {
				$pdf->SetFont('Arial','B',9);
				$pdf->SetTextColor(0,0,0);
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

				$pdf->Ln(10);
			} 
			
			if(!empty($pelaku_tertangkap_detail)) {
				$pdf->SetFont('Arial','B',7);
				$pdf->SetFillColor(158,130,75);
				$pdf->SetTextColor(255,255,255);
				$pdf->Cell(30,6,'Photo',1,0,'C',true);
				$pdf->Cell(133,6,'Description',1,0,'C',true);
				$pdf->Cell(30,6,'Date',1,0,'C',true);
				$pdf->Ln();
				$pdf->SetFont("");
				$pdf->SetTextColor(0,0,0);	
				$pdf->SetWidths(array(30, 133, 30));
				foreach($pelaku_tertangkap_detail as $pelaku2) {
					if($pelaku2['issue_date'] > "2019-10-23 14:30:00")
					{
						$issuedate = explode("-",$pelaku2['issue_date']);
						$imageURL = str_replace("https","http",$this->config->general->url)."storage/images/issues/".$issuedate[0]."/";
						$imageDir = $this->config->paths->storage.'/images/issues/'.$issuedate[0]."/";
					}
					else
					{
						$imageURL = str_replace("https","http",$this->config->general->url)."storage/images/issues/";
						$imageDir = $this->config->paths->storage.'/images/issues/';
					}

					$x1 = $pdf->GetY();
					$pdf->Row(array("", $pelaku2['description']."\n\n\n\n\n",$pelaku2['date']));
					$x2= $pdf->GetY();
					if($x2<$x1) $y = 12;
					else $y = $pdf->GetY()-($x2-$x1-2);

					if (file_exists($imageDir.str_replace(".","_thumb.",$pelaku2['picture']))) {
						$pelaku2['picture'] = str_replace(".","_thumb.",$pelaku2['picture']);
					}
					list($width, $height) = getimagesize($imageDir.$pelaku2['picture']);
					if($width > $height)
					{
						$w = 20;
						$h = 0;
					}
					else {
						$w = 0;
						$h = 20;
					}
					$pdf->Image($imageURL.$pelaku2['picture'],15,$y, $w,$h);
				}
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
					$pdf->SetFont('Arial','B',8);
					$pdf->SetFillColor(158,130,75);
					$pdf->SetTextColor(255,255,255);
					$pdf->Cell(190,6,$incident['kejadian'].' ('.$incident['total_kejadian'].' Kaizen)',1,0,'C',true);
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

			$pdf->Output('I', $this->ident['initial']."_safety_monthly_analysis_report_".str_replace(" ","",$monthYear).".pdf", false);
		}		
	}
	
}

?>
