<?php

require_once('dbClass.php');

class defaultClass
{
	public $config;
	public $db;				//db object
	//public $db_contest;
	//public $db_bestballot;
	public $session;		//session object
	public $error_message;
	public $xrclient;

	function __construct()
	{
		$this->config		= Zend_Registry::get('config');
		$this->db			= Zend_Registry::get('db');
		$this->db2			= Zend_Registry::get('db2');
		$this->db3			= Zend_Registry::get('db3');
		$this->session		= Zend_Registry::get('session');
		$this->view			= Zend_Registry::get('view');
		$this->dbLogger		= Zend_Registry::get('dbLogger');
		$this->site_id		= Zend_Registry::get('site_id');
	}

	function niceDate($date)
	{
		$year = substr($date,0,4);
		$month = substr($date,4,2);
		$day = substr($date,-2);
		return("$month/$day/$year");
	}
}
?>