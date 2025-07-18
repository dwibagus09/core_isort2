<?php

require_once('actionControllerBase.php');
require_once('Zend/Json.php');

class HodController extends actionControllerBase
{
    public function dashboardAction() {
		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "View HOD Dashboard";
		$logData['data'] = "View HOD Dashboard";
		$logsTable->insertLogs($logData);	

		$this->renderTemplate('hod_dashboard.tpl'); 
	}
    
	public function addAction() {
		if($this->showAddHOD)
		{
			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "Add BOD Meeting";
			$logData['data'] = "Add BOD Meeting";
			$logsTable->insertLogs($logData);	

			$category = $this->loadModel('category');
			$this->view->categories = $category->getCategories();

            if($this->teacher) $this->view->title = "Add BOT Meeting MoM";
			else $this->view->title = "Add BOD Meeting MoM";

			$hodTable = $this->loadModel('hod');
			$prevHodMeeting = $hodTable->getPrevHodMeeting("");
			if(!empty($prevHodMeeting['hod_meeting_id'])) {
				$attendance = $hodTable->getAttendanceByHodMeetingId($prevHodMeeting['hod_meeting_id']);
				foreach($attendance as &$att)
				{
					unset($att['attendance_id']);
				}
				$this->view->attendance = $attendance;
			}

			$this->renderTemplate('form_hod_meeting.tpl'); 
		}
		else {
			$this->_response->setRedirect($this->baseUrl);
			$this->_response->sendResponse();
			exit();
		}
	}

