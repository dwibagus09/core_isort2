<?php

require_once('defaultClass.php');
require_once('dbClass.php');

class issueClass extends defaultClass
{
	function __construct()
	{
		parent::__construct();
	}
	
	function saveIssue($params) {
		$issuesTable = new issues(array('db'=>'db'));
		
		$data = array(
			"picture" => $params["picture"],
			"location" => $params["location"],
			"description" => $params["description"],
			"user_id" => $params["user_id"],
			"category_id" => $params["category"],
			"issue_type_id" => $params["type_id"],
			"issue_date" => date("Y-m-d H:i:s"),
			"solved" => '0',
			"site_id" => $params["site_id"],
			"kejadian_id" => $params["incident_id"],
			"modus_id" => $params["modus_id"],
			"floor_id" => $params["floor_id"],
			"area_id" => intval($params["area"]),
			"pelaku_tertangkap" => $params["pelaku_tertangkap"],
			"manpower_id" => $params["manpower_id"]
		);
		$issuesTable->insert($data);
		return $this->db->lastInsertId();
	}
	
	function getIssues($params) { 
		$issuesTable = new issues(array('db' => 'db'));
		$select = $issuesTable->getAdapter()->select();
		$select->from(array("i"=>"issues"), array("i.*"));
		$select->joinLeft(array("it"=>"issue_type"), "it.issue_type_id = i.issue_type_id", array("it.issue_type"));
		$select->joinLeft(array("s"=>"sites"), "s.site_id = i.site_id", array("s.site_name"));		
		$select->joinLeft(array("u"=>"users"), "u.user_id = i.user_id", array("name"));
		$select->joinLeft(array("a"=>"area"), "a.area_id = i.area_id", array("area_name"));
		if(!empty($params['category']) && in_array($params['category'], array(1,2,3,5,6,10,11))) 
		{
			if($params['category'] == 1) 
			{
				$kejadian_table = "security_kejadian";
				$modus_table = "security_modus";
				$floor_table = "security_floor";
			}
			if($params['category'] == 2) 
			{
				$kejadian_table = "housekeeping_kejadian";
				$modus_table = "housekeeping_modus";
				$floor_table = "housekeeping_floor";
			}
			if($params['category'] == 3) 
			{
				$kejadian_table = "safety_kejadian";
				$modus_table = "safety_modus";
				$floor_table = "safety_floor";
			}
			if($params['category'] == 5) 
			{
				$kejadian_table = "parking_kejadian";
				$modus_table = "parking_modus";
				$floor_table = "parking_floor";
			}
			if($params['category'] == 6) 
			{
				$kejadian_table = "engineering_kejadian";
				$modus_table = "engineering_modus";
				$floor_table = "engineering_floor";
			}
			
			if($params['category'] == 10) 
			{
				$kejadian_table = "building_service_kejadian";
				$modus_table = "building_service_modus";
				$floor_table = "building_service_floor";
			}
			if($params['category'] == 11) 
			{
				$kejadian_table = "tenant_relation_kejadian";
				$modus_table = "tenant_relation_modus";
				$floor_table = "tenant_relation_floor";
			}
			$select->joinLeft(array("k"=>$kejadian_table), "k.kejadian_id = i.kejadian_id", array("k.kejadian"));
			$select->joinLeft(array("m"=>$modus_table), "m.modus_id = i.modus_id", array("m.modus"));
			$select->joinLeft(array("f"=>$floor_table), "f.floor_id = i.floor_id", array("f.floor"));
			$select->joinLeft(array("mp"=>"manpower"), "mp.manpower_id = i.manpower_id", array("mp.name as manpower_name"));
		}
		$select->where('i.solved = ?', intval($params['solved']));
		if(!empty($params['site_id'])) $select->where('i.site_id = ?', $params['site_id']);
		if(!empty($params['category'])) $select->where('i.category_id = ?', $params['category']);
		if(!empty($params['issue_id'])) $select->where('i.issue_id = ?', $params['issue_id']);
		if(!empty($params['start_date'])) $select->where('date(i.issue_date) >= ?', $params['start_date']);
		if(!empty($params['end_date'])) $select->where('date(i.issue_date) <= ?', $params['end_date']);
		if(empty($params['sort'])) $select->order("issue_id asc");
		else  $select->order("issue_id ".$params['sort']);
		$select->limit($params['pagesize'],$params['start']);
		$issues = $issuesTable->getAdapter()->fetchAll($select);
		return $issues;
	}

	function getIssueIds($params) {
		if(empty($params['site_id'])) $params['site_id'] = $this->site_id;

		$select = "select issue_id from issues where solved = '".intval($params['solved'])."' and site_id = ".$params['site_id'];

		if(!empty($params['category'])) $select .= " and category_id = ".$params['category'];
		if(!empty($params['issue_id'])) $select .= " and issue_id = ". $params['issue_id'];
		if(!empty($params['start_date'])) $select .= " and date(issue_date) >= '".$params['start_date']."'";
		if(!empty($params['end_date'])) $select .= " and date(issue_date) <= '". $params['end_date']."'";
		$select .= " order by issue_id asc";
		$select .= " limit ".$params['start'].",".$params['pagesize'];
		$issues = $this->db->fetchAll($select);

		return $issues;
	}
	
	function getTotalPendingIssues($solved, $site_id, $params) {
		$issuesTable= new issues(array('db'=>'db'));
		$select = "select count(*) as total from issues where solved = '".$solved."' and site_id = ".$site_id;
		if(!empty($params['category'])) $select .= " and category_id = ".$params['category'];
		if(!empty($params['issue_id'])) $select .= " and issue_id = ".$params['issue_id'];
		if(!empty($params['start_date'])) $select .= ' and date(issue_date) >= "'. $params['start_date'].'"';
		if(!empty($params['end_date'])) $select .= ' and date(issue_date) <= "'. $params['end_date'].'"';
		$issues = $issuesTable->getAdapter()->fetchRow($select);
		return $issues;
	}
	
	function getIssueById($id) {		
		$issuesTable = new issues(array('db' => 'db'));
		$select = $issuesTable->getAdapter()->select();
		$select->from(array("i"=>"issues"), array("i.*"));
		$select->joinLeft(array("it"=>"issue_type"), "it.issue_type_id = i.issue_type_id", array("it.issue_type"));
		$select->where("i.issue_id='".$id."'");
		$issues = $issuesTable->getAdapter()->fetchRow($select);
		
		return $issues;
	}
	
	function getSolvedIssues($params) {
		$issuesTable= new issues(array('db'=>'db'));
		$select = $issuesTable->select()->where("solved='1'");
		$select->where('site_id = ?', $this->site_id);
		if(!empty($params['category'])) $select->where('category_id = ?', $params['category']);
		if(!empty($params['issue_id'])) $select->where('issue_id = ?', $params['issue_id']);
		if(!empty($params['start_date'])) $select->where('date(issue_date) >= ?', $params['start_date']);
		if(!empty($params['end_date'])) $select->where('date(issue_date) <= ?', $params['end_date']);
		$select->order("issue_id asc");
		$select->limit($params['pagesize'],$params['start']);
		$issues = $issuesTable->getAdapter()->fetchAll($select);
		return $issues;
	}

	function getSolvedIssueIds($params) {
		$select = "select issue_id from issues where solved = 1 and site_id = ".$this->site_id;

		if(!empty($params['category'])) $select .= " and category_id = ".$params['category'];
		if(!empty($params['issue_id'])) $select .= " and issue_id = ". $params['issue_id'];
		if(!empty($params['start_date'])) $select .= " and date(issue_date) >= '".$params['start_date']."'";
		if(!empty($params['end_date'])) $select .= " and date(issue_date) <= '". $params['end_date']."'";
		$select .= " order by issue_id asc";
		$select .= " limit ".$params['start'].",".$params['pagesize'];
		$issues = $this->db->fetchAll($select);

		return $issues;
	}
	
	function saveSolveIssue($params) {
		$issuesTable = new issues(array('db'=>'db'));
		
		$data = array(
			"solved" => '1',
			"solved_picture" => $params["picture"],
			"solved_date" => date("Y-m-d H:i:s")
		);
		$where = $issuesTable->getAdapter()->quoteInto('issue_id = ?', $params['issue_id']);
		$issuesTable->update($data, $where);	
		return $params['issue_id'];
	}
	
	function getIssueByTypeId($id, $cat_id, $date, $site_id) {
		$issuesTable= new issues(array('db'=>'db'));
		$select = $issuesTable->select()->where("issue_type_id='".$id."'")->where("category_id='".$cat_id."'")->where('site_id = ?', $site_id)->where('date(issue_date) = ?', $date);
		$issues = $issuesTable->getAdapter()->fetchAll($select);
		return $issues;
	}
	
	function getIssuesByTypeShift($type_id, $cat_id, $site_id, $startdate, $enddate) {
	    
		$issuesTable= new issues(array('db'=>'db'));
		/*$select = $issuesTable->select()->where("issue_type_id='".$type_id."'")->where("category_id='".$cat_id."'")->where('site_id = ?', $site_id)->where('issue_date > ?', $startdate)->where('issue_date <= ?', $enddate);
		$issues = $issuesTable->getAdapter()->fetchAll($select);*/
		
		$select = $issuesTable->getAdapter()->select();
		$select->from(array("i"=>"issues"), array("i.*"));
		if($type_id == 4) // defect list
		    $select->joinLeft(array("sdl"=>"security_defect_list"), "sdl.issue_id = i.issue_id", array("sdl.sdl_id", "sdl.follow_up"));
		if($type_id == 2) // glitch
		    $select->joinLeft(array("sg"=>"security_glitch"), "sg.issue_id = i.issue_id", array("sg.glitch_id", "sg.status"));
		if($type_id == 3) // lost & found
		    $select->joinLeft(array("slf"=>"security_lost_found"), "slf.issue_id = i.issue_id", array("slf.lost_found_id", "slf.status"));
		if($type_id == 1) // incident report
		    $select->joinLeft(array("si"=>"security_incident"), "si.issue_id = i.issue_id", array("si.incident_id", "si.status"));
		   
		$select->where("i.issue_type_id = ?", $type_id);
		$select->where("i.category_id = ?", $cat_id);
		$select->where('i.site_id = ?', $site_id);
		$select->where('i.issue_date > ?', $startdate);
		$select->where('i.issue_date <= ?', $enddate);
		$issues = $issuesTable->getAdapter()->fetchAll($select);
		
		return $issues;
	}
	
