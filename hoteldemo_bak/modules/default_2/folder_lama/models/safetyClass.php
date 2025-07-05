<?php

require_once('defaultClass.php');
require_once('dbClass.php');

class safetyClass extends defaultClass
{	
	function saveReport($params) {
		$safetyReportTable = new safety_daily_report(array('db'=>'db'));
		
		$data = array(
			"site_id" => $this->site_id,
			"user_id" => $params["user_id"],
			"man_power_afternoon" => $params["man_power_afternoon"],
			"man_power_night" => $params["man_power_night"],
			"man_power_morning" => $params["man_power_morning"],
			"briefing1" => $params["briefing1"],
			"briefing2" => $params["briefing2"],
			"briefing3" => $params["briefing3"],
			"sop1" => $params["sop1"],
			"sop2" => $params["sop2"],
			"sop3" => $params["sop3"],
			"created_date" => date("Y-m-d H:i:s")
		);
		if(empty($params['safety_report_id']))
		{
			$safetyReportTable->insert($data);
			return $this->db->lastInsertId();
		}
		else
		{
			unset($data['created_date']);
			unset($data['user_id']);
			$where = $safetyReportTable->getAdapter()->quoteInto('report_id = ?', $params['safety_report_id']);
			$safetyReportTable->update($data, $where);
			return $params['safety_report_id'];
		}
	}
	
	function getReports($params) {
		$safetyReportTable = new safety_daily_report(array('db'=>'db'));
		$select = $safetyReportTable->getAdapter()->select();
		$select->from(array("s"=>"safety_daily_report"), array("s.*"));
		$select->joinLeft(array("u"=>"users"), "u.user_id = s.user_id", array("u.name"));
		$select->where('s.site_id = ?', $this->site_id);
		$select->order('s.created_date desc');
		$select->limit($params['pagesize'],$params['start']);
		$safety = $safetyReportTable->getAdapter()->fetchAll($select);
		return $safety;
	}
	
	function getReportById($id) {
		$safetyReportTable = new safety_daily_report(array('db'=>'db'));
		
		$select = $safetyReportTable->getAdapter()->select();
		$select->from(array("s"=>"safety_daily_report"), array("s.*"));
		$select->joinLeft(array("u"=>"users"), "u.user_id = s.user_id", array("u.name"));
		$select->joinLeft(array("si"=>"sites"), "si.site_id = s.site_id", array("si.site_fullname"));
		$select->where('s.report_id = ?', $id);
		$safety = $safetyReportTable->getAdapter()->fetchRow($select);

		return $safety;
	}
	
	function deleteReportById($id)
	{
		$safetyReportTable = new safety_daily_report(array('db'=>'db'));
		
		if ( is_numeric($id) && $id > 0 )
		{		
			$where = $safetyReportTable->getAdapter()->quoteInto('report_id = ?', $id);
			$safetyReportTable->delete($where);
		}
	}
	
	
	function addSpecificReport($params) {
		$specificReportTable = new safety_specific_report(array('db'=>'db'));
		
		$data = array(
			"safety_report_id" => intval($params["safety_report_id"]),
			"issue_type" => intval($params["issue_type"]),
			"detail" => $params["detail"],"detail" => $params["detail"],
			"status" => $params["status"],
			"created_date" => date("Y-m-d H:i:s"),
			"issue_id" => intval($params["issue_id"]),
			"site_id" => $this->site_id
		);
		$specificReportTable->insert($data);
		return $this->db->lastInsertId();
	}
	
	function deleteSpecificReportBySafetyId($safety_id)
	{
		$specificReportTable = new safety_specific_report(array('db'=>'db'));
		
		if ( is_numeric($safety_id) && $safety_id > 0 )
		{		
			$where = $specificReportTable->getAdapter()->quoteInto('safety_report_id = ?', $safety_id);
			$specificReportTable->delete($where);
		}
	}
	
