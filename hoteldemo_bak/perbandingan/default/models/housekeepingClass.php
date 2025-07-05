<?php

require_once('defaultClass.php');
require_once('dbClass.php');

class housekeepingClass extends defaultClass
{	
	function saveReport($params) {
		$housekeepingReportTable = new housekeeping_daily_report(array('db'=>'db'));
		
		$data = array(
			"site_id" => $this->site_id,
			"user_id" => $params["user_id"],
			"inhouse_chief_housekeeping_shift1" => $params["inhouse_chief_housekeeping_shift1"],
			"inhouse_chief_housekeeping_shift2" => $params["inhouse_chief_housekeeping_shift2"],
			"inhouse_chief_housekeeping_shift3" => $params["inhouse_chief_housekeeping_shift3"],
			"inhouse_supervisor_shift1" => $params["inhouse_supervisor_shift1"],
			"inhouse_supervisor_shift2" => $params["inhouse_supervisor_shift2"],
			"inhouse_supervisor_shift3" => $params["inhouse_supervisor_shift3"],
			"inhouse_staff_shift1" => $params["inhouse_staff_shift1"],
			"inhouse_staff_shift2" => $params["inhouse_staff_shift2"],
			"inhouse_staff_shift3" => $params["inhouse_staff_shift3"],
			"inhouse_admin_shift1" => $params["inhouse_admin_shift1"],
			"inhouse_admin_shift2" => $params["inhouse_admin_shift2"],
			"inhouse_admin_shift3" => $params["inhouse_admin_shift3"],
			"outsource_chief_housekeeping_shift1" => $params["outsource_chief_housekeeping_shift1"],
			"outsource_chief_housekeeping_shift2" => $params["outsource_chief_housekeeping_shift2"],
			"outsource_chief_housekeeping_shift3" => $params["outsource_chief_housekeeping_shift3"],
			"outsource_supervisor_shift1" => $params["outsource_supervisor_shift1"],
			"outsource_supervisor_shift2" => $params["outsource_supervisor_shift2"],
			"outsource_supervisor_shift3" => $params["outsource_supervisor_shift3"],
			"outsource_leader_shift1" => $params["outsource_leader_shift1"],
			"outsource_leader_shift2" => $params["outsource_leader_shift2"],
			"outsource_leader_shift3" => $params["outsource_leader_shift3"],
			"outsource_crew_shift1" => $params["outsource_crew_shift1"],
			"outsource_crew_shift2" => $params["outsource_crew_shift2"],
			"outsource_crew_shift3" => $params["outsource_crew_shift3"],
			"outsource_toilet_crew_shift1" => $params["outsource_toilet_crew_shift1"],
			"outsource_toilet_crew_shift2" => $params["outsource_toilet_crew_shift2"],
			"outsource_toilet_crew_shift3" => $params["outsource_toilet_crew_shift3"],
			"outsource_gondola_shift1" => $params["outsource_gondola_shift1"],
			"outsource_gondola_shift2" => $params["outsource_gondola_shift2"],
			"outsource_gondola_shift3" => $params["outsource_gondola_shift3"],
			"outsource_admin_shift1" => $params["outsource_admin_shift1"],
			"outsource_admin_shift2" => $params["outsource_admin_shift2"],
			"outsource_admin_shift3" => $params["outsource_admin_shift3"],
			"outsource_total_shift1" => $params["outsource_total_shift1"],
			"outsource_total_shift2" => $params["outsource_total_shift2"],
			"outsource_total_shift3" => $params["outsource_total_shift3"],
			"pest_control_koordinator_shift1" => $params["pest_control_koordinator_shift1"],
			"pest_control_koordinator_shift2" => $params["pest_control_koordinator_shift2"],
			"pest_control_koordinator_shift3" => $params["pest_control_koordinator_shift3"],
			"pest_control_leader_shift1" => $params["pest_control_leader_shift1"],
			"pest_control_leader_shift2" => $params["pest_control_leader_shift2"],
			"pest_control_leader_shift3" => $params["pest_control_leader_shift3"],
			"pest_control_crew_shift1" => $params["pest_control_crew_shift1"],
			"pest_control_crew_shift2" => $params["pest_control_crew_shift2"],
			"pest_control_crew_shift3" => $params["pest_control_crew_shift3"],
			"briefing1" => $params["briefing1"],
			"briefing2" => $params["briefing2"],
			"briefing3" => $params["briefing3"],
			"created_date" => date("Y-m-d H:i:s")
		);
		if(empty($params['housekeeping_report_id']))
		{
			$housekeepingReportTable->insert($data);
			return $this->db->lastInsertId();
		}
		else
		{
			unset($data['created_date']);
			unset($data['user_id']);
			$where = $housekeepingReportTable->getAdapter()->quoteInto('housekeeping_report_id = ?', $params['housekeeping_report_id']);
			$housekeepingReportTable->update($data, $where);
			return $params['housekeeping_report_id'];
		}
	}
	