	function getIssuesByShift($cat_id, $site_id, $startdate, $enddate) {
	    
		$issuesTable= new issues(array('db'=>'db'));
		$select = $issuesTable->select()->where("category_id='".$cat_id."'")->where('site_id = ?', $site_id)->where('issue_date > ?', $startdate)->where('issue_date <= ?', $enddate);
		$issues = $issuesTable->getAdapter()->fetchAll($select);
		return $issues;
	}

	function getIssuesByMonthCatType($month, $year, $cat_id, $type_id) {
		$issuesTable= new issues(array('db'=>'db'));
		$select = $issuesTable->select()->where("issue_type_id = ?", $type_id)->where("category_id = ?", $cat_id)->where('site_id = ?', $this->site_id)->where('month(issue_date) = ?', $month)->where('year(issue_date) = ?', $year);
		$issues = $issuesTable->getAdapter()->fetchAll($select);
		return $issues;
	}
	
	function getIssuesByIds($table, $ids) {
		$select = "select t.*, i.*, it.issue_type as issue_type_name from ".$table." t 
			left join issues i on i.issue_id = t.issue_id 
			left join issue_type it on it.issue_type_id = i.issue_type_id 
			where security_id in (".$ids.")";
		$issues = $this->db->fetchAll($select);
		return $issues;
	}
	
	function getDefectListByIds($ids) {
		$select = "select * from security_defect_list where security_id in (".$ids.")";
		$issues = $this->db->fetchAll($select);
		return $issues;
	}
	
	function getIssueByCategoryDateAndCatId($date, $id) {		
		$issuesTable = new issues(array('db' => 'db'));
		$select = $issuesTable->getAdapter()->select();
		$select->from(array("i"=>"issues"), array("i.*"));
		$select->joinLeft(array("it"=>"issue_type"), "it.issue_type_id = i.issue_type_id", array("it.issue_type"));
		$select->where("date(i.issue_date)='".$date."'");
		$select->where("i.category_id='".$id."'");
		$select->where("i.site_id='".$this->site_id."'");
		$issues = $issuesTable->getAdapter()->fetchAll($select);
		
		return $issues;
	}
	
	function updateSolvedDate($issue_id, $date, $report_id, $fieldName) {
		$issuesTable = new issues(array('db'=>'db'));
		
		$data = array(
			"solved_date" => $date,
			$fieldName => $report_id
		);
		$where = $issuesTable->getAdapter()->quoteInto('issue_id = ?', $issue_id);
		$issuesTable->update($data, $where);	
	}
	

	/* munculin issue yang blm solve tapi di disable, yg uda solve hari ini, dan yg uda pernah di save */
	function getUnsolvedIssueByCategoryId($om_report_id, $id) {		
		$issuesTable = new issues(array('db' => 'db'));
		$select = $issuesTable->getAdapter()->select();
		$select->from(array("i"=>"issues"), array("i.*"));
		$select->joinLeft(array("it"=>"issue_type"), "it.issue_type_id = i.issue_type_id", array("it.issue_type"));
		$select->where("date(i.solved_date) = '".date("Y-m-d")."' OR i.solved = 0");
		$select->where("i.category_id='".$id."'");
		$select->where("i.site_id='".$this->site_id."'");
		if(!empty($om_report_id	))	$select->where("i.om_report_id ='".$om_report_id."' or i.om_report_id is NULL or i.om_report_id = 0");
		else $select->where("i.om_report_id is NULL OR i.om_report_id = 0");
		$select->where("i.mod_report_id is NULL or i.mod_report_id = 0");
		$issues = $issuesTable->getAdapter()->fetchAll($select);
		
		return $issues;
	}

	/* munculin issue yang blm solve tapi di disable, yg uda solve hari ini, dan yg uda pernah di save */
	function getUnsolvedIssueByCategoryId2($mod_report_id, $id) {		
		$issuesTable = new issues(array('db' => 'db'));
		$select = $issuesTable->getAdapter()->select();
		$select->from(array("i"=>"issues"), array("i.*"));
		$select->joinLeft(array("it"=>"issue_type"), "it.issue_type_id = i.issue_type_id", array("it.issue_type"));
		$select->where("date(i.solved_date) = '".date("Y-m-d")."' OR i.solved = 0");
		$select->where("i.category_id='".$id."'");
		$select->where("i.site_id='".$this->site_id."'");
		if(!empty($mod_report_id))	$select->where("i.mod_report_id is NULL OR i.mod_report_id = 0 OR i.mod_report_id ='".$mod_report_id."'");
		else $select->where("i.mod_report_id is NULL OR i.mod_report_id = 0");
		$select->where("i.om_report_id is null or i.om_report_id = 0");
		$issues = $issuesTable->getAdapter()->fetchAll($select);
		return $issues;
	}
	
	function getIssueByReportAndCatId($fieldName, $report_id, $id, $report_date) {		
		$issuesTable = new issues(array('db' => 'db'));
		$select = $issuesTable->getAdapter()->select();
		$select->from(array("i"=>"issues"), array("i.*"));
		$select->joinLeft(array("it"=>"issue_type"), "it.issue_type_id = i.issue_type_id", array("it.issue_type"));
		$select->where("i.category_id='".$id."'");
		$select->where("i.".$fieldName." ='".$report_id."' OR date(i.solved_date) = '".$report_date."'");
		$select->where("i.site_id='".$this->site_id."'");
		$issues = $issuesTable->getAdapter()->fetchAll($select);
		return $issues;
	}
	
	function getIssueByDateAndType($date, $type) {		
		$issuesTable = new issues(array('db' => 'db'));
		$select = $issuesTable->getAdapter()->select();
		$select->from(array("i"=>"issues"), array("i.*"));
		$select->joinLeft(array("it"=>"issue_type"), "it.issue_type_id = i.issue_type_id", array("it.issue_type"));
		//$select->joinLeft(array("c"=>"comments"), "c.comment_date = i.solved_date", array("c.comment"));
		$select->where("date(i.issue_date)='".$date."'");
		$select->where("it.issue_type_id ='".$type."'");
		$select->where("i.site_id='".$this->site_id."'");
		$issues = $issuesTable->getAdapter()->fetchAll($select);
		
		return $issues;
	}
	
	function updateIssue($issue_id, $data) {
		$issuesTable = new issues(array('db'=>'db'));
		
		$where = $issuesTable->getAdapter()->quoteInto('issue_id = ?', $issue_id);
		$issuesTable->update($data, $where);	
	}
	
	function saveProgressImage($params) {
		$issuesTable = new issue_progress_images(array('db'=>'db'));
		
		$data = array(
			"issue_id" => $params["progress_issue_id"],
			"filename" => $params["picture"],
			"user_id" => $params["user_id"],
			"upload_date" => date("Y-m-d H:i:s")
		);
		$issuesTable->insert($data);
		return $this->db->lastInsertId();
	}
	
	function getProgressImages($issue_id) {
		$issuesTable = new issue_progress_images(array('db'=>'db'));
		$select = $issuesTable->select()->where("issue_id= ?", $issue_id);
		$issues = $issuesTable->getAdapter()->fetchAll($select);
		return $issues;
	}

	function getTotalIssues($params, $type = 0, $open = 0, $close = 0) {
		$issuesTable= new issues(array('db'=>'db'));
		if(!empty($params['site_id'])) $site_id = $params['site_id'];
		else $site_id = $this->site_id;
		$select = "select count(*) as total from issues where site_id = ".$site_id;
		$select .= " and date(issue_date) >= '".$params['start_date']."' and date(issue_date) <= '".$params['end_date']."'";
		if($type > 0) $select .= " and issue_type_id =".$type;
		if($open > 0) $select .= " and solved = 0";
		if($close > 0) $select .= " and solved = 1";
		if($params['category_id'] > 0)	$select .= " and category_id = ".$params['category_id'];
		$issues = $issuesTable->getAdapter()->fetchRow($select);
		return $issues;
	}

	function getAllIssues() {
		$issuesTable = new issues(array('db' => 'db'));
		$select = $issuesTable->select();
		$issues = $issuesTable->getAdapter()->fetchAll($select);
		return $issues;
	}
	
	function getUserIssuesStatistic($startdate, $enddate, $limit = 10) {
		$issuesTable= new issues(array('db'=>'db'));
		
		$select = $issuesTable->getAdapter()->select();
		$select->from(array("i"=>"issues"), array("i.issue_id", "i.site_id", "i.user_id", "count(i.issue_id) as total_issues"));
		$select->joinLeft(array("u"=>"users"), "u.user_id = i.user_id", array("u.name", "u.role_id"));
		$select->where('i.site_id = ?', $this->site_id);
		$select->where('date(i.issue_date) >= ?', $startdate);
		$select->where('date(i.issue_date) <= ?', $enddate);
		/*$select->where('role_id <> ?', 1);
		$select->where('role_id <> ?', 6);*/
		$select->where('u.user_id > ?', 2);
		$select->group('i.user_id');
		$select->order('total_issues desc');
		if($limit > 0) $select->limit($limit);
		return $issuesTable->getAdapter()->fetchAll($select);
	}

	function getOMOpenedIssues($report_date, $category_id)
	{
		$issuesTable= new issues(array('db'=>'db'));

		$select = $issuesTable->select();
		$select->where('site_id = ?', $this->site_id);
		$select->where('category_id = ?', $category_id);
		$select->where('solved_date is null or date(solved_date) = "0000-00-00" or date(solved_date) = "'.$report_date.'"');
		$select->order("solved_date desc");
		$issues = $issuesTable->getAdapter()->fetchAll($select);
		return $issues;
	}

	function getOMClosedIssues($report_date, $category_id)
	{
		$issuesTable= new issues(array('db'=>'db'));

		$select = $issuesTable->select();
		$select->where('site_id = ?', $this->site_id);
		$select->where('category_id = ?', $category_id);
		$select->where('date(solved_date) = ?', $report_date);
		$select->order("solved_date desc");
		$issues = $issuesTable->getAdapter()->fetchAll($select);
		return $issues;
	}

	function getMODOpenedIssues($report_date, $category_id)
	{
		$issuesTable= new issues(array('db'=>'db'));

		$select = $issuesTable->select();
		$select->where('site_id = ?', $this->site_id);
		$select->where('category_id = ?', $category_id);
		$select->where('(date(issue_date) = "'.$report_date.'" and solved <> 1) or date(solved_date) = "'.$report_date.'"');
		$select->order("solved_date desc");
		$issues = $issuesTable->getAdapter()->fetchAll($select);
		return $issues;
	}

