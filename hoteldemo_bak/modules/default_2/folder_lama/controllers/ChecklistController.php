<?php
require_once('actionControllerBase.php');
class ChecklistController extends actionControllerBase
{
	function addAction() {
		if($this->showAddDigitalChecklist)
		{
			$params = $this->_getAllParams();
			
			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "Add Checklist";
			$logData['data'] = json_encode($param);
			$logsTable->insertLogs($logData);
			
			$this->view->title = "Add Digital Checklist";
			
			
			$user = $this->loadModel('user');
			$dept = $user->getDepartmentsByRoles(implode($this->ident['role_ids']));
			$dept_ids = "";
			if(!empty($dept))
			{
				foreach($dept as $d)
				{
					if($d['category_id'] > 0) $dept_ids = $d['category_id'].",";
				}
			}
			$dept_ids = substr($dept_ids, 0, -1);
			$checklist = $this->loadModel('checklist');
			$this->view->templates = $templates = $checklist->getTemplatesByDept($dept_ids);
			
			$this->view->rooms = $rooms = $checklist->getRoomsByTemplateId($templates[0]['template_id'], $templates[0]['category_id']);
			$this->view->err = $params['err'];
			
			$this->renderTemplate('form_checklist.tpl');	
		}
		else {
			$this->_response->setRedirect($this->baseUrl);
			$this->_response->sendResponse();
			exit();
		}
	}
	
	function getroomsbytemplateidAction() {
		if($this->showAddDigitalChecklist)
		{
			$params = $this->_getAllParams();
			
			$checklist = $this->loadModel('checklist');
			$template = $checklist->getTemplatesById($params['id']);
			
			$rooms = $checklist->getRoomsByTemplateId($params['id'], $template['category_id']);
			echo json_encode($rooms);
		}
		else {
			$this->_response->setRedirect($this->baseUrl);
			$this->_response->sendResponse();
			exit();
		}
	}

