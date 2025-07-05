<?php
require_once('actionControllerBase.php');
class WorkorderController extends actionControllerBase
{
	function addworkorderAction() {
		if($this->addWorkOrder)
		{
			$params = $this->_getAllParams();
			
			$issue_id = $params['issue_id'];
			
			Zend_Loader::LoadClass('workorderClass', $this->modelDir);
			$workorderClass = new workorderClass();
			
			$params["expected_work_time2"] = 1;
			
			$params['worker'] = ",".implode(",",$params['worker_id']).",";
			$params["wo_starthour"] = "00";
			$params["wo_startmin"] = "00";
			$params["wo_endhour"] = "23";
			$params["wo_endmin"] = "59";
			$wo_id = $workorderClass->addworkorder($params);
			
			Zend_Loader::LoadClass('userClass', $this->modelDir);
			$userClass = new userClass();
			$users = $userClass->getUsersByIds(implode(",",$params['worker_id']));
			$workers = "";
			if(!empty($users)) {
				foreach($users as $u) {
					$workers .= $u['name'].", ";
				}
				$workers = substr($workers, 0, -2);
			}
			
			Zend_Loader::LoadClass('issueClass', $this->modelDir);
			$issueClass = new issueClass();
			$issue = $issueClass->getIssueById($params['issue_id']);
			$categoryTable = $this->loadModel('category');
			$category = $categoryTable->getCategoryById($issue['category_id']);			
			$issuetypeTable = $this->loadModel('issuetype');
			$type = $issuetypeTable->getIssueTypeById($issue['issue_type_id']);

			$kejadian = "";
			if(!empty($issue['kejadian_id'])) {
				$incidentTable = $this->loadModel('incident');
				$selIncident = $incidentTable->getIncidentById($issue['kejadian_id'],$issue['category_id']);	
				$kejadian = " - ".$selIncident['kejadian'];
			}
			$modus = "";
			if(!empty($issue['modus_id'])) {
				$modusTable = $this->loadModel('modus');
				$selModus = $modusTable->getModusById($issue['modus_id'],$issue['category_id']);
				$modus = " - ".$selModus['modus'];
			}
			$manpowername = "";
			if(!empty($issue['manpower_id'])) {
				Zend_Loader::LoadClass('manpowerClass', $this->modelDir);
				$manpowerClass = new manpowerClass();
				$manpower = $manpowerClass->getManPowerById($issue['manpower_id']);
				$manpowername = " - ".$manpower['name'];
			}
			$floor = "";
			$tenant_public = "";
			
			if(!empty($issue['floor_id'])) {
				$floorTable = $this->loadModel('floor');
				$selFloor = $floorTable->getFloorById($issue['floor_id'],$issue['category_id']);
				$floor = $selFloor['floor']." - ";
				$areaTable = $this->loadModel('area');
				$selArea = $areaTable->getAreaById($issue['area_id']);
				$area = $selArea['area_name']." - ";
			}

						
			//$pic_url = $this->config->general->url."index/issueimage/id/".$id;
			if(date("Y-m-d H:i:s") > "2019-10-23 15:35:00")
				$pic_url = $this->config->general->url."images/issues/".date("Y")."/".$new_file_large;
			else
				$pic_url = $this->config->general->url."images/issues/".$new_file_large;

			$botToken = '716005387:AAFhUdDGlXjK5YFMYqwx_lCpqpp8760S_TE';
			$website="https://api.telegram.org/bot".$botToken;
			$chatId=$category['site_'.$this->site_id];  //Receiver Chat Id 
			
			
			$txt = '&#128227; <b><u>WORK ORDER ASSIGNED TO '.strtoupper($workers).'</u></b>
&#128172; '.$params['assigned_comment'].'

<b><u>KAIZEN DETAIL</u></b>
&#128205; '.$area.$floor.$issue['location'].' 
&#9888; '.$type['issue_type'].$kejadian.$modus.$manpowername. '
&#8505; '.$issue['description'].'
&#128279; <a href="'.$this->config->general->url."default/issue/listissues/s/".$this->site_id."/c/".$issue['category_id']."/id/".$params['issue_id'].'">Open Kaizen</a>';

			$allParams = $params;

			$params=array(
				'chat_id'=>$chatId,
				'photo'=>$this->config->general->url."images/issues/".substr($issue['issue_date'],0,4)."/".str_replace(".","_large.",$issue['picture']),
				'caption'=>$txt,
				'parse_mode'=>"HTML"
			);
			$ch = curl_init($website . '/sendPhoto');
			curl_setopt($ch, CURLOPT_HEADER, false);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, ($params));
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch,CURLOPT_CONNECTTIMEOUT, 3);
			curl_setopt($ch,CURLOPT_TIMEOUT, 30);
			$result = curl_exec($ch);
			curl_close($ch);

