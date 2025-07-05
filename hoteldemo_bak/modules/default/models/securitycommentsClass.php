<?php

require_once('defaultClass.php');
require_once('dbClass.php');

class securitycommentsClass extends defaultClass
{
	function __construct()
	{
		parent::__construct();
	}
	
	function addComment($params) {
		$commentTable = new security_comments(array('db'=>'db3'));
		$commentTable->insert(array(
			"comment"		=> $params["comment"],
			"user_id"		=> $params["user_id"],
			"comment_date"	=> date("Y-m-d H:i:s"),
			"report_date"	=> $params["report_date"],
			"site_id"		=> $params["site_id"],
			"filename"		=> $params["filename"],
		));
	}
	
	function getCommentsByReportDate($report_date, $qty=0, $site_id, $sort = 'desc') {
		$commentsTable = new security_comments(array('db'=>'db3'));
		$select = $commentsTable->getAdapter()->select();
		$select->from(array("c"=>"security_comments"), array('c.*'));
		$select->joinLeft(array("u"=>"users"), "u.user_id = c.user_id", array("u.*"));
		$select->where("c.report_date=?", $report_date);
		$select->where("c.site_id=?", $site_id);
		$select->order("c.comment_id ".$sort);
		if($qty > 0) $select->limit($qty);
		return $commentsTable->getAdapter()->fetchAll($select);
	}
	
	function deleteCommentsBySecurityId($id)
	{
		$commentsTable = new security_comments(array('db'=>'db3'));
		
		if ( is_numeric($id) && $id > 0 )
		{		
			$where = $commentsTable->getAdapter()->quoteInto('security_id = ?', $id);
			$commentsTable->delete($where);
		}
	}

	
	/*** CHIEF SECURITY REPORT COMMENTS ***/
	
	function getCommentsByChiefSecurityId($chief_security_report_id, $qty=0) {
		$commentsTable = new chief_security_comments(array('db'=>'db3'));
		$select = $commentsTable->getAdapter()->select();
		$select->from(array("c"=>"chief_security_comments"), array('c.*'));
		$select->joinLeft(array("u"=>"users"), "u.user_id = c.user_id", array("u.*"));
		$select->where("c.chief_security_report_id=?", $chief_security_report_id);
		$select->order("c.comment_id desc");
		if($qty > 0) $select->limit($qty);
		return $commentsTable->getAdapter()->fetchAll($select);
	}
	
	function addChiefComment($params) {
		$commentTable = new chief_security_comments(array('db'=>'db3'));
		$commentTable->insert(array(
			"chief_security_report_id"	=> $params["chief_security_report_id"],
			"comment"		=> $params["comment"],
			"user_id"		=> $params["user_id"],
			"comment_date"	=> date("Y-m-d H:i:s"),
			"report_date"	=> $params["report_date"]
		));
	}
	
	function deleteChiefCommentsById($id)
	{
		$commentTable = new chief_security_comments(array('db'=>'db3'));
		
		if ( is_numeric($id) && $id > 0 )
		{		
			$where = $commentTable->getAdapter()->quoteInto('chief_security_report_id = ?', $id);
			$commentTable->delete($where);
		}
	}

	function getTotalCommentsByUser($user_id, $startdate, $enddate) {
		$commentsTable = new security_comments(array('db'=>'db3'));
		$select = $commentsTable->select();
		$select->from('security_comments', array('count(comment_id) as total_comment'));
		$select->where('user_id = ?', $user_id);	
		$select->where('site_id = ?', $this->site_id);	
		$select->where('date(comment_date) >= ?', $startdate);
		$select->where('date(comment_date) <= ?', $enddate);
		return $commentsTable->getAdapter()->fetchRow($select);
	}

	function getTotalCommentsBySiteId($site_id, $startdate, $enddate) {
		$commentsTable = new security_comments(array('db'=>'db3'));
		$select = $commentsTable->select();
		$select->from('security_comments', array('count(comment_id) as total_comment'));
		$select->where('site_id = ?', $site_id);	
		$select->where('date(comment_date) >= ?', $startdate);
		$select->where('date(comment_date) <= ?', $enddate);
		return $commentsTable->getAdapter()->fetchRow($select);
	}
}
?>