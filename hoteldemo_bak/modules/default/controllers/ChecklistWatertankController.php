<?php
require_once('actionControllerBase.php');
class ChecklistWatertankController extends actionControllerBase
{
	function addAction() {
		
		{
			$params = $this->_getAllParams();
			
			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "Add Checklist";
			$logData['data'] = json_encode($param);
			$logsTable->insertLogs($logData);
			
			$this->view->title = "Add Watertank Checklist";
			
			
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
			
			$this->renderTemplate('form_checklist_watertank.tpl');	
		}
	}
	
	function registerwater2Action()
	{
		$this->view->title = "Cheklist Water Tank";
		if ($this->getRequest()->isPost())
		{
			//Zend_Loader::loadClass('Zend_Filter_StripTags');
			//$filter = new Zend_Filter_StripTags();
			$request = $this->getRequest();
			
			$txtsiteid = $this->site_id;;
			$txtshift = $request->getPost('shift');		
			$txttempcool = $request->getPost('tempcool');
			$txttemphot = $request->getPost('temphot');
			$txtvol1 = $request->getPost('vol1');
			$txtvol2 = $request->getPost('vol2');
			$txtph = $request->getPost('ph');
			$txtcl = $request->getPost('cl');
			$txtpsu = $request->getPost('psu');
			$txtpss1 = $request->getPost('pss1');
			$txtpss2 = $request->getPost('pss2');
			$txtpss3 = $request->getPost('pss3');
			$txtpsk = $request->getPost('psk');
			$txtgenzet1 = $request->getPost('genzet1');
			$txtgenzet2 = $request->getPost('genzet2');
			$txtfuel1 = $request->getPost('fuel1');
			$txtfuel2 = $request->getPost('fuel2');
			$txtfu = intval($this->ident['user_id']);
			$txtremark = $request->getPost('remark');
			$date = date('Y-m-d H:i:s');
			$currentDate = new \DateTime();
			$tanggal=$currentDate->format('Y-m-d');
			$checklist = $this->loadModel('checklistwatertank');
			$dataExists = $checklist->getdatechecklist($txtshift,$tanggal);
			
		if ($dataExists){
			//echo "<script>// Display an alert using window.alert()
			//window.alert('Simpan Gagal , anda sudah input shift $txtshift untuk hari ini. Redirecting...');
	
			// Redirect after a delay
			//setTimeout(function() {
			//	window.location.href = 'http://cmms.hotel.localhost/default/checklistwatertank/view'; // Replace with your desired URL
			//}, 1800); // Delay in milliseconds (2 seconds)</script>";
			$this->_response->setRedirect($this->baseUrl."/default/checklistwatertank/add/err/1");
			$this->_response->sendResponse();
			exit();
		}else{
			
			$data = array(
			'site_id' => $txtsiteid,
			'shift' => $txtshift,
			'temper_cool' => $txttempcool,
			'temper_hot' => $txttemphot,
			'vol_1' => $txtvol1,
			'vol_2' => $txtvol2,
			'ph' => $txtph,
			'cl' => $txtcl,
			'sampit_utara' => $txtpsu,
			'sampit_sel1' => $txtpss1,
			'sampit_sel2' => $txtpss2,
			'sampit_sel3' => $txtpss3,
			'sampit_kitchen' => $txtpsk,
			'genzet_1' => $txtgenzet1,
			'genzet_2' => $txtgenzet2,
			'fuel_1' => $txtfuel1,
			'fuel_2' => $txtfuel2,
			'fu' => $txtfu,
			'remarks' =>$txtremark,
			'created' => $date,
			);
			//echo "<pre>";
			//var_dump($data);
			//exit();
			//echo "</pre>";
			$checklist = $this->loadModel('checklistwatertank');
			$checklist->registerwater3($data);
			$this->_redirect('/default/checklistwatertank/view');
			return;
			}
		}
	}