	function getReports($params) {
		$housekeepingReportTable = new housekeeping_daily_report(array('db'=>'db'));
		$select = $housekeepingReportTable->getAdapter()->select();
		$select->from(array("h"=>"housekeeping_daily_report"), array("h.*"));
		$select->joinLeft(array("u"=>"users"), "u.user_id = h.user_id", array("u.name"));
		$select->where('h.site_id = ?', $this->site_id);
		$select->order('h.created_date desc');
		$select->limit($params['pagesize'],$params['start']);
		$housekeeping = $housekeepingReportTable->getAdapter()->fetchAll($select);
		return $housekeeping;
	}
	
	function getReportById($id) {
		$housekeepingReportTable = new housekeeping_daily_report(array('db'=>'db'));
		
		$select = $housekeepingReportTable->getAdapter()->select();
		$select->from(array("s"=>"housekeeping_daily_report"), array("s.*"));
		$select->joinLeft(array("u"=>"users"), "u.user_id = s.user_id", array("u.name"));
		$select->joinLeft(array("si"=>"sites"), "si.site_id = s.site_id", array("si.site_fullname"));
		$select->where('s.housekeeping_report_id = ?', $id);
		$housekeeping = $housekeepingReportTable->getAdapter()->fetchRow($select);

		return $housekeeping;
	}
	
	function deleteReportById($id)
	{
		$housekeepingReportTable = new housekeeping_daily_report(array('db'=>'db'));
		
		if ( is_numeric($id) && $id > 0 )
		{		
			$where = $housekeepingReportTable->getAdapter()->quoteInto('housekeeping_report_id = ?', $id);
			$housekeepingReportTable->delete($where);
		}
	}
	
	
	/*function addSpecificReport($params) {
		$specificReportTable = new housekeeping_specific_report(array('db'=>'db'));
		
		$data = array(
			"housekeeping_report_id" => intval($params["housekeeping_report_id"]),
			"issue_type" => intval($params["issue_type"]),
			"time" => $params["time"],
			"detail" => $params["detail"],
			"status" => $params["status"],
			"created_date" => date("Y-m-d H:i:s"),
			"issue_id" => intval($params["issue_id"]),
			"area" => $params["area"]
		);
		$specificReportTable->insert($data);
		return $this->db->lastInsertId();
	}
	
	function deleteSpecificReportByHousekeepingId($housekeeping_id)
	{
		$specificReportTable = new housekeeping_specific_report(array('db'=>'db'));
		
		if ( is_numeric($housekeeping_id) && $housekeeping_id > 0 )
		{		
			$where = $specificReportTable->getAdapter()->quoteInto('housekeeping_report_id = ?', $housekeeping_id);
			$specificReportTable->delete($where);
		}
	}
	
	function getSpecificReportByHousekeepingReport($housekeeping_report_id) {
		$specificReportTable = new housekeeping_specific_report(array('db'=>'db'));
		
		$select = $specificReportTable->getAdapter()->select();
		$select->from(array("ssr"=>"housekeeping_specific_report"), array("ssr.*"));
		$select->joinLeft(array("it"=>"issue_type"), "it.issue_type_id = ssr.issue_type", array("it.issue_type_id","it.issue_type as issue_type_name"));
		$select->where('ssr.housekeeping_report_id = ?', $housekeeping_report_id);
		$specific_reports = $specificReportTable->getAdapter()->fetchAll($select);
		return $specific_reports;
	}
	
	function getSpecificReportById($housekeeping_report_id) {
		$specificReportTable = new housekeeping_specific_report(array('db'=>'db'));
		
		$select = $specificReportTable->getAdapter()->select();
		$select->from(array("ssr"=>"housekeeping_specific_report"), array("ssr.*"));
		$select->joinLeft(array("it"=>"issue_type"), "it.issue_type_id = ssr.issue_type", array("it.issue_type_id","it.issue_type as issue_type_name"));
		$select->joinLeft(array("i"=>"issues"), "i.issue_id = ssr.issue_id", array("i.issue_date","i.description"));
		if($housekeeping_report_id > 0) $select->orwhere('ssr.housekeeping_report_id = ?', $housekeeping_report_id);
		$specific_reports = $specificReportTable->getAdapter()->fetchAll($select);
		return $specific_reports;
	}*/
	
