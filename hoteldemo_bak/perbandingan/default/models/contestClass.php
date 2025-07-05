<?php

require_once('defaultClass.php');
require_once('dbClass.php');

class contestClass extends defaultClass
{
	function __construct()
	{
		parent::__construct();
	}
	
	function getContests($contestSiteId) {
		$contestTable = new contest_db(array('db'=>'db_contest'));
		$select = $contestTable->select();
		$select->where("endDate>=?", date("Y-m-d"));
		$select->where("contestId!=?", '1');
		$select->order("contestId desc");
		//$select->where("siteId=?", $contestSiteId);
		$contest = $contestTable->getAdapter()->fetchAll($select);
		//print_r($contest); exit();
		return $contest;
	}
	
	function getBestballot($bestballotSiteId) {
		$contestsTable = new contests(array('db'=>'db_bestballot'));
		$select = $contestsTable->select();
		$select->where("site_id=?", $bestballotSiteId);
		$bestballot = $contestsTable->getAdapter()->fetchAll($select);
		//print_r($contest); exit();
		return $bestballot;
	}
	
	function getContest($siteId) {
		$contestTable = new contest(array('db'=>'db'));
		$select = $contestTable->select();
		$now = date("Y-m-d");
		$select->where("site_id=?", $siteId);
		$select->where("'{$now}' BETWEEN start_date AND end_date");
		$select->order("order_id");
		$contest = $contestTable->getAdapter()->fetchAll($select);
		return $contest;
	}
}
?>