	public function editAction() {
		if($this->showAddHOD)
		{
			$params = $this->_getAllParams();

			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "Edit HOD Meeting";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);	

			$category = $this->loadModel('category');
			$this->view->categories = $category->getCategories();

			$hodTable = $this->loadModel('hod');
			$hodMeeting = $hodTable->getHodMeetingById($params['id']);
			$hodMeetingDateTime = explode(" ", $hodMeeting['meeting_date']);
			$hodMeeting['tanggal'] =$hodMeetingDateTime[0];
			$this->view->hodMeeting = $hodMeeting;

			$this->view->attendance = $hodTable->getAttendanceByHodMeetingId($params['id']);

            if($this->teacher) $this->view->title = "Edit BOT Meeting MoM";
			else $this->view->title = "Edit BOD Meeting MoM";

			$this->renderTemplate('form_hod_meeting.tpl'); 
		}
		else {
			$this->_response->setRedirect($this->baseUrl);
			$this->_response->sendResponse();
			exit();
		}
	}

	public function saveAction() {
		if($this->showAddHOD)
		{
			$params = $this->_getAllParams();

			$params['user_id'] = $this->ident['user_id'];
			$hodTable = $this->loadModel('hod');
			$data['hod_meeting_id'] = $hodTable->saveHod($params);

			$i = 0;
			foreach($params['attendance_site_id'] as $site_id)
			{
				$data["attendance_id"] = $params["attendance_id"][$i];
				$data["category_id"] = 1;
				$data["site_id"] = $site_id;
				$data["attendance_name"] = $params["attendance_name"][$i];
				$hodTable->saveAttendance($data);
				$i++;
			}

			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "Save BOD Meeting";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);	

			$this->_response->setRedirect($this->baseUrl."/default/hod/hodmeetingform2/id/".$data['hod_meeting_id']);
			$this->_response->sendResponse();
			exit();
		}
		else {
			$this->_response->setRedirect($this->baseUrl);
			$this->_response->sendResponse();
			exit();
		}
	}

	public function hodmeetingform2Action() {
		if($this->showAddHOD)
		{
			$params = $this->_getAllParams();

			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "Open Form BOD Meeting 2";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);	

			$hodTable = $this->loadModel('hod');
			$hodMeeting = $hodTable->getHodMeetingById($params['id']);
			$hodMeetingDateTime = explode(" ", $hodMeeting['meeting_date']);
			$hodMeeting['tanggal_jam'] = date("l, j F Y", strtotime($hodMeetingDateTime[0]))." / ". $hodMeeting['meeting_time'];
			$hodMeeting['tanggal'] = date("j-M", strtotime($hodMeetingDateTime[0]));
			$this->view->hodMeeting = $hodMeeting;

			$this->view->attendance = $hodTable->getAttendanceByHodMeetingId($params['id']);
			
			$topic = $hodTable->getHodMeetingTopics($params['id']);
			foreach($topic as &$t)
			{
				if($t['start_date'] == "0000-00-00 00:00:00") $t['startdate'] = "";
				else {
					$startdate = explode(" ", $t['start_date']);
					$t['startdate'] = date("j M Y", strtotime($startdate[0]));
				} 

				if($t['finish_date'] == "0000-00-00 00:00:00") $t['finishdate'] = "";
				else {
					$finishdate = explode(" ", $t['finish_date']);
					$t['finishdate'] = date("j M Y", strtotime($finishdate[0]));
				} 

				if(!empty($t['filename']))
				{
					$t['images'] = $hodTable->getTopicImages($t['topic_id']);
				}
			}
			$this->view->topic = $topic;

			$category = $this->loadModel('category');
			$this->view->categories = $category->getCategories();

			$this->view->hod_meeting_id = $params['id'];

			$this->renderTemplate('form_hod_meeting2.tpl'); 
		}
		else {
			$this->_response->setRedirect($this->baseUrl);
			$this->_response->sendResponse();
			exit();
		}
	}

	public function savetopicAction() {
		if($this->showAddHOD)
		{
			$params = $this->_getAllParams();
			$params['user_id'] = $this->ident['user_id'];
			$hodTable = $this->loadModel('hod');
			$params["department_id"] = 1;
			$params['hod_meeting_topic_id'] = $topic_id = $hodTable->saveTopic($params);

			if(!empty($_FILES["topic_image"]))
			{
				$magickPath = "/usr/bin/convert";
				$datafolder = $this->config->paths->html."/images/hod_meeting/".date("Ym")."/";
				if(!is_dir($datafolder)) mkdir($datafolder, 0777, true);
				$i = 0;
				foreach($_FILES["topic_image"]['tmp_name'] as $tmpname)
				{
					$ext = explode(".",$_FILES["topic_image"]['name'][$i]);
					$filename = "topic_".$topic_id."_".date("YmdHis")."_".$i.".".$ext[count($ext)-1];
					if(move_uploaded_file($tmpname, $datafolder.$filename))
					{
						/*** convert to jpg ***/
						if(!in_array(strtolower($ext[count($ext)-1]), array("jpg"))) 
						{
							$newFilename =  "topic_".$topic_id."_".date("YmdHis")."_".$i.".jpg";
							exec($magickPath . ' ' . $datafolder.$filename . ' ' . $datafolder.$newFilename);
						}
						else  $newFilename = "topic_".$topic_id."_".date("YmdHis")."_".$i.".".$ext[count($ext)-1];

						$params['filename'] = "/".date("Ym")."/".$newFilename;
						$hodTable->saveTopicImage($params);
						
						/*** create thumbnail image ***/
						exec($magickPath . ' ' . $datafolder.$newFilename . ' -resize 128x128 ' . $datafolder."topic_".$topic_id."_".date("YmdHis")."_".$i."_thumb.jpg");
						/*** resize image if size greater than 500 Kb ***/
						if(filesize($datafolder.$newFilename) > 500000) exec($magickPath . ' ' . $datafolder.$newFilename . ' -resize 800x800\> ' . $datafolder.$newFilename);
					}
					$i++;
				}
			}

			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "Save BOD Meeting Topic";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);	

			$this->_response->setRedirect($this->baseUrl."/default/hod/hodmeetingform2/id/".$params['hod_meeting_id']);
			$this->_response->sendResponse();
			exit();
		}
		else {
			$this->_response->setRedirect($this->baseUrl);
			$this->_response->sendResponse();
			exit();
		}
	}

	public function viewAction() {
		$params = $this->_getAllParams();
		
		$hodTable = $this->loadModel('hod');

		if(empty($params['start'])) $params['start'] = '0';
		$params['pagesize'] = 10;
		$this->view->start = $params['start'];
		$hod = $hodTable->getHodMeetingMom($params);
		foreach($hod as &$h)
		{
			$date = explode(" ", $h['meeting_date']);
			$arr_date = explode("-",$date[0]);
			$h['day_date'] = date("l, j F Y", mktime(0, 0, 0, $arr_date[1], $arr_date[2], $arr_date[0]));

			$h['comments'] = $hodTable->getCommentsByHODMeetingId($h['hod_meeting_id'], '3');
		}
		$this->view->hodMeeting = $hod;	
		
		$totalReport = $hodTable->getTotalHodMom();
		if($totalReport['total'] > 10)
		{
			if($params['start'] >= 10)
			{
				$this->view->firstPageUrl = "/default/hod/view";
				$this->view->prevUrl = "/default/hod/view/start/".($params['start']-$params['pagesize']);
			}
			if($params['start'] < (floor(($totalReport['total']-1)/10)*10))
			{
				$this->view->nextUrl = "/default/hod/view/start/".($params['start']+$params['pagesize']);
				$this->view->lastPageUrl = "/default/hod/view/start/".(floor(($totalReport['total']-1)/10)*10);
			}
		}
		$this->view->curPage = ($params['start']/$params['pagesize'])+1;
		if($totalReport['total'] > 0)
			$totalPage = ceil($totalReport['total']/$params['pagesize']);
		else
			$totalPage = 1;
		$this->view->totalPage = $totalPage;
		if($totalReport['total'] > 0) $this->view->startRec = $params['start'] + 1;
		else $this->view->startRec = 0;
		$endRec = $params['start'] + $params['pagesize'];
		if($totalReport['total'] >=  $endRec) $this->view->endRec =  $endRec;
		else $this->view->endRec =  $totalReport['total'];		
		$this->view->totalRec = $totalReport['total'];

	
		$this->view->site_id = $this->site_id;

		$this->view->showHODMeetingAdmin = $this->showHODMeetingAdmin;

		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "View BOD Meeting MOM List";
		$logData['data'] = json_encode($params);
		$logsTable->insertLogs($logData);	

		$this->renderTemplate('view_hod_meeting.tpl');  
	}

	public function viewdetailAction() {
		if($this->showHODMeeting == 1 || $this->showHODMeetingAdmin == 1)
		{

			$params = $this->_getAllParams();
			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "View BOD Meeting MoM";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);	

			$hodTable = $this->loadModel('hod');
			$hodMeeting = $hodTable->getHodMeetingById($params['id']);
			$hodMeetingDateTime = explode(" ", $hodMeeting['meeting_date']);
			$hodMeeting['tanggal_jam'] = date("l, j F Y", strtotime($hodMeetingDateTime[0]))." / ".$hodMeeting['meeting_time'];
			$hodMeeting['tanggal'] = date("j-M", strtotime($hodMeetingDateTime[0]));
			$this->view->hodMeeting = $hodMeeting;
			$this->view->currentMeetingDate = $hodMeetingDateTime[0];

			if($this->showSiteSelection == 1)
			{
				$siteTable = $this->loadModel('site');
				if($hodMeeting['site_id'] != $this->ident['site_id'])
				{
					$siteTable->setSite($hodMeeting['site_id']);
					//$this->ident['site_id'] = $hodMeeting['site_id'];
					$this->_response->setRedirect($this->baseUrl."/default/hod/viewdetail/id/".$params['id']);
					$this->_response->sendResponse();
					exit();
				}
			}
			
			$this->view->attendance = $attendance = $hodTable->getAttendanceByHodMeetingId($params['id']);	
			$pic = array();
			$a=0;
			if(!empty($attendance))
			{
				foreach($attendance as $att)
				{
					$pic[$att['site_id']] .= "<br/>".$att['attendance_name'];
				}
			}
			$this->view->pic = $pic;

			/*$role_ids = implode(",",$this->ident['role_ids']);
			Zend_Loader::LoadClass('userClass', $this->modelDir);
			$userClass = new userClass();
			$cat_ids = $userClass->getCategoriesByRoles($role_ids);
			$dept_ids = "";
			if(!empty($cat_ids))
			{
				foreach($cat_ids as $ci)
				{
					if($ci['category_id'] > 0)
						$dept_ids .= $ci['category_id'].",";
					else
					{
						$dept_ids = "";
						break;
					}
				}
				$dept_ids = substr($dept_ids, 0, -1);
			}
			$this->view->dept_ids = $dept_ids;*/
			
			if(in_array($this->ident['role_id'], array('2','3')))
			    $this->view->site_ids = $site_ids = $this->ident['site_id'];
			
			
			/*$commentsTable = $this->loadModel('comments');

			Zend_Loader::LoadClass('issueClass', $this->modelDir);
			$issueClass = new issueClass();
			$issues = $issueClass->getHODIssues($dept_ids);
			foreach($issues as &$i)
			{
				$issue_date_time = explode(" ",$i['issue_date']);
				$i['date'] = date("j-M-Y", strtotime($issue_date_time[0]));

				$i['comments'] = $commentsTable->getCommentsByIssueId($i['issue_id'], '3');
			}
			$this->view->issues = $issues; 

			if($this->showFitOutOnGoing == 1 && $this->site_id < 4)
			{
				if($this->site_id == 1) $sitename = "GANDARIA CITY";
				elseif($this->site_id == 2) $sitename = "KOTA KASABLANKA";
				elseif($this->site_id == 3) $sitename = "PLAZA BLOK M";
				$prevHodMeeting = $hodTable->getPrevHodMeeting($hodMeetingDateTime[0]);
				if(!empty($prevHodMeeting))
				{
					$prevHodMeetingDateTime = explode(" ", $prevHodMeeting['meeting_date']);
					$prevHodMeetingDate = $prevHodMeetingDateTime[0];
				}
				else {
					$hod_meeting_date = explode("-", $hodMeetingDateTime[0]);
					$prevHodMeetingDate = date("Y-m-d", mktime(0, 0, 0, $hod_meeting_date[1]  , $hod_meeting_date[2]-7, $hod_meeting_date[0]));					
				}

				$ch = curl_init(); 
				curl_setopt($ch, CURLOPT_URL, "http://fom.pakuwon.com/views/ws/index.php?ACT=ongoing&D1=".$prevHodMeetingDate."&D2=".$hodMeetingDateTime[0]."&MALL=".urlencode($sitename)."&PIN="); 
				//return the transfer as a string 
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
				$output = curl_exec($ch); 
				curl_close($ch); 
				$this->view->fitOutOnGoing = json_decode($output, true);
			}

			if($this->showFitOutOnGoing == 1 && $this->site_id < 4)	$this->view->loadFitOutOnGoing = 1;*/

			$topic = $hodTable->getHodMeetingTopics($params['id'], $hodMeetingDateTime[0], $site_ids);
			if(!empty($topic))
			{
				foreach($topic as &$t)
				{
					/*if($t['start_date'] == "0000-00-00 00:00:00") $t['startdate'] = "";
					else {
						$startdate = explode(" ", $t['start_date']);
						$t['startdate'] = date("j M Y", strtotime($startdate[0]));
					} */

					if(!empty($t['topic_target_id']))
					{
						$topic_target_date = $hodTable->getTopicTargetDate($t['topic_id']);
						foreach($topic_target_date as $target_date)
						{
							$t['targetdate'] .= date("j M Y", strtotime($target_date['target_date'])).' <i class="fa fa-trash remove-target-date" data-id="'.$target_date['topic_target_id'].'" data-topicid="'.$t['topic_id'].'" style="cursor:pointer;"></i><br/>';
						}						
					}

					if(!empty($t['topic_start_id']))
					{
						$topic_start_date = $hodTable->getTopicStartDate($t['topic_id']);
						foreach($topic_start_date as $start_date)
						{
							$t['startdate'] .= date("j M Y", strtotime($start_date['start_date'])).' <i class="fa fa-trash remove-start-date" data-id="'.$start_date['topic_start_id'].'" data-topicid="'.$t['topic_id'].'" style="cursor:pointer;"></i><br/>';
						}	
					}

					if(empty($t['finish_date']) || $t['finish_date'] == "0000-00-00 00:00:00") $t['finishdate'] = $t['finish_date'] = "";
					else {
						$finishdate = explode(" ", $t['finish_date']);
						$t['finishdate'] = $finishdate[0];
						$t['finish_date'] = date("j M Y", strtotime($finishdate[0]));
					} 

					if(!empty($t['filename']))
					{
						$t['images'] = $hodTable->getTopicImages($t['topic_id']);
					}
				}
			}
			$this->view->topic = $topic;

			if($this->showHODMeetingAdmin == 1) $limitFollowUp = 1;
			else $limitFollowUp = 2;

			$prevHodMeetingFollowUp = $hodTable->getPrevHodMeetingFollowUp($params['id'], $hodMeetingDateTime[0], $dept_ids);
			if(!empty($prevHodMeetingFollowUp))
			{
				$prevFollowUpTopic = array();
				$prevTopicId = 0;
				foreach($prevHodMeetingFollowUp as $prevfu)
				{
					if($prevTopicId != $prevfu['topic_id'])
					{	
						$f = 0;		
						$prevTopicId = $prevfu['topic_id'];
					}
					if(!empty($prevfu['follow_up']))
					{	
						if($this->showHODMeetingAdmin == 1 && $prevfu['hod_meeting_id'] == $params['id'] && $hodMeeting['approved'] != 1)
						{
							$currentFollowUp = $hodTable->getCurrentFollowUp($prevfu['topic_id'], $params['id']);
							if(!empty($currentFollowUp))
							{								
								$curFollowUpTopic = "";
								$fu=0;
								foreach($currentFollowUp as $curfu)
								{
									if(!empty($curfu['follow_up']))
									{	
										
										$fuDateTime = explode(" ", $curfu['added_date']);
										$fuDate = date("j M Y", strtotime($fuDateTime[0]));
										if($fu==0) $curFollowUpTopic .= "<strong>".$fuDate.'</strong> <a id="edit-progress-'.$curfu['followup_id'].'" class="edit-progress" href="#progress-form" data-id="'.$curfu['followup_id'].'"><i class="fa fa-edit edit-progress-img" style="cursor:pointer;" data-id="'.$curfu['followup_id'].'"></i><br/>';
										$curFollowUpTopic .= nl2br($curfu['follow_up']);
										if(!empty($curfu['filename']))
										{
											$curFollowUpTopic .= '<br/>';
											$currentFollowUpImages = $hodTable->getFollowUpImages($curfu['followup_id']);
											foreach($currentFollowUpImages as $img)
											{
												$curFollowUpTopic .= '<a class="image-popup-vertical-fit" href="/images/hod_meeting'.$img['filename'].'"><img src="/images/hod_meeting'.str_replace(".", "_thumb.", $img['filename']).'" class="hod_thumb"></a> '; 
											}
										}
										$curFollowUpTopic .= "<br/><br/>";
									}
									$fu++;
								}
								$currentFollowUpTopic[$prevfu['topic_id']] = $curFollowUpTopic;
								$this->view->curFollowUpTopic = $currentFollowUpTopic;
							}
						}
						else
						{
							if($f < $limitFollowUp) {
								$fuDateTime = explode(" ", $prevfu['meeting_date']);
								$fuDate = date("j M Y", strtotime($fuDateTime[0]));
								$prevFollowUpTopic[$prevfu['topic_id']] .= "<strong>".$fuDate."</strong><br/>".nl2br($prevfu['follow_up']);								
								$prevFollowUpImages = array();
								$prevFollowUpImages = $hodTable->getFollowUpImages($prevfu['followup_id']);
								if(!empty($prevFollowUpImages))
								{
									$prevFollowUpTopic[$prevfu['topic_id']] .= '<br/>';
									foreach($prevFollowUpImages as $img)
									{
										$prevFollowUpTopic[$prevfu['topic_id']] .= '<a class="image-popup-vertical-fit" href="/images/hod_meeting'.$img['filename'].'"><img src="/images/hod_meeting'.str_replace(".", "_thumb.", $img['filename']).'" class="hod_thumb"></a> '; 
									}
								}
								$prevFollowUpTopic[$prevfu['topic_id']] .= "<br/><br/>";
								$f++;
							}
							else {
								if($f == $limitFollowUp)
									$prevFollowUpTopic[$prevfu['topic_id']] .= '<a class="view-more-link" href="#followup-form" data-id="'.$prevfu['topic_id'].'">View More...</a><br/>';
								$f++;
							}
						}						
					}
				}
				$this->view->prevFollowUpTopic = $prevFollowUpTopic;
			}
			
			
			$this->view->comments = $hodTable->getCommentsByHODMeetingId($params['id'], 0, 'asc');

			if($this->showHODMeetingAdmin == 1 && $hodMeeting['approved'] != 1) {
				$this->renderTemplate('view_hod_meeting_detail_admin.tpl');  
			} else {
				$this->view->approveHODMeeting = $this->approveHODMeeting;
				$this->renderTemplate('view_hod_meeting_detail.tpl'); 
			}
		}
		else {
			$this->_response->setRedirect($this->baseUrl);
			$this->_response->sendResponse();
			exit();
		}
	}

	public function gettopicbyidAction() {
		if($this->showAddHOD)
		{
			$params = $this->_getAllParams();

			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "Get BOD Meeting Topic By Id";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);	

			$hodTable = $this->loadModel('hod');
			$hodMeetingTopic = $hodTable->getHodMeetingTopicById($params['id']);
			$start_date = explode(" ", $hodMeetingTopic['start_date']);
			$hodMeetingTopic['startdate'] = $start_date[0];
			$finish_date = explode(" ", $hodMeetingTopic['finish_date']);
			$hodMeetingTopic['finishdate'] = $finish_date[0];
			$images = $hodTable->getTopicImages($hodMeetingTopic['topic_id']);
			if(!empty($images))
			{
				$imagelist = '<ul class="hod_image_list">';
				foreach($images as $image)
				{
					$imagelist .= '<li><a class="image-popup-vertical-fit" href="/images/hod_meeting'.$image['filename'].'"><img src="/images/hod_meeting'.str_replace(".", "_thumb.", $image['filename']).'" class="hod_thumb"></a>  <i class="fa fa-trash remove-image-db" data-id="'.$image['image_id'].'" style="cursor:pointer;"></i></li>';
				}
				$imagelist .= "</ul>";
				$hodMeetingTopic['imagelist'] = $imagelist;
			}
			echo json_encode($hodMeetingTopic);
		}
		else {
			$this->_response->setRedirect($this->baseUrl);
			$this->_response->sendResponse();
			exit();
		}
	}

	public function deletetopicAction() {
		if($this->showAddHOD)
		{
			$params = $this->_getAllParams();

			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "Delete BOD Meeting Topic By Id";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);	

			$hodTable = $this->loadModel('hod');
			$hodTable->deleteTopic($params['id']);
			
			$this->_response->setRedirect($this->baseUrl."/default/hod/hodmeetingform2/id/".$params['hodid']);
			$this->_response->sendResponse();
			exit();
		}
		else {
			$this->_response->setRedirect($this->baseUrl);
			$this->_response->sendResponse();
			exit();
		}
	}

	public function addtargetdateAction() {
		if($this->showAddHOD)
		{
			$params = $this->_getAllParams();
			$params['user_id'] = $this->ident['user_id'];
			$hodTable = $this->loadModel('hod');
			$hodTable->addTopicTargetDate($params);

			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "Add HOD Meeting Topic Target Date";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);	

			$target_dates = $hodTable->getTopicTargetDate($params['topic_id']);
			if(!empty($target_dates))
			{
				$tdate = "";
				foreach($target_dates as $date)
				{
					$tdate .= date("j M Y", strtotime($date['target_date'])).' <i class="fa fa-trash remove-target-date" data-id="'.$date['topic_target_id'].'" data-topicid="'.$params['topic_id'].'" style="cursor:pointer;"></i><br/>';
				}
				echo $tdate;
			}
		}
		else {
			$this->_response->setRedirect($this->baseUrl);
			$this->_response->sendResponse();
			exit();
		}
	}

	public function addstartdateAction() {
		if($this->showAddHOD)
		{
			$params = $this->_getAllParams();
			$params['user_id'] = $this->ident['user_id'];
			$hodTable = $this->loadModel('hod');
			$hodTable->addTopicStartDate($params);

			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "Add BOD Meeting Topic Start Date";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);	

			$start_dates = $hodTable->getTopicStartDate($params['topic_id']);
			if(!empty($start_dates))
			{
				$sdate = "";
				foreach($start_dates as $date)
				{
					$sdate .= date("j M Y", strtotime($date['start_date'])).' <i class="fa fa-trash remove-start-date" data-id="'.$date['topic_start_id'].'" data-topicid="'.$params['topic_id'].'" style="cursor:pointer;"></i><br/>';
				}
				echo $sdate;
			}
		}
		else {
			$this->_response->setRedirect($this->baseUrl);
			$this->_response->sendResponse();
			exit();
		}
	}

	public function savedetailadminAction() {
		if($this->showAddHOD)
		{
			$params = $this->_getAllParams();
			if(!empty($params['topic_id']))
			{
				$hodTable = $this->loadModel('hod');
				$i=0;
				foreach($params['topic_id'] as $topic_id)
				{
					$data['topic_id'] = $topic_id;
					$data['finish_date'] = $params['finish_date'][$i];
					$data['done'] = $params['done'][$i];
					if($data['done'] == '1') $data['done_hod_meeting_id'] = $params['hod_meeting_id'];
					else $data['done_hod_meeting_id'] = 0;
					$hodTable->updateFinishDate($data);
					if(!empty($params['followup'][$i]))
					{
						$data['followup'] = $params['followup'][$i];
						$data['user_id'] = $this->ident['user_id'];
						$data['followup_id'] = $params['followup_id'][$i];
						$data['hod_meeting_id'] = $params['hod_meeting_id'];
						if(empty($data['followup_id']))
						{
							$data['followup_id'] = $hodTable->checkIfFollowUpExist($data['topic_id'], $data['hod_meeting_id']);
						}
						$hodTable->saveFollowUp($data);
					}
					$i++;
				}
			}
			
			
			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "Save BOD Meeting Detail Admin";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);	

			$this->_response->setRedirect($this->baseUrl."/default/hod/viewdetail/id/".$params['hod_meeting_id']);
			$this->_response->sendResponse();
			exit();
		}
		else {
			$this->_response->setRedirect($this->baseUrl);
			$this->_response->sendResponse();
			exit();
		}
	}

	public function savedetailAction() {
		if($this->showHODMeeting)
		{
			$params = $this->_getAllParams();
			if(!empty($params['topic_id']))
			{
				$hodTable = $this->loadModel('hod');
				$i=0;
				foreach($params['topic_id'] as $topic_id)
				{
					$data['topic_id'] = $topic_id;
					$data['done_by_pic'] = $params['done_by_pic'][$i];
					if($data['done_by_pic'] == '1') $data['done_hod_meeting_id_pic'] = $params['hod_meeting_id'];
					else $data['done_hod_meeting_id_pic'] = 0;
					$hodTable->updateDoneByPic($data);
					$i++;
				}
			}			
			
			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "Save HOD Meeting Detail";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);	

			$this->_response->setRedirect($this->baseUrl."/default/hod/viewdetail/id/".$params['hod_meeting_id']);
			$this->_response->sendResponse();
			exit();
		}
		else {
			$this->_response->setRedirect($this->baseUrl);
			$this->_response->sendResponse();
			exit();
		}
	}

	public function approvemomAction() {
		if($this->approveHODMeeting)
		{
			$params = $this->_getAllParams();
			
			if(!empty($params['hod_meeting_id']))
			{
				$hodTable = $this->loadModel('hod');
			
				if(!empty($params['topic_id']))
				{
					$i=0;
					foreach($params['topic_id'] as $topic_id)
					{
						$data['topic_id'] = $topic_id;
						$data['done_by_pic'] = $params['done_by_pic'][$i];
						if($data['done_by_pic'] == '1') $data['done_hod_meeting_id_pic'] = $params['hod_meeting_id'];
						else $data['done_hod_meeting_id_pic'] = 0;
						$hodTable->updateDoneByPic($data);
						$i++;
					}
				}	
				$params['user_id'] = $this->ident['user_id'];
				$hodTable->approveMoM($params);

				$logsTable = $this->loadModel('logs');
				$logData['user_id'] = intval($this->ident['user_id']);
				$logData['action'] = "Approve HOD Meeting MoM";
				$logData['data'] = json_encode($params);
				$logsTable->insertLogs($logData);	

				/*$this->_response->setRedirect($this->baseUrl."/default/hod/viewdetail/id/".$params['hod_meeting_id']);
				$this->_response->sendResponse();
				exit();*/
			}		
		}
		else {
			$this->_response->setRedirect($this->baseUrl);
			$this->_response->sendResponse();
			exit();
		}
	}

	public function getfollowupAction() {
		if($this->showHODMeeting == 1 || $this->showHODMeetingAdmin == 1)
		{
			$params = $this->_getAllParams();
			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "Get Follow Up BOD";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);	

			$hodTable = $this->loadModel('hod');
			$hodMeetingFollowUp = $hodTable->getFollowUpByTopicId($params['id']);
			$followUpTopic="";
			if(!empty($hodMeetingFollowUp))
			{
				foreach($hodMeetingFollowUp as $fu)
				{
					if(!empty($fu['follow_up']))
					{	
						$fuDateTime = explode(" ", $fu['meeting_date']);
						$fuDate = date("j M Y", strtotime($fuDateTime[0]));
						$followUpTopic .= "<strong>".$fuDate."</strong><br/>".nl2br($fu['follow_up'])."<br/><br/>";					
					}
				}
			}
			echo $followUpTopic;
		}
		else {
			$this->_response->setRedirect($this->baseUrl);
			$this->_response->sendResponse();
			exit();
		}
	}

	public function deleteattendancebyidAction() {
		if($this->showAddHOD)
		{
			$params = $this->_getAllParams();

			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "Delete BOD Meeting Attendance";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);	

			$hodTable = $this->loadModel('hod');
			$hodTable->deleteAttendanceById($params['id']);
		}
		else {
			$this->_response->setRedirect($this->baseUrl);
			$this->_response->sendResponse();
			exit();
		}
	}


	public function exporttopdfAction() {	
		$params = $this->_getAllParams();

		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "Export BOD Meeting to PDF";
		$logData['data'] = json_encode($params);
		$logsTable->insertLogs($logData);	

		$hodTable = $this->loadModel('hod');
		$hodMeeting = $hodTable->getHodMeetingById($params['id']);
		$hodMeetingDateTime = explode(" ", $hodMeeting['meeting_date']);
		$hodMeeting['tanggal_jam'] = date("l, j F Y", strtotime($hodMeetingDateTime[0]))." / ".$hodMeeting['meeting_time'];
		$hodMeeting['tanggal'] = date("j-M", strtotime($hodMeetingDateTime[0]));
			
		$attendance = $hodTable->getAttendanceByHodMeetingId($params['id']);	
		$pic = array();
		foreach($attendance as $att)
		{
			$pic[$att['site_id']] .= "\n".$att['attendance_name'];
		}

		/*$role_ids = implode(",",$this->ident['role_ids']);
		Zend_Loader::LoadClass('userClass', $this->modelDir);
		$userClass = new userClass();
		$cat_ids = $userClass->getCategoriesByRoles($role_ids);
		$dept_ids = "";
		if(!empty($cat_ids))
		{
			foreach($cat_ids as $ci)
			{
				if($ci['category_id'] > 0)
					$dept_ids .= $ci['category_id'].",";
				else
				{
					$dept_ids = "";
					break;
				}
			}
			$dept_ids = substr($dept_ids, 0, -1);
		}*/
		
		$site_ids = $this->ident['site_id'];

		$commentsTable = $this->loadModel('comments');

		Zend_Loader::LoadClass('issueClass', $this->modelDir);
		$issueClass = new issueClass();
		$issues = $issueClass->getHODIssues($site_ids);

		/*if($this->showFitOutOnGoing == 1 && $this->site_id < 4)
		{
			if($this->site_id == 1) $sitename = "GANDARIA CITY";
			elseif($this->site_id == 2) $sitename = "KOTA KASABLANKA";
			elseif($this->site_id == 3) $sitename = "PLAZA BLOK M";
			$prevHodMeeting = $hodTable->getPrevHodMeeting($hodMeetingDateTime[0]);
			if(!empty($prevHodMeeting))
			{
				$prevHodMeetingDateTime = explode(" ", $prevHodMeeting['meeting_date']);
				$prevHodMeetingDate = $prevHodMeetingDateTime[0];
			}
			else {
				$hod_meeting_date = explode("-", $hodMeetingDateTime[0]);
				$prevHodMeetingDate = date("Y-m-d", mktime(0, 0, 0, $hod_meeting_date[1]  , $hod_meeting_date[2]-7, $hod_meeting_date[0]));					
			}

			$ch = curl_init(); 
			curl_setopt($ch, CURLOPT_URL, "http://fom.pakuwon.com/views/ws/index.php?ACT=ongoing&D1=".$prevHodMeetingDate."&D2=".$hodMeetingDateTime[0]."&MALL=".urlencode($sitename)."&PIN="); 
			//return the transfer as a string 
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
			$output = curl_exec($ch); 
			curl_close($ch); 
			$fitOutOnGoing = json_decode($output, true);
		}*/

		$topic = $hodTable->getHodMeetingTopics($params['id'], $hodMeetingDateTime[0], $dept_ids);
		if(!empty($topic))
		{
			foreach($topic as &$t)
			{
				if(!empty($t['topic_target_id']))
				{
					$topic_target_date = $hodTable->getTopicTargetDate($t['topic_id']);
					foreach($topic_target_date as $target_date)
					{
						$t['targetdate'] .= date("j M Y", strtotime($target_date['target_date']))."\n";
					}						
				}

				if(!empty($t['topic_start_id']))
				{
					$topic_start_date = $hodTable->getTopicStartDate($t['topic_id']);
					foreach($topic_start_date as $start_date)
					{
						$t['startdate'] .= date("j M Y", strtotime($start_date['start_date']))."\n";
					}	
				}

				if(empty($t['finish_date']) ||  $t['finish_date'] == "0000-00-00 00:00:00") $t['finishdate'] = $t['finish_date'] = "";
				else {
					$finishdate = explode(" ", $t['finish_date']);
					$t['finish_date'] = $finishdate[0];
					$t['finishdate'] = date("j M Y", strtotime($finishdate[0]));
				}
				
				if(!empty($t['filename']))
				{
					$t['images'] = $hodTable->getTopicImages($t['topic_id']);
				}
			}
		}

		$limitFollowUp = 2;

		$prevHodMeetingFollowUp = $hodTable->getPrevHodMeetingFollowUp($params['id'], $hodMeetingDateTime[0], $dept_ids);
		$prevHodFollowUp = array();
		foreach($prevHodMeetingFollowUp as $prevhodfu) {
			$prevHodFollowUp[$prevhodfu['topic_id']][]= $prevhodfu;
		}
		
		require_once('fpdf/mc_table.php');
		$pdf=new PDF_MC_Table();
		$pdf->AddPage();
		if($this->teacher) $pdf->SetTitle($this->ident['initial']." - BOT Meeting MoM");
		else $pdf->SetTitle($this->ident['initial']." - BOD Meeting MoM");
		$pdf->SetFont('Arial','B',15);
		if($this->teacher) $pdf->Write(10,$this->ident['initial']." - BOT Meeting MoM");
		else  $pdf->Write(10,$this->ident['initial']." - BOD Meeting MoM");
		$pdf->ln(10);

		$pdf->SetFont('Arial','B',8);
		//$pdf->Cell(50,6,'Nama Site',0,0,'L');
		//$pdf->Cell(138,6,$this->ident['site_fullname'],0,0,'L');
		//$pdf->Ln();
		$pdf->Cell(50,6,'Title',0,0,'L');
		$pdf->Cell(138,6,$hodMeeting['meeting_title'],0,0,'L');
		$pdf->Ln();
		$pdf->Cell(50,6,'Date / Time',0,0,'L');
		$pdf->Cell(138,6,$hodMeeting['tanggal_jam'],0,0,'L');
		$pdf->Ln();
		$pdf->Cell(50,6,'Attendance',0,0,'L');

		if(!empty($attendance))
		{
			$i = 1;
			foreach($attendance as $a) {
				$pdf->Cell(50,4,$a['attendance_name'],0,0,'L');
				if($i > 1 && $i % 3 == 0)
				{
					$pdf->Ln();
					$pdf->Cell(50,4,'',0,0,'L');
				}
				$i++;
			}
		}
		$pdf->Ln(9);

		if(!empty($issues))
		{
			$pdf->SetFont('Arial','B',9);
			$pdf->SetTextColor(0,0,0);
			$pdf->Write(10,"Opened Kaizen");
			$pdf->Ln();
			$pdf->SetFont('Arial','B',8);
			$pdf->SetFillColor(158,130,75);
			$pdf->SetTextColor(255,255,255);
			$pdf->Cell(20,7,'Site',1,0,'C',true);
			$pdf->Cell(45,7,'Kaizen',1,0,'C',true);
			$pdf->Cell(20,7,'Picture',1,0,'C',true);
			$pdf->Cell(35,7,'Location',1,0,'C',true);
			$pdf->Cell(20,7,'Date',1,0,'C',true);
			$pdf->Cell(50,7,'Comment',1,0,'C',true);
			$pdf->Ln();
			$pdf->SetFont('Arial','',7);
			$pdf->SetTextColor(0,0,0);
			$pdf->SetWidths(array(20, 45, 20, 35, 20, 50));	
			foreach($issues as $issue) { 	
				$issue_date_time = explode(" ",$issue['issue_date']);
				$issue['date'] = date("j-M-Y", strtotime($issue_date_time[0]));

				$issue['comments'] = $commentsTable->getCommentsByIssueId($issue['issue_id'], '3');

				$comments = "";
				if(!empty($issue['comments'])) { 
					foreach($issue['comments'] as $comment)
					{
						$comments .= $comment['name'].' : '.trim($comment['comment'])."\n";
						if(!empty($comment['filename'])) 
						{
							//$comments .= $comment['filename']."\n";							
						}
						$comments .= $comment['comment_date']."\n\n";
					}
				}
				$x1 = $pdf->GetY();
				$pdf->Row(array($issue['site_name'].$pic[$issue['site_id']],$issue['description'],"\n\n\n\n",$issue['location'],$issue['date'], $comments));
				$x2= $pdf->GetY();
				if($x2<$x1) $y = 11;
				else $y = $pdf->GetY()-($x2-$x1-1);		
				$issuedate = explode("-",$issue['issue_date']); 
				$issueImageURL = $url."images/issues/".$issuedate[0]."/";
				$issueImagePath = $this->config->paths->html.'/images/issues/'.$issuedate[0]."/";
				if(!empty($issue['picture']) && @getimagesize($issueImagePath.str_replace(".", "_thumb.", $issue['picture']))) {
					list($width, $height) = getimagesize($issueImagePath.str_replace(".", "_thumb.", $issue['picture']));
					if($width > $height)
					{
						$w = 18;
						$h = 0;
					}
					else {
						$w = 0;
						$h = 18;
					}
					$pdf->Image($issueImageURL.str_replace(".", "_thumb.", $issue['picture']),76,$y, $w, $h);
				}
			}
		}
		
		$pdf->Ln(10);

		if(!empty($fitOutOnGoing))
		{
			$pdf->SetFont('Arial','B',9);
			$pdf->SetTextColor(0,0,0);
			$pdf->Write(10,"Fit Out On Going");
			$pdf->Ln();
			$pdf->SetFont('Arial','B',8);
			$pdf->SetFillColor(158,130,75);
			$pdf->SetTextColor(255,255,255);
			$pdf->Cell(30,7,'Shop Name',1,0,'C',true);
			$pdf->Cell(40,7,'Detail',1,0,'C',true);
			$pdf->Cell(35,7,'FO PIC',1,0,'C',true);
			$pdf->Cell(85,7,'Progress',1,0,'C',true);
			$pdf->Ln();
			$pdf->SetFont('Arial','',7);
			$pdf->SetTextColor(0,0,0);
			$pdf->SetWidths(array(30, 40, 35, 85));	
			foreach($fitOutOnGoing as $fo) { 
				$fo_progress = "";
				if(!empty($fo['27'])) $fo_progress .= "Floor: ".$fo['27']."% - ".$fo['42']."\n\n";
				if(!empty($fo['28'])) $fo_progress .= "Wall: ".$fo['28']."% - ".$fo['43']."\n\n";
				if(!empty($fo['29'])) $fo_progress .= "Ceiling: ".$fo['29']."% - ".$fo['44']."\n\n";
				if(!empty($fo['30'])) $fo_progress .= "Shopfront: ".$fo['30']."% - ".$fo['45']."\n\n";
				if(!empty($fo['31'])) $fo_progress .= "Fixture: ".$fo['31']."% - ".$fo['46']."\n\n";
				if(!empty($fo['32'])) $fo_progress .= "Electrical: ".$fo['32']."% - ".$fo['47']."\n\n";
				if(!empty($fo['33'])) $fo_progress .= "Air Conditioning: ".$fo['33']."% - ".$fo['48']."\n\n";
				if(!empty($fo['34'])) $fo_progress .= "Exhaust: ".$fo['34']."% - ".$fo['49']."\n\n";
				if(!empty($fo['35'])) $fo_progress .= "Fresh Air: ".$fo['35']."% - ".$fo['50']."\n\n";
				if(!empty($fo['36'])) $fo_progress .= "Clean Water: ".$fo['36']."% - ".$fo['51']."\n\n";
				if(!empty($fo['37'])) $fo_progress .= "Waste Water: ".$fo['37']."% - ".$fo['52']."\n\n";
				if(!empty($fo['38'])) $fo_progress .= "Gas: ".$fo['38']."% - ".$fo['53']."\n\n";
				if(!empty($fo['39'])) $fo_progress .= "Sprinkler: ".$fo['39']."% - ".$fo['54']."\n\n";
				if(!empty($fo['40'])) $fo_progress .= "Fire Alarm: ".$fo['40']."% - ".$fo['55']."\n\n";
				if(!empty($fo['41'])) $fo_progress .= "Fire Suppression: ".$fo['41']."% - ".$fo['56']."\n\n";
				$pdf->Row(array($fo[6],"Floor: ".$fo[3]."\nUnit No: ".$fo[4]."\nUnit Type: ".$fo[5]."\nActual HO Date: ".$fo[7]."\nOpening Date: ".$fo[57]."\nPeriod: ".intval($fo[8])."Week(s)", $fo[10], $fo_progress));	
			}
		}
		$pdf->Ln(10);

		if(!empty($topic))
		{
			$pdf->SetFont('Arial','B',9);
			$pdf->SetTextColor(0,0,0);
			$pdf->Write(10,"Projects/Issues");
			$pdf->Ln();
			$pdf->SetFont('Arial','B',8);
			$pdf->SetFillColor(158,130,75);
			$pdf->SetTextColor(255,255,255);
			$pdf->Cell(20,7,'Site',1,0,'C',true);
			$pdf->Cell(60,7,'Projects / Issues',1,0,'C',true);
			$pdf->Cell(20,7,'Target Date',1,0,'C',true);
			$pdf->Cell(20,7,'Start Date',1,0,'C',true);			
			$pdf->Cell(20,7,'Finish Date',1,0,'C',true);
			$pdf->Cell(50,7,'Follow Up',1,0,'C',true);
			$pdf->Ln();
			$pdf->SetFont('Arial','',7);
			$pdf->SetTextColor(0,0,0);
			$pdf->SetWidths(array(20, 60, 20, 20, 20, 50));	

			$hodImagePath = $this->config->paths->html.'/images/hod_meeting';
			$hodImageURL = $url."images/hod_meeting";
			foreach($topic as $to) {
				$curX =  $pdf->GetX();
				$curY = $pdf->GetY();
				if($curY > 245) {
					$curY = 10;
					$pdf->AddPage();
				}
				if(!empty($to['filename']))
				{
					$to['images'] = $hodTable->getTopicImages($to['topic_id']);
					if(!empty($to['images']))
					{
						$totalLetter = strlen($to['topic']);
						$topicY = $curY + (ceil($totalLetter/52) * 5);
						$topicX = $curX+21;
						foreach($to['images'] as $image)
						{
							if (file_exists($hodImagePath.str_replace(".", "_thumb.", $image['filename']))) {
								list($width, $height) = getimagesize($hodImagePath.str_replace(".", "_thumb.", $image['filename']));
								if($width > $height)
								{
									$w = 15;
									$h = 0;
								}
								else {
									$w = 0;
									$h = 15;
								}
								$to['topic'] .= $pdf->Image($hodImageURL.str_replace(".", "_thumb.", $image['filename']), $topicX, $topicY, $w, $h)." ";
								$topicX = $topicX+18;
							}
						}		
						$to['topic'] .= "\n\n\n\n\n";				
					}
				}

				if(!empty($prevHodFollowUp[$to['topic_id']]))
				{
					$j = 0;
					$prevFollowUpTopic = "";
					$fuY = $fuX = 0;
					foreach($prevHodFollowUp[$to['topic_id']] as $prevfu) {
						if($j < 2)
						{
							$fuDateTime = explode(" ", $prevfu['meeting_date']);
							$fuDate = date("j M Y", strtotime($fuDateTime[0]));
							$prevFollowUpTopic .= $fuDate."\n".$prevfu['follow_up'];
							
							$totalLetter = strlen($prevfu['follow_up']);
							$fuY = $curY + (ceil($totalLetter/42) * 5) + 5;
							$fuX = $curX+141;

							$prevFollowUpTopicImages = $hodTable->getFollowUpImages($prevfu['followup_id']);
							if(!empty($prevFollowUpTopicImages))
							{
								foreach($prevFollowUpTopicImages as $image2) {
									if (file_exists($hodImagePath.str_replace(".", "_thumb.", $image2['filename']))) {
										list($width, $height) = getimagesize($hodImagePath.str_replace(".", "_thumb.", $image2['filename']));
										if($width > $height)
										{
											$w = 15;
											$h = 0;
										}
										else {
											$w = 0;
											$h = 15;
										}
										$prevFollowUpTopic .= $pdf->Image($hodImageURL.str_replace(".", "_thumb.", $image2['filename']), $fuX, $fuY, $w, $h)." ";
										$fuX = $fuX+18;
									}
								}
								$prevFollowUpTopic .= "\n\n";								
								$curY = $fuY + 15;
							}
							else {
								$curY = $fuY + 5;
							}
							$prevFollowUpTopic .= "\n\n";	
							$j++;
						}
					}
				}
				$pdf->Row(array($to['site_name'].$pic[$to['site_id']],$to['topic'],$to['targetdate'],$to['startdate'], $to['finishdate'], $prevFollowUpTopic));	
			}
		}
		
		$pdf->Output('I', $this->ident['initial']."_hod_meeting_mom.pdf", false);
		
	}

	public function deletestartdateAction() {
		if($this->allowDeleteHODMeeting)
		{
			$params = $this->_getAllParams();
			$params['user_id'] = $this->ident['user_id'];
			$hodTable = $this->loadModel('hod');
			$hodTable->deleteTopicStartDate($params['id']);

			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "Delete BOD Meeting Topic Start Date";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);	

			$start_dates = $hodTable->getTopicStartDate($params['topic_id']);
			if(!empty($start_dates))
			{
				$sdate = "";
				foreach($start_dates as $date)
				{
					$sdate .= date("j M Y", strtotime($date['start_date'])).' <i class="fa fa-trash remove-start-date" data-id="'.$date['topic_start_id'].'" data-topicid="'.$params['topic_id'].'" style="cursor:pointer;"></i><br/>';
				}
				echo $sdate;
			}
		}
		else {
			$this->_response->setRedirect($this->baseUrl);
			$this->_response->sendResponse();
			exit();
		}
	}

	public function deletetargetdateAction() {
		if($this->allowDeleteHODMeeting)
		{
			$params = $this->_getAllParams();
			$params['user_id'] = $this->ident['user_id'];
			$hodTable = $this->loadModel('hod');
			$hodTable->deleteTopicTargetDate($params['id']);

			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "Delete BOD Meeting Topic Target Date";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);	

			$target_dates = $hodTable->getTopicTargetDate($params['topic_id']);
			if(!empty($target_dates))
			{
				$tdate = "";
				foreach($target_dates as $date)
				{
					$tdate .= date("j M Y", strtotime($date['target_date'])).' <i class="fa fa-trash remove-target-date" data-id="'.$date['topic_target_id'].'" data-topicid="'.$params['topic_id'].'" style="cursor:pointer;"></i><br/>';
				}
				echo $tdate;
			}
		}
		else {
			$this->_response->setRedirect($this->baseUrl);
			$this->_response->sendResponse();
			exit();
		}
	}

	public function historyAction() {
		if($this->showHistoryHOD)
		{
			$params = $this->_getAllParams();
			
			$hodTable = $this->loadModel('hod');

			if(empty($params['start'])) $params['start'] = '0';
			$params['pagesize'] = 10;
			$this->view->start = $params['start'];
			$topics = $hodTable->getHodMeetingTopicsHistory($params);
			
			foreach($topics as &$t)
			{
				if(!empty($t['finish_date']) && $t['finish_date']!= "0000-00-00 00:00:00")
				{
					$date = explode(" ", $t['finish_date']);
					$arr_date = explode("-",$date[0]);
					$t['finishdate'] = date("j M Y", mktime(0, 0, 0, $arr_date[1], $arr_date[2], $arr_date[0]));
				}
				else
				{
					$t['finishdate'] = "";
				}
					
				$topic_target_date = $hodTable->getTopicTargetDate($t['topic_id']);
				if(!empty($topic_target_date))
				{
					foreach($topic_target_date as $target_date)
					{
						$t['targetdate'] .= date("j M Y", strtotime($target_date['target_date'])).'<br/>';
					}
				}

				$topic_start_date = $hodTable->getTopicStartDate($t['topic_id']);
				if(!empty($topic_start_date))
				{
					foreach($topic_start_date as $start_date)
					{
						$t['startdate'] .= date("j M Y", strtotime($start_date['start_date'])).'<br/>';
					}	
				}

				$topicFollowUp = $hodTable->getFollowUpByTopicId($t['topic_id']);
				$followUpTopic="";
				if(!empty($topicFollowUp))
				{
					foreach($topicFollowUp as $fu)
					{
						if(!empty($fu['follow_up']))
						{	
							$fuDateTime = explode(" ", $fu['meeting_date']);
							$fuDate = date("j M Y", strtotime($fuDateTime[0]));
							$followUpTopic .= "<strong>".$fuDate."</strong><br/>".nl2br($fu['follow_up']);	
							if(!empty($fu['filename']))
							{
								$followUpTopic .= '<br/>';
								$currentFollowUpImages = $hodTable->getFollowUpImages($fu['followup_id']);
								foreach($currentFollowUpImages as $img)
								{
									$followUpTopic .= '<a class="image-popup-vertical-fit" href="/images/hod_meeting'.$img['filename'].'"><img src="/images/hod_meeting'.str_replace(".", "_thumb.", $img['filename']).'" class="hod_thumb"></a> '; 
								}
							}
							$followUpTopic .= "<br/><br/>";									
						}
					}
				}
				$t['follow_up'] = $followUpTopic;
				
				if(!empty($t['filename']))
				{
					$t['images'] = $hodTable->getTopicImages($t['topic_id']);
				}
			}
			$this->view->topics = $topics;	
			
			$totalReport = $hodTable->getTotalHodMeetingTopicsHistory($params);
			if($totalReport['total'] > $params['pagesize'])
			{
				if($params['start'] >= $params['pagesize'])
				{
					$this->view->firstPageUrl = "/default/hod/history";
					$this->view->prevUrl = "/default/hod/history/start/".($params['start']-$params['pagesize']);
				}
				if($params['start'] < (floor(($totalReport['total']-1)/$params['pagesize'])*$params['pagesize']))
				{
					$this->view->nextUrl = "/default/hod/history/start/".($params['start']+$params['pagesize']);
					$this->view->lastPageUrl = "/default/hod/history/start/".(floor(($totalReport['total']-1)/$params['pagesize'])*$params['pagesize']);
				}
			}
			$this->view->curPage = ($params['start']/$params['pagesize'])+1;
			
			if($totalReport['total'] > 0) 
			{
				$this->view->startRec = $params['start'] + 1;
				$this->view->totalPage = ceil($totalReport['total']/$params['pagesize']);
			}
			else 
			{
				$this->view->startRec = 0;
				$this->view->totalPage = 1;
			}
			$endRec = $params['start'] + $params['pagesize'];
			if($totalReport['total'] >=  $endRec) $this->view->endRec =  $endRec;
			else $this->view->endRec =  $totalReport['total'];		
			$this->view->totalRec = $totalReport['total'];

			$category = $this->loadModel('category');
			$this->view->categories = $category->getCategories();

			$this->view->category_id = $params['category'];
			$this->view->project_name = $params['project_name'];

			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "View History BOD Meeting Projects";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);	

			$this->renderTemplate('history_hod_meeting.tpl');  
		}
		else {
			$this->_response->setRedirect($this->baseUrl);
			$this->_response->sendResponse();
			exit();
		}
	}

	public function sendapprovalreminderAction() {
		$params = $this->_getAllParams();
		
		$siteTable = $this->loadModel('site');
		$sites = $siteTable->getSites();
		if(!empty($sites))
		{
			$hodTable = $this->loadModel('hod');
			foreach($sites as $site)
			{
				if($site['site_id'] < 4)
				{
					$hodMeeting = $hodTable->getUnapprovedHodMeeting($site['site_id'], date("Y-m-d"));
					if(!empty($hodMeeting))
					{
						$html = '<p>Dear GM,</p>
						<p>Please approve BOD Meeting MOM below:</p>
						<table cellpadding="0" cellspacing="0">
						<tr>
							<th style="background-color:#555; color: #fff; border:1px solid #fff; padding:5px;">Day/Date</th>
							<th style="background-color:#555; color: #fff; border:1px solid #fff; padding:5px;">Title</th>
						</tr>';

						foreach($hodMeeting as $hod)
						{
							$date = explode(" ",$hod['meeting_date']);
							$schedule_date = date("l / j F Y", strtotime($date[0]));
							$html.='<tr>
									<td style="border:1px solid #bbb; padding:5px;">'.$schedule_date.'</td>
									<td style="border:1px solid #bbb; padding:5px;"><a href="'.$this->baseUrl.'/default/hod/viewdetail/id/'.$hod['hod_meeting_id'].'" target="_blank">'.$hod['meeting_title'].'</a></td>
								</tr>';
						}
						$html .= "</table>";

						require_once 'Zend/Mail.php';
						$mail = new Zend_Mail();
						$mail->setBodyHtml($html);
						$mail->setFrom("srt@pakuwon.com");

						$mail->addTo("emmadarmawan@pakuwon.com");

						/*if($site['site_id'] == 1) $mail->addTo("lilimulyadi@pakuwon.com");
						elseif($site['site_id'] == 2) $mail->addTo("lusiana@pakuwon.com");
						elseif($site['site_id'] == 3) $mail->addTo("achmadhakiki@pakuwon.com");

						$mail->addCC("eiffeltedja@pakuwon.com");
						$mail->addCC("muratkusuma@pakuwon.com");
						$mail->addBcc("emmadarmawan@pakuwon.com");
						$mail->addBcc("habsaribanoewati@pakuwon.com");*/
					
						$mail->setSubject($site['initial'] . ' - BOD Meeting MOM needs to be approved');
						
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
			}
		}
	}

	public function deletetopicimageAction() {
		if($this->allowDeleteHODMeeting)
		{
			$params = $this->_getAllParams();
			$params['user_id'] = $this->ident['user_id'];
			$hodTable = $this->loadModel('hod');
			$hodTable->deleteTopicImage($params['id']);

			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "Delete BOD Meeting Topic Image";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);	
		}
		else {
			$this->_response->setRedirect($this->baseUrl);
			$this->_response->sendResponse();
			exit();
		}
	}

	public function gethodissuesAction() {
		$params = $this->_getAllParams();
		$commentsTable = $this->loadModel('comments');
		Zend_Loader::LoadClass('issueClass', $this->modelDir);
		$issueClass = new issueClass();
		$issues = $issueClass->getHODIssues($params['id']);
		foreach($issues as &$i)
		{
			$issue_date_time = explode(" ",$i['issue_date']);
			$i['date'] = date("j-M-Y", strtotime($issue_date_time[0]));

			$i['comments'] = $commentsTable->getCommentsByIssueId($i['issue_id'], '1');
		}
		$this->view->issues = $issues;
		
		$hodTable = $this->loadModel('hod');
		$attendance = $hodTable->getAttendanceByHodMeetingId($params['hod_meeting_id']);	
		$pic = array();
		$a=0;
		if(!empty($attendance))
		{
			foreach($attendance as $att)
			{
				$pic[$att['site_id']] .= "<br/>".$att['attendance_name'];
			}
		}
		$this->view->pic = $pic;

		echo $this->view->render('hod_issue_finding.tpl');
	}

	public function getfitoutongoingAction() {
		if($this->showFitOutOnGoing == 1 && $this->site_id < 4)
		{
			$params = $this->_getAllParams();
			if($this->site_id == 1) $sitename = "GANDARIA CITY";
			elseif($this->site_id == 2) $sitename = "KOTA KASABLANKA";
			elseif($this->site_id == 3) $sitename = "PLAZA BLOK M";
			$hodTable = $this->loadModel('hod');
			$prevHodMeeting = $hodTable->getPrevHodMeeting($params['cur_meeting_date']);
			if(!empty($prevHodMeeting))
			{
				$prevHodMeetingDateTime = explode(" ", $prevHodMeeting['meeting_date']);
				$prevHodMeetingDate = $prevHodMeetingDateTime[0];
			}
			else {
				$hod_meeting_date = explode("-", $params['cur_meeting_date']);
				$prevHodMeetingDate = date("Y-m-d", mktime(0, 0, 0, $hod_meeting_date[1]  , $hod_meeting_date[2]-7, $hod_meeting_date[0]));					
			}

			$ch = curl_init(); 
			curl_setopt($ch, CURLOPT_URL, "http://fom.pakuwon.com/views/ws/index.php?ACT=ongoing&D1=".$prevHodMeetingDate."&D2=".$params['cur_meeting_date']."&MALL=".urlencode($sitename)."&PIN="); 
			//return the transfer as a string 
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
			$output = curl_exec($ch); 
			curl_close($ch); 
			$this->view->fitOutOnGoing = $fitOutOnGoing = json_decode($output, true);
			echo $this->view->render('hod_fitout_ongoing.tpl');
		}
	}

	public function addprogressAction() {
		if($this->showAddHOD)
		{
			$params = $this->_getAllParams();
			$params['user_id'] = $this->ident['user_id'];
			$hodTable = $this->loadModel('hod');
			$params['followup_id'] = $followup_id = $hodTable->saveFollowUp($params);

			if(!empty($_FILES["followup_image"]))
			{
				$magickPath = "/usr/bin/convert";
				$datafolder = $this->config->paths->html."/images/hod_meeting/".date("Ym")."/";
				if(!is_dir($datafolder)) mkdir($datafolder, 0777, true);
				$i = 0;
				foreach($_FILES["followup_image"]['tmp_name'] as $tmpname)
				{
					$ext = explode(".",$_FILES["followup_image"]['name'][$i]);
					$filename = "followup_".$followup_id."_".date("YmdHis")."_".$i.".".$ext[count($ext)-1];
					if(move_uploaded_file($tmpname, $datafolder.$filename))
					{
						/*** convert to jpg ***/
						if(!in_array(strtolower($ext[count($ext)-1]), array("jpg"))) 
						{
							$newFilename =  "followup_".$followup_id."_".date("YmdHis")."_".$i.".jpg";
							exec($magickPath . ' ' . $datafolder.$filename . ' ' . $datafolder.$newFilename);
						}
						else  $newFilename = "followup_".$followup_id."_".date("YmdHis")."_".$i.".".$ext[count($ext)-1];

						$params['filename'] = "/".date("Ym")."/".$newFilename;
						$hodTable->saveFollowUpImage($params);
						
						/*** create thumbnail image ***/
						exec($magickPath . ' ' . $datafolder.$newFilename . ' -resize 128x128 ' . $datafolder."followup_".$followup_id."_".date("YmdHis")."_".$i."_thumb.jpg");
						/*** resize image if size greater than 500 Kb ***/
						if(filesize($datafolder.$newFilename) > 500000) exec($magickPath . ' ' . $datafolder.$newFilename . ' -resize 800x800\> ' . $datafolder.$newFilename);
					}
					$i++;
				}
			}

			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "Save HOD Meeting Topic Follow Up Progress";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);	

			$currentFollowUp = $hodTable->getCurrentFollowUp($params['topic_id'], $params['hod_meeting_id']);
			if(!empty($currentFollowUp))
			{
				$curFollowUpTopic = "";
				$j = 0;
				foreach($currentFollowUp as $curfu)
				{
					if(!empty($curfu['follow_up']))
					{	
						if($j == 0)
						{
							$fuDateTime = explode(" ", $curfu['added_date']);
							$fuDate = date("j M Y", strtotime($fuDateTime[0]));
							$curFollowUpTopic .= "<strong>".$fuDate.'</strong>  <a id="edit-progress-'.$curfu['followup_id'].'" class="edit-progress" href="#progress-form" data-id="'.$curfu['followup_id'].'"><i class="fa fa-edit edit-progress-img" style="cursor:pointer;" data-id="'.$curfu['followup_id'].'"></i><br/>';
						}
						$curFollowUpTopic .= nl2br($curfu['follow_up']);
						if(!empty($curfu['filename']))
						{
							$curFollowUpTopic .= "<br/>";
							$currentFollowUpImages = $hodTable->getFollowUpImages($curfu['followup_id']);
							foreach($currentFollowUpImages as $img)
							{
								$curFollowUpTopic .= '<a class="image-popup-vertical-fit" href="/images/hod_meeting'.$img['filename'].'"><img src="/images/hod_meeting'.str_replace(".", "_thumb.", $img['filename']).'" class="hod_thumb"></a> '; 
							}
						}
						$curFollowUpTopic .= "<br/><br/>";
						$j++;
					}
				}
			}
			echo $curFollowUpTopic;
		}
		else {
			$this->_response->setRedirect($this->baseUrl);
			$this->_response->sendResponse();
			exit();
		}
	}

	public function getfollowupbyidAction() {
		if($this->showAddHOD)
		{
			$params = $this->_getAllParams();

			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "Get BOD Meeting Topic Follow By Id";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);	

			$hodTable = $this->loadModel('hod');
			$topicFollowUp = $hodTable->getFollowUpById($params['id']);
			$topicFollowUpImages = $hodTable->getFollowUpImages($params['id']);
			if(!empty($topicFollowUpImages))
			{
				$imagelist = '<ul class="hod_image_list">';
				foreach($topicFollowUpImages as $image)
				{
					$imagelist .= '<li><a class="image-popup-vertical-fit" href="/images/hod_meeting'.$image['filename'].'"><img src="/images/hod_meeting'.str_replace(".", "_thumb.", $image['filename']).'" class="hod_thumb"></a>  <i class="fa fa-trash remove-image-db" data-id="'.$image['image_id'].'" style="cursor:pointer;"></i></li>';
				}
				$imagelist .= "</ul>";
				$topicFollowUp['imagelist'] = $imagelist;
			}
			echo json_encode($topicFollowUp);
		}
		else {
			$this->_response->setRedirect($this->baseUrl);
			$this->_response->sendResponse();
			exit();
		}
	}

	public function deletefollowupimageAction() {
		if($this->allowDeleteHODMeeting)
		{
			$params = $this->_getAllParams();
			$params['user_id'] = $this->ident['user_id'];
			$hodTable = $this->loadModel('hod');
			$hodTable->deleteFollowUpImage($params['id']);

			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "Delete BOD Meeting Topic Follow Up Image";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);	
		}
		else {
			$this->_response->setRedirect($this->baseUrl);
			$this->_response->sendResponse();
			exit();
		}
	}

	/*** COMMENTS ***/

	public function addcommentAction() {
		if($this->showHODMeeting || $this->showHODMeetingAdmin)
		{
			$params = $this->_getAllParams();
			$params['user_id'] = $this->ident['user_id'];
			$hodTable = $this->loadModel('hod');

			if($_FILES["attachment"]["size"] > 0)
			{
				$datafolder = $this->config->paths->html."/images/hod_meeting/comments_".date("Ym")."/";
				if(!is_dir($datafolder)) mkdir($datafolder, 0777, true);
				$ext = explode(".",$_FILES["attachment"]['name']);
				$filename = "hod_meeting_cmt_".date("YmdHis").".".$ext[count($ext)-1];
				if(move_uploaded_file($_FILES["attachment"]["tmp_name"], $datafolder.$filename))
				{
					
					if(in_array(strtolower($ext[count($ext)-1]), array("jpg", "jpeg", "png","bmp")))
					{
						$magickPath = "/usr/bin/convert";
						/*** resize image if size greater than 500 Kb ***/
						if(filesize($datafolder.$filename) > 500000) exec($magickPath . ' ' . $datafolder.$filename . ' -resize 800x800\> ' . $datafolder.$filename);
					}					
					$params['filename'] = $filename;	
					$hodTable->addComment($params);
				}		
			}
			else{
				$hodTable->addComment($params);
			}	

			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "Add BOD Meeting Comment";
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

	function getcommentsbymeetingidAction()
	{
		$params = $this->_getAllParams();
		$this->view->ident = $this->ident;
		$category = $this->loadModel('category');
		$hodTable = $this->loadModel('hod');
		$comments = $hodTable->getCommentsByHODMeetingId($params['id']);
		$commentText = "";
		if(!empty($comments)) {
			foreach($comments as $comment)
			{
				$comment_date = explode("-", $comment['comment_date']); 
				$commentText .= '<div style="border-bottom:1px solid #ccc; text-align:left; clear:both; margin-bottom:5px; padding-bottom:5px;"><strong>'.$comment['name'].' : </strong>'.$comment['comment'].'<br/>';
				if(!empty($comment['filename'])) $commentText .= '<a href="'.$this->baseUrl."/images/hod_meeting/comments_".$comment_date[0].$comment_date[1]."/".$comment['filename'].'" target="_blank"><i class="fa fa-paperclip"></i> '.$comment['filename'].'</a>';
				$commentText .= '<div style="font-size:10px; color:#aaa; text-align:right;">'.$comment['comment_date']."</div></div>";
			}
		}
		
		echo $commentText;	
	}

	function updatecommentsAction()
	{
		$params = $this->_getAllParams();
		$params['pagesize'] = 10;
		
		$hodTable = $this->loadModel('hod');
		
		$data= array();

		$commentCacheName = "hod_comments_".$this->site_id."_".$params["start"];
		//$data = $this->cache->load($commentCacheName);
		$i=0;
		if(empty($data))
		{
			$hodMeetingMoM = $hodTable->getReportIds($params);	
			foreach($hodMeetingMoM as $s) {
				$data[$i]['hod_meeting_id'] = $s['hod_meeting_id'];
				$comments = $hodTable->getCommentsByHODMeetingId($s['hod_meeting_id'], '3');
				if(!empty($comments)) { 
					$comment_content = "";
					foreach($comments as $comment)
					{
						$comment_date = explode("-", $comment['comment_date']); 
						$comment_content .= '<div style="border-bottom:1px solid #ccc; text-align:left; clear:both; margin-bottom:5px; padding-bottom:5px;"><strong>'.$comment['name'].' : </strong>'.$comment['comment'].'<br/>';
						if(!empty($comment['filename'])) $comment_content .= '<a href="'.$this->baseUrl."/images/hod_meeting/comments_".$comment_date[0].$comment_date[1]."/".$comment['filename'].'" target="_blank"><i class="fa fa-paperclip"></i> '.$comment['filename'].'</a>';
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
	
	public function exporttopictopdfAction() {	
		$params = $this->_getAllParams();

		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "Export BOD Meeting Topic to PDF";
		$logData['data'] = json_encode($params);
		$logsTable->insertLogs($logData);	

		$hodTable = $this->loadModel('hod');
		$topic = $hodTable->getHodMeetingTopicById($params['id']);
		$pic = $hodTable->getAttendanceByMeetingSiteId($topic['hod_meeting_id'], $topic['site_id']);
		if(!empty($pic))
		{
		    foreach($pic as $p)
		    {
		        $topicPIC .= $p['attendance_name']. ", "; 
		    }
		    $topicPIC = substr($p['attendance_name'], 0, -2);
		}
		$topic_images = $hodTable->getTopicImages($params['id']);
		$topic_target_date = $hodTable->getTopicTargetDate($params['id']);
		if(!empty($topic_target_date))
		{
    		foreach($topic_target_date as $target_date)
    		{
    			$topic['targetdate'] .= date("j M Y", strtotime($target_date['target_date'])).", ";
    		}		
    		$topic['targetdate'] = substr($topic['targetdate'], 0, -2);
		}
		
		$topic_start_date = $hodTable->getTopicStartDate($params['id']);
		if(!empty($topic_target_date))
		{
    		foreach($topic_start_date as $start_date)
    		{
    			$topic['startdate'] .= date("j M Y", strtotime($start_date['start_date'])).", ";
    		}	
    		$topic['startdate'] = substr($topic['startdate'], 0, -2);
		}
		
		if(empty($topic['finish_date']) ||  $topic['finish_date'] == "0000-00-00 00:00:00") $topic['finishdate'] = $topic['finish_date'] = "";
		else {
			$finishdate = explode(" ", $topic['finish_date']);
			$topic['finish_date'] = $finishdate[0];
			$topic['finishdate'] = date("j M Y", strtotime($finishdate[0]));
		}
		$followUp = $hodTable->getFollowUpByTopicId($params['id']);
		
		$scImagePath = $this->config->paths->html.'/images/hod_meeting';
		$scImageURL = $url."images/hod_meeting";
		
		require_once('fpdf/mc_table.php');
		$pdf=new PDF_MC_Table();
		$pdf->AddPage();
		$pdf->SetTitle("BOD Meeting Project/Issue");
		$pdf->SetFont('Arial','B',15);
		$pdf->Write(10,"BOD Meeting Project/Issue");
		$pdf->ln(10);

		$pdf->SetFont('Arial','B',8);
		$pdf->Cell(35,6,'Site',0,0,'L');
		$pdf->Cell(138,6,$topic['site_name'],0,0,'L');
		$pdf->Ln();
		$pdf->Cell(35,6,'PIC',0,0,'L');
		$pdf->Cell(138,6,$topicPIC,0,0,'L');
		$pdf->Ln();
		$pdf->Cell(35,6,'Project/Issue',0,0,'L');
		$pdf->Cell(138,6,$topic['topic'],0,0,'L');
		$pdf->Ln();
		$curY = $pdf->getY();
		if(!empty($topic_images))
		{
			$j = 0;
			$tiY = $tiX = 0;
			$pdf->Cell(35,5,'',0,0,'L');
			$tiimgctr = 0;
			foreach($topic_images as $ti) {
				$tiY = $curY;
				$tiX = $curX+46;
				
				if (file_exists($scImagePath.str_replace(".", "_thumb.", $ti['filename']))) {
    				list($width, $height) = getimagesize($scImagePath.str_replace(".", "_thumb.", $ti['filename']));
    				if($width > $height)
    				{
    					$w = 14;
    					$h = 0;
    				}
    				else {
    					$w = 0;
    					$h = 14;
    				}
    				$pdf->Image($scImageURL.str_replace(".", "_thumb.", $ti['filename']), $tiX, $tiY, $w, $h);
    				if($tiimgctr%2 == 1)
    				{
    				    $tiX = $curX+105;
    				    $tiY = $tiY+14.5;
    				}
    				else
    				    $tiX = $tiX+14.5;
    				    
    				$tiimgctr++;
    			}
			}
			$tiY = $tiY + 14.5;
			$pdf->Ln(15);
		}
		
		$pdf->Ln();
		$pdf->Cell(35,6,'Start Date',0,0,'L');
		$pdf->Cell(138,6,$topic['startdate'],0,0,'L');
		$pdf->Ln();
		$pdf->Cell(35,6,'Target Date',0,0,'L');
		$pdf->Cell(138,6,$topic['targetdate'],0,0,'L');
		$pdf->Ln();
		$pdf->Cell(35,6,'Finish Date',0,0,'L');
		$pdf->Cell(138,6,$topic['finishdate'],0,0,'L');
		$pdf->Ln();
		$pdf->Cell(35,6,'Follow Up',0,0,'L');
		
		if(!empty($followUp))
		{
			$j = 0;
			$followUpTopic = "";
			$fuY = $fuX = 0;
			foreach($followUp as $fu) {
			    if($j > 0) $pdf->Cell(35,6,'',0,0,'L');
			    $pdf->SetFont('Arial','B',8);
				$fuDateTime = explode(" ", $fu['added_date']);
				$fuDate = date("j M Y", strtotime($fuDateTime[0]));
				$pdf->Cell(138,5,$fuDate,0,0,'L');
				$pdf->Ln();
				$pdf->SetFont('Arial','',8);
				$pdf->Cell(35,5,'',0,0,'L');
				$pdf->Cell(138,5,str_replace("<br />", " ",nl2br($fu['follow_up'])),0,0,'L');
				$pdf->Ln();
				$curY = $pdf->getY();
				$fuY = $curY;
				$fuX = $curX+46;

				$prevFollowUpTopicImages = $hodTable->getFollowUpImages($fu['followup_id']);
				if(!empty($prevFollowUpTopicImages))
				{
				    $fuimgctr = 0;
				    $pdf->Cell(35,5,'',0,0,'L');
					foreach($prevFollowUpTopicImages as $image2) {
						if (file_exists($scImagePath.str_replace(".", "_thumb.", $image2['filename']))) {
							list($width, $height) = getimagesize($scImagePath.str_replace(".", "_thumb.", $image2['filename']));
							if($width > $height)
							{
								$w = 14;
								$h = 0;
							}
							else {
								$w = 0;
								$h = 14;
							}
							$pdf->Image($scImageURL.str_replace(".", "_thumb.", $image2['filename']), $fuX, $fuY, $w, $h);
						    $fuX = $fuX+14.5;
							    
							$fuimgctr++;
						}
					}							
					$fuY = $fuY + 14.5;
					$pdf->Ln(15);
				}
				else {
					$fuY = $fuY + 5;
				}	
				$j++;
			}
		}
		
		$pdf->Output('I', $this->ident['initial']."_hod_meeting_mom.pdf", false);
		
	}
}
?>