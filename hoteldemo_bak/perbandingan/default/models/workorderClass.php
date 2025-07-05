<?php

require_once('defaultClass.php');
require_once('dbClass.php');

class workorderClass extends defaultClass
{	
	function addworkorder($params) {
		$workorderTable = new work_order(array('db'=>'db'));
		$data = array(
			"site_id" => $this->site_id,
			"issue_id" => $params["issue_id"],
			"start_scheduled_date" => $params["wo_startdate"]." ".$params["wo_starthour"].":".$params["wo_startmin"].":00",
			"end_scheduled_date" => $params["wo_enddate"]." ".$params["wo_endhour"].":".$params["wo_endmin"].":59",
			"expected_work_time" => $params["expected_work_time"],
			"expected_work_time2" => $params["expected_work_time2"],
			"worker" => $params["worker"],
			"assigned_date" => date("Y-m-d H:i:s"),
			"assigned_remark" => addslashes($params["assigned_comment"])
		); 	 	
		
		$workorderTable->insert($data);
		return $this->db->lastInsertId();
	}
	
	function getWOByIssueId($issue_id)
	{
		$workorderTable = new work_order(array('db'=>'db'));
		
		$select = $workorderTable->select()->where("issue_id=?", $issue_id);		
		$rs = $workorderTable->getAdapter()->fetchRow($select);
		return $rs;
	}
	
	function getCurMonthSchedules($m, $y, $user_id)
	{
		$workorderTable = new work_order(array('db'=>'db'));
		
		$select = $workorderTable->select()->where("month(start_scheduled_date) =?", $m)->where("year(start_scheduled_date) = ?", $y);
		
		$select = $workorderTable->getAdapter()->select();
		$select->from(array("wo"=>"work_order"), array("wo.*"));
		$select->joinLeft(array("i"=>"issues"), "i.issue_id=wo.issue_id", array("i.description"));
		$select->where('wo.site_id = ?', $this->site_id);
		$select->where('month(wo.start_scheduled_date) = ?', $m);
		$select->where('year(wo.start_scheduled_date) = ?', $y);	
		if($user_id > 0) $select->where('worker like "%,'.$user_id.',%"');
		$rs = $workorderTable->getAdapter()->fetchAll($select);
		return $rs;
	}
	
	function getWOById($id)
	{
		$workorderTable = new work_order(array('db'=>'db'));
			
		$select = $workorderTable->getAdapter()->select();
		$select->from(array("wo"=>"work_order"), array("wo.*"));
		$select->joinLeft(array("i"=>"issues"), "i.issue_id=wo.issue_id", array("i.description", "i.location"));
		//$select->joinLeft(array("u"=>"users"), "u.user_id=wo.worker", array("u.name"));
		$select->where('wo.wo_id = ?', $id);	
		$rs = $workorderTable->getAdapter()->fetchRow($select);
		return $rs;
	}
	
	function startworkorder($params) {
		$workorderTable = new work_order(array('db'=>'db'));
		$data = array(
			"executed_date" => date("Y-m-d H:i:s"),
		); 	 	
		
		$where = $workorderTable->getAdapter()->quoteInto('wo_id = ?', $params["wo_id"]);
		$workorderTable->update($data, $where);
		return $params["wo_id"];
	}
	
	function addWOAttachment($params)
	{		
		$attachmentTable = new work_order_progress_attachment(array('db'=>'db'));
		
		$data = array(
			'site_id'					=> $this->site_id,
			'user_id'					=> $params['user_id'],
			'wo_id'						=> $params['wo_id'],
			'uploaded_date'				=> date("Y-m-d H:i:s")
		);
		$attachmentTable->insert($data);
		return $this->db->lastInsertId();
	}
	
	function updateWOAttachment($attachment_id,$fieldname, $filename)
	{		
		$attachmentTable = new work_order_progress_attachment(array('db'=>'db'));
			
		if(!empty($attachment_id))
		{
			$data = array(
				$fieldname					=> $filename
			);
			$where = $attachmentTable->getAdapter()->quoteInto('attachment_id = ?', $attachment_id);
			$attachmentTable->update($data, $where);
		}
	}
	
