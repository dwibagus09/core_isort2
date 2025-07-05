<?php
require_once('actionControllerBase.php');

class SitemanagerController extends actionControllerBase
{
    public function dashboardAction() {
		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "View Site Manager Dashboard";
		$logData['data'] = "View Site Manager Dashboard";
		$logsTable->insertLogs($logData);	

		$this->renderTemplate('site_manager_dashboard.tpl'); 
	}
	
}

?>