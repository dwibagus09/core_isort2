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
}
?>