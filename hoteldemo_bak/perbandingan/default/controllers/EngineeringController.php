<?php
require_once('actionControllerBase.php');

class EngineeringController extends actionControllerBase
{
    public function dashboardAction() {
		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "View Engineering Dashboard";
		$logData['data'] = "View Engineering Dashboard";
		$logsTable->insertLogs($logData);	

		$this->renderTemplate('engineering_dashboard.tpl'); 
	}
	
}

?>