<?php

class userlogClass extends defaultClass
{

	function __construct()
	{
		parent::__construct();
	}

	
	function insertUserLog($ident) {
		$table = "user_log_".date("Y");
		$userlogTable = new $table(array('db'=>'db'));
		
		$data = array(
			"user_id" => $ident['user_id']
		);
		$userlogTable->insert($data);
	}

	function getUserLog($startdate, $enddate, $limit = 10) {
		$table = "user_log_".date("Y");
		$userlogTable = new $table(array('db'=>'db'));
		
		/*$select = $userlogTable->getAdapter()->select();
		$select->from(array("l"=>"user_log"), array("l.*", "count(l.log_id) as total_login"));
		$select->joinLeft(array("u"=>"users"), "u.user_id = l.user_id", array("u.name", "u.role_id"));
		$select->where('u.site_id = '. $this->site_id . ' or u.role_id IN (13,14,15,16)');
		$select->where('u.role_id <> ?', 1);
		$select->where('u.role_id <> ?', 6);
		$select->where('u.role_id <> ?', 4);
		$select->where('date(login_date) >= ?', $startdate);
		$select->where('date(login_date) <= ?', $enddate);
		$select->group('l.user_id');
		$select->order('total_login desc');
		$select->limit($limit);*/
		
		if(date("Y") == $this->config->general->start_year) $prevYear = date("Y");
		else $prevYear = date("Y")-1;
		
		$curYear = date("Y");
		
		$select = "SELECT user_id, name, role_id, SUM(total) as total_login
			FROM
			(
			(SELECT `l`.*, count(l.log_id) AS `total`, `u`.`name`, `u`.`role_id` FROM `user_log_{$prevYear}` AS `l` LEFT JOIN `users` AS `u` ON u.user_id = l.user_id WHERE (u.site_id = {$this->site_id} or u.role_id IN (13,14,15,16)) AND (u.role_id <> 1) AND (u.role_id <> 6) AND (u.role_id <> 4) AND (date(login_date) >= '{$startdate}') AND (date(login_date) <= '{$enddate}') GROUP BY `l`.`user_id` ORDER BY `total` desc, name LIMIT 10)
			UNION
			(SELECT `l`.*, count(l.log_id) AS `total`, `u`.`name`, `u`.`role_id` FROM `user_log_{$curYear}` AS `l` LEFT JOIN `users` AS `u` ON u.user_id = l.user_id WHERE (u.site_id = {$this->site_id} or u.role_id IN (13,14,15,16)) AND (u.role_id <> 1) AND (u.role_id <> 6) AND (u.role_id <> 4) AND (date(login_date) >= '{$startdate}') AND (date(login_date) <= '{$enddate}') GROUP BY `l`.`user_id` ORDER BY `total` desc, name LIMIT 10)
			) t
			group by user_id order by total_login desc, name limit 10";

		return $userlogTable->getAdapter()->fetchAll($select);
	}

	function getLastUserLog($user_id) {
		$table = "user_log_".date("Y");
		$userlogTable = new $table(array('db'=>'db'));

		$select = $userlogTable->select()->where('user_id = ?', $user_id)->order('login_date desc')->limit(1);
		return $this->db->fetchRow($select);
	}

