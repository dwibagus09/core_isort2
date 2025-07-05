<?php

require_once('actionControllerBase.php');
require_once('Zend/Json.php');

class FeedbackController extends actionControllerBase
{
	public function viewformAction() {
		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "View Feedback Me Form";
		$logData['data'] = "View Feedback Me Form";
		$logsTable->insertLogs($logData);	

		$this->renderTemplate('form_feedback_me.tpl'); 
	}

	public function sendAction() {
		$params = $this->_getAllParams();

		$params['user_id'] = $this->ident['user_id'];
		$feedbackTable = $this->loadModel('feedback');
		$feedback = $feedbackTable->saveFeedback($params);

		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "Send Feedback Me Form";
		$logData['data'] = json_encode($params);
		$logsTable->insertLogs($logData);	

		return true;
	}

	public function inboxAction() {
		if($this->viewFeedbackInbox == 1)
		{
			$params = $this->_getAllParams();

			$this->view->params = $params;

			$feedbackTable = $this->loadModel('feedback');
			$feedback = $feedbackTable->getFeedback($params);
			if(!empty($feedback))
			{
				foreach($feedback as &$fb)
				{
					$send_date = explode(" ", $fb['send_date']);
					$fb['date'] = date("j F Y", strtotime($send_date[0]))." ".$send_date[1];
				}
			}
			$this->view->feedback = $feedback;

			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "View Feedback Me Inbox";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);	
			

			$this->renderTemplate('feedback_me_inbox.tpl'); 
		}
		else {
			$this->_response->setRedirect($this->baseUrl);
			$this->_response->sendResponse();
			exit();
		}
	}

	public function viewdetailAction() {		
		$params = $this->_getAllParams();
		if($this->viewFeedbackInbox == 1 && !empty($params['id']))
		{
			$feedbackTable = $this->loadModel('feedback');
			$fb = $feedbackTable->getFeedbackById($params['id']);
			$send_date = explode(" ", $fb['send_date']);
			$fb['date'] = date("l, j F Y", strtotime($send_date[0]))." ".$send_date[1];
			$this->view->feedback = $fb;

			$params['user_view'] = $this->ident['user_id'];
			$feedbackTable->updateView($params);

			$logsTable = $this->loadModel('logs');
			$logData['user_id'] = intval($this->ident['user_id']);
			$logData['action'] = "View Feedback Me Detail";
			$logData['data'] = json_encode($params);
			$logsTable->insertLogs($logData);	

			$this->renderTemplate('feedback_me_detail.tpl'); 
		}
		else {
			$this->_response->setRedirect($this->baseUrl);
			$this->_response->sendResponse();
			exit();
		}
	}

}
?>