	function getMODClosedIssues($report_date, $category_id)
	{
		$issuesTable= new issues(array('db'=>'db'));

		$select = $issuesTable->select();
		$select->where('site_id = ?', $this->site_id);
		$select->where('category_id = ?', $category_id);
		$select->where('date(solved_date) = ?', $report_date);
		$select->order("solved_date desc");
		$issues = $issuesTable->getAdapter()->fetchAll($select);
		return $issues;
	}

	function getSecurityIssueSummary($month, $year, $monthly_analysis_id = 0) {		
		$issuesTable = new issues(array('db' => 'db'));
		$select = $issuesTable->getAdapter()->select();
		$select->from(array("i"=>"issues"), array("i.kejadian_id","count(i.issue_id) as total_kejadian"));
		$select->joinLeft(array("k"=>"security_kejadian"), "k.kejadian_id = i.kejadian_id", array("k.kejadian"));
		/*$select->joinLeft(array("m"=>"security_monthly_analysis"), "month(m.save_date) = '".$month."' and year(m.save_date) = '".$year."'", array());*/
		if(!empty($monthly_analysis_id)) $select->joinLeft(array("ma"=>"security_monthly_analysis_summary"), "ma.kejadian_id = k.kejadian_id AND ma.monthly_analysis_id = {$monthly_analysis_id} ", array("ma.summary_id", "ma.analisa", "ma.tindakan"));
		$select->where('month(i.issue_date) = ?', $month);
		$select->where('year(i.issue_date) = ?', $year);
		$select->where('i.site_id = ?', $this->site_id);
		$select->where('i.category_id = ?', '1');
		$select->where('i.kejadian_id > ?', 0);
		$select->where('i.modus_id > ?', 0);
		//if(!empty($monthly_analysis_id)) $select->where('ma.monthly_analysis_id = ?', $monthly_analysis_id);
		$select->group('i.kejadian_id');
		$select->order('total_kejadian desc');
		$issues = $issuesTable->getAdapter()->fetchAll($select);
		return $issues;
	}

	function getSafetyIssueSummary($month, $year, $monthly_analysis_id = 0) {		
		$issuesTable = new issues(array('db' => 'db'));
		$select = $issuesTable->getAdapter()->select();
		$select->from(array("i"=>"issues"), array("i.kejadian_id","count(i.issue_id) as total_kejadian"));
		$select->joinLeft(array("k"=>"safety_kejadian"), "k.kejadian_id = i.kejadian_id", array("k.kejadian"));
		if(!empty($monthly_analysis_id)) $select->joinLeft(array("ma"=>"safety_monthly_analysis_summary"), "ma.kejadian_id = k.kejadian_id AND ma.monthly_analysis_id = {$monthly_analysis_id}", array("ma.summary_id", "ma.analisa", "ma.tindakan", "ma.rekomendasi"));
		$select->where('month(i.issue_date) = ?', $month);
		$select->where('year(i.issue_date) = ?', $year);
		$select->where('i.site_id = ?', $this->site_id);
		$select->where('i.category_id = ?', '3');
		$select->where('i.kejadian_id > ?', 0);
		$select->where('i.modus_id > ?', 0);
		//if(!empty($monthly_analysis_id)) $select->where('ma.monthly_analysis_id = ?', $monthly_analysis_id);
		$select->group('i.kejadian_id');
		$select->order('total_kejadian desc');
		$issues = $issuesTable->getAdapter()->fetchAll($select);

		return $issues;
	}

	function checkSafetyIssueSummaryExist($monthly_analysis_id) {		
		$issuesTable = new issues(array('db' => 'db'));
		$select = $issuesTable->getAdapter()->select();
		$select->from(array("ma"=>"safety_monthly_analysis_summary"), array("count(*) as total"));
		$select->where('ma.monthly_analysis_id = ?', $monthly_analysis_id);
		$issues = $issuesTable->getAdapter()->fetchRow($select);

		return $issues['total'];
	}

	function getParkingIssueSummary($month, $year, $monthly_analysis_id = 0) {		
		$issuesTable = new issues(array('db' => 'db'));
		$select = $issuesTable->getAdapter()->select();
		$select->from(array("i"=>"issues"), array("i.kejadian_id","count(i.issue_id) as total_kejadian"));
		$select->joinLeft(array("k"=>"parking_kejadian"), "k.kejadian_id = i.kejadian_id", array("k.kejadian"));
		//$select->joinLeft(array("m"=>"safety_monthly_analysis"), "month(m.save_date) = '".$month."' and year(m.save_date) = '".$year."'", array());
		if(!empty($monthly_analysis_id)) $select->joinLeft(array("ma"=>"parking_monthly_analysis_summary"), "ma.kejadian_id = k.kejadian_id  AND ma.monthly_analysis_id = {$monthly_analysis_id}", array("ma.summary_id", "ma.analisa", "ma.tindakan"));
		$select->where('month(i.issue_date) = ?', $month);
		$select->where('year(i.issue_date) = ?', $year);
		$select->where('i.site_id = ?', $this->site_id);
		$select->where('i.category_id = ?', '5');
		$select->where('i.kejadian_id > ?', 0);
		$select->where('i.modus_id > ?', 0);
		//if(!empty($monthly_analysis_id)) $select->where('ma.monthly_analysis_id = ?', $monthly_analysis_id);
		$select->group('i.kejadian_id');
		$select->order('total_kejadian desc');
		$issues = $issuesTable->getAdapter()->fetchAll($select);

		return $issues;
	}

	function getHousekeepingIssueSummary($month, $year, $monthly_analysis_id = 0) {		
		$issuesTable = new issues(array('db' => 'db'));
		$select = $issuesTable->getAdapter()->select();
		$select->from(array("i"=>"issues"), array("i.kejadian_id","count(i.issue_id) as total_kejadian"));
		$select->joinLeft(array("k"=>"housekeeping_kejadian"), "k.kejadian_id = i.kejadian_id", array("k.kejadian"));
		/*$select->joinLeft(array("m"=>"security_monthly_analysis"), "month(m.save_date) = '".$month."' and year(m.save_date) = '".$year."'", array());*/
		if(!empty($monthly_analysis_id)) $select->joinLeft(array("ma"=>"housekeeping_monthly_analysis_summary"), "ma.kejadian_id = k.kejadian_id AND ma.monthly_analysis_id = {$monthly_analysis_id} ", array("ma.summary_id", "ma.analisa", "ma.tindakan"));
		$select->where('month(i.issue_date) = ?', $month);
		$select->where('year(i.issue_date) = ?', $year);
		$select->where('i.site_id = ?', $this->site_id);
		$select->where('i.category_id = ?', '2');
		$select->where('i.kejadian_id > ?', 0);
		$select->where('i.modus_id > ?', 0);
		//if(!empty($monthly_analysis_id)) $select->where('ma.monthly_analysis_id = ?', $monthly_analysis_id);
		$select->group('i.kejadian_id');
		$select->order('total_kejadian desc');
		$issues = $issuesTable->getAdapter()->fetchAll($select);
		return $issues;
	}
	
	function getEngineeringIssueSummary($month, $year, $monthly_analysis_id = 0) {		
		$issuesTable = new issues(array('db' => 'db'));
		$select = $issuesTable->getAdapter()->select();
		$select->from(array("i"=>"issues"), array("i.kejadian_id","count(i.issue_id) as total_kejadian"));
		$select->joinLeft(array("k"=>"engineering_kejadian"), "k.kejadian_id = i.kejadian_id", array("k.kejadian"));
		/*$select->joinLeft(array("m"=>"security_monthly_analysis"), "month(m.save_date) = '".$month."' and year(m.save_date) = '".$year."'", array());*/
		if(!empty($monthly_analysis_id)) $select->joinLeft(array("ma"=>"engineering_monthly_analysis_summary"), "ma.kejadian_id = k.kejadian_id AND ma.monthly_analysis_id = {$monthly_analysis_id} ", array("ma.summary_id", "ma.analisa", "ma.tindakan"));
		$select->where('month(i.issue_date) = ?', $month);
		$select->where('year(i.issue_date) = ?', $year);
		$select->where('i.site_id = ?', $this->site_id);
		$select->where('i.category_id = ?', '6');
		$select->where('i.kejadian_id > ?', 0);
		$select->where('i.modus_id > ?', 0);
		//if(!empty($monthly_analysis_id)) $select->where('ma.monthly_analysis_id = ?', $monthly_analysis_id);
		$select->group('i.kejadian_id');
		$select->order('total_kejadian desc');
		$issues = $issuesTable->getAdapter()->fetchAll($select);
		return $issues;
	}
	
	function getBuildingServiceIssueSummary($month, $year, $monthly_analysis_id = 0) {		
		$issuesTable = new issues(array('db' => 'db'));
		$select = $issuesTable->getAdapter()->select();
		$select->from(array("i"=>"issues"), array("i.kejadian_id","count(i.issue_id) as total_kejadian"));
		$select->joinLeft(array("k"=>"building_service_kejadian"), "k.kejadian_id = i.kejadian_id", array("k.kejadian"));
		/*$select->joinLeft(array("m"=>"security_monthly_analysis"), "month(m.save_date) = '".$month."' and year(m.save_date) = '".$year."'", array());*/
		if(!empty($monthly_analysis_id)) $select->joinLeft(array("ma"=>"building_service_monthly_analysis_summary"), "ma.kejadian_id = k.kejadian_id AND ma.monthly_analysis_id = {$monthly_analysis_id} ", array("ma.summary_id", "ma.analisa", "ma.tindakan"));
		$select->where('month(i.issue_date) = ?', $month);
		$select->where('year(i.issue_date) = ?', $year);
		$select->where('i.site_id = ?', $this->site_id);
		$select->where('i.category_id = ?', '10');
		$select->where('i.kejadian_id > ?', 0);
		$select->where('i.modus_id > ?', 0);
		$select->group('i.kejadian_id');
		$select->order('total_kejadian desc');
		$issues = $issuesTable->getAdapter()->fetchAll($select);
		return $issues;
	}
	
