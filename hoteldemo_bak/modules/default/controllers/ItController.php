<?php

require_once('actionControllerBase.php');
require_once('Zend/Json.php');

class ItController extends actionControllerBase
{
	public function addAction() {
		if($this->showAddITMeeting)
		{
			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "Add IT Meeting";
			$logData['data'] = "Add IT Meeting";
			$logsTable->insertLogs($logData);	

			$this->view->title = "Add IT Meeting MoM";

			$itTable = $this->loadModel('it');
			$prevItMeeting = $itTable->getPrevItMeeting("");
			if(!empty($prevItMeeting['it_meeting_id'])) {
				$attendance = $itTable->getAttendanceByItMeetingId($prevItMeeting['it_meeting_id']);
				foreach($attendance as &$att)
				{
					unset($att['attendance_id']);
				}
				$this->view->attendance = $attendance;
			}

			$userClass = $this->loadModel('user');
			$this->view->users = $userClass->getAllUsersByRole(25);

			$this->renderTemplate('form_it_meeting.tpl'); 
		}
		else {
			$this->_response->setRedirect($this->baseUrl);
			$this->_response->sendResponse();
			exit();
		}
	}

	public function editAction() {
		if($this->showAddITMeeting)
		{
			$params = $this->_getAllParams();

			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "Edit IT Meeting";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);	

			$category = $this->loadModel('category');
			$this->view->categories = $category->getCategories();

			$itTable = $this->loadModel('it');
			$itMeeting = $itTable->getItMeetingById($params['id']);
			$itMeetingDateTime = explode(" ", $itMeeting['meeting_date']);
			$itMeeting['tanggal'] =$itMeetingDateTime[0];
			$this->view->itMeeting = $itMeeting;

			$this->view->attendance = $itTable->getAttendanceByItMeetingId($params['id']);

			$userClass = $this->loadModel('user');
			$this->view->users = $userClass->getAllUsersByRole(25);

			$this->view->title = "Edit IT Meeting MoM";

			$this->renderTemplate('form_it_meeting.tpl'); 
		}
		else {
			$this->_response->setRedirect($this->baseUrl);
			$this->_response->sendResponse();
			exit();
		}
	}

	public function saveAction() {
		if($this->showAddITMeeting)
		{
			$params = $this->_getAllParams();

			$params['user_id'] = $this->ident['user_id'];
			$itTable = $this->loadModel('it');
			$data['it_meeting_id'] = $itTable->saveIt($params);

			$i = 0;
			foreach($params['attendance_user_id'] as $userid)
			{
				$data["attendance_id"] = $params["attendance_id"][$i];
				$data["user_id"] = $userid;
				$itTable->saveAttendance($data);
				$i++;
			}

			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "Save IT Meeting";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);	

			$this->_response->setRedirect($this->baseUrl."/default/it/itmeetingform2/id/".$data['it_meeting_id']);
			$this->_response->sendResponse();
			exit();
		}
		else {
			$this->_response->setRedirect($this->baseUrl);
			$this->_response->sendResponse();
			exit();
		}
	}

	public function itmeetingform2Action() {
		if($this->showAddITMeeting)
		{
			$params = $this->_getAllParams();

			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "Open Form IT Meeting 2";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);	

			$itTable = $this->loadModel('it');
			$itMeeting = $itTable->getItMeetingById($params['id']);
			$itMeetingDateTime = explode(" ", $itMeeting['meeting_date']);
			$itMeeting['tanggal_jam'] = date("l, j F Y", strtotime($itMeetingDateTime[0]))." / ". $itMeeting['meeting_time'];
			$itMeeting['tanggal'] = date("j-M", strtotime($itMeetingDateTime[0]));
			$this->view->itMeeting = $itMeeting;

			$this->view->attendance = $itTable->getAttendanceByItMeetingId($params['id']);
			
			$topic = $itTable->getItMeetingTopics($params['id']);
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
			}
			$this->view->topic = $topic;

			$userClass = $this->loadModel('user');
			$this->view->users = $userClass->getAllUsersByRole(25);

			$this->view->it_meeting_id = $params['id'];

			$this->renderTemplate('form_it_meeting2.tpl'); 
		}
		else {
			$this->_response->setRedirect($this->baseUrl);
			$this->_response->sendResponse();
			exit();
		}
	}

	public function savetopicAction() {
		if($this->showAddITMeeting)
		{
			$params = $this->_getAllParams();
			$params['user_id'] = $this->ident['user_id'];
			$itTable = $this->loadModel('it');
			$itTable->saveTopic($params);

			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "Save IT Meeting Topic";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);	

			$this->_response->setRedirect($this->baseUrl."/default/it/itmeetingform2/id/".$params['it_meeting_id']);
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
		
		$itTable = $this->loadModel('it');

		if(empty($params['start'])) $params['start'] = '0';
		$params['pagesize'] = 10;
		$this->view->start = $params['start'];
		$it = $itTable->getItMeetingMom();
		foreach($it as &$h)
		{
			$date = explode(" ", $h['meeting_date']);
			$arr_date = explode("-",$date[0]);
			$h['day_date'] = date("l, j F Y", mktime(0, 0, 0, $arr_date[1], $arr_date[2], $arr_date[0]));

			$h['comments'] = $itTable->getCommentsByITMeetingId($h['it_meeting_id'], '3');
		}
		$this->view->itMeeting = $it;	
		
		$totalReport = $itTable->getTotalItMom();
		if($totalReport['total'] > 10)
		{
			if($params['start'] >= 10)
			{
				$this->view->firstPageUrl = "/default/it/view";
				$this->view->prevUrl = "/default/it/view/start/".($params['start']-$params['pagesize']);
			}
			if($params['start'] < (floor(($totalReport['total']-1)/10)*10))
			{
				$this->view->nextUrl = "/default/it/view/start/".($params['start']+$params['pagesize']);
				$this->view->lastPageUrl = "/default/it/view/start/".(floor(($totalReport['total']-1)/10)*10);
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

		$this->view->showITMeetingAdmin = $this->showITMeetingAdmin;

		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "View IT Meeting MOM List";
		$logData['data'] = json_encode($params);
		$logsTable->insertLogs($logData);	

		$this->renderTemplate('view_it_meeting.tpl');  
	}

	public function viewdetailAction() {
		if($this->showITMeeting == 1 || $this->showITMeetingAdmin == 1)
		{
			$params = $this->_getAllParams();
			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "View IT Meeting MoM";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);	

			$itTable = $this->loadModel('it');
			$itMeeting = $itTable->getItMeetingById($params['id']);
			$itMeetingDateTime = explode(" ", $itMeeting['meeting_date']);
			$itMeeting['tanggal_jam'] = date("l, j F Y", strtotime($itMeetingDateTime[0]))." / ".$itMeeting['meeting_time'];
			$itMeeting['tanggal'] = date("j-M", strtotime($itMeetingDateTime[0]));
			$this->view->itMeeting = $itMeeting;
			
			$this->view->attendance = $attendance = $itTable->getAttendanceByItMeetingId($params['id']);	
			
			/*$key = array_search(25, $this->ident['role_ids']);
			if(strval($key) == "" )*/ $pic_id = 0;
			/*else $pic_id = $this->ident['user_id']; */

			$topic = $itTable->getItMeetingTopics($params['id'], $itMeetingDateTime[0], $pic_id);
			if(!empty($topic))
			{
				foreach($topic as &$t)
				{
					if(!empty($t['topic_target_id']))
					{
						$topic_target_date = $itTable->getTopicTargetDate($t['topic_id']);
						foreach($topic_target_date as $target_date)
						{
							$t['targetdate'] .= date("j M Y", strtotime($target_date['target_date'])).' <i class="fa fa-trash remove-target-date" data-id="'.$target_date['topic_target_id'].'" data-topicid="'.$t['topic_id'].'" style="cursor:pointer;"></i><br/>';
						}						
					}

					if(!empty($t['topic_start_id']))
					{
						$topic_start_date = $itTable->getTopicStartDate($t['topic_id']);
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
				}
			}
			$this->view->topic = $topic;

			if($this->showITMeetingAdmin == 1) $limitFollowUp = 2;
			else $limitFollowUp = 3;

			$prevItMeetingFollowUp = $itTable->getPrevItMeetingFollowUp($params['id'], $itMeetingDateTime[0], $pic_id);
			if(!empty($prevItMeetingFollowUp))
			{
				$prevFollowUpTopic = array();
				$prevTopicId = 0;
				foreach($prevItMeetingFollowUp as $prevfu)
				{
					if($prevTopicId != $prevfu['topic_id'])
					{	
						$f = 0;		
						$prevTopicId = $prevfu['topic_id'];
					}
					if(!empty($prevfu['follow_up']))
					{	
						if($this->showITMeetingAdmin == 1 && $prevfu['it_meeting_id'] == $params['id'] && $itMeeting['approved'] != 1) ;
						else
						{
							if($f < $limitFollowUp) {
								$fuDateTime = explode(" ", $prevfu['meeting_date']);
								$fuDate = date("j M Y", strtotime($fuDateTime[0]));
								$prevFollowUpTopic[$prevfu['topic_id']] .= "<strong>".$fuDate."</strong><br/>".nl2br($prevfu['follow_up'])."<br/><br/>";
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

			if($this->showITMeetingAdmin == 1 && $itMeeting['approved'] != 1) {
				$this->renderTemplate('view_it_meeting_detail_admin.tpl');  
			} else {
				$this->view->comments = $itTable->getCommentsByITMeetingId($params['id'], 0, 'asc');
				$this->view->approveITMeeting = $this->approveITMeeting;
				$this->renderTemplate('view_it_meeting_detail.tpl'); 
			}
		}
		else {
			$this->_response->setRedirect($this->baseUrl);
			$this->_response->sendResponse();
			exit();
		}
	}

	public function gettopicbyidAction() {
		if($this->showAddITMeeting)
		{
			$params = $this->_getAllParams();

			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "Get IT Meeting Topic By Id";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);	

			$itTable = $this->loadModel('it');
			$itMeetingTopic = $itTable->getItMeetingTopicById($params['id']);
			$start_date = explode(" ", $itMeetingTopic['start_date']);
			$itMeetingTopic['startdate'] = $start_date[0];
			$finish_date = explode(" ", $itMeetingTopic['finish_date']);
			$itMeetingTopic['finishdate'] = $finish_date[0];
			$this->view->itMeeting = $itMeeting;
			
			echo json_encode($itMeetingTopic);
		}
		else {
			$this->_response->setRedirect($this->baseUrl);
			$this->_response->sendResponse();
			exit();
		}
	}

	public function deletetopicAction() {
		if($this->showAddITMeeting)
		{
			$params = $this->_getAllParams();

			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "Delete IT Meeting Topic By Id";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);	

			$itTable = $this->loadModel('it');
			$itTable->deleteTopic($params['id']);
			
			$this->_response->setRedirect($this->baseUrl."/default/it/itmeetingform2/id/".$params['itid']);
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
		if($this->showAddITMeeting)
		{
			$params = $this->_getAllParams();
			$params['user_id'] = $this->ident['user_id'];
			$itTable = $this->loadModel('it');
			$itTable->addTopicTargetDate($params);

			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "Add IT Meeting Topic Target Date";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);	

			$target_dates = $itTable->getTopicTargetDate($params['topic_id']);
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
		if($this->showAddITMeeting)
		{
			$params = $this->_getAllParams();
			$params['user_id'] = $this->ident['user_id'];
			$itTable = $this->loadModel('it');
			$itTable->addTopicStartDate($params);

			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "Add IT Meeting Topic Start Date";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);	

			$start_dates = $itTable->getTopicStartDate($params['topic_id']);
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
		if($this->showAddITMeeting)
		{
			$params = $this->_getAllParams();
			if(!empty($params['topic_id']))
			{
				$itTable = $this->loadModel('it');
				$i=0;
				foreach($params['topic_id'] as $topic_id)
				{
					$data['topic_id'] = $topic_id;
					$data['finish_date'] = $params['finish_date'][$i];
					$data['done'] = $params['done'][$i];
					if($data['done'] == '1') $data['done_it_meeting_id'] = $params['it_meeting_id'];
					else $data['done_it_meeting_id'] = 0;
					$itTable->updateFinishDate($data);
					if(!empty($params['followup'][$i]))
					{
						$data['followup'] = $params['followup'][$i];
						$data['user_id'] = $this->ident['user_id'];
						$data['followup_id'] = $params['followup_id'][$i];
						$data['it_meeting_id'] = $params['it_meeting_id'];
						if(empty($data['followup_id']))
						{
							$data['followup_id'] = $itTable->checkIfFollowUpExist($data['topic_id'], $data['it_meeting_id']);
						}
						$itTable->saveFollowUp($data);
					}
					$i++;
				}
			}
			
			
			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "Save IT Meeting Detail Admin";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);	

			$this->_response->setRedirect($this->baseUrl."/default/it/viewdetail/id/".$params['it_meeting_id']);
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
		if($this->showITMeeting)
		{
			$params = $this->_getAllParams();
			if(!empty($params['topic_id']))
			{
				$itTable = $this->loadModel('it');
				$i=0;
				foreach($params['topic_id'] as $topic_id)
				{
					$data['topic_id'] = $topic_id;
					$data['done_by_pic'] = $params['done_by_pic'][$i];
					if($data['done_by_pic'] == '1') $data['done_it_meeting_id_pic'] = $params['it_meeting_id'];
					else $data['done_it_meeting_id_pic'] = 0;
					$itTable->updateDoneByPic($data);
					$i++;
				}
			}			
			
			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "Save IT Meeting Detail";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);	

			$this->_response->setRedirect($this->baseUrl."/default/it/viewdetail/id/".$params['it_meeting_id']);
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
		if($this->approveITMeeting)
		{
			$params = $this->_getAllParams();
			if(!empty($params['id']))
			{
				$itTable = $this->loadModel('it');
				$params['user_id'] = $this->ident['user_id'];
				$itTable->approveMoM($params);

				$logsTable = $this->loadModel('logs');
				$logData['user_id'] = intval($this->ident['user_id']);
				$logData['action'] = "Approve IT Meeting MoM";
				$logData['data'] = json_encode($params);
				$logsTable->insertLogs($logData);	

				$this->_response->setRedirect($this->baseUrl."/default/it/viewdetail/id/".$params['id']);
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
		if($this->showITMeeting == 1 || $this->showITMeetingAdmin == 1)
		{
			$params = $this->_getAllParams();
			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "Get Follow Up IT";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);	

			$itTable = $this->loadModel('it');
			$itMeetingFollowUp = $itTable->getFollowUpByTopicId($params['id']);
			$followUpTopic="";
			if(!empty($itMeetingFollowUp))
			{
				foreach($itMeetingFollowUp as $fu)
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
		if($this->showAddITMeeting)
		{
			$params = $this->_getAllParams();

			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "Delete IT Meeting Attendance";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);	

			$itTable = $this->loadModel('it');
			$itTable->deleteAttendanceById($params['id']);
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
		$logData['action'] = "Export IT Meeting to PDF";
		$logData['data'] = json_encode($params);
		$logsTable->insertLogs($logData);	

		$itTable = $this->loadModel('it');
		$itMeeting = $itTable->getItMeetingById($params['id']);
		$itMeetingDateTime = explode(" ", $itMeeting['meeting_date']);
		$itMeeting['tanggal_jam'] = date("l, j F Y", strtotime($itMeetingDateTime[0]))." / ".$itMeeting['meeting_time'];
		$itMeeting['tanggal'] = date("j-M", strtotime($itMeetingDateTime[0]));

		$attendance = $itTable->getAttendanceByItMeetingId($params['id']);	
		
		/*$key = array_search(25, $this->ident['role_ids']);
		if(strval($key) == "" ) */ $pic_id = 0;
		/*else $pic_id = $this->ident['user_id'];*/

		$topic = $itTable->getItMeetingTopics($params['id'], $itMeetingDateTime[0], $pic_id);
		if(!empty($topic))
		{
			foreach($topic as &$t)
			{
				if(!empty($t['topic_target_id']))
				{
					$topic_target_date = $itTable->getTopicTargetDate($t['topic_id']);
					foreach($topic_target_date as $target_date)
					{
						$t['targetdate'] .= date("j M Y", strtotime($target_date['target_date']))."\n";
					}						
				}

				if(!empty($t['topic_start_id']))
				{
					$topic_start_date = $itTable->getTopicStartDate($t['topic_id']);
					foreach($topic_start_date as $start_date)
					{
						$t['startdate'] .= date("j M Y", strtotime($start_date['start_date']))."\n";
					}	
				}

				if(empty($t['finish_date']) || $t['finish_date'] == "0000-00-00 00:00:00") $t['finishdate'] = $t['finish_date'] = "";
				else {
					$finishdate = explode(" ", $t['finish_date']);
					$t['finishdate'] = $finishdate[0];
					$t['finish_date'] = date("j M Y", strtotime($finishdate[0]));
				} 
			}
		}

		$limitFollowUp = 3;

		$prevItMeetingFollowUp = $itTable->getPrevItMeetingFollowUp($params['id'], $itMeetingDateTime[0], $pic_id);
		if(!empty($prevItMeetingFollowUp))
		{
			$prevFollowUpTopic = array();
			$prevTopicId = 0;
			foreach($prevItMeetingFollowUp as $prevfu)
			{
				if($prevTopicId != $prevfu['topic_id'])
				{	
					$f = 0;		
					$prevTopicId = $prevfu['topic_id'];
				}
				if(!empty($prevfu['follow_up']))
				{	
					if($f < $limitFollowUp) {
						$fuDateTime = explode(" ", $prevfu['meeting_date']);
						$fuDate = date("j M Y", strtotime($fuDateTime[0]));
						$prevFollowUpTopic[$prevfu['topic_id']] .= $fuDate."\n".$prevfu['follow_up']."\n\n";
						$f++;
					}
					else {
						$f++;
					}				
				}
			}
		}

		require_once('fpdf/mc_table.php');
		$pdf=new PDF_MC_Table();
		$pdf->AddPage();
		$pdf->SetTitle($this->ident['initial']." - IT Meeting MoM");
		$pdf->SetFont('Arial','B',15);
		$pdf->Write(10,$this->ident['initial']." - IT Meeting MoM");
		$pdf->ln(10);

		$pdf->SetFont('Arial','B',8);
		$pdf->Cell(50,6,'Nama Site',0,0,'L');
		$pdf->Cell(138,6,$this->ident['site_fullname'],0,0,'L');
		$pdf->Ln();
		$pdf->Cell(50,6,'Judul',0,0,'L');
		$pdf->Cell(138,6,$itMeeting['meeting_title'],0,0,'L');
		$pdf->Ln();
		$pdf->Cell(50,6,'Tanggal / Jam',0,0,'L');
		$pdf->Cell(138,6,$itMeeting['tanggal_jam'],0,0,'L');
		$pdf->Ln();
		$pdf->Cell(50,6,'Peserta',0,0,'L');

		if(!empty($attendance))
		{
			$i = 1;
			foreach($attendance as $a) {
				$pdf->Cell(50,4,$a['name'],0,0,'L');
				if($i > 1 && $i % 3 == 0)
				{
					$pdf->Ln();
					$pdf->Cell(50,4,'',0,0,'L');
				}
				$i++;
			}
		}
		$pdf->Ln(9);

		if(!empty($topic))
		{
			$pdf->SetFillColor(9,41,102);
			$pdf->SetTextColor(255,255,255);
			$pdf->Cell(20,7,'PIC',1,0,'C',true);
			$pdf->Cell(60,7,'Projects / Issues',1,0,'C',true);
			$pdf->Cell(20,7,'Target Date',1,0,'C',true);
			$pdf->Cell(20,7,'Start Date',1,0,'C',true);			
			$pdf->Cell(20,7,'Finish Date',1,0,'C',true);
			$pdf->Cell(50,7,'Follow Up',1,0,'C',true);
			$pdf->Ln();
			$pdf->SetFont('Arial','',7);
			$pdf->SetTextColor(0,0,0);
			$pdf->SetWidths(array(20, 60, 20, 20, 20, 50));	
			foreach($topic as $to) { 
				$pdf->Row(array($to['name'],$to['topic'],$to['targetdate'],$to['startdate'], $to['finish_date'], $prevFollowUpTopic[$to['topic_id']]));	
			}
		}
		
		$pdf->Output('I', $this->ident['initial']."_it_meeting_mom.pdf", false);
		
	}

	public function deletestartdateAction() {
		if($this->allowDeleteITMeeting)
		{
			$params = $this->_getAllParams();
			$params['user_id'] = $this->ident['user_id'];
			$itTable = $this->loadModel('it');
			$itTable->deleteTopicStartDate($params['id']);

			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "Delete IT Meeting Topic Start Date";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);	

			$start_dates = $itTable->getTopicStartDate($params['topic_id']);
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
		if($this->allowDeleteITMeeting)
		{
			$params = $this->_getAllParams();
			$params['user_id'] = $this->ident['user_id'];
			$itTable = $this->loadModel('it');
			$itTable->deleteTopicTargetDate($params['id']);

			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "Delete IT Meeting Topic Target Date";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);	

			$target_dates = $itTable->getTopicTargetDate($params['topic_id']);
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
		if($this->showHistoryITMeeting)
		{
			$params = $this->_getAllParams();
			
			$itTable = $this->loadModel('it');

			if(empty($params['start'])) $params['start'] = '0';
			$params['pagesize'] = 10;
			$this->view->start = $params['start'];
			$topics = $itTable->getItMeetingTopicsHistory($params);
			
			foreach($topics as &$t)
			{
				if(!empty($t['finish_date']) && $t['finish_date']!="0000-00-00 00:00:00")
				{
					$date = explode(" ", $t['finish_date']);
					$arr_date = explode("-",$date[0]);
					$t['finishdate'] = date("j F Y", mktime(0, 0, 0, $arr_date[1], $arr_date[2], $arr_date[0]));
				}

				$topic_target_date = $itTable->getTopicTargetDate($t['topic_id']);
				if(!empty($topic_target_date))
				{
					foreach($topic_target_date as $target_date)
					{
						$t['targetdate'] .= date("j M Y", strtotime($target_date['target_date'])).'<br/>';
					}
				}

				$topic_start_date = $itTable->getTopicStartDate($t['topic_id']);
				if(!empty($topic_start_date))
				{
					foreach($topic_start_date as $start_date)
					{
						$t['startdate'] .= date("j M Y", strtotime($start_date['start_date'])).'<br/>';
					}	
				}

				$topicFollowUp = $itTable->getFollowUpByTopicId($t['topic_id']);
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
			
			$totalReport = $itTable->getTotalItMeetingTopicsHistory($params);
			if($totalReport['total'] > $params['pagesize'])
			{
				if($params['start'] >= $params['pagesize'])
				{
					$this->view->firstPageUrl = "/default/it/history";
					$this->view->prevUrl = "/default/it/history/start/".($params['start']-$params['pagesize']);
				}
				if($params['start'] < (floor(($totalReport['total']-1)/$params['pagesize'])*$params['pagesize']))
				{
					$this->view->nextUrl = "/default/it/history/start/".($params['start']+$params['pagesize']);
					$this->view->lastPageUrl = "/default/it/history/start/".(floor(($totalReport['total']-1)/$params['pagesize'])*$params['pagesize']);
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

			$userClass = $this->loadModel('user');
			$this->view->users = $userClass->getAllUsersByRole(25);

			$this->view->pic_id = $params['pic_id'];
			$this->view->project_name = $params['project_name'];

			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "View History IT Meeting Projects";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);	

			$this->renderTemplate('history_it_meeting.tpl');  
		}
		else {
			$this->_response->setRedirect($this->baseUrl);
			$this->_response->sendResponse();
			exit();
		}
	}

	/*** COMMENTS ***/

	public function addcommentAction() {
		if($this->showITMeeting || $this->showITMeetingAdmin)
		{
			$params = $this->_getAllParams();
			$params['user_id'] = $this->ident['user_id'];
			$itTable = $this->loadModel('it');

			if($_FILES["attachment"]["size"] > 0)
			{
				$datafolder = $this->config->paths->html."/images/it_meeting/comments_".date("Ym")."/";
				if(!is_dir($datafolder)) mkdir($datafolder, 0777, true);
				$ext = explode(".",$_FILES["attachment"]['name']);
				$filename = "it_meeting_cmt_".date("YmdHis").".".$ext[count($ext)-1];
				if(move_uploaded_file($_FILES["attachment"]["tmp_name"], $datafolder.$filename))
				{
					
					if(in_array(strtolower($ext[count($ext)-1]), array("jpg", "jpeg", "png","bmp")))
					{
						$magickPath = "/usr/bin/convert";
						/*** resize image if size greater than 500 Kb ***/
						if(filesize($datafolder.$filename) > 500000) exec($magickPath . ' ' . $datafolder.$filename . ' -resize 800x800\> ' . $datafolder.$filename);
					}					
					$params['filename'] = $filename;	
					$itTable->addComment($params);
				}		
			}
			else{
				$itTable->addComment($params);
			}	

			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "Add IT Meeting Comment";
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
		$itTable = $this->loadModel('it');
		$comments = $itTable->getCommentsByITMeetingId($params['id']);
		$commentText = "";
		if(!empty($comments)) {
			foreach($comments as $comment)
			{
				$comment_date = explode("-", $comment['comment_date']); 
				$commentText .= '<div style="border-bottom:1px solid #ccc; text-align:left; clear:both; margin-bottom:5px; padding-bottom:5px;"><strong>'.$comment['name'].' : </strong>'.$comment['comment'].'<br/>';
				if(!empty($comment['filename'])) $commentText .= '<a href="'.$this->baseUrl."/images/it_meeting/comments_".$comment_date[0].$comment_date[1]."/".$comment['filename'].'" target="_blank"><i class="fa fa-paperclip"></i> '.$comment['filename'].'</a>';
				$commentText .= '<div style="font-size:10px; color:#aaa; text-align:right;">'.$comment['comment_date']."</div></div>";
			}
		}
		
		echo $commentText;	
	}

	function updatecommentsAction()
	{
		$params = $this->_getAllParams();
		$params['pagesize'] = 10;
		
		$itTable = $this->loadModel('it');
		
		$data= array();

		$commentCacheName = "it_comments_".$this->site_id."_".$params["start"];
		$data = $this->cache->load($commentCacheName);
		$i=0;
		if(empty($data))
		{
			$itMeetingMoM = $itTable->getReportIds($params);	
			foreach($itMeetingMoM as $s) {
				$data[$i]['it_meeting_id'] = $s['it_meeting_id'];
				$comments = $itTable->getCommentsByITMeetingId($s['it_meeting_id'], '3');
				if(!empty($comments)) { 
					$comment_content = "";
					foreach($comments as $comment)
					{
						$comment_date = explode("-", $comment['comment_date']); 
						$comment_content .= '<div style="border-bottom:1px solid #ccc; text-align:left; clear:both; margin-bottom:5px; padding-bottom:5px;"><strong>'.$comment['name'].' : </strong>'.$comment['comment'].'<br/>';
						if(!empty($comment['filename'])) $comment_content .= '<a href="'.$this->baseUrl."/images/it_meeting/comments_".$comment_date[0].$comment_date[1]."/".$comment['filename'].'" target="_blank"><i class="fa fa-paperclip"></i> '.$comment['filename'].'</a>';
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