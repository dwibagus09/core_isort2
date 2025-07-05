<?php

require_once('actionControllerBase.php');
require_once('Zend/Json.php');

class SafetycomitteeController extends actionControllerBase
{
    public function dashboardAction() {
		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "View Safety Comittee Dashboard";
		$logData['data'] = "View Safety Comittee Dashboard";
		$logsTable->insertLogs($logData);	

		$this->renderTemplate('safety_comittee_dashboard.tpl'); 
	}
    
	public function addAction() {
		if($this->showAddSafetyComittee)
		{
			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "Add Safety Comittee";
			$logData['data'] = "Add Safety Comittee";
			$logsTable->insertLogs($logData);	

			$category = $this->loadModel('category');
			$this->view->categories = $category->getCategories();

			$this->view->title = "Add Safety Comittee MoM";

			$safetyComitteeTable = $this->loadModel('safetycomittee');
			$prevSafetyComittee = $safetyComitteeTable->getPrevSafetyComittee("");
			if(!empty($prevSafetyComittee['safety_comittee_id'])) {
				$attendance = $safetyComitteeTable->getAttendanceBySafetyComitteeId($prevSafetyComittee['safety_comittee_id']);
				foreach($attendance as &$att)
				{
					unset($att['attendance_id']);
				}
				$this->view->attendance = $attendance;
			}

			$this->renderTemplate('form_safety_comittee.tpl'); 
		}
		else {
			$this->_response->setRedirect($this->baseUrl);
			$this->_response->sendResponse();
			exit();
		}
	}