	function getTenantRelationIssueSummary($month, $year, $monthly_analysis_id = 0) {		
		$issuesTable = new issues(array('db' => 'db'));
		$select = $issuesTable->getAdapter()->select();
		$select->from(array("i"=>"issues"), array("i.kejadian_id","count(i.issue_id) as total_kejadian"));
		$select->joinLeft(array("k"=>"tenant_relation_kejadian"), "k.kejadian_id = i.kejadian_id", array("k.kejadian"));
		if(!empty($monthly_analysis_id)) $select->joinLeft(array("ma"=>"tenant_relation_monthly_analysis_summary"), "ma.kejadian_id = k.kejadian_id AND ma.monthly_analysis_id = {$monthly_analysis_id} ", array("ma.summary_id", "ma.analisa", "ma.tindakan"));
		$select->where('month(i.issue_date) = ?', $month);
		$select->where('year(i.issue_date) = ?', $year);
		$select->where('i.site_id = ?', $this->site_id);
		$select->where('i.category_id = ?', '11');
		$select->where('i.kejadian_id > ?', 0);
		$select->where('i.modus_id > ?', 0);
		$select->group('i.kejadian_id');
		$select->order('total_kejadian desc');
		$issues = $issuesTable->getAdapter()->fetchAll($select);
		return $issues;
	}
	
	function getIssueSummary($month, $year, $category_id, $monthly_analysis_id = 0) {		
		switch($category_id)
		{
			case 1: $kejadian_table = "security_kejadian";
					$modus_table = "security_modus";
					$mas_table = "security_monthly_analysis_summary";
					break;
					
			case 2: $kejadian_table = "housekeeping_kejadian";
					$modus_table = "housekeeping_modus";
					$mas_table = "housekeeping_monthly_analysis_summary";
					break;

			case 3: $kejadian_table = "safety_kejadian";
					$modus_table = "safety_modus";
					$mas_table = "safety_monthly_analysis_summary";
					break;
					
			case 5: $kejadian_table = "parking_kejadian";
					$modus_table = "parking_modus";
					$mas_table = "parking_monthly_analysis_summary";
					break;
					
			case 6: $kejadian_table = "engineering_kejadian";
					$modus_table = "engineering_modus";
					$mas_table = "engineering_monthly_analysis_summary";
					break;
					
			case 10: $kejadian_table = "building_service_kejadian";
					 $modus_table = "building_service_modus";
					 $mas_table = "building_service_monthly_analysis_summary";
					 break;
					
			case 11: $kejadian_table = "tenant_relation_kejadian";
					 $modus_table = "tenant_relation_modus";
					 $mas_table = "tenant_relation_monthly_analysis_summary";
					 break;
		}
	
		$issuesTable = new issues(array('db' => 'db'));
		$select = $issuesTable->getAdapter()->select();
		$select->from(array("i"=>"issues"), array("i.kejadian_id","count(i.issue_id) as total_kejadian"));
		$select->joinLeft(array("k"=>$kejadian_table), "k.kejadian_id = i.kejadian_id", array("k.kejadian"));
		if(!empty($monthly_analysis_id)) $select->joinLeft(array("ma"=>$mas_table), "ma.kejadian_id = k.kejadian_id AND ma.monthly_analysis_id = {$monthly_analysis_id} ", array("ma.summary_id", "ma.analisa", "ma.tindakan"));
		$select->where('month(i.issue_date) = ?', $month);
		$select->where('year(i.issue_date) = ?', $year);
		$select->where('i.site_id = ?', $this->site_id);
		$select->where('i.category_id = ?', $category_id);
		$select->where('i.kejadian_id > ?', 0);
		$select->where('i.modus_id > ?', 0);
		$select->group('i.kejadian_id');
		$select->order('total_kejadian desc');
		$issues = $issuesTable->getAdapter()->fetchAll($select);
		return $issues;
	}
	
	function getGcIssueSummary($month, $year, $category_id, $monthly_analysis_id = 0, $issue_type_id = 0) {	
		switch($category_id)
		{
			case 1: $kejadian_table = "security_kejadian";
					$modus_table = "security_modus";
					break;
					
			case 2: $kejadian_table = "housekeeping_kejadian";
					$modus_table = "housekeeping_modus";
					break;

			case 3: $kejadian_table = "safety_kejadian";
					$modus_table = "safety_modus";
					break;
					
			case 5: $kejadian_table = "parking_kejadian";
					$modus_table = "parking_modus";
					break;
					
			case 6: $kejadian_table = "engineering_kejadian";
					$modus_table = "engineering_modus";
					break;
					
			case 10: $kejadian_table = "building_service_kejadian";
					 $modus_table = "building_service_modus";
					 break;
					
			case 11: $kejadian_table = "tenant_relation_kejadian";
					 $modus_table = "tenant_relation_modus";
					 break;
		}
	
		$issuesTable = new issues(array('db' => 'db'));
		$select = $issuesTable->getAdapter()->select();
		$select->from(array("i"=>"issues"), array("i.modus_id","count(i.issue_id) as total_modus", "i.category_id"));
		$select->joinLeft(array("k"=>$kejadian_table), "k.kejadian_id = i.kejadian_id", array());
		$select->joinLeft(array("m"=>$modus_table), "m.modus_id = i.modus_id", array("m.modus"));
		if(!empty($monthly_analysis_id)) $select->joinLeft(array("ma"=>"gc_monthly_analysis_summary"), "ma.modus_id = m.modus_id AND ma.monthly_analysis_id = {$monthly_analysis_id} ", array("ma.summary_id", "ma.analisa", "ma.tindakan"));
		$select->where('month(i.issue_date) = ?', $month);
		$select->where('year(i.issue_date) = ?', $year);
		$select->where('i.site_id = ?', $this->site_id);
		$select->where('i.category_id = ?', $category_id);
		$select->where('i.kejadian_id > ?', 0);
		$select->where('i.modus_id > ?', 0);
		if(!empty($issue_type_id)) $select->where('i.issue_type_id = ?', $issue_type_id);
		$select->group('i.modus_id');
		$select->order('total_modus desc');
		$issues = $issuesTable->getAdapter()->fetchAll($select);
		return $issues;
	}

	function getIssuesByModus($month, $year, $category_id, $issue_type_id = 0) {		
		$issuesTable = new issues(array('db' => 'db'));
		$select = $issuesTable->getAdapter()->select();
		$select->from(array("i"=>"issues"), array("i.modus_id","count(i.issue_id) as total_modus"));
		$select->where('month(i.issue_date) = ?', $month);
		$select->where('year(i.issue_date) = ?', $year);
		$select->where('i.site_id = ?', $this->site_id);
		$select->where('i.category_id = ?', $category_id);
		if(!empty($issue_type_id)) $select->where('i.issue_type_id = ?', $issue_type_id);
		$select->where('i.modus_id > ?', 0);
		$select->group('i.modus_id');
		$issues = $issuesTable->getAdapter()->fetchAll($select);

		return $issues;
	}

	function getIssuesByModusId($modus_id, $month, $year, $category_id) {		
		if($category_id == 1) 
		{
			$floor_table = "security_floor";
		}
		if($category_id == 2) 
		{
			$floor_table = "housekeeping_floor";
		}
		if($category_id == 3) 
		{
			$floor_table = "safety_floor";
		}
		if($category_id == 5) 
		{
			$floor_table = "parking_floor";
		}
		if($category_id == 6) 
		{
			$floor_table = "engineering_floor";
		}
		if($category_id == 10) 
		{
			$floor_table = "building_service_floor";
		}
		if($category_id == 11) 
		{
			$floor_table = "tenant_relation_floor";
		}
		
		$issuesTable = new issues(array('db' => 'db'));
		$select = $issuesTable->getAdapter()->select();
		$select->from(array("i"=>"issues"), array("i.*"));
		$select->joinLeft(array("f"=>$floor_table), "f.floor_id = i.floor_id", array("f.floor"));
		$select->where('month(i.issue_date) = ?', $month);
		$select->where('year(i.issue_date) = ?', $year);
		$select->where('i.site_id = ?', $this->site_id);
		$select->where('i.modus_id = ?', $modus_id);
		$select->where('i.category_id = ?', $category_id);
		$select->order('i.issue_date');
		
		$issues = $issuesTable->getAdapter()->fetchAll($select);
		return $issues;
	}

	function getMonthlyIssuesByDay($kejadian_id, $month, $year, $category_id) {		
		$issuesTable = new issues(array('db' => 'db'));
		$select = $issuesTable->getAdapter()->select();
		$select->from(array("i"=>"issues"), array("i.issue_id","i.kejadian_id","count(issue_id) as total","dayofweek(issue_date) as day"));
		$select->where('month(i.issue_date) = ?', $month);
		$select->where('year(i.issue_date) = ?', $year);
		$select->where('i.site_id = ?', $this->site_id);
		$select->where('i.kejadian_id = ?', $kejadian_id);
		$select->where('i.category_id = ?', $category_id);
		$select->group('DAYOFWEEK(i.issue_date)');
		$issues = $issuesTable->getAdapter()->fetchAll($select);

		return $issues;
	}

	function getTotalIssuesByDayDescending($month, $year, $category_id, $issue_type_id = 0) {		
		$issuesTable = new issues(array('db' => 'db'));
		$select = $issuesTable->getAdapter()->select();
		$select->from(array("i"=>"issues"), array("dayofweek(issue_date) as day", "count(issue_id) as total"));
		$select->where('month(i.issue_date) = ?', $month);
		$select->where('year(i.issue_date) = ?', $year);
		$select->where('i.site_id = ?', $this->site_id);
		$select->where('i.category_id = ?', $category_id);
		$select->where('i.kejadian_id > ?', 0);
		$select->where('i.modus_id > ?', 0);
		if(!empty($issue_type_id)) $select->where('i.issue_type_id = ?', $issue_type_id);
		$select->group('DAYOFWEEK(i.issue_date)');
		$select->order('total desc');
		$issues = $issuesTable->getAdapter()->fetchAll($select);
		return $issues;
	}

	function getTotalIssuesByTimePeriode($month, $year, $start_time, $end_time, $category_id, $issue_type_id = 0) {		
		$issuesTable = new issues(array('db' => 'db'));
		$select = $issuesTable->getAdapter()->select();
		$select->from(array("i"=>"issues"), array("count(issue_id) as total"));
		$select->where('month(i.issue_date) = ?', $month);
		$select->where('year(i.issue_date) = ?', $year);
		$select->where('i.site_id = ?', $this->site_id);
		$select->where('i.category_id = ?', $category_id);
		$select->where('i.kejadian_id > ?', 0);
		$select->where('i.modus_id > ?', 0);
		if(!empty($issue_type_id)) $select->where('i.issue_type_id = ?', $issue_type_id);
		if($start_time == "23:00:00" && $end_time == "09:00:00")
		{
			$select->where('time(issue_date) > "'. $start_time . '" OR time(issue_date) <= "'. $end_time.'"');
		}
		else{
			$select->where('time(issue_date) > ?', $start_time);
			$select->where('time(issue_date) <= ?', $end_time);
		}
		$issues = $issuesTable->getAdapter()->fetchRow($select);

		return $issues['total'];
	}

