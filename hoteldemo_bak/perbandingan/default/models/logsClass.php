<?php

class logsClass extends defaultClass
{

	function __construct()
	{
		parent::__construct();
	}

	
	function insertLogs($params) {
		/*if(date("Y") < 2020) $year = 2020;
		else $year = date("Y");*/

		$mo = date("n");

		$table = "logs_".$mo;
		$logTable = new $table(array('db'=>'db3'));

		$protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
		
		$data = array(
			"site_id" => intval($this->site_id),
			"user_id" => $params['user_id'],
			"action" => $params['action'],
			"data" => $params['data'],
			"log_date" => date("Y-m-d H:i:s"),
			"browser" => $_SERVER['HTTP_USER_AGENT'],
			"ip_address" => $_SERVER['REMOTE_ADDR'],
			"url" => $protocol.$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']
		);

		$logTable->insert($data);
	}
	
	function clearMonthlyLogs() {
		$this->db->query('truncate table logs_'.date("n", mktime(0, 0, 0, date("m")+1, date("d"),   date("Y"))));
	}
}
