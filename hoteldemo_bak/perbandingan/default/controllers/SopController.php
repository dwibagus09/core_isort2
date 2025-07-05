<?php

require_once('actionControllerBase.php');
require_once('Zend/Json.php');

class SopController extends actionControllerBase
{
	public function dashboardAction() {
		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "View SOP Dashboard";
		$logData['data'] = "View SOP Dashboard";
		$logsTable->insertLogs($logData);	

		$this->renderTemplate('sop_dashboard.tpl'); 
	}
	
	public function securityAction() {
		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "View Security SOP & IK";
		$logData['data'] = "View Security SOP & IK";
		$logsTable->insertLogs($logData);	

		$this->renderTemplate('security_sop_ik.tpl'); 
	}

	public function safetyAction() {
		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "View Safety SOP & IK";
		$logData['data'] = "View Safety SOP & IK";
		$logsTable->insertLogs($logData);	

		$this->renderTemplate('safety_sop_ik.tpl'); 
	}
	
	public function parkingAction() {
		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "View Parking & Traffic SOP & IK";
		$logData['data'] = "View Parking & Traffic SOP & IK";
		$logsTable->insertLogs($logData);	

		$this->renderTemplate('parking_sop_ik.tpl'); 
	}
	
	public function housekeepingAction() {
		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "View Housekeeping SOP & IK";
		$logData['data'] = "View Housekeeping SOP & IK";
		$logsTable->insertLogs($logData);	

		$this->renderTemplate('housekeeping_sop_ik.tpl'); 
	}
	
	public function trainingmaterialdashboardAction() {
		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "View SOP Dashboard";
		$logData['data'] = "View SOP Dashboard";
		$logsTable->insertLogs($logData);	

		$this->renderTemplate('training_material_dashboard.tpl'); 
	}
	
	public function securitytrainingmaterialAction() {
		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "View Security Training Material";
		$logData['data'] = "View Security Training Material";
		$logsTable->insertLogs($logData);	

		$this->renderTemplate('security_training_material.tpl'); 
	}

	public function safetytrainingmaterialAction() {
		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "View Safety Training Material";
		$logData['data'] = "View Safety Training Material";
		$logsTable->insertLogs($logData);	

		$this->renderTemplate('safety_training_material.tpl'); 
	}
	
	public function parkingtrainingmaterialAction() {
		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "View Parking & Traffing Training Material";
		$logData['data'] = "View Parking & Traffing Training Material";
		$logsTable->insertLogs($logData);	

		$this->renderTemplate('parking_training_material.tpl'); 
	}
	
	public function housekeepingtrainingmaterialAction() {
		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "View Housekeeping Training Material";
		$logData['data'] = "View Housekeeping Training Material";
		$logsTable->insertLogs($logData);	

		$this->renderTemplate('housekeeping_training_material.tpl'); 
	}
}
?>