	function getMonthlyIssuesByTime($kejadian_id, $month, $year, $start_time, $end_time, $category_id) {		
		$issuesTable = new issues(array('db' => 'db'));
		$select = $issuesTable->getAdapter()->select();
		$select->from(array("i"=>"issues"), array("count(issue_id) as total"));
		$select->where('month(i.issue_date) = ?', $month);
		$select->where('year(i.issue_date) = ?', $year);
		$select->where('i.site_id = ?', $this->site_id);
		$select->where('i.kejadian_id = ?', $kejadian_id);
		$select->where('i.category_id = ?', $category_id);
		if($start_time == "23:00:00" && $end_time == "09:00:00")
		{
			$select->where('time(issue_date) > "'. $start_time . '" OR time(issue_date) <= "'. $end_time.'"');
		}
		else{
			$select->where('time(issue_date) > ?', $start_time);
			$select->where('time(issue_date) <= ?', $end_time);
		}
		$issues = $issuesTable->getAdapter()->fetchRow($select);

		return $issues['total'];
	}

	function getTotalIssuesByTenantPublik($month, $year, $tenant_umum, $category_id) {	
 
		if($category_id == 1) 
		{
			$floor_table = "security_floor";
		}
		if($category_id == 2) 
		{
			$floor_table = "housekeeping_floor";
		}
		if($category_id == 3) 
		{
			$floor_table = "safety_floor";
		}
		if($category_id == 5) 
		{
			$floor_table = "parking_floor";
		}
		if($category_id == 6) 
		{
			$floor_table = "engineering_floor";
		}
		if($category_id == 10) 
		{
			$floor_table = "building_service_floor";
		}
		if($category_id == 11) 
		{
			$floor_table = "tenant_relation_floor";
		}
		$issuesTable = new issues(array('db' => 'db'));
		$select = $issuesTable->getAdapter()->select();
		$select->from(array("i"=>"issues"), array("i.location", "i.floor_id", "count(issue_id) as total"));
		$select->joinLeft(array("f"=>$floor_table), "f.floor_id = i.floor_id", array("f.floor"));
		$select->where('month(i.issue_date) = ?', $month);
		$select->where('year(i.issue_date) = ?', $year);
		$select->where('i.site_id = ?', $this->site_id);
		$select->where('i.kejadian_id > ?', 0);
		$select->where('i.modus_id > ?', 0);
		$select->where('i.area_id = ?', $tenant_umum);
		$select->where('i.category_id = ?', $category_id);
		$select->group('i.location');
		$select->order('total desc');
		$issues = $issuesTable->getAdapter()->fetchAll($select);

		return $issues;
	}

	function getTotalIssuesByLocation($month, $year, $tenant_umum, $location, $floor_id, $category_id) {		
		if($category_id == 1) 
		{
			$floor_table = "security_floor";
		}
		if($category_id == 2) 
		{
			$floor_table = "housekeeping_floor";
		}
		if($category_id == 3) 
		{
			$floor_table = "safety_floor";
		}
		if($category_id == 5) 
		{
			$floor_table = "parking_floor";
		}
		if($category_id == 6) 
		{
			$floor_table = "engineering_floor";
		}
		if($category_id == 10) 
		{
			$floor_table = "building_service_floor";
		}
		if($category_id == 11) 
		{
			$floor_table = "tenant_relation_floor";
		}
		
		$issuesTable = new issues(array('db' => 'db'));
		$select = $issuesTable->getAdapter()->select();
		$select->from(array("i"=>"issues"), array("i.location", "i.floor_id", "i.kejadian_id", "count(issue_id) as total_kejadian"));
		$select->joinLeft(array("f"=>$floor_table), "f.floor_id = i.floor_id", array("f.floor"));
		$select->where('month(i.issue_date) = ?', $month);
		$select->where('year(i.issue_date) = ?', $year);
		$select->where('i.site_id = ?', $this->site_id);
		$select->where('i.kejadian_id > ?', 0);
		$select->where('i.area_id = ?', $tenant_umum);
		$select->where('i.location = ?', $location);
		$select->where('i.floor_id = ?', $floor_id);
		$select->where('i.category_id = ?', $category_id);
		$select->group('i.kejadian_id');
		$issues = $issuesTable->getAdapter()->fetchAll($select);

		return $issues;
	}

	function getPelakuTertangkapByCategory($year, $category_id) {
		if($category_id == 1) 
		{
			$modus_table = "security_modus";
		}
		if($category_id == 2) 
		{
			$modus_table = "housekeeping_modus";
		}
		if($category_id == 3) 
		{
			$modus_table = "safety_modus";
		}
		if($category_id == 5) 
		{
			$modus_table = "parking_modus";
		}
		if($category_id == 6) 
		{
			$modus_table = "engineering_modus";
		}
		if($category_id == 10) 
		{
			$modus_table = "building_service_modus";
		}
		if($category_id == 11) 
		{
			$modus_table = "tenant_relation_modus";
		}
		
		$issuesTable = new issues(array('db' => 'db'));
		$select = $issuesTable->getAdapter()->select();
		$select->from(array("i"=>"issues"), array("i.modus_id", "count(issue_id) as total_peryear", "i.description", "i.picture", "i.solved_picture"));
		$select->joinLeft(array("m"=>$modus_table), "m.modus_id = i.modus_id", array("m.modus"));
		$select->where('year(i.issue_date) = ?', $year);
		$select->where('i.site_id = ?', $this->site_id);
		$select->where('i.modus_id > ?', 0);
		$select->where('i.category_id = ?', $category_id);
		$select->where('i.pelaku_tertangkap = ?', "1");
		$select->group('i.modus_id');
		$issues = $issuesTable->getAdapter()->fetchAll($select);

		return $issues;
	}

	function getMonthlyPelakuTertangkapByModus($month, $year, $modus_id) {
		$issuesTable = new issues(array('db' => 'db'));
		$select = $issuesTable->getAdapter()->select();
		$select->from(array("i"=>"issues"), array("month(issue_date) as mo", "count(issue_date) as total_permonth"));
		$select->where('year(i.issue_date) = ?', $year);
		$select->where('i.site_id = ?', $this->site_id);
		$select->where('i.pelaku_tertangkap = ?', "1");
		$select->where('i.modus_id = ?', $modus_id);
		$select->where('month(i.issue_date) <= ?', $month);
		$select->group('month(issue_date)');
		$issues = $issuesTable->getAdapter()->fetchAll($select);

		return $issues;
	}

	function getPelakuTertangkapDetail($month, $year, $category_id) {
		$issuesTable = new issues(array('db' => 'db'));
		$select = $issuesTable->getAdapter()->select();
		$select->from(array("i"=>"issues"), array("issue_id", "description", "picture", "solved_picture", "issue_date", "solved_date"));
		$select->where('month(i.issue_date) = ?', $month);
		$select->where('year(i.issue_date) = ?', $year);
		$select->where('i.site_id = ?', $this->site_id);
		$select->where('i.modus_id > ?', 0);
		$select->where('i.pelaku_tertangkap = ?', "1");
		$select->where('i.category_id = ?', $category_id);
		$issues = $issuesTable->getAdapter()->fetchAll($select);

		return $issues;
	}
	
	function getTotalAllIssues($params) {
		$issuesTable= new issues(array('db'=>'db'));
		$select = $issuesTable->getAdapter()->select();
		$select->from(array("i"=>"issues"), array("count(issue_id) as total_issues"));
		$select->where('date(i.issue_date) >= ?', $params['start_date']);
		$select->where('date(i.issue_date) <= ?', $params['end_date']);
		
		$issues = $issuesTable->getAdapter()->fetchRow($select);

		return $issues;
	}

	function getTotalIssuesBySites($params) {
		$issuesTable= new issues(array('db'=>'db'));
		$select = $issuesTable->getAdapter()->select();
		$select->from(array("i"=>"issues"), array("i.site_id", "count(issue_id) as total_issues"));
		$select->joinLeft(array("s"=>"sites"), "s.site_id = i.site_id", array("s.initial"));
		$select->where('date(i.issue_date) >= ?', $params['start_date']);
		$select->where('date(i.issue_date) <= ?', $params['end_date']);
		if($params['solved'] != "") $select->where('i.solved = ?', $params['solved']);
		$select->group('i.site_id');
		//$select->order('total_issues desc');
		$issues = $issuesTable->getAdapter()->fetchAll($select);

		return $issues;
	}
	
	function getTotalIssuesByDept($params) {
		$issuesTable= new issues(array('db'=>'db'));
		$select = $issuesTable->getAdapter()->select();
		$select->from(array("i"=>"issues"), array("i.site_id", "count(issue_id) as total_issues"));
		$select->joinLeft(array("c"=>"categories"), "c.category_id = i.category_id", array("c.category_name"));
		$select->where('date(i.issue_date) >= ?', $params['start_date']);
		$select->where('date(i.issue_date) <= ?', $params['end_date']);
		$select->group('i.category_id');
		$select->order('total_issues desc');
		$issues = $issuesTable->getAdapter()->fetchAll($select);

		return $issues;
	}

	function getEmptyTotalIssuesBySites($params) {
		$issuesTable= new issues(array('db'=>'db'));
		$select = $issuesTable->getAdapter()->select();
		$select->from(array("i"=>"issues"), array("i.site_id", "count(issue_id) as total_issues"));
		$select->joinLeft(array("s"=>"sites"), "s.site_id = i.site_id", array("s.initial"));
		$select->where('date(i.issue_date) >= ?', $params['start_date']);
		$select->where('date(i.issue_date) <= ?', $params['end_date']);
		$select->group('i.site_id');
		$select->order('total_issues desc');
		$issues = $issuesTable->getAdapter()->fetchAll($select);

		return $issues;
	}

	function getPotentialHazardIssues($month, $year) {
		$issuesTable= new issues(array('db'=>'db'));
		
		$select = $issuesTable->getAdapter()->select();
		$select->from(array("i"=>"issues"), array("i.issue_id", "i.description", "i.solved", "i.issue_date"));
		$select->where('area_id  = ?', '1');
		$select->where('i.site_id  = ?', $this->site_id);
		$select->where('issue_type_id = ?', 11);
		$select->where('month(issue_date) = ?', $month);
		$select->where('year(issue_date) = ?', $year);
		$potential_hazard = $issuesTable->getAdapter()->fetchAll($select);
		return $potential_hazard;
	}

