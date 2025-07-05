<?php

require_once('defaultClass.php');
require_once('dbClass.php');

class commentsClass extends defaultClass
{
	function __construct()
	{
		parent::__construct();
	}
	
	function addComment($params) {
		if(empty($params['site_id'])) $params['site_id'] = $this->site_id;
		$commentTable = new comments(array('db'=>'db3'));
		$commentTable->insert(array(
			"issue_id"		=> $params["issue_id"],
			"comment"		=> $params["comment"],
			"user_id"		=> $params["user_id"],
			"site_id"		=> $params['site_id'],
			"comment_date"	=> date("Y-m-d H:i:s"),
			"filename"		=> $params["filename"]
		));
	}
	
	function getCommentsByIssueId($issue_id, $qty=0) {
		$commentsTable = new comments(array('db'=>'db3'));
		$select = $commentsTable->getAdapter()->select();
		$select->from(array("c"=>"comments"), array('c.*'));
		$select->joinLeft(array("u"=>"users"), "u.user_id = c.user_id", array("u.*"));
		$select->where("c.issue_id=?", $issue_id);
		$select->order("c.comment_id desc");
		if($qty > 0) $select->limit($qty);
		return $commentsTable->getAdapter()->fetchAll($select);
	}

	function getTotalCommentsByUser($user_id, $startdate, $enddate) {
		$commentsTable = new comments(array('db'=>'db3'));
		$select = $commentsTable->select();
		$select->from('comments', array('count(comment_id) as total_comment'));
		$select->where('user_id = ?', $user_id);	
		$select->where('site_id = ?', $this->site_id);	
		$select->where('date(comment_date) >= ?', $startdate);
		$select->where('date(comment_date) <= ?', $enddate);
		return $commentsTable->getAdapter()->fetchRow($select);
	}

	function updateSiteIdComment($issue_id, $site_id) {
		$commentsTable = new comments(array('db'=>'db3'));
		$data = array(
			"site_id" => $site_id
		);
		$where = $commentsTable->getAdapter()->quoteInto('issue_id = ?', $issue_id);
		$commentsTable->update($data, $where);
	}

	function getTotalCommentsBySiteId($site_id, $startdate, $enddate) {
		$commentsTable = new comments(array('db'=>'db3'));
		$select = $commentsTable->select();
		$select->from('comments', array('count(comment_id) as total_comment'));
		$select->where('site_id = ?', $site_id);	
		$select->where('date(comment_date) >= ?', $startdate);
		$select->where('date(comment_date) <= ?', $enddate);
		return $commentsTable->getAdapter()->fetchRow($select);
	}

	function getCommentByCommentDate($date) {
		$commentsTable = new comments(array('db'=>'db3'));
		$select = $commentsTable->select()->where("comment_date= ?", $date);
		$comment = $commentsTable->getAdapter()->fetchRow($select);
		return $comment;
	}

	function getStatusByIssueId($issue_id) {
		$commentsTable = new comments(array('db'=>'db3'));
		$select = $commentsTable->select()->where("issue_id= ?", $issue_id)->order("comment_date")->limit(1);
		return $commentsTable->getAdapter()->fetchRow($select);
	}

	function getTotalCommentDailyReportByUser($startdate, $enddate, $user_id) {
		if(!empty($user_id))
		{
			$commentsTable = new comments(array('db'=>'db3'));
			$select = "Select sum(total_comment) as total from (
				select 'chief security', count(*) as total_comment from chief_security_comments where date(comment_date) >= '".$startdate."' and date(comment_date) <= '".$enddate."' and user_id = ".$user_id." union
				select 'spv security', count(*) as total_comment from security_comments where date(comment_date) >= '".$startdate."' and date(comment_date) <= '".$enddate."' and user_id = ".$user_id."
				) as t;
			";
			$total = $commentsTable->getAdapter()->fetchRow($select);
			return $total['total'];
		}
	}

	function getTotalCommentIssueFindingByUser($user_id, $startdate, $enddate) {
		$commentsTable = new comments(array('db'=>'db3'));
		$select = $commentsTable->select();
		$select->from('comments', array('count(*) as total_comment'));
		$select->where('user_id = ?', $user_id);	
		$select->where('date(comment_date) >= ?', $startdate);
		$select->where('date(comment_date) <= ?', $enddate);
		$total = $commentsTable->getAdapter()->fetchRow($select);
		return $total['total_comment'];
	}
	
}
?>