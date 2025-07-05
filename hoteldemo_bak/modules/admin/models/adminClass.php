<?php

require_once('dbClass.php');

class adminClass
{
	public $config;
	public $db;				//db object
	public $session;		//session object
	public $error_message;
	public $site_id;
	public $ident;

	function __construct()
	{
		$this->config	= Zend_Registry::get('config');
		$this->db		= Zend_Registry::get('db');
		$this->session	= Zend_Registry::get('session');
		$this->view		= Zend_Registry::get('view');
		$this->dbLogger	= Zend_Registry::get('dbLogger');
		$this->auth     = Zend_Registry::get('auth');
		if(Zend_Registry::isRegistered("site_id"))
			$this->site_id	= Zend_Registry::get('site_id');
		if(Zend_Registry::isRegistered("site_group_id"))
			$this->site_group_id = Zend_Registry::get('site_group_id');
			
		$this->ident = $this->auth->getIdentity();
	}

	function niceDate($date)
	{
		$year = substr($date,0,4);
		$month = substr($date,4,2);
		$day = substr($date,-2);
		return("$month/$day/$year");
	}
	
	function addLog($uniqueId, $action = "add", $moduleName = "", $oldData, $newData, $altDesc = "") {
		$description = $action." ".$moduleName.": #".$uniqueId;
		if(!empty($altDesc)) $description .= " - ".$altDesc;
		$logTable = new logs(array('db'=>'db'));
		$logTable->insert(array(
			"site_id"			=> $this->site_id,
			"user_id"			=> $this->ident['adminuserid'],
			"log_date"			=> date("Y-m-d H:i:s"),
			"description"		=> $description,
			"before_edit"		=> serialize($oldData),
			"after_edit"		=> serialize($newData),
			"from_ip"			=> $_SERVER['REMOTE_ADDR'],
		));
	}
}