	/*function getSpecificReportBySafetyReport($safety_report_id) {
		$specificReportTable = new safety_specific_report(array('db'=>'db'));
		
		$select = $specificReportTable->getAdapter()->select();
		$select->from(array("ssr"=>"safety_specific_report"), array("ssr.*"));
		$select->joinLeft(array("it"=>"issue_type"), "it.issue_type_id = ssr.issue_type", array("it.issue_type_id","it.issue_type as issue_type_name"));
		$select->where('ssr.safety_report_id = ?', $safety_report_id);
		$specific_reports = $specificReportTable->getAdapter()->fetchAll($select);
		return $specific_reports;
	}*/
	
	function getSpecificReportById($safety_report_id) {
		$specificReportTable = new safety_specific_report(array('db'=>'db'));
		
		$select = $specificReportTable->getAdapter()->select();
		$select->from(array("ssr"=>"safety_specific_report"), array("ssr.*"));
		$select->joinLeft(array("it"=>"issue_type"), "it.issue_type_id = ssr.issue_type", array("it.issue_type_id","it.issue_type as issue_type_name"));
		$select->joinLeft(array("i"=>"issues"), "i.issue_id = ssr.issue_id", array("i.issue_date","i.description","i.location","i.picture","i.solved_picture","i.solved_date"));
		if($safety_report_id > 0) $select->orwhere('ssr.safety_report_id = ?', $safety_report_id);
		$specific_reports = $specificReportTable->getAdapter()->fetchAll($select);
		return $specific_reports;
	}
	
	function getTotalReport() {
		$safetyReportTable = new safety_daily_report(array('db'=>'db'));
		$select = "select count(*) as total from safety_daily_report where site_id =".$this->site_id;
		$safetyReports = $safetyReportTable->getAdapter()->fetchRow($select);
		return $safetyReports;
	}
	
	function getSafetyReportByDate($date) {
		$safetyReportTable = new safety_daily_report(array('db'=>'db'));
		
		$select = $safetyReportTable->select();
		$select->where('date(created_date) = ?', $date);
		$select->where('site_id = ?', $this->site_id);
		$safety = $safetyReportTable->getAdapter()->fetchRow($select);
		return $safety;
	}
	
	function getSpecificReportByDate($date) {
		$specificReportTable = new safety_specific_report(array('db'=>'db'));
		
		$select = $specificReportTable->getAdapter()->select();
		$select->from(array("ssr"=>"safety_specific_report"), array("ssr.*"));
		$select->joinLeft(array("it"=>"issue_type"), "it.issue_type_id = ssr.issue_type", array("it.issue_type_id","it.issue_type as issue_type_name"));
		$select->joinLeft(array("i"=>"issues"), "i.issue_id = ssr.issue_id", array("i.issue_date","i.description","i.location","i.picture","i.solved_picture","i.solved_date"));
		$select->where('date(ssr.created_date) = ?', $date);
		$select->where('site_id = ?', $this->site_id);
		$specific_reports = $specificReportTable->getAdapter()->fetchAll($select);
		return $specific_reports;
	}
	
	function updateSpecificReportCompletionDate($id, $completion_date, $report_id, $fieldName) {
		$specificReportTable = new safety_specific_report(array('db'=>'db'));
		
		$data = array(
			"completion_date" => $completion_date." 00:00:00",
			$fieldName => $report_id
		);

		$where = $specificReportTable->getAdapter()->quoteInto('specific_report_id = ?', $id);
		$specificReportTable->update($data, $where);
	}
	
	/* tampilin yg completion date-nya null atau yg completion datenya pas hari itu. 
	completion date field otomatis terisi dari solved date.  */
	function getUnsolvedIssues($om_report_id, $report_date = "0000-00-00") {
		$specificReportTable = new safety_specific_report(array('db'=>'db'));
		
		$select = $specificReportTable->getAdapter()->select();
		$select->from(array("ssr"=>"safety_specific_report"), array("ssr.*"));
		$select->joinLeft(array("it"=>"issue_type"), "it.issue_type_id = ssr.issue_type", array("it.issue_type_id","it.issue_type as issue_type_name"));
		$select->joinLeft(array("i"=>"issues"), "i.issue_id = ssr.issue_id and i.site_id = ssr.site_id", array("i.issue_id", "i.solved_date", "i.picture", "i.solved_picture", "i.solved"));
		$select->where('ssr.site_id = ?', $this->site_id);
		$select->where('(date(i.solved_date) = "'.$report_date.'" AND (date(ssr.completion_date) = "0000-00-00" or ssr.completion_date is NULL or date(ssr.completion_date) = "'.$report_date.'")) OR ((i.solved_date is null OR date(i.solved_date) = "0000-00-00")  AND (date(ssr.completion_date) = "0000-00-00" or ssr.completion_date is NULL or date(ssr.completion_date) = "'.$report_date.'"))');
		$select->where('ssr.mod_report_id is null or ssr.mod_report_id = 0');
		if(!empty($om_report_id)) $select->orWhere('ssr.om_report_id = ?', $om_report_id);
		$select->order("ssr.completion_date desc");
		$select->order("ssr.specific_report_id desc");
		$select->limit("50");
		$specific_reports = $specificReportTable->getAdapter()->fetchAll($select);
		return $specific_reports;
	}

