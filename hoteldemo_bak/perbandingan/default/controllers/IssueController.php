<?php
require_once('actionControllerBase.php');
class IssueController extends actionControllerBase
{
	function submitissueAction()
	{
		set_time_limit(7200);
		$params = $this->_getAllParams();
		
		$magickPath = "/usr/bin/convert";
		
		
		$file = $_FILES['picture'];
		if ($file["error"] > 0) {
			$this->view->msg = "Submitting kaizen failed, please try again";
			$this->renderTemplate('index.tpl');
		}
		else {					
			$datafolder = $this->config->paths->html."/images/issues/".date("Y");
			if(!is_dir($datafolder)) mkdir($datafolder, 0777, true);
			
			$origFileName = $file["name"];
			$temp = explode(".", $origFileName);
			$ext = $temp[count($temp)-1];
			$ext = strtolower($ext);
				
			$curDate = 	date("Ymd_his")."_".$this->site_id."_".$params['category'];
			$fileName = $curDate.'.'.$ext;
			
			$logsTable = $this->loadModel('logs');
			
			Zend_Loader::LoadClass('modusClass', $this->modelDir);
			$modusClass = new modusClass();

			if (move_uploaded_file($file["tmp_name"],$datafolder."/".$fileName)){
				$params['picture'] = $fileName;
				Zend_Loader::LoadClass('issueClass', $this->modelDir);
				$issueClass = new issueClass();
				$params['user_id'] = $this->ident['user_id'];
				if(empty($params['site_id'])) $params['site_id'] = $this->site_id;
				if(!empty($params['manpower_id'])) {
					$modus = $modusClass->getModusById($params['modus_id'], $params['category']);
					if(strpos(strtolower($modus['modus']), "outsource")) $inhouse_outsource = '1';
					else $inhouse_outsource = '0';	

					Zend_Loader::LoadClass('manpowerClass', $this->modelDir);
					$manpowerClass = new manpowerClass();
					$manpower = $manpowerClass->getManPowerIdByName($params['manpower_id'], $params['category'], $inhouse_outsource);
					$manpower_name = $params['manpower_id'];
					$params['manpower_id'] = $manpower['manpower_id'];
				}
				$id = $issueClass->saveIssue($params);

				$new_file_thumb = $curDate."_thumb.".$ext;
				$new_file_large = $curDate."_large.".$ext;

				switch($params["type_id"])
				{
					case 1: $color = "#fea7b7"; break;
					case 2: $color = "#e3bfdb"; break;
					case 3: $color = "#dadaf6"; break;
					case 4: $color = "#f6d7c2"; break;
					case 5: $color = "#d1bfab"; break;
					case 6: $color = "#a1eccd"; break;
					case 7: $color = "#a0d994"; break;
					case 8: $color = "#f9e285"; break;
					case 9: $color = "#c4d182"; break;
					case 10: $color = "#a0d994"; break;
					case 11: $color = "#d2c895"; break;
					case 12: $color = "#b4dbf8"; break;
					default: $color = "#ffcc00";
				}

				/*** create thumbnail image ***/
				$label = "isort.id ".date("d/m/Y")." ".date("H:i");
//exec($magickPath . ' ' . $datafolder."/".$fileName . " -resize 3% -pointsize 12 -fill '#9e824b' -gravity NorthEast -draw \"text 100,100 'isort.id' \" " . $datafolder."/".$new_file_thumb);
exec($magickPath . ' ' . $datafolder."/".$fileName . " -resize 100x100 -bordercolor red -border 5 " . $datafolder."/".$new_file_thumb);
				/*** create medium image ***/
//exec($magickPath . ' ' . $datafolder."/".$fileName . " -resize 20% -pointsize 30  -fill '#9e824b' -gravity East -draw \"text 100,100 'isort.id' \" " . $datafolder."/".$new_file_large);
exec($magickPath . ' ' . $datafolder."/".$fileName . " -resize 800x800 -bordercolor red -border 5 " . $datafolder."/".$new_file_large);
				
				//imagedestroy($datafolder."/".$fileName);
				unlink($datafolder."/".$fileName);

				/*** SEND NOTIFICATION VIA TELEGRAM ***/
                
				$categoryTable = $this->loadModel('category');
				$category = $categoryTable->getCategoryById($params['category']);	
				$issuetypeTable = $this->loadModel('issuetype');
				$type = $issuetypeTable->getIssueTypeById($params['type_id']);

				$kejadian = "";
				if(!empty($params['incident_id'])) {
					$incidentTable = $this->loadModel('incident');
					$selIncident = $incidentTable->getIncidentById($params['incident_id'],$params['category']);	
					$kejadian = " - ".$selIncident['kejadian'];
				}
				$modus = "";
				if(!empty($params['modus_id'])) {
					$modusTable = $this->loadModel('modus');
					$selModus = $modusTable->getModusById($params['modus_id'],$params['category']);
					$modus = " - ".$selModus['modus'];
				}
				$manpowername = "";
				if(!empty($manpower['manpower_id'])) {
					$manpowername = " - ".$manpower_name;
				}
				$floor = "";
				$area = "";
				if(!empty($params['floor_id'])) {
					$floorTable = $this->loadModel('floor');
					$selFloor = $floorTable->getFloorById($params['floor_id'],$params['category']);
					$floor = $selFloor['floor']." - ";
				}

				//$pic_url = $this->config->general->url."index/issueimage/id/".$id;
				if(date("Y-m-d H:i:s") > "2019-10-23 14:30:00")
					$pic_url = $this->config->general->url."images/issues/".date("Y")."/".$new_file_large;
				else
					$pic_url = $this->config->general->url."images/issues/".$new_file_large;

				$botToken = '716005387:AAFhUdDGlXjK5YFMYqwx_lCpqpp8760S_TE';
				$website="https://api.telegram.org/bot".$botToken;
				$chatId=$category['site_'.$params['site_id']];  //Receiver Chat Id 
				
				if(strlen($params['description']) > 700)
					$params['description'] = substr($params['description'], 0, 700)."...";
				
				$allParams = $params;
				$paramsTelegram=array(
					'chat_id'=>$chatId,
					'photo'=>$pic_url,
					'caption'=> '&#128227; <b><u>NEW KAIZEN</u></b> &#128227;
&#128205; '.$area.$floor.$params['location'].' 
&#9888; '.$type['issue_type'].$kejadian.$modus.$manpowername.' 
&#8505; '.$params['description'].'
&#128279; <a href="'.$this->config->general->url."default/issue/listissues/s/".$params['site_id']."/c/".$params['category']."/id/".$id.'">Open Kaizen</a>',
					
					'parse_mode'=>'html'
				);
				
				$ch = curl_init($website . '/sendPhoto');
				curl_setopt($ch, CURLOPT_HEADER, false);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, ($paramsTelegram));
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($ch,CURLOPT_CONNECTTIMEOUT, 3);
				curl_setopt($ch,CURLOPT_TIMEOUT, 30);
				$result = curl_exec($ch);
				curl_close($ch);

				/****** Linked to Other Modus from Other Department *******/
				if(!empty($params['incident_id']) && !empty($params['modus_id'])) {
				$modus_linked = $modusClass->getModusLinked($params);
				if(!empty($modus_linked))
				{
					$dtlinked = array();
					$dtlinked['picture'] = $params["picture"];
					$dtlinked["location"] = $params["location"];
					$dtlinked["description"] = $params["description"];
					$dtlinked["user_id"] = $params["user_id"];
					$dtlinked["type_id"] = $params["type_id"];
					$dtlinked["site_id"] = $params["site_id"];
						$dtlinked["area"] = $params["area"];
					$dtlinked["pelaku_tertangkap"] = $params["pelaku_tertangkap"];
					$dtlinked["manpower_id"] = $params["manpower_id"];
					Zend_Loader::LoadClass('floorClass', $this->modelDir);
					$floorClass = new floorClass();
					foreach($modus_linked as $link)
					{							
						$dtlinked["category"] = $link["category_id2"];
						$dtlinked["incident_id"] = $link["kejadian_id2"];
						$dtlinked["modus_id"] = $link["modus_id2"];
						$oriFloor = $floorClass->getFloorById($params["floor_id"], $params["category"]);
						if(!empty($oriFloor)) $newFloor = $floorClass->getFloorIdByFloorNameAndSites($link["category_id2"], $oriFloor['floor'], $this->site_id);					
						$dtlinked["floor_id"] = $newFloor[0]["floor_id"];
						$idLink = $issueClass->saveIssue($dtlinked);

						/*** SEND NOTIFICATION VIA TELEGRAM ***/

						$categoryTable = $this->loadModel('category');
						$categorylink = $categoryTable->getCategoryById($link["category_id2"]);	

						/*$kejadian = "";
						if(!empty($params['incident_id'])) {
							$incidentTable = $this->loadModel('incident');
							$selIncident = $incidentTable->getIncidentById($params['incident_id'],$params['category']);	
							$kejadian = " - ".$selIncident['kejadian'];
						}
						$modus = "";
						if(!empty($params['modus_id'])) {
							$modusTable = $this->loadModel('modus');
							$selModus = $modusTable->getModusById($params['modus_id'],$params['category']);
							$modus = " - ".$selModus['modus'];
						}
						$manpowername = "";
						if(!empty($manpower['manpower_id'])) {
							$manpowername = " - ".$manpower_name;
						}*/
						$floor = "";
						if(!empty($params['floor_id'])) {
							$selFloorLink = $floorTable->getFloorById($dtlinked["floor_id"],$link["category_id2"]);
							$floorLink = $selFloorLink['floor']." - ";
						}

							$chatIdLink=$categorylink['site_'.$params['site_id']];  //Receiver Chat Id 
							
							$paramsTelegramLink=array(
								'chat_id'=>$chatIdLink,
								'photo'=>$pic_url,
								'caption'=> '&#128227; <b><u>NEW KAIZEN</u></b> &#128227;

			&#128205; '.$area.$floorLink.$params['location'].' 
			&#9888; '.$type['issue_type'].$kejadian.$modus.$manpowername.' 
			&#8505; '.$params['description'].'
			&#128279; <a href="'.$this->config->general->url."default/issue/listissues/s/".$params['site_id']."/c/".$link["category_id2"]."/id/".$idLink.'">Open Kaizen</a>',
			
								'parse_mode'=>'HTML'
							);
							
							$ch = curl_init($website . '/sendPhoto');
							curl_setopt($ch, CURLOPT_HEADER, false);
							curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
							curl_setopt($ch, CURLOPT_POST, 1);
							curl_setopt($ch, CURLOPT_POSTFIELDS, ($paramsTelegramLink));
							curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
							curl_setopt($ch,CURLOPT_CONNECTTIMEOUT, 3);
							curl_setopt($ch,CURLOPT_TIMEOUT, 30);
							$result = curl_exec($ch);
							curl_close($ch);
						}
					}				
				}

				$allParams['telegram'] = $paramsTelegram;
				$logData['user_id'] = intval($this->ident['user_id']);
				$logData['action'] = "Submit Kaizen Successful";
				$logData['data'] = json_encode($allParams);
				$logsTable->insertLogs($logData);
				
				$this->view->msg = "Kaizen successfully submitted";

				echo $id;
				/*$this->getResponse()->setRedirect($this->config->general->url."/issue/listissues".$id);
				$this->getResponse()->sendResponse();
				exit;*/
			}	
			else {
				$logData['user_id'] = intval($this->ident['user_id']);
				$logData['action'] = "Submit Kaizen Failed";
				$logData['data'] = json_encode($params);
				$logsTable->insertLogs($logData);

				echo "0";
				/*$this->getResponse()->setRedirect($this->config->general->url."/index/index/err/1");
				$this->getResponse()->sendResponse();
				exit;*/			
			}
		}
	}
	
	function listissuesAction()
	{
		$params = $this->_getAllParams();
		
		if($params['c'] == 6)
		{
			Zend_Loader::LoadClass('workorderClass', $this->modelDir);
			$workorderClass = new workorderClass();
			
			$wo = $workorderClass->getWOByIssueId($params['id']);
			$worker = explode(",", $wo['worker']);
			if(in_array($this->ident['user_id'], $worker))
			{
				$this->_response->setRedirect($this->baseUrl.'/default/workorder/view/id/'.$wo['wo_id']);
				$this->_response->sendResponse();
				exit();
			}
		}
		
		$this->view->ident = $this->ident;
		Zend_Loader::LoadClass('issueClass', $this->modelDir);
		$issueClass = new issueClass();

		if($params['s'] > 0)
		{
				if($this->showSiteSelection == 1)
				{
					$siteTable = $this->loadModel('site');
					if($params['s'] != $this->ident['site_id'])
					{
						$siteTable->setSite($params['s']);
						$this->ident['site_id'] = $this->site_id = $params['s'];
					}
					$selIssue = $issueClass->getIssueById($params['id']);
					if($selIssue['solved'] == '1')
					{
						$url = str_replace("listissues","solvedissues",$_SERVER['REQUEST_URI']);
						//else $url = $_SERVER['REQUEST_URI'];

						$this->_response->setRedirect($this->baseUrl.$url);
						$this->_response->sendResponse();
						exit();
					}
				}
		}
		if($params['c'] > 0) $params['category'] = $params['c'];
		if(!empty($params['id'])) $params['issue_id'] = $params['id'];
		if(empty($params['start'])) $params['start'] = '0';
		$params['pagesize'] = 10;
		$params['site_id']= $this->site_id;
		if(empty($params['category']) && $params['category']!='0')
		{
				if(count($this->ident['role_ids']) == 1)
				{
					Zend_Loader::LoadClass('userClass', $this->modelDir);
					$userClass = new userClass();
					$userRole = $userClass->getRoleById($this->ident['role_ids'][0]);
					if($userRole['category_id'] > 0) 	$params['category'] = $userRole['category_id'];
				} 
		}
		$issues = $issueClass->getIssues($params);
		if($this->isMobile == true)
		{			
			$totalIssues = $issueClass->getTotalPendingIssues('0', $this->site_id, $params);
			$category = $this->loadModel('category');
			$commentsTable = $this->loadModel('comments');
			$newIssue=array();
			foreach($issues as &$issue)
			{
				$issue_date_time = explode(" ",$issue['issue_date']);
				$issue['issue_date_time'] = date("j M Y", strtotime($issue_date_time[0]))." ".$issue_date_time[1];
				$d_start    = new DateTime($issue['issue_date']); 
				$d_end      = new DateTime(date('Y-m-d H:i:s')); 
				$diff = $d_start->diff($d_end);
				$issue['count_years'] = $diff->format('%y'); 
				$issue['count_months'] = $diff->format('%m'); 
				$issue['count_days'] = $diff->format('%d') + 1; 

				if($issue['issue_date'] > "2019-10-23 14:30:00")
				{
					$issuedate = explode("-",$issue_date_time[0]);
					$imageURL = "/images/issues/".$issuedate[0]."/";
				}
				else
					$imageURL = "/images/issues/";

				$pic = explode(".", $issue['picture']);
				$issue['large_pic'] = $imageURL.$pic[0]."_large.".$pic[1];
				$issue['thumb_pic'] = $imageURL.$pic[0]."_thumb.".$pic[1];
				if(!empty($issue['category_id'])) {
					$issue['category'] = $category->getCategoryById($issue['category_id']);				
				}
				if($issue['issue_id'] == $params['id']) $newIssue = $issue;
				$issue['comments'] = $commentsTable->getCommentsByIssueId($issue['issue_id'], '3');
				$issue['progress_images'] = $issueClass->getProgressImages($issue['issue_id']);
				foreach($issue['progress_images'] as &$progress_image)
				{
					if($progress_image['upload_date'] > "2019-10-23 15:40:00")
					{
						$issuedate = explode("-",$issue_date_time[0]);
						$progressImageURL = "/images/issues/".$issuedate[0]."/";
					}
					else
						$progressImageURL = "/images/issues/";
					$pic = explode(".", $progress_image['filename']);
					$progress_image['large_pic'] = $progressImageURL.$pic[0]."_large.".$pic[1];
					$progress_image['thumb_pic'] = $progressImageURL.$pic[0]."_thumb.".$pic[1];
				}

				if(empty($params['category']))
				{
					$issue['kejadian'] = "";
					if(!empty($issue['kejadian_id'])) {
						$incidentTable = $this->loadModel('incident');
						$selIncident = $incidentTable->getIncidentById($issue['kejadian_id'],$issue['category_id']);	
						$issue['kejadian'] = $selIncident['kejadian'];
					}
					$issue['modus'] = "";
					if(!empty($issue['modus_id'])) {
						$modusTable = $this->loadModel('modus');
						$selModus = $modusTable->getModusById($issue['modus_id'],$issue['category_id']);
						$issue['modus'] = $selModus['modus'];
					}
					$issue['manpower_name'] = "";
					if(!empty($issue['manpower_id'])) {
						Zend_Loader::LoadClass('manpowerClass', $this->modelDir);
						$manpowerClass = new manpowerClass();
						$selManPower = $manpowerClass->getManPowerById($issue['manpower_id']);
						$issue['manpower_name'] = $selManPower['name'];
					}
					$issue['floor'] = "";
					if(!empty($issue['floor_id'])) {
						$floorTable = $this->loadModel('floor');
						$selFloor = $floorTable->getFloorById($issue['floor_id'],$issue['category_id']);
						$issue['floor'] = $selFloor['floor'];
					}
				}
			}
		
			/*if(!empty($newIssue))
			{	
				//$pic_url = $this->config->general->url."/images/issues/".$newIssue['large_pic'] ;
				$pic_url = $this->config->general->url."/index/issueimage/id/".$newIssue['issue_id'] ;
				$this->view->txt = urlencode('*[NEW ISSUE]* Image : '.$pic_url.' Category : _'.$newIssue['category']['category_name'].'_, Location : _'.$newIssue['location'].'_, Discussion : _'.$newIssue['description']).'_';
				$this->view->phone = '6285885556333'; //'6282260400777'; //'6282111508181';
				$this->view->f = $params['f'];
			}*/
			
			if($totalIssues['total'] > 10)
			{
				if($params['category'] > 0)	$cat_url = "/category/".$params['category'];
				else $cat_url = "";
				$dateParams = "";
				if(!empty($params['start_date'])) $dateParams .= "/start_date/".urlencode($params['start_date']);
				if(!empty($params['start_date'])) $dateParams .= "/end_date/".urlencode($params['end_date']);
				if($params['start'] >= 10)
				{
					
					$this->view->firstPageUrl = "/default/issue/listissues".$cat_url.$dateParams;
					$this->view->prevUrl = "/default/issue/listissues/start/".($params['start']-$params['pagesize']).$cat_url.$dateParams;
				}
				if($params['start'] < (floor(($totalIssues['total']-1)/10)*10))
				{
					$this->view->nextUrl = "/default/issue/listissues/start/".($params['start']+$params['pagesize']).$cat_url.$dateParams;
					$this->view->lastPageUrl = "/default/issue/listissues/start/".(floor(($totalIssues['total']-1)/10)*10).$cat_url.$dateParams;
				}
			}
			$this->view->curPage = ($params['start']/$params['pagesize'])+1;
			$this->view->totalPage = ceil($totalIssues['total']/$params['pagesize']);
			if($totalIssues['total'] == 0) $this->view->startRec = 0;
			else $this->view->startRec = $params['start'] + 1;
			$endRec = $params['start'] + $params['pagesize'];
			if($totalIssues['total'] >=  $endRec) $this->view->endRec =  $endRec;
			else $this->view->endRec =  $totalIssues['total'];		
			$this->view->totalRec = $totalIssues['total'];
			$this->view->issues = $issues;
		}
		$category = $this->loadModel('category');
		$this->view->categories = $category->getCategories(6);
		$this->view->category_id = $params['category'];
		$this->view->issue_id = $params['issue_id'];
		$this->view->start_date = $params['start_date'];
		$this->view->end_date = $params['end_date'];
		$this->view->start = $params['start'];

		if(!empty($params['issue_id'])) $this->view->selectedCategory = $issues[0]['category_id'];
		else if(empty($params['category'])) $this->view->selectedCategory = 1;
		else $this->view->selectedCategory = $params['category'];

		$params['category'] = 1; // Security
		$this->view->totalSecIssues = $issueClass->getTotalPendingIssues('0', $this->site_id, $params);
		$params['category'] = 3; // Safety
		$this->view->totalSafIssues = $issueClass->getTotalPendingIssues('0', $this->site_id, $params);
		$params['category'] = 5; // Parking & Traffic
		$this->view->totalParkIssues = $issueClass->getTotalPendingIssues('0', $this->site_id, $params);
		$params['category'] = 2; // Housekeeping
		$this->view->totalHKIssues = $issueClass->getTotalPendingIssues('0', $this->site_id, $params);
		$params['category'] = 6; // Engineering
		$this->view->totalEngIssues = $issueClass->getTotalPendingIssues('0', $this->site_id, $params);
		$params['category'] = 10; // Building Service
		$this->view->totalBSIssues = $issueClass->getTotalPendingIssues('0', $this->site_id, $params);
		$params['category'] = 11; // Tenant Relation
		$this->view->totalTRIssues = $issueClass->getTotalPendingIssues('0', $this->site_id, $params);
		
		//if(mktime(date("H"), date("i"), date("s"), date("m"), date("d"), date("Y")) > mktime(14, 30, 0, 10, 23, 2019))
		if(date("Y-m-d H:i:s") > "2019-10-23 14:30:00")
			$this->view->imageURL = "/images/issues/".date("Y")."/";
		else
			$this->view->imageURL = "/images/issues/";

		$issuetypeTable = $this->loadModel('issuetype');
		$this->view->lostFoundOptions = $issuetypeTable->getLostFoundOptions();
		
		$users = $this->loadModel('user');
		$this->view->workers = $users->getUsersByRole(31);
		
		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "Opened Issues";
		$logData['data'] = json_encode($params);
		$logsTable->insertLogs($logData);	

		$this->renderTemplate('list_issues.tpl');	
	}
	
	function registrationAction() {
		require_once('whatsapp/Registration.php');
 
		$debug = false;
		$username = "6282260400777"; // Phone number with country code but without '+' or '00', ie: 34123456789
		 
		if(empty($username)){ 
		echo "Mobile Number can't be Empty"; 
		exit(0);
		}
		if (!preg_match('!^\d+$!', $username))
		{
		  echo "Wrong number. Do NOT use '+' or '00' before your number\n";
		  exit(0);
		}
		$w = new Registration($username, $debug);
		  try {
			$resp = $w->checkCredentials();
			print_r($resp); exit();
			echo "Verification Code Sent via SMS";
		  } catch(Exception $e) {
			echo $e->getMessage();
			exit(0);
		 }
	}
	
	function solvedissuesAction()
	{
		$params = $this->_getAllParams();
		$this->view->ident = $this->ident;
		Zend_Loader::LoadClass('issueClass', $this->modelDir);
    	$issueClass = new issueClass();
		//$params['site_id']= $this->site_id;

		if($params['s'] > 0  && $params['s'] != $this->ident['site_id'])
		{
				if($this->showSiteSelection == 1)
				{
					$siteTable = $this->loadModel('site');
					$siteTable->setSite($params['s']);
					$this->ident['site_id'] = $params['s'];
					$this->_response->setRedirect($this->baseUrl.$_SERVER['REQUEST_URI']);
					$this->_response->sendResponse();
					exit();
				}

		}

		if($params['c'] > 0) $params['category'] = $params['c'];
		if(!empty($params['id'])) $params['issue_id'] = $params['id'];
		if(empty($params['start'])) $params['start'] = '0';
		$params['pagesize'] = 10;
		if(empty($params['category']) && $params['category']!='0')
		{
				if(count($this->ident['role_ids']) == 1)
				{
					Zend_Loader::LoadClass('userClass', $this->modelDir);
					$userClass = new userClass();
					$userRole = $userClass->getRoleById($this->ident['role_ids'][0]);
					if($userRole['category_id'] > 0) 	$params['category'] = $userRole['category_id'];
				} 
		}

		$params['solved'] = 1;
		$issues = $issueClass->getIssues($params);

		if($this->isMobile == true)
		{
			//$issues = $issueClass->getSolvedIssues($params);
			
			$totalIssues = $issueClass->getTotalPendingIssues('1', $this->site_id, $params);
			$category = $this->loadModel('category');
			$commentsTable = $this->loadModel('comments');
			$newIssue=array();
			foreach($issues as &$issue)
			{

				if($issue['issue_date'] > "2019-10-23 14:30:00")
				{
					$issuedate = explode("-",$issue['issue_date']);
					$imageURL = "/images/issues/".$issuedate[0]."/";
				}
				else
					$imageURL = "/images/issues/";

				if($issue['solved_date'] > "2019-10-23 15:35:00")
				{
					$solvedissuedate = explode("-",$issue['solved_date']);
					$solvedImageURL = "/images/issues/".$solvedissuedate[0]."/";
				}
				else
					$solvedImageURL = "/images/issues/";


				$pic = explode(".", $issue['picture']);
				$issue['large_pic'] = $imageURL.$pic[0]."_large.".$pic[1];
				$issue['thumb_pic'] = $imageURL.$pic[0]."_thumb.".$pic[1];
				$solved_pic = explode(".", $issue['solved_picture']);
				$issue['large_solved_pic'] = $solvedImageURL.$solved_pic[0]."_large.".$solved_pic[1];
				$issue['thumb_solved_pic'] = $solvedImageURL.$solved_pic[0]."_thumb.".$solved_pic[1];
				if(!empty($issue['category_id'])) {
					$issue['category'] = $category->getCategoryById($issue['category_id']);				
				}
				if($issue['issue_id'] == $params['id']) $newIssue = $issue;
				$issue['comments'] = $commentsTable->getCommentsByIssueId($issue['issue_id'], '3');
				$issue['progress_images'] = $issueClass->getProgressImages($issue['issue_id']);
				foreach($issue['progress_images'] as &$progress_image)
				{
					if($progress_image['upload_date'] > "2019-10-23 15:40:00")
					{
						$issuedate = explode("-",$issue['issue_date']);
						$progressImageURL = "/images/issues/".$issuedate[0]."/";
					}
					else
						$progressImageURL = "/images/issues/";
					$pic = explode(".", $progress_image['filename']);
					$progress_image['large_pic'] = $progressImageURL.$pic[0]."_large.".$pic[1];
					$progress_image['thumb_pic'] = $progressImageURL.$pic[0]."_thumb.".$pic[1];
				}

				if(empty($params['category']))
				{
					$issue['kejadian'] = "";
					if(!empty($issue['kejadian_id'])) {
						$incidentTable = $this->loadModel('incident');
						$selIncident = $incidentTable->getIncidentById($issue['kejadian_id'],$issue['category_id']);	
						$issue['kejadian'] = $selIncident['kejadian'];
					}
					$issue['modus'] = "";
					if(!empty($issue['modus_id'])) {
						$modusTable = $this->loadModel('modus');
						$selModus = $modusTable->getModusById($issue['modus_id'],$issue['category_id']);
						$issue['modus'] = $selModus['modus'];
					}
					$issue['manpower_name'] = "";
					if(!empty($issue['manpower_id'])) {
						Zend_Loader::LoadClass('manpowerClass', $this->modelDir);
						$manpowerClass = new manpowerClass();
						$selManPower = $manpowerClass->getManPowerById($issue['manpower_id']);
						$issue['manpower_name'] = $selManPower['name'];
					}
					$issue['floor'] = "";
					if(!empty($issue['floor_id'])) {
						$floorTable = $this->loadModel('floor');
						$selFloor = $floorTable->getFloorById($issue['floor_id'],$issue['category_id']);
						$issue['floor'] = $selFloor['floor'];
					}
				}
			}
			
			if($totalIssues['total'] > 10)
			{
				if($params['category'] > 0)	$cat_url = "/category/".$params['category'];
				else $cat_url = "";
				$dateParams = "";
				if(!empty($params['start_date'])) $dateParams .= "/start_date/".urlencode($params['start_date']);
				if(!empty($params['start_date'])) $dateParams .= "/end_date/".urlencode($params['end_date']);
				if($params['start'] >= 10)
				{
					$this->view->firstPageUrl = "/default/issue/solvedissues".$cat_url.$dateParams;
					$this->view->prevUrl = "/default/issue/solvedissues/start/".($params['start']-$params['pagesize']).$cat_url.$dateParams;
				}
				if($params['start'] < (floor(($totalIssues['total']-1)/10)*10))
				{
					$this->view->nextUrl = "/default/issue/solvedissues/start/".($params['start']+$params['pagesize']).$cat_url.$dateParams;
					$this->view->lastPageUrl = "/default/issue/solvedissues/start/".(floor(($totalIssues['total']-1)/10)*10).$cat_url.$dateParams;
				}
			}	

			$this->view->curPage = ($params['start']/$params['pagesize'])+1;
			$this->view->totalPage = ceil($totalIssues['total']/$params['pagesize']);
			if($totalIssues['total'] == 0) $this->view->startRec = 0;
			else $this->view->startRec = $params['start'] + 1;
			$endRec = $params['start'] + $params['pagesize'];
			if($totalIssues['total'] >=  $endRec) $this->view->endRec =  $endRec;
			else $this->view->endRec =  $totalIssues['total'];
			$this->view->totalRec = $totalIssues['total'];		
		}

		if(!empty($params['issue_id'])) $this->view->selectedCategory = $issues[0]['category_id'];
		else if(empty($params['category'])) $this->view->selectedCategory = 1;
		else $this->view->selectedCategory = $params['category'];

		$category = $this->loadModel('category');
		$this->view->categories = $category->getCategories();
		$this->view->category_id = $params['category'];
		$this->view->issue_id = $params['issue_id'];
		$this->view->issues = $issues;
		$this->view->start_date = $params['start_date'];
		$this->view->end_date = $params['end_date'];
		$this->view->start = $params['start'];
		$this->view->solved = '1';

		$params['category'] = 1; // Security
		$this->view->totalSecIssues = $issueClass->getTotalPendingIssues('1', $this->site_id, $params);
		$params['category'] = 3; // Safety
		$this->view->totalSafIssues = $issueClass->getTotalPendingIssues('1', $this->site_id, $params);
		$params['category'] = 5; // Parking & Traffic
		$this->view->totalParkIssues = $issueClass->getTotalPendingIssues('1', $this->site_id, $params);
		$params['category'] = 2; // Housekeeping
		$this->view->totalHKIssues = $issueClass->getTotalPendingIssues('1', $this->site_id, $params);
		$params['category'] = 6; // Engineering
		$this->view->totalEngIssues = $issueClass->getTotalPendingIssues('1', $this->site_id, $params);
		$params['category'] = 10; // Building Service
		$this->view->totalBSIssues = $issueClass->getTotalPendingIssues('1', $this->site_id, $params);
		$params['category'] = 11; // Tenant Relation
		$this->view->totalTRIssues = $issueClass->getTotalPendingIssues('1', $this->site_id, $params);
		
		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "Closed Issues";
		$logData['data'] = json_encode($params);
		$logsTable->insertLogs($logData);

		$this->renderTemplate('solved_issues.tpl');	
	}
	
	function submitsolveissueAction()
	{
		set_time_limit(7200);
		$params = $this->_getAllParams();
		
		$magickPath = "/usr/bin/convert";
		
		
		$file = $_FILES['solved-picture'];
		if ($file["error"] > 0) {
			$this->view->msg = "Submitting close kaizen failed, please try again";
			$this->renderTemplate('list_issue.tpl');
		}
		else {									
			$datafolder = $this->config->paths->html."/images/issues/".date("Y");
			if(!is_dir($datafolder)) mkdir($datafolder, 0777, true);					
			
			$origFileName = $file["name"];
			$temp = explode(".", $origFileName);
			$ext = $temp[count($temp)-1];
			$ext = strtolower($ext);
				
			$curDate = 	date("Ymd_his")."_".$this->site_id."_".$params['issue_id'];
			$fileName = $curDate.'.'.$ext;
			
			$logsTable = $this->loadModel('logs');
			
			if (move_uploaded_file($file["tmp_name"],$datafolder."/".$fileName)){
				$params['picture'] = $fileName;
				Zend_Loader::LoadClass('issueClass', $this->modelDir);
				$issueClass = new issueClass();
				$params['user_id'] = $this->ident['user_id'];
				$id = $issueClass->saveSolveIssue($params);
				
				Zend_Loader::LoadClass('commentsClass', $this->modelDir);
				$commentsClass = new commentsClass();
				$commentsClass->addComment($params);
				
				$new_file_thumb = $curDate."_thumb.".$ext;
				$new_file_large = $curDate."_large.".$ext;
				/*** create thumbnail image ***/
				$label = "isort.id ".date("d/m/Y")." ".date("H:i");
exec($magickPath . ' ' . $datafolder."/".$fileName . " -resize 100x100 -bordercolor green -border 5 " . $datafolder."/".$new_file_thumb);
				/*** create medium image ***/
exec($magickPath . ' ' . $datafolder."/".$fileName . " -resize 800x800 -bordercolor green -border 5 " . $datafolder."/".$new_file_large);
				
				imagedestroy($datafolder."/".$fileName);
				unlink($datafolder."/".$fileName);
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
				if(!empty($issue['floor_id'])) {
					$floorTable = $this->loadModel('floor');
					$selFloor = $floorTable->getFloorById($issue['floor_id'],$issue['category_id']);
					$floor = $selFloor['floor']." - ";
				}
				
				if(!empty($issue['area_id']))
				{
					$areaTable = $this->loadModel('area');
					$selArea = $areaTable->getAreaById($issue['area_id']);
					$area = $selArea['area_name']." - ";
				}
							
				$pic_url = $this->config->general->url."images/issues/".date("Y")."/".$new_file_large;
				if(strlen($issue['description']) > 700)
					$issue['description'] = substr($issue['description'], 0, 700)."...";
				
				$botToken = '716005387:AAFhUdDGlXjK5YFMYqwx_lCpqpp8760S_TE';
				$website="https://api.telegram.org/bot".$botToken;
				$chatId=$category['site_'.$this->site_id];  //Receiver Chat Id 
				$txt = '&#128227; <b><u>KAIZEN CLOSED BY '.strtoupper($this->ident['name']).'</u></b>
&#128172; '.$params['comment'].'

<b><u>KAIZEN DETAIL</u></b>
&#128205; '.$area.$floor.$issue['location'].' 
&#9888; '.$type['issue_type'].$kejadian.$modus.$manpowername.'
&#8505; '.$issue['description'].'
&#128279; <a href="'.$this->config->general->url."default/issue/solvedissues/s/".$this->site_id."/c/".$issue['category_id']."/id/".$params['issue_id'].'">Open Kaizen</a>';

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
				$logData['user_id'] = intval($this->ident['user_id']);
				$logData['action'] = "Submit Solved Kaizen Successful";
				$logData['data'] = json_encode($allParams);
				$logsTable->insertLogs($logData);
				
				
				$this->view->msg = "Closed Kaizen successfully submitted";
				$this->getResponse()->setRedirect($this->config->general->url."/issue/solvedissues/id/".$id);
				$this->getResponse()->sendResponse();
				exit;
			}	
			else {
				$logData['user_id'] = intval($this->ident['user_id']);
				$logData['action'] = "Submit Solved Kaizen Failed";
				$logData['data'] = json_encode($params);
				$logsTable->insertLogs($logData);

				$this->getResponse()->setRedirect($this->config->general->url."/issue/listissues/err/1");
				$this->getResponse()->sendResponse();
				exit;			
			}
		}
	}
	
	function submitprogressissueAction()
	{
		set_time_limit(7200);
		$params = $this->_getAllParams();
		
		$magickPath = "/usr/bin/convert";
		
		
		$file = $_FILES['progress-picture'];
		if ($file["error"] > 0) {
			$this->view->msg = "Submitting progress image failed, please try again";
			$this->renderTemplate('list_issue.tpl');
		}
		else {					
			$datafolder = $this->config->paths->html."/images/issues/".date("Y");
			if(!is_dir($datafolder)) mkdir($datafolder, 0777, true);
			
			$origFileName = $file["name"];
			$temp = explode(".", $origFileName);
			$ext = $temp[count($temp)-1];
			$ext = strtolower($ext);
				
			$curDate = 	date("Ymd_his");
			$fileName = $curDate.'.'.$ext;
			
			$logsTable = $this->loadModel('logs');
			
			if (move_uploaded_file($file["tmp_name"],$datafolder."/".$fileName)){
				$params['picture'] = $fileName;
				Zend_Loader::LoadClass('issueClass', $this->modelDir);
				$issueClass = new issueClass();
				$params['user_id'] = $this->ident['user_id'];
				$id = $issueClass->saveProgressImage($params);
				
				$new_file_thumb = $curDate."_thumb.".$ext;
				$new_file_large = $curDate."_large.".$ext;
				/*** create thumbnail image ***/
exec($magickPath . ' ' . $datafolder."/".$fileName . ' -resize 100x100 -bordercolor yellow -border 5 ' . $datafolder."/".$new_file_thumb);
				/*** create medium image ***/
exec($magickPath . ' ' . $datafolder."/".$fileName . ' -resize 800x800 -bordercolor yellow -border 5 ' . $datafolder."/".$new_file_large);
				
				imagedestroy($datafolder."/".$fileName);
				unlink($datafolder."/".$fileName);
				
				/*** SEND NOTIFICATION VIA TELEGRAM ***/
				
				$selIssue = $issueClass->getIssueById($params['progress_issue_id']);
                
				$categoryTable = $this->loadModel('category');
				$category = $categoryTable->getCategoryById($selIssue['category_id']);	
				$issuetypeTable = $this->loadModel('issuetype');
				$type = $issuetypeTable->getIssueTypeById($selIssue['issue_type_id']);

				$kejadian = "";
				if(!empty($selIssue['kejadian_id'])) {
					$incidentTable = $this->loadModel('incident');
					$selIncident = $incidentTable->getIncidentById($selIssue['kejadian_id'],$selIssue['category_id']);	
					$kejadian = " - ".$selIncident['kejadian'];
				}
				$modus = "";
				if(!empty($selIssue['modus_id'])) {
					$modusTable = $this->loadModel('modus');
					$selModus = $modusTable->getModusById($selIssue['modus_id'],$selIssue['category_id']);
					$modus = " - ".$selModus['modus'];
				}
				$manpowername = "";
				if(!empty($manpower['manpower_id'])) {
					$manpowername = " - ".$manpower_name;
				}
				$floor = "";
				$area = "";
				if(!empty($selIssue['floor_id'])) {
					$floorTable = $this->loadModel('floor');
					$selFloor = $floorTable->getFloorById($selIssue['floor_id'],$selIssue['category_id']);
					$floor = $selFloor['floor']." - ";
					if(!empty($selIssue['area_id']))
					{
						$areaTable = $this->loadModel('area');
						$selArea = $areaTable->getAreaById($selIssue['area_id']);
						$area = $selArea['area_name']." - ";
					}
				}

				//$pic_url = $this->config->general->url."index/issueimage/id/".$id;
				if(date("Y-m-d H:i:s") > "2019-10-23 14:30:00")
					$pic_url = $this->config->general->url."images/issues/".date("Y")."/".$new_file_large;
				else
					$pic_url = $this->config->general->url."images/issues/".$new_file_large;

				/*$botToken = '476346623:AAFlB9X5-bShAkdTK9ziNDrfZ0TCePXl2cY';
				/*if($this->site_id == 4) */
				$botToken = '716005387:AAFhUdDGlXjK5YFMYqwx_lCpqpp8760S_TE';
				/*else $botToken = '476346623:AAFlB9X5-bShAkdTK9ziNDrfZ0TCePXl2cY';*/
				$website="https://api.telegram.org/bot".$botToken;
				//$chatId=$category['telegram_channel_id'];  //Receiver Chat Id 
				$chatId=$category['site_'.$selIssue['site_id']];  //Receiver Chat Id 
				
				if(strlen($selIssue['description']) > 700)
					$selIssue['description'] = substr($selIssue['description'], 0, 700)."...";
				
				$allParams = $selIssue;
				$paramsTelegram=array(
					'chat_id'=>$chatId,
					'photo'=>$pic_url,
					'caption'=>'&#128227; <b><u>PROGRESS KAIZEN UPLOADED BY '.strtoupper($this->ident['name']).'</u></b>
&#128205; '.$area.$floor.$selIssue['location'].' 
&#9888; '.$type['issue_type'].$kejadian.$modus.$manpowername.' 
&#8505; '.$selIssue['description'].'
&#128279; <a href="'.$this->config->general->url."default/issue/listissues/s/".$selIssue['site_id']."/c/".$selIssue['category_id']."/id/".$selIssue['issue_id'].'">Open Kaizen</a>',
					'parse_mode'=>'HTML'
				);
				$ch = curl_init($website . '/sendPhoto');
				curl_setopt($ch, CURLOPT_HEADER, false);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, ($paramsTelegram));
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($ch,CURLOPT_CONNECTTIMEOUT, 3);
				curl_setopt($ch,CURLOPT_TIMEOUT, 30);
				$result = curl_exec($ch);
				curl_close($ch);

				$logData['user_id'] = intval($this->ident['user_id']);
				$logData['action'] = "Submit Progress Image Kaizen Successful";
				$logData['data'] = json_encode($params);
				$logsTable->insertLogs($logData);
				
				$this->view->msg = "Progress image successfully uploaded";
				$this->getResponse()->setRedirect($this->config->general->url."/issue/listissues/s/1/id/".$params['progress_issue_id']);
				$this->getResponse()->sendResponse();
				exit;
			}	
			else {
				$logData['user_id'] = intval($this->ident['user_id']);
				$logData['action'] = "Submitting Progress Image Kaizen Failed";
				$logData['data'] = json_encode($params);
				$logsTable->insertLogs($logData);

				$this->getResponse()->setRedirect($this->config->general->url."/issue/listissues/err/1");
				$this->getResponse()->sendResponse();
				exit;			
			}
		}
	}
	
	function getcommentsbyissueidAction()
	{
		$params = $this->_getAllParams();
		$this->view->ident = $this->ident;
		$category = $this->loadModel('category');
		$commentsTable = $this->loadModel('comments');
		$comments = $commentsTable->getCommentsByIssueId($params['id']);
		$commentText = "";
		if(!empty($comments)) {
			foreach($comments as $comment)
			{
				$commentText .= '<div style="border-bottom:1px solid #ccc; text-align:left; clear:both; margin-bottom:5px; padding-bottom:5px;"><strong>'.$comment['name'].' : </strong>'.$comment['comment'].'<br/>';
				if(!empty($comment['filename'])) $commentText .= '<a href="'.$this->baseUrl."/comments/".substr($comment['comment_date'],0,4)."/".$comment['filename'].'" target="_blank"><i class="fa fa-paperclip"></i> '.$comment['filename'].'</a>';
				$commentText .= '<div style="font-size:10px; color:#aaa; text-align:right;">'.$comment['comment_date']."</div></div>";
			}
		}
		
		echo $commentText;	
	}
	
	function addcommentAction() {
		$params = $this->_getAllParams();
		
		$commentsTable = $this->loadModel('comments');
		$params['user_id'] = $this->ident['user_id'];
		
		if(empty($params['site_id'])) $params['site_id'] = $this->site_id;

		if($_FILES["attachment"]["size"] > 0)
		{
			$ext = explode(".",$_FILES["attachment"]['name']);
			$filename = "issue_".date("YmdHis").".".$ext[count($ext)-1];
			$datafolder = $this->config->paths->html."/comments/".date("Y")."/";
			if(!is_dir($datafolder)) mkdir($datafolder, 0777, true);
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

		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "Add Kaizen Comment";
		$logData['data'] = json_encode($params);
		$logsTable->insertLogs($logData);
		
		
		$issueClass = $this->loadModel('issue');
		$issue = $issueClass->getIssueById($params['issue_id']);

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
		if(!empty($issue['floor_id'])) {
			$floorTable = $this->loadModel('floor');
			$selFloor = $floorTable->getFloorById($issue['floor_id'],$issue['category_id']);
			$floor = $selFloor['floor']." - ";
			if(!empty($issue['area_id']))
			{
				$areaTable = $this->loadModel('area');
				$selArea = $areaTable->getAreaById($issue['area_id']);
				$area = $selArea['area_name']." - ";
			}
		}
		
		$categoryClass = $this->loadModel('category');
		$category = $categoryClass->getCategoryById($issue['category_id']);	
		$issuetypeTable = $this->loadModel('issuetype');
		$type = $issuetypeTable->getIssueTypeById($issue['issue_type_id']);

		$issuedate = explode("-",$issue['issue_date']);

		if(!empty($issue['solved']))
		{
			$solvedIssuedate = explode("-",$issue['solved_date']);
			$solvedImageURL = $this->config->general->url."images/issues/".$solvedIssuedate[0]."/";
			$pic_url= $solvedImageURL.str_replace(".","_large.",$issue['solved_picture']);
			$page = "solvedissues";
		}
		else
		{
			$imageURL = $this->config->general->url."images/issues/".$issuedate[0]."/";		
			$pic_url = $imageURL.str_replace(".","_large.",$issue['picture']);
			$page = "listissues";
		}
		$imageURL = $this->config->general->url."images/issues/".$issuedate[0]."/";
					
		$pic_url = $imageURL.str_replace(".","_large.",$issue['picture']);
		$botToken = '716005387:AAFhUdDGlXjK5YFMYqwx_lCpqpp8760S_TE';
		$website="https://api.telegram.org/bot".$botToken;
		//$chatId=$category['telegram_channel_id'];  //Receiver Chat Id 
		$chatId=$category['site_'.$this->site_id];  //Receiver Chat Id 

		if(!empty($params['filename'])) $attachmenttext = '
&#128279; <a href="'.$this->config->general->url."comments/".date("Y")."/".$params['filename'].'">Open Attachment</a>';

		if(strlen($issue['description']) > 700)
			$issue['description'] = substr($issue['description'], 0, 700)."...";

		$txt = '<b><u>NEW COMMENT FROM '.strtoupper($this->ident['name']).'</u></b>
&#128172; '.$params['comment'].$attachmenttext.'

<b><u>KAIZEN DETAIL</u></b> 
&#128205; '.$area.$floor.$issue['location'].' 
&#9888; '.$type['issue_type'].$kejadian.$modus.$manpowername.' 
&#8505; '.$issue['description'].'
&#128279; <a href="'.$this->config->general->url."default/issue/".$page."/s/".$this->site_id."/c/".$issue['category_id']."/id/".$issue['issue_id'].'">Open Kaizen</a>';


		$paramsTelegram=array(
			'chat_id'=>$chatId,
			'photo'=>$pic_url,
			'caption'=>$txt,
			"parse_mode"=>"HTML"
		);
		$ch = curl_init($website . '/sendPhoto');
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, ($paramsTelegram));
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch,CURLOPT_CONNECTTIMEOUT, 3);
		curl_setopt($ch,CURLOPT_TIMEOUT, 30);
		$result = curl_exec($ch);
		curl_close($ch);
		echo $issue['issue_id'];
		/*$this->_response->setRedirect($this->baseUrl.'/default/bwm/view/id/'.$params['server_id']);
		$this->_response->sendResponse();
		exit();
*/	}	
	
	function getupdatedpendingcommentsAction()
	{
		$params = $this->_getAllParams();

		if(empty($params['start'])) $params['start'] = 0;
		
		$data= array();
		/*$issueTable = $this->loadModel('issue');
		$params['pagesize'] = 10;
		$issues = $issueTable->getIssueIds($params);	
		$commentsTable = $this->loadModel('comments');
		$i=0;
		foreach($issues as $issue) {
			$data[$i]['id'] = $issue['issue_id'];
			$comments = $commentsTable->getCommentsByIssueId( $issue['issue_id'], '3');
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
		}*/
		$data = $this->cache->load("opened_issue_comments_".$params["start"]."_".$this->site_id);	
		echo json_encode($data);
	}
	
	function getupdatedsolvedcommentsAction()
	{
		$params = $this->_getAllParams();
	
		$issueTable = $this->loadModel('issue');

		$params['pagesize'] = 10;

		if(empty($params['start'])) $params['start'] = 0;
		
		$data= array();
		$issues = $issueTable->getSolvedIssueIds($params);	
		$commentsTable = $this->loadModel('comments');
		$i=0;
		foreach($issues as $issue) {
			$data[$i]['id'] = $issue['issue_id'];
			$comments = $commentsTable->getCommentsByIssueId( $issue['issue_id'], '3');
			if(!empty($comments)) { 
				$comment_content = "";
				foreach($comments as $comment)
				{
					$comment_content .= '<div style="border-bottom:1px solid #ccc; text-align:left; clear:both; margin-bottom:5px; padding-bottom:5px;"><strong>'.$comment['name'].' : </strong>'.$comment['comment'].'<br/>';
					if(!empty($comment['filename'])) $comment_content .= '<a href="'.$this->baseUrl."/comments/".substr($comment['comment_date'],0,4)."/".$comment['filename'].'" target="_blank"><i class="fa fa-paperclip"></i> '.$comment['filename'].'</a>';
					$comment_content .= '<div style="font-size:10px; color:#aaa; text-align:right;">'.$comment['comment_date']."</div></div>";
				}
				$data[$i]['comment'] = $comment_content;
			}
			$i++;
		}
				
		echo json_encode($data);
	}
	
	function getissuebytypeAction()
	{
		$params = $this->_getAllParams();
		$this->view->ident = $this->ident;
		Zend_Loader::LoadClass('issueClass', $this->modelDir);
		$issueClass = new issueClass();
		$issues = $issueClass->getIssueByTypeId($params['id'], $params['cat_id'], $params['report_date'], $this->site_id);
		$txt = '<input class="type-id" type="hidden" name="type_id" value="'.$params['id'].'" />';
		if($params['show_shift'] == '1')
		{
			Zend_Loader::LoadClass('shiftClass', $this->modelDir);
			$shiftClass = new shiftClass();
			$shift = $shiftClass->getShift();
			$txt .= '<label class="control-label col-md-3 col-sm-3 col-xs-12" for="safety_inhouse_malam">Shift <span class="required">*</span>
				</label> 
				<div class="col-md-6 col-sm-6 col-xs-12">
					<select id="shift_id" name="shift_id" class="form-control" required>';
				if(!empty($shift)) { 
					foreach($shift as $s) {
						$txt .= '<option value="'.$s['shift_id'].'">'.$s['shift_name'].'</option>';
					} 
				}
			$txt .= '</select>
				</div><br/><br/>';
		}
		if(!empty($issues)) {
			$txt .= '<table width="100%">
			<tr>
				<th width="20"></th>
				<th width="50">Image</th>
				<th width="100">Location</th>
				<th>Discussion</th>
			</tr>
			</table>
			<div class="list-issues">
			<table>
			';
			foreach($issues as $issue)
			{
				$pic = explode(".", $issue['picture']);
				$issue['large_pic'] = $pic[0]."_large.".$pic[1];
				$issue['thumb_pic'] = $pic[0]."_thumb.".$pic[1];
				$txt .= '<tr>
				<td width="20"><input class="chk-issue-id" type="checkbox" name="chk_issue_id" value="'.$issue['issue_id'].'" /></td>
				<td width="50"><a class="image-popup-vertical-fit" href="/images/issues/'.$issue['large_pic'].'"><img src="/images/issues/'.$issue['thumb_pic'].'" data-large="/images/issues/'.$issue['large_pic'].'" width="50px" /></a></td>
				<td width="100">'.$issue['location'].'</td>
				<td>'.$issue['description'].'</td>
				</tr>';
			}
			$txt .= '</table></div>';
		}
		
		echo $txt;	
	}
	
	function getissuesbyshiftAction()
	{
		$params = $this->_getAllParams();
		$this->view->ident = $this->ident;
		Zend_Loader::LoadClass('issueClass', $this->modelDir);
		$issueClass = new issueClass();
		if($params['shift'] == 1)
		{
		    $startdate = $params['report_date']." 07:00:00";
		    $enddate = $params['report_date']." 15:00:00";
		}
		if($params['shift'] == 2)
		{
		    $startdate = $params['report_date']." 15:00:00";
		    $enddate = $params['report_date']." 23:00:00";
		}
		if($params['shift'] == 3)
		{
		    $reportdate = explode('-',$params['report_date']);
		    $startdate =  date("Y-m-d",mktime(0, 0, 0, $reportdate[1], $reportdate[2]-1, $reportdate[0]))." 23:00:00";
		    $enddate = $params['report_date']." 07:00:00";
		}
		$issues = $issueClass->getIssuesByTypeShift($params['id'], $params['cat_id'], $this->site_id, $startdate, $enddate);

		if(!empty($issues)) {
		    $txt = "";
			foreach($issues as $issue)
			{
			    $issuedatetime = explode(" ", $issue['issue_date']);
				$issue_date_time = date("j M Y", strtotime($issuedatetime[0]))." ".$issuedatetime[1];
				
			    if($issue['issue_date'] > "2019-10-23 14:30:00")
    			{
    				$issuedate = explode("-",$issuedatetime[0]);
    				$imageURL = "/images/issues/".$issuedate[0]."/";
    			}
    			else
    				$imageURL = "/images/issues/";
			    
				$pic = explode(".", $issue['picture']);
				$issue['large_pic'] = $pic[0]."_large.".$pic[1];
				$issue['thumb_pic'] = $pic[0]."_thumb.".$pic[1];
				
				$txt .= '<tr id="'.$issue['issue_id'].'">
				<td class="id-hidden"><a class="image-popup-vertical-fit" href="'.$this->baseUrl.$imageURL.$issue['large_pic'].'"><img src="'.$this->baseUrl.$imageURL.$issue['thumb_pic'].'" data-large="'.$this->baseUrl.$imageURL.$issue['large_pic'].'" width="50px" /></a></td>
				<td>'.$issue_date_time.'</td>
				<td>'.$issue['location'].'</td>
				<td>'.$issue['description'].'</td>
				<td><textarea id="followup-'.$issue['issue_id'].'" name="followup-'.$issue['issue_id'].'[]" class="form-control col-md-7 col-xs-12" style="height:50px;"></textarea></td></tr>';
			}
		}
		
		echo $txt;	
	}
	
	function getissuebyidAction()
	{
		$params = $this->_getAllParams();
		$this->view->ident = $this->ident;
		Zend_Loader::LoadClass('issueClass', $this->modelDir);
		$issueClass = new issueClass();
		$issue = $issueClass->getIssueById($params['id']);
		
		if(!empty($params['report_date']) && !empty($params['shift_id']))
		{
			Zend_Loader::LoadClass('securityClass', $this->modelDir);
			$securityClass = new securityClass();
			$sec = $securityClass->getSecurityReportByShift($params['report_date'],$params['shift_id'], $this->site_id);
			$issue['security_id'] = $sec['security_id'];
		}

		$issue_date_time = explode(" ",$issue['issue_date']);
		$issue['date_time'] = date("j M Y", strtotime($issue_date_time[0]))." ".$issue_date_time[1];
		
		$issue['security_id'] = $sec['security_id'];
		echo json_encode($issue);	
	}
	
	function getissuetypebyidAction()
	{
		$params = $this->_getAllParams();
		$this->view->ident = $this->ident;
		Zend_Loader::LoadClass('issuetypeClass', $this->modelDir);
		$issuetypeClass = new issuetypeClass();
		$issue_type = $issuetypeClass->getIssueTypeById($params['id']);
		echo json_encode($issue_type);	
	}

	function getissuetypeandfloorbycatidAction()
	{
		$rs = array();
		$params = $this->_getAllParams();
		$this->view->ident = $this->ident;
		Zend_Loader::LoadClass('issuetypeClass', $this->modelDir);
		$issuetypeClass = new issuetypeClass();
		$issue_type = $issuetypeClass->getIssueTypeByCategoryId($params['category_id']);

		Zend_Loader::LoadClass('floorClass', $this->modelDir);
		$floorClass = new floorClass();
		$floor = $floorClass->getFloorByCategoryId($params['category_id']);

		$rs['issue_type'] = $issue_type;
		$rs['floor'] = $floor;

		echo json_encode($rs);	
	}

	function getissuetypebycatidAction()
	{
		$params = $this->_getAllParams();
		$this->view->ident = $this->ident;
		Zend_Loader::LoadClass('issuetypeClass', $this->modelDir);
		$issuetypeClass = new issuetypeClass();
		$issue_type = $issuetypeClass->getIssueTypeByCategoryId($params['category_id']);
		echo json_encode($issue_type);	
	}


	function cacheopenedissuesommentsAction()
	{
		Zend_Loader::LoadClass('issueClass', $this->modelDir);
		$params = $this->_getAllParams();
		$issueClass = $this->loadModel('issue');
		$commentsTable = $this->loadModel('comments');

		/*for($j = 0; $j < 8; $j++)
		{*/
			$params['pagesize'] = 10;

			$totalIssues = $issueClass->getTotalPendingIssues('0', $params['site_id'], $params);
			
			$totalPage = ceil($totalIssues['total']/$params['pagesize']);
			for($k = 0; $k < $totalPage; $k++)
			{
				$params['start'] = $k * $params['pagesize'];
				$data= array();

				$issues = $issueClass->getIssueIds($params);	
				$i=0;
				foreach($issues as $issue) {
					$data[$i]['id'] = $issue['issue_id'];
					$comments = $commentsTable->getCommentsByIssueId( $issue['issue_id'], '3');
					if(!empty($comments)) { 
						$comment_content = "";
						foreach($comments as $comment)
						{
							$comment_content .= '<div style="border-bottom:1px solid #ccc; text-align:left; clear:both; margin-bottom:5px; padding-bottom:5px;"><strong>'.$comment['name'].' : </strong>'.$comment['comment'].'<br/>';
							if(!empty($comment['filename'])) $comment_content .= '<a href="'.$this->baseUrl."/comments/".substr($comment['comment_date'],0,4)."/".$comment['filename'].'" target="_blank"><i class="fa fa-paperclip"></i> '.$comment['filename'].'</a>';
							$comment_content .= '<div style="font-size:10px; color:#aaa; text-align:right;">'.$comment['comment_date']."</div></div>";
						}
						$data[$i]['comment'] = $comment_content;
					}
					$i++;
				}
				echo  "<br/><br/>opened_issue_comments_".$params["start"]."_".$params['site_id']."<br/>";
				print_r($data); 
				//$this->cache->save($data, "opened_issue_comments_".$params["start"]."_".$params['site_id'], array("opened_issues_comments_".$params["start"]."_".$params['site_id']), 0);
			}

		/*	sleep(5);
		} */
	}

	function  showissuesbycategoryAction()
	{
		$params = $this->_getAllParams();
		
		$this->view->ident = $this->ident;
		Zend_Loader::LoadClass('issueClass', $this->modelDir);
		$issueClass = new issueClass();

		if($params['s'] > 0  && $params['s'] != $this->ident['site_id'])
		{
				if($this->showSiteSelection == 1)
				{
					$siteTable = $this->loadModel('site');
					$siteTable->setSite($params['s']);
					$this->ident['site_id'] = $params['s'];
				
					$selIssue = $issueClass->getIssueById($params['id']);
					if($selIssue['solved'] == 1) $url = str_replace("listissues","solvedissues",$_SERVER['REQUEST_URI']);
					else $url = $_SERVER['REQUEST_URI'];

					$this->_response->setRedirect($this->baseUrl.$url);
					$this->_response->sendResponse();
					exit();
				}
		}
		if($params['c'] > 0) $params['category'] = $params['c'];
		if(!empty($params['id'])) $params['issue_id'] = $params['id'];
		if(empty($params['start'])) $params['start'] = '0';
		$params['pagesize'] = 10;
		$params['site_id']= $this->site_id;
		if(empty($params['category']) && $params['category']!='0')
		{
				if(count($this->ident['role_ids']) == 1)
				{
					Zend_Loader::LoadClass('userClass', $this->modelDir);
					$userClass = new userClass();
					$userRole = $userClass->getRoleById($this->ident['role_ids'][0]);
					if($userRole['category_id'] > 0) 	$params['category'] = $userRole['category_id'];
				} 
		}

		$issues = $issueClass->getIssues($params);
		if(empty($params['solved'])) $params['solved'] = 0;
	
		$totalIssues = $issueClass->getTotalPendingIssues($params['solved'], $this->site_id, $params);
		$category = $this->loadModel('category');
		$commentsTable = $this->loadModel('comments');
		$newIssue=array();
		foreach($issues as &$issue)
		{
			$issue_date_time = explode(" ",$issue['issue_date']);
			$issue['issue_date_time'] = date("j M Y", strtotime($issue_date_time[0]))." ".$issue_date_time[1];
			$d_start    = new DateTime($issue['issue_date']); 
			$d_end      = new DateTime(date('Y-m-d H:i:s')); 
			$diff = $d_start->diff($d_end);
			$issue['count_years'] = $diff->format('%y'); 
			$issue['count_months'] = $diff->format('%m'); 
			$issue['count_days'] = $diff->format('%d') + 1; 
			
			if(!empty($issue['issue_date']) && $issue['issue_date'] != "0000-00-00 00:00:00")
			{
			    $solved_issue_date_time = explode(" ",$issue['solved_date']);
		    	$issue['solved_issue_date_time'] = date("j M Y", strtotime($solved_issue_date_time[0]))." ".$solved_issue_date_time[1];
			}

			if($issue['issue_date'] > "2019-10-23 14:30:00")
			{
				$issuedate = explode("-",$issue_date_time[0]);
				$imageURL = "/images/issues/".$issuedate[0]."/";
			}
			else
				$imageURL = "/images/issues/";

			if($issue['solved_date'] > "2019-10-23 15:35:00")
			{
				$solvedissuedate = explode("-",$issue['solved_date']);
				$solvedImageURL = "/images/issues/".$solvedissuedate[0]."/";
			}
			else
				$solvedImageURL = "/images/issues/";

			$pic = explode(".", $issue['picture']);
			$issue['large_pic'] = $imageURL.$pic[0]."_large.".$pic[1];
			$issue['thumb_pic'] = $imageURL.$pic[0]."_thumb.".$pic[1];
			if(!empty($issue['category_id'])) {
				$issue['category'] = $category->getCategoryById($issue['category_id']);				
			}
			if($issue['issue_id'] == $params['id']) $newIssue = $issue;
			$solved_pic = explode(".", $issue['solved_picture']);
			$issue['large_solved_pic'] = $solvedImageURL.$solved_pic[0]."_large.".$solved_pic[1];
			$issue['thumb_solved_pic'] = $solvedImageURL.$solved_pic[0]."_thumb.".$solved_pic[1];
			$issue['comments'] = $commentsTable->getCommentsByIssueId($issue['issue_id'], '3');
			if($issue['category_id'] == 6)
			{
				$workorderClass = $this->loadModel('workorder');
				$progress_images = $workorderClass->getProgressImagesByIssueId($issue['issue_id']);
				if(!empty($progress_images)) {
					$k = 0;
					foreach($progress_images as $progress_image)
					{
						$progressImageURL = $this->baseUrl.'/workorder/'.substr($progress_image['uploaded_date'],0,4).'/'.$progress_image['filename'];
						$issue['progress_images'][$k]['large_pic'] = $progressImageURL;
						$issue['progress_images'][$k]['thumb_pic'] = $progressImageURL;
						$k++;
					}
					if($progress_images[0]['approved'] == 1) {
						unset($issue['progress_images'][$k-1]); 
					}
				}
				
			}
			else
			{
				$issue['progress_images'] = $issueClass->getProgressImages($issue['issue_id']);
				foreach($issue['progress_images'] as &$progress_image)
				{
					$issuedate = explode("-",$progress_image['upload_date']);
					$progressImageURL = "/images/issues/".$issuedate[0]."/";
					$pic = explode(".", $progress_image['filename']);
					$progress_image['large_pic'] = $progressImageURL.$pic[0]."_large.".$pic[1];
					$progress_image['thumb_pic'] = $progressImageURL.$pic[0]."_thumb.".$pic[1];
				}
			}
			
			if($issue['category_id'] == 6)
			{
				$woTable = $this->loadModel('workorder');
				$wo = $woTable->getWOByIssueId($issue['issue_id']);
				if(empty($wo)) $issue['show_add_wo_btn'] = 1;
				else $issue['show_add_wo_btn'] = 0;
			}
		}
		
		if($totalIssues['total'] > 10)
		{
			if($params['category'] > 0)	$cat_url = "/category/".$params['category'];
			else $cat_url = "";
			$dateParams = "";
			$solveParams = "";
			if(!empty($params['start_date'])) $dateParams .= "/start_date/".urlencode($params['start_date']);
			if(!empty($params['start_date'])) $dateParams .= "/end_date/".urlencode($params['end_date']);
			if(!empty($params['solved'])) $solveParams .= "/solved/".urlencode($params['solved']);
			if($params['start'] >= 10)
			{
				
				$this->view->firstPageUrl = "/default/issue/showissuesbycategory".$cat_url.$dateParams.$solveParams;
				$this->view->prevUrl = "/default/issue/showissuesbycategory/start/".($params['start']-$params['pagesize']).$cat_url.$dateParams.$solveParams;
			}
			if($params['start'] < (floor(($totalIssues['total']-1)/10)*10))
			{
				$this->view->nextUrl = "/default/issue/showissuesbycategory/start/".($params['start']+$params['pagesize']).$cat_url.$dateParams.$solveParams;
				$this->view->lastPageUrl = "/default/issue/showissuesbycategory/start/".(floor(($totalIssues['total']-1)/10)*10).$cat_url.$dateParams.$solveParams;
			}
		}
		$this->view->curPage = ($params['start']/$params['pagesize'])+1;
		$this->view->totalPage = ceil($totalIssues['total']/$params['pagesize']);
		if($totalIssues['total'] == 0) $this->view->startRec = 0;
		else $this->view->startRec = $params['start'] + 1;
		$endRec = $params['start'] + $params['pagesize'];
		if($totalIssues['total'] >=  $endRec) $this->view->endRec =  $endRec;
		else $this->view->endRec =  $totalIssues['total'];		
		$this->view->totalRec = $totalIssues['total'];
		$this->view->issues = $issues;
		
		$category = $this->loadModel('category');
		$this->view->categories = $category->getCategories();
		$this->view->category_id = $params['category'];
		$this->view->issue_id = $params['issue_id'];
		$this->view->start_date = $params['start_date'];
		$this->view->end_date = $params['end_date'];
		$this->view->start = $params['start'];
		$this->view->solved = intval($params['solved']);

		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "Issues Tab";
		$logData['data'] = json_encode($params);
		$logsTable->insertLogs($logData);

		echo $this->view->render('issues.tpl');
	}

	function updatecommentsAction()
	{
		$params = $this->_getAllParams();

		if(empty($params['start'])) $params['start'] = 0;

		if(empty($params['display_comment'])) $params['display_comment'] = 3;
		
		$commentCacheName = "issue_comments_".$this->site_id."_".$params["start"]."_"."_".$params["issue_id"]."_".$params["start_date"]."_".$params["end_date"]."_".$params["category"]."_".$params["solved"];
		if(empty($data))
		{
			$data= array();
			$issueTable = $this->loadModel('issue');
			$params['pagesize'] = 10;
			$issues = $issueTable->getIssueIds($params);	
			$commentsTable = $this->loadModel('comments');
			$i=0;
			foreach($issues as $issue) {
				$data[$i]['id'] = $issue['issue_id'];
				$comments = $commentsTable->getCommentsByIssueId( $issue['issue_id'], $params['display_comment']);
				if(!empty($comments)) { 
					$comment_content = "";
					foreach($comments as $comment)
					{
						$comment_content .= '<div style="border-bottom:1px solid #ccc; text-align:left; clear:both; margin-bottom:5px; padding-bottom:5px;"><strong>'.$comment['name'].' : </strong>'.$comment['comment'].'<br/>';
						if(!empty($comment['filename'])) $comment_content .= '<a href="'.$this->baseUrl."/comments/".substr($comment['comment_date'],0,4)."/".$comment['filename'].'" target="_blank"><i class="fa fa-paperclip"></i> '.$comment['filename'].'</a>';
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

	function removecommentcacheAction()
	{
		$params = $this->_getAllParams();

		if(empty($params['start'])) $params['start'] = 0;

		$commentCacheName = "issue_comments_".$this->site_id."_".$params["start"]."_"."_".$params["issue_id"]."_".$params["start_date"]."_".$params["end_date"]."_".$params["category"]."_".$params["solved"];
		//$this->cache->remove($commentCacheName);
	}

	function getincidentbyissuetypeidAction()
	{
		$params = $this->_getAllParams();
		Zend_Loader::LoadClass('incidentClass', $this->modelDir);
		$incidentClass = new incidentClass();
		$incidents = $incidentClass->getIncidentByIssueTypeId($params['issue_type'], $params['category_id']);
		echo json_encode($incidents);	
	}
	
	function getkejadianbyissuetypesAction()
	{
		$params = $this->_getAllParams();

		$issue_type_ids = implode(",",$params['issue_type_ids']);
		Zend_Loader::LoadClass('incidentClass', $this->modelDir);
		$incidentClass = new incidentClass();
		$incidents = $incidentClass->getKejadianByIssueTypeIds($issue_type_ids, $params['category_id']);
		echo json_encode($incidents);
	}
	
	function getkejadianbyissuetypesandsitesAction()
	{
		$params = $this->_getAllParams();

		$issue_type_ids = implode(",",$params['issue_type_ids']);
		if(!empty($sites)) $sites = implode(",",$params['sites']);
		Zend_Loader::LoadClass('incidentClass', $this->modelDir);
		$incidentClass = new incidentClass();
		$incidents = $incidentClass->getIncidentByCategoryIssueTypesSites($params['category_id'], $issue_type_ids, $sites );
		echo json_encode($incidents);
	}

	function getmodusbykejadianidAction()
	{
		$params = $this->_getAllParams();
		Zend_Loader::LoadClass('modusClass', $this->modelDir);
		$modusClass = new modusClass();
		$modus = $modusClass->getModusByKejadianId($params['kejadian_id'], $params['category_id']);
		echo json_encode($modus);	
	}

	function getmodusbykejadianidsAction()
	{
		$params = $this->_getAllParams();

		$kejadian_ids = implode(",",$params['kejadian_ids']);
		Zend_Loader::LoadClass('modusClass', $this->modelDir);
		$modusClass = new modusClass();
		$modus = $modusClass->getModusByKejadianIds($kejadian_ids, $params['category_id']);
		echo json_encode($modus);
	}

	function getmodusbyincidentsAction()
	{
		$params = $this->_getAllParams();

		Zend_Loader::LoadClass('incidentClass', $this->modelDir);
		$incidentClass = new incidentClass();

		Zend_Loader::LoadClass('siteClass', $this->modelDir);
		$siteClass = new siteClass();

		if(!empty($params['site_ids']))
		{
			$site_id = implode(",",$params['site_ids']);
		}
		else if(!empty($params['city_ids']))
		{
			$city = implode(",",$params['city_ids']);
			$this->view->sites = $sites = $siteClass->getSitesByCityId($city);
			$site_id = "";
			if(!empty($sites))
			{
				foreach($sites as $site)
				{
					$site_id .= $site['site_id'].",";
				}
			}	
			$site_id = substr($site_id, 0, -1);
		}
		else $site_id = 0;

		$incidents = "";
		foreach($params['incidents'] as $in)
		{
			$incident_ids = $incidentClass->getIncidentIdByIncidentNameAndSites($params['category_id'], $in, $site_id);
			$incidents .= implode(',', array_column($incident_ids, 'kejadian_id')).",";
		}
		$incidents = substr($incidents, 0, -1);

		Zend_Loader::LoadClass('modusClass', $this->modelDir);
		$modusClass = new modusClass();
		$modus = $modusClass->getModusByIncidentsAndSites($incidents, $params['category_id'], $site_id);
		echo json_encode($modus);
	}

	function updateissueAction()
	{
		$params = $this->_getAllParams();

		Zend_Loader::LoadClass('issueClass', $this->modelDir);
		$issueClass = new issueClass();
		if($params['pelaku_tertangkap'] == "on") $params['pelaku_tertangkap'] = '1';
		else $params['pelaku_tertangkap'] = '0';
		$issueClass->updateIncidentModusIssue($params);
	}
	
	function getlocationbyareaidAction()
	{
		$params = $this->_getAllParams();
		
		if(is_array($params['area_id'])) $params['area_id'] = implode(",",$params['area_id']);
		
		$this->view->ident = $this->ident;
		Zend_Loader::LoadClass('floorClass', $this->modelDir);
		$floorClass = new floorClass();
		$floor = $floorClass->getFloorByAreaId($params['area_id'], $params['cat_id']);
		
		echo json_encode($floor);	
	}
	function exportissuestopdfAction() {
		$params = $this->_getAllParams();
		if($params['solved'] == 1) $stat = "Closed";
		else $stat = "Opened";

		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "Export ".$stat." Kaizen to PDF";
		$logData['data'] = json_encode($params);
		$logsTable->insertLogs($logData);	
		
		$commentsTable = $this->loadModel('comments');
		
		require_once('fpdf/mc_table.php');
		$pdf=new PDF_MC_Table();
		$pdf->AddPage();
		if($this->teacher) $pdf->SetTitle("Opened Kaizen");
		else $pdf->SetTitle($stat." Kaizen");
		$pdf->SetFont('Arial','B',14);
		if($this->teacher) $pdf->Write(10,$stat."Opened Kaizen");
		else  $pdf->Write(10,$stat." Kaizen");
		$pdf->ln(10);
		
		Zend_Loader::LoadClass('categoryClass', $this->modelDir);
		$categoryClass = new categoryClass();
		$categories = $categoryClass->getCategories();
		Zend_Loader::LoadClass('issueClass', $this->modelDir);
		$issueClass = new issueClass();
		
		$workorderClass = $this->loadModel('workorder');
		
		if(!empty($categories))
		{
			$cat_ctr = 0;
			foreach($categories as $cat)
			{
				$params['category'] = $cat['category_id'];
				$params['site_id'] = $this->site_id;
				$issues = $issueClass->getIssues($params);
		
				if(!empty($issues))
				{
					if($cat_ctr > 0) $pdf->AddPage();
					$pdf->SetFont('Arial','B',10);
					$pdf->SetTextColor(0,0,0);
					$pdf->Write(10,$cat['category_name']);
					$pdf->Ln();
					$pdf->SetFont('Arial','B',8);
					$pdf->SetFillColor(158,130,75);
					$pdf->SetTextColor(255,255,255);
					$pdf->Cell(25,7,'Picture',1,0,'C',true);
					$pdf->Cell(80,7,'Detail',1,0,'C',true);
					$pdf->Cell(85,7,'Comment',1,0,'C',true);
					$pdf->Ln();
					$pdf->SetFont('Arial','',7);
					$pdf->SetTextColor(0,0,0);
					$pdf->SetWidths(array(25, 80, 85));	
					
					foreach($issues as $issue) { 	
						$issue_date_time = explode(" ",$issue['issue_date']);
						$issue['date'] = date("j-M-Y", strtotime($issue_date_time[0]));
						$detail = "Kaizen ID:".$issue['issue_id']."\nDate: ".$issue['date']."\n";
						if($this->config->general->show_kaizen_submitter == 1) $detail .= "Submitted by: ".$issue['name']."\n";
						$detail .= "Location: ".$issue['area_name']." - ".$issue['floor']." - ".$issue['location']."\n"."Category: ".$issue['issue_type']." - ".$issue['kejadian']." - ".$issue['modus']."\n"."Description: ".$issue['description'];
						

						$issue['comments'] = $commentsTable->getCommentsByIssueId($issue['issue_id'], '3');

						$comments = "";
						if(!empty($issue['comments'])) { 
							foreach($issue['comments'] as $comment)
							{
								$comment_date_time = explode(" ",$comment['comment_date']);
								$comment_date = date("j-M-Y", strtotime($comment_date_time[0]))." ".$comment_date_time[1];
								$comments .= strtoupper($comment['name']).' ('.$comment_date.') : '.trim($comment['comment'])."\n";
								if(!empty($comment['filename'])) 
								{
									//$comments .= $comment['filename']."\n";							
								}
								$comments .= "\n";
							}
						}
						$x1 = $pdf->GetY();
						
						$images = "Issue Image:\n\n\n\n\n";
						
						if($issue['category_id'] == 6)
						{
							$progress_images = $workorderClass->getProgressImagesByIssueId($issue['issue_id']);
							if($progress_images[0]['approved'] == 1) array_pop($progress_images);
						}
						else
						{
							$progress_images = $issueClass->getProgressImages($issue['issue_id']);
						}
						
						if(!empty($progress_images))
						{
							$images .= "Progress Image";
							if(count($progress_images) > 1) $images .= "s";
							foreach($progress_images as $pi)
							{
								$images .= "\n\n\n\n\n";
							}
						}
						
						if($params['solved'] == 1)  $images .= "Closed Image:\n\n\n\n\n";
						
						$pdf->Row(array($images,$detail,$comments));
						
						$x2= $pdf->GetY();
						if($x2<$x1) $y = 15;
						else $y = $pdf->GetY()-($x2-$x1-5);		
						$issuedate = explode("-",$issue['issue_date']); 
						$issueImageURL = $this->baseUrl."/images/issues/".$issuedate[0]."/";
						$issueImagePath = $this->config->paths->html.'/images/issues/'.$issuedate[0]."/";
						if(!empty($issue['picture']) && @getimagesize($issueImagePath.str_replace(".", "_thumb.", $issue['picture']))) {
							$pic = explode(".", $issue['picture']);
							if(in_array($pic[count($pic)-1], array("jpg", "jpeg")))
							{
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
								$fkaizen = fopen($issueImagePath.str_replace(".", "_thumb.", $issue['picture']), 'rb');

								if (!$fkaizen) {
									echo 'Error: Unable to open image for reading';
									exit;
								}
								
								$exifimgkaizen = exif_read_data($fkaizen);
								
								if($exifimgkaizen['Orientation'] == '3'  || $exifimgkaizen['Orientation'] == '6' || $exifimgkaizen['Orientation']=='8')
								{
									if($exifimgkaizen['Orientation'] == '3') $angleKaizen = 180;
									if($exifimgkaizen['Orientation'] == '6') $angleKaizen = -90;
									if($exifimgkaizen['Orientation'] == '8') $angleKaizen = 90;
									$pdf->Rotate($angleKaizen,11,$y);
									//echo $issue['picture']."#".$w."#".$width."#".$height."<br/>";
									if($width == $height) $yPos = $y-18;
									else if($width > $height) 
									{
										$scale = $width/18;
										$newHeight = $height/$scale;
										$newOff = 14-$newHeight;
										$yPos = $y-$newHeight;
									}
									else $yPos = $y-16;
									$pdf->Image($issueImagePath.str_replace(".", "_thumb.", $issue['picture']),11,$yPos, $w,$h);
									$pdf->Rotate(0);
								}
								else
									$pdf->Image($issueImagePath.str_replace(".", "_thumb.", $issue['picture']),11,$y, $w, $h);
							}
						}
						
						/*** PROGRESS IMAGES ***/
						
						if(!empty($progress_images)) {
							$y = $y+25;	
							if($issue['category_id'] == 6)
							{								
								$k = 0;
								foreach($progress_images as $progress_image)
								{
									if(($k == count($progress_images)-1) && $progress_image['approved'] == 1) ;
									else
									{
										$progressImage = $this->config->paths->html.'/workorder/'.substr($progress_image['uploaded_date'],0,4).'/'.$progress_image['filename'];
										$issue['progress_images'][$k]['large_pic'] = $progressImageURL;
										$issue['progress_images'][$k]['thumb_pic'] = $progressImageURL;
										
										$progress_picture_type = explode(".", strtolower($progress_image['filename']));
										if(@getimagesize($progressImage) && in_array($progress_picture_type[count($progress_picture_type)-1], array("jpg", "jpeg"))) {
											list($width, $height) = getimagesize($progressImage);
											if($width > $height)
											{
												$w = 18;
												$h = 0;
											}
											else {
												$w = 0;
												$h = 18;
											}
											$fkaizen = fopen($progressImage, 'rb');

											if (!$fkaizen) {
												echo 'Error: Unable to open image for reading';
												exit;
											}
											
											$exifimgkaizen = exif_read_data($fkaizen);
											
											if($exifimgkaizen['Orientation'] == '3'  || $exifimgkaizen['Orientation'] == '6' || $exifimgkaizen['Orientation']=='8')
											{
												if($exifimgkaizen['Orientation'] == '3') $angleKaizen = 180;
												if($exifimgkaizen['Orientation'] == '6') $angleKaizen = -90;
												if($exifimgkaizen['Orientation'] == '8') $angleKaizen = 90;
												$pdf->Rotate($angleKaizen,11,$y);
												if($width == $height) $yPos = $y-18;
												else if($width > $height) 
												{
													$scale = $width/18;
													$newHeight = $height/$scale;
													$newOff = 14-$newHeight;
													$yPos = $y-$newHeight;
												}
												else $yPos = $y-16;
												$pdf->Image($progressImage,11,$yPos, $w,$h);
												$pdf->Rotate(0);
											}
											else
												$pdf->Image($progressImage,11,$y, $w, $h); 
										}
									}
									$k++;
								} 		
							}
							else
							{
								foreach($progress_images as &$progress_image)
								{
									$issuedate = explode("-",$progress_image['upload_date']);
									$progressImagePath =  $this->config->paths->html."/images/issues/".$issuedate[0]."/";
									$pic = explode(".", $progress_image['filename']);
									
									if(@getimagesize($progressImagePath.str_replace(".", "_thumb.", $progress_image['filename'])) && in_array($pic[count($pic)-1], array("jpg", "jpeg"))) {
										list($width, $height) = getimagesize($progressImagePath.str_replace(".", "_thumb.", $progress_image['filename']));
										if($width > $height)
										{
											$w = 18;
											$h = 0;
										}
										else {
											$w = 0;
											$h = 18;
										}
										$fkaizen = fopen($progressImagePath.str_replace(".", "_thumb.", $progress_image['filename']), 'rb');

										if (!$fkaizen) {
											echo 'Error: Unable to open image for reading';
											exit;
										}
										
										$exifimgkaizen = exif_read_data($fkaizen);
										
										if($exifimgkaizen['Orientation'] == '3'  || $exifimgkaizen['Orientation'] == '6' || $exifimgkaizen['Orientation']=='8')
										{
											if($exifimgkaizen['Orientation'] == '3') $angleKaizen = 180;
											if($exifimgkaizen['Orientation'] == '6') $angleKaizen = -90;
											if($exifimgkaizen['Orientation'] == '8') $angleKaizen = 90;
											$pdf->Rotate($angleKaizen,11,$y);
											//echo $issue['solved_picture']."#".$w."#".$width."#".$height."<br/>";
											if($width == $height) $yPos = $y-18;
											else if($width > $height) 
											{
												$scale = $width/18;
												$newHeight = $height/$scale;
												$newOff = 14-$newHeight;
												$yPos = $y-$newHeight;
											}
											else $yPos = $y-16;
											$pdf->Image($progressImagePath.str_replace(".", "_thumb.", $progress_image['filename']),11,$yPos, $w,$h);
											$pdf->Rotate(0);
										}
										else
											$pdf->Image($progressImagePath.str_replace(".", "_thumb.", $progress_image['filename']),11,$y, $w, $h); 
									}
								}
							}
						}						
						
						
						/*** CLOSED IMAGES ***/
						
						if($params['solved'] == 1)
						{
							$y = $y+25;	
							$solvedIssuedate = explode("-",$issue['issue_date']); 
							$solvedIssueImageURL = $this->baseUrl."/images/issues/".$solvedIssuedate[0]."/";
							$solvedIssueImagePath = $this->config->paths->html.'/images/issues/'.$solvedIssuedate[0]."/";
							$solved_picture_type = explode(".", strtolower($issue['solved_picture']));
							if(!empty($issue['solved_picture']) && @getimagesize($solvedIssueImagePath.str_replace(".", "_thumb.", $issue['solved_picture'])) && in_array($solved_picture_type[count($solved_picture_type)-1], array("jpg", "jpeg"))) {
								list($width, $height) = getimagesize($solvedIssueImagePath.str_replace(".", "_thumb.", $issue['solved_picture']));
								if($width > $height)
								{
									$w = 18;
									$h = 0;
								}
								else {
									$w = 0;
									$h = 18;
								}
								$fkaizen = fopen($solvedIssueImagePath.str_replace(".", "_thumb.", $issue['solved_picture']), 'rb');

								if (!$fkaizen) {
									echo 'Error: Unable to open image for reading';
									exit;
								}
								
								$exifimgkaizen = exif_read_data($fkaizen);
								
								if($exifimgkaizen['Orientation'] == '3'  || $exifimgkaizen['Orientation'] == '6' || $exifimgkaizen['Orientation']=='8')
								{
									if($exifimgkaizen['Orientation'] == '3') $angleKaizen = 180;
									if($exifimgkaizen['Orientation'] == '6') $angleKaizen = -90;
									if($exifimgkaizen['Orientation'] == '8') $angleKaizen = 90;
									$pdf->Rotate($angleKaizen,11,$y);
									//echo $issue['solved_picture']."#".$w."#".$width."#".$height."<br/>";
									if($width == $height) $yPos = $y-18;
									else if($width > $height) 
									{
										$scale = $width/18;
										$newHeight = $height/$scale;
										$newOff = 14-$newHeight;
										$yPos = $y-$newHeight;
									}
									else $yPos = $y-16;
									$pdf->Image($solvedIssueImagePath.str_replace(".", "_thumb.", $issue['solved_picture']),11,$yPos, $w,$h);
									$pdf->Rotate(0);
								}
								else
									$pdf->Image($solvedIssueImagePath.str_replace(".", "_thumb.", $issue['solved_picture']),11,$y, $w, $h); 
							}
						}
					}
					$cat_ctr++;
				}
			}
		}
		
		$pdf->Output('I', "opened_kaizen.pdf", false);
	}
}
?>