	function updatechecklistwatertankAction()
	{
		$this->view->title = "Update Cheklist Water Tank";
		if ($this->getRequest()->isPost())
		{
			//Zend_Loader::loadClass('Zend_Filter_StripTags');
			//$filter = new Zend_Filter_StripTags();
			$request = $this->getRequest();
			
			$txtsiteid = $this->site_id;;
			$txtshift = $request->getPost('shift');
			$checklistwater = $request->getPost('checklistwater');	
			$txttempcool = $request->getPost('tempcool');
			$txttemphot = $request->getPost('temphot');
			$txtvol1 = $request->getPost('vol1');
			$txtvol2 = $request->getPost('vol2');
			$txtph = $request->getPost('ph');
			$txtcl = $request->getPost('cl');
			$txtpsu = $request->getPost('psu');
			$txtpss1 = $request->getPost('pss1');
			$txtpss2 = $request->getPost('pss2');
			$txtpss3 = $request->getPost('pss3');
			$txtpsk = $request->getPost('psk');
			$txtgenzet1 = $request->getPost('genzet1');
			$txtgenzet2 = $request->getPost('genzet2');
			$txtfuel1 = $request->getPost('fuel1');
			$txtfuel2 = $request->getPost('fuel2');
			$txtfu = intval($this->ident['user_id']);
			$txtremark = $request->getPost('remark');
			$date = date('Y-m-d H:i:s');
			
			
			
			$data = array(
			'site_id' => $txtsiteid,
			'shift' => $txtshift,
			'temper_cool' => $txttempcool,
			'temper_hot' => $txttemphot,
			'vol_1' => $txtvol1,
			'vol_2' => $txtvol2,
			'ph' => $txtph,
			'cl' => $txtcl,
			'sampit_utara' => $txtpsu,
			'sampit_sel1' => $txtpss1,
			'sampit_sel2' => $txtpss2,
			'sampit_sel3' => $txtpss3,
			'sampit_kitchen' => $txtpsk,
			'genzet_1' => $txtgenzet1,
			'genzet_2' => $txtgenzet2,
			'fuel_1' => $txtfuel1,
			'fuel_2' => $txtfuel2,
			'fu' => $txtfu,
			'remarks' =>$txtremark,
			'created' => $date,
			);
			//echo "<pre>";
			//var_dump($data);
			//exit();
			//echo "</pre>";
			$checklist = $this->loadModel('checklistwatertank');
			$checklist->updatechecklist2($data,$checklistwater);
			$this->_redirect('/default/checklistwatertank/view');
			return;
		}
	}