	function getAttachmentByWoId($wo_id)
	{
		$attachmentTable = new work_order_progress_attachment(array('db'=>'db'));
		
		$select = $attachmentTable->select()->where("wo_id=?", $wo_id);		
		$rs = $attachmentTable->getAdapter()->fetchAll($select);
		return $rs;
	}
	
	function getAttachmentById($id)
	{
		$attachmentTable = new work_order_progress_attachment(array('db'=>'db'));
		
		$select = $attachmentTable->select()->where("attachment_id=?", $id);		
		$rs = $attachmentTable->getAdapter()->fetchRow($select);
		return $rs;
	}
	
	function deleteAttachmentById($id)
	{
		$attachmentTable = new work_order_progress_attachment(array('db'=>'db'));
		
		if ( is_numeric($id) && $id > 0 )
		{		
			$where = array();
			$where[] = $attachmentTable->getAdapter()->quoteInto('attachment_id = ?', $id);
			$attachmentTable->delete($where);
		}
	}
	
	function getLastAttachmentByWoId($wo_id)
	{
		$attachmentTable = new work_order_progress_attachment(array('db'=>'db'));
		
		$select = $attachmentTable->select()->where("wo_id=?", $wo_id)->order("attachment_id desc")->limit(1);		
		$rs = $attachmentTable->getAdapter()->fetchRow($select);
		return $rs;
	}
	
	function finishworkorder($params) {
		$workorderTable = new work_order(array('db'=>'db'));
		$data = array(
			"finish_date" => date("Y-m-d H:i:s"),
		); 	 	
		
		$where = $workorderTable->getAdapter()->quoteInto('wo_id = ?', $params["wo_id"]);
		$workorderTable->update($data, $where);
		return $params["id"];
	}
	
	function rejectworkorder($params) {
		$workorderTable = new work_order(array('db'=>'db'));
		$data = array(
			"finish_date" => "0000-00-00 00:00:00",
		); 	 	
		
		$where = $workorderTable->getAdapter()->quoteInto('wo_id = ?', $params["id"]);
		$workorderTable->update($data, $where);
		return $params["id"];
	}
	
	function approveworkorder($params) {
		$workorderTable = new work_order(array('db'=>'db'));
		$data = array(
			"approved_date" => date("Y-m-d H:i:s"),
			"approved" => 1
		); 	 	
		
		$where = $workorderTable->getAdapter()->quoteInto('wo_id = ?', $params["id"]);
		$workorderTable->update($data, $where);
		return $params["id"];
	}
	
	function addComment($params)
	{		
		$commentTable = new work_order_comments(array('db'=>'db'));
		
		$data = array(
			'site_id'					=> $this->site_id,
			'user_id'					=> $params['user_id'],
			'wo_id'						=> $params['id'],
			'comment'					=> $params['comment'],
			'comment_date'				=> date("Y-m-d H:i:s"),
			'status'					=> $params['stat']
		);
		$commentTable->insert($data);
		return $this->db->lastInsertId();
	}
	
	function getCommentsByWoId($wo_id)
	{
		$commentTable = new work_order_comments(array('db'=>'db'));
		
		$select = $commentTable->select()->where("wo_id=?", $wo_id);		
		$rs = $commentTable->getAdapter()->fetchAll($select);
		return $rs;
	}

	function getWorkers()
	{
		$workorderTable = new work_order(array('db'=>'db'));

		$select = $workorderTable->getAdapter()->select();
		$select->from(array("wo"=>"work_order"), array("count(*) as total", "wo.worker"));
		$select->where('wo.approved = ?', '1');
		$select->group("worker");
		$select->order("total desc");
		//$select->where('wo.wo_id = ?', $id);
		$rs = $workorderTable->getAdapter()->fetchAll($select);
		return $rs;
	}
	
	function getProgressImagesByIssueId($issue_id)
	{
			$progressTable = new work_order_progress_attachment(array('db'=>'db'));
			
			$select = $progressTable->getAdapter()->select();
			$select->from(array("p"=>"work_order_progress_attachment"), array("p.filename", "p.uploaded_date"));
			$select->joinLeft(array("wo"=>"work_order"), "wo.wo_id = p.wo_id", array("wo.approved"));
			$select->where('wo.site_id = ?', $this->site_id);
			$select->where('wo.issue_id = ?', $issue_id);
			$rs = $progressTable->getAdapter()->fetchAll($select);
			return $rs;
	}
}
?>