	function getTotalUserLog($site_id, $startdate, $enddate) {
		$table = "user_log_".date("Y");
		$userlogTable = new $table(array('db'=>'db'));
		
		/*$select = $userlogTable->getAdapter()->select();
		$select->from(array("l"=>"user_log"), array("count(*) as total_login"));
		$select->joinLeft(array("u"=>"users"), "u.user_id = l.user_id", array());
		$select->where('u.site_id = '. $site_id . ' or u.role_id IN (13,14,15,16)');
		$select->where('u.role_id <> ?', 1);
		$select->where('u.role_id <> ?', 6);
		$select->where('date(login_date) >= ?', $startdate);
		$select->where('date(login_date) <= ?', $enddate);*/
		
		if(date("Y") == $this->config->general->start_year) $prevYear = date("Y");
		else $prevYear = date("Y")-1;
		
		$curYear = date("Y");

		$select = "SELECT SUM(total) as total_login
			FROM
			(
			(SELECT count(*) AS `total` FROM `user_log_{$prevYear}` AS `l` LEFT JOIN `users` AS `u` ON u.user_id = l.user_id WHERE (u.site_id = {$site_id} or u.role_id IN (13,14,15,16)) AND (u.role_id <> 1) AND (u.role_id <> 6) AND (date(login_date) >= '{$startdate}') AND (date(login_date) <= '{$enddate}'))
			UNION
			(SELECT count(*) AS `total` FROM `user_log_{$curYear}` AS `l` LEFT JOIN `users` AS `u` ON u.user_id = l.user_id WHERE (u.site_id = {$site_id} or u.role_id IN (13,14,15,16)) AND (u.role_id <> 1) AND (u.role_id <> 6) AND (date(login_date) >= '{$startdate}') AND (date(login_date) <= '{$enddate}'))
			) t";

		$total = $userlogTable->getAdapter()->fetchRow($select);
		return $total['total_login'];
	}

	function getOMLoginStat($startdate, $enddate, $limit = 10) {
		$table = "user_log_".date("Y");
		$userlogTable = new $table(array('db'=>'db'));
		
		/*$select = $userlogTable->getAdapter()->select();
		$select->from(array("l"=>"user_log"), array("count(l.log_id) as total_login", "l.user_id"));
		$select->joinLeft(array("u"=>"users"), "u.user_id = l.user_id", array("u.name","u.role_id", "u.site_id"));
		$select->joinLeft(array("s"=>"sites"), "s.site_id = u.site_id", array("s.initial"));
		$select->where('u.role_id like "%4%"');
		$select->where('u.role_id not like "%14%"');
		$select->where('date(login_date) >= ?', $startdate);
		$select->where('date(login_date) <= ?', $enddate);
		$select->group("l.user_id");
		$select->order('total_login desc');*/
		//$select->limit($limit);

		if(date("Y") == $this->config->general->start_year) $prevYear = date("Y");
		else $prevYear = date("Y")-1;
		
		$curYear = date("Y");

		
		$sql = "SELECT user_id, name, role_id, initial, SUM(total) as total_login
			FROM
			((SELECT `l`.user_id, count(l.log_id) AS `total`, `u`.`name`, `u`.`role_id`, `s`.`initial` FROM `user_log_{$prevYear}` AS `l` 
			LEFT JOIN `users` AS `u` ON u.user_id = l.user_id 
			LEFT JOIN `sites` AS `s` ON s.site_id = u.site_id 
			WHERE (u.role_id like '4,%' or u.role_id = '4' or u.role_id like '%,4' or u.role_id like '%,4,%') 
			AND (date(login_date) >= '{$startdate}') AND (date(login_date) <= '{$enddate}') GROUP BY `l`.`user_id` ORDER BY `total` desc)
			UNION
			(SELECT `l`.user_id, count(l.log_id) AS `total`, `u`.`name`, `u`.`role_id`, `s`.`initial` FROM `user_log_{$curYear}` AS `l` 
			LEFT JOIN `users` AS `u` ON u.user_id = l.user_id 
			LEFT JOIN `sites` AS `s` ON s.site_id = u.site_id 
			WHERE (u.role_id like '4,%' or u.role_id = '4' or u.role_id like '%,4' or u.role_id like '%,4,%') 
			AND (date(login_date) >= '{$startdate}') AND (date(login_date) <= '{$enddate}') GROUP BY `l`.`user_id` ORDER BY `total` desc)
			) t
			group by user_id order by total_login desc";
		
		return $userlogTable->getAdapter()->fetchAll($sql);
	}