	public function viewAction() {
		$params = $this->_getAllParams();
		
		$checklistTable = $this->loadModel('checklistwatertank');

		if(empty($params['start'])) $params['start'] = '0';
		$params['pagesize'] = 10;
		$this->view->start = $params['start'];
		$checklist = $checklistTable->getChecklist($params);
		foreach($checklist as &$ch)
		{
			$date = explode(" ", $ch['created']);
			$arr_date = explode("-",$date[0]);
			$ch['created_2'] = date("j F Y",mktime(0, 0, 0, $arr_date[1], $arr_date[2], $arr_date[0]));
			$ch['comments'] = $checklistTable->getCommentsByChecklistId($ch['checklist_watertank_id'], '3');
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
				$this->view->firstPageUrl = "/default/checklistwatertank/view".$addUrl;
				$this->view->prevUrl = "/default/checklistwatertank/view/start/".($params['start']-$params['pagesize']).$addUrl;
			}
			if($params['start'] < (floor(($totalReport['total']-1)/10)*10))
			{
				$this->view->nextUrl = "/default/checklistwatertank/view/start/".($params['start']+$params['pagesize']).$addUrl;
				$this->view->lastPageUrl = "/default/checklistwatertank/view/start/".(floor(($totalReport['total']-1)/10)*10).$addUrl;
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
		$logData['action'] = "View Checklist Watertank List";
		$logData['data'] = json_encode($params);
		$logsTable->insertLogs($logData);	

		$this->renderTemplate('view_checklist_watertank.tpl');  
	}

	public function viewmonthlyAction() {
		$params = $this->_getAllParams();
		
		$checklistTable = $this->loadModel('checklistwatertank');

		if(empty($params['start'])) $params['start'] = '0';
		$params['pagesize'] = 10;
		$this->view->start = $params['start'];
		$checklist = $checklistTable->getChecklistbulan($params);
		foreach($checklist as &$ch)
		{
			$date = explode(" ", $ch['created']);
			$arr_date = explode("-",$date[0]);
			$ch['created_2'] = date("F Y",mktime(0, 0, 0, $arr_date[1], $arr_date[2], $arr_date[0]));
			$ch['created_3'] = date("m",mktime(0, 0, 0, $arr_date[1], $arr_date[2], $arr_date[0]));
			//$ch['comments'] = $checklistTable->getCommentsByChecklistId($ch['checklist_watertank_id'], '3');
		}
		$this->view->checklist = $checklist;

		if(!empty($params['room_no']))
		{
			$addUrl = "/room_no/".$params['room_no'];
		}
		
		$totalReport = $checklistTable->getTotalChecklistbulan2($params);
		if($totalReport['total'] > 10)
		{
			if($params['start'] >= 10)
			{
				$this->view->firstPageUrl = "/default/checklistwatertank/viewmonthly".$addUrl;
				$this->view->prevUrl = "/default/checklistwatertank/viewmonthly/start/".($params['start']-$params['pagesize']).$addUrl;
			}
			if($params['start'] < (floor(($totalReport['total']-1)/10)*10))
			{
				$this->view->nextUrl = "/default/checklistwatertank/viewmonthly/start/".($params['start']+$params['pagesize']).$addUrl;
				$this->view->lastPageUrl = "/default/checklistwatertank/viewmonthly/start/".(floor(($totalReport['total']-1)/10)*10).$addUrl;
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
		$logData['action'] = "View Monthly Checklist Watertank List";
		$logData['data'] = json_encode($params);
		$logsTable->insertLogs($logData);	

		$this->renderTemplate('view_monthly_checklist_watertank.tpl');  
	}

	public function viewdetailmonthlyAction() {
		$params = $this->_getAllParams();
		
		$checklistTable = $this->loadModel('checklistwatertank');

		if(empty($params['start'])) $params['start'] = '0';
		$params['pagesize'] = 40;
		$this->view->start = $params['start'];
		$checklist = $checklistTable->getChecklistdetailbulan($params);
		foreach($checklist as &$ch)
		{
			$date = explode(" ", $ch['created']);
			$arr_date = explode("-",$date[0]);
			$ch['created_2'] = date("j F Y",mktime(0, 0, 0, $arr_date[1], $arr_date[2], $arr_date[0]));
			//$ch['comments'] = $checklistTable->getCommentsByChecklistId($ch['checklist_watertank_id'], '3');
		}
		$this->view->checklist = $checklist;

		if(!empty($params['room_no']))
		{
			$addUrl = "/room_no/".$params['room_no'];
		}
		
		$totalReport = $checklistTable->getTotalChecklistbulan($params);
		if($totalReport['total'] > 40)
		{
			if($params['start'] >= 40)
			{
				$this->view->firstPageUrl = "/default/checklistwatertank/viewdetailmonthly".$addUrl;
				$this->view->prevUrl = "/default/checklistwatertank/viewdetailmonthly/start/".($params['start']-$params['pagesize']).$addUrl;
			}
			if($params['start'] < (floor(($totalReport['total']-1)/10)*10))
			{
				$this->view->nextUrl = "/default/checklistwatertank/viewdetailmonthly/start/".($params['start']+$params['pagesize']).$addUrl;
				$this->view->lastPageUrl = "/default/checklistwatertank/viewdetailmonthly/start/".(floor(($totalReport['total']-1)/10)*10).$addUrl;
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
		$logData['action'] = "View Checklist Watertank List";
		$logData['data'] = json_encode($params);
		$logsTable->insertLogs($logData);

		$this->renderTemplate('view_detail_watertank_monthly.tpl'); 
		
	}


	public function viewdetailAction() {
		if($this->showDigitalChecklist)
		{

			$params = $this->_getAllParams();
			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "View Watertank Checklist Detail";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);	

			$checklist = $this->loadModel('checklistwatertank');			
			$this->view->checklist = $dChecklist = $checklist->getChecklistById($params['is']);
			//$template = $checklist->getTemplatesById($dChecklist['template_id']);
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
			// $date1 = explode(" ",$items[0]['save_date_staff']);
			// $this->view->date1 = date("j M Y", strtotime($date1[0]));
			// $this->view->user_staff = $allUsers[$items[0]['user_staff']];
			// $this->view->user_spv = $allUsers[$items[0]['user_spv']];
			// if(!empty($items[0]['save_date_staff2']))
			// {
			// 	$date2 = explode(" ",$items[0]['save_date_staff2']);
			// 	$this->view->date2 = date("j M Y", strtotime($date2[0]));
			// 	$this->view->user_staff2 = $allUsers[$items[0]['user_staff2']];
			// 	$this->view->user_spv2 = $allUsers[$items[0]['user_spv2']];
			// }
			// if(!empty($items[0]['save_date_staff3']))
			// {
			// 	$date3 = explode(" ",$items[0]['save_date_staff3']);
			// 	$this->view->date3 = date("j M Y", strtotime($date3[0]));
			// 	$this->view->user_staff3 = $allUsers[$items[0]['user_staff3']];
			// 	$this->view->user_spv3 = $allUsers[$items[0]['user_spv3']];
			// }
			$this->renderTemplate('view_detail_watertank.tpl'); 
		}
		else {
			$this->_response->setRedirect($this->baseUrl);
			$this->_response->sendResponse();
			exit();
		}
	}

	public function viewupdatedetailAction() {
		if($this->showDigitalChecklist)
		{
			$this->view->title = "Update Watertank Checklist";
			$params = $this->_getAllParams();
			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "View Watertank Checklist Detail";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);	

			$checklist = $this->loadModel('checklistwatertank');			
			$this->view->checklist = $dChecklist = $checklist->getChecklistById($params['id']);
			//$template = $checklist->getTemplatesById($dChecklist['template_id']);
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
			// $date1 = explode(" ",$items[0]['save_date_staff']);
			// $this->view->date1 = date("j M Y", strtotime($date1[0]));
			// $this->view->user_staff = $allUsers[$items[0]['user_staff']];
			// $this->view->user_spv = $allUsers[$items[0]['user_spv']];
			// if(!empty($items[0]['save_date_staff2']))
			// {
			// 	$date2 = explode(" ",$items[0]['save_date_staff2']);
			// 	$this->view->date2 = date("j M Y", strtotime($date2[0]));
			// 	$this->view->user_staff2 = $allUsers[$items[0]['user_staff2']];
			// 	$this->view->user_spv2 = $allUsers[$items[0]['user_spv2']];
			// }
			// if(!empty($items[0]['save_date_staff3']))
			// {
			// 	$date3 = explode(" ",$items[0]['save_date_staff3']);
			// 	$this->view->date3 = date("j M Y", strtotime($date3[0]));
			// 	$this->view->user_staff3 = $allUsers[$items[0]['user_staff3']];
			// 	$this->view->user_spv3 = $allUsers[$items[0]['user_spv3']];
			// }
			$this->renderTemplate('view_update_detail_watertank.tpl'); 
		}
		else {
			$this->_response->setRedirect($this->baseUrl);
			$this->_response->sendResponse();
			exit();
		}
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
			
			$this->view->title = "Edit Checklist Watertank";
			
			
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

	function getcommentsbychecklistidAction()
	{
		$params = $this->_getAllParams();
		$this->view->ident = $this->ident;
		$category = $this->loadModel('category');
		$checklistTable = $this->loadModel('checklistwatertank');
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

	public function addcommentAction() 
	{
			$params = $this->_getAllParams();
			$params['user_id'] = $this->ident['user_id'];
			$checklistTable = $this->loadModel('checklistwatertank');

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
			$logData['action'] = "Add Checklist Watertank Comment";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);	

			echo $params['filename'];
	}

	public function exportAction()
    {
		$params = $this->_getAllParams();
		
		$checklistTable = $this->loadModel('checklistwatertank');
		//require_once('phpspreadsheet/src/PhpSpreadsheet/IOFactory.php');
		require_once('PHPExcel/PHPExcel.php');

       // Create a new PHPExcel object
	   $objPHPExcel = new PHPExcel();

	   // Create a new worksheet
	   $objPHPExcel->setActiveSheetIndex(0);
	   $sheet = $objPHPExcel->getActiveSheet();

	   // Judul
		$sheet->setCellValue('A1', 'Date / Day');
		//$sheet->mergeCells('A1:D1'); // Menggabungkan sel untuk judul
		$sheet->mergeCells('A1:A2'); // Menggabungkan sel untuk judul
		$style = $sheet->getStyle('A1'); // Mengambil style untuk seluruh kolom C
		$style->getFont()->setBold(true); // Mengatur teks pada kolom C menjadi tebal
		$style->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Mengatur teks pada kolom C menjadi rata tengah
		$style->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER); // Mengatur teks pada kolom C menjadi rata tengah
		$sheet->setCellValue('B1', 'Location');
		$sheet->mergeCells('B1:B2'); // Menggabungkan sel untuk judul
		$style = $sheet->getStyle('B1'); // Mengambil style untuk seluruh kolom C
		$style->getFont()->setBold(true); // Mengatur teks pada kolom C menjadi tebal
		$style->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Mengatur teks pada kolom C menjadi rata tengah
		$style->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER); // Mengatur teks pada kolom C menjadi rata tengah
		
		$sheet->setCellValue('C1', 'Temperature (°C)');
		$sheet->mergeCells('C1:D1'); // Menggabungkan sel untuk judul
		$style = $sheet->getStyle('C1'); // Mengambil style untuk seluruh kolom C
		$style->getFont()->setBold(true); // Mengatur teks pada kolom C menjadi tebal
		$style->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Mengatur teks pada kolom C menjadi rata tengah
		$sheet->setCellValue('E1', 'Volume (%)');
		$sheet->mergeCells('E1:F1'); // Menggabungkan sel untuk judul
		$style = $sheet->getStyle('E1'); // Mengambil style untuk seluruh kolom C
		$style->getFont()->setBold(true); // Mengatur teks pada kolom C menjadi tebal
		$style->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Mengatur teks pada kolom C menjadi rata tengah
		$sheet->setCellValue('G1', 'Location');
		$sheet->mergeCells('G1:G2'); // Menggabungkan sel untuk judul
		$style = $sheet->getStyle('G1'); // Mengambil style untuk seluruh kolom C
		$style->getFont()->setBold(true); // Mengatur teks pada kolom C menjadi tebal
		$style->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Mengatur teks pada kolom C menjadi rata tengah
		$style->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER); // Mengatur teks pada kolom C menjadi rata tengah
		
		$sheet->setCellValue('H1', 'PH');
		$sheet->mergeCells('H1:H2'); // Menggabungkan sel untuk judul
		$style = $sheet->getStyle('H1'); // Mengambil style untuk seluruh kolom C
		$style->getFont()->setBold(true); // Mengatur teks pada kolom C menjadi tebal
		$style->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Mengatur teks pada kolom C menjadi rata tengah
		$style->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER); // Mengatur teks pada kolom C menjadi rata tengah
		
		$sheet->setCellValue('I1', 'CL');
		$sheet->mergeCells('I1:I2'); // Menggabungkan sel untuk judul
		$style = $sheet->getStyle('I1'); // Mengambil style untuk seluruh kolom C
		$style->getFont()->setBold(true); // Mengatur teks pada kolom C menjadi tebal
		$style->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Mengatur teks pada kolom C menjadi rata tengah
		$style->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER); // Mengatur teks pada kolom C menjadi rata tengah
		