	function getTotalIssuesStatBySites($category_id, $year, $months, $days, $period, $floors, $issuetypes, $incidents, $tenant_umum, $modus, $sites) {		
		$issuesTable = new issues(array('db' => 'db'));
		$select = $issuesTable->getAdapter()->select();
		$select->from(array("i"=>"issues"), array("i.site_id","count(issue_id) as total"));
		$select->joinLeft(array("s"=>"sites"), "s.site_id = i.site_id", array("s.initial"));
		/*if($category_id == 1) 
			$select->joinLeft(array("k"=>"security_kejadian"), "i.kejadian_id = k.kejadian_id and k.issue_type = i.issue_type_id and k.site_id = i.site_id", array("k.kejadian"));
		elseif($category_id == 2) 
			$select->joinLeft(array("k"=>"housekeeping_kejadian"), "i.kejadian_id = k.kejadian_id and k.issue_type = i.issue_type_id and k.site_id = i.site_id", array("k.kejadian"));
		elseif($category_id == 3)
			$select->joinLeft(array("k"=>"safety_kejadian"), "i.kejadian_id = k.kejadian_id and k.issue_type = i.issue_type_id and k.site_id = i.site_id", array("k.kejadian"));
		elseif($category_id == 5) 
			$select->joinLeft(array("k"=>"parking_kejadian"), "i.kejadian_id = k.kejadian_id and k.issue_type = i.issue_type_id and k.site_id = i.site_id", array("k.kejadian"));
		elseif($category_id == 6) 
			$select->joinLeft(array("k"=>"engineering_kejadian"), "i.kejadian_id = k.kejadian_id and k.issue_type = i.issue_type_id and k.site_id = i.site_id", array("k.kejadian"));*/

		$select->where('year(i.issue_date) = ?', $year);
		//$select->where('i.site_id = ?', $this->site_id);
		$select->where('i.category_id = ?', $category_id);
		//$select->where('k.kejadian_id > ?', 0);
		$select->where('i.issue_type_id > ?', 0);
		if(!empty($sites)) $select->where("i.site_id IN (".$sites.")");
		if(!empty($months)) $select->where("month(issue_date) IN (".$months.")");
		if(!empty($days)) $select->where("dayofweek(issue_date) IN (".$days.")");
		if(!empty($floors)) $select->where("floor_id IN (".$floors.")");
		if($tenant_umum != "") $select->where("i.area_id IN (".$tenant_umum.")");
		if(!empty($issuetypes)) $select->where("i.issue_type_id IN (".$issuetypes.")");
		if(!empty($incidents)) $select->where("i.kejadian_id IN (".$incidents.")");
		if(!empty($modus)) $select->where("i.modus_id IN (".$modus.")");
		if(!empty($period) && count($period) < 5)
		{
			$q = "";
			$j = 0;
			foreach($period as $p)
			{
				if($j > 0) $q .= ' OR ';
				switch($p)
				{
					case 1: $q .= '(time(i.issue_date) > "09:00" and time(i.issue_date) <= "12:00")'; break; 
					case 2: $q .= '(time(i.issue_date) > "12:00" and time(i.issue_date) <= "16:00")'; break;
					case 3: $q .= '(time(i.issue_date) > "16:00" and time(i.issue_date) <= "19:00")'; break;
					case 4: $q .= '(time(i.issue_date) > "19:00" and time(i.issue_date) <= "23:00")'; break;
					case 5: $q .= '(time(i.issue_date) > "23:00" or time(i.issue_date) <= "09:00")'; break;
				}
				$j++;
			}
			$select->where($q);
		}
		$select->group("i.site_id");
		$issues = $issuesTable->getAdapter()->fetchAll($select);
		return $issues;
	}

	function getTotalIssuesStatByTimePeriode($start_time, $end_time, $category_id, $year, $months, $days, $period, $floors, $issuetypes, $incidents, $tenant_umum, $modus, $sites) {		
		$issuesTable = new issues(array('db' => 'db'));
		$select = $issuesTable->getAdapter()->select();
		$select->from(array("i"=>"issues"), array("count(issue_id) as total"));
		/*if($category_id == 1) 
			$select->joinLeft(array("k"=>"security_kejadian"), "i.kejadian_id = k.kejadian_id and k.issue_type = i.issue_type_id and k.site_id = i.site_id", array("k.kejadian"));
		elseif($category_id == 2)
			$select->joinLeft(array("k"=>"housekeeping_kejadian"), "i.kejadian_id = k.kejadian_id and k.issue_type = i.issue_type_id and k.site_id = i.site_id", array("k.kejadian"));
		elseif($category_id == 3)
			$select->joinLeft(array("k"=>"safety_kejadian"), "i.kejadian_id = k.kejadian_id and k.issue_type = i.issue_type_id and k.site_id = i.site_id", array("k.kejadian"));
		elseif($category_id == 5) 
			$select->joinLeft(array("k"=>"parking_kejadian"), "i.kejadian_id = k.kejadian_id and k.issue_type = i.issue_type_id and k.site_id = i.site_id", array("k.kejadian"));
		elseif($category_id == 6) 
			$select->joinLeft(array("k"=>"engineering_kejadian"), "i.kejadian_id = k.kejadian_id and k.issue_type = i.issue_type_id and k.site_id = i.site_id", array("k.kejadian"));
		*/
		$select->where('year(i.issue_date) = ?', $year);
		//$select->where('i.site_id = ?', $this->site_id);
		$select->where('i.category_id = ?', $category_id);
		//$select->where('k.kejadian_id > ?', 0);
		if($start_time == "23:00:00" && $end_time == "09:00:00")
		{
			$select->where('time(issue_date) > "'. $start_time . '" OR time(issue_date) <= "'. $end_time.'"');
		}
		else{
			$select->where('time(issue_date) > ?', $start_time);
			$select->where('time(issue_date) <= ?', $end_time);
		}
		if(!empty($sites)) $select->where("i.site_id IN (".$sites.")");
		if(!empty($months)) $select->where("month(issue_date) IN (".$months.")");
		if(!empty($days)) $select->where("dayofweek(issue_date) IN (".$days.")");
		if(!empty($floors)) $select->where("floor_id IN (".$floors.")");
		if($tenant_umum != "") $select->where("i.area_id IN (".$tenant_umum.")");
		if(!empty($issuetypes)) $select->where("i.issue_type_id IN (".$issuetypes.")");
		if(!empty($incidents)) $select->where("i.kejadian_id IN (".$incidents.")");
		if(!empty($modus)) $select->where("i.modus_id IN (".$modus.")");
		$issues = $issuesTable->getAdapter()->fetchRow($select);

		return $issues['total'];
	}

	function getDetailIssuesandSummary($category_id, $year, $months, $days, $period, $floors, $tenant_umum, $incidents, $modus, $site_ids, $offset=0, $pagesize = 15) {		
		$issuesTable = new issues(array('db' => 'db'));
		$select = $issuesTable->getAdapter()->select();
		$select->from(array("i"=>"issues"), array());
		if($category_id == 1) {
			$select->joinLeft(array("s"=>"security_monthly_analysis_summary"), "s.kejadian_id = i.kejadian_id", array("s.analisa", "s.tindakan"));
			$select->joinLeft(array("k"=>"security_kejadian"), "i.kejadian_id = k.kejadian_id and k.issue_type = i.issue_type_id and k.site_id = i.site_id", array("k.kejadian_id", "k.kejadian"));
			$select->joinLeft(array("m"=>"security_modus"), "i.modus_id = m.modus_id", array("m.modus"));
		}
		elseif($category_id == 2) 
		{
			$select->joinLeft(array("s"=>"housekeeping_monthly_analysis_summary"), "s.kejadian_id = i.kejadian_id", array("s.analisa", "s.tindakan"));
			$select->joinLeft(array("k"=>"housekeeping_kejadian"), "i.kejadian_id = k.kejadian_id and k.issue_type = i.issue_type_id and k.site_id = i.site_id", array("k.kejadian_id", "k.kejadian"));
			$select->joinLeft(array("m"=>"housekeeping_modus"), "i.modus_id = m.modus_id", array("m.modus"));
		}
		elseif($category_id == 3)
		{
			$select->joinLeft(array("s"=>"safety_monthly_analysis_summary"), "s.kejadian_id = i.kejadian_id", array("s.analisa", "s.tindakan", "s.rekomendasi"));
			$select->joinLeft(array("k"=>"safety_kejadian"), "i.kejadian_id = k.kejadian_id and k.issue_type = i.issue_type_id and k.site_id = i.site_id", array("k.kejadian_id", "k.kejadian"));
			$select->joinLeft(array("m"=>"safety_modus"), "i.modus_id = m.modus_id", array("m.modus"));
		}
		elseif($category_id == 5) 
		{
			$select->joinLeft(array("s"=>"parking_monthly_analysis_summary"), "s.kejadian_id = i.kejadian_id", array("s.analisa", "s.tindakan"));
			$select->joinLeft(array("k"=>"parking_kejadian"), "i.kejadian_id = k.kejadian_id and k.issue_type = i.issue_type_id and k.site_id = i.site_id", array("k.kejadian_id", "k.kejadian"));
			$select->joinLeft(array("m"=>"parking_modus"), "i.modus_id = m.modus_id", array("m.modus"));
		}
		elseif($category_id == 6) 
		{
			$select->joinLeft(array("s"=>"engineering_monthly_analysis_summary"), "s.kejadian_id = i.kejadian_id", array("s.analisa", "s.tindakan"));
			$select->joinLeft(array("k"=>"engineering_kejadian"), "i.kejadian_id = k.kejadian_id and k.issue_type = i.issue_type_id and k.site_id = i.site_id", array("k.kejadian_id", "k.kejadian"));
			$select->joinLeft(array("m"=>"engineering_modus"), "i.modus_id = m.modus_id", array("m.modus"));
		}
		elseif($category_id == 10) 
		{
			$select->joinLeft(array("s"=>"building_service_monthly_analysis_summary"), "s.kejadian_id = i.kejadian_id", array("s.analisa", "s.tindakan"));
			$select->joinLeft(array("k"=>"building_service_kejadian"), "i.kejadian_id = k.kejadian_id and k.issue_type = i.issue_type_id and k.site_id = i.site_id", array("k.kejadian_id", "k.kejadian"));
			$select->joinLeft(array("m"=>"building_service_modus"), "i.modus_id = m.modus_id", array("m.modus"));
		}
		elseif($category_id == 11) 
		{
			$select->joinLeft(array("s"=>"tenant_relation_monthly_analysis_summary"), "s.kejadian_id = i.kejadian_id", array("s.analisa", "s.tindakan"));
			$select->joinLeft(array("k"=>"tenant_relation_kejadian"), "i.kejadian_id = k.kejadian_id and k.issue_type = i.issue_type_id and k.site_id = i.site_id", array("k.kejadian_id", "k.kejadian"));
			$select->joinLeft(array("m"=>"tenant_relation_modus"), "i.modus_id = m.modus_id", array("m.modus"));
		}
		
		$select->where('year(i.issue_date) = ?', $year);
		//$select->where('i.site_id = ?', $this->site_id);
		$select->where('i.category_id = ?', $category_id);
		$select->where('i.kejadian_id > ?', 0);
		$select->where('k.issue_type > ?', 0);
		$select->where('year(s.save_date) = ?', $year);
		if(!empty($site_ids)) $select->where("i.site_id IN (".$site_ids.")");
		if(!empty($months)) 
		{
			$select->where("month(issue_date) IN (".$months.")");
			$summaryMonth = explode(",", $months);
			$months2 = "";
			foreach($summaryMonth as $sm)
			{
				if($sm == 12) 
				{
					$sm = 0;
					$year = $year + 1;
				}
				$months2 .= ($sm+1).",";
			}
			$months2 = substr($months2,0,-1);
			$select->where("month(s.save_date) IN (".$months2.")");
			$select->where('year(s.save_date) = ?', $year);
		}
		else{
			$select->where('month(s.save_date) > ?', 1);
		}
		if(!empty($days)) $select->where("dayofweek(issue_date) IN (".$days.")");
		if(!empty($floors)) $select->where("floor_id IN (".$floors.")");
		if($tenant_umum != "") $select->where("i.area_id IN (".$tenant_umum.")");
		if(!empty($incidents)) $select->where("i.kejadian_id IN (".$incidents.")");
		if(!empty($modus)) $select->where("i.modus_id IN (".$modus.")");
		if(!empty($period) && count($period) < 5)
		{
			$q = "";
			$j = 0;
			foreach($period as $p)
			{
				if($j > 0) $q .= ' OR ';
				switch($p)
				{
					case 1: $q .= '(time(i.issue_date) > "09:00" and time(i.issue_date) <= "12:00")'; break; 
					case 2: $q .= '(time(i.issue_date) > "12:00" and time(i.issue_date) <= "16:00")'; break;
					case 3: $q .= '(time(i.issue_date) > "16:00" and time(i.issue_date) <= "19:00")'; break;
					case 4: $q .= '(time(i.issue_date) > "19:00" and time(i.issue_date) <= "23:00")'; break;
					case 5: $q .= '(time(i.issue_date) > "23:00" or time(i.issue_date) <= "09:00")'; break;
				}
				$j++;
			}
			$select->where($q);
		}
		$select->group("s.summary_id");
		$select->order("i.kejadian_id");
		//if($pagesize > 0 || $offset > 0) $select->limit($pagesize,$offset);
		$issues = $issuesTable->getAdapter()->fetchAll($select);

		return $issues;
	}

