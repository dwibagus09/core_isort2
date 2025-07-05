<?php

require_once('defaultClass.php');
require_once('dbClass.php');

class feedbackClass extends defaultClass
{
	function __construct()
	{
		parent::__construct();
	}
	
	function saveFeedback($params) {
		$feedbackTable = new feedback(array('db'=>'db'));
		
		$data = array(
			"site_id" => $this->site_id,
			"user_id" => $params["user_id"],
			"module" => $params["module_menu"],
			"submodule" => $params["submodule"],
			"suggestion" => $params["suggestion"],
			"send_date" => date("Y-m-d H:i:s")
		);
		$feedbackTable->insert($data);
		return $this->db->lastInsertId();
	}

	function getFeedback($params) {
		$feedbackTable = new feedback(array('db'=>'db'));
		$select = $feedbackTable->getAdapter()->select();
		$select->from(array("f"=>"feedback"), array("f.*"));
		$select->joinLeft(array("u"=>"users"), "u.user_id = f.user_id", array("u.name"));
		$select->joinLeft(array("s"=>"sites"), "s.site_id = f.site_id", array("s.initial"));
		if(!empty($params['site_id']))
		{
			$select->where('f.site_id = ?', $params['site_id']);
		}
		if(!empty($params['module_menu']))
		{
			$select->where('f.module = ?', $params['module_menu']);
		}
		if(!empty($params['submodule']))
		{
			$select->where('f.submodule = ?', $params['submodule']);
		}
		//$select->where('f.site_id = ?', $this->site_id);
		$select->order('f.send_date desc');
		//$select->limit($params['pagesize'],$params['start']);
		$feedback = $feedbackTable->getAdapter()->fetchAll($select);
		
		return $feedback;
	}

	function getFeedbackById($id) {
		$feedbackTable = new feedback(array('db'=>'db'));
		$select = $feedbackTable->getAdapter()->select();
		$select->from(array("f"=>"feedback"), array("f.*"));
		$select->joinLeft(array("u"=>"users"), "u.user_id = f.user_id", array("u.name"));
		$select->joinLeft(array("s"=>"sites"), "s.site_id = f.site_id", array("s.site_fullname"));
		$select->where('f.feedback_id = ?', $id);
		//$select->limit($params['pagesize'],$params['start']);
		$feedback = $feedbackTable->getAdapter()->fetchRow($select);
		return $feedback;
	}

	function updateView($params) {
		$feedbackTable = new feedback(array('db'=>'db'));
		
		$data = array(
			"view" => '1',
			"view_date" => date("Y-m-d H:i:s"),
			"user_view" => $params["user_view"]
		);
		$where = $feedbackTable->getAdapter()->quoteInto('feedback_id = ?', $params['id']);
		$feedbackTable->update($data, $where);
	}
}

?>