	/* tampilin yg completion date-nya null atau yg completion datenya pas hari itu. 
	completion date field otomatis terisi dari solved date.  */
	function getUnsolvedIssues2($safety_report_id,$mod_report_id, $report_date = "0000-00-00") {
		$specificReportTable = new safety_specific_report(array('db'=>'db'));

		$select = $specificReportTable->select();
		$select->where('site_id = ?', $this->site_id);
		if(!empty($mod_report_id)) $select->where('completion_date is null or date(completion_date) = "0000-00-00" or date(completion_date) = "'.$report_date.'" or mod_report_id = '.$mod_report_id);
		else  $select->where('completion_date is null or date(completion_date) = "0000-00-00" or date(completion_date) = "'.$report_date.'"');
		$select->where('issue_id = 0 or issue_id is null');
		$select->where('safety_report_id = ?', $safety_report_id);
		$select->where('om_report_id is null or om_report_id=0');
		$select->order("completion_date desc");
		$select->order("specific_report_id desc");
		$specific_reports = $specificReportTable->getAdapter()->fetchAll($select);
		return $specific_reports;
	}

	/* tampilin semua data yg ada di safety specific report table yang completion date-nya kosong atau yg completion-date-nya hari itu */
	function getOMUnsolvedSpecificReports($om_report_id, $report_date)
	{
		$specificReportTable = new safety_specific_report(array('db'=>'db'));

		$select = $specificReportTable->select();
		$select->where('site_id = ?', $this->site_id);
		if(!empty($om_report_id)) $select->where('completion_date is null or date(completion_date) = "0000-00-00" or date(completion_date) = "'.$report_date.'" or om_report_id = '.$om_report_id);
		else  $select->where('completion_date is null or date(completion_date) = "0000-00-00" or date(completion_date) = "'.$report_date.'"');
		$select->where('issue_id = 0 or issue_id is null');
		$select->where('safety_report_id is not null or safety_report_id >0');
		$select->where('mod_report_id is null or mod_report_id=0');
		$select->order("completion_date desc");
		$select->order("specific_report_id desc");
		$specific_reports = $specificReportTable->getAdapter()->fetchAll($select);
		return $specific_reports;
	}

	/* tampilin semua data yg ada di safety specific report table yang completion-date-nya hari itu dan sesuai report id */
	function getOMSolvedSpecificReports($om_report_id, $report_date)
	{
		$specificReportTable = new safety_specific_report(array('db'=>'db'));

		$select = $specificReportTable->select();
		$select->where('site_id = ?', $this->site_id);
		$select->where('date(completion_date) = "'.$report_date.'" or om_report_id = '.$om_report_id);
		$select->where('issue_id = 0 or issue_id is null');
		$select->where('safety_report_id is not null or safety_report_id >0');
		$select->where('mod_report_id is null or mod_report_id=0');
		$select->order("completion_date desc");
		$select->order("specific_report_id desc");
		$specific_reports = $specificReportTable->getAdapter()->fetchAll($select);
		return $specific_reports;
	}

