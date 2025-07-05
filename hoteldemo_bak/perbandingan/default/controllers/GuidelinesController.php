<?php

require_once('actionControllerBase.php');
require_once('Zend/Json.php');

class GuidelinesController extends actionControllerBase
{
	public function covid19Action() {
		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "View Covid-19 Guidelines";
		$logData['data'] = "View Covid-19 Guidelines";
		$logsTable->insertLogs($logData);	

		$this->renderTemplate('sop_dashboard.tpl'); 
	}
	
	public function trainingmaterialAction() {
		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "View SOP Training Material";
		$logData['data'] = "View SOP Training Material";
		$logsTable->insertLogs($logData);	

		$this->renderTemplate('sop_training_material.tpl'); 
	}

}
?>