	function getTotalDetailIssuesandSummary($category_id, $year, $months, $days, $period, $floors, $tenant_umum, $incidents, $modus, $site_ids) {		
		$issuesTable = new issues(array('db' => 'db'));
		$select = $issuesTable->getAdapter()->select();
		$select->from(array("i"=>"issues"), array("issue_id", "issue_date", "description"));
		if($category_id == 1) {
			$select->joinLeft(array("s"=>"security_monthly_analysis_summary"), "s.kejadian_id = i.kejadian_id", array());
			$select->joinLeft(array("k"=>"security_kejadian"), "i.kejadian_id = k.kejadian_id and k.issue_type = i.issue_type_id and k.site_id = i.site_id", array());
		}
		elseif($category_id == 2) 
		{
			$select->joinLeft(array("s"=>"housekeeping_monthly_analysis_summary"), "s.kejadian_id = i.kejadian_id", array());
			$select->joinLeft(array("k"=>"housekeeping_kejadian"), "i.kejadian_id = k.kejadian_id and k.issue_type = i.issue_type_id and k.site_id = i.site_id", array());
		}
		elseif($category_id == 3)
		{
			$select->joinLeft(array("s"=>"safety_monthly_analysis_summary"), "s.kejadian_id = i.kejadian_id", array());
			$select->joinLeft(array("k"=>"safety_kejadian"), "i.kejadian_id = k.kejadian_id and k.issue_type = i.issue_type_id and k.site_id = i.site_id", array());
		}
		elseif($category_id == 5) 
		{
			$select->joinLeft(array("s"=>"parking_monthly_analysis_summary"), "s.kejadian_id = i.kejadian_id", array());
			$select->joinLeft(array("k"=>"parking_kejadian"), "i.kejadian_id = k.kejadian_id and k.issue_type = i.issue_type_id and k.site_id = i.site_id", array());
		}
		elseif($category_id == 6) 
		{
			$select->joinLeft(array("s"=>"engineering_monthly_analysis_summary"), "s.kejadian_id = i.kejadian_id", array());
			$select->joinLeft(array("k"=>"engineering_kejadian"), "i.kejadian_id = k.kejadian_id and k.issue_type = i.issue_type_id and k.site_id = i.site_id", array());
		}
		elseif($category_id == 10) 
		{
			$select->joinLeft(array("s"=>"building_service_analysis_summary"), "s.kejadian_id = i.kejadian_id", array());
			$select->joinLeft(array("k"=>"building_service_kejadian"), "i.kejadian_id = k.kejadian_id and k.issue_type = i.issue_type_id and k.site_id = i.site_id", array());
		}
		elseif($category_id == 11) 
		{
			$select->joinLeft(array("s"=>"tenant_relation_monthly_analysis_summary"), "s.kejadian_id = i.kejadian_id", array());
			$select->joinLeft(array("k"=>"tenant_relation_kejadian"), "i.kejadian_id = k.kejadian_id and k.issue_type = i.issue_type_id and k.site_id = i.site_id", array());
		}
		
		$select->where('year(i.issue_date) = ?', $year);
		//$select->where('i.site_id = ?', $this->site_id);
		$select->where('i.category_id = ?', $category_id);
		$select->where('i.kejadian_id > ?', 0);
		$select->where('k.issue_type > ?', 0);
		if(!empty($site_ids)) $select->where("i.site_id IN (".$site_ids.")");
		if(!empty($months)) $select->where("month(issue_date) IN (".$months.")");
		if(!empty($days)) $select->where("dayofweek(issue_date) IN (".$days.")");
		if(!empty($floors)) $select->where("floor_id IN (".$floors.")");
		if($tenant_umum != "") $select->where("i.area_id IN (".$tenant_umum.")");
		if(!empty($incidents)) $select->where("i.kejadian_id IN (".$incidents.")");
		if(!empty($modus)) $select->where("i.modus_id IN (".$modus.")");
		if(!empty($period) && count($period) < 5)
		{
			$q = "";
			$j = 0;
			foreach($period as $p)
			{
				if($j > 0) $q .= ' OR ';
				switch($p)
				{
					case 1: $q .= '(time(i.issue_date) > "09:00" and time(i.issue_date) <= "12:00")'; break; 
					case 2: $q .= '(time(i.issue_date) > "12:00" and time(i.issue_date) <= "16:00")'; break;
					case 3: $q .= '(time(i.issue_date) > "16:00" and time(i.issue_date) <= "19:00")'; break;
					case 4: $q .= '(time(i.issue_date) > "19:00" and time(i.issue_date) <= "23:00")'; break;
					case 5: $q .= '(time(i.issue_date) > "23:00" or time(i.issue_date) <= "09:00")'; break;
				}
				$j++;
			}
			$select->where($q);
		}
		$select->group("i.issue_id");
		//echo $select; exit();
		$issues = $issuesTable->getAdapter()->fetchAll($select);

		return count($issues);
	}

	function getMonthlyAnalysisIssues($month, $year, $category_id, $issue_type_id = 0) {
		$issuesTable = new issues(array('db' => 'db'));
		$select = $issuesTable->getAdapter()->select();
		$select->from(array("i"=>"issues"), array("issue_id", "description", "picture", "solved_picture", "issue_date", "solved_date", "location"));
		if($category_id == 1) {
			$select->joinLeft(array("k"=>"security_kejadian"), "i.kejadian_id = k.kejadian_id and k.issue_type = i.issue_type_id and k.site_id = i.site_id", array("k.kejadian"));
			$select->joinLeft(array("m"=>"security_modus"), "i.modus_id = m.modus_id", array("m.modus"));
		}
		elseif($category_id == 2) 
		{
			$select->joinLeft(array("k"=>"housekeeping_kejadian"), "i.kejadian_id = k.kejadian_id and k.issue_type = i.issue_type_id and k.site_id = i.site_id", array("k.kejadian"));
			$select->joinLeft(array("m"=>"housekeeping_modus"), "i.modus_id = m.modus_id", array("m.modus"));
		}
		elseif($category_id == 3)
		{
			$select->joinLeft(array("k"=>"safety_kejadian"), "i.kejadian_id = k.kejadian_id and k.issue_type = i.issue_type_id and k.site_id = i.site_id", array("k.kejadian"));
			$select->joinLeft(array("m"=>"safety_modus"), "i.modus_id = m.modus_id", array("m.modus"));
		}
		elseif($category_id == 5) 
		{
			$select->joinLeft(array("k"=>"parking_kejadian"), "i.kejadian_id = k.kejadian_id and k.issue_type = i.issue_type_id and k.site_id = i.site_id", array("k.kejadian"));
			$select->joinLeft(array("m"=>"parking_modus"), "i.modus_id = m.modus_id", array("m.modus"));
		}
		elseif($category_id == 6) 
		{
			$select->joinLeft(array("k"=>"engineering_kejadian"), "i.kejadian_id = k.kejadian_id and k.issue_type = i.issue_type_id and k.site_id = i.site_id", array("k.kejadian"));
			$select->joinLeft(array("m"=>"engineering_modus"), "i.modus_id = m.modus_id", array("m.modus"));
		}
		elseif($category_id == 10) 
		{
			$select->joinLeft(array("k"=>"building_service_kejadian"), "i.kejadian_id = k.kejadian_id and k.issue_type = i.issue_type_id and k.site_id = i.site_id", array("k.kejadian"));
			$select->joinLeft(array("m"=>"building_service_modus"), "i.modus_id = m.modus_id", array("m.modus"));
		}
		elseif($category_id == 11) 
		{
			$select->joinLeft(array("k"=>"tenant_relation_kejadian"), "i.kejadian_id = k.kejadian_id and k.issue_type = i.issue_type_id and k.site_id = i.site_id", array("k.kejadian"));
			$select->joinLeft(array("m"=>"tenant_relation_modus"), "i.modus_id = m.modus_id", array("m.modus"));
		}
		$select->where('month(i.issue_date) = ?', $month);
		$select->where('year(i.issue_date) = ?', $year);
		$select->where('i.site_id = ?', $this->site_id);
		$select->where('i.modus_id > ?', 0);
		$select->where('i.category_id = ?', $category_id);
		if(!empty($issue_type_id)) $select->where('i.issue_type_id = ?', $issue_type_id);
		$issues = $issuesTable->getAdapter()->fetchAll($select);

		return $issues;
	}