	/* tampilin semua data yg ada di safety specific report table yang completion-date-nya hari itu dan sesuai report id */
	function getMODSolvedSpecificReports($mod_report_id, $report_date)
	{
		$specificReportTable = new safety_specific_report(array('db'=>'db'));

		$select = $specificReportTable->select();
		$select->where('site_id = ?', $this->site_id);
		$select->where('date(completion_date) = "'.$report_date.'" or mod_report_id = '.$mod_report_id);
		$select->where('issue_id = 0 or issue_id is null');
		$select->where('safety_report_id is not null or safety_report_id >0');
		$select->where('om_report_id is null or om_report_id=0');
		$select->order("completion_date desc");
		$select->order("specific_report_id desc");
		$specific_reports = $specificReportTable->getAdapter()->fetchAll($select);
		return $specific_reports;
	}
	
	function getSpecificReportByReport($fieldName, $report_id, $report_date) {
		$specificReportTable = new safety_specific_report(array('db'=>'db'));
		
		$select = $specificReportTable->getAdapter()->select();
		$select->from(array("ssr"=>"safety_specific_report"), array("ssr.*"));
		$select->joinLeft(array("it"=>"issue_type"), "it.issue_type_id = ssr.issue_type", array("it.issue_type_id","it.issue_type as issue_type_name"));
		$select->joinLeft(array("i"=>"issues"), "i.issue_id = ssr.issue_id", array("i.solved_date", "i.picture", "i.solved_picture"));
		$select->where('ssr.site_id = ?', $this->site_id);
		$select->where("ssr.".$fieldName." ='".$report_id."' or date(ssr.completion_date) = '".$report_date."' or date(i.solved_date) = '".$report_date."'");
		if($fieldName == "om_report_id") $select->where("ssr.mod_report_id is null or ssr.mod_report_id = 0");
		else $select->where("ssr.om_report_id is null or ssr.om_report_id = 0");
		$select->order("ssr.completion_date desc");
		$specific_reports = $specificReportTable->getAdapter()->fetchAll($select);
		return $specific_reports;
	}
	
	/*** ATTACHMENT ***/
	
	function getAttachmentById($attachment_id)
	{
		$attachmentTable = new safety_attachments(array('db'=>'db'));
		$select = $attachmentTable->select()->where('attachment_id = ?', $attachment_id);
		$attachment = $attachmentTable->getAdapter()->fetchRow($select);
		return $attachment;
	}	
	
	function addAttachment($params) {
		$attachmentTable = new safety_attachments(array('db'=>'db'));
		$data = array(
			"report_id" => $params["report_id"],
			"site_id" => $this->site_id,
			"description" => $params["attachment_description"]
		);
		
		if(empty($params["attachment_id"])) 
		{
			$attachmentTable->insert($data);
			return $this->db->lastInsertId();
		}
		else {
			$where = $attachmentTable->getAdapter()->quoteInto('attachment_id = ?', $params["attachment_id"]);
			$attachmentTable->update($data, $where);
			return $params["attachment_id"];
		}	
	}
	
	function deleteAttachmentByReportId($report_id)
	{
		$attachmentTable = new safety_attachments(array('db'=>'db'));
		
		if ( is_numeric($report_id) && $report_id > 0 )
		{		
			$where = array();
			$where[] = $attachmentTable->getAdapter()->quoteInto('report_id = ?', $report_id);
			$attachmentTable->delete($where);
		}
	}
	
	function updateAttachment($id, $fieldname, $filename)
	{
		$attachmentTable = new safety_attachments(array('db'=>'db'));
		
		$data = array(
			$fieldname => $filename
		);
		$where = $attachmentTable->getAdapter()->quoteInto('attachment_id = ?', $id);
		$attachmentTable->update($data, $where);
	}
	
	function getAttachments($report_id) {
		$attachmentTable = new safety_attachments(array('db'=>'db'));
		$select = $attachmentTable->select()->where('report_id = ?', $report_id);
		$attachments = $attachmentTable->getAdapter()->fetchAll($select);
		return $attachments;
	}
	
	function deleteAttachmentById($id)
	{
		$attachmentTable = new safety_attachments(array('db'=>'db'));
		
		if ( is_numeric($id) && $id > 0 )
		{		
			$where = array();
			$where[] = $attachmentTable->getAdapter()->quoteInto('attachment_id = ?', $id);
			$attachmentTable->delete($where);
		}
	}