	public function savechecklistAction() {
		if($this->showAddDigitalChecklist || $this->showAddSpvDigitalChecklist)
		{
			$params = $this->_getAllParams();
						
			$params['user_id'] = intval($this->ident['user_id']);
			$checklist = $this->loadModel('checklist');
			
			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "Save Checklist";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);	
			
			if(empty($params['checklist_id'])) {
				$existingOpen = $checklist->checkChecklist($params);
				if(!empty($existingOpen) && (empty($existingOpen['save_date_staff2']) || empty($existingOpen['save_date_staff3'])))
				{
					$this->_response->setRedirect($this->baseUrl."/default/checklist/add/err/1");
					$this->_response->sendResponse();
					exit();
				}				
				
				$curTemplate = $checklist->getTemplatesById($params['template_id']);
				$floorTable = $this->loadModel('floor');
				$floorExist = $floorTable->getFloorByName(trim($params['room_no']), $curTemplate['category_id']);
				if(empty($floorExist))
				{
					$this->_response->setRedirect($this->baseUrl."/default/checklist/add/err/2");
					$this->_response->sendResponse();
					exit();
				}	
			}
			
			$id = $checklist->saveChecklist($params);
			
			$items = $checklist->getItemsByTemplateAndChecklist($params['template_id'], $id);
			//echo "<pre>"; print_r($items); exit();
			if(!empty($items))
			{
				foreach($items as $val) {
					$data['template_id'] = $params['template_id'];
					$data['checklist_id'] = $id;
					$data['position'] = "staff";
					$data['template_item_id'] = intval($val['template_item_id_ori']);
					$data['template_item_name'] = $val['template_item_name'];
					$data['user_staff'] = $params['user_id'];
					//print_r($data); exit();
					$checklist->saveChecklistItem($data);
				}
			}
			
			
			$this->_response->setRedirect($this->baseUrl."/default/checklist/viewchecklistitems/id/".$id);
			$this->_response->sendResponse();
			exit();
		}
		else {
			$this->_response->setRedirect($this->baseUrl);
			$this->_response->sendResponse();
			exit();
		}
	}
	
	public function viewchecklistitemsAction() {
		if($this->showAddDigitalChecklist || $this->showAddSpvDigitalChecklist)
		{
			$params = $this->_getAllParams();	

			$checklist = $this->loadModel('checklist');			
			$dChecklist = $checklist->getChecklistById($params['id']);
			$checklist_date = explode(" ", $dChecklist['submitted_date']);
			$dChecklist['checklist_date'] = date("j M Y");
			$this->view->checklist = $dChecklist;
			$template = $checklist->getTemplatesById($dChecklist['template_id']);
            $this->view->title = $template['template_name'];			
			
			//$items = $checklist->getItemsByTemplateAndChecklist($dChecklist['template_id'], $dChecklist['checklist_id']);
			$items = $checklist->getItemsByChecklistId($dChecklist['checklist_id']);
			//echo "<pre>"; print_r($items); exit();
			$date1 = explode(" ", $items[0]['save_date_staff']);
			$date2 = explode(" ", $items[0]['save_date_staff2']);
			$date3 = explode(" ", $items[0]['save_date_staff3']);
			if($this->showAddDigitalChecklist && empty($params['p'])) 
			{
				$position = "Staff";
				if($date1[0] == date("Y-m-d")) $posOff = "";
				elseif(empty($items[0]['save_date_staff2']) || $date2[0] == date("Y-m-d")) $posOff = "2";
				elseif(empty($items[0]['save_date_staff3']) || $date3[0] == date("Y-m-d")) $posOff = "3";
			}
			elseif($this->showAddSpvDigitalChecklist && $params['p'] == "spv") {
				$position = "Spv";
				$dateSpv1 = explode(" ", $items[0]['save_date_spv']);
				$dateSpv2 = explode(" ", $items[0]['save_date_spv2']);
				$dateSpv3 = explode(" ", $items[0]['save_date_spv3']);
				if((empty($items[0]['save_date_spv']) || $dateSpv1[0] == date("Y-m-d")) && $date1[0] == date("Y-m-d") ) $posOff = "";
				elseif((empty($items[0]['save_date_spv2']) || $dateSpv2[0] == date("Y-m-d")) && $date2[0] == date("Y-m-d")) $posOff = "2";
				elseif((empty($items[0]['save_date_spv3']) || $dateSpv3[0] == date("Y-m-d")) && $date3[0] == date("Y-m-d")) $posOff = "3";
			}
			$this->view->position = $position;
			$this->view->posOff = $posOff;
			
			// select * from checklist_items i join checklist c on c.checklist_id = i.checklist_id where c.room_no = 100 and issue_id > 0;
			$itemHasIssues = $checklist->getChecklistItemsIssue($dChecklist['room_no']);
			$issue_items = array();
			if(!empty($itemHasIssues))
			{
				foreach($itemHasIssues as $ii)
				{
					$issue_items[$ii['template_item_id']] = $ii['issue_id'];
				}
			}
			if(!empty($items))
			{
				foreach($items as &$it)
				{
					if(empty($it['condition_'.strtolower($position).$posOff]) && $issue_items[$it['template_item_id']] > 0 /*$it['issue_id'] > 0*/) $it['condition'] = 2;
					else $it['condition'] = $it['condition_'.strtolower($position).$posOff];
				}
			}
			//echo "<pre>"; print_r($items); exit();
			$this->view->items = $items;
			$areaTable = $this->loadModel('area');	
			$this->view->area = $areaTable->getArea();
			
			$floorTable = $this->loadModel('floor');	
			
			$incidentTable = $this->loadModel('incident');
			
			if($dChecklist['template_id'] == 4) $type_id = 15;
			if($dChecklist['template_id'] <= 5) $type_id = 16;
			else $type_id = 15;
			
			$this->view->type_id = $type_id;
			$this->view->incident = $incidentTable->getIncidentByIssueTypeId($type_id, $dChecklist['category_id']);
			
			/*if($template['template_id'] == 1) {
				$this->view->floor_id = 288;
				$this->view->area_id = $area_id = 10;				
			}
			else { */
				$curFloor = $floorTable->getFloorByName($dChecklist['room_no'], $dChecklist['category_id']);
				$this->view->floor_id = $curFloor['floor_id'];
				$this->view->area_id = $area_id = $curFloor['area'];
			/*}*/
			$this->view->curFloor = $floorTable->getFloorByAreaId($area_id, $dChecklist['category_id']);
			
			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "View Checklist Items";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);
			
			$this->view->defaultCategory = 6;
			
			$this->renderTemplate('form_checklist_items.tpl'); 
		}
		else {
			$this->_response->setRedirect($this->baseUrl);
			$this->_response->sendResponse();
			exit();
		}
	}
	
	public function viewchecklistitemshodAction() {
		if($this->showAddHodDigitalChecklist)
		{
			$params = $this->_getAllParams();	

			$checklist = $this->loadModel('checklist');			
			$dChecklist = $checklist->getChecklistById($params['id']);
			$checklist_date = explode(" ", $dChecklist['submitted_date']);
			$dChecklist['checklist_date'] = date("j M Y");
			$this->view->checklist = $dChecklist;
			$template = $checklist->getTemplatesById($dChecklist['template_id']);
            $this->view->title = $template['template_name'];			
			
			$items = $checklist->getItemsByChecklistId($dChecklist['checklist_id']);
		
			$this->view->items = $items;
			
			$this->view->comments = $checklist->getCommentsByChecklistId($params['id'], 0, 'asc');
			
			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "View Checklist Items";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);
			
			$this->renderTemplate('form_checklist_items_hod.tpl'); 
		}
		else {
			$this->_response->setRedirect($this->baseUrl);
			$this->_response->sendResponse();
			exit();
		}
	}
	
	public function savechecklistitemsAction() {
		if($this->showAddDigitalChecklist || $this->showAddSpvDigitalChecklist)
		{
			$params = $this->_getAllParams();
			$checklist = $this->loadModel('checklist');			
			$dChecklist = $checklist->getChecklistById($params['checklist_id']);
			$params['template_id'] = $dChecklist['template_id'];
			if(!empty($params['item_id']))
			{
				$i = 0;
				foreach($params['item_id'] as $key=>$val) {
					$data['item_id'] = intval($val);
					$data['template_id'] = $params['template_id'];
					$data['checklist_id'] = $params['checklist_id'];
					$data['position'] = $params['position'].$params['pos_off'];
					$data['template_item_name'] = $params['item_name'][$key];
					$data['condition'] = intval($params['item_condition'][$key]);
					$data["user_id"] = $this->ident['user_id'];
					$checklist->saveChecklistItem($data);
					$params['data'][$i] = $data;
					$i++;
				}
			}
			
			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "Save Checklist";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);	

			/*$params['user_id'] = intval($this->ident['user_id']);
			$checklist = $this->loadModel('checklist');
			$id = $checklist->saveChecklist($params);

			$this->_response->setRedirect($this->baseUrl."/default/checklist/viewchecklistitems/id/".$id);
			$this->_response->sendResponse();
			exit();*/
		}
		else {
			$this->_response->setRedirect($this->baseUrl);
			$this->_response->sendResponse();
			exit();
		}
	}
	
	public function viewAction() {
		$params = $this->_getAllParams();
		
		$checklistTable = $this->loadModel('checklist');

		if(empty($params['start'])) $params['start'] = '0';
		$params['pagesize'] = 10;
		$this->view->start = $params['start'];
		$checklist = $checklistTable->getChecklist($params);
		foreach($checklist as &$ch)
		{
			$date = explode(" ", $ch['submitted_date']);
			$arr_date = explode("-",$date[0]);
			$ch['day_date'] = date("l, j F Y", mktime(0, 0, 0, $arr_date[1], $arr_date[2], $arr_date[0]));

			$ch['comments'] = $checklistTable->getCommentsByChecklistId($ch['checklist_id'], '3');
			
			$ch['show_edit_staff'] = $ch['show_edit_spv'] = 0;
			
			$date1 = explode(" ", $ch['save_date_staff']);
			$date2 = explode(" ", $ch['save_date_staff2']);
			$date3 = explode(" ", $ch['save_date_staff3']);
			if($this->showAddDigitalChecklist && ($date1[0] >= date("Y-m-d") || empty($ch['save_date_staff2']) || $date2[0] >= date("Y-m-d") || empty($ch['save_date_staff3']) || $date3[0] >= date("Y-m-d"))) $ch['show_edit_staff'] = 1;
			
			$dateSpv1 = explode(" ", $ch['save_date_spv']);
			$dateSpv2 = explode(" ", $ch['save_date_spv2']);
			$dateSpv3 = explode(" ", $ch['save_date_spv3']);
			
			if($this->showAddSpvDigitalChecklist && ($date1[0] == date("Y-m-d") || $date2[0] == date("Y-m-d") || $date3[0] == date("Y-m-d")) && (empty($ch['save_date_spv']) || empty($ch['save_date_spv2']) || empty($ch['save_date_spv3']) || $dateSpv1[0] == date("Y-m-d") || $dateSpv2[0] == date("Y-m-d") || $dateSpv3[0] == date("Y-m-d"))) { $ch['show_edit_spv'] = 1; }
			
			if($this->showAddHodDigitalChecklist && ((!empty($ch['save_date_staff3']) && $date3[0] <= date("Y-m-d")) || (!empty($ch['save_date_spv3']) && $dateSpv3[0] <= date("Y-m-d")))) { 
				$ch['show_edit_hod'] = 1; 
			}
			
			if((!empty($ch['save_date_staff3']) && $date3[0] <= date("Y-m-d")) || (!empty($ch['save_date_spv3']) && $dateSpv3[0] <= date("Y-m-d")))
			{				
				$totalImage = $checklistTable->getTotalImageHod($ch['checklist_id']);
				if($totalImage < 10) $ch['highlight'] = 1;
			}
			
			$ch['status'] = "VCC";
			$badStatus = $checklistTable->getStatusByChecklistId($ch['checklist_id']);
			if(!empty($badStatus))
			{
				foreach($badStatus as $bs)
				{
					if($bs['save_date_spv3'] > '0000-00-00 00:00:00')
					{
						if($bs['condition_spv3'] == 2) 
						{
							$ch['status'] = "VD";
							break;
						}
					}
					elseif($bs['save_date_staff3'] > '0000-00-00 00:00:00')
					{
						if($bs['condition_staff3'] == 2) 
						{
							$ch['status'] = "VD";
							break;
						}
					}
					elseif($bs['save_date_spv2'] > '0000-00-00 00:00:00')
					{
						if($bs['condition_spv2'] == 2) 
						{
							$ch['status'] = "VD";
							break;
						}
					}
					elseif($bs['save_date_staff2'] > '0000-00-00 00:00:00')
					{
						if($bs['condition_staff2'] == 2) 
						{
							$ch['status'] = "VD";
							break;
						}
					}
					elseif($bs['save_date_spv'] > '0000-00-00 00:00:00')
					{
						if($bs['condition_spv'] == 2) 
						{
							$ch['status'] = "VD";
							break;
						}
					}
					elseif($bs['save_date_staff'] > '0000-00-00 00:00:00')
					{
						if($bs['condition_staff'] == 2) 
						{
							$ch['status'] = "VD";
							break;
						}
					}
				}
			}
		}
		$this->view->checklist = $checklist;

		if(!empty($params['room_no']))
		{
			$addUrl = "/room_no/".$params['room_no'];
		}
		
		$totalReport = $checklistTable->getTotalChecklist($params);
		if($totalReport['total'] > 10)
		{
			if($params['start'] >= 10)
			{
				$this->view->firstPageUrl = "/default/checklist/view".$addUrl;
				$this->view->prevUrl = "/default/checklist/view/start/".($params['start']-$params['pagesize']).$addUrl;
			}
			if($params['start'] < (floor(($totalReport['total']-1)/10)*10))
			{
				$this->view->nextUrl = "/default/checklist/view/start/".($params['start']+$params['pagesize']).$addUrl;
				$this->view->lastPageUrl = "/default/checklist/view/start/".(floor(($totalReport['total']-1)/10)*10).$addUrl;
			}
		}
		$this->view->curPage = ($params['start']/$params['pagesize'])+1;
		$this->view->totalPage = ceil($totalReport['total']/$params['pagesize']);
		if($totalReport['total'] > 0) $this->view->startRec = $params['start'] + 1;
		else $this->view->startRec = 0;
		$endRec = $params['start'] + $params['pagesize'];
		if($totalReport['total'] >=  $endRec) $this->view->endRec =  $endRec;
		else $this->view->endRec =  $totalReport['total'];		
		$this->view->totalRec = $totalReport['total'];

		$this->view->site_id = $this->site_id;
		$this->view->room_no = $params['room_no'];

		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "View Checklist List";
		$logData['data'] = json_encode($params);
		$logsTable->insertLogs($logData);	

		$this->renderTemplate('view_checklist.tpl');  
	}
	
	function editAction() {
		if($this->showAddDigitalChecklist)
		{
			$params = $this->_getAllParams();
			
			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "Edit Checklist";
			$logData['data'] = json_encode($param);
			$logsTable->insertLogs($logData);
			
			$this->view->title = "Edit Digital Checklist";
			
			
			$user = $this->loadModel('user');
			$dept = $user->getDepartmentsByRoles(implode($this->ident['role_ids']));
			$dept_ids = "";
			if(!empty($dept))
			{
				foreach($dept as $d)
				{
					if($d['category_id'] > 0) $dept_ids = $d['category_id'].",";
				}
			}
			$dept_ids = substr($dept_ids, 0, -1);
			$checklist = $this->loadModel('checklist');
			$this->view->templates = $checklist->getTemplatesByDept($dept_ids);
			
			$this->view->checklistDetail = $checklistDetail = $checklist->getChecklistById($params['id']);
			
			$this->renderTemplate('form_checklist.tpl');	
		}
		else {
			$this->_response->setRedirect($this->baseUrl);
			$this->_response->sendResponse();
			exit();
		}
	}
	
	public function viewdetailAction() {
		if($this->showDigitalChecklist)
		{

			$params = $this->_getAllParams();
			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "View Digital Checklist Detail";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);	

			$checklist = $this->loadModel('checklist');			
			$this->view->checklist = $dChecklist = $checklist->getChecklistById($params['id']);
			$template = $checklist->getTemplatesById($dChecklist['template_id']);
            $this->view->title = $template['template_name'];
			$this->view->items = $items = $items = $checklist->getItemsByChecklistId($dChecklist['checklist_id']);
			
			$user = $this->loadModel('user');	
			$users = $user->getAllUsersName($this->site_id);
			$allUsers = array();
			if(!empty($users))
			{
				foreach($users as $user)
				{
					$allUsers[$user['user_id']] = $user['name'];
				}
			}
			$date1 = explode(" ",$items[0]['save_date_staff']);
			$this->view->date1 = date("j M Y", strtotime($date1[0]));
			$this->view->user_staff = $allUsers[$items[0]['user_staff']];
			$this->view->user_spv = $allUsers[$items[0]['user_spv']];
			if(!empty($items[0]['save_date_staff2']))
			{
				$date2 = explode(" ",$items[0]['save_date_staff2']);
				$this->view->date2 = date("j M Y", strtotime($date2[0]));
				$this->view->user_staff2 = $allUsers[$items[0]['user_staff2']];
				$this->view->user_spv2 = $allUsers[$items[0]['user_spv2']];
			}
			if(!empty($items[0]['save_date_staff3']))
			{
				$date3 = explode(" ",$items[0]['save_date_staff3']);
				$this->view->date3 = date("j M Y", strtotime($date3[0]));
				$this->view->user_staff3 = $allUsers[$items[0]['user_staff3']];
				$this->view->user_spv3 = $allUsers[$items[0]['user_spv3']];
			}
			$this->renderTemplate('view_digital_checklist_detail.tpl'); 
		}
		else {
			$this->_response->setRedirect($this->baseUrl);
			$this->_response->sendResponse();
			exit();
		}
	}
	
	/*** COMMENTS ***/

	public function addcommentAction() {
		if($this->showDigitalChecklist)
		{
			$params = $this->_getAllParams();
			$params['user_id'] = $this->ident['user_id'];
			$checklistTable = $this->loadModel('checklist');

			if($_FILES["attachment"]["size"] > 0)
			{
				$datafolder = $this->config->paths->html."/images/checklist/comments_".date("Ym")."/";
				if(!is_dir($datafolder)) mkdir($datafolder, 0777, true);
				$ext = explode(".",$_FILES["attachment"]['name']);
				$filename = "checklist_cmt_".date("YmdHis").".".$ext[count($ext)-1];
				if(move_uploaded_file($_FILES["attachment"]["tmp_name"], $datafolder.$filename))
				{
					
					if(in_array(strtolower($ext[count($ext)-1]), array("jpg", "jpeg", "png","bmp")))
					{
						$magickPath = "/usr/bin/convert";
						/*** resize image if size greater than 500 Kb ***/
						if(filesize($datafolder.$filename) > 500000) exec($magickPath . ' ' . $datafolder.$filename . ' -resize 800x800\> ' . $datafolder.$filename);
					}					
					$params['filename'] = $filename;	
					$checklistTable->addComment($params);
				}		
			}
			else{
				$checklistTable->addComment($params);
			}	

			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "Add Checklist Comment";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);	

			echo $params['filename'];
		}
		else {
			$this->_response->setRedirect($this->baseUrl);
			$this->_response->sendResponse();
			exit();
		}
	}

	function getcommentsbychecklistidAction()
	{
		$params = $this->_getAllParams();
		$this->view->ident = $this->ident;
		$category = $this->loadModel('category');
		$checklistTable = $this->loadModel('checklist');
		$comments = $checklistTable->getCommentsByChecklistId($params['id']);
		$commentText = "";
		if(!empty($comments)) {
			foreach($comments as $comment)
			{
				$comment_date = explode("-", $comment['comment_date']); 
				$commentText .= '<div style="border-bottom:1px solid #ccc; text-align:left; clear:both; margin-bottom:5px; padding-bottom:5px;"><strong>'.$comment['name'].' : </strong>'.$comment['comment'].'<br/>';
				if(!empty($comment['filename'])) $commentText .= '<a href="'.$this->baseUrl."/images/checklist/comments_".$comment_date[0].$comment_date[1]."/".$comment['filename'].'" target="_blank"><i class="fa fa-paperclip"></i> '.$comment['filename'].'</a>';
				$commentText .= '<div style="font-size:10px; color:#aaa; text-align:right;">'.$comment['comment_date']."</div></div>";
			}
		}
		
		echo $commentText;	
	}

	function updatecommentsAction()
	{
		$params = $this->_getAllParams();
		$params['pagesize'] = 10;
		
		$checklistTable = $this->loadModel('checklist');
		
		$data= array();

		$commentCacheName = "checklist_comments_".$this->site_id."_".$params["start"];
		//$data = $this->cache->load($commentCacheName);
		$i=0;
		if(empty($data))
		{
			$checklistList = $checklistTable->getChecklist($params);	
			foreach($checklistList as $s) {
				$data[$i]['checklist_id'] = $s['checklist_id'];
				$comments = $checklistTable->getCommentsByChecklistId($s['checklist_id'], '3');
				if(!empty($comments)) { 
					$comment_content = "";
					foreach($comments as $comment)
					{
						$comment_date = explode("-", $comment['comment_date']); 
						$comment_content .= '<div style="border-bottom:1px solid #ccc; text-align:left; clear:both; margin-bottom:5px; padding-bottom:5px;"><strong>'.$comment['name'].' : </strong>'.$comment['comment'].'<br/>';
						if(!empty($comment['filename'])) $comment_content .= '<a href="'.$this->baseUrl."/images/checklist/comments_".$comment_date[0].$comment_date[1]."/".$comment['filename'].'" target="_blank"><i class="fa fa-paperclip"></i> '.$comment['filename'].'</a>';
						$comment_content .= '<div style="font-size:10px; color:#aaa; text-align:right;">'.$comment['comment_date']."</div></div>";
					}
					$data[$i]['comment'] = $comment_content;
				}
				$i++;
			}
			//$this->cache->save($data, $commentCacheName, array($commentCacheName), 60);
		}		
		echo json_encode($data);
	}
	
	public function exporttopdfAction() {	
		$params = $this->_getAllParams();

		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "Export Checklist to PDF";
		$logData['data'] = json_encode($params);
		$logsTable->insertLogs($logData);	

		$checklist = $this->loadModel('checklist');			
		$dChecklist = $checklist->getChecklistById($params['id']);
		$template = $checklist->getTemplatesById($dChecklist['template_id']);
		$title = $template['template_name'];
		$items = $items = $checklist->getItemsByChecklistId($dChecklist['checklist_id']);
		
		$user = $this->loadModel('user');	
		$users = $user->getAllUsersName($this->site_id);
		$allUsers = array();
		if(!empty($users))
		{
			foreach($users as $user)
			{
				$allUsers[$user['user_id']] = $user['name'];
			}
		}
		
		$date1 = explode(" ",$items[0]['save_date_staff']);
		$date1Formatted = date("j M Y", strtotime($date1[0]));
		$user_staff = $allUsers[$items[0]['user_staff']];
		$user_spv = $allUsers[$items[0]['user_spv']];
		if(!empty($items[0]['save_date_staff2']))
		{
			$date2 = explode(" ",$items[0]['save_date_staff2']);
			$date2Formatted = date("j M Y", strtotime($date2[0]));
			$user_staff2 = $allUsers[$items[0]['user_staff2']];
			$user_spv2 = $allUsers[$items[0]['user_spv2']];
		}
		if(!empty($items[0]['save_date_staff3']))
		{
			$date3 = explode(" ",$items[0]['save_date_staff3']);
			$date3Formatted = date("j M Y", strtotime($date3[0]));
			$user_staff3 = $allUsers[$items[0]['user_staff3']];
			$user_spv3 = $allUsers[$items[0]['user_spv3']];
		}
		
		//$hodcomments = $hodTable->getCommentsByHODMeetingId($params['id'], 0, 'asc');
		
		require_once('fpdf/mc_table.php');
		$pdf=new PDF_MC_Table();
		$pdf->AddPage();
		$pdf->SetTitle($title);
		$pdf->SetFont('Arial','B',15);
		$pdf->Write(10, $title);
		$pdf->ln(10);

		$pdf->SetFont('Arial','B',8);
		$pdf->Cell(30,6,'Room No',0,0,'L');
		$pdf->Cell(138,6,$dChecklist['room_no'],0,0,'L');
		$pdf->Ln();
		
		$pdf->SetFont('Arial','B',8);
		
		//$pdf->SetTextColor(255,255,255);
		$pdf->Cell(70,7,'Date',1,0,'L',false);
		$pdf->Cell(30,7,$date1Formatted,1,0,'C',false);
		$pdf->Cell(30,7,$date2Formatted,1,0,'C',false);
		$pdf->Cell(30,7,$date3Formatted,'LTR',0,'C',false);
		$pdf->Cell(30,7,'',1,0,'C',false);
		$pdf->Ln();
		$pdf->Cell(70,7,'Checked By',1,0,'L',false);
		$pdf->Cell(15,7,'Staff',1,0,'C',false);
		$pdf->Cell(15,7,'Spv',1,0,'C',false);
		$pdf->Cell(15,7,'Staff',1,0,'C',false);
		$pdf->Cell(15,7,'Spv',1,0,'C',false);
		$pdf->Cell(15,7,'Staff',1,0,'C',false);
		$pdf->Cell(15,7,'Spv',1,0,'C',false);
		$pdf->Cell(30,7,'HOD','LBR',0,'C',false);
		$pdf->Ln();
		/*$pdf->Cell(70,7,'',1,0,'L',false);
		$pdf->Cell(15,7,$user_staff,1,0,'C',false);
		$pdf->Cell(15,7,$user_spv,1,0,'C',false);
		$pdf->Cell(15,7,$user_staff2,1,0,'C',false);
		$pdf->Cell(15,7,$user_spv2,1,0,'C',false);
		$pdf->Cell(15,7,$user_staff3,1,0,'C',false);
		$pdf->Cell(15,7,$user_spv3,1,0,'C',false);
		$pdf->Cell(30,7,'','LBR',0,'C',false);
		$pdf->Ln();*/		
		$pdf->SetFont('Arial','',7);
		$pdf->SetTextColor(0,0,0);
		$pdf->SetWidths(array(70, 15, 15, 15, 15, 15, 15, 30));	
		$pdf->Row(array('',$user_staff,$user_spv,$user_staff2,$user_spv2, $user_staff3, $user_spv3, ''));
		$pdf->SetWidths(array(10, 60, 15, 15, 15, 15, 15, 15, 30));	
		if(!empty($items))
		{
			$i = 1;
			foreach($items as $item) {
				if($item['category_id'] != $category_id)
				{					
					if($pdf->GetY() >= 250) $pdf->AddPage();
					$pdf->SetFillColor(158,130,75);
					$pdf->SetTextColor(255,255,255);
					$pdf->Cell(190,7,$item['category_name'],1,0,'L',true);
					$pdf->Ln();
					$category_id = $item['category_id']; 
				}
				if($item['subcategory_id'] != $subcategory_id)
				{
					if($pdf->GetY() >= 260) $pdf->AddPage();
					$pdf->SetFillColor(161,162,166);
					$pdf->SetTextColor(255,255,255);
					$pdf->Cell(190,7,$item['subcategory_name'],1,0,'L',true);
					$pdf->Ln();
					$subcategory_id = $item['subcategory_id']; 
				}
				
				$tickImg = $this->config->paths->html."/images/tick.jpg";
				$crossImg = $this->config->paths->html."/images/cross.jpg";
				
				if($pdf->GetY() >= 270) $pdf->AddPage();
				
				if($item['condition_staff']== 1) 
					$pdf->Image($tickImg,86,$pdf->GetY()+1, 0, 4); 
				else if($item['condition_staff']== 2) 
					$pdf->Image($crossImg,86,$pdf->GetY()+1, 0, 4);
				
				if($item['condition_spv']== 1) 
					$pdf->Image($tickImg,101,$pdf->GetY()+1, 0, 4); 
				else if($item['condition_spv']== 2) 
					$pdf->Image($crossImg,101,$pdf->GetY()+1, 0, 4);
					
				if($item['condition_staff2']== 1) 
					$pdf->Image($tickImg,116,$pdf->GetY()+1, 0, 4); 
				else if($item['condition_staff2']== 2) 
					$pdf->Image($crossImg,116,$pdf->GetY()+1, 0, 4);
					
				if($item['condition_spv2']== 1) 
					$pdf->Image($tickImg,131,$pdf->GetY()+1, 0, 4); 
				else if($item['condition_spv2']== 2) 
					$pdf->Image($crossImg,131,$pdf->GetY()+1, 0, 4);
					
				if($item['condition_staff3']== 1) 
					$pdf->Image($tickImg,146,$pdf->GetY()+1, 0, 4); 
				else if($item['condition_staff3']== 2) 
					$pdf->Image($crossImg,146,$pdf->GetY()+1, 0, 4);
				
				if($item['condition_spv3']== 1) 
					$pdf->Image($tickImg,161,$pdf->GetY()+1, 0, 4); 
				else if($item['condition_spv3']== 2) 
					$pdf->Image($crossImg,161,$pdf->GetY()+1, 0, 4);
				$pdf->SetTextColor(0,0,0);	
				
				if(!empty($item['hod_image_update']))
				{					
					if($pdf->GetY() > 250)  $pdf->AddPage();
					$hod_image = "\n\n\n\n";
					$imagePath = $this->config->paths->html.str_replace(".", "_thumb.", $item['hod_image_update']);
					if(@getimagesize($imagePath)) {
						$pdf->Image($imagePath,176,$pdf->GetY()+2, 16, 0);
					}
				}
				else
					$hod_image = "";
				
				$pdf->Row(array($i,$item['item_name'],$condition_staff,$condition_spv,$condition_staff2, $condition_spv2, $condition_staff3, $condition_spv3, $hod_image));
				$i++; 
			}
		}
		
		$checklistComments = $checklist->getCommentsByChecklistId($params['id'], 0, 'asc');
		
		if(!empty($checklistComments))
		{
			$pdf->Ln();
			$pdf->SetFont('Arial','B',9);
			$pdf->SetTextColor(0,0,0);
			$pdf->Write(10,"Comments");
			$pdf->Ln();
			
			foreach($checklistComments as $comment)
			{
				$comment_date = date("l, j F Y", strtotime(substr($comment['comment_date'], 0, 10)));
				$pdf->SetFont('Arial','B',8);
				$pdf->SetTextColor(0,0,0);
				$pdf->Cell(10,4,$comment['name'],0,0,'L');
				$pdf->Ln();
				$pdf->SetFont('Arial','',6);
				$pdf->SetTextColor(170,170,170);
				$pdf->Cell(10,3,$comment_date,0,0,'L');
				$pdf->Ln();
				$pdf->SetFont('Arial','',8);
				$pdf->SetTextColor(0,0,0);
				$pdf->Write(5,str_replace("<br>","\n",$comment['comment']));
				if(!empty($comment['filename']))
				{
					$pdf->Ln();
					$pdf->SetTextColor(0,67,187);
					$pdf->write(5,'# '.$comment['filename']);
					$pdf->Link(10, $pdf->getY(), 189, 5, $this->baseUrl.'/images/hod_meeting/comments_'.substr($comment['comment_date'], 0, 4).substr($comment['comment_date'], 5, 2).'/'.$comment['filename']);
				}
				$pdf->Ln(10);
			}
		}
		
		$pdf->Output('I', "digital_checklist.pdf", false);
	}
	
	function updateissueAction()
	{
		$params = $this->_getAllParams();
		$checklistTable = $this->loadModel('checklist');
		$checklistTable->updateFieldChecklistItemByItemId($params['item_id'], 'issue_id', $params['issue_id']);
	}
	
	function uploadimagehodAction()
	{
		set_time_limit(7200);
		$params = $this->_getAllParams();
		
		$magickPath = "/usr/bin/convert";
		
		
		$file = $_FILES['picture'];
		if ($file["error"] > 0) {
			$this->view->msg = "Uploading image failed, please try again";
			$this->renderTemplate('index.tpl');
		}
		else {					
			$datafolder = $this->config->paths->html."images/checklist/".date("Ym");
			if(!is_dir($datafolder)) mkdir($datafolder, 0777, true);
			
			$origFileName = $file["name"];
			$temp = explode(".", $origFileName);
			$ext = $temp[count($temp)-1];
			$ext = strtolower($ext);
				
			$curDate = 	date("Ymd_his")."_".$this->site_id."_".$params['checklist_item_id'];
			$fileName = $curDate.'.'.$ext;
			
			$logsTable = $this->loadModel('logs');

			if (move_uploaded_file($file["tmp_name"],$datafolder."/".$fileName)){
				$params['picture'] = $fileName;
				$checklistTable = $this->loadModel('checklist');
				$checklistTable->updateFieldChecklistItemByItemId($params['checklist_item_id'], 'hod_image_update', "/images/checklist/".date("Ym")."/".$fileName);
				$checklistTable->updateFieldChecklistItemByItemId($params['checklist_item_id'], 'hod_image_update_date', date("Y-m-d H:i:s"));
			
				$new_file_thumb = $curDate."_thumb.".$ext;
				$new_file_large = $curDate."_large.".$ext;
			
				/*** create thumbnail image ***/
				$label = "isort.id ".date("d/m/Y")." ".date("H:i");
//exec($magickPath . ' ' . $datafolder."/".$fileName . " -resize 3% -pointsize 12 -fill '#9e824b' -gravity NorthEast -draw \"text 100,100 'isort.id' \" " . $datafolder."/".$new_file_thumb);
exec($magickPath . ' ' . $datafolder."/".$fileName . " -resize 100x100 " . $datafolder."/".$new_file_thumb);
				/*** create medium image ***/
//exec($magickPath . ' ' . $datafolder."/".$fileName . " -resize 20% -pointsize 30  -fill '#9e824b' -gravity East -draw \"text 100,100 'isort.id' \" " . $datafolder."/".$new_file_large);
exec($magickPath . ' ' . $datafolder."/".$fileName . " -resize 800x800 " . $datafolder."/".$new_file_large);
				
				//imagedestroy($datafolder."/".$fileName);
				unlink($datafolder."/".$fileName);

				$logData['user_id'] = intval($this->ident['user_id']);
				$logData['action'] = "Upload Image Checklist HOD";
				$logData['data'] = json_encode($allParams);
				$logsTable->insertLogs($logData);
				
				$this->view->msg = "Image successfully uploaded";

				echo "/images/checklist/".date("Ym")."/".$new_file_thumb;
			}	
			else {
				$logData['user_id'] = intval($this->ident['user_id']);
				$logData['action'] = "Uploading Image Checklist HOD Failed";
				$logData['data'] = json_encode($params);
				$logsTable->insertLogs($logData);

				echo "0";	
			}
		}
	}
}
?>