		$sheet->setCellValue('J1', 'P Sumpit Utara');
		$sheet->mergeCells('J1:J2'); // Menggabungkan sel untuk judul
		$style = $sheet->getStyle('J1'); // Mengambil style untuk seluruh kolom C
		$style->getFont()->setBold(true); // Mengatur teks pada kolom C menjadi tebal
		$style->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Mengatur teks pada kolom C menjadi rata tengah
		$style->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER); // Mengatur teks pada kolom C menjadi rata tengah
		
		$sheet->setCellValue('K1', 'P Sumpit Selatan');
		$sheet->mergeCells('K1:M1'); // Menggabungkan sel untuk judul
		$style = $sheet->getStyle('K1'); // Mengambil style untuk seluruh kolom C
		$style->getFont()->setBold(true); // Mengatur teks pada kolom C menjadi tebal
		$style->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Mengatur teks pada kolom C menjadi rata tengah
		$sheet->setCellValue('N1', 'Sumpit Kitchen');
		$sheet->mergeCells('N1:N2'); // Menggabungkan sel untuk judul
		$style = $sheet->getStyle('N1'); // Mengambil style untuk seluruh kolom C
		$style->getFont()->setBold(true); // Mengatur teks pada kolom C menjadi tebal
		$style->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Mengatur teks pada kolom C menjadi rata tengah
		$style->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER); // Mengatur teks pada kolom C menjadi rata tengah
		
		$sheet->setCellValue('O1', 'Genset');
		$sheet->mergeCells('O1:P1'); // Menggabungkan sel untuk judul
		$style = $sheet->getStyle('O1'); // Mengambil style untuk seluruh kolom C
		$style->getFont()->setBold(true); // Mengatur teks pada kolom C menjadi tebal
		$style->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Mengatur teks pada kolom C menjadi rata tengah
		$sheet->setCellValue('Q1', 'Fuel (%)');
		$sheet->mergeCells('Q1:R1'); // Menggabungkan sel untuk judul
		$style = $sheet->getStyle('Q1'); // Mengambil style untuk seluruh kolom C
		$style->getFont()->setBold(true); // Mengatur teks pada kolom C menjadi tebal
		$style->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Mengatur teks pada kolom C menjadi rata tengah
		$sheet->setCellValue('S1', 'Remarks');
		$sheet->mergeCells('S1:S2'); // Menggabungkan sel untuk judul
		$style = $sheet->getStyle('S1'); // Mengambil style untuk seluruh kolom C
		$style->getFont()->setBold(true); // Mengatur teks pada kolom C menjadi tebal
		$style->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Mengatur teks pada kolom C menjadi rata tengah
		$style->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER); // Mengatur teks pada kolom C menjadi rata tengah
		
		$sheet->setCellValue('T1', 'Follow Up');
		$sheet->mergeCells('T1:T2'); // Menggabungkan sel untuk judul
		$style = $sheet->getStyle('T1'); // Mengambil style untuk seluruh kolom C
		$style->getFont()->setBold(true); // Mengatur teks pada kolom C menjadi tebal
		$style->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Mengatur teks pada kolom C menjadi rata tengah
		$style->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER); // Mengatur teks pada kolom C menjadi rata tengah
		
		// Subjudul
		$sheet->setCellValue('C2', 'Cool');
		$style = $sheet->getStyle('C2'); // Mengambil style untuk seluruh kolom C
		$style->getFont()->setBold(true); // Mengatur teks pada kolom C menjadi tebal
		$style->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Mengatur teks pada kolom C menjadi rata tengah
		$sheet->setCellValue('D2', 'Hot');
		$style = $sheet->getStyle('D2'); // Mengambil style untuk seluruh kolom C
		$style->getFont()->setBold(true); // Mengatur teks pada kolom C menjadi tebal
		$style->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Mengatur teks pada kolom C menjadi rata tengah
		$sheet->setCellValue('E2', '1');
		$style = $sheet->getStyle('E2'); // Mengambil style untuk seluruh kolom C
		$style->getFont()->setBold(true); // Mengatur teks pada kolom C menjadi tebal
		$style->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Mengatur teks pada kolom C menjadi rata tengah
		$sheet->setCellValue('F2', '2');
		$style = $sheet->getStyle('F2'); // Mengambil style untuk seluruh kolom C
		$style->getFont()->setBold(true); // Mengatur teks pada kolom C menjadi tebal
		$style->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Mengatur teks pada kolom C menjadi rata tengah
		
		$sheet->setCellValue('K2', '1');
		$style = $sheet->getStyle('K2'); // Mengambil style untuk seluruh kolom C
		$style->getFont()->setBold(true); // Mengatur teks pada kolom C menjadi tebal
		$style->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Mengatur teks pada kolom C menjadi rata tengah
		
		$sheet->setCellValue('L2', '2');
		$style = $sheet->getStyle('L2'); // Mengambil style untuk seluruh kolom C
		$style->getFont()->setBold(true); // Mengatur teks pada kolom C menjadi tebal
		$style->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Mengatur teks pada kolom C menjadi rata tengah
		
		$sheet->setCellValue('M2', '3');
		$style = $sheet->getStyle('M2'); // Mengambil style untuk seluruh kolom C
		$style->getFont()->setBold(true); // Mengatur teks pada kolom C menjadi tebal
		$style->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Mengatur teks pada kolom C menjadi rata tengah
		
		$sheet->setCellValue('O2', '1');
		$style = $sheet->getStyle('O2'); // Mengambil style untuk seluruh kolom C
		$style->getFont()->setBold(true); // Mengatur teks pada kolom C menjadi tebal
		$style->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Mengatur teks pada kolom C menjadi rata tengah
		
		$sheet->setCellValue('P2', '2');
		$style = $sheet->getStyle('P2'); // Mengambil style untuk seluruh kolom C
		$style->getFont()->setBold(true); // Mengatur teks pada kolom C menjadi tebal
		$style->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Mengatur teks pada kolom C menjadi rata tengah
		
		$sheet->setCellValue('Q2', '1');
		$style = $sheet->getStyle('Q2'); // Mengambil style untuk seluruh kolom C
		$style->getFont()->setBold(true); // Mengatur teks pada kolom C menjadi tebal
		$style->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Mengatur teks pada kolom C menjadi rata tengah
		
		$sheet->setCellValue('R2', '2');
		$style = $sheet->getStyle('R2'); // Mengambil style untuk seluruh kolom C
		$style->getFont()->setBold(true); // Mengatur teks pada kolom C menjadi tebal
		$style->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Mengatur teks pada kolom C menjadi rata tengah
		
		//$sheet->setCellValue('A2', 'Tanggal: ' . date('Y-m-d'));
		//$sheet->mergeCells('A2:D2'); // Menggabungkan sel untuk subjudul

	   $checklist = $checklistTable->getChecklistdetailbulanexport($params);

	   $row = 3;
	   foreach ($checklist as $rowData) {
		   $col = 'A';
		   $col2 = 'B';
		   $col3 = 'G';
		   foreach ($rowData as $cellData) {
			$headerCell = $sheet->getCell('A1');
			   $sheet->setCellValue($col . $row, $cellData);
			   $sheet->setCellValue($col2 . $row, 'Pump Room');
			   $sheet->setCellValue($col3 . $row, 'Swimming Pool');
			   $col++;
		   }
		   $row++;
	   }

	   // Configure Excel headers
	   header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	   header('Content-Disposition: attachment;filename="data-export-watertank-checklist.xlsx"');
	   header('Cache-Control: max-age=0');

	   // Save the spreadsheet to output
	   $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	   $objWriter->save('php://output');

	   exit;
	}

}
?>