	function getSafetyAllSitesByDate($date) {
		$safetyReportTable = new safety_daily_report(array('db'=>'db'));
		
		$select = $safetyReportTable->getAdapter()->select();
		$select->from(array("s"=>"safety_daily_report"), array("s.report_id", "s.site_id"));
		$select->where('date(s.created_date)  = ?', $date);
		$safety = $safetyReportTable->getAdapter()->fetchAll($select);
		return $safety;
	}

	function addReadSafetyReportLog($params) {
		$safetyReadReportLogTable = new safety_read_report_log(array('db'=>'db'));
		
		$data = array(			
			"report_id" => $params["id"],
			"user_id" => $params["user_id"],
			"site_id" => $this->site_id,
			"read_datetime" => date("Y-m-d H:i:s")
		);
		$safetyReadReportLogTable->insert($data);		
	}

	function getReadReportLogByReportIdUser($report_id, $user_id) {
		$safetyReadReportLogTable = new safety_read_report_log(array('db'=>'db'));
		
		$select = $safetyReadReportLogTable->select()->where('report_id = ?', $report_id)->where('user_id = ?', $user_id);
		$report = $safetyReadReportLogTable->getAdapter()->fetchRow($select);
		return $report;	
	}

	function saveMonthlyAnalysis($params) {
		$monthlyAnalysisTable = new safety_monthly_analysis(array('db'=>'db'));
		
		$data = array(
			"site_id" => $this->site_id,
			"user_id" => $params["user_id"],
			"save_date" => date("Y-m-d H:i:s")
		);

		$monthlyAnalysisTable->insert($data);
		return $this->db->lastInsertId();
	}

	function getMonthlyAnalysis() {
		$monthlyAnalysisTable = new safety_monthly_analysis(array('db'=>'db'));
		$select = $monthlyAnalysisTable->getAdapter()->select();
		$select->from(array("m"=>"safety_monthly_analysis"), array("m.*"));
		$select->joinLeft(array("u"=>"users"), "u.user_id = m.user_id", array("u.name"));
		$select->where('m.site_id = ?', $this->site_id);
		$monthlyanalysis = $monthlyAnalysisTable->getAdapter()->fetchAll($select);
		return $monthlyanalysis;
	}

	function getTotalMonthlyAnalysis() {
		$monthlyAnalysisTable = new safety_monthly_analysis(array('db'=>'db'));

		$select = "select count(*) as total from safety_monthly_analysis where site_id =".$this->site_id;
		$monthlyAnalysis = $monthlyAnalysisTable->getAdapter()->fetchRow($select);
		return $monthlyAnalysis['total'];
	}

	function getMonthlyAnalysisById($monthly_analysis_id) {
		$monthlyAnalysisTable = new safety_monthly_analysis(array('db'=>'db'));
		$select = $monthlyAnalysisTable->select()->where('monthly_analysis_id = ?', $monthly_analysis_id);
		$monthlyanalysis = $monthlyAnalysisTable->getAdapter()->fetchRow($select);
		return $monthlyanalysis;
	}

	/*** Get Training or Safety Induction */

	function getTrainingSafetyInduction($month, $year) {
		$dailyReportTable = new safety_daily_report(array('db'=>'db'));
		
		$select = $dailyReportTable->getAdapter()->select();
		$select->from(array("st"=>"safety_training"), array("st.description", "st.participant", "st.remark", "st.document"));
		$select->joinLeft(array("sdr"=>"safety_daily_report"), "sdr.report_id = st.safety_report_id", array("sdr.created_date"));
		$select->joinLeft(array("sta"=>"safety_training_activity"), "sta.training_activity_id = st.training_activity_id", array("sta.activity"));
		$select->where('sdr.site_id = ?', $this->site_id);
		$select->where('month(sdr.created_date) = ?', $month);
		$select->where('year(sdr.created_date) = ?', $year);
		$select->order("sdr.created_date");
		$safety_induction = $dailyReportTable->getAdapter()->fetchAll($select);
		return $safety_induction;
	}

	/*** Get Potential Hazard */

