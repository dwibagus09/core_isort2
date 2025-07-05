<?php

require_once('actionControllerBase.php');
require_once('Zend/Json.php');

class BsController extends actionControllerBase
{
	public function addAction() {
		if($this->showAddBSMeeting)
		{
			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "Add BS Meeting";
			$logData['data'] = "Add BS Meeting";
			$logsTable->insertLogs($logData);	

			$site = $this->loadModel('site');
			$this->view->sites = $site->getSites();

			$this->view->title = "Add BS Meeting MoM";

			$bsTable = $this->loadModel('bs');
			$prevBsMeeting = $bsTable->getPrevBsMeeting("");
			if(!empty($prevBsMeeting['bs_meeting_id'])) {
				$attendance = $bsTable->getAttendanceByBsMeetingId($prevBsMeeting['bs_meeting_id']);
				foreach($attendance as &$att)
				{
					unset($att['attendance_id']);
				}
				$this->view->attendance = $attendance;
			}

			$this->renderTemplate('form_bs_meeting.tpl'); 
		}
		else {
			$this->_response->setRedirect($this->baseUrl);
			$this->_response->sendResponse();
			exit();
		}
	}

	public function editAction() {
		if($this->showAddBSMeeting)
		{
			$params = $this->_getAllParams();

			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "Edit BS Meeting";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);	

			$site = $this->loadModel('site');
			$this->view->sites = $site->getSites();

			$bsTable = $this->loadModel('bs');
			$bsMeeting = $bsTable->getBsMeetingById($params['id']);
			$bsMeetingDateTime = explode(" ", $bsMeeting['meeting_date']);
			$bsMeeting['tanggal'] =$bsMeetingDateTime[0];
			$this->view->bsMeeting = $bsMeeting;

			$this->view->attendance = $bsTable->getAttendanceByBsMeetingId($params['id']);

			$this->view->title = "Edit BS Meeting MoM";

			$this->renderTemplate('form_bs_meeting.tpl'); 
		}
		else {
			$this->_response->setRedirect($this->baseUrl);
			$this->_response->sendResponse();
			exit();
		}
	}

	public function saveAction() {
		if($this->showAddBSMeeting)
		{
			$params = $this->_getAllParams();

			$params['user_id'] = $this->ident['user_id'];
			$bsTable = $this->loadModel('bs');
			$data['bs_meeting_id'] = $bsTable->saveBs($params);

			$i = 0;
			foreach($params['attendance_site_id'] as $site_id)
			{
				$data["attendance_id"] = $params["attendance_id"][$i];
				$data["site_id"] = $site_id;
				$data["attendance_name"] = $params["attendance_name"][$i];
				$bsTable->saveAttendance($data);
				$i++;
			}

			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "Save BS Meeting";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);	

			$this->_response->setRedirect($this->baseUrl."/default/bs/bsmeetingform2/id/".$data['bs_meeting_id']);
			$this->_response->sendResponse();
			exit();
		}
		else {
			$this->_response->setRedirect($this->baseUrl);
			$this->_response->sendResponse();
			exit();
		}
	}

	public function bsmeetingform2Action() {
		if($this->showAddBSMeeting)
		{
			$params = $this->_getAllParams();

			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "Open Form BS Meeting 2";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);	

			$bsTable = $this->loadModel('bs');
			$bsMeeting = $bsTable->getBsMeetingById($params['id']);
			$bsMeetingDateTime = explode(" ", $bsMeeting['meeting_date']);
			$bsMeeting['tanggal_jam'] = date("l, j F Y", strtotime($bsMeetingDateTime[0]))." / ". $bsMeeting['meeting_time'];
			$bsMeeting['tanggal'] = date("j-M", strtotime($bsMeetingDateTime[0]));
			$this->view->bsMeeting = $bsMeeting;

			$this->view->attendance = $bsTable->getAttendanceByBsMeetingId($params['id']);
			
			$topic = $bsTable->getBsMeetingTopics($params['id']);
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
					$t['images'] = $bsTable->getTopicImages($t['topic_id']);
				}
			}
			$this->view->topic = $topic;

			$site = $this->loadModel('site');
			$this->view->sites = $site->getSites();

			$this->view->bs_meeting_id = $params['id'];

			$this->renderTemplate('form_bs_meeting2.tpl'); 
		}
		else {
			$this->_response->setRedirect($this->baseUrl);
			$this->_response->sendResponse();
			exit();
		}
	}

	public function savetopicAction() {
		if($this->showAddBSMeeting)
		{
			$params = $this->_getAllParams();
			$params['user_id'] = $this->ident['user_id'];
			$bsTable = $this->loadModel('bs');
			$params['bs_meeting_topic_id'] = $topic_id = $bsTable->saveTopic($params);

			if(!empty($_FILES["topic_image"]))
			{
				$magickPath = "/usr/bin/convert";
				$datafolder = $this->config->paths->html."/images/bs_meeting/".date("Ym")."/";
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
						$bsTable->saveTopicImage($params);
						
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
			$logData['action'] = "Save BS Meeting Topic";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);	

			$this->_response->setRedirect($this->baseUrl."/default/bs/bsmeetingform2/id/".$params['bs_meeting_id']);
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
		
		$bsTable = $this->loadModel('bs');

		if(empty($params['start'])) $params['start'] = '0';
		$params['pagesize'] = 10;
		$this->view->start = $params['start'];
		$bs = $bsTable->getBsMeetingMom();
		if(!empty($bs))
		{
			foreach($bs as &$h)
			{
				$date = explode(" ", $h['meeting_date']);
				$arr_date = explode("-",$date[0]);
				$h['day_date'] = date("l, j F Y", mktime(0, 0, 0, $arr_date[1], $arr_date[2], $arr_date[0]));

				$h['comments'] = $bsTable->getCommentsByBSMeetingId($h['bs_meeting_id'], '3');
			}
		}
		$this->view->bsMeeting = $bs;	
		
		$totalReport = $bsTable->getTotalBsMom();
		if($totalReport['total'] > 10)
		{
			if($params['start'] >= 10)
			{
				$this->view->firstPageUrl = "/default/bs/view";
				$this->view->prevUrl = "/default/bs/view/start/".($params['start']-$params['pagesize']);
			}
			if($params['start'] < (floor(($totalReport['total']-1)/10)*10))
			{
				$this->view->nextUrl = "/default/bs/view/start/".($params['start']+$params['pagesize']);
				$this->view->lastPageUrl = "/default/bs/view/start/".(floor(($totalReport['total']-1)/10)*10);
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

		$this->view->showBSMeetingAdmin = $this->showBSMeetingAdmin;

		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "View BS Meeting MOM List";
		$logData['data'] = json_encode($params);
		$logsTable->insertLogs($logData);	

		$this->renderTemplate('view_bs_meeting.tpl');  
	}

	public function viewdetailAction() {
		if($this->showBSMeeting == 1 || $this->showBSMeetingAdmin == 1)
		{
			$params = $this->_getAllParams();
			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "View BS Meeting MoM";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);	

			$bsTable = $this->loadModel('bs');
			$bsMeeting = $bsTable->getBsMeetingById($params['id']);
			$bsMeetingDateTime = explode(" ", $bsMeeting['meeting_date']);
			$bsMeeting['tanggal_jam'] = date("l, j F Y", strtotime($bsMeetingDateTime[0]))." / ".$bsMeeting['meeting_time'];
			$bsMeeting['tanggal'] = date("j-M", strtotime($bsMeetingDateTime[0]));
			$this->view->bsMeeting = $bsMeeting;
			$this->view->currentMeetingDate = $bsMeetingDateTime[0];
			
			$this->view->attendance = $attendance = $bsTable->getAttendanceByBsMeetingId($params['id']);	
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

			$role_ids = implode(",",$this->ident['role_ids']);
			Zend_Loader::LoadClass('userClass', $this->modelDir);
			$userClass = new userClass();
			/*$cat_ids = $userClass->getCategoriesByRoles($role_ids);
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
			/*$commentsTable = $this->loadModel('comments');

			Zend_Loader::LoadClass('issueClass', $this->modelDir);
			$issueClass = new issueClass();
			$issues = $issueClass->getBSIssues($dept_ids);
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
				$prevBsMeeting = $bsTable->getPrevBsMeeting($bsMeetingDateTime[0]);
				if(!empty($prevBsMeeting))
				{
					$prevBsMeetingDateTime = explode(" ", $prevBsMeeting['meeting_date']);
					$prevBsMeetingDate = $prevBsMeetingDateTime[0];
				}
				else {
					$bs_meeting_date = explode("-", $bsMeetingDateTime[0]);
					$prevBsMeetingDate = date("Y-m-d", mktime(0, 0, 0, $bs_meeting_date[1]  , $bs_meeting_date[2]-7, $bs_meeting_date[0]));					
				}

				$ch = curl_init(); 
				curl_setopt($ch, CURLOPT_URL, "http://fom.pakuwon.com/views/ws/index.php?ACT=ongoing&D1=".$prevBsMeetingDate."&D2=".$bsMeetingDateTime[0]."&MALL=".urlencode($sitename)."&PIN="); 
				//return the transfer as a string 
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
				$output = curl_exec($ch); 
				curl_close($ch); 
				$this->view->fitOutOnGoing = json_decode($output, true);
			}*/

			if($this->showFitOutOnGoing == 1 && $this->site_id < 4)	$this->view->loadFitOutOnGoing = 1;

			$topic = $bsTable->getBsMeetingTopics($params['id'], $bsMeetingDateTime[0], $dept_ids);
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
						$topic_target_date = $bsTable->getTopicTargetDate($t['topic_id']);
						foreach($topic_target_date as $target_date)
						{
							$t['targetdate'] .= date("j M Y", strtotime($target_date['target_date'])).' <i class="fa fa-trash remove-target-date" data-id="'.$target_date['topic_target_id'].'" data-topicid="'.$t['topic_id'].'" style="cursor:pointer;"></i><br/>';
						}						
					}

					if(!empty($t['topic_start_id']))
					{
						$topic_start_date = $bsTable->getTopicStartDate($t['topic_id']);
						foreach($topic_start_date as $start_date)
						{
							$t['startdate'] .= date("j M Y", strtotime($start_date['start_date'])).' <i class="fa fa-trash remove-start-date" data-id="'.$start_date['topic_start_id'].'" data-topicid="'.$t['topic_id'].'" style="cursor:pointer;"></i><br/>';
						}	
					}

					if($t['finish_date'] == "0000-00-00 00:00:00") $t['finishdate'] = $t['finish_date'] = "";
					else {
						$finishdate = explode(" ", $t['finish_date']);
						$t['finishdate'] = $finishdate[0];
						$t['finish_date'] = date("j M Y", strtotime($finishdate[0]));
					} 

					if(!empty($t['filename']))
					{
						$t['images'] = $bsTable->getTopicImages($t['topic_id']);
					}
				}
			}
			$this->view->topic = $topic;

			if($this->showBSMeetingAdmin == 1) $limitFollowUp = 1;
			else $limitFollowUp = 2;

			$prevBsMeetingFollowUp = $bsTable->getPrevBsMeetingFollowUp($params['id'], $bsMeetingDateTime[0], $dept_ids);
			if(!empty($prevBsMeetingFollowUp))
			{
				$prevFollowUpTopic = array();
				$prevTopicId = 0;
				foreach($prevBsMeetingFollowUp as $prevfu)
				{
					if($prevTopicId != $prevfu['topic_id'])
					{	
						$f = 0;		
						$prevTopicId = $prevfu['topic_id'];
					}
					if(!empty($prevfu['follow_up']))
					{	
						if($this->showBSMeetingAdmin == 1 && $prevfu['bs_meeting_id'] == $params['id'] && $bsMeeting['approved'] != 1)
						{
							$currentFollowUp = $bsTable->getCurrentFollowUp($prevfu['topic_id'], $params['id']);
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
											$currentFollowUpImages = $bsTable->getFollowUpImages($curfu['followup_id']);
											foreach($currentFollowUpImages as $img)
											{
												$curFollowUpTopic .= '<a class="image-popup-vertical-fit" href="/images/bs_meeting'.$img['filename'].'"><img src="/images/bs_meeting'.str_replace(".", "_thumb.", $img['filename']).'" class="bs_thumb"></a> '; 
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
								$prevFollowUpImages = $bsTable->getFollowUpImages($prevfu['followup_id']);
								if(!empty($prevFollowUpImages))
								{
									$prevFollowUpTopic[$prevfu['topic_id']] .= '<br/>';
									foreach($prevFollowUpImages as $img)
									{
										$prevFollowUpTopic[$prevfu['topic_id']] .= '<a class="image-popup-vertical-fit" href="/images/bs_meeting'.$img['filename'].'"><img src="/images/bs_meeting'.str_replace(".", "_thumb.", $img['filename']).'" class="bs_thumb"></a> '; 
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

			if($this->showBSMeetingAdmin == 1 && $bsMeeting['approved'] != 1) {
				$this->renderTemplate('view_bs_meeting_detail_admin.tpl');  
			} else {
				$this->view->comments = $bsTable->getCommentsByBSMeetingId($params['id'], 0, 'asc');
				$this->view->approveBSMeeting = $this->approveBSMeeting;
				$this->renderTemplate('view_bs_meeting_detail.tpl'); 
			}
		}
		else {
			$this->_response->setRedirect($this->baseUrl);
			$this->_response->sendResponse();
			exit();
		}
	}

	public function gettopicbyidAction() {
		if($this->showAddBSMeeting)
		{
			$params = $this->_getAllParams();

			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "Get BS Meeting Topic By Id";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);	

			$bsTable = $this->loadModel('bs');
			$bsMeetingTopic = $bsTable->getBsMeetingTopicById($params['id']);
			$start_date = explode(" ", $bsMeetingTopic['start_date']);
			$bsMeetingTopic['startdate'] = $start_date[0];
			$finish_date = explode(" ", $bsMeetingTopic['finish_date']);
			$bsMeetingTopic['finishdate'] = $finish_date[0];
			$images = $bsTable->getTopicImages($bsMeetingTopic['topic_id']);
			if(!empty($images))
			{
				$imagelist = '<ul class="bs_image_list">';
				foreach($images as $image)
				{
					$imagelist .= '<li><a class="image-popup-vertical-fit" href="/images/bs_meeting'.$image['filename'].'"><img src="/images/bs_meeting'.str_replace(".", "_thumb.", $image['filename']).'" class="bs_thumb"></a>  <i class="fa fa-trash remove-image-db" data-id="'.$image['image_id'].'" style="cursor:pointer;"></i></li>';
				}
				$imagelist .= "</ul>";
				$bsMeetingTopic['imagelist'] = $imagelist;
			}
			echo json_encode($bsMeetingTopic);
		}
		else {
			$this->_response->setRedirect($this->baseUrl);
			$this->_response->sendResponse();
			exit();
		}
	}

	public function deletetopicAction() {
		if($this->showAddBSMeeting)
		{
			$params = $this->_getAllParams();

			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "Delete BS Meeting Topic By Id";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);	

			$bsTable = $this->loadModel('bs');
			$bsTable->deleteTopic($params['id']);
			
			$this->_response->setRedirect($this->baseUrl."/default/bs/bsmeetingform2/id/".$params['bsid']);
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
		if($this->showAddBSMeeting)
		{
			$params = $this->_getAllParams();
			$params['user_id'] = $this->ident['user_id'];
			$bsTable = $this->loadModel('bs');
			$bsTable->addTopicTargetDate($params);

			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "Add BS Meeting Topic Target Date";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);	

			$target_dates = $bsTable->getTopicTargetDate($params['topic_id']);
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
		if($this->showAddBSMeeting)
		{
			$params = $this->_getAllParams();
			$params['user_id'] = $this->ident['user_id'];
			$bsTable = $this->loadModel('bs');
			$bsTable->addTopicStartDate($params);

			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "Add BS Meeting Topic Start Date";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);	

			$start_dates = $bsTable->getTopicStartDate($params['topic_id']);
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
		if($this->showAddBSMeeting)
		{
			$params = $this->_getAllParams();
			if(!empty($params['topic_id']))
			{
				$bsTable = $this->loadModel('bs');
				$i=0;
				foreach($params['topic_id'] as $topic_id)
				{
					$data['topic_id'] = $topic_id;
					$data['finish_date'] = $params['finish_date'][$i];
					$data['done'] = $params['done'][$i];
					if($data['done'] == '1') $data['done_bs_meeting_id'] = $params['bs_meeting_id'];
					else $data['done_bs_meeting_id'] = 0;
					$bsTable->updateFinishDate($data);
					if(!empty($params['followup'][$i]))
					{
						$data['followup'] = $params['followup'][$i];
						$data['user_id'] = $this->ident['user_id'];
						$data['followup_id'] = $params['followup_id'][$i];
						$data['bs_meeting_id'] = $params['bs_meeting_id'];
						if(empty($data['followup_id']))
						{
							$data['followup_id'] = $bsTable->checkIfFollowUpExist($data['topic_id'], $data['bs_meeting_id']);
						}
						$bsTable->saveFollowUp($data);
					}
					$i++;
				}
			}
			
			
			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "Save BS Meeting Detail Admin";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);	

			$this->_response->setRedirect($this->baseUrl."/default/bs/viewdetail/id/".$params['bs_meeting_id']);
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
		if($this->showBSMeeting)
		{
			$params = $this->_getAllParams();
			if(!empty($params['topic_id']))
			{
				$bsTable = $this->loadModel('bs');
				$i=0;
				foreach($params['topic_id'] as $topic_id)
				{
					$data['topic_id'] = $topic_id;
					$data['done_by_pic'] = $params['done_by_pic'][$i];
					if($data['done_by_pic'] == '1') $data['done_bs_meeting_id_pic'] = $params['bs_meeting_id'];
					else $data['done_bs_meeting_id_pic'] = 0;
					$bsTable->updateDoneByPic($data);
					$i++;
				}
			}			
			
			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "Save BS Meeting Detail";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);	

			$this->_response->setRedirect($this->baseUrl."/default/bs/viewdetail/id/".$params['bs_meeting_id']);
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
		if($this->approveBSMeeting)
		{
			$params = $this->_getAllParams();
			if(!empty($params['id']))
			{
				$bsTable = $this->loadModel('bs');
				$params['user_id'] = $this->ident['user_id'];
				$bsTable->approveMoM($params);

				$logsTable = $this->loadModel('logs');
				$logData['user_id'] = intval($this->ident['user_id']);
				$logData['action'] = "Approve BS Meeting MoM";
				$logData['data'] = json_encode($params);
				$logsTable->insertLogs($logData);	

				$this->_response->setRedirect($this->baseUrl."/default/bs/viewdetail/id/".$params['id']);
				$this->_response->sendResponse();
				exit();
			}		
		}
		else {
			$this->_response->setRedirect($this->baseUrl);
			$this->_response->sendResponse();
			exit();
		}
	}

	public function getfollowupAction() {
		if($this->showBSMeeting == 1 || $this->showBSMeetingAdmin == 1)
		{
			$params = $this->_getAllParams();
			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "Get Follow Up BS";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);	

			$bsTable = $this->loadModel('bs');
			$bsMeetingFollowUp = $bsTable->getFollowUpByTopicId($params['id']);
			$followUpTopic="";
			if(!empty($bsMeetingFollowUp))
			{
				foreach($bsMeetingFollowUp as $fu)
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
		if($this->showAddBSMeeting)
		{
			$params = $this->_getAllParams();

			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "Delete BS Meeting Attendance";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);	

			$bsTable = $this->loadModel('bs');
			$bsTable->deleteAttendanceById($params['id']);
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
		$logData['action'] = "Export BS Meeting to PDF";
		$logData['data'] = json_encode($params);
		$logsTable->insertLogs($logData);	

		$bsTable = $this->loadModel('bs');
		$bsMeeting = $bsTable->getBsMeetingById($params['id']);
		$bsMeetingDateTime = explode(" ", $bsMeeting['meeting_date']);
		$bsMeeting['tanggal_jam'] = date("l, j F Y", strtotime($bsMeetingDateTime[0]))." / ".$bsMeeting['meeting_time'];
		$bsMeeting['tanggal'] = date("j-M", strtotime($bsMeetingDateTime[0]));
			
		$attendance = $bsTable->getAttendanceByBsMeetingId($params['id']);	
		$pic = array();
		foreach($attendance as $att)
		{
			$pic[$att['site_id']] .= "\n".$att['attendance_name'];
		}

		$role_ids = implode(",",$this->ident['role_ids']);
		Zend_Loader::LoadClass('userClass', $this->modelDir);
		$userClass = new userClass();
		
		$commentsTable = $this->loadModel('comments');

		Zend_Loader::LoadClass('issueClass', $this->modelDir);
		$issueClass = new issueClass();
		$issues = $issueClass->getBSIssues($dept_ids);

		if($this->showFitOutOnGoing == 1 && $this->site_id < 4)
		{
			if($this->site_id == 1) $sitename = "GANDARIA CITY";
			elseif($this->site_id == 2) $sitename = "KOTA KASABLANKA";
			elseif($this->site_id == 3) $sitename = "PLAZA BLOK M";
			$prevBsMeeting = $bsTable->getPrevBsMeeting($bsMeetingDateTime[0]);
			if(!empty($prevBsMeeting))
			{
				$prevBsMeetingDateTime = explode(" ", $prevBsMeeting['meeting_date']);
				$prevBsMeetingDate = $prevBsMeetingDateTime[0];
			}
			else {
				$bs_meeting_date = explode("-", $bsMeetingDateTime[0]);
				$prevBsMeetingDate = date("Y-m-d", mktime(0, 0, 0, $bs_meeting_date[1]  , $bs_meeting_date[2]-7, $bs_meeting_date[0]));					
			}

			$ch = curl_init(); 
			curl_setopt($ch, CURLOPT_URL, "http://fom.pakuwon.com/views/ws/index.php?ACT=ongoing&D1=".$prevBsMeetingDate."&D2=".$bsMeetingDateTime[0]."&MALL=".urlencode($sitename)."&PIN="); 
			//return the transfer as a string 
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
			$output = curl_exec($ch); 
			curl_close($ch); 
			$fitOutOnGoing = json_decode($output, true);
		}

		$topic = $bsTable->getBsMeetingTopics($params['id'], $bsMeetingDateTime[0], $dept_ids);
		if(!empty($topic))
		{
			foreach($topic as &$t)
			{
				if(!empty($t['topic_target_id']))
				{
					$topic_target_date = $bsTable->getTopicTargetDate($t['topic_id']);
					foreach($topic_target_date as $target_date)
					{
						$t['targetdate'] .= date("j M Y", strtotime($target_date['target_date']))."\n";
					}						
				}

				if(!empty($t['topic_start_id']))
				{
					$topic_start_date = $bsTable->getTopicStartDate($t['topic_id']);
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
					$t['images'] = $bsTable->getTopicImages($t['topic_id']);
				}
			}
		}

		$limitFollowUp = 2;

		$prevBsMeetingFollowUp = $bsTable->getPrevBsMeetingFollowUp($params['id'], $bsMeetingDateTime[0], $dept_ids);
		$prevBsFollowUp = array();
		foreach($prevBsMeetingFollowUp as $prevbsfu) {
			$prevBsFollowUp[$prevbsfu['topic_id']][]= $prevbsfu;
		}
		
		require_once('fpdf/mc_table.php');
		$pdf=new PDF_MC_Table();
		$pdf->AddPage();
		$pdf->SetTitle("BS Meeting MoM");
		$pdf->SetFont('Arial','B',15);
		$pdf->Write(10,"BS Meeting MoM");
		$pdf->ln(10);

		$pdf->SetFont('Arial','B',8);
		$pdf->Cell(50,6,'Judul',0,0,'L');
		$pdf->Cell(138,6,$bsMeeting['meeting_title'],0,0,'L');
		$pdf->Ln();
		$pdf->Cell(50,6,'Tanggal / Jam',0,0,'L');
		$pdf->Cell(138,6,$bsMeeting['tanggal_jam'],0,0,'L');
		$pdf->Ln();
		$pdf->Cell(50,6,'Peserta',0,0,'L');

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
			$pdf->Write(10,"SRT Issues");
			$pdf->Ln();
			$pdf->SetFont('Arial','B',8);
			$pdf->SetFillColor(9,41,102);
			$pdf->SetTextColor(255,255,255);
			$pdf->Cell(20,7,'Site/PIC',1,0,'C',true);
			$pdf->Cell(45,7,'Open Issues in SRT',1,0,'C',true);
			$pdf->Cell(20,7,'Picture',1,0,'C',true);
			$pdf->Cell(35,7,'Location',1,0,'C',true);
			$pdf->Cell(20,7,'Issue Date',1,0,'C',true);
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
				$pdf->Row(array($issue['initial'].$pic[$issue['site_id']],$issue['description'],"\n\n\n\n",$issue['location'],$issue['date'], $comments));
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
			$pdf->SetFillColor(9,41,102);
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
			$pdf->SetFillColor(9,41,102);
			$pdf->SetTextColor(255,255,255);
			$pdf->Cell(20,7,'Site/PIC',1,0,'C',true);
			$pdf->Cell(60,7,'Projects / Issues',1,0,'C',true);
			$pdf->Cell(20,7,'Target Date',1,0,'C',true);
			$pdf->Cell(20,7,'Start Date',1,0,'C',true);			
			$pdf->Cell(20,7,'Finish Date',1,0,'C',true);
			$pdf->Cell(50,7,'Follow Up',1,0,'C',true);
			$pdf->Ln();
			$pdf->SetFont('Arial','',7);
			$pdf->SetTextColor(0,0,0);
			$pdf->SetWidths(array(20, 60, 20, 20, 20, 50));	

			$bsImagePath = $this->config->paths->html.'/images/bs_meeting';
			$bsImageURL = $url."images/bs_meeting";
			foreach($topic as $to) {
				$curX =  $pdf->GetX();
				$curY = $pdf->GetY();
				if($curY > 245) {
					$curY = 10;
					$pdf->AddPage();
				}
				if(!empty($to['filename']))
				{
					$to['images'] = $bsTable->getTopicImages($to['topic_id']);
					if(!empty($to['images']))
					{
						$totalLetter = strlen($to['topic']);
						$topicY = $curY + (ceil($totalLetter/52) * 5);
						$topicX = $curX+21;
						foreach($to['images'] as $image)
						{
							list($width, $height) = getimagesize($bsImagePath.str_replace(".", "_thumb.", $image['filename']));
							if($width > $height)
							{
								$w = 15;
								$h = 0;
							}
							else {
								$w = 0;
								$h = 15;
							}
							$to['topic'] .= $pdf->Image($bsImageURL.str_replace(".", "_thumb.", $image['filename']), $topicX, $topicY, $w, $h)." ";
							$topicX = $topicX+18;
						}		
						$to['topic'] .= "\n\n\n\n\n";				
					}
				}

				if(!empty($prevBsFollowUp[$to['topic_id']]))
				{
					$j = 0;
					$prevFollowUpTopic = "";
					$fuY = $fuX = 0;
					foreach($prevBsFollowUp[$to['topic_id']] as $prevfu) {
						if($j < 2)
						{
							$fuDateTime = explode(" ", $prevfu['meeting_date']);
							$fuDate = date("j M Y", strtotime($fuDateTime[0]));
							$prevFollowUpTopic .= $fuDate."\n".$prevfu['follow_up'];
							
							$totalLetter = strlen($prevfu['follow_up']);
							$fuY = $curY + (ceil($totalLetter/45) * 5) + 5;
							$fuX = $curX+141;

							$prevFollowUpTopicImages = $bsTable->getFollowUpImages($prevfu['followup_id']);
							if(!empty($prevFollowUpTopicImages))
							{
								foreach($prevFollowUpTopicImages as $image2) {
									list($width, $height) = getimagesize($bsImagePath.str_replace(".", "_thumb.", $image2['filename']));
									if($width > $height)
									{
										$w = 15;
										$h = 0;
									}
									else {
										$w = 0;
										$h = 15;
									}
									$prevFollowUpTopic .= $pdf->Image($bsImageURL.str_replace(".", "_thumb.", $image2['filename']), $fuX, $fuY, $w, $h)." ";
									$fuX = $fuX+18;
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
				$pdf->Row(array($to['initial'].$pic[$to['site_id']],$to['topic'],$to['targetdate'],$to['startdate'], $to['finishdate'], $prevFollowUpTopic));	
			}
		}
		
		$pdf->Output('I', $this->ident['initial']."_bs_meeting_mom.pdf", false);
		
	}

	public function deletestartdateAction() {
		if($this->allowDeleteBSMeeting)
		{
			$params = $this->_getAllParams();
			$params['user_id'] = $this->ident['user_id'];
			$bsTable = $this->loadModel('bs');
			$bsTable->deleteTopicStartDate($params['id']);

			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "Delete BS Meeting Topic Start Date";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);	

			$start_dates = $bsTable->getTopicStartDate($params['topic_id']);
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
		if($this->allowDeleteBSMeeting)
		{
			$params = $this->_getAllParams();
			$params['user_id'] = $this->ident['user_id'];
			$bsTable = $this->loadModel('bs');
			$bsTable->deleteTopicTargetDate($params['id']);

			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "Delete BS Meeting Topic Target Date";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);	

			$target_dates = $bsTable->getTopicTargetDate($params['topic_id']);
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
		if($this->showHistoryBSMeeting)
		{
			$params = $this->_getAllParams();
			
			$bsTable = $this->loadModel('bs');

			if(empty($params['start'])) $params['start'] = '0';
			$params['pagesize'] = 10;
			$this->view->start = $params['start'];
			$topics = $bsTable->getBsMeetingTopicsHistory($params);
			
			foreach($topics as &$t)
			{
				$date = explode(" ", $t['finish_date']);
				$arr_date = explode("-",$date[0]);
				$t['finishdate'] = date("j F Y", mktime(0, 0, 0, $arr_date[1], $arr_date[2], $arr_date[0]));

				$topic_target_date = $bsTable->getTopicTargetDate($t['topic_id']);
				if(!empty($topic_target_date))
				{
					foreach($topic_target_date as $target_date)
					{
						$t['targetdate'] .= date("j M Y", strtotime($target_date['target_date'])).'<br/>';
					}
				}

				$topic_start_date = $bsTable->getTopicStartDate($t['topic_id']);
				if(!empty($topic_start_date))
				{
					foreach($topic_start_date as $start_date)
					{
						$t['startdate'] .= date("j M Y", strtotime($start_date['start_date'])).'<br/>';
					}	
				}

				$topicFollowUp = $bsTable->getFollowUpByTopicId($t['topic_id']);
				$followUpTopic="";
				if(!empty($topicFollowUp))
				{
					foreach($topicFollowUp as $fu)
					{
						if(!empty($fu['follow_up']))
						{	
							$fuDateTime = explode(" ", $fu['meeting_date']);
							$fuDate = date("j M Y", strtotime($fuDateTime[0]));
							$followUpTopic .= "<strong>".$fuDate."</strong><br/>".nl2br($fu['follow_up'])."<br/><br/>";					
						}
					}
				}
				$t['follow_up'] = $followUpTopic;
			}
			$this->view->topics = $topics;	
			
			$totalReport = $bsTable->getTotalBsMeetingTopicsHistory($params);
			if($totalReport['total'] > $params['pagesize'])
			{
				if($params['start'] >= $params['pagesize'])
				{
					$this->view->firstPageUrl = "/default/bs/history";
					$this->view->prevUrl = "/default/bs/history/start/".($params['start']-$params['pagesize']);
				}
				if($params['start'] < (floor(($totalReport['total']-1)/$params['pagesize'])*$params['pagesize']))
				{
					$this->view->nextUrl = "/default/bs/history/start/".($params['start']+$params['pagesize']);
					$this->view->lastPageUrl = "/default/bs/history/start/".(floor(($totalReport['total']-1)/$params['pagesize'])*$params['pagesize']);
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

			$siteClass = $this->loadModel('site');
			$this->view->sites = $siteClass->getSites();

			$this->view->site_id = $params['site'];
			$this->view->project_name = $params['project_name'];

			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "View History BS Meeting Projects";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);	

			$this->renderTemplate('history_bs_meeting.tpl');  
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
			$bsTable = $this->loadModel('bs');
			foreach($sites as $site)
			{
				if($site['site_id'] < 4)
				{
					$bsMeeting = $bsTable->getUnapprovedBsMeeting($site['site_id'], date("Y-m-d"));
					if(!empty($bsMeeting))
					{
						$html = '<p>Dear GM,</p>
						<p>Please approve BS Meeting MOM below:</p>
						<table cellpadding="0" cellspacing="0">
						<tr>
							<th style="background-color:#555; color: #fff; border:1px solid #fff; padding:5px;">Day/Date</th>
							<th style="background-color:#555; color: #fff; border:1px solid #fff; padding:5px;">Title</th>
						</tr>';

						foreach($bsMeeting as $bs)
						{
							$date = explode(" ",$bs['meeting_date']);
							$schedule_date = date("l / j F Y", strtotime($date[0]));
							$html.='<tr>
									<td style="border:1px solid #bbb; padding:5px;">'.$schedule_date.'</td>
									<td style="border:1px solid #bbb; padding:5px;"><a href="'.$this->baseUrl.'/default/bs/viewdetail/id/'.$bs['bs_meeting_id'].'" target="_blank">'.$bs['meeting_title'].'</a></td>
								</tr>';
						}
						$html .= "</table>";

						require_once 'Zend/Mail.php';
						$mail = new Zend_Mail();
						$mail->setBodyHtml($html);
						$mail->setFrom("srt@pakuwon.com");

						$mail->addTo("emmadarmawan@pakuwon.com");

						if($site['site_id'] == 1) $mail->addTo("lilimulyadi@pakuwon.com");
						elseif($site['site_id'] == 2) $mail->addTo("lusiana@pakuwon.com");
						elseif($site['site_id'] == 3) $mail->addTo("achmadhakiki@pakuwon.com");

						$mail->addCC("eiffeltedja@pakuwon.com");
						$mail->addBcc("emmadarmawan@pakuwon.com");
						$mail->addBcc("habsaribanoewati@pakuwon.com");
					
						$mail->setSubject($site['initial'] . ' - BS Meeting MOM needs to be approved');
						
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
		if($this->allowDeleteBSMeeting)
		{
			$params = $this->_getAllParams();
			$params['user_id'] = $this->ident['user_id'];
			$bsTable = $this->loadModel('bs');
			$bsTable->deleteTopicImage($params['id']);

			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "Delete BS Meeting Topic Image";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);	
		}
		else {
			$this->_response->setRedirect($this->baseUrl);
			$this->_response->sendResponse();
			exit();
		}
	}

	public function getbsissuesAction() {
		$params = $this->_getAllParams();
		$commentsTable = $this->loadModel('comments');
		Zend_Loader::LoadClass('issueClass', $this->modelDir);
		$issueClass = new issueClass();
		$issues = $issueClass->getBSIssues();
		foreach($issues as &$i)
		{
			$issue_date_time = explode(" ",$i['issue_date']);
			$i['date'] = date("j-M-Y", strtotime($issue_date_time[0]));

			$i['comments'] = $commentsTable->getCommentsByIssueId($i['issue_id'], '1');
		}
		$this->view->issues = $issues;

		echo $this->view->render('bs_issue_finding.tpl');
	}

	public function getfitoutongoingAction() {
		if($this->showFitOutOnGoing == 1 && $this->site_id < 4)
		{
			$params = $this->_getAllParams();
			if($this->site_id == 1) $sitename = "GANDARIA CITY";
			elseif($this->site_id == 2) $sitename = "KOTA KASABLANKA";
			elseif($this->site_id == 3) $sitename = "PLAZA BLOK M";
			$bsTable = $this->loadModel('bs');
			$prevBsMeeting = $bsTable->getPrevBsMeeting($params['cur_meeting_date']);
			if(!empty($prevBsMeeting))
			{
				$prevBsMeetingDateTime = explode(" ", $prevBsMeeting['meeting_date']);
				$prevBsMeetingDate = $prevBsMeetingDateTime[0];
			}
			else {
				$bs_meeting_date = explode("-", $params['cur_meeting_date']);
				$prevBsMeetingDate = date("Y-m-d", mktime(0, 0, 0, $bs_meeting_date[1]  , $bs_meeting_date[2]-7, $bs_meeting_date[0]));					
			}

			$ch = curl_init(); 
			curl_setopt($ch, CURLOPT_URL, "http://fom.pakuwon.com/views/ws/index.php?ACT=ongoing&D1=".$prevBsMeetingDate."&D2=".$params['cur_meeting_date']."&MALL=".urlencode($sitename)."&PIN="); 
			//return the transfer as a string 
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
			$output = curl_exec($ch); 
			curl_close($ch); 
			$this->view->fitOutOnGoing = json_decode($output, true);

			echo $this->view->render('hod_fitout_ongoing.tpl');
		}
	}

	public function addprogressAction() {
		if($this->showAddBSMeeting)
		{
			$params = $this->_getAllParams();
			$params['user_id'] = $this->ident['user_id'];
			$bsTable = $this->loadModel('bs');
			$params['followup_id'] = $followup_id = $bsTable->saveFollowUp($params);

			if(!empty($_FILES["followup_image"]))
			{
				$magickPath = "/usr/bin/convert";
				$datafolder = $this->config->paths->html."/images/bs_meeting/".date("Ym")."/";
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
						$bsTable->saveFollowUpImage($params);
						
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
			$logData['action'] = "Save BS Meeting Topic Follow Up Progress";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);	

			$currentFollowUp = $bsTable->getCurrentFollowUp($params['topic_id'], $params['bs_meeting_id']);
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
							$currentFollowUpImages = $bsTable->getFollowUpImages($curfu['followup_id']);
							foreach($currentFollowUpImages as $img)
							{
								$curFollowUpTopic .= '<a class="image-popup-vertical-fit" href="/images/bs_meeting'.$img['filename'].'"><img src="/images/bs_meeting'.str_replace(".", "_thumb.", $img['filename']).'" class="bs_thumb"></a> '; 
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
		if($this->showAddBSMeeting)
		{
			$params = $this->_getAllParams();

			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "Get BS Meeting Topic Follow By Id";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);	

			$bsTable = $this->loadModel('bs');
			$topicFollowUp = $bsTable->getFollowUpById($params['id']);
			$topicFollowUpImages = $bsTable->getFollowUpImages($params['id']);
			if(!empty($topicFollowUpImages))
			{
				$imagelist = '<ul class="bs_image_list">';
				foreach($topicFollowUpImages as $image)
				{
					$imagelist .= '<li><a class="image-popup-vertical-fit" href="/images/bs_meeting'.$image['filename'].'"><img src="/images/bs_meeting'.str_replace(".", "_thumb.", $image['filename']).'" class="bs_thumb"></a>  <i class="fa fa-trash remove-image-db" data-id="'.$image['image_id'].'" style="cursor:pointer;"></i></li>';
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
		if($this->allowDeleteBSMeeting)
		{
			$params = $this->_getAllParams();
			$params['user_id'] = $this->ident['user_id'];
			$bsTable = $this->loadModel('bs');
			$bsTable->deleteFollowUpImage($params['id']);

			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "Delete BS Meeting Topic Follow Up Image";
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
		if($this->showBSMeeting || $this->showBSMeetingAdmin)
		{
			$params = $this->_getAllParams();
			$params['user_id'] = $this->ident['user_id'];
			$bsTable = $this->loadModel('bs');

			if($_FILES["attachment"]["size"] > 0)
			{
				$datafolder = $this->config->paths->html."/images/bs_meeting/comments_".date("Ym")."/";
				if(!is_dir($datafolder)) mkdir($datafolder, 0777, true);
				$ext = explode(".",$_FILES["attachment"]['name']);
				$filename = "bs_meeting_cmt_".date("YmdHis").".".$ext[count($ext)-1];
				if(move_uploaded_file($_FILES["attachment"]["tmp_name"], $datafolder.$filename))
				{
					
					if(in_array(strtolower($ext[count($ext)-1]), array("jpg", "jpeg", "png","bmp")))
					{
						$magickPath = "/usr/bin/convert";
						/*** resize image if size greater than 500 Kb ***/
						if(filesize($datafolder.$filename) > 500000) exec($magickPath . ' ' . $datafolder.$filename . ' -resize 800x800\> ' . $datafolder.$filename);
					}					
					$params['filename'] = $filename;	
					$bsTable->addComment($params);
				}		
			}
			else{
				$bsTable->addComment($params);
			}	

			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "Add BS Meeting Comment";
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
		$bsTable = $this->loadModel('bs');
		$comments = $bsTable->getCommentsByBSMeetingId($params['id']);
		$commentText = "";
		if(!empty($comments)) {
			foreach($comments as $comment)
			{
				$comment_date = explode("-", $comment['comment_date']); 
				$commentText .= '<div style="border-bottom:1px solid #ccc; text-align:left; clear:both; margin-bottom:5px; padding-bottom:5px;"><strong>'.$comment['name'].' : </strong>'.$comment['comment'].'<br/>';
				if(!empty($comment['filename'])) $commentText .= '<a href="'.$this->baseUrl."/images/bs_meeting/comments_".$comment_date[0].$comment_date[1]."/".$comment['filename'].'" target="_blank"><i class="fa fa-paperclip"></i> '.$comment['filename'].'</a>';
				$commentText .= '<div style="font-size:10px; color:#aaa; text-align:right;">'.$comment['comment_date']."</div></div>";
			}
		}
		
		echo $commentText;	
	}

	function updatecommentsAction()
	{
		$params = $this->_getAllParams();
		$params['pagesize'] = 10;
		
		$bsTable = $this->loadModel('bs');
		
		$data= array();

		$commentCacheName = "bs_comments_".$this->site_id."_".$params["start"];
		$data = $this->cache->load($commentCacheName);
		$i=0;
		if(empty($data))
		{
			$bsMeetingMoM = $bsTable->getReportIds($params);	
			foreach($bsMeetingMoM as $s) {
				$data[$i]['bs_meeting_id'] = $s['bs_meeting_id'];
				$comments = $bsTable->getCommentsByBSMeetingId($s['bs_meeting_id'], '3');
				if(!empty($comments)) { 
					$comment_content = "";
					foreach($comments as $comment)
					{
						$comment_date = explode("-", $comment['comment_date']); 
						$comment_content .= '<div style="border-bottom:1px solid #ccc; text-align:left; clear:both; margin-bottom:5px; padding-bottom:5px;"><strong>'.$comment['name'].' : </strong>'.$comment['comment'].'<br/>';
						if(!empty($comment['filename'])) $comment_content .= '<a href="'.$this->baseUrl."/images/bs_meeting/comments_".$comment_date[0].$comment_date[1]."/".$comment['filename'].'" target="_blank"><i class="fa fa-paperclip"></i> '.$comment['filename'].'</a>';
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
	
	
	public function addmonthlyanalysisAction() {
		if($this->addBuildingServiceMonthlyAnalysis)
		{
			$params = $this->_getAllParams();

			if(!empty($params['id'])) 
			{
				$this->view->monthly_analysis_id = $params['id'];		
				$bsClass = $this->loadModel('bs');
				$monthly_analysis = $bsClass->geMonthlyAnalysisById($params['id']);
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
				$modus = $modusClass->getModus('10');
				//$this->cache->save($modus, "modus_".$this->site_id."_2_".$ym, array("modus_".$this->site_id."_2_".$ym), 0);
			}
			
			//$totalModusPerMonth = $this->cache->load("total_modus_per_month_".$this->site_id."_1_".$ym);
			if(empty($totalModusPerMonth))
			{	
				$totalModusPerMonth =  array();
				for($b=1; $b<=$m; $b++) // get rekapitulasi utk bulan ini dan bulan2 sblmnya
				{
					$totalModus = $issueClass->getIssuesByModus($b, $y, '10');
					foreach($totalModus as $tm) {
						$totalModusPerMonth[$b][$tm['modus_id']] = $tm['total_modus'];
					}
				}
				//$this->cache->save($totalModusPerMonth, "total_modus_per_month_".$this->site_id."_1_".$ym, array("total_modus_per_month_".$this->site_id."_1_".$ym), 0);
			}
			else{
				$totalModus = $issueClass->getIssuesByModus($m, $y, '10');
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
					
					$analisa_hari = $issueClass->getMonthlyIssuesByDay($mo['kejadian_id'], $m, $y, '10');
					if(!empty($analisa_hari))
					{
						foreach($analisa_hari as $ah) {
							$rekap[$i]['analisa_hari'][$ah['day']]= $ah['total'];
						}
					}
					
					$rekap[$i]['analisa_jam'][0] = $issueClass->getMonthlyIssuesByTime($mo['kejadian_id'], $m, $y, '09:00:00', '12:00:00', '10');
					$rekap[$i]['analisa_jam'][1] = $issueClass->getMonthlyIssuesByTime($mo['kejadian_id'], $m, $y, '12:00:00', '16:00:00', '10');
					$rekap[$i]['analisa_jam'][2] = $issueClass->getMonthlyIssuesByTime($mo['kejadian_id'], $m, $y, '16:00:00', '19:00:00', '10');
					$rekap[$i]['analisa_jam'][3] = $issueClass->getMonthlyIssuesByTime($mo['kejadian_id'], $m, $y, '19:00:00', '23:00:00', '10');
					$rekap[$i]['analisa_jam'][4] = $issueClass->getMonthlyIssuesByTime($mo['kejadian_id'], $m, $y, '23:00:00', '09:00:00', '10');
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
				
				$uraian_kejadian = $issueClass->getIssuesByModusId($mo['modus_id'], $m, $y, '10');

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
			
			$this->view->urutan_hari_tertinggi = $issueClass->getTotalIssuesByDayDescending($m, $y, '10');
			$urutan_total_jam[0] = $issueClass->getTotalIssuesByTimePeriode($m, $y, '09:00:00', '12:00:00', '10');
			$urutan_total_jam[1] = $issueClass->getTotalIssuesByTimePeriode($m, $y, '12:00:00', '16:00:00', '10');
			$urutan_total_jam[2] = $issueClass->getTotalIssuesByTimePeriode($m, $y, '16:00:00', '19:00:00', '10');
			$urutan_total_jam[3] = $issueClass->getTotalIssuesByTimePeriode($m, $y, '19:00:00', '23:00:00', '10');
			$urutan_total_jam[4] = $issueClass->getTotalIssuesByTimePeriode($m, $y, '23:00:00', '09:00:00', '10');
			arsort($urutan_total_jam);

			$this->view->urutan_total_jam = $urutan_total_jam;
			
			$this->view->incidents = $issueClass->getBuildingServiceIssueSummary($m, $y, $params['id']);

			$urutan_total_issue_tenant = $issueClass->getTotalIssuesByTenantPublik($m, $y, '0', '10');
			if(!empty($urutan_total_issue_tenant))
			{
				$urutan_total_all_issue_tenant = 0;
				foreach($urutan_total_issue_tenant as &$t)
				{
					$data = array();
					$data = $issueClass->getTotalIssuesByLocation($m, $y, '0', $t['location'], $t['floor_id'], '10');
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

			$urutan_total_issue_publik = $issueClass->getTotalIssuesByTenantPublik($m, $y, '1', '10');
			if(!empty($urutan_total_issue_publik))
			{
				$urutan_total_all_issue_publik = 0;
				foreach($urutan_total_issue_publik as &$p)
				{
					$data = array();
					$data = $issueClass->getTotalIssuesByLocation($m, $y, '1', $p['location'], $p['floor_id'], '10');
					
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
			$list_tangkapan = $issueClass->getPelakuTertangkapByCategory($y, '10');
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

			$pelaku_tertangkap_detail = $issueClass->getPelakuTertangkapDetail($m, $y, '10');
			foreach($pelaku_tertangkap_detail as &$pelaku)
			{
				$tgl = explode(" ", $pelaku['issue_date']);
				$pelaku['date'] = date("j M Y", strtotime($tgl[0]))." ".$tgl[1];
			}
			$this->view->pelaku_tertangkap_detail = $pelaku_tertangkap_detail;

			$listIssues = $issueClass->getMonthlyAnalysisIssues($m, $y, '10');
			foreach($listIssues as &$issue)
			{
				$tgl = explode(" ", $issue['issue_date']);
				$issue['date'] = date("j M Y", strtotime($tgl[0]))." ".$tgl[1];
			}
			$this->view->listIssues = $listIssues;

			Zend_Loader::LoadClass('incidentClass', $this->modelDir);
			$incidentClass = new incidentClass();	
			$this->view->listKejadian = $incidentClass->getIncidentByCategoryId('10');


			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "Add Building Service Monthly Analysis";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);	

			$this->renderTemplate('form_bs_monthly_analysis.tpl'); 
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
			$bsClass = $this->loadModel('bs');
			$params['monthly_analysis_id'] = $bsClass->saveMonthlyAnalysis($params);
		}
		
		Zend_Loader::LoadClass('issueClass', $this->modelDir);
		$issueClass = new issueClass();
		$this->view->incidents = $issueClass->getBuildingServiceIssueSummary(date("m"), date("Y"));

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
			$monthlyanalysissummaryClass->saveMonthlyAnalysis($data, '10');
			$i++;
		}

		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "Save Building Service Monthly Analysis";
		$logData['data'] = json_encode($params);
		$logsTable->insertLogs($logData);	

		$this->_response->setRedirect($this->baseUrl.'/default/bs/viewmonthlyanalysis');
		$this->_response->sendResponse();
		exit();
	}

	public function viewmonthlyanalysisAction() {
		$params = $this->_getAllParams();
		$this->view->ident = $this->ident;
		if(empty($params['start'])) $params['start'] = '0';
		$params['pagesize'] = 10;
		$this->view->start = $params['start'];
		$bsClass = $this->loadModel('bs');
		$monthlyAnalysis = $bsClass->getMonthlyAnalysis($params);
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
		$totalMonthlyAnalysis = $bsClass->getTotalMonthlyAnalysis();
		if($totalMonthlyAnalysis > 10)
		{
			if($params['start'] >= 10)
			{
				$this->view->firstPageUrl = "/default/bs/viewmonthlyanalysis";
				$this->view->prevUrl = "/default/bs/viewmonthlyanalysis/start/".($params['start']-$params['pagesize']);
			}
			if($params['start'] < (floor(($totalMonthlyAnalysis-1)/10)*10))
			{
				$this->view->nextUrl = "/default/bs/viewmonthlyanalysis/start/".($params['start']+$params['pagesize']);
				$this->view->lastPageUrl = "/default/bs/viewmonthlyanalysis/start/".(floor(($totalMonthlyAnalysis-1)/10)*10);
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
		$logData['action'] = "View Building Service Monthly Analysis List";
		$logData['data'] = "";
		$logsTable->insertLogs($logData);	

		$this->renderTemplate('view_building_service_monthly_analysis.tpl'); 
	}

	public function viewdetailmonthlyanalysisAction() {
		$params = $this->_getAllParams();

		if(!empty($params['id'])) 
		{
			$this->view->monthly_analysis_id = $params['id'];		
			$bsClass = $this->loadModel('bs');
			$monthly_analysis = $bsClass->geMonthlyAnalysisById($params['id']);
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
				$modus = $modusClass->getModus('10');
			}

			//$totalModusPerMonth = $this->cache->load("total_modus_per_month_".$this->site_id."_1_".$ym);
			if(empty($totalModusPerMonth))
			{	
				$totalModusPerMonth =  array();
				for($b=1; $b<=$m; $b++) // get rekapitulasi utk bulan ini dan bulan2 sblmnya
				{
					$totalModus = $issueClass->getIssuesByModus($b, $y, '10');
					foreach($totalModus as $tm) {
						$totalModusPerMonth[$b][$tm['modus_id']] = $tm['total_modus'];
					}
				}
			}
			else{
				$totalModus = $issueClass->getIssuesByModus($m, $y, '10');
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
						$analisa_hari = $issueClass->getMonthlyIssuesByDay($mo['kejadian_id'], $m, $y, '10');
						
						if(!empty($analisa_hari))
						{
							foreach($analisa_hari as $ah) {
								$rekap[$i]['analisa_hari'][$ah['day']]= $ah['total'];
							}
						}
						$rekap[$i]['analisa_jam'][0] = $issueClass->getMonthlyIssuesByTime($mo['kejadian_id'], $m, $y, '09:00:00', '12:00:00', '10');
						$rekap[$i]['analisa_jam'][1] = $issueClass->getMonthlyIssuesByTime($mo['kejadian_id'], $m, $y, '12:00:00', '16:00:00', '10');
						$rekap[$i]['analisa_jam'][2] = $issueClass->getMonthlyIssuesByTime($mo['kejadian_id'], $m, $y, '16:00:00', '19:00:00', '10');
						$rekap[$i]['analisa_jam'][3] = $issueClass->getMonthlyIssuesByTime($mo['kejadian_id'], $m, $y, '19:00:00', '23:00:00', '10');
						$rekap[$i]['analisa_jam'][4] = $issueClass->getMonthlyIssuesByTime($mo['kejadian_id'], $m, $y, '23:00:00', '09:00:00', '10');
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
					$uraian_kejadian = $issueClass->getIssuesByModusId($mo['modus_id'], $m, $y, '10');
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
				$this->view->urutan_hari_tertinggi = $issueClass->getTotalIssuesByDayDescending($m, $y, '10');
				$urutan_total_jam[0] = $issueClass->getTotalIssuesByTimePeriode($m, $y, '09:00:00', '12:00:00', '10');
				$urutan_total_jam[1] = $issueClass->getTotalIssuesByTimePeriode($m, $y, '12:00:00', '16:00:00', '10');
				$urutan_total_jam[2] = $issueClass->getTotalIssuesByTimePeriode($m, $y, '16:00:00', '19:00:00', '10');
				$urutan_total_jam[3] = $issueClass->getTotalIssuesByTimePeriode($m, $y, '19:00:00', '23:00:00', '10');
				$urutan_total_jam[4] = $issueClass->getTotalIssuesByTimePeriode($m, $y, '23:00:00', '09:00:00', '10');
				arsort($urutan_total_jam);
				$this->view->urutan_total_jam = $urutan_total_jam;

				$this->view->incidents = $issueClass->getBuildingServiceIssueSummary($m, $y, $params['id']);
				$urutan_total_issue_tenant = $issueClass->getTotalIssuesByTenantPublik($m, $y, '0', '10');
				if(!empty($urutan_total_issue_tenant))
				{
					$urutan_total_all_issue_tenant = 0;
					foreach($urutan_total_issue_tenant as &$t)
					{
						$data = array();
						$data = $issueClass->getTotalIssuesByLocation($m, $y, '0', $t['location'], $t['floor_id'], '10');
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

				$urutan_total_issue_publik = $issueClass->getTotalIssuesByTenantPublik($m, $y, '1', '10');
				if(!empty($urutan_total_issue_publik))
				{
					$urutan_total_all_issue_publik = 0;
					foreach($urutan_total_issue_publik as &$p)
					{
						$data = array();
						$data = $issueClass->getTotalIssuesByLocation($m, $y, '1', $p['location'], $p['floor_id'], '10');
						
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
			$list_tangkapan = $issueClass->getPelakuTertangkapByCategory($y, '10');
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

			$pelaku_tertangkap_detail = $issueClass->getPelakuTertangkapDetail($m, $y, '10');
			foreach($pelaku_tertangkap_detail as &$pelaku)
			{
				$tgl = explode(" ", $pelaku['issue_date']);
				$pelaku['date'] = date("j M Y", strtotime($tgl[0]))." ".$tgl[1];
			}
			$this->view->pelaku_tertangkap_detail = $pelaku_tertangkap_detail;

			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "View Building Service Monthly Analysis Detail";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);	

			//echo "<pre>"; print_r($pelaku_tertangkap_detail); exit();
			$this->renderTemplate('bs_monthly_analysis_detail.tpl'); 
		}
	}

	public function downloadmonthlyanalysisAction() {		
		$params = $this->_getAllParams();

		if(!empty($params['id']))
		{			
			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "Export Building Service Monthly Analysis Report to PDF";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);

			$monthly_analysis_id = $params['id'];		
			$bsClass = $this->loadModel('bs');
			$monthly_analysis = $bsClass->geMonthlyAnalysisById($params['id']);
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
				$modus = $modusClass->getModus('10');
			}

			//$totalModusPerMonth = $this->cache->load("total_modus_per_month_".$this->site_id."_1_".$ym);
			if(empty($totalModusPerMonth))
			{	
				$totalModusPerMonth =  array();
				for($b=1; $b<=$m; $b++) // get rekapitulasi utk bulan ini dan bulan2 sblmnya
				{
					$totalModus = $issueClass->getIssuesByModus($b, $y, '10');
					foreach($totalModus as $tm) {
						$totalModusPerMonth[$b][$tm['modus_id']] = $tm['total_modus'];
					}
				}
			}
			else{
				$totalModus = $issueClass->getIssuesByModus($m, $y, '10');
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
						$analisa_hari = $issueClass->getMonthlyIssuesByDay($mo['kejadian_id'], $m, $y, '10');
						
						if(!empty($analisa_hari))
						{
							foreach($analisa_hari as $ah) {
								$rekap[$i]['analisa_hari'][$ah['day']]= $ah['total'];
							}
						}
						$rekap[$i]['analisa_jam'][0] = $issueClass->getMonthlyIssuesByTime($mo['kejadian_id'], $m, $y, '09:00:00', '12:00:00', '10');
						$rekap[$i]['analisa_jam'][1] = $issueClass->getMonthlyIssuesByTime($mo['kejadian_id'], $m, $y, '12:00:00', '16:00:00', '10');
						$rekap[$i]['analisa_jam'][2] = $issueClass->getMonthlyIssuesByTime($mo['kejadian_id'], $m, $y, '16:00:00', '19:00:00', '10');
						$rekap[$i]['analisa_jam'][3] = $issueClass->getMonthlyIssuesByTime($mo['kejadian_id'], $m, $y, '19:00:00', '23:00:00', '10');
						$rekap[$i]['analisa_jam'][4] = $issueClass->getMonthlyIssuesByTime($mo['kejadian_id'], $m, $y, '23:00:00', '09:00:00', '10');
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
					$uraian_kejadian = $issueClass->getIssuesByModusId($mo['modus_id'], $m, $y, '10');
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
				
				$urutan_hari_tertinggi = $issueClass->getTotalIssuesByDayDescending($m, $y, '10');
				$urutan_total_jam[0] = $issueClass->getTotalIssuesByTimePeriode($m, $y, '09:00:00', '12:00:00', '10');
				$urutan_total_jam[1] = $issueClass->getTotalIssuesByTimePeriode($m, $y, '12:00:00', '16:00:00', '10');
				$urutan_total_jam[2] = $issueClass->getTotalIssuesByTimePeriode($m, $y, '16:00:00', '19:00:00', '10');
				$urutan_total_jam[3] = $issueClass->getTotalIssuesByTimePeriode($m, $y, '19:00:00', '23:00:00', '10');
				$urutan_total_jam[4] = $issueClass->getTotalIssuesByTimePeriode($m, $y, '23:00:00', '09:00:00', '10');
				arsort($urutan_total_jam);

				$incidents = $issueClass->getBuildingServiceIssueSummary($m, $y, $params['id']);
				
				/*$urutan_total_issue_tenant = $issueClass->getTotalIssuesByTenantPublik($m, $y, '0', '10');
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
			$list_tangkapan = $issueClass->getPelakuTertangkapByCategory($y, '10');
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

			$pelaku_tertangkap_detail = $issueClass->getPelakuTertangkapDetail($m, $y, '10');
			foreach($pelaku_tertangkap_detail as &$pelaku)
			{
				$tgl = explode(" ", $pelaku['issue_date']);
				$pelaku['date'] = date("j M Y", strtotime($tgl[0]))." ".$tgl[1];
			}
			
			require_once('fpdf/mc_table.php');
			$pdf=new PDF_MC_Table();
			$pdf->AddPage();
			
			$pdf->SetTitle($this->ident['initial']." - HUMAN OPERATION MONTHLY ANALYTICS - ".$monthYear);
			$pdf->SetFont('Arial','B',12);
			$pdf->Write(5, $this->ident['initial']." - HUMAN OPERATION MONTHLY ANALYTICS - ".$monthYear);
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
			$pdf->SetFont('Arial','B',7);
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
			$pdf->SetWidths(array(6, 27, 15, 72, 72));
			if(!empty($incidents)) {
				$i = 1;
				foreach($incidents as $incident) {
					$pdf->Row(array($i,$incident['kejadian'],$incident['total_kejadian'], str_replace("<br>","\n",stripslashes($incident['analisa'])), str_replace("<br>","\n",stripslashes($incident['tindakan']))));
					$i++; 
				} 
			}

			$pdf->Output('I', $this->ident['initial']."_safety_monthly_analysis_report_".str_replace(" ","",$monthYear).".pdf", false);
		}		
	}
}
?>