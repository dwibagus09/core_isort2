<?php

require_once('actionControllerBase.php');
require_once('Zend/Json.php');

class NfcController extends actionControllerBase
{
	public function viewAction() {
		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "View NFC";
		$logData['data'] = "View NFC Form";
		$logsTable->insertLogs($logData);	

		$this->renderTemplate('view_nfc.tpl'); 
	}

}
?>