	function getPotentialHazardSpecificReport($month, $year) {
		$specificReportTable = new safety_specific_report(array('db'=>'db'));
		$select = $specificReportTable->select()->where('site_id = ?', $this->site_id)->where('issue_type = ?', 9)->where('month(created_date) = ?', $month)->where('year(created_date) = ?', $year);
		$potential_hazard = $specificReportTable->getAdapter()->fetchAll($select);
		return $potential_hazard;
	}

	function getSafetyMonthlyAnalysisByMonthYear($m, $y) {
		$monthlyAnalysisTable = new safety_monthly_analysis(array('db'=>'db'));
		$select = $monthlyAnalysisTable->select()->where('site_id = ?', $this->site_id)->where('month(save_date) = ?', $m)->where('year(save_date) = ?', $y);
		$monthlyanalysis = $monthlyAnalysisTable->getAdapter()->fetchRow($select);
		return $monthlyanalysis;
	}

	/*function getMonthlyAnalysisById($monthly_analysis_id) {
		$monthlyAnalysisTable = new safety_monthly_analysis(array('db'=>'db'));
		$select = $monthlyAnalysisTable->select()->where('site_id = ?', $this->site_id)->where('monthly_analysis_id = ?', $monthly_analysis_id);
		$monthlyanalysis = $monthlyAnalysisTable->getAdapter()->fetchRow($select);
		return $monthlyanalysis;
	}*/

	function getLastMonthlyAnalysis($save_date) {
		$monthlyAnalysisTable = new safety_monthly_analysis(array('db'=>'db'));
		$select = $monthlyAnalysisTable->select()->where('site_id = ?', $this->site_id)->where('save_date < ?', $save_date)->order('save_date desc')->limit(1);
		$monthlyanalysis = $monthlyAnalysisTable->getAdapter()->fetchRow($select);
		return $monthlyanalysis;
	}


	/*** SAFETY BOARD ***/

	function saveSafetyBoard($params) {
		$safetyBoardTable = new safety_board(array('db'=>'db'));
		$data = array(
			"site_id" 		=> $this->site_id,
			"description" 	=> $params["description"],
			"enable" 		=> $params["enable"],
			"month" 		=> $params["month"],
			"year" 			=> $params["year"],
			"upload_date" 	=> date("Y-m-d H:i:s"),
			"user_id" 		=> $params["user_id"]
		);
		
		if(empty($params["safety_board_id"])) 
		{
			$safetyBoardTable->insert($data);
			return $this->db->lastInsertId();
		}
		else {
			$where = $safetyBoardTable->getAdapter()->quoteInto('safety_board_id = ?', $params["safety_board_id"]);
			$safetyBoardTable->update($data, $where);
			return $params["safety_board_id"];
		}	
	}

	function updateSafetyBoard($safety_board_id, $field, $value) {
		$safetyBoardTable = new safety_board(array('db'=>'db'));
		$data = array(
			$field 		=> $value
		);
		$where = $safetyBoardTable->getAdapter()->quoteInto('safety_board_id = ?', $safety_board_id);
		$safetyBoardTable->update($data, $where);
	}

	function getSafetyBoard($enable = 0, $year=0, $month=0)
	{
		$safetyBoardTable = new safety_board(array('db'=>'db'));
		$select = $safetyBoardTable->select(); /*->where('site_id = ?', $this->site_id);*/
		if(!empty($enable)) $select->where('enable = "1"');
		if(!empty($year)) $select->where('year = ?', $year);
		if(!empty($month)) $select->where('month = ?', $month);
		$safetyBoard = $safetyBoardTable->getAdapter()->fetchAll($select);
		return $safetyBoard;
	}	

	function getSafetyBoardById($id)
	{
		$safetyBoardTable = new safety_board(array('db'=>'db'));
		$select = $safetyBoardTable->select()->where('safety_board_id = ?', $id);
		$safetyBoard = $safetyBoardTable->getAdapter()->fetchRow($select);
		return $safetyBoard;
	}	

	function deleteSafetyBoardById($id)
	{
		$safetyBoardTable = new safety_board(array('db'=>'db'));
		
		if ( is_numeric($id) && $id > 0 )
		{		
			$where = $safetyBoardTable->getAdapter()->quoteInto('safety_board_id = ?', $id);
			$safetyBoardTable->delete($where);
		}
	}
}
?>