	function updateIncidentModusIssue($params) {
		$issuesTable = new issues(array('db'=>'db'));

		$data = array(
			"kejadian_id" => $params["kejadian_id"],
			"modus_id" => $params["modus_id"],
			"pelaku_tertangkap" => $params["pelaku_tertangkap"]
		);
		
		$where = $issuesTable->getAdapter()->quoteInto('issue_id = ?', $params['issue_id']);
		$issuesTable->update($data, $where);	
	}

	
	function getHODIssues($cat_id) {		
		$issuesTable = new issues(array('db' => 'db'));
		$select = $issuesTable->getAdapter()->select();
		$select->from(array("i"=>"issues"), array("i.issue_id", "i.issue_date", "i.description", "i.location", "i.picture", "i.floor_id"));
		$select->joinLeft(array("s"=>"sites"), "s.site_id = i.site_id", array("s.site_id", "s.site_name"));
		$select->joinLeft(array("c"=>"categories"), "c.category_id = i.category_id", array("c.category_id", "c.category_name"));
		$select->where("i.solved = 0");
		//if(!empty($site_ids)) $select->where("i.site_id IN (".$site_ids.")");
		if(!empty($cat_id)) $select->where("i.category_id IN (".$cat_id.")");
		//$select->where("i.category_id = ?", 1);
		$select->where("i.site_id = ?", $this->site_id);
		$select->where("i.issue_type_id <> ?", 3);
		$select->order("i.site_id");
		$select->order("i.issue_date");
		$issues = $issuesTable->getAdapter()->fetchAll($select);
		
		return $issues;
	}

	function getBSIssues() {		
		$issuesTable = new issues(array('db' => 'db'));
		$select = $issuesTable->getAdapter()->select();
		$select->from(array("i"=>"issues"), array("i.issue_id", "i.issue_date", "i.description", "i.location", "i.picture"));
		$select->joinLeft(array("s"=>"sites"), "s.site_id = i.site_id", array("s.site_id", "s.initial"));
		$select->where("i.solved = 0");
		$select->where("i.category_id = ?", 10);
		$select->where("i.issue_type_id <> ?", 3);
		$select->where("i.site_id > ?", 0);
		$select->order("i.site_id");
		$select->order("i.issue_date");
		$issues = $issuesTable->getAdapter()->fetchAll($select);
		
		return $issues;
	}

	
	function getPotentialHazardFindingsTenant($month, $year) {		
		$issuesTable = new issues(array('db' => 'db'));
		$select = $issuesTable->getAdapter()->select();
		$select->from(array("i"=>"issues"), array("i.issue_id", "i.issue_date", "i.description", "i.location", "i.description"));
		$select->joinLeft(array("f"=>"safety_floor"), "f.floor_id = i.floor_id", array("floor"));
		$select->joinLeft(array("fpte"=>"fire_protection_tenant_equipment"), "fpte.issue_id = i.issue_id", array("perlengkapan_tenant_id", "potensi_bahaya", "keterangan"));
		$select->where("i.site_id = ?", $this->site_id);
		$select->where("i.category_id = ?", 3);
		$select->where("month(i.issue_date) = ?", $month);
		$select->where("year(i.issue_date) = ?", $year);
		$select->where("i.issue_type_id = ?", 9);
		$select->where("i.area_id = ?", '0');
		$select->order("i.issue_date");
		$issues = $issuesTable->getAdapter()->fetchAll($select);
		
		return $issues;
	}
	
	function getAvgIssuesDuration($params) {
		$issuesTable= new issues(array('db'=>'db'));
		
		$select = 'SELECT site_name, count(*) as total_data, sum(TIMESTAMPDIFF(SECOND,issue_date,solved_date)) as time_diff from issues i left join sites s on s.site_id = i.site_id where solved_date > "0000-00-00 00:00:00" group by i.site_id ';
		$issues = $issuesTable->getAdapter()->fetchAll($select);
		return $issues;
	}
	
	function getIssuesPerMonth($category_id, $year, $months, $days, $period, $floors, $tenant_umum, $issuetypes, $incidents, $modus, $site_ids) {
		$issuesTable= new issues(array('db'=>'db'));
		$select = $issuesTable->getAdapter()->select();
		$select->from(array("i"=>"issues"), array("count(*) as total", "month(i.issue_date) as mo"));
		$select->where("year(i.issue_date)= ?", $year);
		$select->where("category_id= ?", $category_id);
		if(!empty($site_ids)) $select->where("i.site_id IN (".$site_ids.")");
		if(!empty($months)) $select->where("month(i.issue_date) IN (".$months.")");
		if(!empty($days)) $select->where("dayofweek(i.issue_date) IN (".$days.")");
		if(!empty($floors)) $select->where("i.floor_id IN (".$floors.")");
		if($tenant_umum != "") $select->where("i.area_id IN (".$tenant_umum.")");
		if(!empty($incidents)) $select->where("i.kejadian_id IN (".$incidents.")");
		if(!empty($issuetypes)) $select->where("i.issue_type_id IN (".$issuetypes.")");
		if(!empty($modus)) $select->where("i.modus_id IN (".$modus.")");
		if(!empty($period) && count($period) < 5)
		{
			$q = "";
			$j = 0;
			foreach($period as $p)
			{
				if($j > 0) $q .= ' OR ';
				switch($p)
				{
					case 1: $q .= '(time(i.issue_date) > "09:00" and time(i.issue_date) <= "12:00")'; break; 
					case 2: $q .= '(time(i.issue_date) > "12:00" and time(i.issue_date) <= "16:00")'; break;
					case 3: $q .= '(time(i.issue_date) > "16:00" and time(i.issue_date) <= "19:00")'; break;
					case 4: $q .= '(time(i.issue_date) > "19:00" and time(i.issue_date) <= "23:00")'; break;
					case 5: $q .= '(time(i.issue_date) > "23:00" or time(i.issue_date) <= "09:00")'; break;
				}
				$j++;
			}
			$select->where($q);
		}
		$select->group("month(issue_date)");	
		$issues = $issuesTable->getAdapter()->fetchAll($select);
		return $issues;
	}
	
	function getTotalEachType($category_id, $year, $months, $days, $period, $floors, $tenant_umum, $issuetypes, $incidents, $modus, $site_ids) {
		$issuesTable= new issues(array('db'=>'db'));
		$select = $issuesTable->getAdapter()->select();
		$select->from(array("i"=>"issues"), array("count(*) as total"));
		$select->joinLeft(array("t"=>"issue_type"), "t.issue_type_id = i.issue_type_id", array("t.issue_type"));
		$select->where("year(i.issue_date)= ?", $year);
		$select->where("i.category_id= ?", $category_id);
		if(!empty($site_ids)) $select->where("i.site_id IN (".$site_ids.")");
		if(!empty($months)) $select->where("month(i.issue_date) IN (".$months.")");
		if(!empty($days)) $select->where("dayofweek(i.issue_date) IN (".$days.")");
		if(!empty($floors)) $select->where("i.floor_id IN (".$floors.")");
		if($tenant_umum != "") $select->where("i.area_id IN (".$tenant_umum.")");
		if(!empty($issuetypes)) $select->where("i.issue_type_id IN (".$issuetypes.")");
		if(!empty($incidents)) $select->where("i.kejadian_id IN (".$incidents.")");
		if(!empty($modus)) $select->where("i.modus_id IN (".$modus.")");
		if(!empty($period) && count($period) < 5)
		{
			$q = "";
			$j = 0;
			foreach($period as $p)
			{
				if($j > 0) $q .= ' OR ';
				switch($p)
				{
					case 1: $q .= '(time(i.issue_date) > "09:00" and time(i.issue_date) <= "12:00")'; break; 
					case 2: $q .= '(time(i.issue_date) > "12:00" and time(i.issue_date) <= "16:00")'; break;
					case 3: $q .= '(time(i.issue_date) > "16:00" and time(i.issue_date) <= "19:00")'; break;
					case 4: $q .= '(time(i.issue_date) > "19:00" and time(i.issue_date) <= "23:00")'; break;
					case 5: $q .= '(time(i.issue_date) > "23:00" or time(i.issue_date) <= "09:00")'; break;
				}
				$j++;
			}
			$select->where($q);
		}
		$select->group("i.issue_type_id");	
		$issues = $issuesTable->getAdapter()->fetchAll($select);
		return $issues;
	}

	function getLongestClosedIssues() {
		$issuesTable= new issues(array('db'=>'db'));

		$select = 'select max(datediff(date(solved_date), date(issue_date))) as difclosed from issues';
		$issue = $issuesTable->getAdapter()->fetchRow($select);
		return $issue['difclosed'];
	}
	
	function getTopKaizenSubmitter() {
		$issuesTable= new issues(array('db'=>'db'));
		$select = $issuesTable->getAdapter()->select();
		$select->from(array("i"=>"issues"), array("i.user_id", "count(*) as total"));
		$select->joinLeft(array("u"=>"users"), "u.user_id = i.user_id", array("u.name"));
		$select->where('i.site_id = ?', $this->site_id);
		$select->group('i.user_id');
		$select->order('total desc');
		$issues = $issuesTable->getAdapter()->fetchAll($select);

		return $issues[0];
	}
}
?>
