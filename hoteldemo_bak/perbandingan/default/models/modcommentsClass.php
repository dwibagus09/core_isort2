<?php

require_once('defaultClass.php');
require_once('dbClass.php');

class modcommentsClass extends defaultClass
{
	function __construct()
	{
		parent::__construct();
	}
	
	function addComment($params) {
		$commentTable = new mod_comments(array('db'=>'db3'));
		$commentTable->insert(array(
			"mod_report_id"			=> $params["report_id"],
			"comment"				=> $params["comment"],
			"user_id"				=> $params["user_id"],
			"comment_date"			=> date("Y-m-d H:i:s"),
			"site_id"				=> $params["site_id"],
			"filename"				=> $params["filename"]
		));
	}
	
	function getCommentsByModReportId($mod_report_id, $qty=0, $sort='desc') {
		$commentsTable = new mod_comments(array('db'=>'db3'));
		$select = $commentsTable->getAdapter()->select();
		$select->from(array("c"=>"mod_comments"), array('c.*'));
		$select->joinLeft(array("u"=>"users"), "u.user_id = c.user_id", array("u.*"));
		$select->where("c.mod_report_id=?", intval($mod_report_id));
		$select->order("c.comment_id ".$sort);
		if($qty > 0) $select->limit($qty);
		return $commentsTable->getAdapter()->fetchAll($select);
	}

	
	function deleteCommentsByModReportId($id)
	{
		$commentTable = new mod_comments(array('db'=>'db3'));
		
		if ( is_numeric($id) && $id > 0 )
		{		
			$where = $commentTable->getAdapter()->quoteInto('mod_report_id = ?', $id);
			$commentTable->delete($where);
		}
	}

	function getTotalCommentsByUser($user_id, $startdate, $enddate) {
		$commentsTable = new mod_comments(array('db'=>'db3'));
		$select = $commentsTable->select();
		$select->from('mod_comments', array('count(comment_id) as total_comment'));
		$select->where('user_id = ?', $user_id);	
		$select->where('site_id = ?', $this->site_id);	
		$select->where('date(comment_date) >= ?', $startdate);
		$select->where('date(comment_date) <= ?', $enddate);
		return $commentsTable->getAdapter()->fetchRow($select);
	}

	function getTotalCommentsBySiteId($site_id, $startdate, $enddate) {
		$commentsTable = new mod_comments(array('db'=>'db3'));
		$select = $commentsTable->select();
		$select->from('mod_comments', array('count(comment_id) as total_comment'));
		$select->where('site_id = ?', $site_id);	
		$select->where('date(comment_date) >= ?', $startdate);
		$select->where('date(comment_date) <= ?', $enddate);
		return $commentsTable->getAdapter()->fetchRow($select);
	}
}
?>