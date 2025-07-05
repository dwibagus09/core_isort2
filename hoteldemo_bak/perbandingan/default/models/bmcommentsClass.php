<?php

require_once('defaultClass.php');
require_once('dbClass.php');

class bmcommentsClass extends defaultClass
{
	function __construct()
	{
		parent::__construct();
	}
	
	function addComment($params) {
		$commentsTable = new bm_comments(array('db'=>'db3'));
		$commentsTable->insert(array(
			"report_id"				=> $params["report_id"],
			"comment"				=> $params["comment"],
			"user_id"				=> $params["user_id"],
			"site_id"				=> $this->site_id,
			"comment_date"			=> date("Y-m-d H:i:s"),
			"filename"				=> $params["filename"]
		));
	}
	
	function getCommentsByReportId($bm_report_id, $qty=0) {
		$commentsTable = new bm_comments(array('db'=>'db3'));
		$select = $commentsTable->getAdapter()->select();
		$select->from(array("c"=>"bm_comments"), array('c.*'));
		$select->joinLeft(array("u"=>"users"), "u.user_id = c.user_id", array("u.*"));
		$select->where("c.report_id=?", $bm_report_id);
		$select->order("c.comment_id desc");
		if($qty > 0) $select->limit($qty);
		return $commentsTable->getAdapter()->fetchAll($select);
	}

	
	function deleteCommentsByBmReportId($id)
	{
		$commentTable = new bm_comments(array('db'=>'db3'));
		
		if ( is_numeric($id) && $id > 0 )
		{		
			$where = $commentTable->getAdapter()->quoteInto('report_id = ?', $id);
			$commentTable->delete($where);
		}
	}

	function getTotalCommentsByUser($user_id, $startdate, $enddate) {
		$commentsTable = new bm_comments(array('db'=>'db3'));
		$select = $commentsTable->select();
		$select->from('bm_comments', array('count(comment_id) as total_comment'));
		$select->where('user_id = ?', $user_id);	
		$select->where('site_id = ?', $this->site_id);	
		$select->where('date(comment_date) >= ?', $startdate);
		$select->where('date(comment_date) <= ?', $enddate);
		return $commentsTable->getAdapter()->fetchRow($select);
	}

	function getTotalCommentsBySiteId($site_id, $startdate, $enddate) {
		$commentsTable = new bm_comments(array('db'=>'db3'));
		$select = $commentsTable->select();
		$select->from('bm_comments', array('count(comment_id) as total_comment'));
		$select->where('site_id = ?', $site_id);	
		$select->where('date(comment_date) >= ?', $startdate);
		$select->where('date(comment_date) <= ?', $enddate);
		return $commentsTable->getAdapter()->fetchRow($select);
	}
}
?>