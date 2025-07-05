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

		
		$botToken = '476346623:AAFlB9X5-bShAkdTK9ziNDrfZ0TCePXl2cY';
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
	
}

?>