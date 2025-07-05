<?php
require_once('actionControllerBase.php');

class ManpowerController extends actionControllerBase
{
	public function viewAction() {
		$params = $this->_getAllParams();
		Zend_Loader::LoadClass('manpowerClass', $this->modelDir);
		$manpowerClass = new manpowerClass();

		$manPower = $manpowerClass->getManPower($params);

		$this->view->manPower = $manPower;

		Zend_Loader::LoadClass('categoryClass', $this->modelDir);
		$categoryClass = new categoryClass();
	
		$this->view->category = $category = $categoryClass->getCategoryById($params['c']);

		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "View ".$category['category_name']." Man Power";
		$logData['data'] = json_encode($params);
		$logsTable->insertLogs($logData);

		$this->renderTemplate('manpower.tpl'); 
	}

	public function savemanpowerAction() {
		$params = $this->_getAllParams();
		Zend_Loader::LoadClass('manpowerClass', $this->modelDir);
		$manpowerClass = new manpowerClass();
		
		$manpowerClass->saveManPower($params);

		Zend_Loader::LoadClass('categoryClass', $this->modelDir);
		$categoryClass = new categoryClass();
		$this->view->category = $category = $categoryClass->getCategoryById($params['c']);
		
		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "Add Man Power";
		$logData['data'] = json_encode($params);
		$logsTable->insertLogs($logData);
	}

	public function getmanpowerbyidAction() {
		$params = $this->_getAllParams();
		Zend_Loader::LoadClass('manpowerClass', $this->modelDir);
		$manpowerClass = new manpowerClass();
		
		if(!empty($params['id'])) 
		{
			echo json_encode($manpowerClass->getManPowerById($params['id']));
		}
	}

	public function deletemanpowerbyidAction() {
		$params = $this->_getAllParams();
		Zend_Loader::LoadClass('manpowerClass', $this->modelDir);
		$manpowerClass = new manpowerClass();
		
		$manpowerClass->deleteManPowerById($params['id']);

		$logsTable = $this->loadModel('logs');
		$logData['user_id'] = intval($this->ident['user_id']);
		$logData['action'] = "Delete Man Power";
		$logData['data'] = json_encode($params);
		$logsTable->insertLogs($logData);	
		
		$this->_response->setRedirect($this->baseUrl.'/default/manpower/view/c/'.$params['c']);
		$this->_response->sendResponse();
		exit();
	}

	public function importmanpowerAction() {
		include "SimpleXLSX.php";
		$params = $this->_getAllParams();
		$xlsx = SimpleXLSX::parse('/home/emma/srt_pakuwon/sites/default/html/man_power_new/'.$params['f']);
		foreach($xlsx->rows() as $row )
		{
			$params["site_id"] = $row[0];
			$params["inhouse_outsource"] = $row[1];
			$params["name"] = $row[2];
			$params["c"] = $row[3];
			$params["year_start_exp"] = $row[4];
			$params["year_of_birth"] = $row[5];
			$params["join_year"] = $row[6];
			$params["position"] = $row[7];
			$params["vendor"] = $row[8];
			$params["certificate_no"] = $row[9];

			Zend_Loader::LoadClass('manpowerClass', $this->modelDir);
			$manpowerClass = new manpowerClass();	
			print_r($params); echo "<br/>";		
			$manpowerClass->insertManPower($params);
		}
	}

	public function getmanpowerbykeywordAction() {
		$params = $this->_getAllParams();

		Zend_Loader::LoadClass('modusClass', $this->modelDir);
		$modusClass = new modusClass();
		$modus = $modusClass->getModusById($params['m'], $params['c']);
		if(strpos(strtolower($modus['modus']), "inhouse")) $inhouse_outsource = '0';
		else $inhouse_outsource = '1';
		
		Zend_Loader::LoadClass('manpowerClass', $this->modelDir);
		$manpowerClass = new manpowerClass();
		$manpower = $manpowerClass->getManPowerByKeyword($params['q'], $params['c'], $inhouse_outsource);
		$mp = array();
		if(!empty($manpower))
		{
			foreach($manpower as $m)
			{
				$mp[$m['manpower_id']] = $m['name'];
			}
		}
		echo json_encode($mp);
		
	}

	public function getmanpowerbynameAction() {
		$params = $this->_getAllParams();
		Zend_Loader::LoadClass('manpowerClass', $this->modelDir);
		$manpowerClass = new manpowerClass();
		
		if(!empty($params['name'])) 
		{
			Zend_Loader::LoadClass('modusClass', $this->modelDir);
			$modusClass = new modusClass();
			$modus = $modusClass->getModusById($params['m'], $params['c']);
			if(strpos(strtolower($modus['modus']), "inhouse")) $inhouse_outsource = '0';
			else $inhouse_outsource = '1';
			$manpower = $manpowerClass->getManPowerIdByName($params['name'], $params['c'], $inhouse_outsource);
			
			echo json_encode($manpower);
		}
	}
}

?>