			$allParams['telegram'] = $params;
			
			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "Add Work Order";
			$logData['data'] = json_encode($allParams);
			$logsTable->insertLogs($logData);
			
			echo $issue_id;
		}
		else {
			$this->_response->setRedirect($this->baseUrl);
			$this->_response->sendResponse();
			exit();
		}
	}

	function viewAction() {
		if($this->viewWorkOrder)
		{
			$params = $this->_getAllParams();
			
			$this->view->wo_id = $params['id'];
			Zend_Loader::LoadClass('workorderClass', $this->modelDir);
			$workorderClass = new workorderClass();
			
			if(empty($params['m'])) $m = date("n");
			else $m = $params['m'];
			if(empty($params['y'])) $y = date("Y");
			else $y = $params['y'];
			
			$key = array_search('31', $this->ident['role_ids']);
			if(is_numeric($key)) $user_id = $this->ident['user_id'];
			else $user_id = 0;
			
			Zend_Loader::LoadClass('issueClass', $this->modelDir);
			$issueClass = new issueClass();
			
			$schedules = $workorderClass->getCurMonthSchedules($m, $y, $user_id);
			if(!empty($schedules))
			{
				foreach($schedules as &$s)
				{
					$issue = $issueClass->getIssueById($s['issue_id']);
					
					$s['description'] = $issue['description'];
					if($s['end_scheduled_date'] < date("Y-m-d H:i:s") && (empty($s['finish_date']) || $s['finish_date'] == "0000-00-00 00:00:00")) {
						$s['color'] = "red";
						$s['description'] .= " [Task exceeded]";
					}
					else if(empty($s['executed_date']) || $s['executed_date'] == "0000-00-00 00:00:00")
					{
						$s['color'] = "#a1a2a6";
						$s['description'] .= " [Not started yet]";
					}
					elseif(empty($s['finish_date']) || $s['finish_date'] == "0000-00-00 00:00:00")
					{
						$s['color'] = "green";
						$s['description'] .= " [On Progress]";
					}
					elseif(empty($s['approved_date']) || $s['approved_date'] == "0000-00-00 00:00:00")	
					{
						$s['color'] = "orange";
						$s['description'] .= " [Waiting for the approval]";
					}
					else 
					{
						$s['color'] = "#9e824b";
						$s['description'] .= " [Done]";
					}
					
				}
			}
			
			$this->view->schedules = $schedules;
			
			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "View Work Order";
			$logData['data'] = json_encode($allParams);
			$logsTable->insertLogs($logData);
			
			$this->renderTemplate('work_order.tpl');	
		
		}
		else {
			$this->_response->setRedirect($this->baseUrl);
			$this->_response->sendResponse();
			exit();
		}
	}

	function getschedulebymonthyearAction() {
		if($this->viewWorkOrder)
		{
			$params = $this->_getAllParams();
			Zend_Loader::LoadClass('workorderClass', $this->modelDir);
			$workorderClass = new workorderClass();
			
			if(empty($params['m'])) $m = date("n");
			else $m = $params['m'];
			if(empty($params['y'])) $y = date("Y");
			else $y = $params['y'];
			
			$key = array_search('31', $this->ident['role_ids']);
			if(is_numeric($key)) $user_id = $this->ident['user_id'];
			else $user_id = 0;
			
			Zend_Loader::LoadClass('issueClass', $this->modelDir);
			$issueClass = new issueClass();
			
			$schedules = $workorderClass->getCurMonthSchedules($m, $y, $user_id);
			if(!empty($schedules))
			{
				$i = 0;
				$sched = array();
				foreach($schedules as &$s)
				{
					$issue = $issueClass->getIssueById($s['issue_id']);
					
					$sched[$i]['title'] = $issue['description'];
					$sched[$i]['start'] = str_replace(" ", "T", $s['start_scheduled_date']);
					
					$sched[$i]['end'] = str_replace(" ", "T", $s['end_scheduled_date']);
					$sched[$i]['id'] = $s['wo_id'];
					
					if($s['end_scheduled_date'] < date("Y-m-d H:i:s") && (empty($s['finish_date']) || $s['finish_date'] == "0000-00-00 00:00:00")) {
						$sched[$i]['color'] = "red";
						$sched[$i]['title'] .= " [Task exceeded]";
					}	
					else if(empty($s['executed_date']) || $s['executed_date'] == "0000-00-00 00:00:00")
					{
						$sched[$i]['color'] = "#9e824b";
						$sched[$i]['title'] .= " [Not started yet]";
					}
					elseif(empty($s['finish_date']) || $s['finish_date'] == "0000-00-00 00:00:00")
					{
						$sched[$i]['color'] = "green";
						$sched[$i]['title'] .= " [On Progress]";
					}
					elseif(empty($s['approved_date']) || $s['approved_date'] == "0000-00-00 00:00:00")	
					{
						$sched[$i]['color'] = "orange";
						$sched[$i]['title'] .= " [Waiting for the approval]";
					}
					else 
					{
						$sched[$i]['color'] = "#a1a2a6";
						$sched[$i]['title'] .= " [Done]";
					}
					$i++;
				}
			}
			
			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "View Work Order";
			$logData['data'] = json_encode($allParams);
			$logsTable->insertLogs($logData);
			
			echo json_encode($sched);
		}
		else {
			$this->_response->setRedirect($this->baseUrl);
			$this->_response->sendResponse();
			exit();
		}
	}
	
	function getschedulebyidAction() {
		if($this->viewWorkOrder)
		{
			$params = $this->_getAllParams();
			Zend_Loader::LoadClass('workorderClass', $this->modelDir);
			$workorderClass = new workorderClass();
			
			$wo = $workorderClass->getWOById($params['id']);
			
			if($wo['expected_work_time2'] == 1) $expected_work_time = "Hours";
			else $expected_work_time = "Days";
			
			Zend_Loader::LoadClass('userClass', $this->modelDir);
			$userClass = new userClass();
			$users = $userClass->getUsersByIds(substr($wo['worker'], 1, -1));
			$workers = "";
			if(!empty($users)) {
				foreach($users as $u) {
					$workers .= $u['name'].", ";
				}
				$workers = substr($workers, 0, -2);
			}
			
			$start_scheduled_date = explode(" ",$wo['start_scheduled_date']);
			$start = date("j M Y", strtotime($start_scheduled_date[0]))/*." ".$start_scheduled_date[1]*/;
			$end_scheduled_date = explode(" ",$wo['end_scheduled_date']);
			$end = date("j M Y", strtotime($end_scheduled_date[0]))/*." ".$end_scheduled_date[1]*/;
			
			if(empty($wo['executed_date']) || $wo['executed_date'] == "0000-00-00 00:00:00")
			{
				$key = array_search('1', $this->ident['role_ids']);
				
				if((is_numeric(strpos($wo['worker'], ",".$this->ident['user_id'].",")) || is_numeric($key)) && $this->showStartWO)
					$data['show_start_btn'] = 1;
				$status = "Not started yet";
			}
			else if(empty($wo['finish_date']) || $wo['finish_date'] == "0000-00-00 00:00:00")
			{
				$data['status'] = 1;
				$key = array_search('1', $this->ident['role_ids']);
				
				if((is_numeric(strpos($wo['worker'], ",".$this->ident['user_id'].",")) || is_numeric($key)) && $this->showProgressWO)
					$data['show_progress_btn'] = 1;
				$status = "On Progress";
			}
			else if(empty($wo['approved_date']) || $wo['approved_date'] == "0000-00-00 00:00:00")
			{
				/*$key = array_search('1', $this->ident['role_ids']);
				$key2 = array_search('21', $this->ident['role_ids']);*/
				if(/*(is_numeric($key) || is_numeric($key2)) && */$this->showApprovedWO)
					$data['show_approve_btn'] = 1;
				$status = "Waiting for approval";
			}
			
			$progress="";
			$attachment = $workorderClass->getAttachmentByWoId($params['id']);
			if(!empty($attachment))
			{
				$progress .= "<tr>
					<td>Description</td>
					<td>:</td>
					<td>";
				foreach($attachment as $a)
				{
					$progress .= '<div id="progressatt'.$a['attachment_id'].'" class="woprogressatt"><a href="'.$this->baseUrl.'/workorder/'.substr($a['uploaded_date'],0,4).'/'.$a['filename'].'" target="_blank"><i class="fa fa-paperclip"></i> '.$a['description'].'</a> ';
					if($data['show_progress_btn'] == 1) $progress .= '<a class="action-btn delete-wo-att" data-id="'.$a['attachment_id'].'" data-filename="'.$attachment['filename'].'"><i class="fa fa-trash" ></i></a>';
					$progress .= '</div>';
				}
				$progress .= "<td>
				</tr>";
			}
			
			$comments = $workorderClass->getCommentsByWoId($params['id']);
			$cmt = "";
			if(!empty($comments))
			{
				foreach($comments as $comment)
				{
					if($comment['status'] == 1) $stat = "Approved";
					else $stat = "Rejected";
					
					$comment_date = explode(" ",$comment['comment_date']);
					$commentdate = date("j M Y", strtotime($comment_date[0]))." ".$comment_date[1];
			
					$cmt .= '<div class="comment-box"><strong>'.$stat.' on '.$commentdate.'</strong><br/>'.$comment['comment'].'</div>';
				}
			}
			
			$data['wo_id'] = $wo['wo_id'];
			$data['issue_id'] = $wo['issue_id'];
			$data['info'] = '<table>
				<tr>
					<td>Kaizen ID</td>
					<td>:</td>
					<td>'.$wo['issue_id'].'<td>
				</tr>
				<tr>
					<td>Description</td>
					<td>:</td>
					<td>'.$wo['description'].'<td>
				</tr>
				<tr>
					<td>Location</td>
					<td>:</td>
					<td>'.$wo['location'].'<td>
				</tr>
				<tr>
					<td>Scheduled Date</td>
					<td>:</td>
					<td>'.$start . " - " .$end.'<td>
				</tr>
				<tr>
					<td>Expected Work Time</td>
					<td>:</td>
					<td>'.$wo['expected_work_time'].' '.$expected_work_time.'<td>
				</tr>
				<tr>
					<td>Worker</td>
					<td>:</td>
					<td>'.$workers.'<td>
				</tr>
				<tr>
					<td>Remark</td>
					<td>:</td>
					<td>'.$wo['assigned_remark'].'<td>
				</tr>'.$progress.'
				<tr>
					<td>Status</td>
					<td>:</td>
					<td>'.$status.'<td>
				</tr>
				</table>'.$cmt;
			
			
			
			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "View Work Order Detail";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);
			
			echo json_encode($data);
		}
		else {
			$this->_response->setRedirect($this->baseUrl);
			$this->_response->sendResponse();
			exit();
		}
	}
	
	function updatestatusAction() {
		$magickPath = "/usr/bin/convert";
		
		if($this->viewWorkOrder)
		{
			$params = $this->_getAllParams();
			
			$issue_id = $params['issue_id'];
			
			Zend_Loader::LoadClass('workorderClass', $this->modelDir);
			$workorderClass = new workorderClass();
			
			Zend_Loader::LoadClass('issueClass', $this->modelDir);
			$issueClass = new issueClass();
			$issue = $issueClass->getIssueById($params['issue_id']);
			
			if($params['upload'] == "Upload Progress")
			{
				if(!empty($_FILES["attachment"]))
				{
					$i=0;
					foreach($_FILES["attachment"]['name'] as $attachment)
					{
						if(!empty($attachment)) {
							$params['user_id'] = $this->ident['user_id'];
							$attachment_id = $workorderClass->addWOAttachment($params);
							if($attachment)
							{
								$ext = explode(".",$attachment);
								$filename = $attachment_id.".".$ext[count($ext)-1];
								$datafolder = $this->config->paths->html."/workorder/".date("Y")."/";
								if(!is_dir($datafolder)) mkdir($datafolder, 0777, true);
								if(move_uploaded_file($_FILES["attachment"]["tmp_name"][$i], $datafolder.$filename))
								{
									$workorderClass->updateWOAttachment($attachment_id,'filename', $filename);
									$workorderClass->updateWOAttachment($attachment_id,'description', $params['description'][$i]);
									
									if(in_array(strtolower($ext[count($ext)-1]), array("jpg", "jpeg", "png","bmp")))
									{
										$magickPath = "/usr/bin/convert";										
										/*** create thumbnail image ***/
										$filename_thumb = str_replace(".","_thumb.",$filename);
										exec($magickPath . ' ' . $datafolder."/".$filename . ' -resize 100x100 -bordercolor yellow -border 5 ' . $datafolder."/".$filename_thumb);
										/*** create medium image ***/
										exec($magickPath . ' ' . $datafolder."/".$filename . ' -resize 800x800 -bordercolor yellow -border 5 ' . $datafolder."/".$filename);
									}								
									$pic_url = $this->config->general->url."workorder/".date("Y")."/".$filename;
								}						
								$i++;
							}
						}
					}
				}
				$actionLog = "Uploading Work Order Progress";
				$telegramNotifTitle = 'PROGRESS IMAGES UPLOADED BY '.strtoupper($this->ident['name']);
			}
			//if($params['start-working'] == "Start Working")
			else
			{
				$wo_id = $workorderClass->startworkorder($params);
				$actionLog = "Work Order Start";
				$telegramNotifTitle = 'WORK ORDER STARTED BY '.strtoupper($this->ident['name']);
				$pic_url = $this->config->general->url."images/issues/".substr($issue['issue_date'],0,4)."/".str_replace(".","_large.",$issue['picture']);
			}

			
			$categoryTable = $this->loadModel('category');
			$category = $categoryTable->getCategoryById($issue['category_id']);			
			$issuetypeTable = $this->loadModel('issuetype');
			$type = $issuetypeTable->getIssueTypeById($issue['issue_type_id']);

			$kejadian = "";
			if(!empty($issue['kejadian_id'])) {
				$incidentTable = $this->loadModel('incident');
				$selIncident = $incidentTable->getIncidentById($issue['kejadian_id'],$issue['category_id']);	
				$kejadian = " - ".$selIncident['kejadian'];
			}
			$modus = "";
			if(!empty($issue['modus_id'])) {
				$modusTable = $this->loadModel('modus');
				$selModus = $modusTable->getModusById($issue['modus_id'],$issue['category_id']);
				$modus = " - ".$selModus['modus'];
			}
			$manpowername = "";
			if(!empty($issue['manpower_id'])) {
				Zend_Loader::LoadClass('manpowerClass', $this->modelDir);
				$manpowerClass = new manpowerClass();
				$manpower = $manpowerClass->getManPowerById($issue['manpower_id']);
				$manpowername = " - ".$manpower['name'];
			}
			$floor = "";
			$tenant_public = "";
			if(!empty($issue['floor_id'])) {
				$floorTable = $this->loadModel('floor');
				$selFloor = $floorTable->getFloorById($issue['floor_id'],$issue['category_id']);
				$floor = $selFloor['floor']." - ";
				$areaTable = $this->loadModel('area');
				$selArea = $areaTable->getAreaById($issue['area_id']);
				$area = $selArea['area_name']." - ";
			}

			if($issue['issue_type_id'] == 3) // khusus utk lost and found
			{
				$lostFoundOption = $issuetypeTable->getLostFoundOptionsById($params['lost_found_option_id']);
				$lostFoundSelOption = ' - '.$lostFoundOption['options'];
			}
					

			$botToken = '1635979900:AAHYn6pBb1KvD6SVTZ3tlb7AbJ5OaXk1BcY';
			$website="https://api.telegram.org/bot".$botToken;
			$chatId=$category['site_'.$this->site_id];  //Receiver Chat Id 
			$txt = '&#128227; <b><u>'.$telegramNotifTitle.'</u></b>
&#128205; '.$area.$floor.$issue['location'].' 
&#9888; '.$type['issue_type'].$kejadian.$modus.$manpowername.$lostFoundSelOption . '
&#8505; '.$issue['description'].'
&#128279; <a href="'.$this->config->general->url."default/issue/listissues/s/".$this->site_id."/c/".$issue['category_id']."/id/".$params['issue_id'].'">Open Kaizen</a>';

			$allParams = $params;

			$params=array(
				'chat_id'=>$chatId,
				'photo'=>$pic_url,
				'caption'=>$txt,
				'parse_mode'=>"HTML"
			);
			
			$ch = curl_init($website . '/sendPhoto');
			curl_setopt($ch, CURLOPT_HEADER, false);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, ($params));
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch,CURLOPT_CONNECTTIMEOUT, 3);
			curl_setopt($ch,CURLOPT_TIMEOUT, 30);
			$result = curl_exec($ch);
			curl_close($ch);

			$allParams['telegram'] = $params;
			
			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = $actionLog;
			$logData['data'] = json_encode($allParams);
			$logsTable->insertLogs($logData);
			
			$this->_response->setRedirect($this->baseUrl."/default/workorder/view");
			$this->_response->sendResponse();
			exit();
		}
		else {
			$this->_response->setRedirect($this->baseUrl);
			$this->_response->sendResponse();
			exit();
		}
	}
	
	public function deleteattachmentbyidAction() {
		if($this->viewWorkOrder)
		{
			$params = $this->_getAllParams();
			Zend_Loader::LoadClass('workorderClass', $this->modelDir);
			$workorderClass = new workorderClass();

			$curAttachment = $workorderClass->getAttachmentById($params['id']);
			
			$workorderClass->deleteAttachmentById($params['id']);
			
			$datafolder = $this->config->paths->html."workorder/".substr($curAttachment['uploaded_date'], 0, 4);
			unlink($datafolder."/".$curAttachment['filename']);

			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "Delete Work Order Progress Attachment";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);	
			
			echo $params['id'];
		}
		else {
			$this->_response->setRedirect($this->baseUrl);
			$this->_response->sendResponse();
			exit();
		}
	}
	
	function completewoAction() {
		$magickPath = "/usr/bin/convert";
		if($this->showFinishWO)
		{
			$params = $this->_getAllParams();
			
			$issue_id = $params['issue_id'];
			
			Zend_Loader::LoadClass('workorderClass', $this->modelDir);
			$workorderClass = new workorderClass();
			
			$progress_img = "";
		
			if(!empty($_FILES["attachment"]['name'][0]))
			{
				$i=0;
				foreach($_FILES["attachment"]['name'] as $attachment)
				{
					if(!empty($attachment)) {
						$params['user_id'] = $this->ident['user_id'];
						$attachment_id = $workorderClass->addWOAttachment($params);
						if($attachment)
						{
							$ext = explode(".",$attachment);
							$filename = $attachment_id.".".$ext[count($ext)-1];
							$datafolder = $this->config->paths->html."/workorder/".date("Y")."/";
							if(move_uploaded_file($_FILES["attachment"]["tmp_name"][$i], $datafolder.$filename))
							{
								$workorderClass->updateWOAttachment($attachment_id,'filename', $filename);
								$workorderClass->updateWOAttachment($attachment_id,'description', $params['description'][$i]);
								
								if(in_array(strtolower($ext[count($ext)-1]), array("jpg", "jpeg", "png","bmp")))
								{
									$magickPath = "/usr/bin/convert";
									/*** resize image if size greater than 500 Kb ***/
									if(filesize($datafolder.$filename) > 500000) //exec($magickPath . ' ' . $datafolder.$filename . ' -resize 800x800\> ' . $datafolder.$filename);
									
									$filename_thumb = str_replace(".","_thumb.",$filename);
									
									/*** create thumbnail image ***/
									exec($magickPath . ' ' . $datafolder."/".$filename . " -resize 100x100 -bordercolor green -border 5 " . $datafolder."/".$filename_thumb);
									/*** create medium image ***/
									exec($magickPath . ' ' . $datafolder."/".$filename . " -resize 800x800 -bordercolor green -border 5 " . $datafolder."/".$filename);
								}								
								$progress_img = $this->config->general->url."workorder/".date("Y")."/".$filename.'
';
							}						
							$i++;
						}
					}
				}
			}
			
			$wo_id = $workorderClass->finishworkorder($params);
			
			Zend_Loader::LoadClass('issueClass', $this->modelDir);
			$issueClass = new issueClass();
			$issue = $issueClass->getIssueById($params['issue_id']);
			$categoryTable = $this->loadModel('category');
			$category = $categoryTable->getCategoryById($issue['category_id']);			
			$issuetypeTable = $this->loadModel('issuetype');
			$type = $issuetypeTable->getIssueTypeById($issue['issue_type_id']);

			$kejadian = "";
			if(!empty($issue['kejadian_id'])) {
				$incidentTable = $this->loadModel('incident');
				$selIncident = $incidentTable->getIncidentById($issue['kejadian_id'],$issue['category_id']);	
				$kejadian = " - ".$selIncident['kejadian'];
			}
			$modus = "";
			if(!empty($issue['modus_id'])) {
				$modusTable = $this->loadModel('modus');
				$selModus = $modusTable->getModusById($issue['modus_id'],$issue['category_id']);
				$modus = " - ".$selModus['modus'];
			}
			$manpowername = "";
			if(!empty($issue['manpower_id'])) {
				Zend_Loader::LoadClass('manpowerClass', $this->modelDir);
				$manpowerClass = new manpowerClass();
				$manpower = $manpowerClass->getManPowerById($issue['manpower_id']);
				$manpowername = " - ".$manpower['name'];
			}
			$floor = "";
			$tenant_public = "";
			if(!empty($issue['floor_id'])) {
				$floorTable = $this->loadModel('floor');
				$selFloor = $floorTable->getFloorById($issue['floor_id'],$issue['category_id']);
				$floor = $selFloor['floor']." - ";
				$areaTable = $this->loadModel('area');
				$selArea = $areaTable->getAreaById($issue['area_id']);
				$area = $selArea['area_name']." - ";
			}

					

			$botToken = '716005387:AAFhUdDGlXjK5YFMYqwx_lCpqpp8760S_TE';
			$website="https://api.telegram.org/bot".$botToken;
			$chatId=$category['site_'.$this->site_id];  //Receiver Chat Id 
			$txt = '&#128227; <b><u>WORK ORDER COMPLETED AND WAITING FOR APPROVAL]</u></b>
&#128205; '.$area.$floor.$issue['location'].' 
&#9888; '.$type['issue_type'].$kejadian.$modus.$manpowername.'
&#8505; '.$issue['description'].'
&#128279; <a href="'.$this->config->general->url."default/issue/listissues/s/".$this->site_id."/c/".$issue['category_id']."/id/".$params['issue_id'].'">Open Kaizen</a>';

			$allParams = $params;

			$params=array(
				'chat_id'=>$chatId,
				'photo'=>$progress_img,
				'caption'=>$txt,
				'parse_mode'=>"HTML"
			);
			$ch = curl_init($website . '/sendPhoto');
			curl_setopt($ch, CURLOPT_HEADER, false);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, ($params));
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch,CURLOPT_CONNECTTIMEOUT, 3);
			curl_setopt($ch,CURLOPT_TIMEOUT, 30);
			$result = curl_exec($ch);
			curl_close($ch);

			$allParams['telegram'] = $params;
			
			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "Work Order Completed";
			$logData['data'] = json_encode($allParams);
			$logsTable->insertLogs($logData);
			
			$this->_response->setRedirect($this->baseUrl."/default/workorder/view");
			$this->_response->sendResponse();
			exit();
		}
		else {
			$this->_response->setRedirect($this->baseUrl);
			$this->_response->sendResponse();
			exit();
		}
	}
	
	function woapprovalAction() {
		$magickPath = "/usr/bin/convert";
		if($this->showApprovedWO)
		{
			$params = $this->_getAllParams();
			
			Zend_Loader::LoadClass('workorderClass', $this->modelDir);
			$workorderClass = new workorderClass();
			
			Zend_Loader::LoadClass('issueClass', $this->modelDir);
			$issueClass = new issueClass();
			
			$issue = $issueClass->getIssueById($params['issue_id']);
			$categoryTable = $this->loadModel('category');
			$category = $categoryTable->getCategoryById($issue['category_id']);			
			$issuetypeTable = $this->loadModel('issuetype');
			$type = $issuetypeTable->getIssueTypeById($issue['issue_type_id']);

			$kejadian = "";
			if(!empty($issue['kejadian_id'])) {
				$incidentTable = $this->loadModel('incident');
				$selIncident = $incidentTable->getIncidentById($issue['kejadian_id'],$issue['category_id']);	
				$kejadian = " - ".$selIncident['kejadian'];
			}
			$modus = "";
			if(!empty($issue['modus_id'])) {
				$modusTable = $this->loadModel('modus');
				$selModus = $modusTable->getModusById($issue['modus_id'],$issue['category_id']);
				$modus = " - ".$selModus['modus'];
			}
			$manpowername = "";
			if(!empty($issue['manpower_id'])) {
				Zend_Loader::LoadClass('manpowerClass', $this->modelDir);
				$manpowerClass = new manpowerClass();
				$manpower = $manpowerClass->getManPowerById($issue['manpower_id']);
				$manpowername = " - ".$manpower['name'];
			}
			$floor = "";
			$tenant_public = "";
			if(!empty($issue['floor_id'])) {
				$floorTable = $this->loadModel('floor');
				$selFloor = $floorTable->getFloorById($issue['floor_id'],$issue['category_id']);
				$floor = $selFloor['floor']." - ";
				$areaTable = $this->loadModel('area');
				$selArea = $areaTable->getAreaById($issue['area_id']);
				$area = $selArea['area_name']." - ";
			}
			
			$closeimage = "";
			
			$att = $workorderClass->getLastAttachmentByWoId($params['id']);
			
			if($params['stat'] == 1)
			{
				$workorderClass->approveworkorder($params);
				$action = "Work Order Approved";
				$statuswo = '&#128227; <b><u>WORK ORDER APPROVED</u></b>';				
				$temp = explode(".", $att['filename']);
				$ext = $temp[count($temp)-1];
				$ext = strtolower($ext);
				$curDate = 	date("Ymd_his")."_".$this->site_id."_".$params['issue_id'];
				$fileName = $curDate.'.'.$ext;
				if (@copy($this->config->paths->html."/workorder/".substr($att['uploaded_date'],0,4)."/".$att['filename'],$this->config->paths->html."/images/issues/".date("Y")."/".$curDate."_large.".$ext)){
					$new_file_thumb = $curDate."_thumb.".$ext;
					/*** create thumbnail image ***/
					$label = "isort.id ".date("d/m/Y")." ".date("H:i");
					exec($magickPath . ' ' . $this->config->paths->html."images/issues/".date("Y")."/".$curDate."_large.".$ext. " -resize 3% -pointsize 16 label:'isort.id' -gravity Center -append " . $this->config->paths->html."images/issues/".date("Y")."/".$new_file_thumb);
				
					$params['picture'] = $fileName;
					$params['user_id'] = $this->ident['user_id'];
					$id = $issueClass->saveSolveIssue($params);
					
					Zend_Loader::LoadClass('commentsClass', $this->modelDir);
					$commentsClass = new commentsClass();
					$commentsClass->addComment($params);
					
					$url = 'solvedissues';
				}
			}
			else
			{
				$workorderClass->rejectworkorder($params);
				$action = "Work Order Rejected";
				$statuswo = '&#128227; <b><u>WORK ORDER REJECTED</u></b>';
				$url = 'listissues';
			}
			
			$params['user_id'] = $this->ident['user_id'];
			$workorderClass->addComment($params);
					

			$botToken = '716005387:AAFhUdDGlXjK5YFMYqwx_lCpqpp8760S_TE';
			$website="https://api.telegram.org/bot".$botToken;
			$chatId=$category['site_'.$this->site_id];  //Receiver Chat Id 
			$txt = $statuswo.'
&#128172; '.$params['comment'].'

&#128205; '.$area.$floor.$issue['location'].' 
&#9888; '.$type['issue_type'].$kejadian.$modus.$manpowername.$lostFoundSelOption . '
&#8505; '.$issue['description'].'
&#128279; <a href="'.$this->config->general->url."default/issue/".$url."/s/".$this->site_id."/c/".$issue['category_id']."/id/".$params['issue_id'].'">Open Kaizen</a>';

			$allParams = $params;

			$params=array(
				'chat_id'=>$chatId,
				'photo'=>$this->config->general->url."workorder/".substr($att['uploaded_date'],0,4)."/".$att['filename'],
				'caption'=>$txt,
				'parse_mode'=>"HTML"
			);
			$ch = curl_init($website . '/sendPhoto');
			curl_setopt($ch, CURLOPT_HEADER, false);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, ($params));
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch,CURLOPT_CONNECTTIMEOUT, 3);
			curl_setopt($ch,CURLOPT_TIMEOUT, 30);
			$result = curl_exec($ch);
			curl_close($ch);

			$allParams['telegram'] = $params;
			
			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = $action;
			$logData['data'] = json_encode($allParams);
			$logsTable->insertLogs($logData);
			
			$this->_response->setRedirect($this->baseUrl."/default/workorder/view");
			$this->_response->sendResponse();
			exit();
		}
		else {
			$this->_response->setRedirect($this->baseUrl);
			$this->_response->sendResponse();
			exit();
		}
	}
}
?>