	function getTotalReport() {
		$housekeepingReportTable = new housekeeping_daily_report(array('db'=>'db'));
		$select = "select count(*) as total from housekeeping_daily_report where site_id =".$this->site_id;
		$housekeepingReports = $housekeepingReportTable->getAdapter()->fetchRow($select);
		return $housekeepingReports;
	}
	
	function getReportByDate($date) {
		$housekeepingReportTable = new housekeeping_daily_report(array('db'=>'db'));
		
		$select = $housekeepingReportTable->select();
		$select->where('date(created_date) = ?', $date);
		$select->where('site_id = ?', $this->site_id);
		$housekeepingReports = $housekeepingReportTable->getAdapter()->fetchRow($select);
		
		return $housekeepingReports;
	}
	
	function updateProgressReportCompletionDate($id, $completion_date, $report_id, $fieldName) {
		$progressReportTable = new housekeeping_progress_report(array('db'=>'db'));
		
		$data = array(
			"completion_date" => $completion_date." 00:00:00",
			$fieldName => $report_id
		);

		$where = $progressReportTable->getAdapter()->quoteInto('progress_report_id = ?', $id);
		$progressReportTable->update($data, $where);
	}
	
	function updateOtherInfoCompletionDate($id, $completion_date, $report_id, $fieldName) {
		$otherInfoTable = new housekeeping_other_info(array('db'=>'db'));
		
		$data = array(
			"completion_date" => $completion_date." 00:00:00",
			$fieldName => $report_id
		);

		$where = $otherInfoTable->getAdapter()->quoteInto('other_info_id = ?', $id);
		$otherInfoTable->update($data, $where);
	}
	
	/*** ATTACHMENT ***/
	
	function getAttachmentById($attachment_id)
	{
		$attachmentTable = new housekeeping_attachments(array('db'=>'db'));
		$select = $attachmentTable->select()->where('attachment_id = ?', $attachment_id);
		$attachment = $attachmentTable->getAdapter()->fetchRow($select);
		return $attachment;
	}	
	
	function addAttachment($params) {
		$attachmentTable = new housekeeping_attachments(array('db'=>'db'));
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
		$attachmentTable = new housekeeping_attachments(array('db'=>'db'));
		
		if ( is_numeric($report_id) && $report_id > 0 )
		{		
			$where = array();
			$where[] = $attachmentTable->getAdapter()->quoteInto('report_id = ?', $report_id);
			$attachmentTable->delete($where);
		}
	}
	
	function updateAttachment($id, $fieldname, $filename)
	{
		$attachmentTable = new housekeeping_attachments(array('db'=>'db'));
		
		$data = array(
			$fieldname => $filename
		);
		$where = $attachmentTable->getAdapter()->quoteInto('attachment_id = ?', $id);
		$attachmentTable->update($data, $where);
	}
	
	function getAttachments($report_id) {
		$attachmentTable = new housekeeping_attachments(array('db'=>'db'));
		$select = $attachmentTable->select()->where('report_id = ?', $report_id);
		$attachments = $attachmentTable->getAdapter()->fetchAll($select);
		return $attachments;
	}
	
	function deleteAttachmentById($id)
	{
		$attachmentTable = new housekeeping_attachments(array('db'=>'db'));
		
		if ( is_numeric($id) && $id > 0 )
		{		
			$where = array();
			$where[] = $attachmentTable->getAdapter()->quoteInto('attachment_id = ?', $id);
			$attachmentTable->delete($where);
		}
	}

	function getHousekeepingAllSitesByDate($date) {
		$housekeepingReportTable = new housekeeping_daily_report(array('db'=>'db'));
		
		$select = $housekeepingReportTable->getAdapter()->select();
		$select->from(array("hk"=>"housekeeping_daily_report"), array("hk.housekeeping_report_id", "hk.site_id"));
		$select->where('date(hk.created_date)  = ?', $date);
		$hk = $housekeepingReportTable->getAdapter()->fetchAll($select);
		return $hk;
	}

	function addReadHousekeepingReportLog($params) {
		$HKReadReportLogTable = new housekeeping_read_report_log(array('db'=>'db'));
		
		$data = array(			
			"report_id" => $params["id"],
			"user_id" => $params["user_id"],
			"site_id" => $this->site_id,
			"read_datetime" => date("Y-m-d H:i:s")
		);
		$HKReadReportLogTable->insert($data);		
	}

	function getReadReportLogByReportIdUser($report_id, $user_id) {
		$HKReadReportLogTable = new housekeeping_read_report_log(array('db'=>'db'));
		
		$select = $HKReadReportLogTable->select()->where('report_id = ?', $report_id)->where('user_id = ?', $user_id);
		$report = $HKReadReportLogTable->getAdapter()->fetchRow($select);
		return $report;	
	}
}
?>