	public function editAction() {
		if($this->showAddSafetyComittee)
		{
			$params = $this->_getAllParams();

			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "Edit Safety Comittee";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);	

			$category = $this->loadModel('category');
			$this->view->categories = $category->getCategories();

			$safetyComitteeTable = $this->loadModel('safetycomittee');
			$SafetyComittee = $safetyComitteeTable->getSafetyComitteeById($params['id']);
			$SafetyComitteeDateTime = explode(" ", $SafetyComittee['meeting_date']);
			$SafetyComittee['tanggal'] =$SafetyComitteeDateTime[0];
			$this->view->safetyComittee = $SafetyComittee;

			$this->view->attendance = $safetyComitteeTable->getAttendanceBySafetyComitteeId($params['id']);

			$this->view->title = "Edit Safety Comittee MoM";

			$this->renderTemplate('form_safety_comittee.tpl'); 
		}
		else {
			$this->_response->setRedirect($this->baseUrl);
			$this->_response->sendResponse();
			exit();
		}
	}

	public function saveAction() {
		if($this->showAddSafetyComittee)
		{
			$params = $this->_getAllParams();

			$params['user_id'] = $this->ident['user_id'];
			$safetyComitteeTable = $this->loadModel('safetycomittee');
			$data['safety_comittee_id'] = $safetyComitteeTable->saveSafetyComittee($params);

			$i = 0;
			foreach($params['attendance_department_id'] as $dept_id)
			{
				$data["attendance_id"] = $params["attendance_id"][$i];
				$data["category_id"] = $dept_id;
				$data["site_id"] = $this->site_id;
				$data["attendance_name"] = $params["attendance_name"][$i];
				$safetyComitteeTable->saveAttendance($data);
				$i++;
			}

			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "Save Safety Comittee";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);	

			$this->_response->setRedirect($this->baseUrl."/default/safetycomittee/safetycomitteeform2/id/".$data['safety_comittee_id']);
			$this->_response->sendResponse();
			exit();
		}
		else {
			$this->_response->setRedirect($this->baseUrl);
			$this->_response->sendResponse();
			exit();
		}
	}

	public function safetycomitteeform2Action() {
		if($this->showAddSafetyComittee)
		{
			$params = $this->_getAllParams();

			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "Open Form Safety Comittee 2";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);	

			$safetyComitteeTable = $this->loadModel('safetycomittee');
			$SafetyComittee = $safetyComitteeTable->getSafetyComitteeById($params['id']);
			$SafetyComitteeDateTime = explode(" ", $SafetyComittee['meeting_date']);
			$SafetyComittee['tanggal_jam'] = date("l, j F Y", strtotime($SafetyComitteeDateTime[0]))." / ". $SafetyComittee['meeting_time'];
			$SafetyComittee['tanggal'] = date("j-M", strtotime($SafetyComitteeDateTime[0]));
			$this->view->safetyComittee = $SafetyComittee;

			$this->view->attendance = $safetyComitteeTable->getAttendanceBySafetyComitteeId($params['id']);
			
			$topic = $safetyComitteeTable->getSafetyComitteeTopics($params['id']);
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
					$t['images'] = $safetyComitteeTable->getTopicImages($t['topic_id']);
				}
			}
			$this->view->topic = $topic;

			$category = $this->loadModel('category');
			$this->view->categories = $category->getCategories();

			$this->view->safety_comittee_id = $params['id'];

			$this->renderTemplate('form_safety_comittee2.tpl'); 
		}
		else {
			$this->_response->setRedirect($this->baseUrl);
			$this->_response->sendResponse();
			exit();
		}
	}

	public function savetopicAction() {
		if($this->showAddSafetyComittee)
		{
			$params = $this->_getAllParams();
			$params['user_id'] = $this->ident['user_id'];
			$safetyComitteeTable = $this->loadModel('safetycomittee');
			//$params["department_id"] = 1;
			$params['safety_comittee_topic_id'] = $topic_id = $safetyComitteeTable->saveTopic($params);

			if(!empty($_FILES["topic_image"]))
			{
				$magickPath = "/usr/bin/convert";
				$datafolder = $this->config->paths->html."/images/safety_comittee/".date("Ym")."/";
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
						$safetyComitteeTable->saveTopicImage($params);
						
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
			$logData['action'] = "Save Safety Comittee Topic";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);	

			$this->_response->setRedirect($this->baseUrl."/default/safetycomittee/SafetyComitteeform2/id/".$params['safety_comittee_id']);
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
		
		$safetyComitteeTable = $this->loadModel('safetycomittee');

		if(empty($params['start'])) $params['start'] = '0';
		$params['pagesize'] = 10;
		$this->view->start = $params['start'];
		$safetycomittee = $safetyComitteeTable->getSafetyComitteeMom($params);
		foreach($safetycomittee as &$sc)
		{
			$date = explode(" ", $sc['meeting_date']);
			$arr_date = explode("-",$date[0]);
			$sc['day_date'] = date("l, j F Y", mktime(0, 0, 0, $arr_date[1], $arr_date[2], $arr_date[0]));

			$sc['comments'] = $safetyComitteeTable->getCommentsBySafetyComitteeId($sc['safety_comittee_id'], '3');
		}
		$this->view->safetyComittee = $safetycomittee;	
		
		$totalReport = $safetyComitteeTable->getTotalSafetyComitteeMom();
		if($totalReport['total'] > 10)
		{
			if($params['start'] >= 10)
			{
				$this->view->firstPageUrl = "/default/safetycomittee/view";
				$this->view->prevUrl = "/default/safetycomittee/view/start/".($params['start']-$params['pagesize']);
			}
			if($params['start'] < (floor(($totalReport['total']-1)/10)*10))
			{
				$this->view->nextUrl = "/default/safetycomittee/view/start/".($params['start']+$params['pagesize']);
				$this->view->lastPageUrl = "/default/safetycomittee/view/start/".(floor(($totalReport['total']-1)/10)*10);
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

		$this->view->showSafetyComitteeAdmin = $this->showSafetyComitteeAdmin;

		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "View Safety Comittee MOM List";
		$logData['data'] = json_encode($params);
		$logsTable->insertLogs($logData);	

		$this->renderTemplate('view_safety_comittee.tpl');  
	}

	public function viewdetailAction() {
		if($this->showSafetyComittee == 1 || $this->showSafetyComitteeAdmin == 1)
		{

			$params = $this->_getAllParams();
			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "View Safety Comittee MoM";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);	

			$safetyComitteeTable = $this->loadModel('safetycomittee');
			$SafetyComittee = $safetyComitteeTable->getSafetyComitteeById($params['id']);
			$SafetyComitteeDateTime = explode(" ", $SafetyComittee['meeting_date']);
			$SafetyComittee['tanggal_jam'] = date("l, j F Y", strtotime($SafetyComitteeDateTime[0]))." / ".$SafetyComittee['meeting_time'];
			$SafetyComittee['tanggal'] = date("j-M", strtotime($SafetyComitteeDateTime[0]));
			$this->view->safetyComittee = $SafetyComittee;
			$this->view->currentMeetingDate = $SafetyComitteeDateTime[0];

			if($this->showSiteSelection == 1)
			{
				$siteTable = $this->loadModel('site');
				if($SafetyComittee['site_id'] != $this->ident['site_id'])
				{
					$siteTable->setSite($SafetyComittee['site_id']);
					//$this->ident['site_id'] = $SafetyComittee['site_id'];
					$this->_response->setRedirect($this->baseUrl."/default/safetycomittee/viewdetail/id/".$params['id']);
					$this->_response->sendResponse();
					exit();
				}
			}
			
			$this->view->attendance = $attendance = $safetyComitteeTable->getAttendanceBySafetyComitteeId($params['id']);	
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


			$topic = $safetyComitteeTable->getSafetyComitteeTopics($params['id'], $SafetyComitteeDateTime[0], $site_ids);
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
						$topic_target_date = $safetyComitteeTable->getTopicTargetDate($t['topic_id']);
						foreach($topic_target_date as $target_date)
						{
							$t['targetdate'] .= date("j M Y", strtotime($target_date['target_date'])).' <i class="fa fa-trash remove-target-date" data-id="'.$target_date['topic_target_id'].'" data-topicid="'.$t['topic_id'].'" style="cursor:pointer;"></i><br/>';
						}						
					}

					if(!empty($t['topic_start_id']))
					{
						$topic_start_date = $safetyComitteeTable->getTopicStartDate($t['topic_id']);
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
						$t['images'] = $safetyComitteeTable->getTopicImages($t['topic_id']);
					}
				}
			}
			$this->view->topic = $topic;

			if($this->showSafetyComitteeAdmin == 1) $limitFollowUp = $limitAccidentReview = $limitRecommendation = 1;
			else $limitFollowUp = $limitAccidentReview = $limitRecommendation = 2;

			$prevSafetyComitteeFollowUp = $safetyComitteeTable->getPrevSafetyComitteeFollowUp($params['id'], $SafetyComitteeDateTime[0], $dept_ids);
			if(!empty($prevSafetyComitteeFollowUp))
			{
				$prevFollowUpTopic = array();
				$prevTopicId = 0;
				foreach($prevSafetyComitteeFollowUp as $prevfu)
				{
					if($prevTopicId != $prevfu['topic_id'])
					{	
						$f = 0;		
						$prevTopicId = $prevfu['topic_id'];
					}
					if(!empty($prevfu['follow_up']))
					{	
						if($this->showSafetyComitteeAdmin == 1 && $prevfu['safety_comittee_id'] == $params['id'] && $SafetyComittee['approved'] != 1)
						{
							$currentFollowUp = $safetyComitteeTable->getCurrentFollowUp($prevfu['topic_id'], $params['id']);
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
											$currentFollowUpImages = $safetyComitteeTable->getFollowUpImages($curfu['followup_id']);
											foreach($currentFollowUpImages as $img)
											{
												$curFollowUpTopic .= '<a class="image-popup-vertical-fit" href="/images/safety_comittee'.$img['filename'].'"><img src="/images/safety_comittee'.str_replace(".", "_thumb.", $img['filename']).'" class="sc_thumb"></a> '; 
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
								$prevFollowUpImages = $safetyComitteeTable->getFollowUpImages($prevfu['followup_id']);
								if(!empty($prevFollowUpImages))
								{
									$prevFollowUpTopic[$prevfu['topic_id']] .= '<br/>';
									foreach($prevFollowUpImages as $img)
									{
										$prevFollowUpTopic[$prevfu['topic_id']] .= '<a class="image-popup-vertical-fit" href="/images/safety_comittee'.$img['filename'].'"><img src="/images/safety_comittee'.str_replace(".", "_thumb.", $img['filename']).'" class="sc_thumb"></a> '; 
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
			
			/*** ACCIDENT REVIEW ***/
			
			$prevSafetyComitteeAccidentReview = $safetyComitteeTable->getPrevSafetyComitteeAccidentReview($params['id'], $SafetyComitteeDateTime[0], $dept_ids);
			if(!empty($prevSafetyComitteeAccidentReview))
			{
				$prevAccidentReviewTopic = array();
				$prevTopicId = 0;
				foreach($prevSafetyComitteeAccidentReview as $prevar)
				{
					if($prevTopicId != $prevar['topic_id'])
					{	
						$f = 0;		
						$prevTopicId = $prevar['topic_id'];
					}
					if(!empty($prevar['accident_review']))
					{	
						if($this->showSafetyComitteeAdmin == 1 && $prevar['safety_comittee_id'] == $params['id'] && $SafetyComittee['approved'] != 1)
						{
							$currentAccidentReview = $safetyComitteeTable->getCurrentAccidentReview($prevar['topic_id'], $params['id']);
							if(!empty($currentAccidentReview))
							{								
								$curAccidentReviewTopic = "";
								$ar=0;
								foreach($currentAccidentReview as $curar)
								{
									if(!empty($curar['accident_review']))
									{	
										
										$arDateTime = explode(" ", $curar['added_date']);
										$arDate = date("j M Y", strtotime($arDateTime[0]));
										if($ar==0) $curAccidentReviewTopic .= "<strong>".$arDate.'</strong> <a id="edit-accident-review-'.$curar['accident_review_id'].'" class="edit-accident-review" href="#accident-review-form" data-id="'.$curar['accident_review_id'].'"><i class="fa fa-edit edit-accident-review-img" style="cursor:pointer;" data-id="'.$curar['accident_review_id'].'"></i><br/>';
										$curAccidentReviewTopic .= nl2br($curar['accident_review']);
										if(!empty($curar['filename']))
										{
											$curAccidentReviewTopic .= '<br/>';
											$currentAccidentReviewImages = $safetyComitteeTable->getAccidentReviewImages($curar['accident_review_id']);
											foreach($currentAccidentReviewImages as $img)
											{
												$curAccidentReviewTopic .= '<a class="image-popup-vertical-fit" href="/images/safety_comittee'.$img['filename'].'"><img src="/images/safety_comittee'.str_replace(".", "_thumb.", $img['filename']).'" class="ar_thumb"></a> '; 
											}
										}
										$curAccidentReviewTopic .= "<br/><br/>";
									}
									$ar++;
								}
								$currentAccidentReviewTopic[$prevar['topic_id']] = $curAccidentReviewTopic;
								$this->view->curAccidentReviewTopic = $currentAccidentReviewTopic;
							}
						}
						else
						{
							if($f < $limitAccidentReview) {
								$arDateTime = explode(" ", $prevar['meeting_date']);
								$arDate = date("j M Y", strtotime($arDateTime[0]));
								$prevAccidentReviewTopic[$prevar['topic_id']] .= "<strong>".$arDate."</strong><br/>".nl2br($prevar['accident_review']);		
								$prevAccidentReviewImages = array();
								$prevAccidentReviewImages = $safetyComitteeTable->getAccidentReviewImages($prevar['accident_review_id']);
								if(!empty($prevAccidentReviewImages))
								{
									$prevAccidentReviewTopic[$prevar['topic_id']] .= '<br/>';
									foreach($prevAccidentReviewImages as $img)
									{
										$prevAccidentReviewTopic[$prevar['topic_id']] .= '<a class="image-popup-vertical-fit" href="/images/safety_comittee'.$img['filename'].'"><img src="/images/safety_comittee'.str_replace(".", "_thumb.", $img['filename']).'" class="ar_thumb"></a> '; 
									}
								}
								$prevAccidentReviewTopic[$prevar['topic_id']] .= "<br/><br/>";
								$f++;
							}
							else {
								if($f == $limitAccidentReview)
									$prevAccidentReviewTopic[$prevar['topic_id']] .= '<a class="view-more-link" href="#accident-review-form" data-id="'.$prevar['topic_id'].'">View More...</a><br/>';
								$f++;
							}
						}						
					}
				}
				$this->view->prevAccidentReviewTopic = $prevAccidentReviewTopic;
			}
			
			/*** RECOMMENDATION ***/
			
			$prevSafetyComitteeRecommendation = $safetyComitteeTable->getPrevSafetyComitteeRecommendation($params['id'], $SafetyComitteeDateTime[0], $dept_ids);
			if(!empty($prevSafetyComitteeRecommendation))
			{
				$prevRecommendationTopic = array();
				$prevTopicId = 0;
				foreach($prevSafetyComitteeRecommendation as $prevrec)
				{
					if($prevTopicId != $prevrec['topic_id'])
					{	
						$f = 0;		
						$prevTopicId = $prevrec['topic_id'];
					}
					if(!empty($prevrec['recommendation']))
					{	
						if($this->showSafetyComitteeAdmin == 1 && $prevrec['safety_comittee_id'] == $params['id'] && $SafetyComittee['approved'] != 1)
						{
							$currentRecommendation = $safetyComitteeTable->getCurrentRecommendation($prevrec['topic_id'], $params['id']);
							
							if(!empty($currentRecommendation))
							{								
								$curRecommendationTopic = "";
								$ar=0;
								foreach($currentRecommendation as $currec)
								{
									if(!empty($currec['recommendation']))
									{	
										
										$recDateTime = explode(" ", $currec['added_date']);
										$recDate = date("j M Y", strtotime($recDateTime[0]));
										if($ar==0) $curRecommendationTopic .= "<strong>".$recDate.'</strong> <a id="edit-recommendation-'.$currec['recommendation_id'].'" class="edit-recommendation" href="#recommendation-form" data-id="'.$currec['recommendation_id'].'"><i class="fa fa-edit edit-recommendation-img" style="cursor:pointer;" data-id="'.$currec['recommendation_id'].'"></i><br/>';
										$curRecommendationTopic .= nl2br($currec['recommendation']);
										if(!empty($currec['filename']))
										{
											$curRecommendationTopic .= '<br/>';
											$currentRecommendationImages = $safetyComitteeTable->getRecommendationImages($currec['recommendation_id']);
											foreach($currentRecommendationImages as $img)
											{
												$curRecommendationTopic .= '<a class="image-popup-vertical-fit" href="/images/safety_comittee'.$img['filename'].'"><img src="/images/safety_comittee'.str_replace(".", "_thumb.", $img['filename']).'" class="rec_thumb"></a> '; 
											}
										}
										$curRecommendationTopic .= "<br/><br/>";
									}
									$ar++;
								}
								$currentRecommendationTopic[$prevrec['topic_id']] = $curRecommendationTopic;
								$this->view->curRecommendationTopic = $currentRecommendationTopic;
							}
						}
						else
						{
							if($f < $limitRecommendation) {
								$recDateTime = explode(" ", $prevrec['meeting_date']);
								$recDate = date("j M Y", strtotime($recDateTime[0]));
								$prevRecommendationTopic[$prevrec['topic_id']] .= "<strong>".$recDate."</strong><br/>".nl2br($prevrec['recommendation']);			
								$prevRecommendationImages = array();
								$prevRecommendationImages = $safetyComitteeTable->getRecommendationImages($prevrec['recommendation_id']);
								if(!empty($prevRecommendationImages))
								{
									$prevRecommendationTopic[$prevrec['topic_id']] .= '<br/>';
									foreach($prevRecommendationImages as $img)
									{
										$prevRecommendationTopic[$prevrec['topic_id']] .= '<a class="image-popup-vertical-fit" href="/images/safety_comittee'.$img['filename'].'"><img src="/images/safety_comittee'.str_replace(".", "_thumb.", $img['filename']).'" class="rec_thumb"></a> '; 
									}
								}
								$prevRecommendationTopic[$prevrec['topic_id']] .= "<br/><br/>";
								$f++;
							}
							else {
								if($f == $limitRecommendation)
									$prevRecommendationTopic[$prevrec['topic_id']] .= '<a class="view-more-link" href="#recommendation-form" data-id="'.$prevrec['topic_id'].'">View More...</a><br/>';
								$f++;
							}
						}						
					}
				}
				$this->view->prevRecommendationTopic = $prevRecommendationTopic;
			}
			
			$this->view->comments = $safetyComitteeTable->getCommentsBySafetyComitteeId($params['id'], 0, 'asc');

			if($this->showSafetyComitteeAdmin == 1 && $SafetyComittee['approved'] != 1) {
				$this->renderTemplate('view_safety_comittee_detail_admin.tpl');  
			} else {
				$this->view->approveSafetyComittee = $this->approveSafetyComittee;
				$this->renderTemplate('view_safety_comittee_detail.tpl'); 
			}
		}
		else {
			$this->_response->setRedirect($this->baseUrl);
			$this->_response->sendResponse();
			exit();
		}
	}

	public function gettopicbyidAction() {
		if($this->showAddSafetyComittee)
		{
			$params = $this->_getAllParams();

			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "Get Safety Comittee Topic By Id";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);	

			$safetyComitteeTable = $this->loadModel('safetycomittee');
			$SafetyComitteeTopic = $safetyComitteeTable->getSafetyComitteeTopicById($params['id']);
			$start_date = explode(" ", $SafetyComitteeTopic['start_date']);
			$SafetyComitteeTopic['startdate'] = $start_date[0];
			$finish_date = explode(" ", $SafetyComitteeTopic['finish_date']);
			$SafetyComitteeTopic['finishdate'] = $finish_date[0];
			$images = $safetyComitteeTable->getTopicImages($SafetyComitteeTopic['topic_id']);
			if(!empty($images))
			{
				$imagelist = '<ul class="hod_image_list">';
				foreach($images as $image)
				{
					$imagelist .= '<li><a class="image-popup-vertical-fit" href="/images/safety_comittee'.$image['filename'].'"><img src="/images/safety_comittee'.str_replace(".", "_thumb.", $image['filename']).'" class="sc_thumb"></a>  <i class="fa fa-trash remove-image-db" data-id="'.$image['image_id'].'" style="cursor:pointer;"></i></li>';
				}
				$imagelist .= "</ul>";
				$SafetyComitteeTopic['imagelist'] = $imagelist;
			}
			echo json_encode($SafetyComitteeTopic);
		}
		else {
			$this->_response->setRedirect($this->baseUrl);
			$this->_response->sendResponse();
			exit();
		}
	}

	public function deletetopicAction() {
		if($this->showAddSafetyComittee)
		{
			$params = $this->_getAllParams();

			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "Delete Safety Comittee Topic By Id";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);	

			$safetyComitteeTable = $this->loadModel('safetycomittee');
			$safetyComitteeTable->deleteTopic($params['id']);
			
			$this->_response->setRedirect($this->baseUrl."/default/safetycomittee/SafetyComitteeform2/id/".$params['scid']);
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
		if($this->showAddSafetyComittee)
		{
			$params = $this->_getAllParams();
			$params['user_id'] = $this->ident['user_id'];
			$safetyComitteeTable = $this->loadModel('safetycomittee');
			$safetyComitteeTable->addTopicTargetDate($params);

			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "Add Safety Comittee Topic Target Date";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);	

			$target_dates = $safetyComitteeTable->getTopicTargetDate($params['topic_id']);
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
		if($this->showAddSafetyComittee)
		{
			$params = $this->_getAllParams();
			$params['user_id'] = $this->ident['user_id'];
			$safetyComitteeTable = $this->loadModel('safetycomittee');
			$safetyComitteeTable->addTopicStartDate($params);

			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "Add Safety Comittee Topic Start Date";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);	

			$start_dates = $safetyComitteeTable->getTopicStartDate($params['topic_id']);
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
		if($this->showAddSafetyComittee)
		{
			$params = $this->_getAllParams();
			if(!empty($params['topic_id']))
			{
				$safetyComitteeTable = $this->loadModel('safetycomittee');
				$i=0;
				foreach($params['topic_id'] as $topic_id)
				{
					$data['topic_id'] = $topic_id;
					$data['finish_date'] = $params['finish_date'][$i];
					$data['done'] = $params['done'][$i];
					if($data['done'] == '1') $data['done_safety_comittee_id'] = $params['safety_comittee_id'];
					else $data['done_safety_comittee_id'] = 0;
					$safetyComitteeTable->updateFinishDate($data);
					if(!empty($params['followup'][$i]))
					{
						$data['followup'] = $params['followup'][$i];
						$data['user_id'] = $this->ident['user_id'];
						$data['followup_id'] = $params['followup_id'][$i];
						$data['safety_comittee_id'] = $params['safety_comittee_id'];
						if(empty($data['followup_id']))
						{
							$data['followup_id'] = $safetyComitteeTable->checkIfFollowUpExist($data['topic_id'], $data['safety_comittee_id']);
						}
						$safetyComitteeTable->saveFollowUp($data);
					}
					$i++;
				}
			}
			
			
			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "Save Safety Comittee Detail Admin";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);	

			$this->_response->setRedirect($this->baseUrl."/default/safetycomittee/viewdetail/id/".$params['safety_comittee_id']);
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
		if($this->showSafetyComittee)
		{
			$params = $this->_getAllParams();
			if(!empty($params['topic_id']))
			{
				$safetyComitteeTable = $this->loadModel('safetycomittee');
				$i=0;
				foreach($params['topic_id'] as $topic_id)
				{
					$data['topic_id'] = $topic_id;
					$data['done_by_pic'] = $params['done_by_pic'][$i];
					if($data['done_by_pic'] == '1') $data['done_safety_comittee_id_pic'] = $params['safety_comittee_id'];
					else $data['done_safety_comittee_id_pic'] = 0;
					$safetyComitteeTable->updateDoneByPic($data);
					$i++;
				}
			}			
			
			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "Save Safety Comittee Detail";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);	

			$this->_response->setRedirect($this->baseUrl."/default/safetycomittee/viewdetail/id/".$params['safety_comittee_id']);
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
		if($this->approveSafetyComittee)
		{
			$params = $this->_getAllParams();
			if(!empty($params['safety_comittee_id']))
			{
				$safetyComitteeTable = $this->loadModel('safetycomittee');
				if(!empty($params['topic_id']))
				{
					$i=0;
					foreach($params['topic_id'] as $topic_id)
					{
						$data['topic_id'] = $topic_id;
						$data['done_by_pic'] = $params['done_by_pic'][$i];
						if($data['done_by_pic'] == '1') $data['done_safety_comittee_id_pic'] = $params['safety_comittee_id'];
						else $data['done_safety_comittee_id_pic'] = 0;
						$safetyComitteeTable->updateDoneByPic($data);
						$i++;
					}
				}	
				$params['user_id'] = $this->ident['user_id'];
				$safetyComitteeTable->approveMoM($params);

				$logsTable = $this->loadModel('logs');
				$logData['user_id'] = intval($this->ident['user_id']);
				$logData['action'] = "Approve Safety Comittee MoM";
				$logData['data'] = json_encode($params);
				$logsTable->insertLogs($logData);	

				/*$this->_response->setRedirect($this->baseUrl."/default/safetycomittee/viewdetail/id/".$params['safety_comittee_id']);
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
		if($this->showSafetyComittee == 1 || $this->showSafetyComitteeAdmin == 1)
		{
			$params = $this->_getAllParams();
			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "Get Follow Up BOD";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);	

			$safetyComitteeTable = $this->loadModel('safetycomittee');
			$SafetyComitteeFollowUp = $safetyComitteeTable->getFollowUpByTopicId($params['id']);
			$followUpTopic="";
			if(!empty($SafetyComitteeFollowUp))
			{
				foreach($SafetyComitteeFollowUp as $fu)
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
		if($this->showAddSafetyComittee)
		{
			$params = $this->_getAllParams();

			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "Delete Safety Comittee Attendance";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);	

			$safetyComitteeTable = $this->loadModel('safetycomittee');
			$safetyComitteeTable->deleteAttendanceById($params['id']);
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
		$logData['action'] = "Export Safety Comittee to PDF";
		$logData['data'] = json_encode($params);
		$logsTable->insertLogs($logData);	

		$safetyComitteeTable = $this->loadModel('safetycomittee');
		$SafetyComittee = $safetyComitteeTable->getSafetyComitteeById($params['id']);
		$SafetyComitteeDateTime = explode(" ", $SafetyComittee['meeting_date']);
		$SafetyComittee['tanggal_jam'] = date("l, j F Y", strtotime($SafetyComitteeDateTime[0]))." / ".$SafetyComittee['meeting_time'];
		$SafetyComittee['tanggal'] = date("j-M", strtotime($SafetyComitteeDateTime[0]));
			
		$attendance = $safetyComitteeTable->getAttendanceBySafetyComitteeId($params['id']);	
		$pic = array();
		if(!empty($attendance))
		{
			foreach($attendance as $att)
			{
				$pic[$att['site_id']] .= "\n".$att['attendance_name'];
			}
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
		$params['category'] = 3;
		$params['solved'] = 0;
		$issues = $issueClass->getIssues($params);

		$topic = $safetyComitteeTable->getSafetyComitteeTopics($params['id'], $SafetyComitteeDateTime[0], $dept_ids);
		if(!empty($topic))
		{
			foreach($topic as &$t)
			{
				if(!empty($t['topic_target_id']))
				{
					$topic_target_date = $safetyComitteeTable->getTopicTargetDate($t['topic_id']);
					foreach($topic_target_date as $target_date)
					{
						$t['targetdate'] .= date("j M Y", strtotime($target_date['target_date']))."\n";
					}						
				}

				if(!empty($t['topic_start_id']))
				{
					$topic_start_date = $safetyComitteeTable->getTopicStartDate($t['topic_id']);
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
					$t['images'] = $safetyComitteeTable->getTopicImages($t['topic_id']);
				}
			}
		}

		$limitFollowUp = 2;

		$prevSafetyComitteeFollowUp = $safetyComitteeTable->getPrevSafetyComitteeFollowUp($params['id'], $SafetyComitteeDateTime[0], $dept_ids);
		$prevScFollowUp = array();
		foreach($prevSafetyComitteeFollowUp as $prevscfu) {
			$prevScFollowUp[$prevscfu['topic_id']][]= $prevscfu;
		}
		
		$prevSafetyComitteeAccidentReview = $safetyComitteeTable->getPrevSafetyComitteeAccidentReview($params['id'], $SafetyComitteeDateTime[0], $dept_ids);
		$prevScAccidentReview = array();
		foreach($prevSafetyComitteeAccidentReview as $prevscar) {
			$prevScAccidentReview[$prevscar['topic_id']][]= $prevscar;
		}
		
		$prevSafetyComitteeRecommendation = $safetyComitteeTable->getPrevSafetyComitteeRecommendation($params['id'], $SafetyComitteeDateTime[0], $dept_ids);
		$prevScRecommendation = array();
		foreach($prevSafetyComitteeRecommendation as $prevscrec) {
			$prevScRecommendation[$prevscrec['topic_id']][]= $prevscrec;
		}
		
		require_once('fpdf/mc_table.php');
		$pdf=new PDF_MC_Table();
		$pdf->AddPage();
		$pdf->SetTitle($this->ident['initial']." - Safety Comittee MoM");
		$pdf->SetFont('Arial','B',15);
		$pdf->Write(10,$this->ident['initial']." - Safety Comittee MoM");
		$pdf->ln(10);

		$pdf->SetFont('Arial','B',8);
		//$pdf->Cell(50,6,'Nama Site',0,0,'L');
		//$pdf->Cell(138,6,$this->ident['site_fullname'],0,0,'L');
		//$pdf->Ln();
		$pdf->Cell(50,6,'Title',0,0,'L');
		$pdf->Cell(138,6,$SafetyComittee['meeting_title'],0,0,'L');
		$pdf->Ln();
		$pdf->Cell(50,6,'Date / Time',0,0,'L');
		$pdf->Cell(138,6,$SafetyComittee['tanggal_jam'],0,0,'L');
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
			$pdf->Cell(60,7,'Kaizen',1,0,'C',true);
			$pdf->Cell(20,7,'Picture',1,0,'C',true);
			$pdf->Cell(35,7,'Location',1,0,'C',true);
			$pdf->Cell(20,7,'Date',1,0,'C',true);
			$pdf->Cell(55,7,'Comment',1,0,'C',true);
			$pdf->Ln();
			$pdf->SetFont('Arial','',7);
			$pdf->SetTextColor(0,0,0);
			$pdf->SetWidths(array(60, 20, 35, 20, 55));	
			
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
				$pdf->Row(array($issue['description'],"\n\n\n\n",$issue['location'],$issue['date'], $comments));
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
					$pdf->Image($issueImageURL.str_replace(".", "_thumb.", $issue['picture']),71,$y, $w, $h);
				}
			}
		}
		
		$pdf->Ln(5);
		

		if(!empty($topic))
		{
			$pdf->SetFont('Arial','B',9);
			$pdf->SetTextColor(0,0,0);
			$pdf->Write(10,"Projects/Issues");
			$pdf->Ln();
			$pdf->SetFont('Arial','B',8);
			$pdf->SetFillColor(158,130,75);
			$pdf->SetTextColor(255,255,255);
			$pdf->Cell(15,7,'Dept/PIC',1,0,'C',true);
			$pdf->Cell(35,7,'Projects / Issues',1,0,'C',true);
			$pdf->Cell(18,7,'Target Date',1,0,'C',true);
			$pdf->Cell(18,7,'Start Date',1,0,'C',true);			
			$pdf->Cell(18,7,'Finish Date',1,0,'C',true);
			$pdf->Cell(30,7,'Follow Up',1,0,'C',true);
			$pdf->Cell(30,7,'Accident Review',1,0,'C',true);
			$pdf->Cell(30,7,'Recommendation',1,0,'C',true);
			$pdf->Ln();
			$pdf->SetFont('Arial','',7);
			$pdf->SetTextColor(0,0,0);
			$pdf->SetWidths(array(15, 35, 18, 18, 18, 30, 30, 30));	

			$scImagePath = $this->config->paths->html.'/images/safety_comittee';
			$scImageURL = $url."images/safety_comittee";
			foreach($topic as $to) {
				$curX =  $pdf->GetX();
				$curY = $pdf->GetY();
				if($curY > 245) {
					$curY = 10;
					$pdf->AddPage();
				}
				if(!empty($to['filename']))
				{
					$to['images'] = $safetyComitteeTable->getTopicImages($to['topic_id']);
					if(!empty($to['images']))
					{
						$totalLetter = strlen($to['topic']);
						$topicY = $curY + (ceil($totalLetter/52) * 5);
						$topicX = $curX+16;
						foreach($to['images'] as $image)
						{
							if (file_exists($scImagePath.str_replace(".", "_thumb.", $image['filename']))) {
								list($width, $height) = getimagesize($scImagePath.str_replace(".", "_thumb.", $image['filename']));
								if($width > $height)
								{
									$w = 14;
									$h = 0;
								}
								else {
									$w = 0;
									$h = 14;
								}
								$to['topic'] .= $pdf->Image($scImageURL.str_replace(".", "_thumb.", $image['filename']), $topicX, $topicY, $w, $h)." ";
								$topicX = $topicX+14.5;
							}
						}		
						$to['topic'] .= "\n\n\n\n\n";				
					}
				}

				if(!empty($prevScFollowUp[$to['topic_id']]))
				{
					$j = 0;
					$prevFollowUpTopic = "";
					$fuY = $fuX = 0;
					foreach($prevScFollowUp[$to['topic_id']] as $prevfu) {
						if($j < 2)
						{
							$fuDateTime = explode(" ", $prevfu['meeting_date']);
							$fuDate = date("j M Y", strtotime($fuDateTime[0]));
							$prevFollowUpTopic .= $fuDate."\n".$prevfu['follow_up'];
							
							$totalLetter = strlen($prevfu['follow_up']);
							$fuY = $curY + (ceil($totalLetter/25) * 5) + 5;
							$fuX = $curX+105;

							$prevFollowUpTopicImages = $safetyComitteeTable->getFollowUpImages($prevfu['followup_id']);
							if(!empty($prevFollowUpTopicImages))
							{
							    $fuimgctr = 0;
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
										$prevFollowUpTopic .= $pdf->Image($scImageURL.str_replace(".", "_thumb.", $image2['filename']), $fuX, $fuY, $w, $h)." ";
										if($fuimgctr%2 == 1)
    									{
    									    $fuX = $curX+105;
    									    $fuY = $fuY+14.5;
    									    $prevFollowUpTopic .= "\n\n\n";
    									}
    									else
    									    $fuX = $fuX+14.5;
    									    
    									$fuimgctr++;
									}
								}
								$prevFollowUpTopic .= "\n\n";								
								$fuY = $fuY + 14.5;
							}
							else {
								$fuY = $fuY + 5;
							}
							$prevFollowUpTopic .= "\n\n";	
							$j++;
						}
					}
				}
				if(!empty($prevScAccidentReview[$to['topic_id']]))
				{
					$j = 0;
					$prevAccidentReviewTopic = "";
					$arY = $arX = 0;
					foreach($prevScAccidentReview[$to['topic_id']] as $prevar) {
						if($j < 2)
						{
							$arDateTime = explode(" ", $prevar['meeting_date']);
							$arDate = date("j M Y", strtotime($arDateTime[0]));
							$prevAccidentReviewTopic .= $arDate."\n".$prevar['accident_review'];
							
							$totalLetter = strlen($prevar['accident_review']);
							$arY = $curY + (ceil($totalLetter/25) * 5) + 5;
							$arX = $curX+135;
							
							$prevAccidentReviewTopicImages = $safetyComitteeTable->getAccidentReviewImages($prevar['accident_review_id']);
							if(!empty($prevAccidentReviewTopicImages))
							{
							    $arimgctr = 0;
								foreach($prevAccidentReviewTopicImages as $image2) {
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
										$prevAccidentReviewTopic .= $pdf->Image($scImageURL.str_replace(".", "_thumb.", $image2['filename']), $arX, $arY, $w, $h)." ";
										if($arimgctr%2 == 1)
    									{
    									    $arX = $curX+135;
    									    $arY = $arY+14.5;
    									    $prevAccidentReviewTopic .= "\n\n\n";
    									}
    									else
    									    $arX = $arX+14.5;
    									    
    									$arimgctr++;
									}
								}
								$prevAccidentReviewTopic .= "\n\n";								
								$arY = $arY + 14.5;
							}
							else {
								$arY = $arY + 5;
							}

							$prevAccidentReviewTopic .= "\n\n";	
							$j++;
						}
					}
				}
				if(!empty($prevScRecommendation[$to['topic_id']]))
				{
					$j = 0;
					$prevRecommendationTopic = "";
					foreach($prevScRecommendation[$to['topic_id']] as $prevrec) {
						if($j < 2)
						{
							$recDateTime = explode(" ", $prevrec['meeting_date']);
							$recDate = date("j M Y", strtotime($recDateTime[0]));
							$prevRecommendationTopic .= $recDate."\n".$prevrec['recommendation'];
							
							$totalLetter = strlen($prevrec['recommendation']);
							$recY = $curY + (ceil($totalLetter/25) * 5) + 5;
							$recX = $curX+165;
							
							$prevRecommendationTopicImages = $safetyComitteeTable->getRecommendationImages($prevrec['recommendation_id']);
							if(!empty($prevRecommendationTopicImages))
							{
							    $recimgctr = 0;
								foreach($prevRecommendationTopicImages as $image2) {
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
										$prevAccidentReviewTopic .= $pdf->Image($scImageURL.str_replace(".", "_thumb.", $image2['filename']), $recX, $recY, $w, $h)." ";
										if($recimgctr%2 == 1)
    									{
    									    $recX = $curX+165;
    									    $recY = $recY+14.5;
    									    $prevRecommendationTopic .= "\n\n\n";
    									}
    									else
    									    $recX = $recX+14.5;
    									    
    									$recimgctr++;
									}
								}
								$prevRecommendationTopic .= "\n\n";								
								$recY = $recY + 14.5;
							}
							else {
								$recY = $recY + 5;
							}
							
							$prevRecommendationTopic .= "\n\n";	
							$j++;
						}
					}
				}
				$pdf->Row(array($to['category_name']."\n".$to['pic_name'],$to['topic'],$to['targetdate'],$to['startdate'], $to['finishdate'], $prevFollowUpTopic, $prevAccidentReviewTopic, $prevRecommendationTopic));	
			}
		}
		
		$pdf->Output('I', $this->ident['initial']."_safety_comittee_mom.pdf", false);
		
	}

	public function deletestartdateAction() {
		if($this->allowDeleteSafetyComittee)
		{
			$params = $this->_getAllParams();
			$params['user_id'] = $this->ident['user_id'];
			$safetyComitteeTable = $this->loadModel('safetycomittee');
			$safetyComitteeTable->deleteTopicStartDate($params['id']);

			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "Delete Safety Comittee Topic Start Date";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);	

			$start_dates = $safetyComitteeTable->getTopicStartDate($params['topic_id']);
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
		if($this->allowDeleteSafetyComittee)
		{
			$params = $this->_getAllParams();
			$params['user_id'] = $this->ident['user_id'];
			$safetyComitteeTable = $this->loadModel('safetycomittee');
			$safetyComitteeTable->deleteTopicTargetDate($params['id']);

			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "Delete Safety Comittee Topic Target Date";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);	

			$target_dates = $safetyComitteeTable->getTopicTargetDate($params['topic_id']);
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
		if($this->showHistorySafetyComittee)
		{
			$params = $this->_getAllParams();
			
			$safetyComitteeTable = $this->loadModel('safetycomittee');

			if(empty($params['start'])) $params['start'] = '0';
			$params['pagesize'] = 10;
			$this->view->start = $params['start'];
			$topics = $safetyComitteeTable->getSafetyComitteeTopicsHistory($params);
			
			foreach($topics as &$t)
			{
				$date = explode(" ", $t['finish_date']);
				$arr_date = explode("-",$date[0]);
				$t['finishdate'] = date("j F Y", mktime(0, 0, 0, $arr_date[1], $arr_date[2], $arr_date[0]));

				$topic_target_date = $safetyComitteeTable->getTopicTargetDate($t['topic_id']);
				if(!empty($topic_target_date))
				{
					foreach($topic_target_date as $target_date)
					{
						$t['targetdate'] .= date("j M Y", strtotime($target_date['target_date'])).'<br/>';
					}
				}

				$topic_start_date = $safetyComitteeTable->getTopicStartDate($t['topic_id']);
				if(!empty($topic_start_date))
				{
					foreach($topic_start_date as $start_date)
					{
						$t['startdate'] .= date("j M Y", strtotime($start_date['start_date'])).'<br/>';
					}	
				}

				$topicFollowUp = $safetyComitteeTable->getFollowUpByTopicId($t['topic_id']);
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
								$currentFollowUpImages = $safetyComitteeTable->getFollowUpImages($fu['followup_id']);
								foreach($currentFollowUpImages as $img)
								{
									$followUpTopic .= '<a class="image-popup-vertical-fit" href="/images/safety_comittee'.$img['filename'].'"><img src="/images/safety_comittee'.str_replace(".", "_thumb.", $img['filename']).'" class="sc_thumb"></a> '; 
								}
							}
							$followUpTopic .= "<br/><br/>";							
						}
					}
				}
				$t['follow_up'] = $followUpTopic;
				
				/*** ACCIDENT REVIEW ***/
				
				$topicAccidentReview = $safetyComitteeTable->getAccidentReviewByTopicId($t['topic_id']);
				$accidentReviewTopic="";
				if(!empty($topicAccidentReview))
				{
					foreach($topicAccidentReview as $ar)
					{
						if(!empty($ar['accident_review']))
						{	
							$arDateTime = explode(" ", $ar['added_date']);
							$arDate = date("j M Y", strtotime($arDateTime[0]));
							$accidentReviewTopic .= "<strong>".$arDate."</strong><br/>".nl2br($ar['accident_review']);	
							if(!empty($ar['filename']))
							{
								$accidentReviewTopic .= '<br/>';
								$currentAccidentReviewImages = $safetyComitteeTable->getAccidentReviewImages($ar['accident_review_id']);
								foreach($currentAccidentReviewImages as $img)
								{
									$accidentReviewTopic .= '<a class="image-popup-vertical-fit" href="/images/safety_comittee'.$img['filename'].'"><img src="/images/safety_comittee'.str_replace(".", "_thumb.", $img['filename']).'" class="sc_thumb"></a> '; 
								}
							}
							$accidentReviewTopic .= "<br/><br/>";							
						}
					}
				}
				$t['accident_review'] = $accidentReviewTopic;
				
				
				/*** RECOMMENDATION ***/
				
				$topicRecommendation = $safetyComitteeTable->getRecommendationByTopicId($t['topic_id']);
				$recommendationTopic="";
				if(!empty($topicRecommendation))
				{
					foreach($topicRecommendation as $r)
					{
						if(!empty($r['recommendation']))
						{	
							$rDateTime = explode(" ", $r['added_date']);
							$rDate = date("j M Y", strtotime($rDateTime[0]));
							$recommendationTopic .= "<strong>".$rDate."</strong><br/>".nl2br($r['recommendation']);	
							
							if(!empty($r['filename']))
							{
								$recommendationTopic .= '<br/>';
								$currentRecommendationImages = $safetyComitteeTable->getRecommendationImages($r['recommendation_id']);
								foreach($currentRecommendationImages as $img)
								{
									$recommendationTopic .= '<a class="image-popup-vertical-fit" href="/images/safety_comittee'.$img['filename'].'"><img src="/images/safety_comittee'.str_replace(".", "_thumb.", $img['filename']).'" class="sc_thumb"></a> '; 
								}
							}
							$recommendationTopic .= "<br/><br/>";							
						}
					}
				}
				$t['recommendation'] = $recommendationTopic;
			}
			$this->view->topics = $topics;	
			
			$totalReport = $safetyComitteeTable->getTotalSafetyComitteeTopicsHistory($params);
			if($totalReport['total'] > $params['pagesize'])
			{
				if($params['start'] >= $params['pagesize'])
				{
					$this->view->firstPageUrl = "/default/safetycomittee/history";
					$this->view->prevUrl = "/default/safetycomittee/history/start/".($params['start']-$params['pagesize']);
				}
				if($params['start'] < (floor(($totalReport['total']-1)/$params['pagesize'])*$params['pagesize']))
				{
					$this->view->nextUrl = "/default/safetycomittee/history/start/".($params['start']+$params['pagesize']);
					$this->view->lastPageUrl = "/default/safetycomittee/history/start/".(floor(($totalReport['total']-1)/$params['pagesize'])*$params['pagesize']);
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

			$category = $this->loadModel('category');
			$this->view->categories = $category->getCategories();

			$this->view->category_id = $params['category'];
			$this->view->project_name = $params['project_name'];

			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "View History Safety Comittee Projects";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);	

			$this->renderTemplate('history_safety_comittee.tpl');  
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
			$safetyComitteeTable = $this->loadModel('safetycomittee');
			foreach($sites as $site)
			{
				if($site['site_id'] < 4)
				{
					$SafetyComittee = $safetyComitteeTable->getUnapprovedSafetyComittee($site['site_id'], date("Y-m-d"));
					if(!empty($SafetyComittee))
					{
						$html = '<p>Dear GM,</p>
						<p>Please approve Safety Comittee MOM below:</p>
						<table cellpadding="0" cellspacing="0">
						<tr>
							<th style="background-color:#555; color: #fff; border:1px solid #fff; padding:5px;">Day/Date</th>
							<th style="background-color:#555; color: #fff; border:1px solid #fff; padding:5px;">Title</th>
						</tr>';

						foreach($SafetyComittee as $sc)
						{
							$date = explode(" ",$sc['meeting_date']);
							$schedule_date = date("l / j F Y", strtotime($date[0]));
							$html.='<tr>
									<td style="border:1px solid #bbb; padding:5px;">'.$schedule_date.'</td>
									<td style="border:1px solid #bbb; padding:5px;"><a href="'.$this->baseUrl.'/default/safetycomittee/viewdetail/id/'.$sc['safety_comittee_id'].'" target="_blank">'.$sc['meeting_title'].'</a></td>
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
					
						$mail->setSubject($site['initial'] . ' - Safety Comittee MOM needs to be approved');
						
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
		if($this->allowDeleteSafetyComittee)
		{
			$params = $this->_getAllParams();
			$params['user_id'] = $this->ident['user_id'];
			$safetyComitteeTable = $this->loadModel('safetycomittee');
			$safetyComitteeTable->deleteTopicImage($params['id']);

			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "Delete Safety Comittee Topic Image";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);	
		}
		else {
			$this->_response->setRedirect($this->baseUrl);
			$this->_response->sendResponse();
			exit();
		}
	}

	public function getsafetycomitteeissuesAction() {
		$params = $this->_getAllParams();
		$commentsTable = $this->loadModel('comments');
		Zend_Loader::LoadClass('issueClass', $this->modelDir);
		$issueClass = new issueClass();
		$params['category'] = 3;
		$params['solved'] = 0;
		$issues = $issueClass->getIssues($params);
		foreach($issues as &$i)
		{
			$issue_date_time = explode(" ",$i['issue_date']);
			$i['date'] = date("j-M-Y", strtotime($issue_date_time[0]));

			$i['comments'] = $commentsTable->getCommentsByIssueId($i['issue_id'], '1');
		}
		$this->view->issues = $issues;
		
		$safetyComitteeTable = $this->loadModel('safetycomittee');
		$attendance = $safetyComitteeTable->getAttendanceBySafetyComitteeId($params['safety_comittee_id']);	
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

		echo $this->view->render('safety_comittee_issue.tpl');
	}

	public function addprogressAction() {
		if($this->showAddSafetyComittee)
		{
			$params = $this->_getAllParams();
			$params['user_id'] = $this->ident['user_id'];
			$safetyComitteeTable = $this->loadModel('safetycomittee');
			$params['followup_id'] = $followup_id = $safetyComitteeTable->saveFollowUp($params);

			if(!empty($_FILES["followup_image"]))
			{
				$magickPath = "/usr/bin/convert";
				$datafolder = $this->config->paths->html."/images/safety_comittee/".date("Ym")."/";
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
						$safetyComitteeTable->saveFollowUpImage($params);
						
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
			$logData['action'] = "Save Safety Comittee Topic Follow Up Progress";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);	

			$currentFollowUp = $safetyComitteeTable->getCurrentFollowUp($params['topic_id'], $params['safety_comittee_id']);
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
							$currentFollowUpImages = $safetyComitteeTable->getFollowUpImages($curfu['followup_id']);
							foreach($currentFollowUpImages as $img)
							{
								$curFollowUpTopic .= '<a class="image-popup-vertical-fit" href="/images/safety_comittee'.$img['filename'].'"><img src="/images/safety_comittee'.str_replace(".", "_thumb.", $img['filename']).'" class="sc_thumb"></a> '; 
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
		if($this->showAddSafetyComittee)
		{
			$params = $this->_getAllParams();

			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "Get Safety Comittee Topic Follow By Id";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);	

			$safetyComitteeTable = $this->loadModel('safetycomittee');
			$topicFollowUp = $safetyComitteeTable->getFollowUpById($params['id']);
			$topicFollowUpImages = $safetyComitteeTable->getFollowUpImages($params['id']);
			if(!empty($topicFollowUpImages))
			{
				$imagelist = '<ul class="sc_image_list">';
				foreach($topicFollowUpImages as $image)
				{
					$imagelist .= '<li><a class="image-popup-vertical-fit" href="/images/safety_comittee'.$image['filename'].'"><img src="/images/safety_comittee'.str_replace(".", "_thumb.", $image['filename']).'" class="sc_thumb"></a>  <i class="fa fa-trash remove-image-db" data-id="'.$image['image_id'].'" style="cursor:pointer;"></i></li>';
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
		if($this->allowDeleteSafetyComittee)
		{
			$params = $this->_getAllParams();
			$params['user_id'] = $this->ident['user_id'];
			$safetyComitteeTable = $this->loadModel('safetycomittee');
			$safetyComitteeTable->deleteFollowUpImage($params['id']);

			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "Delete Safety Comittee Topic Follow Up Image";
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
		if($this->showSafetyComittee || $this->showSafetyComitteeAdmin)
		{
			$params = $this->_getAllParams();
			$params['user_id'] = $this->ident['user_id'];
			$safetyComitteeTable = $this->loadModel('safetycomittee');

			if($_FILES["attachment"]["size"] > 0)
			{
				$datafolder = $this->config->paths->html."/images/safety_comittee/comments_".date("Ym")."/";
				if(!is_dir($datafolder)) mkdir($datafolder, 0777, true);
				$ext = explode(".",$_FILES["attachment"]['name']);
				$filename = "safety_comittee_cmt_".date("YmdHis").".".$ext[count($ext)-1];
				if(move_uploaded_file($_FILES["attachment"]["tmp_name"], $datafolder.$filename))
				{
					
					if(in_array(strtolower($ext[count($ext)-1]), array("jpg", "jpeg", "png","bmp")))
					{
						$magickPath = "/usr/bin/convert";
						/*** resize image if size greater than 500 Kb ***/
						if(filesize($datafolder.$filename) > 500000) exec($magickPath . ' ' . $datafolder.$filename . ' -resize 800x800\> ' . $datafolder.$filename);
					}					
					$params['filename'] = $filename;	
					$safetyComitteeTable->addComment($params);
				}		
			}
			else{
				$safetyComitteeTable->addComment($params);
			}	

			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "Add Safety Comittee Comment";
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
		$safetyComitteeTable = $this->loadModel('safetycomittee');
		$comments = $safetyComitteeTable->getCommentsBySafetyComitteeId($params['id']);
		$commentText = "";
		if(!empty($comments)) {
			foreach($comments as $comment)
			{
				$comment_date = explode("-", $comment['comment_date']); 
				$commentText .= '<div style="border-bottom:1px solid #ccc; text-align:left; clear:both; margin-bottom:5px; padding-bottom:5px;"><strong>'.$comment['name'].' : </strong>'.$comment['comment'].'<br/>';
				if(!empty($comment['filename'])) $commentText .= '<a href="'.$this->baseUrl."/images/safety_comittee/comments_".$comment_date[0].$comment_date[1]."/".$comment['filename'].'" target="_blank"><i class="fa fa-paperclip"></i> '.$comment['filename'].'</a>';
				$commentText .= '<div style="font-size:10px; color:#aaa; text-align:right;">'.$comment['comment_date']."</div></div>";
			}
		}
		
		echo $commentText;	
	}

	function updatecommentsAction()
	{
		$params = $this->_getAllParams();
		$params['pagesize'] = 10;
		
		$safetyComitteeTable = $this->loadModel('safetycomittee');
		
		$data= array();

		$commentCacheName = "sc_comments_".$this->site_id."_".$params["start"];
		//$data = $this->cache->load($commentCacheName);
		$i=0;
		if(empty($data))
		{
			$SafetyComitteeMoM = $safetyComitteeTable->getReportIds($params);	
			foreach($SafetyComitteeMoM as $s) {
				$data[$i]['safety_comittee_id'] = $s['safety_comittee_id'];
				$comments = $safetyComitteeTable->getCommentsBySafetyComitteeId($s['safety_comittee_id'], '3');
				if(!empty($comments)) { 
					$comment_content = "";
					foreach($comments as $comment)
					{
						$comment_date = explode("-", $comment['comment_date']); 
						$comment_content .= '<div style="border-bottom:1px solid #ccc; text-align:left; clear:both; margin-bottom:5px; padding-bottom:5px;"><strong>'.$comment['name'].' : </strong>'.$comment['comment'].'<br/>';
						if(!empty($comment['filename'])) $comment_content .= '<a href="'.$this->baseUrl."/images/safety_comittee/comments_".$comment_date[0].$comment_date[1]."/".$comment['filename'].'" target="_blank"><i class="fa fa-paperclip"></i> '.$comment['filename'].'</a>';
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
	
	
	/*** ACCIDENT REVIEW ***/
	
	public function addaccidentreviewAction() {
		if($this->showAddSafetyComittee)
		{
			$params = $this->_getAllParams();
			$params['user_id'] = $this->ident['user_id'];
			$safetyComitteeTable = $this->loadModel('safetycomittee');
			$params['accident_review_id'] = $accident_review_id = $safetyComitteeTable->saveAccidentReview($params);
			
			if(!empty($_FILES["accident_review_image"]))
			{
				$magickPath = "/usr/bin/convert";
				$datafolder = $this->config->paths->html."/images/safety_comittee/".date("Ym")."/";
				if(!is_dir($datafolder)) mkdir($datafolder, 0777, true);
				$i = 0;
				foreach($_FILES["accident_review_image"]['tmp_name'] as $tmpname)
				{
					$ext = explode(".",$_FILES["accident_review_image"]['name'][$i]);
					$filename = "accident_review_".$accident_review_id."_".date("YmdHis")."_".$i.".".$ext[count($ext)-1];
					if(move_uploaded_file($tmpname, $datafolder.$filename))
					{
						/*** convert to jpg ***/
						if(!in_array(strtolower($ext[count($ext)-1]), array("jpg"))) 
						{
							$newFilename =  "accident_review_".$accident_review_id."_".date("YmdHis")."_".$i.".jpg";
							exec($magickPath . ' ' . $datafolder.$filename . ' ' . $datafolder.$newFilename);
						}
						else  $newFilename = "accident_review_".$accident_review_id."_".date("YmdHis")."_".$i.".".$ext[count($ext)-1];

						$params['filename'] = "/".date("Ym")."/".$newFilename;
						$safetyComitteeTable->saveAccidentReviewImage($params);
						
						/*** create thumbnail image ***/
						exec($magickPath . ' ' . $datafolder.$newFilename . ' -resize 128x128 ' . $datafolder."accident_review_".$accident_review_id."_".date("YmdHis")."_".$i."_thumb.jpg");
						/*** resize image if size greater than 500 Kb ***/
						if(filesize($datafolder.$newFilename) > 500000) exec($magickPath . ' ' . $datafolder.$newFilename . ' -resize 800x800\> ' . $datafolder.$newFilename);
					}
					$i++;
				}
			}

			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "Save Safety Comittee Topic Accident Review";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);	

			$currentAccidentReview = $safetyComitteeTable->getCurrentAccidentReview($params['topic_id'], $params['safety_comittee_id']);
			if(!empty($currentAccidentReview))
			{
				$curAccidentReviewTopic = "";
				$j = 0;
				foreach($currentAccidentReview as $curar)
				{
					if(!empty($curar['accident_review']))
					{	
						if($j == 0)
						{
							$arDateTime = explode(" ", $curar['added_date']);
							$arDate = date("j M Y", strtotime($arDateTime[0]));
							$curAccidentReviewTopic .= "<strong>".$arDate.'</strong>  <a id="edit-accident-review-'.$curar['accident_review_id'].'" class="edit-accident-review" href="#accident-review-form" data-id="'.$curar['accident_review_id'].'"><i class="fa fa-edit edit-accident-review-img" style="cursor:pointer;" data-id="'.$curar['accident_review_id'].'"></i></a><br/>';
						}
						$curAccidentReviewTopic .= nl2br($curar['accident_review']);
						if(!empty($curar['filename']))
						{
							$curAccidentReviewTopic .= "<br/>";
							$currentAccidentReviewImages = $safetyComitteeTable->getAccidentReviewImages($curar['accident_review_id']);
							foreach($currentAccidentReviewImages as $img)
							{
								$curAccidentReviewTopic .= '<a class="image-popup-vertical-fit" href="/images/safety_comittee'.$img['filename'].'"><img src="/images/safety_comittee'.str_replace(".", "_thumb.", $img['filename']).'" class="ar_thumb"></a> '; 
							}
						}
						$curAccidentReviewTopic .= "<br/><br/>";
						$j++;
					}
				}
			}
			echo $curAccidentReviewTopic;
		}
		else {
			$this->_response->setRedirect($this->baseUrl);
			$this->_response->sendResponse();
			exit();
		}
	}

	public function getaccidentreviewbyidAction() {
		if($this->showAddSafetyComittee)
		{
			$params = $this->_getAllParams();

			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "Get Safety Comittee Topic Accident Review By Id";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);	

			$safetyComitteeTable = $this->loadModel('safetycomittee');
			$topicAccidentReview = $safetyComitteeTable->getAccidentReviewById($params['id']);
			$topicAccidentReviewImages = $safetyComitteeTable->getAccidentReviewImages($params['id']);
			if(!empty($topicAccidentReviewImages))
			{
				$imagelist = '<ul class="ar_image_list">';
				foreach($topicAccidentReviewImages as $image)
				{
					$imagelist .= '<li><a class="image-popup-vertical-fit" href="/images/safety_comittee'.$image['filename'].'"><img src="/images/safety_comittee'.str_replace(".", "_thumb.", $image['filename']).'" class="ar_thumb"></a>  <i class="fa fa-trash remove-image-db" data-id="'.$image['image_id'].'" style="cursor:pointer;"></i></li>';
				}
				$imagelist .= "</ul>";
				$topicAccidentReview['imagelist'] = $imagelist;
			}
			echo json_encode($topicAccidentReview);
		}
		else {
			$this->_response->setRedirect($this->baseUrl);
			$this->_response->sendResponse();
			exit();
		}
	}
	
	public function deleteaccidentreviewimageAction() {
		if($this->allowDeleteSafetyComittee)
		{
			$params = $this->_getAllParams();
			$params['user_id'] = $this->ident['user_id'];
			$safetyComitteeTable = $this->loadModel('safetycomittee');
			$safetyComitteeTable->deleteAccidentReviewImage($params['id']);

			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "Delete Safety Comittee Topic Accident Review Image";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);	
		}
		else {
			$this->_response->setRedirect($this->baseUrl);
			$this->_response->sendResponse();
			exit();
		}
	}
	
	/*** RECOMMENDATION ***/
	
	public function addrecommendationAction() {
		if($this->showAddSafetyComittee)
		{
			$params = $this->_getAllParams();
			$params['user_id'] = $this->ident['user_id'];
			$safetyComitteeTable = $this->loadModel('safetycomittee');
			$params['recommendation_id'] = $recommendation_id = $safetyComitteeTable->saveRecommendation($params);
			
			if(!empty($_FILES["recommendation_image"]))
			{
				$magickPath = "/usr/bin/convert";
				$datafolder = $this->config->paths->html."/images/safety_comittee/".date("Ym")."/";
				if(!is_dir($datafolder)) mkdir($datafolder, 0777, true);
				$i = 0;
				foreach($_FILES["recommendation_image"]['tmp_name'] as $tmpname)
				{
					$ext = explode(".",$_FILES["recommendation_image"]['name'][$i]);
					$filename = "recommendation_".$recommendation_id."_".date("YmdHis")."_".$i.".".$ext[count($ext)-1];
					if(move_uploaded_file($tmpname, $datafolder.$filename))
					{
						/*** convert to jpg ***/
						if(!in_array(strtolower($ext[count($ext)-1]), array("jpg"))) 
						{
							$newFilename =  "recommendation_".$recommendation_id."_".date("YmdHis")."_".$i.".jpg";
							exec($magickPath . ' ' . $datafolder.$filename . ' ' . $datafolder.$newFilename);
						}
						else  $newFilename = "recommendation_".$recommendation_id."_".date("YmdHis")."_".$i.".".$ext[count($ext)-1];

						$params['filename'] = "/".date("Ym")."/".$newFilename;
						$safetyComitteeTable->saveRecommendationImage($params);
						
						/*** create thumbnail image ***/
						exec($magickPath . ' ' . $datafolder.$newFilename . ' -resize 128x128 ' . $datafolder."recommendation_".$recommendation_id."_".date("YmdHis")."_".$i."_thumb.jpg");
						/*** resize image if size greater than 500 Kb ***/
						if(filesize($datafolder.$newFilename) > 500000) exec($magickPath . ' ' . $datafolder.$newFilename . ' -resize 800x800\> ' . $datafolder.$newFilename);
					}
					$i++;
				}
			}

			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "Save Safety Comittee Topic Recommendation";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);	

			$currentRecommendation = $safetyComitteeTable->getCurrentRecommendation($params['topic_id'], $params['safety_comittee_id']);
			if(!empty($currentRecommendation))
			{
				$curRecommendationTopic = "";
				$j = 0;
				foreach($currentRecommendation as $currec)
				{
					if(!empty($currec['recommendation']))
					{	
						if($j == 0)
						{
							$recDateTime = explode(" ", $currec['added_date']);
							$recDate = date("j M Y", strtotime($recDateTime[0]));
							$curRecommendationTopic .= "<strong>".$recDate.'</strong>  <a id="edit-recommendation-'.$currec['recommendation_id'].'" class="edit-recommendation" href="#recommendation-form" data-id="'.$currec['recommendation_id'].'"><i class="fa fa-edit edit-recommendation-img" style="cursor:pointer;" data-id="'.$currec['recommendation_id'].'"></i></a><br/>';
						}
						$curRecommendationTopic .= nl2br($currec['recommendation']);
						if(!empty($currec['filename']))
						{
							$curRecommendationTopic .= "<br/>";
							$currentRecommendationImages = $safetyComitteeTable->getRecommendationImages($currec['recommendation_id']);
							foreach($currentRecommendationImages as $img)
							{
								$curRecommendationTopic .= '<a class="image-popup-vertical-fit" href="/images/safety_comittee'.$img['filename'].'"><img src="/images/safety_comittee'.str_replace(".", "_thumb.", $img['filename']).'" class="rec_thumb"></a> '; 
							}
						}
						$curRecommendationTopic .= "<br/><br/>";
						$j++;
					}
				}
			}
			echo $curRecommendationTopic;
		}
		else {
			$this->_response->setRedirect($this->baseUrl);
			$this->_response->sendResponse();
			exit();
		}
	}

	public function getrecommendationbyidAction() {
		if($this->showAddSafetyComittee)
		{
			$params = $this->_getAllParams();

			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "Get Safety Comittee Topic Recommendation By Id";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);	

			$safetyComitteeTable = $this->loadModel('safetycomittee');
			$topicRecommendation = $safetyComitteeTable->getRecommendationById($params['id']);
			$topicRecommendationImages = $safetyComitteeTable->getRecommendationImages($params['id']);
			if(!empty($topicRecommendationImages))
			{
				$imagelist = '<ul class="rec_image_list">';
				foreach($topicRecommendationImages as $image)
				{
					$imagelist .= '<li><a class="image-popup-vertical-fit" href="/images/safety_comittee'.$image['filename'].'"><img src="/images/safety_comittee'.str_replace(".", "_thumb.", $image['filename']).'" class="rec_thumb"></a>  <i class="fa fa-trash remove-image-db" data-id="'.$image['image_id'].'" style="cursor:pointer;"></i></li>';
				}
				$imagelist .= "</ul>";
				$topicRecommendation['imagelist'] = $imagelist;
			}
			echo json_encode($topicRecommendation);
		}
		else {
			$this->_response->setRedirect($this->baseUrl);
			$this->_response->sendResponse();
			exit();
		}
	}

    public function deleterecommendationimageAction() {
		if($this->allowDeleteSafetyComittee)
		{
			$params = $this->_getAllParams();
			$params['user_id'] = $this->ident['user_id'];
			$safetyComitteeTable = $this->loadModel('safetycomittee');
			$safetyComitteeTable->deleteRecommendationImage($params['id']);

			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "Delete Safety Comittee Topic Recommendation Image";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);	
		}
		else {
			$this->_response->setRedirect($this->baseUrl);
			$this->_response->sendResponse();
			exit();
		}
	}
	
	public function exporttopictopdfAction() {	
		$params = $this->_getAllParams();

		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "Export Safety Comittee Topic to PDF";
		$logData['data'] = json_encode($params);
		$logsTable->insertLogs($logData);	

		$safetyComitteeTable = $this->loadModel('safetycomittee');
		$topic = $safetyComitteeTable->getsafetyComitteeTopicById($params['id']);
		$topic_images = $safetyComitteeTable->getTopicImages($params['id']);
		$topic_target_date = $safetyComitteeTable->getTopicTargetDate($params['id']);
		if(!empty($topic_target_date))
		{
    		foreach($topic_target_date as $target_date)
    		{
    			$topic['targetdate'] .= date("j M Y", strtotime($target_date['target_date'])).", ";
    		}		
    		$topic['targetdate'] = substr($topic['targetdate'], 0, -2);
		}
		
		$topic_start_date = $safetyComitteeTable->getTopicStartDate($params['id']);
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
		
		$followUp = $safetyComitteeTable->getFollowUpByTopicId($params['id']);
		$accidentReview = $safetyComitteeTable->getAccidentReviewByTopicId($params['id']);
		$recommendation = $safetyComitteeTable->getRecommendationByTopicId($params['id']);
		
		$scImagePath = $this->config->paths->html.'/images/safety_comittee';
		$scImageURL = $url."images/safety_comittee";
		
		require_once('fpdf/mc_table.php');
		$pdf=new PDF_MC_Table();
		$pdf->AddPage();
		$pdf->SetTitle($this->ident['initial']." - Safety Comittee Project/Issue");
		$pdf->SetFont('Arial','B',15);
		$pdf->Write(10,$this->ident['initial']." - Safety Comittee Project/Issue");
		$pdf->ln(10);

		$pdf->SetFont('Arial','B',8);
		$pdf->Cell(35,6,'Department',0,0,'L');
		$pdf->Cell(138,6,$topic['category_name'],0,0,'L');
		$pdf->Ln();
		$pdf->Cell(35,6,'PIC',0,0,'L');
		$pdf->Cell(138,6,$topic['pic_name'],0,0,'L');
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
				$tiX = $curX+45;
				
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
				$pdf->Cell(138,5,$fu['follow_up'],0,0,'L');
				$pdf->Ln();
				$curY = $pdf->getY();
				$fuY = $curY;
				$fuX = $curX+45;

				$prevFollowUpTopicImages = $safetyComitteeTable->getFollowUpImages($fu['followup_id']);
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
		
		$pdf->Ln();
		$pdf->SetFont('Arial','B',8);
		$pdf->Cell(35,6,'Accident Review',0,0,'L');
		if(!empty($accidentReview))
		{
			$j = 0;
			$arY = $arX = 0;
			foreach($accidentReview as $ar) {
			    if($j > 0) $pdf->Cell(35,6,'',0,0,'L');
			    $pdf->SetFont('Arial','B',8);
				$arDateTime = explode(" ", $ar['added_date']);
				$arDate = date("j M Y", strtotime($arDateTime[0]));
				$pdf->Cell(138,5,$arDate,0,0,'L');
				$pdf->Ln();
				$pdf->SetFont('Arial','',8);
				$pdf->Cell(35,5,'',0,0,'L');
				$pdf->Cell(138,5,$ar['accident_review'],0,0,'L');
				$pdf->Ln();
				$curY = $pdf->getY();
				$totalLetter = strlen($ar['accident_review']);
				$arY = $curY;
				$arX = $curX+45;

				$accidentReviewImages = $safetyComitteeTable->getAccidentReviewImages($ar['accident_review_id']);
				if(!empty($accidentReviewImages))
				{
				    $arimgctr = 0;
				    $pdf->Cell(35,5,'',0,0,'L');
					foreach($accidentReviewImages as $image2) {
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
							$pdf->Image($scImageURL.str_replace(".", "_thumb.", $image2['filename']), $arX, $arY, $w, $h);
						    $arX = $arX+14.5;
							    
							$arimgctr++;
						}
					}							
					$arY = $arY + 14.5;
					$pdf->Ln(15);
				}
				else {
					$arY = $arY + 5;
				}	
				$j++;
			}
		}
		$pdf->Ln();
		$pdf->SetFont('Arial','B',8);
		$pdf->Cell(35,6,'Recommendation',0,0,'L');
		if(!empty($recommendation))
		{
			$j = 0;
			$fuY = $fuX = 0;
			foreach($recommendation as $rec) {
			    if($j > 0) $pdf->Cell(35,6,'',0,0,'L');
			    $pdf->SetFont('Arial','B',8);
				$recDateTime = explode(" ", $rec['added_date']);
				$recDate = date("j M Y", strtotime($recDateTime[0]));
				$pdf->Cell(138,5,$recDate,0,0,'L');
				$pdf->Ln();
				$pdf->SetFont('Arial','',8);
				$pdf->Cell(35,5,'',0,0,'L');
				$pdf->Cell(138,5,$rec['recommendation'],0,0,'L');
				$pdf->Ln();
				$curY = $pdf->getY();
				$totalLetter = strlen($rec['follow_up']);
				$recY = $curY;
				$recX = $curX+45;

				$recommendationImages = $safetyComitteeTable->getRecommendationImages($rec['recommendation_id']);
				if(!empty($recommendationImages))
				{
				    $recimgctr = 0;
				    $pdf->Cell(35,5,'',0,0,'L');
					foreach($recommendationImages as $image2) {
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
							$pdf->Image($scImageURL.str_replace(".", "_thumb.", $image2['filename']), $recX, $recY, $w, $h);
						    $recX = $recX+14.5;
							    
							$recimgctr++;
						}
					}	
					$pdf->Ln(15);
					$recY = $recY + 14.5;
				}
				else {
					$recY = $recY + 5;
				}	
				$j++;
			}
		}
		$pdf->Output('I', $this->ident['initial']."_safety_comittee_mom.pdf", false);
		
	}
}
?>