	function getUserLogByCatId($startdate, $enddate, $cat_id, $site_id = 0, $limit = 10) {		
		/*$select = $userlogTable->getAdapter()->select();
		$select->from(array("l"=>"user_log"), array("l.*", "count(l.log_id) as total_login"));
		$select->joinLeft(array("u"=>"users"), "u.user_id = l.user_id", array("u.name", "u.role_id"));
		$select->joinLeft(array("s"=>"sites"), "s.site_id = u.site_id", array("s.initial"));
		if(!empty($site_id) && $site_id > 0) $select->where('u.site_id = ?', $site_id );
		switch($cat_id)
		{
			// security
			case 1: $select->where('(u.role_id like "%2%" && u.role_id not like "%12%") or (u.role_id like "%3%" and u.role_id not like "%13%")');
					break;
			// Housekeeping
			case 2: $select->where('u.role_id like "%9%"');
					break;

			// Safety
			case 3: $select->where('(u.role_id like "%7%" && u.role_id not like "%17%")');
					break;

			// Parking & Traffic
			case 5: $select->where('(u.role_id like "%8%" && u.role_id not like "%18%")');
					break;
		}
		$select->where('date(login_date) >= ?', $startdate);
		$select->where('date(login_date) <= ?', $enddate);
		$select->group('l.user_id');
		$select->order('total_login desc');
		if($limit > 0) $select->limit($limit);
		return $userlogTable->getAdapter()->fetchAll($select);*/

		switch($cat_id)
		{
			// security
			case 1: $sqlrole = "u.role_id like '2,%' or u.role_id = '2' or u.role_id like '%,2' or u.role_id like '%,2,%' or u.role_id like '3,%' or u.role_id = '3' or u.role_id like '%,3' or u.role_id like '%,3,%'";
					break;
			// Housekeeping
			case 2: $sqlrole = "u.role_id like '9,%' or u.role_id = '9' or u.role_id like '%,9' or u.role_id like '%,9,%'";
					break;

			// Safety
			case 3: $sqlrole = "u.role_id like '7,%' or u.role_id = '7' or u.role_id like '%,7' or u.role_id like '%,7,%'";
					break;

			// Parking & Traffic
			case 5: $sqlrole = "u.role_id like '8,%' or u.role_id = '8' or u.role_id like '%,8' or u.role_id like '%,8,%'";
					break;
					
			// Engineering
			case 6: $sqlrole = "u.role_id like '21,%' or u.role_id = '21' or u.role_id like '%,21' or u.role_id like '%,21,%' or u.role_id like '31,%' or u.role_id = '31' or u.role_id like '%,31' or u.role_id like '%,31,%'";
					break;
		}
		
		if(date("Y") == $this->config->general->start_year) $prevYear = date("Y");
		else $prevYear = date("Y")-1;
		
		$curYear = date("Y");
		
		$table = "user_log_".$curYear;
		$userlogTable = new $table(array('db'=>'db'));

		
		
		$sql = "SELECT user_id, name, role_id, initial, SUM(total) as total_login
			FROM
			((SELECT `l`.user_id, count(l.log_id) AS `total`, `u`.`name`, `u`.`role_id`, `s`.`initial` FROM `user_log_{$prevYear}` AS `l` 
			LEFT JOIN `users` AS `u` ON u.user_id = l.user_id 
			LEFT JOIN `sites` AS `s` ON s.site_id = u.site_id 
			WHERE ({$sqlrole}) 
			AND (date(login_date) >= '{$startdate}') AND (date(login_date) <= '{$enddate}') GROUP BY `l`.`user_id` ORDER BY `total` desc LIMIT 10)
			UNION
			(SELECT `l`.user_id, count(l.log_id) AS `total`, `u`.`name`, `u`.`role_id`, `s`.`initial` FROM `user_log_{$curYear}` AS `l` 
			LEFT JOIN `users` AS `u` ON u.user_id = l.user_id 
			LEFT JOIN `sites` AS `s` ON s.site_id = u.site_id 
			WHERE ({$sqlrole}) 
			AND (date(login_date) >= '{$startdate}') AND (date(login_date) <= '{$enddate}') GROUP BY `l`.`user_id` ORDER BY `total` desc LIMIT 10)
			) t
			group by user_id order by total_login desc";
		
		return $userlogTable->getAdapter()->fetchAll($sql);
	}


}
